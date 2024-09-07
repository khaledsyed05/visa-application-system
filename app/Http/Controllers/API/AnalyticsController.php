<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        $totalApplications = Application::count();

        $applicationsByStatus = Application::groupBy('status')
            ->select('status', DB::raw('count(*) as total'))
            ->get();

        $applicationsByDestination = Application::groupBy('destination_id')
            ->select('destination_id', DB::raw('count(*) as total'))
            ->with('destination')
            ->get();

        $applicationsByVisaType = Application::groupBy('visa_type_id')
            ->select('visa_type_id', DB::raw('count(*) as total'))
            ->with('visaType')
            ->get();

        $applicationsTrend = Application::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as total')
        )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $last30DaysApplications = Application::where('created_at', '>=', Carbon::now()->subDays(30))
            ->count();

        return response()->json([
            'total_applications' => $totalApplications,
            'applications_by_status' => $applicationsByStatus,
            'applications_by_destination' => $applicationsByDestination,
            'applications_by_visa_type' => $applicationsByVisaType,
            'applications_trend' => $applicationsTrend,
            'last_30_days_applications' => $last30DaysApplications,
        ]);
    }}
