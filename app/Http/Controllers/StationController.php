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
        $station->load(['users.role', 'commander']);
        return view('stations.show', compact('station'));
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
