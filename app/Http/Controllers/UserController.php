<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Station;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['role', 'station']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                  ->orWhere('rank', 'LIKE', "%$search%")
                  ->orWhere('id', 'LIKE', "%$search%");
            });
        }

        $users = $query->latest()->paginate(15);
        
        // Stats for charts (loading only needed data)
        $roleStats = User::selectRaw('role_id, count(*) as count')->groupBy('role_id')->with('role')->get();
        $rankStats = User::selectRaw('rank, count(*) as count')->groupBy('rank')->get();

        return view('users.index', compact('users', 'roleStats', 'rankStats'));
    }

    public function create()
    {
        $roles = Role::all();
        $stations = Station::all();
        $ranks = \App\Constants\PoliceRanks::all();
        return view('users.create', compact('roles', 'stations', 'ranks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'station_id' => 'nullable|exists:stations,id',
            'region_id' => 'nullable|string|max:255',
            'rank' => 'nullable|string',
            'appointed_date' => 'nullable|date',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'station_id' => $request->station_id,
            'region_id' => $request->region_id,
            'status' => $request->status,
            'rank' => $request->rank,
            'appointed_date' => $request->appointed_date,
        ];

        if ($request->hasFile('profile_image')) {
            $userData['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        $user = User::create($userData);

        // Audit Log
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'CREATE',
            'table_name' => 'users',
            'record_id' => $user->id,
            'description' => 'Created new user: ' . $user->name,
        ]);

        return redirect()->route('users.index')->with('success', 'Isticmaalaha si guul leh ayaa loo abuuray.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $stations = Station::all();
        $ranks = \App\Constants\PoliceRanks::all();
        return view('users.edit', compact('user', 'roles', 'stations', 'ranks'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'station_id' => 'nullable|exists:stations,id',
            'region_id' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'rank' => 'nullable|string',
            'appointed_date' => 'nullable|date',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'station_id' => $request->station_id,
            'region_id' => $request->region_id,
            'status' => $request->status,
            'rank' => $request->rank,
            'appointed_date' => $request->appointed_date,
        ];

        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Audit Log
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE',
            'table_name' => 'users',
            'record_id' => $user->id,
            'description' => 'Updated user: ' . $user->name,
        ]);

        return redirect()->route('users.index')->with('success', 'Isticmaalaha si guul leh ayaa loo cusbooneysiiyay.');
    }

    public function destroy(User $user)
    {
        $userName = $user->name;
        
        // Audit Log before deletion
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'DELETE',
            'table_name' => 'users',
            'record_id' => $user->id,
            'description' => 'Deleted user: ' . $userName,
        ]);

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Isticmaalaha si guul leh ayaa loo tirtiray.');
    }

    public function show(User $user)
    {
        $user->load(['role', 'station', 'crimes', 'cases', 'deployments.station', 'deployments.facility']);
        $auditLogs = AuditLog::where('user_id', $user->id)
            ->orWhere(function($query) use ($user) {
                $query->where('table_name', 'users')
                      ->where('record_id', $user->id);
            })
            ->latest()
            ->take(20)
            ->get();
        
        return view('users.show', compact('user', 'auditLogs'));
    }
}
