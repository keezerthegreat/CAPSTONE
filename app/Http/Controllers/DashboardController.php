<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\Clearance;
use App\Models\Family;
use App\Models\Household;

class DashboardController extends Controller
{
    public function index()
    {
        $living = Resident::where('is_deceased', false);

        $totalResidents  = $living->count();
        $male            = (clone $living)->where('gender', 'Male')->count();
        $female          = (clone $living)->where('gender', 'Female')->count();
        $seniors         = (clone $living)->where('is_senior', true)->count();
        $pwd             = (clone $living)->where('is_pwd', true)->count();
        $voters          = (clone $living)->where('is_voter', true)->count();
        $minors          = (clone $living)->where('age', '<', 18)->count();
        $adults          = (clone $living)->whereBetween('age', [18, 59])->count();
        $clearances      = Clearance::count();
        $totalFamilies   = Family::count();
        $totalHouseholds = Household::count();

        $civilStatus = [
            'Single'    => (clone $living)->where('civil_status', 'Single')->count(),
            'Married'   => (clone $living)->where('civil_status', 'Married')->count(),
            'Widowed'   => (clone $living)->where('civil_status', 'Widowed')->count(),
            'Separated' => (clone $living)->where('civil_status', 'Separated')->count(),
            'Annulled'  => (clone $living)->where('civil_status', 'Annulled')->count(),
            'Live-in'   => (clone $living)->where('civil_status', 'Live-in')->count(),
        ];

        $recentResidents = Resident::where('is_deceased', false)->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalResidents', 'male', 'female', 'seniors', 'pwd', 'voters',
            'minors', 'adults', 'clearances', 'totalFamilies', 'totalHouseholds',
            'civilStatus', 'recentResidents'
        ));
    }
}