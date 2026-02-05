<?php

namespace App\Http\Controllers;

use App\Models\Prosecution;
use App\Models\PoliceCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Facility;
use Illuminate\Http\Request;

class ProsecutionController extends Controller
{
    public function index()
    {
        // Cases waiting for prosecution (status is Xeer-Ilaalinta)
        $pending_cases = PoliceCase::with(['crime', 'investigation'])
            ->where('status', 'Xeer-Ilaalinta')
            ->latest()
            ->get();

        $prosecutions = Prosecution::with(['policeCase.crime', 'prosecutor', 'court'])->latest()->paginate(10);
        return view('prosecutions.index', compact('prosecutions', 'pending_cases'));
    }

    public function create(Request $request)
    {
        $case_id = $request->query('case_id');
        $case = PoliceCase::with(['crime', 'investigation'])->findOrFail($case_id);
        $courts = Facility::where('type', 'Court')->get();
        
        // Auto-select court based on crime location
        $crimeLocation = $case->crime->location;
        $selectedCourt = null;
        
        // Try to match district court by location
        $districtCourt = $courts->first(function($court) use ($crimeLocation) {
            return stripos($court->name, 'Degmada') !== false && 
                   stripos($court->location, $crimeLocation) !== false;
        });
        
        // If no match, select first district court
        if (!$districtCourt) {
            $districtCourt = $courts->first(function($court) {
                return stripos($court->name, 'Degmada') !== false;
            });
        }
        
        $selectedCourt = $districtCourt ? $districtCourt->id : null;
        
        return view('prosecutions.create', compact('case', 'courts', 'selectedCourt'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'case_id' => 'required|exists:cases,id',
            'charges' => 'required|string',
            'court_id' => 'required|exists:facilities,id',
        ]);

        Prosecution::create([
            'case_id' => $request->case_id,
            'prosecutor_id' => auth()->id(),
            'court_id' => $request->court_id,
            'submission_date' => now(),
            'charges' => $request->charges,
            'status' => 'Gudbis', // Submitted
        ]);

        $case = PoliceCase::find($request->case_id);
        $case->update(['status' => 'Maxkamadda']); // Moved to Court

        return redirect()->route('prosecutions.index')->with('success', 'Dacwadda si guul leh ayaa loogu gudbiyay Maxkamadda.');
    }
}
