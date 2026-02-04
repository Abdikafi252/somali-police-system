<?php

namespace App\Http\Controllers;

use App\Models\PoliceCase;
use App\Models\Crime;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PoliceCaseController extends Controller
{
    public function index(Request $request)
    {
        $query = PoliceCase::with(['crime', 'assignedOfficer']);

        if ($request->has('assigned') && $request->assigned == 'me') {
            $query->where('assigned_to', auth()->id());
        }

        $cases = $query->latest()->paginate(10);
        return view('cases.index', compact('cases'));
    }

    public function dashboard()
    {
        $stats = [
            'total' => PoliceCase::count(),
            'investigating' => PoliceCase::whereIn('status', ['assigned', 'Baaris', 'Baarista-CID'])->count(),
            'prosecution' => PoliceCase::where('status', 'Xeer-Ilaalinta')->count(),
            'court' => PoliceCase::where('status', 'Maxkamadda')->count(),
            'closed' => PoliceCase::where('status', 'Xiran')->count(),
        ];

        $recentCases = PoliceCase::with(['crime', 'assignedOfficer'])->latest()->take(5)->get();

        return view('cases.dashboard', compact('stats', 'recentCases'));
    }

    public function create(Request $request)
    {
        $crime_id = $request->query('crime_id');
        $crime = Crime::findOrFail($crime_id);
        
        $cidRole = Role::where('slug', 'cid')->first();
        $officers = User::where('role_id', $cidRole->id)->get();

        return view('cases.create', compact('crime', 'officers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'crime_id' => 'required|exists:crimes,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|string',
        ]);

        // Automated Case Number Generation: SNP-PC-YYYY-001
        $year = date('Y');
        $lastCase = PoliceCase::whereYear('created_at', $year)->latest()->first();
        $nextNumber = 1;
        
        if ($lastCase && preg_match('/-(\d+)$/', $lastCase->case_number, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        }
        
        $caseNumber = sprintf("SNP-PC-%s-%03d", $year, $nextNumber);
        $validated['case_number'] = $caseNumber;
        
        $case = PoliceCase::create($validated);

        // Update crime status
        $crime = Crime::find($request->crime_id);
        $crime->update(['status' => 'Baaris']); // Investigating

        // Notify Assigned Officer
        if ($request->filled('assigned_to')) {
            $officer = User::find($request->assigned_to);
             if ($officer) {
                // Ensure auth user is passed, or system user if needed. 
                // Since this is authenticated route, auth()->user() should exist.
                $user = auth()->user(); 
                $officer->notify(new \App\Notifications\CaseAssignedNotification($case, $user));
            }
        }

        return redirect()->route('cases.index')->with('success', 'Kiiska si guul leh ayaa loo furay loona xilsaaray. Case Number: ' . $caseNumber);
    }

    public function show(PoliceCase $case)
    {
        $case->load([
            'crime.suspects', 
            'assignedOfficer', 
            'investigation.statements', 
            'prosecution.courtCase',
            'logs.officer'
        ]);
        return view('cases.show', compact('case'));
    }

    public function edit(PoliceCase $case)
    {
        $cidRole = Role::where('slug', 'cid')->first();
        $officers = User::where('role_id', $cidRole->id)->get();
        return view('cases.edit', compact('case', 'officers'));
    }

    public function update(Request $request, PoliceCase $case)
    {
        $validated = $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|string',
        ]);

        // Check if assignment changed
        $old_assigned_to = $case->assigned_to;
        $case->update($validated);

        if ($request->filled('assigned_to') && $request->assigned_to != $old_assigned_to) {
            $officer = User::find($request->assigned_to);
            if ($officer) {
                $user = auth()->user();
                $officer->notify(new \App\Notifications\CaseAssignedNotification($case, $user));
            }
        }

        return redirect()->route('cases.index')->with('success', 'Kiiska si guul leh ayaa loo cusbooneysiiyay.');
    }

    public function createUnified()
    {
        return view('cases.create_unified');
    }

    public function storeUnified(Request $request)
    {
        $request->validate([
            // Suspect
            'suspect_name' => 'required',
            'suspect_gender' => 'required',
            'suspect_status' => 'required',
            // Crime details (grouped with suspect)
            'crime_type' => 'required',
            'location' => 'required',
            'crime_date' => 'required|date',
            // Description
            'description' => 'required',
            // Victim (Optional)
            'victim_name' => 'nullable|string',
        ]);

        try {
            \DB::beginTransaction();

            // 1. Create Crime
            $crime = \App\Models\Crime::create([
                'crime_type' => $request->crime_type,
                'crime_date' => $request->crime_date,
                'location' => $request->location,
                'description' => $request->description,
                'case_number' => 'REF-' . strtoupper(uniqid()),
                'status' => 'Reported',
                'reported_by' => auth()->id(),
            ]);

            // Handle Photo Upload
            $photoPath = null;
            if ($request->hasFile('suspect_photo')) {
                $photoPath = $request->file('suspect_photo')->store('suspects', 'public');
            }

            // 2. Create Suspect
            \App\Models\Suspect::create([
                'crime_id' => $crime->id,
                'name' => $request->suspect_name,
                'nickname' => $request->suspect_nickname,
                'age' => $request->suspect_age,
                'mother_name' => $request->suspect_mother_name,
                'gender' => $request->suspect_gender,
                'residence' => $request->suspect_residence,
                'national_id' => $request->suspect_national_id,
                'arrest_status' => $request->suspect_status,
                'photo' => $photoPath,
            ]);

            // 3. Create Victim (If provided)
            if ($request->filled('victim_name')) {
                \App\Models\Victim::create([
                    'crime_id' => $crime->id,
                    'name' => $request->victim_name,
                    'age' => $request->victim_age,
                    'gender' => $request->victim_gender,
                    'injury_type' => $request->victim_injury,
                ]);
            }

            // 4. Create Police Case
            // Generate Case Number based on User Name (e.g. ABDI-2026-X832)
            $userName = strtoupper(substr(auth()->user()->name, 0, 3));
            $uniqueCode = strtoupper(Str::random(4));
            $caseNumber = $userName . '-' . date('Y') . '-' . $uniqueCode;

            $case = PoliceCase::create([
                'crime_id' => $crime->id,
                'case_number' => $caseNumber,
                'assigned_to' => auth()->id(),
                'status' => 'Open',
            ]);

            $crime->update(['case_number' => $case->case_number]);

            // Notify Admins & Commanders
            $commanders = \App\Models\User::whereHas('role', function($q) {
                $q->whereIn('slug', ['admin', 'taliye-saldhig', 'taliye-gobol', 'taliye-ciidan']);
            })->get();

            $reporter = auth()->user();
            \Illuminate\Support\Facades\Notification::send($commanders, new \App\Notifications\NewIncidentNotification($case, $reporter));

            \DB::commit();
            
            // Success message emphasizing the "Code"
            return redirect()->route('cases.index')->with('success', 'Incident recorded. Your Code: ' . $case->case_number);

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
