<?php

namespace App\Http\Controllers;

use App\Models\Deployment;
use App\Models\User;
use App\Models\Facility;
use Illuminate\Http\Request;

class DeploymentController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->check() && in_array(auth()->user()->role->slug, ['prosecutor', 'judge'])) {
                abort(403, 'Ma laguu oggola inay gasho qaybtan.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $deployments = Deployment::with(['user', 'station', 'facility'])->latest()->paginate(10);
        return view('deployments.index', compact('deployments'));
    }

    public function create()
    {
        $officers = User::whereHas('role', function ($query) {
            $query->where('slug', 'askari');
        })->get();

        $stations = \App\Models\Station::all();
        $facilities = Facility::where('type', '!=', 'Court')->get();

        return view('deployments.create', compact('officers', 'stations', 'facilities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'station_id' => 'nullable|exists:stations,id',
            'facility_id' => 'nullable|exists:facilities,id',
            'duty_type' => 'required|string',
            'shift' => 'required|string',
        ]);

        // Prevent duplicate deployments: Delete old deployment for this user
        Deployment::where('user_id', $request->user_id)->delete();

        Deployment::create($validated + ['status' => 'on_duty']);

        return redirect()->route('deployments.index')->with('success', 'Askari si guul leh ayaa loo geeyay shaqada.');
    }
    public function show(Deployment $deployment)
    {
        return view('deployments.show', compact('deployment'));
    }

    public function edit(Deployment $deployment)
    {
        $officers = User::whereHas('role', function ($query) {
            $query->where('slug', 'askari');
        })->get();

        $stations = \App\Models\Station::all();
        $facilities = Facility::where('type', '!=', 'Court')->get();

        return view('deployments.edit', compact('deployment', 'officers', 'stations', 'facilities'));
    }

    public function update(Request $request, Deployment $deployment)
    {
        $validated = $request->validate([
            'station_id' => 'nullable|exists:stations,id',
            'facility_id' => 'nullable|exists:facilities,id',
            'duty_type' => 'required|string',
            'shift' => 'required|string',
            'status' => 'required|string',
        ]);

        $deployment->update($validated);

        return redirect()->route('deployments.index')->with('success', 'Shaqada askariga si guul leh ayaa loo cusbooneysiiyay.');
    }

    public function destroy(Deployment $deployment)
    {
        $deployment->delete();
        return redirect()->route('deployments.index')->with('success', 'Shaqada waa la tirtiray.');
    }
}
