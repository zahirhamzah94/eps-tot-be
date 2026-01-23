<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{
    protected string $disk = 'local';
    protected string $basePath = 'uploads';

    /**
     * Store an uploaded file.
     */
    public function storeFile(
        UploadedFile $uploadedFile,
        ?int $userId = null,
        ?string $category = null,
        ?string $description = null,
        bool $isPublic = false,
        ?string $expiredAt = null,
        ?array $metadata = null
    ): File {
        $originalName = $uploadedFile->getClientOriginalName();
        $mimeType = $uploadedFile->getMimeType();
        $size = $uploadedFile->getSize();

        // Generate unique filename
        $extension = $uploadedFile->getClientOriginalExtension();
        $filename = Str::uuid() . '.' . $extension;

        // Create category-based path
        $categoryPath = $category ? $category . '/' : '';
        $datePath = now()->format('Y/m/d');
        $path = "{$categoryPath}{$datePath}/{$filename}";

        // Store file
        Storage::disk($this->disk)->putFileAs(
            "{$this->basePath}/{$categoryPath}{$datePath}",
            $uploadedFile,
            $filename
        );

        // Generate hash for duplicate detection
        $hash = hash_file('sha256', $uploadedFile->getRealPath());

        // Create database record
        $file = File::create([
            'filename' => $filename,
            'original_filename' => $originalName,
            'mime_type' => $mimeType,
            'size' => $size,
            'path' => "{$this->basePath}/{$path}",
            'disk' => $this->disk,
            'hash' => $hash,
            'user_id' => $userId,
            'category' => $category,
            'description' => $description,
            'is_public' => $isPublic,
            'expires_at' => $expiredAt,
            'metadata' => $metadata,
        ]);

        return $file;
    }

    /**
     * Get file by ID with access control.
     */
    public function getFile(int $fileId, ?int $userId = null): ?File
    {
        $file = File::find($fileId);

        if (!$file) {
            return null;
        }

        // Check if file is expired
        if ($file->isExpired()) {
            return null;
        }

        // Check access permissions
        if (!$file->canAccess(auth()->user())) {
            return null;
        }

        return $file;
    }

    /**
     * Download file.
     */
    public function downloadFile(File $file)
    {
        // Check if file exists
        if (!Storage::disk($this->disk)->exists($file->path)) {
            return null;
        }

        // Increment download count
        $file->incrementDownloadCount();

        return Storage::disk($this->disk)->download(
            $file->path,
            $file->original_filename
        );
    }

    /**
     * Get file contents.
     */
    public function getFileContents(File $file): ?string
    {
        if (!Storage::disk($this->disk)->exists($file->path)) {
            return null;
        }

        return Storage::disk($this->disk)->get($file->path);
    }

    /**
     * Delete file.
     */
    public function deleteFile(File $file): bool
    {
        // Delete from storage
        if (Storage::disk($this->disk)->exists($file->path)) {
            Storage::disk($this->disk)->delete($file->path);
        }

        // Soft delete from database
        return $file->delete();
    }

    /**
     * Permanently delete file (force delete).
     */
    public function forceDeleteFile(File $file): bool
    {
        // Delete from storage
        if (Storage::disk($this->disk)->exists($file->path)) {
            Storage::disk($this->disk)->delete($file->path);
        }

        // Hard delete from database
        return $file->forceDelete();
    }

    /**
     * Restore soft-deleted file.
     */
    public function restoreFile(File $file): bool
    {
        return $file->restore();
    }

    /**
     * Update file metadata.
     */
    public function updateFile(File $file, array $data): File
    {
        $file->update([
            'category' => $data['category'] ?? $file->category,
            'description' => $data['description'] ?? $file->description,
            'is_public' => $data['is_public'] ?? $file->is_public,
            'expires_at' => $data['expires_at'] ?? $file->expires_at,
            'metadata' => $data['metadata'] ?? $file->metadata,
        ]);

        return $file;
    }

    /**
     * Check for duplicate files by hash.
     */
    public function findDuplicate(string $hash): ?File
    {
        return File::where('hash', $hash)->first();
    }

    /**
     * Get files by category.
     */
    public function getFilesByCategory(string $category, int $limit = 50)
    {
        return File::where('category', $category)
            ->whereNull('deleted_at')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get user's files.
     */
    public function getUserFiles(int $userId, int $limit = 50)
    {
        return File::where('user_id', $userId)
            ->whereNull('deleted_at')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Clean up expired files.
     */
    public function cleanupExpiredFiles(): int
    {
        $expiredFiles = File::where('expires_at', '<', now())
            ->whereNull('deleted_at')
            ->get();

        $count = 0;
        foreach ($expiredFiles as $file) {
            $this->deleteFile($file);
            $count++;
        }

        return $count;
    }
}
