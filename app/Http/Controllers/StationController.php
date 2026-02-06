<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function __construct()
    {
        // Restrict access to specific roles
        // admin, taliye-gobol, taliye-qaran, taliye-ciidanka
        // Note: The user said 'taliyaha ciidanka' which maps to 'taliye-qaran' in seeder
        $this->middleware(function ($request, $next) {
            $allowedRoles = ['admin', 'taliye-gobol', 'taliye-qaran'];
            if (!auth()->check() || !in_array(auth()->user()->role->slug, $allowedRoles)) {
                abort(403, 'Ma laguu oggola inay gasho qaybtan.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $stations = Station::all();
        return view('stations.index', compact('stations'));
    }

    public function create()
    {
        return view('stations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'station_name' => 'required|string|max:255|unique:stations,station_name',
            'location' => 'required|string|max:255',
        ]);

        Station::create($request->only(['station_name', 'location']));

        return redirect()->route('stations.index')->with('success', 'Saldhigga si guul leh ayaa loo diiwaangeliyay.');
    }

    public function show(Station $station)
    {
    public function show(Station $station)
    {
        // Load relationships
        $station->load(['commander', 'activeStationOfficers.user.role']);

        // 1. Determine Active Commander
        // Priority: Explicit station->commander, then search active StationCommander record
        $activeCommander = $station->commander;
        
        if (!$activeCommander) {
            $activeCommanderRecord = \App\Models\StationCommander::where('station_id', $station->id)
                ->where('status', 'active')
                ->latest('appointed_date')
                ->first();
            
            if ($activeCommanderRecord) {
                $activeCommander = $activeCommanderRecord->user;
            }
        }

        // 2. Build Comprehensive Staff List
        // Start with Active Station Officers
        $staffList = $station->activeStationOfficers->map(function($officer) {
            $user = $officer->user;
            if ($user) {
                // Attach pivot-like data directly to user object for easy display
                $user->display_rank = $officer->rank;
                $user->display_role = $officer->duty_type; // e.g. 'Patrol', 'Guard'
                $user->display_status = $officer->status;
                $user->source = 'officer';
            }
            return $user;
        })->filter();

        // Add Commander to the list if exists and not already in list
        if ($activeCommander) {
            // Check if already in list to avoid duplicates
            if (!$staffList->contains('id', $activeCommander->id)) {
                $activeCommander->display_rank = $activeCommander->rank ?? 'TALIYE';
                $activeCommander->display_role = 'Taliyaha Saldhigga';
                $activeCommander->display_status = 'active';
                $activeCommander->source = 'commander';
                $staffList->prepend($activeCommander);
            }
        }
        
        // If staff list is empty, fallback to users table (legacy support)
        if ($staffList->isEmpty() && $station->users->count() > 0) {
           $staffList = $station->users;
        }

        return view('stations.show', compact('station', 'activeCommander', 'staffList'));
    }
    }

    public function edit(Station $station)
    {
        return view('stations.edit', compact('station'));
    }

    public function update(Request $request, Station $station)
    {
        $request->validate([
            'station_name' => 'required|string|max:255|unique:stations,station_name,' . $station->id,
            'location' => 'required|string|max:255',
        ]);

        $station->update($request->only(['station_name', 'location']));

        return redirect()->route('stations.index')->with('success', 'Saldhigga si guul leh ayaa loo cusboonaysiiyay.');
    }

    public function destroy(Station $station)
    {
        $station->delete();
        return redirect()->route('stations.index')->with('success', 'Saldhigga si guul leh ayaa loo tirtiray.');
    }
}
