<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Services\AuditService;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Get all audit logs (admin only).
     */
    public function index(Request $request)
    {
        $query = AuditLog::query();

        if ($request->has('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->has('action')) {
            $query->where('action', $request->input('action'));
        }

        if ($request->has('endpoint')) {
            $query->where('endpoint', 'like', '%' . $request->input('endpoint') . '%');
        }

        if ($request->has('method')) {
            $query->where('method', $request->input('method'));
        }

        if ($request->has('from_date')) {
            $query->whereDate('created_at', '>=', $request->input('from_date'));
        }

        if ($request->has('to_date')) {
            $query->whereDate('created_at', '<=', $request->input('to_date'));
        }

        $logs = $query->orderByDesc('created_at')
            ->paginate($request->integer('per_page', 50));

        return response()->json($logs);
    }

    /**
     * Get audit logs for current user.
     */
    public function myLogs(Request $request)
    {
        $userId = auth()->id();

        $logs = AuditService::getUserAuditTrail(
            $userId,
            $request->integer('limit', 50)
        );

        return response()->json([
            'user_id' => $userId,
            'total' => $logs->count(),
            'logs' => $logs,
        ]);
    }

    /**
     * Get audit history for a specific model.
     */
    public function modelHistory(string $modelType, int $modelId)
    {
        $modelMap = [
            'user' => 'App\Models\User',
            'category' => 'App\Models\CourseCategory',
            'course' => 'App\Models\Course',
        ];

        $modelClass = $modelMap[$modelType] ?? null;

        if (!$modelClass) {
            return response()->json(['error' => 'Invalid model type'], 400);
        }

        $history = AuditService::getModelHistory($modelClass, $modelId);

        return response()->json([
            'model_type' => $modelType,
            'model_id' => $modelId,
            'total_changes' => $history->count(),
            'history' => $history,
        ]);
    }

    /**
     * Get authentication audit trail.
     */
    public function authLogs(Request $request)
    {
        $query = AuditLog::whereIn('action', ['login', 'logout', 'register']);

        if ($request->has('email')) {
            $query->where('user_email', 'like', '%' . $request->input('email') . '%');
        }

        if ($request->has('success')) {
            $success = $request->input('success') === 'true';
            $statusCodes = $success ? [200, 201] : [401, 422];
            $query->whereIn('status_code', $statusCodes);
        }

        $logs = $query->orderByDesc('created_at')
            ->paginate($request->integer('per_page', 50));

        return response()->json($logs);
    }

    /**
     * Get suspicious activity (failed logins, failed validations, etc).
     */
    public function suspiciousActivity(Request $request)
    {
        $hoursAgo = $request->integer('hours', 24);

        $logs = AuditLog::where('status_code', '>=', 400)
            ->where('created_at', '>=', now()->subHours($hoursAgo))
            ->orderByDesc('created_at')
            ->paginate($request->integer('per_page', 50));

        return response()->json($logs);
    }

    /**
     * Get audit summary/stats.
     */
    public function summary(Request $request)
    {
        $from = $request->has('from_date') ? $request->input('from_date') : now()->subDays(30);
        $to = $request->has('to_date') ? $request->input('to_date') : now();

        return response()->json([
            'period' => [
                'from' => $from,
                'to' => $to,
            ],
            'total_events' => AuditLog::whereBetween('created_at', [$from, $to])->count(),
            'by_action' => AuditLog::whereBetween('created_at', [$from, $to])
                ->selectRaw('action, count(*) as count')
                ->groupBy('action')
                ->pluck('count', 'action'),
            'by_method' => AuditLog::whereBetween('created_at', [$from, $to])
                ->selectRaw('method, count(*) as count')
                ->groupBy('method')
                ->pluck('count', 'method'),
            'unique_users' => AuditLog::whereBetween('created_at', [$from, $to])
                ->distinct('user_id')
                ->count('user_id'),
            'failed_events' => AuditLog::whereBetween('created_at', [$from, $to])
                ->where('status_code', '>=', 400)
                ->count(),
        ]);
    }
}
