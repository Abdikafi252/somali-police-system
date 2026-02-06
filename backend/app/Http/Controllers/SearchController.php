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

        $user = auth()->user();
        $userRole = $user->role->slug;
        $hasFullAccess = in_array($userRole, ['admin', 'taliye-qaran', 'taliye-gobol']);

        // Search Cases with role-based filtering
        $casesQuery = PoliceCase::where('case_number', 'LIKE', "%{$query}%")
            ->orWhereHas('crime', function ($q) use ($query) {
                $q->where('crime_type', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%")
                    ->orWhere('location', 'LIKE', "%{$query}%");
            });

        // Apply role-based filters
        if (!$hasFullAccess) {
            if ($userRole == 'prosecutor') {
                $casesQuery->whereIn('status', ['Xeer-Ilaalinta', 'Maxkamadda', 'Xiran', 'Xukunsan']);
            } elseif ($userRole == 'judge') {
                $casesQuery->whereIn('status', ['Maxkamadda', 'Xiran', 'Xukunsan']);
            } elseif (in_array($userRole, ['askari', 'taliye-saldhig', 'cid'])) {
                $casesQuery->whereHas('assignedUser', function ($q) use ($user) {
                    $q->where('station_id', $user->station_id);
                });
            }
        }

        $cases = $casesQuery->with('crime')->take(10)->get();

        // Search Suspects with role-based filtering
        $suspectsQuery = Suspect::where('name', 'LIKE', "%{$query}%")
            ->orWhere('national_id', 'LIKE', "%{$query}%");

        if (!$hasFullAccess && in_array($userRole, ['askari', 'taliye-saldhig', 'cid'])) {
            $suspectsQuery->whereHas('crime.reporter', function ($q) use ($user) {
                $q->where('station_id', $user->station_id);
            });
        }

        $suspects = $suspectsQuery->take(10)->get();

        // Search Crimes with role-based filtering
        $crimesQuery = Crime::where('crime_type', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%");

        if (!$hasFullAccess && in_array($userRole, ['askari', 'taliye-saldhig', 'cid'])) {
            $crimesQuery->where(function ($q) use ($user) {
                $q->where('reported_by', $user->id)
                    ->orWhereHas('reporter', function ($sq) use ($user) {
                        $sq->where('station_id', $user->station_id);
                    });
            });
        }

        $crimes = $crimesQuery->take(10)->get();

        // Search Officers (only for admins and commanders)
        $officers = collect([]);
        if ($hasFullAccess || in_array($userRole, ['taliye-saldhig'])) {
            $officersQuery = User::where('name', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%");

            if ($userRole == 'taliye-saldhig') {
                $officersQuery->where('station_id', $user->station_id);
            }

            $officers = $officersQuery->take(5)->get();
        }

        return view('search.results', compact('query', 'cases', 'suspects', 'crimes', 'officers'));
    }
}
