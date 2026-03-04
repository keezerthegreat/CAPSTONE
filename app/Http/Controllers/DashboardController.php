<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\Clearance;

class DashboardController extends Controller
{
    public function index()
    {
        $totalResidents  = Resident::count();
        $male            = Resident::where('gender', 'Male')->count();
        $female          = Resident::where('gender', 'Female')->count();
        $seniors         = Resident::where('is_senior', true)->count();
        $pwd             = Resident::where('is_pwd', true)->count();
        $voters          = Resident::where('is_voter', true)->count();
        $minors          = Resident::where('age', '<', 18)->count();
        $adults          = Resident::whereBetween('age', [18, 59])->count();
        $clearances      = Clearance::count();
        $recentResidents = Resident::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalResidents',
            'male',
            'female',
            'seniors',
            'pwd',
            'voters',
            'minors',
            'adults',
            'clearances',
            'recentResidents'
        ));
    }
}