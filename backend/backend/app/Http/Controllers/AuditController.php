<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        // Build query
        $query = AuditLog::with('user');

        // Filter by action type
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('table_name', 'like', "%{$search}%")
                  ->orWhere('details', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Get paginated logs
        $logs = $query->latest()->paginate(20);

        // Statistics
        $totalLogs = AuditLog::count();
        $todayLogs = AuditLog::whereDate('created_at', Carbon::today())->count();
        
        $actionStats = AuditLog::selectRaw('action, count(*) as total')
            ->groupBy('action')
            ->get()
            ->pluck('total', 'action');

        $topUsers = AuditLog::selectRaw('user_id, count(*) as total')
            ->groupBy('user_id')
            ->with('user')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Get all users for filter dropdown
        $users = User::orderBy('name')->get();

        return view('audit.index', compact('logs', 'totalLogs', 'todayLogs', 'actionStats', 'topUsers', 'users'));
    }

    public function export(Request $request)
    {
        $filename = "audit_logs_" . date('Y-m-d') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['SOMALI NATIONAL POLICE - AUDIT LOGS REPORT']);
            fputcsv($file, ['Generated: ' . date('Y-m-d H:i:s')]);
            fputcsv($file, []);
            
            // Column headers
            fputcsv($file, ['User', 'Action', 'Resource', 'Record ID', 'Details', 'Timestamp']);
            
            // Data
            $logs = AuditLog::with('user')->latest()->get();
            foreach($logs as $log) {
                fputcsv($file, [
                    $log->user->name ?? 'N/A',
                    $log->action,
                    $log->table_name,
                    $log->record_id,
                    $log->details,
                    $log->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
