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
        
        // Determine if user has full access (admin, taliye-ciidan, taliye-gobol)
        $hasFullAccess = in_array($user->role->slug, ['admin', 'taliye-ciidan', 'taliye-gobol']);
        
        // Base query filters based on role
        $caseQuery = PoliceCase::query();
        $crimeQuery = Crime::query();
        $suspectQuery = \App\Models\Suspect::query();
        
        if (!$hasFullAccess) {
            // Station-based filtering for station commanders and officers
            if (in_array($user->role->slug, ['taliye-saldhig', 'askari'])) {
                // Only see cases from their station
                $caseQuery->whereHas('assignedUser', function($q) use ($user) {
                    $q->where('station_id', $user->station_id);
                });
                $crimeQuery->whereHas('reporter', function($q) use ($user) {
                    $q->where('station_id', $user->station_id);
                });
                $suspectQuery->whereHas('crime.reporter', function($q) use ($user) {
                    $q->where('station_id', $user->station_id);
                });
            }
            
            // CID officers only see their assigned cases
            if ($user->role->slug == 'cid') {
                $caseQuery->where('assigned_to', $user->id);
                $crimeQuery->whereHas('case', function($q) use ($user) {
                    $q->where('assigned_to', $user->id);
                });
            }
            
            // Prosecutors only see cases in prosecution/court
            if ($user->role->slug == 'prosecutor') {
                $caseQuery->whereIn('status', ['Xeer-Ilaalinta', 'Maxkamadda', 'Xiran', 'Xukunsan']);
            }
        }
        
        // Base Stats
        $case_stats = [
            'total' => $caseQuery->count(),
            'investigating' => (clone $caseQuery)->whereIn('status', ['assigned', 'Baaris', 'Baarista-CID'])->count(),
            'prosecution' => (clone $caseQuery)->where('status', 'Xeer-Ilaalinta')->count(),
            'court' => (clone $caseQuery)->where('status', 'Maxkamadda')->count(),
            'closed' => (clone $caseQuery)->whereIn('status', ['Xiran', 'Xukunsan', 'Dhamaaday'])->count(),
        ];

        // My Personal Stats
        $my_stats = [];
        if ($user->role->slug == 'cid' || $user->role->slug == 'askari') {
            $my_stats = [
                'assigned' => PoliceCase::where('assigned_to', $user->id)->count(),
                'active' => PoliceCase::where('assigned_to', $user->id)->whereIn('status', ['assigned', 'Baaris'])->count(),
                'completed' => PoliceCase::where('assigned_to', $user->id)->whereNotIn('status', ['Diiwaangelin', 'assigned', 'Baaris'])->count(),
            ];
        } elseif ($user->role->slug == 'prosecutor') {
            $my_stats = [
                'assigned' => PoliceCase::where('status', 'Xeer-Ilaalinta')->count(),
                'active' => Prosecution::where('prosecutor_id', $user->id)->count(),
                'completed' => PoliceCase::whereIn('status', ['Maxkamadda', 'Xiran'])->count(),
            ];
        }

        $total_crimes = $crimeQuery->count();
        $solved_cases = (clone $caseQuery)->whereIn('status', ['Xiran', 'Xukunsan', 'Dhamaaday'])->count();
        
        $stats = [
            'total_crimes' => $total_crimes,
            'active_cases' => (clone $caseQuery)->whereNotIn('status', ['Xiran', 'Xukunsan', 'Dhamaaday'])->count(),
            'total_officers' => $hasFullAccess ? User::count() : User::where('station_id', $user->station_id)->count(),
            'pending_investigations' => (clone $caseQuery)->whereIn('status', ['assigned', 'Baaris', 'Baarista-CID'])->count(),
            'total_suspects' => $suspectQuery->count(),
            'court_proceedings' => \App\Models\Prosecution::when(!$hasFullAccess, function($q) use ($user) {
                return $q->whereHas('case.assignedUser', function($sq) use ($user) {
                    $sq->where('station_id', $user->station_id);
                });
            })->where('status', 'submitted')->count(),
            'active_deployments' => \App\Models\Deployment::when(!$hasFullAccess, function($q) use ($user) {
                return $q->where('station_id', $user->station_id);
            })->where('status', 'on_duty')->count(),
            'solved_percent' => $total_crimes > 0 ? ($solved_cases / $total_crimes) * 100 : 0,
        ];

        $recent_crimes = (clone $crimeQuery)->with('reporter')->latest()->take(5)->get();

        // Crime Type Distribution
        $crime_types = (clone $crimeQuery)->select('crime_type', DB::raw('count(*) as count'))
            ->groupBy('crime_type')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();

        // Get stations with officer counts
        $stations_with_counts = Station::when(!$hasFullAccess, function($q) use ($user) {
            return $q->where('id', $user->station_id);
        })->withCount('activeStationOfficers')
            ->orderBy('active_station_officers_count', 'desc')
            ->take(5)
            ->get();

        // Get user distribution by rank
        $users_by_rank = User::when(!$hasFullAccess, function($q) use ($user) {
            return $q->where('station_id', $user->station_id);
        })->select('rank', DB::raw('count(*) as count'))
            ->whereNotNull('rank')
            ->groupBy('rank')
            ->orderBy('count', 'desc')
            ->get();

        // Monthly Crime Trends
        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            $monthQuery = "strftime('%m', created_at) + 0";
        } elseif ($driver === 'pgsql') {
            $monthQuery = "EXTRACT(MONTH FROM created_at)";
        } else {
            $monthQuery = "MONTH(created_at)";
        }

        $trends = (clone $caseQuery)->select(DB::raw("$monthQuery as month"), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        $months = [];
        $trend_counts = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i)->month;
            $monthName = now()->subMonths($i)->format('M');
            $months[] = $monthName;
            $trend_counts[] = $trends[$month] ?? 0;
        }

        // Top Officers
        $top_officers = User::when(!$hasFullAccess, function($q) use ($user) {
            return $q->where('station_id', $user->station_id);
        })->withCount('cases')
            ->whereHas('cases')
            ->orderBy('cases_count', 'desc')
            ->take(5)
            ->get();

        // Station Performance
        $station_performance = Station::when(!$hasFullAccess, function($q) use ($user) {
            return $q->where('id', $user->station_id);
        })->with(['users.cases' => function($query) {
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

        // Recent Activities
        $activities = \App\Models\AuditLog::when(!$hasFullAccess, function($q) use ($user) {
            return $q->where('user_id', $user->id)
                ->orWhereHas('user', function($sq) use ($user) {
                    $sq->where('station_id', $user->station_id);
                });
        })->with('user')
            ->latest()
            ->take(6)
            ->get();

        // Wanted Suspects
        $wanted_suspects = (clone $suspectQuery)->with('crime')
            ->where('arrest_status', 'wanted')
            ->latest()
            ->take(8)
            ->get();

        // Active Deployments
        $active_deployments = \App\Models\Deployment::when(!$hasFullAccess, function($q) use ($user) {
            return $q->where('station_id', $user->station_id);
        })->with(['user', 'station', 'facility'])
            ->where('status', 'on_duty')
            ->latest()
            ->take(6)
            ->get();

        // Facilities Summary
        $facility_stats = \App\Models\Facility::when(!$hasFullAccess, function($q) use ($user) {
            // If station-based, only show facilities in their region/station
            return $q->where('location', 'LIKE', '%' . ($user->station->location ?? '') . '%');
        })->select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get();

        // Upcoming Court Hearings
        $upcoming_hearings = \App\Models\CourtCase::when(!$hasFullAccess, function($q) use ($user) {
            return $q->whereHas('prosecution.case.assignedUser', function($sq) use ($user) {
                $sq->where('station_id', $user->station_id);
            });
        })->with(['prosecution.suspect', 'judge'])
            ->where('hearing_date', '>=', now())
            ->orderBy('hearing_date', 'asc')
            ->take(5)
            ->get();
            
        $court_stats = \App\Models\CourtCase::when(!$hasFullAccess, function($q) use ($user) {
            return $q->whereHas('prosecution.case.assignedUser', function($sq) use ($user) {
                $sq->where('station_id', $user->station_id);
            });
        })->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Recent Evidence Uploads
        $recent_evidence = \App\Models\Evidence::when(!$hasFullAccess, function($q) use ($user) {
            return $q->whereHas('crime.reporter', function($sq) use ($user) {
                $sq->where('station_id', $user->station_id);
            });
        })->with('crime')
            ->latest()
            ->take(6)
            ->get();

        // Extra Stats
        $today_cases = (clone $caseQuery)->whereDate('created_at', now()->today())->count();
        $week_cases = (clone $caseQuery)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $month_cases = (clone $caseQuery)->whereMonth('created_at', now()->month)->count();
        $last_month_cases = (clone $caseQuery)->whereMonth('created_at', now()->subMonth()->month)->count();
        
        // Suspect Gender Stats (Normalized)
        $suspect_gender = (clone $suspectQuery)->select('gender', DB::raw('count(*) as count'))
            ->whereNotNull('gender')
            ->groupBy('gender')
            ->get()
            ->map(function($item) {
                // Normalize gender values
                $gender = strtolower($item->gender);
                if (in_array($gender, ['male', 'lab', 'm'])) {
                    $item->gender = 'Male';
                } elseif (in_array($gender, ['female', 'dhedig', 'f'])) {
                    $item->gender = 'Female';
                }
                return $item;
            })
            ->groupBy('gender')
            ->map(function($group) {
                return $group->sum('count');
            });

        // Crimes by Station
        $crimes_by_station = PoliceCase::when(!$hasFullAccess, function($q) use ($user) {
            return $q->whereHas('assignedUser', function($sq) use ($user) {
                $sq->where('station_id', $user->station_id);
            });
        })->join('users', 'cases.assigned_to', '=', 'users.id')
            ->join('stations', 'users.station_id', '=', 'stations.id')
            ->select('stations.station_name', 'stations.location', DB::raw('count(*) as total'))
            ->groupBy('stations.id', 'stations.station_name', 'stations.location')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        // NEW WIDGETS (10+ Additional)
        
        // 1. Arrest Status Breakdown
        $arrest_status_stats = (clone $suspectQuery)->select('arrest_status', DB::raw('count(*) as count'))
            ->whereNotNull('arrest_status')
            ->groupBy('arrest_status')
            ->get();

        // 2. Crime Severity Levels
        try {
            $crime_severity = (clone $crimeQuery)->select('severity', DB::raw('count(*) as count'))
                ->whereNotNull('severity')
                ->groupBy('severity')
                ->orderByRaw("CASE 
                    WHEN severity = 'Critical' THEN 1
                    WHEN severity = 'High' THEN 2
                    WHEN severity = 'Medium' THEN 3
                    ELSE 4 END")
                ->get();
        } catch (\Exception $e) {
            // If severity column doesn't exist, return empty collection
            $crime_severity = collect([]);
        }

        // 3. Average Response Time (in hours)
        try {
            $avg_response_time = (clone $caseQuery)->whereNotNull('assigned_at')
                ->selectRaw('AVG(EXTRACT(EPOCH FROM (assigned_at - created_at))/3600) as avg_hours')
                ->value('avg_hours') ?? 0;
        } catch (\Exception $e) {
            // If assigned_at column doesn't exist, return 0
            $avg_response_time = 0;
        }

        // 4. Case Age Distribution
        $case_age_distribution = [
            'new' => (clone $caseQuery)->where('created_at', '>=', now()->subDays(7))->count(),
            'recent' => (clone $caseQuery)->whereBetween('created_at', [now()->subDays(30), now()->subDays(7)])->count(),
            'old' => (clone $caseQuery)->whereBetween('created_at', [now()->subDays(90), now()->subDays(30)])->count(),
            'very_old' => (clone $caseQuery)->where('created_at', '<', now()->subDays(90))->count(),
        ];

        // 5. Monthly Comparison (This month vs Last month)
        $monthly_comparison = [
            'current' => $month_cases,
            'previous' => $last_month_cases,
            'change_percent' => $last_month_cases > 0 ? (($month_cases - $last_month_cases) / $last_month_cases) * 100 : 0
        ];

        // 6. Top Crime Types (Detailed)
        $top_crime_types = (clone $crimeQuery)->select('crime_type', DB::raw('count(*) as count'))
            ->groupBy('crime_type')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();

        // 7. Evidence Collection Stats
        $evidence_stats = [
            'total' => \App\Models\Evidence::when(!$hasFullAccess, function($q) use ($user) {
                return $q->whereHas('crime.reporter', function($sq) use ($user) {
                    $sq->where('station_id', $user->station_id);
                });
            })->count(),
            'this_week' => \App\Models\Evidence::when(!$hasFullAccess, function($q) use ($user) {
                return $q->whereHas('crime.reporter', function($sq) use ($user) {
                    $sq->where('station_id', $user->station_id);
                });
            })->where('created_at', '>=', now()->startOfWeek())->count(),
        ];

        // 8. Victim Statistics
        $victim_stats = [
            'total' => \App\Models\Victim::when(!$hasFullAccess, function($q) use ($user) {
                return $q->whereHas('crime.reporter', function($sq) use ($user) {
                    $sq->where('station_id', $user->station_id);
                });
            })->count(),
        ];

        // 9. Officer Workload Distribution
        $officer_workload = User::when(!$hasFullAccess, function($q) use ($user) {
            return $q->where('station_id', $user->station_id);
        })->withCount(['cases' => function($q) {
                $q->whereNotIn('status', ['Xiran', 'Xukunsan', 'Dhamaaday']);
            }])
            ->having('cases_count', '>', 0)
            ->orderBy('cases_count', 'desc')
            ->take(10)
            ->get();

        // 10. Investigation Success Rate by Officer
        $investigation_success = User::when(!$hasFullAccess, function($q) use ($user) {
            return $q->where('station_id', $user->station_id);
        })->withCount([
                'cases as total_cases',
                'cases as solved_cases' => function($q) {
                    $q->whereIn('status', ['Xiran', 'Xukunsan', 'Dhamaaday']);
                }
            ])
            ->having('total_cases', '>', 0)
            ->get()
            ->map(function($officer) {
                $officer->success_rate = $officer->total_cases > 0 
                    ? ($officer->solved_cases / $officer->total_cases) * 100 
                    : 0;
                return $officer;
            })
            ->sortByDesc('success_rate')
            ->take(5);

        // 11. Daily Activity Timeline (Last 7 days)
        $daily_activity = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $daily_activity[] = [
                'date' => $date->format('M d'),
                'cases' => (clone $caseQuery)->whereDate('created_at', $date)->count(),
                'arrests' => (clone $suspectQuery)->where('arrest_status', 'arrested')
                    ->whereDate('updated_at', $date)->count(),
            ];
        }

        // 12. Court Case Outcomes
        $court_outcomes = \App\Models\CourtCase::when(!$hasFullAccess, function($q) use ($user) {
            return $q->whereHas('prosecution.case.assignedUser', function($sq) use ($user) {
                $sq->where('station_id', $user->station_id);
            });
        })->select('verdict', DB::raw('count(*) as count'))
            ->whereNotNull('verdict')
            ->groupBy('verdict')
            ->get();

        // 13. Pending Tasks Summary
        $pending_tasks = [
            'unassigned_cases' => PoliceCase::when(!$hasFullAccess, function($q) use ($user) {
                if ($user->role->slug == 'taliye-saldhig') {
                    return $q->whereHas('crime.reporter', function($sq) use ($user) {
                        $sq->where('station_id', $user->station_id);
                    });
                }
            })->whereNull('assigned_to')->count(),
            'pending_prosecution' => (clone $caseQuery)->where('status', 'Xeer-Ilaalinta')->count(),
            'pending_hearings' => \App\Models\CourtCase::when(!$hasFullAccess, function($q) use ($user) {
                return $q->whereHas('prosecution.case.assignedUser', function($sq) use ($user) {
                    $sq->where('station_id', $user->station_id);
                });
            })->where('hearing_date', '>=', now())
                ->where('status', 'scheduled')
                ->count(),
        ];

        // 14. Regional Crime Heatmap Data
        $regional_crimes = (clone $crimeQuery)->select('location', DB::raw('count(*) as count'))
            ->whereNotNull('location')
            ->groupBy('location')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();

        return view('dashboard.index', compact(
            'stats', 'case_stats', 'my_stats', 'recent_crimes', 
            'stations_with_counts', 'users_by_rank', 'crime_types', 
            'months', 'trend_counts', 'top_officers', 'activities', 
            'station_performance', 'wanted_suspects', 'active_deployments', 'facility_stats',
            'upcoming_hearings', 'recent_evidence',
            'today_cases', 'week_cases', 'month_cases', 'court_stats', 'suspect_gender', 'crimes_by_station',
            'arrest_status_stats', 'crime_severity', 'avg_response_time', 'case_age_distribution',
            'monthly_comparison', 'top_crime_types', 'evidence_stats', 'victim_stats',
            'officer_workload', 'investigation_success', 'daily_activity', 'court_outcomes',
            'pending_tasks', 'regional_crimes'
        ));
    }

    public function markRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }
}
