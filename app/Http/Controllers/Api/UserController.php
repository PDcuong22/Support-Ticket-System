<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        $me = $request->user();
        $roleQuery = $request->query('role');

        // determine caller roles (supports roles(), role(), or role column)
        if (method_exists($me, 'roles')) {
            $me->loadMissing('roles');
            $callerRoles = $me->roles->pluck('name')->map(fn($n) => strtolower($n))->all();
        } elseif (method_exists($me, 'role')) {
            $me->loadMissing('role');
            $callerRoles = [strtolower(optional($me->role)->name ?? '')];
        } else {
            $callerRoles = [strtolower($me->role ?? '')];
        }

        $isAdmin = in_array('admin', $callerRoles, true);
        $isAgent = in_array('agent', $callerRoles, true) || in_array('support agent', $callerRoles, true) || in_array('support', $callerRoles, true);

        $query = User::query();

        if ($isAgent) {
            // agent sees only support agents
            $targetRole = 'support agent';
            if (method_exists(User::class, 'roles')) {
                $query->whereHas('roles', function ($q) use ($targetRole) {
                    $q->where('name', $targetRole);
                });
            } elseif (method_exists(User::class, 'role')) {
                $query->whereHas('role', function ($q) use ($targetRole) {
                    $q->where('name', $targetRole);
                });
            } else {
                $query->where('role', $targetRole);
            }
        } else {
            // admin: optional role filter
            if ($roleQuery) {
                if (method_exists(User::class, 'roles')) {
                    $query->whereHas('roles', function ($q) use ($roleQuery) {
                        $q->where('name', $roleQuery);
                    });
                } elseif (method_exists(User::class, 'role')) {
                    $query->whereHas('role', function ($q) use ($roleQuery) {
                        $q->where('name', $roleQuery);
                    });
                } else {
                    $query->where('role', $roleQuery);
                }
            }
        }

        $users = $query->get(['id', 'name', 'email']);
        return response()->json(['data' => $users], 200);
    }
}
