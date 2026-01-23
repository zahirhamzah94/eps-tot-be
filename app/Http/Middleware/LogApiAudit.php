<?php

namespace App\Http\Middleware;

use App\Services\AuditService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogApiAudit
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Exclude sensitive endpoints and health checks
        if ($this->shouldSkip($request)) {
            return $response;
        }

        // Log the API call
        try {
            AuditService::logAction(
                action: $this->getActionFromRoute($request),
                endpoint: $request->path(),
                method: $request->method(),
                description: "{$request->method()} {$request->path()}",
                statusCode: $response->getStatusCode(),
                auditableType: $this->extractAuditableType($request),
                auditableId: $this->extractAuditableId($request),
                request: $request
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Audit logging failed: ' . $e->getMessage());
        }

        return $response;
    }

    /**
     * Determine if the request should be skipped from audit logging.
     */
    private function shouldSkip(Request $request): bool
    {
        $skipPaths = [
            '/up',
            '/health',
            'api/user', // Basic user info endpoint
        ];

        foreach ($skipPaths as $skip) {
            if ($request->is($skip)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Extract action from the route.
     */
    private function getActionFromRoute(Request $request): string
    {
        $method = $request->method();
        $path = $request->path();

        // Map HTTP methods to CRUD actions
        $actionMap = [
            'GET' => 'view',
            'POST' => 'create',
            'PUT' => 'update',
            'PATCH' => 'update',
            'DELETE' => 'delete',
        ];

        return $actionMap[$method] ?? 'unknown';
    }

    /**
     * Extract auditable type from route.
     */
    private function extractAuditableType(Request $request): ?string
    {
        $path = $request->path();

        // Extract the resource name from path (e.g., users, course-categories)
        $segments = explode('/', trim($path, '/'));
        if (count($segments) >= 2) {
            $resource = $segments[1];

            // Map common resource names to model names
            $modelMap = [
                'users' => 'App\Models\User',
                'course-categories' => 'App\Models\CourseCategory',
                'courses' => 'App\Models\Course',
            ];

            return $modelMap[$resource] ?? null;
        }

        return null;
    }

    /**
     * Extract auditable ID from route parameters.
     */
    private function extractAuditableId(Request $request): ?int
    {
        $route = $request->route();

        if (!$route) {
            return null;
        }

        // Try to get ID from route parameters
        foreach (['id', 'user', 'course', 'courseCategory', 'category'] as $param) {
            $value = $route->parameter($param);
            if ($value) {
                // If it's a model instance, get its ID
                if (is_object($value) && method_exists($value, 'getKey')) {
                    return $value->getKey();
                }
                // If it's already an ID
                if (is_numeric($value)) {
                    return (int) $value;
                }
            }
        }

        return null;
    }
}
