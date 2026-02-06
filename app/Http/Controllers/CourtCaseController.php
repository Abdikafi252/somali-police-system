<?php

namespace App\Http\Controllers;

use App\Models\CourtCase;
use App\Models\Prosecution;
use App\Models\PoliceCase;
use Illuminate\Http\Request;

class CourtCaseController extends Controller
{
    public function index()
    {
        // Cases waiting for judgment (submitted by prosecutor but no court verdict yet)
        $pending_judgments = Prosecution::with(['policeCase.crime', 'prosecutor', 'court'])
            ->where('status', 'Gudbis')
            ->latest()
            ->get();

        $courtCases = CourtCase::with(['prosecution.policeCase.crime', 'judge'])->latest()->paginate(10);
        return view('court.index', compact('courtCases', 'pending_judgments'));
    }

    public function create(Request $request)
    {
        // Enforce Judge Role
        if (auth()->user()->role->slug !== 'judge') {
            abort(403, 'Ma laguu oggola inaad gasho qaybtan. Garsoore kaliya ayaa gaari kara go\'aan.');
        }

        $prosecution_id = $request->query('prosecution_id');
        $prosecution = Prosecution::with(['policeCase.crime', 'policeCase.investigation.statements', 'prosecutor'])->findOrFail($prosecution_id);
        return view('court.create', compact('prosecution'));
    }

    public function store(Request $request)
    {
        // Enforce Judge Role
        if (auth()->user()->role->slug !== 'judge') {
            abort(403, 'Ma laguu oggola inaad gasho qaybtan. Garsoore kaliya ayaa gaari kara go\'aan.');
        }

        $validated = $request->validate([
            'prosecution_id' => 'required|exists:prosecutions,id',
            'verdict' => 'required|string',
        ]);

        CourtCase::create([
            'prosecution_id' => $request->prosecution_id,
            'judge_id' => auth()->id(),
            'hearing_date' => now(),
            'verdict' => $request->verdict,
            'status' => 'Xiran', // Closed
        ]);

        $prosecution = Prosecution::find($request->prosecution_id);
        $prosecution->update(['status' => 'Dhamaaday']); // Completed

        $case = PoliceCase::find($prosecution->case_id);
        $case->update(['status' => 'Xiran']); // Officially Closed

        return redirect()->route('court-cases.index')->with('success', 'Kiiska si rasmi ah ayaa loo xiray xukuna waa laga soo saaray. Kiisku hadda waa XIRAN.');
    }
}
