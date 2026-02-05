<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\AuditLog;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user()->load(['role', 'station']);
        
        $auditLogs = AuditLog::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('profile.show', compact('user', 'auditLogs'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone' => 'nullable|string|max:20', // Assuming phone field might exist or be added, otherwise can remove
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        $user->update($data);

        // Audit Log
        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'UPDATE',
            'table_name' => 'users',
            'record_id' => $user->id,
            'description' => 'Updated own profile details',
        ]);

        return redirect()->route('profile.show')->with('success', 'Xogtaada si guul leh ayaa loo cusbooneysiiyay.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password-ka hadda jira waa qalad.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        // Audit Log
        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'UPDATE',
            'table_name' => 'users',
            'record_id' => $user->id,
            'description' => 'Changed password',
        ]);

        return redirect()->route('profile.show')->with('success', 'Password-kaaga si guul leh ayaa loo bedelay.');
    }
}
