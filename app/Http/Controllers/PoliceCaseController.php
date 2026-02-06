<?php

namespace App\Http\Controllers;

use App\Models\PoliceCase;
use App\Models\Crime;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class PoliceCaseController extends Controller
{
    public function index(Request $request)
    {
        $query = PoliceCase::with(['crime', 'assignedOfficer']);

        // Role-based filtering
        $userRole = auth()->user()->role->slug;

        if ($userRole == 'prosecutor') {
            // Prosecutors see cases in prosecution/court stages
            $query->whereIn('status', ['Xeer-Ilaalinta', 'Maxkamadda', 'Xiran', 'Xukunsan']);
        } elseif ($userRole == 'judge') {
            // Judges see only court cases
            $query->whereIn('status', ['Maxkamadda', 'Xiran', 'Xukunsan']);
        } elseif ($request->has('assigned') && $request->assigned == 'me') {
            // Officers see their assigned cases
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

        return redirect()->route('cases.show', $case)->with('success', 'Kiiska si guul leh ayaa loo furay loona xilsaaray. Case Number: ' . $caseNumber);
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

    public function destroy(PoliceCase $case)
    {
        if ($case->investigation()->exists()) {
            return redirect()->route('cases.index')->with('error', 'Kiiskan lama tirtiri karo sababtoo ah wuxuu leeyahay Baaris socota ama dhamaatay.');
        }

        if ($case->prosecution()->exists()) {
            return redirect()->route('cases.index')->with('error', 'Kiiskan lama tirtiri karo sababtoo ah wuxuu yaalaa Xeer-Ilaalinta.');
        }

        $case->delete();
        return redirect()->route('cases.index')->with('success', 'Kiiska si guul leh ayaa loo tirtiray.');
    }
}
