<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\User;
use Illuminate\Http\Request;

class FacilityController extends Controller
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
        $facilities = Facility::with('commander')->get();
        return view('facilities.index', compact('facilities'));
    }

    public function create()
    {
        $commanders = User::whereHas('role', function ($query) {
            $query->whereIn('slug', ['admin', 'taliye-qaran', 'taliye-gobol', 'taliye-saldhig']);
        })->get();
        return view('facilities.create', compact('commanders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:facilities,name',
            'type' => 'required|string',
            'location' => 'required|string',
            'security_level' => 'required|string',
            'commander_id' => 'nullable|exists:users,id',
        ]);

        Facility::create($validated);

        return redirect()->route('facilities.index')->with('success', 'Xarunta si guul leh ayaa loo diiwangeliyay.');
    }

    public function edit(Facility $facility)
    {
        $commanders = User::whereHas('role', function ($query) {
            $query->whereIn('slug', ['admin', 'taliye-qaran', 'taliye-gobol', 'taliye-saldhig']);
        })->get();
        return view('facilities.edit', compact('facility', 'commanders'));
    }

    public function update(Request $request, Facility $facility)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:facilities,name,' . $facility->id,
            'type' => 'required|string',
            'location' => 'required|string',
            'security_level' => 'required|string',
            'commander_id' => 'nullable|exists:users,id',
        ]);

        $facility->update($validated);

        return redirect()->route('facilities.index')->with('success', 'Xarunta si guul leh ayaa loo cusboonaysiiyay.');
    }

    public function show(Facility $facility)
    {
        return view('facilities.show', compact('facility'));
    }

    public function destroy(Facility $facility)
    {
        if ($facility->deployments()->exists()) {
            return redirect()->route('facilities.index')->with('error', 'Xaruntan lama tirtiri karo sababtoo ah waxaa ka hawl-gala ciidan (Deployments).');
        }

        if ($facility->prosecutions()->exists()) {
            return redirect()->route('facilities.index')->with('error', 'Xaruntan lama tirtiri karo sababtoo ah waa Maxkamad loo isticmaalay kiisas (Prosecutions).');
        }

        $facility->delete();
        return redirect()->route('facilities.index')->with('success', 'Xarunta si guul leh ayaa loo tirtiray.');
    }
}
