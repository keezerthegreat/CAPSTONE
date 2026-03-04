<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\Clearance;
use App\Models\Certificate;

class ReportsController extends Controller
{
    public function index()
    {
        // General stats
        $totalResidents = Resident::count();
        $male           = Resident::where('gender', 'Male')->count();
        $female         = Resident::where('gender', 'Female')->count();
        $seniors        = Resident::where('is_senior', true)->count();
        $pwd            = Resident::where('is_pwd', true)->count();
        $voters         = Resident::where('is_voter', true)->count();
        $minors         = Resident::where('age', '<', 18)->count();
        $adults         = Resident::whereBetween('age', [18, 59])->count();

        // Civil status breakdown
        $civilStatus = Resident::selectRaw('civil_status, count(*) as total')
            ->groupBy('civil_status')
            ->get();

        // Sitio breakdown
        $bySitio = Resident::selectRaw('barangay, count(*) as total')
            ->groupBy('barangay')
            ->orderByDesc('total')
            ->get();

        // Education breakdown
        $byEducation = Resident::selectRaw('education_level, count(*) as total')
            ->whereNotNull('education_level')
            ->groupBy('education_level')
            ->orderByDesc('total')
            ->get();

        // Senior citizens list
        $seniorList = Resident::where('is_senior', true)->orderBy('last_name')->get();

        // PWD list
        $pwdList = Resident::where('is_pwd', true)->orderBy('last_name')->get();

        // Voters list
        $voterList = Resident::where('is_voter', true)->orderBy('last_name')->get();

        // Minors list
        $minorList = Resident::where('age', '<', 18)->orderBy('last_name')->get();

        // Documents
        $totalClearances   = Clearance::count();
        $totalCertificates = Certificate::count();

        return view('reports', compact(
            'totalResidents', 'male', 'female',
            'seniors', 'pwd', 'voters', 'minors', 'adults',
            'civilStatus', 'bySitio', 'byEducation',
            'seniorList', 'pwdList', 'voterList', 'minorList',
            'totalClearances', 'totalCertificates'
        ));
    }
}