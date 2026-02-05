<?php

namespace App\Http\Controllers;

use App\Models\InvestigationLog;
use Illuminate\Http\Request;

class InvestigationLogController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'case_id' => 'required|exists:cases,id',
            'log_entry' => 'required|string',
            'log_type' => 'required|string',
            'entry_date' => 'required|date',
        ]);

        $validated['user_id'] = auth()->id();

        InvestigationLog::create($validated);

        return back()->with('success', 'Xogta baaritaanka si guul leh ayaa loo diiwaangaliyey.');
    }
}
