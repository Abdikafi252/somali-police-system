<?php

namespace App\Http\Controllers;

use App\Models\Crime;
use App\Models\PoliceCase;
use App\Models\User;
use App\Models\Facility;
use App\Models\Deployment;
use App\Models\Suspect;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Date range filtering
        $startDate = $request->input('start_date', Carbon::now()->subMonths(6)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Overview Statistics
        $totalCrimes = Crime::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalCases = PoliceCase::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalSuspects = Suspect::whereBetween('created_at', [$startDate, $endDate])->count();
        $activeCases = PoliceCase::where('status', 'open')->count();

        // Crime Types Distribution
        $crime_types = Crime::select('crime_type as type', \DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('crime_type')
            ->orderBy('total', 'desc')
            ->get();

        // Crime Trends (Monthly)
        $monthQuery = \DB::getDriverName() === 'sqlite' 
            ? "strftime('%Y-%m', created_at)" 
            : "DATE_FORMAT(created_at, '%Y-%m')";

        $crimeTrends = Crime::selectRaw("$monthQuery as month, count(*) as total")
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Case Status Distribution
        $caseStatus = PoliceCase::select('status', \DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status')
            ->get();

        // Officer Workload
        $officer_workload = PoliceCase::select('assigned_to', \DB::raw('count(*) as total'))
            ->whereNotNull('assigned_to')
            ->with('assignedOfficer')
            ->groupBy('assigned_to')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // Facility Coverage
        $facility_coverage = Facility::withCount('deployments')
            ->orderBy('deployments_count', 'desc')
            ->get();

        // Suspect Demographics (Gender)
        $suspectGender = Suspect::select('gender', \DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('gender')
            ->get();

        // Case Resolution Rate
        $resolvedCases = PoliceCase::where('status', 'closed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $resolutionRate = $totalCases > 0 ? round(($resolvedCases / $totalCases) * 100, 1) : 0;

        return view('reports.index', compact(
            'crime_types',
            'officer_workload',
            'facility_coverage',
            'totalCrimes',
            'totalCases',
            'totalSuspects',
            'activeCases',
            'crimeTrends',
            'caseStatus',
            'suspectGender',
            'resolutionRate',
            'startDate',
            'endDate'
        ));
    }

    public function export(Request $request)
    {
        $format = $request->input('format', 'csv');
        
        if ($format === 'csv') {
            return $this->exportCSV();
        } elseif ($format === 'pdf') {
            return $this->exportPDF();
        } elseif ($format === 'excel') {
            return $this->exportExcel();
        }

        return redirect()->back()->with('error', 'Invalid export format');
    }

    private function exportCSV()
    {
        $filename = "somali_police_report_" . date('Y-m-d') . ".csv";
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
            fputcsv($file, ['SOMALI NATIONAL POLICE - COMPREHENSIVE REPORT']);
            fputcsv($file, ['Generated: ' . date('Y-m-d H:i:s')]);
            fputcsv($file, []);
            
            // Crime Statistics
            fputcsv($file, ['CRIME STATISTICS']);
            fputcsv($file, ['Crime Type', 'Total Cases']);
            $crimes = Crime::select('crime_type as type', \DB::raw('count(*) as total'))
                ->groupBy('crime_type')
                ->get();
            foreach($crimes as $c) {
                fputcsv($file, [$c->type, $c->total]);
            }
            fputcsv($file, []);

            // Case Statistics
            fputcsv($file, ['CASE STATISTICS']);
            fputcsv($file, ['Status', 'Total Cases']);
            $cases = PoliceCase::select('status', \DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get();
            foreach($cases as $case) {
                fputcsv($file, [$case->status, $case->total]);
            }
            fputcsv($file, []);

            // Officer Workload
            fputcsv($file, ['OFFICER WORKLOAD']);
            fputcsv($file, ['Officer Name', 'Assigned Cases']);
            $workload = PoliceCase::select('assigned_to', \DB::raw('count(*) as total'))
                ->whereNotNull('assigned_to')
                ->with('assignedOfficer')
                ->groupBy('assigned_to')
                ->get();
            foreach($workload as $work) {
                fputcsv($file, [$work->assignedOfficer->name ?? 'N/A', $work->total]);
            }
            fputcsv($file, []);

            // Facility Coverage
            fputcsv($file, ['FACILITY COVERAGE']);
            fputcsv($file, ['Facility Name', 'Type', 'Deployed Officers', 'Status']);
            $facilities = Facility::withCount('deployments')->get();
            foreach($facilities as $facility) {
                fputcsv($file, [
                    $facility->name,
                    $facility->type,
                    $facility->deployments_count,
                    $facility->deployments_count > 0 ? 'COVERED' : 'UNGUARDED'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportPDF()
    {
        // Note: This requires barryvdh/laravel-dompdf package
        // For now, return a simple message
        return redirect()->back()->with('info', 'PDF export requires DomPDF package installation');
    }

    private function exportExcel()
    {
        // Note: This requires maatwebsite/excel package
        // For now, return CSV as fallback
        return $this->exportCSV();
    }
}
