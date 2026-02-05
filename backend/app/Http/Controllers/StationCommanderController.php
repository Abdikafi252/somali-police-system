<?php

namespace App\Http\Controllers;

use App\Models\StationCommander;
use App\Models\Station;
use App\Models\User;
use Illuminate\Http\Request;

class StationCommanderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        if (!in_array($user->role->slug, ['admin', 'taliye-gobol', 'taliye-qaran'])) {
            abort(403, 'Ma laguu oggola inay gasho qaybtan.');
        }

        $commanders = StationCommander::with(['user', 'station'])->latest()->paginate(10);
        return view('station_commanders.index', compact('commanders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        if (!in_array($user->role->slug, ['admin', 'taliye-gobol', 'taliye-qaran'])) {
            abort(403, 'Ma laguu oggola inay gasho qaybtan.');
        }

        // Users with role 'taliye-saldhig'
        $available_users = User::whereHas('role', function($q) {
            $q->where('slug', 'taliye-saldhig');
        })->get();

        $stations = Station::all();

        return view('station_commanders.create', compact('available_users', 'stations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'station_id' => 'required|exists:stations,id',
            'appointed_date' => 'required|date',
        ]);

        $commanderUser = User::findOrFail($request->user_id);

        $commander = StationCommander::create([
            'user_id' => $request->user_id,
            'station_id' => $request->station_id,
            'rank' => $commanderUser->rank, // Take rank from user profile
            'appointed_date' => $request->appointed_date,
            'status' => 'active',
        ]);

        \App\Services\AuditService::log('create', $commander, ['description' => "Assigned {$commanderUser->name} as commander for {$commander->station->station_name}"]);

        return redirect()->route('station-commanders.index')->with('success', 'Taliyaha saldhigga si guul leh ayaa loo diiwaangeliyay.');
    }

    /**
     * Display the specified resource.
     */
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = auth()->user();
        if (!in_array($user->role->slug, ['admin', 'taliye-gobol', 'taliye-qaran'])) {
            abort(403, 'Ma laguu oggola inay gasho qaybtan.');
        }

        $commander = StationCommander::with(['user', 'station'])->findOrFail($id);
        return view('station_commanders.show', compact('commander'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = auth()->user();
        if (!in_array($user->role->slug, ['admin', 'taliye-gobol', 'taliye-qaran'])) {
            abort(403, 'Ma laguu oggola inay gasho qaybtan.');
        }

        $commander = StationCommander::findOrFail($id);
        
        // Users with role 'taliye-saldhig'
        $available_users = User::whereHas('role', function($q) {
            $q->where('slug', 'taliye-saldhig');
        })->get();

        $stations = Station::all();

        return view('station_commanders.edit', compact('commander', 'available_users', 'stations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = auth()->user();
        if (!in_array($user->role->slug, ['admin', 'taliye-gobol', 'taliye-qaran'])) {
            abort(403, 'Ma laguu oggola inay gasho qaybtan.');
        }

        $commander = StationCommander::with('user')->findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'station_id' => 'required|exists:stations,id',
            'appointed_date' => 'required|date',
            'status' => 'required|in:active,inactive',
        ]);

        $commander->update([
            'user_id' => $request->user_id,
            'station_id' => $request->station_id,
            'rank' => $commander->user->rank, // Sync rank from user profile
            'appointed_date' => $request->appointed_date,
            'status' => $request->status,
        ]);

        \App\Services\AuditService::log('update', $commander, ['description' => "Updated commander details for {$commander->user->name}"]);

        return redirect()->route('station-commanders.index')->with('success', 'Xogta taliyaha si guul leh ayaa loo cusboonaysiiyay.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = auth()->user();
        if (!in_array($user->role->slug, ['admin', 'taliye-gobol', 'taliye-qaran'])) {
            abort(403, 'Ma laguu oggola inay gasho qaybtan.');
        }

        $commander = StationCommander::with(['user', 'station'])->findOrFail($id);
        
        \App\Services\AuditService::log('delete', $commander, ['description' => "Removed commander {$commander->user->name} from {$commander->station->station_name}"]);

        $commander->delete();

        return redirect()->route('station-commanders.index')->with('success', 'Taliyaha si guul leh ayaa looga saaray liiska.');
    }
}
