<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    /**
     * Display list of residents
     */
    public function index()
    {
        $residents = Resident::latest()->get();
        return view('residents.index', compact('residents'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('residents.create');
    }

    /**
     * Store new resident
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            // Personal Info
            'last_name'      => 'required|string|max:255',
            'first_name'     => 'required|string|max:255',
            'middle_name'    => 'nullable|string|max:255',
            'gender'         => 'required|in:Male,Female,Other',
            'birthdate'      => 'required|date',
            'age'            => 'required|integer|min:0|max:120',
            'civil_status'   => 'nullable|string|max:255',
            'nationality'    => 'nullable|string|max:255',
            'religion'       => 'nullable|string|max:255',

            // Contact
            'contact_number' => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:255',

            // Address
            'province'       => 'required|string|max:255',
            'city'           => 'required|string|max:255',
            'barangay'       => 'required|string|max:255',
            'address'        => 'required|string|max:255',

            // Socio-economic
            'occupation'     => 'nullable|string|max:255',
            'employer'       => 'nullable|string|max:255',
            'monthly_income' => 'nullable|numeric|min:0',
            'education_level'=> 'nullable|string|max:255',

            // Classification
            'is_senior'      => 'nullable|boolean',
            'is_pwd'         => 'nullable|boolean',
            'is_voter'       => 'nullable|boolean',
        ]);

        Resident::create($validated);

        return redirect()
            ->route('residents.index')
            ->with('success', 'Resident record added successfully.');
    }

    /**
     * Delete resident (Stable version)
     */
    public function destroy($id)
    {
        $resident = Resident::findOrFail($id);
        $resident->delete();

        return redirect()
            ->route('residents.index')
            ->with('success', 'Resident deleted successfully!');
    }
    
}