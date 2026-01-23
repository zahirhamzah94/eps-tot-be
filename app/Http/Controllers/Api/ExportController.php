<?php

namespace App\Http\Controllers\Api;

use App\Exports\UsersExport;
use App\Exports\AuditLogsExport;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use App\Services\AuditService;
use App\Services\PdfExportService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    protected PdfExportService $pdfService;

    public function __construct(PdfExportService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    /**
     * Export users to Excel.
     */
    public function usersExcel(Request $request)
    {
        AuditService::logAction(
            action: 'export',
            endpoint: 'exports/users/excel',
            method: 'GET',
            description: 'Exported users to Excel',
            statusCode: 200,
            request: $request
        );

        return Excel::download(
            new UsersExport(),
            'users_' . now()->format('Y-m-d_His') . '.xlsx'
        );
    }

    /**
     * Export users to PDF.
     */
    public function usersPdf(Request $request)
    {
        $users = User::with('roles')->get();

        $pdf = $this->pdfService->generateUsersPdf(
            $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->roles->pluck('name')->join(', '),
                    'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                ];
            })->toArray()
        );

        AuditService::logAction(
            action: 'export',
            endpoint: 'exports/users/pdf',
            method: 'GET',
            description: 'Exported users to PDF',
            statusCode: 200,
            request: $request
        );

        return $this->pdfService->download(
            $pdf,
            'users_' . now()->format('Y-m-d_His') . '.pdf'
        );
    }

    /**
     * Export audit logs to Excel.
     */
    public function auditLogsExcel(Request $request)
    {
        $filters = [
            'action' => $request->input('action'),
            'method' => $request->input('method'),
            'user_id' => $request->input('user_id'),
        ];

        AuditService::logAction(
            action: 'export',
            endpoint: 'exports/audit-logs/excel',
            method: 'GET',
            description: 'Exported audit logs to Excel',
            statusCode: 200,
            request: $request
        );

        return Excel::download(
            new AuditLogsExport(array_filter($filters)),
            'audit_logs_' . now()->format('Y-m-d_His') . '.xlsx'
        );
    }

    /**
     * Export audit logs to PDF.
     */
    public function auditLogsPdf(Request $request)
    {
        $query = AuditLog::query();

        if ($request->has('action')) {
            $query->where('action', $request->input('action'));
        }

        if ($request->has('method')) {
            $query->where('method', $request->input('method'));
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        $logs = $query->orderByDesc('created_at')
            ->limit(500)
            ->get();

        $filters = [
            'action' => $request->input('action'),
            'method' => $request->input('method'),
            'user_id' => $request->input('user_id'),
        ];

        $pdf = $this->pdfService->generateAuditLogsPdf(
            $logs->toArray(),
            array_filter($filters)
        );

        AuditService::logAction(
            action: 'export',
            endpoint: 'exports/audit-logs/pdf',
            method: 'GET',
            description: 'Exported audit logs to PDF',
            statusCode: 200,
            request: $request
        );

        return $this->pdfService->download(
            $pdf,
            'audit_logs_' . now()->format('Y-m-d_His') . '.pdf'
        );
    }

    /**
     * Preview users PDF in browser.
     */
    public function usersPreview(Request $request)
    {
        $users = User::with('roles')->get();

        $pdf = $this->pdfService->generateUsersPdf(
            $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->roles->pluck('name')->join(', '),
                    'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                ];
            })->toArray()
        );

        return $this->pdfService->stream($pdf);
    }

    /**
     * Preview audit logs PDF in browser.
     */
    public function auditLogsPreview(Request $request)
    {
        $query = AuditLog::query();

        if ($request->has('action')) {
            $query->where('action', $request->input('action'));
        }

        if ($request->has('method')) {
            $query->where('method', $request->input('method'));
        }

        $logs = $query->orderByDesc('created_at')
            ->limit(500)
            ->get();

        $filters = [
            'action' => $request->input('action'),
            'method' => $request->input('method'),
        ];

        $pdf = $this->pdfService->generateAuditLogsPdf(
            $logs->toArray(),
            array_filter($filters)
        );

        return $this->pdfService->stream($pdf);
    }
}
