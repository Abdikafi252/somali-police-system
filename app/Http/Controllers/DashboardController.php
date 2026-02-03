<?php

namespace App\Http\Controllers;

use App\Models\Crime;
use App\Models\PoliceCase;
use App\Models\User;
use App\Models\Station;
use App\Models\Prosecution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Base Stats
        $case_stats = [
            'total' => PoliceCase::count(),
            'investigating' => PoliceCase::whereIn('status', ['assigned', 'Baaris', 'Baarista-CID'])->count(),
            'prosecution' => PoliceCase::where('status', 'Xeer-Ilaalinta')->count(),
            'court' => PoliceCase::where('status', 'Maxkamadda')->count(),
            'closed' => PoliceCase::whereIn('status', ['Xiran', 'Xukunsan', 'Dhamaaday'])->count(),
        ];

        // My Personal Stats (for Investigative Officers or Prosecutors)
        $my_stats = [];
        if ($user->role->slug == 'cid' || $user->role->slug == 'askari') {
            $my_stats = [
                'assigned' => PoliceCase::where('assigned_to', $user->id)->count(),
                'active' => PoliceCase::where('assigned_to', $user->id)->whereIn('status', ['assigned', 'Baaris'])->count(),
                'completed' => PoliceCase::where('assigned_to', $user->id)->whereNotIn('status', ['Diiwaangelin', 'assigned', 'Baaris'])->count(),
            ];
        } elseif ($user->role->slug == 'prosecutor') {
            $my_stats = [
                'assigned' => PoliceCase::where('status', 'Xeer-Ilaalinta')->count(), // All cases pending prosecution
                'active' => Prosecution::where('prosecutor_id', $user->id)->count(), // Cases I already started/submitted
                'completed' => PoliceCase::whereIn('status', ['Maxkamadda', 'Xiran'])->count(),
            ];
        }

        $total_crimes = Crime::count();
        $solved_cases = PoliceCase::whereIn('status', ['Xiran', 'Xukunsan', 'Dhamaaday'])->count();
        
        $stats = [
            'total_crimes' => $total_crimes,
            'active_cases' => PoliceCase::whereNotIn('status', ['Xiran', 'Xukunsan', 'Dhamaaday'])->count(),
            'total_officers' => User::count(),
            'pending_investigations' => PoliceCase::whereIn('status', ['assigned', 'Baaris', 'Baarista-CID'])->count(),
            'total_suspects' => \App\Models\Suspect::count(),
            'court_proceedings' => \App\Models\Prosecution::where('status', 'submitted')->count(),
            'active_deployments' => \App\Models\Deployment::where('status', 'on_duty')->count(),
            'solved_percent' => $total_crimes > 0 ? ($solved_cases / $total_crimes) * 100 : 0,
        ];

        $recent_crimes = Crime::with('reporter');
        
        // If CID, prioritize their assigned crimes or recent crimes in general
        if ($user->role->slug == 'cid') {
            $recent_crimes = $recent_crimes->latest()->take(5)->get();
        } else {
            $recent_crimes = $recent_crimes->latest()->take(5)->get();
        }

        // Crime Type Distribution
        $crime_types = Crime::select('crime_type', DB::raw('count(*) as count'))
            ->groupBy('crime_type')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();

        // Get stations with officer counts (Top 5)
        $stations_with_counts = Station::withCount('activeStationOfficers')
            ->orderBy('active_station_officers_count', 'desc')
            ->take(5)
            ->get();

        // Get user distribution by rank
        $users_by_rank = User::select('rank', DB::raw('count(*) as count'))
            ->whereNotNull('rank')
            ->groupBy('rank')
            ->orderBy('count', 'desc')
            ->get();

        // 1. Monthly Crime Trends (Line Chart) - Last 6 Months
        $trends = PoliceCase::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        $months = [];
        $trend_counts = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i)->month;
            $monthName = now()->subMonths($i)->format('M'); // Jan, Feb...
            $months[] = $monthName;
            $trend_counts[] = $trends[$month] ?? 0;
        }

        // 2. Top Officers (Most cases assigned)
        $top_officers = User::withCount('cases')
            ->whereHas('cases')
            ->orderBy('cases_count', 'desc')
            ->take(5)
            ->get();

        // 3. Station Performance (Solved Cases) - New Enhancement
        $station_performance = Station::with(['users.cases' => function($query) {
                $query->whereIn('status', ['Xiran', 'Xukunsan', 'Dhamaaday']);
            }])
            ->get()
            ->map(function($station) {
                $solved_count = $station->users->sum(function($user) {
                    return $user->cases->count();
                });
                return [
                    'name' => $station->station_name,
                    'solved' => $solved_count,
                    'commander' => $station->commander->name ?? 'N/A'
                ];
            })
            ->sortByDesc('solved')
            ->take(5);

        // 3. Recent Activities (Audit Logs)
        $activities = \App\Models\AuditLog::with('user')
            ->latest()
            ->take(6)
            ->get();

        // 4. Wanted Suspects (Simply filter by status 'wanted')
        $wanted_suspects = \App\Models\Suspect::with('crime')
            ->where('arrest_status', 'wanted')
            ->latest()
            ->take(8)
            ->get();

        // 5. Active Deployments (On Duty)
        $active_deployments = \App\Models\Deployment::with(['user', 'station', 'facility'])
            ->where('status', 'on_duty')
            ->latest()
            ->take(6)
            ->get();

        // 6. Facilities Summary
        $facility_stats = \App\Models\Facility::select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get();

        // 7. Upcoming Court Hearings
        $upcoming_hearings = \App\Models\CourtCase::with(['prosecution.suspect', 'judge'])
            ->where('hearing_date', '>=', now())
            ->orderBy('hearing_date', 'asc')
            ->take(5)
            ->get();

        // 8. Recent Evidence Uploads
        $recent_evidence = \App\Models\Evidence::with('crime')
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard.index', compact(
            'stats', 'case_stats', 'my_stats', 'recent_crimes', 
            'stations_with_counts', 'users_by_rank', 'crime_types', 
            'months', 'trend_counts', 'top_officers', 'activities', 
            'station_performance', 'wanted_suspects', 'active_deployments', 'facility_stats',
            'upcoming_hearings', 'recent_evidence'
        ));
    }
    public function markRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }
}
