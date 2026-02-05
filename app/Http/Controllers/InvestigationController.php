<?php

namespace App\Http\Controllers;

use App\Models\Investigation;
use App\Models\PoliceCase;
use Illuminate\Http\Request;

class InvestigationController extends Controller
{
    public function index()
    {
        $query = Investigation::with('policeCase.crime');
        
        $user = auth()->user();
        $userRole = $user->role->slug;
        
        // Role-based filtering
        if (!in_array($userRole, ['admin', 'taliye-ciidan', 'taliye-gobol'])) {
            if ($userRole == 'cid') {
                // CID sees investigations for their assigned cases
                $query->whereHas('policeCase', function($q) use ($user) {
                    $q->where('assigned_to', $user->id);
                });
            } elseif (in_array($userRole, ['askari', 'taliye-saldhig'])) {
                // Station officers see investigations from their station
                $query->whereHas('policeCase.assignedUser', function($q) use ($user) {
                    $q->where('station_id', $user->station_id);
                });
            }
        }
        
        $investigations = $query->latest()->paginate(10);
        return view('investigations.index', compact('investigations'));
    }

    public function create(Request $request)
    {
        $case_id = $request->query('case_id');
        $case = PoliceCase::with('crime')->findOrFail($case_id);
        return view('investigations.create', compact('case'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'case_id' => 'required|exists:cases,id',
            'findings' => 'required|string',
            'outcome' => 'required|string',
            'evidence_list' => 'nullable|string',
            'files.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max per file
        ]);

        $fileData = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('investigations', 'public');
                $fileData[] = $path;
            }
        }

        $investigation = Investigation::create([
            'case_id' => $validated['case_id'],
            'findings' => $validated['findings'],
            'outcome' => $validated['outcome'],
            'evidence_list' => $validated['evidence_list'],
            'files' => $fileData,
            'status' => 'completed'
        ]);

        // Save Statements
        if ($request->has('statements')) {
            foreach ($request->statements as $key => $content) {
                if (!empty($content)) {
                    $investigation->statements()->create([
                        'person_name' => $request->statement_names[$key],
                        'person_type' => $request->statement_types[$key],
                        'statement' => $content,
                        'statement_date' => $request->statement_dates[$key],
                    ]);
                }
            }
        }

        // Update case status
        $case = PoliceCase::find($request->case_id);
        $case->update(['status' => 'Xeer-Ilaalinta']); // Moved to Prosecution

        return redirect()->route('cases.show', $case->id)->with('success', 'Baaritaanka si guul leh ayaa loo xareeyay, waxaana loo gudbiyay Xeer-Ilaalinta.');
    }

    public function show(Investigation $investigation)
    {
        $investigation->load(['policeCase.crime', 'policeCase.assignedOfficer', 'statements']);
        return view('investigations.show', compact('investigation'));
    }

    public function edit(Investigation $investigation)
    {
        return view('investigations.edit', compact('investigation'));
    }

    public function update(Request $request, Investigation $investigation)
    {
        $validated = $request->validate([
            'findings' => 'required|string',
            'outcome' => 'required|string',
            'status' => 'required|string',
        ]);

        $investigation->update($validated);

        return redirect()->route('investigations.index')->with('success', 'Baaritaanka si guul leh ayaa loo cusbooneysiiyay.');
    }

    public function destroy(Investigation $investigation)
    {
        if ($investigation->statements()->exists()) {
            return redirect()->route('investigations.index')->with('error', 'Baaritaankan lama tirtiri karo sababtoo ah wuxuu leeyahay Warbixino (Statements) ku xiran.');
        }

        $investigation->delete();
        return redirect()->route('investigations.index')->with('success', 'Baaritaanka waa la tirtiray.');
    }

    public function showReport($id)
    {
        $investigation = Investigation::with(['policeCase.crime', 'policeCase.assignedOfficer', 'statements'])->findOrFail($id);
        return view('investigations.report', compact('investigation'));
    }
}
