<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    /**
     * Log an API action with request/response details.
     */
    public static function logAction(
        string $action,
        string $endpoint,
        string $method,
        ?string $description = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $auditableType = null,
        ?int $auditableId = null,
        ?int $statusCode = null,
        ?array $response = null,
        ?Request $request = null
    ): AuditLog {
        $user = Auth::user();
        $ipAddress = $request?->ip() ?? request()?->ip();
        $userAgent = $request?->userAgent() ?? request()?->userAgent();

        return AuditLog::create([
            'user_id' => $user?->id,
            'user_email' => $user?->email,
            'username' => $user?->name,
            'method' => $method,
            'endpoint' => $endpoint,
            'action' => $action,
            'description' => $description,
            'auditable_type' => $auditableType,
            'auditable_id' => $auditableId,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'status_code' => $statusCode,
            'response' => $response ? json_encode($response) : null,
        ]);
    }

    /**
     * Log authentication action.
     */
    public static function logAuthAction(
        string $action,
        string $email,
        ?string $username = null,
        bool $success = true,
        ?string $reason = null,
        ?Request $request = null
    ): AuditLog {
        return AuditLog::create([
            'user_email' => $email,
            'username' => $username,
            'method' => 'POST',
            'endpoint' => "auth/{$action}",
            'action' => $action,
            'description' => $success ? "User {$action} successful" : "User {$action} failed: {$reason}",
            'ip_address' => $request?->ip() ?? request()?->ip(),
            'user_agent' => $request?->userAgent() ?? request()?->userAgent(),
            'status_code' => $success ? 200 : 401,
        ]);
    }

    /**
     * Log database model changes.
     */
    public static function logModelChange(
        string $action,
        string $modelClass,
        int $modelId,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null,
        ?Request $request = null
    ): AuditLog {
        $user = Auth::user();

        return AuditLog::create([
            'user_id' => $user?->id,
            'user_email' => $user?->email,
            'username' => $user?->name,
            'method' => 'API',
            'action' => $action,
            'description' => $description ?? "{$action} on " . class_basename($modelClass),
            'auditable_type' => $modelClass,
            'auditable_id' => $modelId,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => $request?->ip() ?? request()?->ip(),
            'user_agent' => $request?->userAgent() ?? request()?->userAgent(),
        ]);
    }

    /**
     * Get audit logs with filtering.
     */
    public static function getLogs(
        ?int $userId = null,
        ?string $action = null,
        ?string $endpoint = null,
        int $limit = 100
    ) {
        $query = AuditLog::query();

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($action) {
            $query->where('action', $action);
        }

        if ($endpoint) {
            $query->where('endpoint', 'like', "%{$endpoint}%");
        }

        return $query->orderByDesc('created_at')->limit($limit)->get();
    }

    /**
     * Get user's audit trail.
     */
    public static function getUserAuditTrail(int $userId, int $limit = 50)
    {
        return AuditLog::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get action history for a specific model.
     */
    public static function getModelHistory(string $modelClass, int $modelId)
    {
        return AuditLog::where('auditable_type', $modelClass)
            ->where('auditable_id', $modelId)
            ->orderByDesc('created_at')
            ->get();
    }
}