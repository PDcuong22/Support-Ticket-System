<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Services\RoleService;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userService;
    protected $roleService;

    public function __construct(UserService $userService, RoleService $roleService) 
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->userService->getAllUsersWithRelations(['role']);
        $roles = $this->roleService->getAllRoles();
        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

    /**
     * Update only the role of a user (called from index form).
     */
    public function updateRole(Request $request, User $user)
    {
        $data = $request->validate([
            'role_id' => 'nullable|exists:roles,id',
        ]);

        if (Auth::check() && Auth::id() === $user->id && ($data['role_id'] ?? null) !== $user->role_id) {
            return redirect()->back()->with('error', 'Don\'t change your own role.');
        }

        $user->role_id = $data['role_id'] ?? null;
        $saved = $user->save();

        if ($saved) {
            return redirect()->back()->with('success', 'Role updated successfully.');
        }

        return redirect()->back()->with('error', 'Unable to update role, please try again later.');
    }
}
