<?php

namespace App\Http\Controllers;

use App\Models\StationOfficer;
use Illuminate\Http\Request;

class StationOfficerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $query = StationOfficer::with(['user', 'station', 'commander']);

        // Check if user is a station commander
        if ($user->role->slug === 'taliye-saldhig') {
            $commanderRecord = \App\Models\StationCommander::where('user_id', $user->id)->first();
            if ($commanderRecord) {
                $query->where('station_id', $commanderRecord->station_id);
            } else {
                // If they don't have a commander record, show empty list or handle as error
                $query->where('id', 0);
            }
        }

        $officers = $query->latest()->paginate(10);
        return view('station_officers.index', compact('officers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $isHighLevel = in_array($user->role->slug, ['admin', 'taliye-gobol', 'taliye-qaran']);
        $commander = \App\Models\StationCommander::where('user_id', $user->id)->first();

        if (!$commander && !$isHighLevel) {
            abort(403, 'Ma laguu oggola inaad diiwaangeliso askar.');
        }

        // Available users to be officers (role 'askari')
        $available_users = \App\Models\User::whereHas('role', function ($q) {
            $q->where('slug', 'askari');
        })->get();

        $stations = \App\Models\Station::all();
        $commanders = \App\Models\StationCommander::with('user')->get();
        $ranks = \App\Constants\PoliceRanks::all();

        return view('station_officers.create', compact('available_users', 'commander', 'stations', 'commanders', 'isHighLevel', 'ranks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $isHighLevel = in_array($user->role->slug, ['admin', 'taliye-gobol', 'taliye-qaran']);
        $commander = \App\Models\StationCommander::where('user_id', $user->id)->first();

        $request->validate([
            'officer_id' => 'required|exists:users,id',
            'duty_type' => 'required|string',
            'assigned_date' => 'required|date',
        ]);

        $officerUser = \App\Models\User::findOrFail($request->officer_id);

        $data = [
            'officer_id' => $request->officer_id,
            'rank' => $officerUser->rank, // Take rank from user profile
            'duty_type' => $request->duty_type,
            'assigned_date' => $request->assigned_date,
            'status' => 'active',
        ];

        // 1. Automatic Cleanup: Deactivate/Delete previous active assignments
        // The user requested: "askari saldhig lo diwan galiye hadda meeel kle lo diwan galinayo waa inukii hore otomatig u delete"
        // We will find any existing ACTIVE assignment for this officer and delete/deactivate it.
        $existingActive = StationOfficer::where('officer_id', $request->officer_id)
            ->where('status', 'active')
            ->get();

        foreach ($existingActive as $oldAssignment) {
            // We can either delete it or mark it as 'transferred'. 
            // Given the user said "delete", we'll delete the record to strictly follow instructions, 
            // OR we can set it to inactive. Let's set it to 'transferred' (inactive) to keep history but ensure it doesn't show as active.
            // Actually, to fully comply with "delete" visually, setting to 'inactive' is enough as lists usually show active.
            // But if they literally mean delete from DB:
            $oldAssignment->delete();
        }

        if ($isHighLevel) {
            $request->validate([
                'station_id' => 'required|exists:stations,id',
                'commander_id' => 'required|exists:station_commanders,id'
            ]);
            $data['station_id'] = $request->station_id;
            $data['commander_id'] = $request->commander_id;
        } else {
            if (!$commander) {
                abort(403, 'Waa inaad ahaataa taliye saldhig si aad askar u diiwaangeliso.');
            }
            $data['station_id'] = $commander->station_id;
            $data['commander_id'] = $commander->id;
        }

        StationOfficer::create($data);

        return redirect()->route('station-officers.index')->with('success', 'Askariga si guul leh ayaa loogu daray saldhigga.');
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
        $officer = StationOfficer::with(['user', 'station', 'commander.user'])->findOrFail($id);

        if ($user->role->slug === 'taliye-saldhig') {
            $commanderRecord = \App\Models\StationCommander::where('user_id', $user->id)->first();
            if (!$commanderRecord || $officer->station_id !== $commanderRecord->station_id) {
                abort(403, 'Ma laguu oggola inaad aragto askarigan.');
            }
        }

        return view('station_officers.show', compact('officer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = auth()->user();
        $officer = StationOfficer::findOrFail($id);
        $isHighLevel = in_array($user->role->slug, ['admin', 'taliye-gobol', 'taliye-qaran']);
        $commander = \App\Models\StationCommander::where('user_id', $user->id)->first();

        if (!$isHighLevel && (!$commander || $officer->station_id !== $commander->station_id)) {
            abort(403, 'Ma laguu oggola inaad wax ka bedesho askarigan.');
        }

        $available_users = \App\Models\User::whereHas('role', function ($q) {
            $q->where('slug', 'askari');
        })->get();

        $stations = \App\Models\Station::all();
        $commanders = \App\Models\StationCommander::with('user')->get();

        return view('station_officers.edit', compact('officer', 'available_users', 'commander', 'stations', 'commanders', 'isHighLevel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = auth()->user();
        $officer = StationOfficer::with('user')->findOrFail($id);
        $isHighLevel = in_array($user->role->slug, ['admin', 'taliye-gobol', 'taliye-qaran']);
        $commander = \App\Models\StationCommander::where('user_id', $user->id)->first();

        if (!$isHighLevel && (!$commander || $officer->station_id !== $commander->station_id)) {
            abort(403, 'Ma laguu oggola inaad wax ka bedesho askarigan.');
        }

        $request->validate([
            'duty_type' => 'required|string',
            'assigned_date' => 'required|date',
            'status' => 'required|in:active,inactive',
        ]);

        $data = [
            'rank' => $officer->user->rank, // Sync rank from user profile
            'duty_type' => $request->duty_type,
            'assigned_date' => $request->assigned_date,
            'status' => $request->status,
        ];

        if ($isHighLevel) {
            $request->validate([
                'station_id' => 'required|exists:stations,id',
                'commander_id' => 'required|exists:station_commanders,id'
            ]);
            $data['station_id'] = $request->station_id;
            $data['commander_id'] = $request->commander_id;
        }

        $officer->update($data);

        return redirect()->route('station-officers.index')->with('success', 'Askariga si guul leh ayaa xogtiisa loo cusboonaysiiyay.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = auth()->user();
        $officer = StationOfficer::findOrFail($id);
        $isHighLevel = in_array($user->role->slug, ['admin', 'taliye-gobol', 'taliye-qaran']);
        $commander = \App\Models\StationCommander::where('user_id', $user->id)->first();

        if (!$isHighLevel && (!$commander || $officer->station_id !== $commander->station_id)) {
            abort(403, 'Ma laguu oggola inaad tirtirto askarigan.');
        }

        $officer->delete();

        return redirect()->route('station-officers.index')->with('success', 'Askariga si guul leh ayaa looga saaray liiska.');
    }
}
