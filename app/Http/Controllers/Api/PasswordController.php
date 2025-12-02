<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'oldPassword' => ['required'],
            'newPassword' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();

        if(!Hash::check($validated['oldPassword'], $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 422);
        }

        $user->password = Hash::make($validated['newPassword']);
        $user->save();

        return response()->json(['message' => 'Password updated successfully'], 200);
    }
}
