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
        $seniors = (clone $base)->whereRaw("CAST((julianday('now') - julianday(birthdate)) / 365.25 AS INTEGER) >= 60")->count();
        $pwd = (clone $base)->where('is_pwd', true)->count();
        $voters = (clone $base)->where('is_voter', true)->count();
        $soloParents = (clone $base)->where('is_solo_parent', true)->count();
        $minors = (clone $base)->whereRaw("CAST((julianday('now') - julianday(birthdate)) / 365.25 AS INTEGER) < 18")->count();
        $adults = (clone $base)->whereRaw("CAST((julianday('now') - julianday(birthdate)) / 365.25 AS INTEGER) BETWEEN 18 AND 59")->count();

        $civilStatus = (clone $base)->selectRaw('civil_status, count(*) as total')
            ->groupBy('civil_status')
            ->get();

        $bySitio = (clone $base)->selectRaw('address as sitio, count(*) as total')
            ->whereNotNull('address')
            ->where('address', '!=', '')
            ->groupBy('address')
            ->orderByDesc('total')
            ->get();

        $byEducation = (clone $base)->selectRaw('education_level, count(*) as total')
            ->whereNotNull('education_level')
            ->groupBy('education_level')
            ->orderByDesc('total')
            ->get();

        $laborForce = (clone $base)->where('is_labor_force', true)->count();
        $unemployed = (clone $base)->where('is_unemployed', true)->count();
        $ofw = (clone $base)->where('is_ofw', true)->count();
        $indigenous = (clone $base)->where('is_indigenous', true)->count();
        $osc = (clone $base)->where('is_out_of_school_child', true)->count();
        $osy = (clone $base)->where('is_out_of_school_youth', true)->count();
        $student = (clone $base)->where('is_student', true)->count();

        $listFields = ['id', 'first_name', 'last_name', 'middle_name', 'birthdate', 'gender', 'address', 'contact_number'];
        $seniorList = (clone $base)->whereRaw("CAST((julianday('now') - julianday(birthdate)) / 365.25 AS INTEGER) >= 60")->orderBy('last_name')->get($listFields);
        $pwdList = (clone $base)->where('is_pwd', true)->orderBy('last_name')->get($listFields);
        $voterList = (clone $base)->where('is_voter', true)->orderBy('last_name')->get($listFields);
        $soloParentList = (clone $base)->where('is_solo_parent', true)->orderBy('last_name')->get($listFields);
        $minorList = (clone $base)->whereRaw("CAST((julianday('now') - julianday(birthdate)) / 365.25 AS INTEGER) < 18")->orderBy('last_name')->get($listFields);

        $laborForceList = (clone $base)->where('is_labor_force', true)->orderBy('last_name')->get($listFields);
        $unemployedList = (clone $base)->where('is_unemployed', true)->orderBy('last_name')->get($listFields);
        $ofwList = (clone $base)->where('is_ofw', true)->orderBy('last_name')->get($listFields);
        $indigenousList = (clone $base)->where('is_indigenous', true)->orderBy('last_name')->get($listFields);
        $oscList = (clone $base)->where('is_out_of_school_child', true)->orderBy('last_name')->get($listFields);
        $osyList = (clone $base)->where('is_out_of_school_youth', true)->orderBy('last_name')->get($listFields);
        $studentList = (clone $base)->where('is_student', true)->orderBy('last_name')->get($listFields);

        $totalClearances = Clearance::count();
        $totalCertificates = Certificate::count();

        return view('reports', compact(
            'totalResidents', 'male', 'female',
            'seniors', 'pwd', 'voters', 'soloParents', 'minors', 'adults',
            'laborForce', 'unemployed', 'ofw', 'indigenous', 'osc', 'osy', 'student',
            'civilStatus', 'bySitio', 'byEducation',
            'seniorList', 'pwdList', 'voterList', 'soloParentList', 'minorList',
            'laborForceList', 'unemployedList', 'ofwList', 'indigenousList', 'oscList', 'osyList', 'studentList',
            'totalClearances', 'totalCertificates'
        ));
    }
}
