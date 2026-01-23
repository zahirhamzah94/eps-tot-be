<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $allowedSorts = ['name', 'email', 'created_at'];
        $sort = in_array($request->get('sort'), $allowedSorts, true)
            ? $request->get('sort')
            : 'name';
        $direction = $request->get('direction') === 'desc' ? 'desc' : 'asc';

        $users = User::with('roles')
            ->when($request->query('search'), function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy($sort, $direction)
            ->paginate($request->integer('per_page', 15));

        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $roles = $data['roles'] ?? [];
        unset($data['roles']);

        $user = User::create($data);

        if (!empty($roles)) {
            $user->syncRoles($roles);
        }

        AuditService::logModelChange(
            action: 'create',
            modelClass: User::class,
            modelId: $user->id,
            newValues: $user->toArray(),
            description: "User created: {$user->email}",
            request: $request
        );

        return new UserResource($user->load('roles'));
    }

    public function show(User $user)
    {
        return new UserResource($user->load('roles'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $oldData = $user->toArray();

        $data = $request->validated();
        $roles = $data['roles'] ?? null;
        unset($data['roles']);

        if (!array_key_exists('password', $data) || $data['password'] === null) {
            unset($data['password']);
        }

        $user->update($data);

        if ($roles !== null) {
            $user->syncRoles($roles);
        }

        AuditService::logModelChange(
            action: 'update',
            modelClass: User::class,
            modelId: $user->id,
            oldValues: $oldData,
            newValues: $user->fresh()->toArray(),
            description: "User updated: {$user->email}",
            request: $request
        );

        return new UserResource($user->load('roles'));
    }

    public function destroy(User $user)
    {
        if (auth()->check() && auth()->id() === $user->id) {
            return response()->json([
                'message' => 'You cannot delete your own account.',
            ], 422);
        }

        $userData = $user->toArray();
        $user->delete();

        AuditService::logModelChange(
            action: 'delete',
            modelClass: User::class,
            modelId: $user->id,
            oldValues: $userData,
            description: "User deleted: {$user->email}",
            request: request()
        );

        return response()->noContent();
    }
}
