<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    /**
     * Show list of residents
     */
    public function index()
    {
        $residents = Resident::latest()->get();
        return view('residents.index', compact('residents'));
    }

    /**
     * Show add resident form
     */
    public function create()
    {
        return view('residents.create');
    }

    /**
     * Store resident data
     * ✅ stays on same page
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            // ================= PERSONAL INFO =================
            'last_name'      => 'required|string|max:255',
            'first_name'     => 'required|string|max:255',
            'middle_name'    => 'nullable|string|max:255',

            'gender'         => 'required|in:Male,Female,Other',
            'birthdate'      => 'required|date',
            'age'            => 'required|integer|min:0|max:120',

            'civil_status'   => 'nullable|string|max:255',
            'nationality'    => 'nullable|string|max:255',
            'religion'       => 'nullable|string|max:255',

            // ================= CONTACT INFO =================
            'contact_number' => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:255',

            // ================= ADDRESS =================
            'province'       => 'required|string|max:255',
            'city'           => 'required|string|max:255',
            'barangay'       => 'required|string|max:255',
            'address'        => 'required|string|max:255',

            // ================= SOCIO-ECONOMIC =================
            'occupation'     => 'nullable|string|max:255',
            'employer'       => 'nullable|string|max:255',
            'monthly_income' => 'nullable|numeric|min:0',
            'education_level'=> 'nullable|string|max:255',

            // ================= SPECIAL CLASSIFICATION =================
            'is_senior'      => 'nullable|boolean',
            'is_pwd'         => 'nullable|boolean',
            'is_voter'       => 'nullable|boolean',

            // ================= GEOLOCATION =================
            'latitude'       => 'required|numeric|between:-90,90',
            'longitude'      => 'required|numeric|between:-180,180',
        ]);

        Resident::create($validated);

        // ✅ STAY ON SAME PAGE
        return back()->with('success', 'Resident record added successfully.');
    }

    /**
     * Show resident locations on map
     */
    public function location()
    {
        $residents = Resident::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('residents.location', compact('residents'));
    }
}


