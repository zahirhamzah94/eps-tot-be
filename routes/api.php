<?php

use App\Http\Controllers\Api\CourseCategoryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KeycloakController;
use App\Http\Controllers\Api\AuditLogController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\ExportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/register', [AuthController::class, 'register']);

// Keycloak OAuth2/OIDC endpoints (public)
Route::get('keycloak/login', [KeycloakController::class, 'loginRedirect']);
Route::post('keycloak/callback', [KeycloakController::class, 'callback']);
Route::post('keycloak/refresh', [KeycloakController::class, 'refresh']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('auth/logout', [AuthController::class, 'logout']);

    // Audit log endpoints
    Route::get('audit-logs/my-logs', [AuditLogController::class, 'myLogs'])->middleware('permission:audit-logs.view-own');
    Route::get('audit-logs/auth', [AuditLogController::class, 'authLogs'])->middleware('permission:audit-logs.view');
    Route::get('audit-logs/suspicious', [AuditLogController::class, 'suspiciousActivity'])->middleware('permission:audit-logs.view');
    Route::get('audit-logs/summary', [AuditLogController::class, 'summary'])->middleware('permission:audit-logs.view');
    Route::get('audit-logs/history/{modelType}/{modelId}', [AuditLogController::class, 'modelHistory'])->middleware('permission:audit-logs.view');
    Route::get('audit-logs', [AuditLogController::class, 'index'])->middleware('permission:audit-logs.view');

    Route::get('course-categories', [CourseCategoryController::class, 'index'])->middleware('permission:course-categories.view');
    Route::post('course-categories', [CourseCategoryController::class, 'store'])->middleware('permission:course-categories.create');
    Route::get('course-categories/{courseCategory}', [CourseCategoryController::class, 'show'])->middleware('permission:course-categories.view');
    Route::put('course-categories/{courseCategory}', [CourseCategoryController::class, 'update'])->middleware('permission:course-categories.update');
    Route::delete('course-categories/{courseCategory}', [CourseCategoryController::class, 'destroy'])->middleware('permission:course-categories.delete');

    Route::get('users', [UserController::class, 'index'])->middleware('permission:users.view');
    Route::post('users', [UserController::class, 'store'])->middleware('permission:users.create');
    Route::get('users/{user}', [UserController::class, 'show'])->middleware('permission:users.view');
    Route::put('users/{user}', [UserController::class, 'update'])->middleware('permission:users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->middleware('permission:users.delete');

    // File management endpoints
    Route::post('files/upload', [FileController::class, 'upload'])->middleware('permission:files.upload');
    Route::get('files', [FileController::class, 'index'])->middleware('permission:files.view');
    Route::get('files/my-files', [FileController::class, 'myFiles']);
    Route::get('files/category/{category}', [FileController::class, 'byCategory'])->middleware('permission:files.view');
    Route::get('files/{fileId}', [FileController::class, 'show'])->middleware('permission:files.view');
    Route::get('files/{fileId}/download', [FileController::class, 'download'])->middleware('permission:files.download');
    Route::get('files/{fileId}/preview', [FileController::class, 'preview'])->middleware('permission:files.view');
    Route::put('files/{fileId}', [FileController::class, 'update'])->middleware('permission:files.update');
    Route::delete('files/{fileId}', [FileController::class, 'destroy'])->middleware('permission:files.delete');

    // Export endpoints
    Route::get('exports/users/excel', [ExportController::class, 'usersExcel'])->middleware('permission:reports.export');
    Route::get('exports/users/pdf', [ExportController::class, 'usersPdf'])->middleware('permission:reports.export');
    Route::get('exports/users/preview', [ExportController::class, 'usersPreview'])->middleware('permission:reports.export');
    Route::get('exports/audit-logs/excel', [ExportController::class, 'auditLogsExcel'])->middleware('permission:reports.export');
    Route::get('exports/audit-logs/pdf', [ExportController::class, 'auditLogsPdf'])->middleware('permission:reports.export');
    Route::get('exports/audit-logs/preview', [ExportController::class, 'auditLogsPreview'])->middleware('permission:reports.export');
});

// Keycloak protected endpoints
Route::middleware('keycloak')->group(function () {
    Route::get('keycloak/user-info', [KeycloakController::class, 'userInfo']);
    Route::post('keycloak/logout', [KeycloakController::class, 'logout']);
});
