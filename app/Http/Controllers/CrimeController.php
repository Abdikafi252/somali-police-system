<?php

namespace App\Http\Controllers;

use App\Models\Crime;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CrimeController extends Controller
{
    public function index()
    {
        $crimes = Crime::with('reporter')->latest()->paginate(10);
        return view('crimes.index', compact('crimes'));
    }

    public function create()
    {
        // Fetch recent crimes to display on the creation page (User request: bring all data to New Incident)
        $recentCrimes = Crime::latest()->take(5)->get();
        return view('crimes.create', compact('recentCrimes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'crime_type' => 'required|string',
            'description' => 'required|string',
            'location' => 'required|string',
            'crime_date' => 'required|date',
            'evidence.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        // Automated Case Number Generation: SNP-CR-YYYY-001
        $year = date('Y');
        $lastCrime = Crime::whereYear('created_at', $year)->latest()->first();
        $nextNumber = 1;
        
        if ($lastCrime && preg_match('/-(\d+)$/', $lastCrime->case_number, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        }
        
        $caseNumber = sprintf("SNP-CR-%s-%03d", $year, $nextNumber);

        $crime = Crime::create([
            'case_number' => $caseNumber,
            'crime_type' => $request->crime_type,
            'description' => $request->description,
            'location' => $request->location,
            'crime_date' => $request->crime_date,
            'reported_by' => auth()->id(),
            'status' => 'Diiwaangelin', // Pending
        ]);

        // Notify All Users (As requested: "users waliba ha u diro message")
        $recipients = \App\Models\User::all();

        \Illuminate\Support\Facades\Notification::send($recipients, new \App\Notifications\NewCrimeReported($crime));

        \App\Services\AuditService::log('create', $crime, ['description' => "Registered new crime case: {$caseNumber} ({$request->crime_type})"]);

        // Handle Suspect Creation (Combined Form)
        if ($request->filled('suspect_name')) {
            $suspectData = [
                'crime_id' => $crime->id,
                'name' => $request->suspect_name,
                'nickname' => $request->suspect_nickname,
                'mother_name' => $request->suspect_mother_name,
                'address' => $request->suspect_address,
                'age' => $request->suspect_age,
                'gender' => $request->suspect_gender,
                'national_id' => $request->national_id,
                'arrest_status' => $request->arrest_status ?? 'Baxsad',
            ];

            if ($request->hasFile('suspect_photo')) {
                $suspectData['photo'] = $request->file('suspect_photo')->store('suspects', 'public');
            }

            \App\Models\Suspect::create($suspectData);
        }

        // Handle Victim Creation
        if ($request->filled('victim_name')) {
            \App\Models\Victim::create([
                'crime_id' => $crime->id,
                'name' => $request->victim_name,
                'age' => $request->victim_age,
                'gender' => $request->victim_gender,
                'injury_type' => $request->victim_injury,
            ]);
        }

        // Handle Evidence Upload
        if ($request->hasFile('evidence')) {
            foreach ($request->file('evidence') as $file) {
                $path = $file->store('evidence', 'public');
                $crime->evidence()->create([
                    'file_path' => $path,
                    'file_type' => str_starts_with($file->getMimeType(), 'image') ? 'image' : 'document',
                ]);
            }
        }

        // Send notifications to prosecutors and judges
        $prosecutorRole = \App\Models\Role::where('slug', 'prosecutor')->first();
        $judgeRole = \App\Models\Role::where('slug', 'judge')->first();
        
        if ($prosecutorRole) {
            $prosecutors = \App\Models\User::where('role_id', $prosecutorRole->id)->get();
            foreach ($prosecutors as $prosecutor) {
                $prosecutor->notify(new \App\Notifications\CaseRegistered($crime, $caseNumber));
            }
        }
        
        if ($judgeRole) {
            $judges = \App\Models\User::where('role_id', $judgeRole->id)->get();
            foreach ($judges as $judge) {
                $judge->notify(new \App\Notifications\CaseRegistered($crime, $caseNumber));
            }
        }

        return redirect()->route('crimes.index')->with('success', 'Dambiga si guul leh ayaa loo diiwangeliyay. Case Number: ' . $caseNumber);
    }

    public function show(Crime $crime)
    {
        return view('crimes.show', compact('crime'));
    }

    public function edit(Crime $crime)
    {
        return view('crimes.edit', compact('crime'));
    }

    public function update(Request $request, Crime $crime)
    {
        $validated = $request->validate([
            'crime_type' => 'required|string',
            'description' => 'required|string',
            'location' => 'required|string',
            'crime_date' => 'required|date',
        ]);

        $crime->update($validated);

        \App\Services\AuditService::log('update', $crime, ['description' => "Updated crime details for case: {$crime->case_number}"]);

        return redirect()->route('crimes.index')->with('success', 'Dambiga si guul leh ayaa loo cusbooneysiiyay.');
    }

    public function destroy(Crime $crime)
    {
        if ($crime->policeCases()->exists()) {
            return redirect()->route('crimes.index')->with('error', 'Dambigan lama tirtiri karo sababtoo ah wuxuu leeyahay Kiisas ku xiran.');
        }

        if ($crime->suspects()->exists()) {
            return redirect()->route('crimes.index')->with('error', 'Dambigan lama tirtiri karo sababtoo ah wuxuu leeyahay Dambiilayaal ku xiran.');
        }

        \App\Services\AuditService::log('delete', $crime, ['description' => "Deleted crime case: {$crime->case_number}"]);
        $crime->delete();
        return redirect()->route('crimes.index')->with('success', 'Dambiga waa la tirtiray.');
    }

    public function exportPDF(Crime $crime)
    {
        $crime->load(['reporter', 'suspects', 'evidence']);
        return view('crimes.pdf', compact('crime'));
    }
}
