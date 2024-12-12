<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// app/Http/Controllers/ReportController.php
class ReportController extends Controller
{
    public function dailyReport()
    {
        $dailyReport = Attendance::selectRaw('DATE(attended_at) as date, COUNT(*) as entries')
            ->groupBy('date')
            ->get();

        return response()->json($dailyReport);
    }

    public function monthlyReport()
    {
        $monthlyReport = Attendance::selectRaw('COUNT(*) as entries, DAY(attended_at) as day')
            ->whereMonth('attended_at', now()->month)
            ->groupBy('day')
            ->get();

        return response()->json($monthlyReport);
    }
}
