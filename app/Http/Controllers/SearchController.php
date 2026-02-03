<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PoliceCase; // Assuming 'PoliceCase' model exists
use App\Models\Suspect;
use App\Models\Crime;
use App\Models\User;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return redirect()->back();
        }

        // Search Cases
        $cases = PoliceCase::where('case_number', 'LIKE', "%{$query}%")
            ->orWhereHas('crime', function($q) use ($query) {
                $q->where('crime_type', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('location', 'LIKE', "%{$query}%");
            })
            ->with('crime')
            ->take(10)
            ->get();

        // Search Suspects
        $suspects = Suspect::where('name', 'LIKE', "%{$query}%")
            ->orWhere('national_id', 'LIKE', "%{$query}%")
            ->take(10)
            ->get();

        // Search Crimes (if separate from cases)
        $crimes = Crime::where('crime_type', 'LIKE', "%{$query}%") // Changed to crime_type based on dashboard usage
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->take(10)
            ->get();
            
        // Search Officers
        $officers = User::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->take(5)
            ->get();

        return view('search.results', compact('query', 'cases', 'suspects', 'crimes', 'officers'));
    }
}
