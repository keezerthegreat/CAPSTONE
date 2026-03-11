<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Clearance;
use App\Models\Resident;

class ReportsController extends Controller
{
    public function index()
    {
        $base = Resident::where('status', 'approved')->where('is_deceased', false);

        $totalResidents = (clone $base)->count();
        $male = (clone $base)->where('gender', 'Male')->count();
        $female = (clone $base)->where('gender', 'Female')->count();
        $seniors = (clone $base)->where('age', '>=', 60)->count();
        $pwd = (clone $base)->where('is_pwd', true)->count();
        $voters = (clone $base)->where('is_voter', true)->count();
        $minors = (clone $base)->where('age', '<', 18)->count();
        $adults = (clone $base)->whereBetween('age', [18, 59])->count();

        $civilStatus = (clone $base)->selectRaw('civil_status, count(*) as total')
            ->groupBy('civil_status')
            ->get();

        $bySitio = (clone $base)->selectRaw('barangay, count(*) as total')
            ->groupBy('barangay')
            ->orderByDesc('total')
            ->get();

        $byEducation = (clone $base)->selectRaw('education_level, count(*) as total')
            ->whereNotNull('education_level')
            ->groupBy('education_level')
            ->orderByDesc('total')
            ->get();

        $seniorList = (clone $base)->where('age', '>=', 60)->orderBy('last_name')->get();
        $pwdList = (clone $base)->where('is_pwd', true)->orderBy('last_name')->get();
        $voterList = (clone $base)->where('is_voter', true)->orderBy('last_name')->get();
        $minorList = (clone $base)->where('age', '<', 18)->orderBy('last_name')->get();

        $totalClearances = Clearance::count();
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
