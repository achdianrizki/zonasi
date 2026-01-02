<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Village;
use App\Models\Visitor;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $visitorsCount   = Visitor::count();
        $schoolsCount    = School::count();
        $districtsCount  = District::count();
        $villagesCount   = Village::count();

        $visitorChart = Visitor::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total')
        )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->get();

        return view('dashboard', compact(
            'visitorsCount',
            'schoolsCount',
            'districtsCount',
            'villagesCount',
            'visitorChart'
        ));
    }
}
