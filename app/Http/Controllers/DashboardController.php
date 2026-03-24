<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Clearance;
use App\Models\Family;
use App\Models\Household;
use App\Models\Resident;

class DashboardController extends Controller
{
    public function index()
    {
        $living = Resident::where('is_deceased', false);

        $totalResidents = $living->count();
        $male = (clone $living)->where('gender', 'Male')->count();
        $female = (clone $living)->where('gender', 'Female')->count();
        $seniors = (clone $living)->where('is_senior', true)->count();
        $pwd = (clone $living)->where('is_pwd', true)->count();
        $voters = (clone $living)->where('is_voter', true)->count();
        $soloParents = (clone $living)->where('is_solo_parent', true)->count();
        $minors = (clone $living)->where('age', '<', 18)->count();
        $adults = (clone $living)->whereBetween('age', [18, 59])->count();
        $clearances = Clearance::count();
        $totalFamilies = Family::count();
        $totalHouseholds = Household::count();

        $civilStatus = [
            'Single' => (clone $living)->where('civil_status', 'Single')->count(),
            'Married' => (clone $living)->where('civil_status', 'Married')->count(),
            'Widowed' => (clone $living)->where('civil_status', 'Widowed')->count(),
            'Separated' => (clone $living)->where('civil_status', 'Separated')->count(),
        ];

        $recentLogs = ActivityLog::latest()->take(8)->get();

        // Residents per sitio (via resident's own address field)
        $bySitio = Resident::where('is_deceased', false)
            ->whereNotNull('address')
            ->where('address', '!=', '')
            ->selectRaw('address as sitio, COUNT(*) as total')
            ->groupBy('address')
            ->orderByDesc('total')
            ->get();

        // Residents with no sitio address
        $noSitio = Resident::where('is_deceased', false)
            ->where(function ($q) {
                $q->whereNull('address')->orWhere('address', '');
            })
            ->count();

        $validSitios = ['Chrysanthemum', 'Dahlia', 'Dama de Noche', 'Ilang-Ilang', 'Ilang-Ilang 1', 'Ilang-Ilang 2', 'Jasmin', 'Rosal', 'Sampaguita'];

        // Households per sitio
        $householdsBySitio = Household::selectRaw('sitio, COUNT(*) as total')
            ->whereIn('sitio', $validSitios)
            ->groupBy('sitio')
            ->orderByDesc('total')
            ->get();

        // Families per sitio (via household)
        $familiesBySitio = Family::join('households', 'families.household_id', '=', 'households.id')
            ->selectRaw('households.sitio, COUNT(*) as total')
            ->whereIn('households.sitio', $validSitios)
            ->groupBy('households.sitio')
            ->orderByDesc('total')
            ->get();

        // Households by residency type
        $householdsByType = [
            'Residential' => Household::where('residency_type', 'Residential')->count(),
            'Commercial' => Household::where('residency_type', 'Commercial')->count(),
            'Rented' => Household::where('residency_type', 'Rented')->count(),
        ];

        return view('dashboard', compact(
            'totalResidents', 'male', 'female', 'seniors', 'pwd', 'voters', 'soloParents',
            'minors', 'adults', 'clearances', 'totalFamilies', 'totalHouseholds',
            'civilStatus', 'recentLogs', 'bySitio', 'noSitio', 'householdsBySitio', 'householdsByType', 'familiesBySitio'
        ));
    }
}
