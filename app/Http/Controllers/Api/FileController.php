<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFileRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use App\Services\AuditService;
use App\Services\FileService;
use Illuminate\Http\Request;

class FileController extends Controller
{
    protected FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * Upload a file.
     */
    public function upload(StoreFileRequest $request)
    {
        $file = $request->file('file');

        // Check for duplicate
        $hash = hash_file('sha256', $file->getRealPath());
        $existing = $this->fileService->findDuplicate($hash);

        if ($existing) {
            AuditService::logAction(
                action: 'upload_duplicate',
                endpoint: 'files/upload',
                method: 'POST',
                description: "File upload (duplicate detected): {$file->getClientOriginalName()}",
                statusCode: 200,
                auditableType: File::class,
                auditableId: $existing->id,
                request: $request
            );

            return new FileResource($existing);
        }

        // Store file
        $storedFile = $this->fileService->storeFile(
            $file,
            userId: auth()->id(),
            category: $request->input('category'),
            description: $request->input('description'),
            isPublic: (bool) $request->input('is_public', false),
            expiredAt: $request->input('expires_at'),
            metadata: $request->input('metadata') ? json_decode($request->input('metadata'), true) : null
        );

        AuditService::logAction(
            action: 'upload',
            endpoint: 'files/upload',
            method: 'POST',
            description: "File uploaded: {$storedFile->original_filename}",
            statusCode: 201,
            auditableType: File::class,
            auditableId: $storedFile->id,
            newValues: [
                'filename' => $storedFile->filename,
                'size' => $storedFile->size,
                'category' => $storedFile->category,
            ],
            request: $request
        );

        return response()->json(new FileResource($storedFile), 201);
    }

    /**
     * List files with pagination and filters.
     */
    public function index(Request $request)
    {
        $query = File::query();

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->input('category'));
        }

        // Filter by user
        if ($request->has('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        // Filter public files or own files
        if (!auth()->user()?->hasPermissionTo('files.view-all')) {
            $query->where(function ($q) {
                $q->where('is_public', true)
                    ->orWhere('user_id', auth()->id());
            });
        }

        // Exclude expired files
        $query->where(function ($q) {
            $q->whereNull('expires_at')
                ->orWhere('expires_at', '>', now());
        });

        $files = $query->with('user')
            ->orderByDesc('created_at')
            ->paginate($request->integer('per_page', 20));

        return FileResource::collection($files);
    }

    /**
     * Get file details.
     */
    public function show(int $fileId, Request $request)
    {
        $file = $this->fileService->getFile($fileId, auth()->id());

        if (!$file) {
            return response()->json(['error' => 'File not found or access denied'], 404);
        }

        AuditService::logAction(
            action: 'view',
            endpoint: "files/{$fileId}",
            method: 'GET',
            description: "File viewed: {$file->original_filename}",
            statusCode: 200,
            auditableType: File::class,
            auditableId: $file->id,
            request: $request
        );

        return new FileResource($file);
    }

    /**
     * Download file.
     */
    public function download(int $fileId, Request $request)
    {
        $file = $this->fileService->getFile($fileId, auth()->id());

        if (!$file) {
            return response()->json(['error' => 'File not found or access denied'], 404);
        }

        AuditService::logAction(
            action: 'download',
            endpoint: "files/{$fileId}/download",
            method: 'GET',
            description: "File downloaded: {$file->original_filename}",
            statusCode: 200,
            auditableType: File::class,
            auditableId: $file->id,
            request: $request
        );

        return $this->fileService->downloadFile($file);
    }

    /**
     * Get file content (inline preview).
     */
    public function preview(int $fileId, Request $request)
    {
        $file = $this->fileService->getFile($fileId, auth()->id());

        if (!$file) {
            return response()->json(['error' => 'File not found or access denied'], 404);
        }

        $contents = $this->fileService->getFileContents($file);

        if (!$contents) {
            return response()->json(['error' => 'Failed to read file'], 500);
        }

        AuditService::logAction(
            action: 'preview',
            endpoint: "files/{$fileId}/preview",
            method: 'GET',
            description: "File previewed: {$file->original_filename}",
            statusCode: 200,
            auditableType: File::class,
            auditableId: $file->id,
            request: $request
        );

        return response($contents, 200)
            ->header('Content-Type', $file->mime_type)
            ->header('Content-Disposition', "inline; filename={$file->original_filename}");
    }

    /**
     * Update file metadata.
     */
    public function update(int $fileId, Request $request)
    {
        $file = File::findOrFail($fileId);

        // Check authorization
        if ($file->user_id !== auth()->id() && !auth()->user()?->hasPermissionTo('files.update-all')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $oldData = $file->toArray();

        $data = $request->validate([
            'category' => 'sometimes|string|max:100',
            'description' => 'sometimes|string|max:1000',
            'is_public' => 'sometimes|boolean',
            'expires_at' => 'sometimes|nullable|date|after:now',
        ]);

        $file = $this->fileService->updateFile($file, $data);

        AuditService::logAction(
            action: 'update',
            endpoint: "files/{$fileId}",
            method: 'PUT',
            description: "File metadata updated: {$file->original_filename}",
            statusCode: 200,
            auditableType: File::class,
            auditableId: $file->id,
            oldValues: $oldData,
            newValues: $file->toArray(),
            request: $request
        );

        return new FileResource($file);
    }

    /**
     * Delete file.
     */
    public function destroy(int $fileId, Request $request)
    {
        $file = File::findOrFail($fileId);

        // Check authorization
        if ($file->user_id !== auth()->id() && !auth()->user()?->hasPermissionTo('files.delete-all')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $fileName = $file->original_filename;
        $this->fileService->deleteFile($file);

        AuditService::logAction(
            action: 'delete',
            endpoint: "files/{$fileId}",
            method: 'DELETE',
            description: "File deleted: {$fileName}",
            statusCode: 200,
            auditableType: File::class,
            auditableId: $fileId,
            oldValues: $file->toArray(),
            request: $request
        );

        return response()->noContent();
    }

    /**
     * Get files by category.
     */
    public function byCategory(string $category, Request $request)
    {
        $files = $this->fileService->getFilesByCategory(
            $category,
            $request->integer('limit', 50)
        );

        return FileResource::collection($files);
    }

    /**
     * Get my uploaded files.
     */
    public function myFiles(Request $request)
    {
        $files = $this->fileService->getUserFiles(
            auth()->id(),
            $request->integer('limit', 50)
        );

        return FileResource::collection($files);
    }
}
