<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(UserRequest $request)
    {
        $data = $request->validated();
        $query = User::with('role');

        if (!empty($data['q'])) {
            $q = $data['q'];
            $query->where(function ($qbuilder) use ($q) {
                $qbuilder->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        if (!empty($data['role_id'])) {
            $query->where('role_id', $data['role_id']);
        }

        $perPage = $data['size'] ?? 10;
        $users = $query->orderBy('created_at', 'desc')->paginate($perPage);
        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        if(!isset($data['role_id'])){
            $data['role_id'] = Role::where('name', 'User')->first()->id;
        }
        $user = User::create($data);
        $user->load('role');
        return new UserResource($user);
    }

    public function update(StoreUserRequest $request, User $user)
    {
        $data = $request->validated();
        $user->update($data);
        $user->load('role');
        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        if ($user->role->name === 'Admin') {
            return response()->json([
                'message' => 'Cannot delete admin user.'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully.'
        ]);
    }

    public function allAgents()
    {
        $query = User::whereHas('role', function ($q) {
            $q->where('name', 'Support Agent');
        });
        $agents = $query->get(['id', 'name', 'email']);
        return response()->json(['data' => $agents], 200);
    }
}
