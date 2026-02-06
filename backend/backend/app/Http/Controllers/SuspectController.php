<?php

namespace App\Http\Controllers;

use App\Models\Suspect;
use Illuminate\Http\Request;

class SuspectController extends Controller
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
        $suspects = Suspect::latest()->paginate(10);
        return view('suspects.index', compact('suspects'));
    }

    public function create()
    {
        return view('suspects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'nullable|integer',
            'gender' => 'nullable|string',
            'national_id' => 'nullable|string|unique:suspects',
            'crime_id' => 'required|exists:crimes,id',
            'arrest_status' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('suspect_photos', 'public');
            $validated['photo'] = $path;
        }

        $suspect = Suspect::create($validated);

        // Notify Relevant Users
        $recipients = \App\Models\User::whereHas('role', function ($query) {
            $query->whereIn('slug', [
                'taliye-saldhig', 
                'taliye-gobol', 
                'taliye-qaran', 
                'prosecutor', 
                'judge',
                'cid'
            ]);
        })->get();

        \Illuminate\Support\Facades\Notification::send($recipients, new \App\Notifications\NewSuspectAdded($suspect));

        \App\Services\AuditService::log('create', $suspect, ['description' => "Registered new suspect: {$suspect->name}"]);

        return redirect()->route('suspects.index')->with('success', 'Dambiilaha si guul leh ayaa loo diiwangeliyay.');
    }

    public function show(Suspect $suspect)
    {
        return view('suspects.show', compact('suspect'));
    }

    public function edit(Suspect $suspect)
    {
        $crimes = \App\Models\Crime::all(); // Need crimes for selection
        return view('suspects.edit', compact('suspect', 'crimes'));
    }

    public function update(Request $request, Suspect $suspect)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'nullable|integer',
            'gender' => 'nullable|string',
            'national_id' => 'nullable|string|unique:suspects,national_id,' . $suspect->id,
            'crime_id' => 'required|exists:crimes,id',
            'arrest_status' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('suspect_photos', 'public');
            $validated['photo'] = $path;
        }

        $suspect->update($validated);

        \App\Services\AuditService::log('update', $suspect, ['description' => "Updated suspect details: {$suspect->name}"]);

        return redirect()->route('suspects.index')->with('success', 'Xogta dambiilaha waa la cusbooneysiiyay.');
    }

    public function destroy(Suspect $suspect)
    {
        if ($suspect->arrests()->exists()) {
            return redirect()->route('suspects.index')->with('error', 'Dambiilahan lama tirtiri karo sababtoo ah wuxuu leeyahay xog xarig (Arrest Records).');
        }

        \App\Services\AuditService::log('delete', $suspect, ['description' => "Deleted suspect record: {$suspect->name}"]);
        $suspect->delete();
        return redirect()->route('suspects.index')->with('success', 'Dambiilaha waa la tirtiray.');
    }
}
