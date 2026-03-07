<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\Household;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    public function index()
    {
        $residents = Resident::latest()->get();
        return view('residents.index', compact('residents'));
    }

    public function create()
    {
        return view('residents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'last_name'       => 'required|string|max:255',
            'first_name'      => 'required|string|max:255',
            'middle_name'     => 'nullable|string|max:255',
            'gender'          => 'required|in:Male,Female,Other',
            'birthdate'       => 'required|date',
            'age'             => 'required|integer|min:0|max:120',
            'civil_status'    => 'nullable|string|max:255',
            'nationality'     => 'nullable|string|max:255',
            'religion'        => 'nullable|string|max:255',
            'contact_number'  => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            'province'        => 'required|string|max:255',
            'city'            => 'required|string|max:255',
            'barangay'        => 'required|string|max:255',
            'address'         => 'required|string|max:255',
            'occupation'      => 'nullable|string|max:255',
            'employer'        => 'nullable|string|max:255',
            'monthly_income'  => 'nullable|numeric|min:0',
            'education_level' => 'nullable|string|max:255',
            'is_senior'       => 'nullable|boolean',
            'is_pwd'          => 'nullable|boolean',
            'is_voter'        => 'nullable|boolean',
            'latitude'        => 'nullable|numeric',
            'longitude'       => 'nullable|numeric',
        ]);

        $validated['is_senior'] = $request->has('is_senior') ? 1 : 0;
        $validated['is_pwd']    = $request->has('is_pwd')    ? 1 : 0;
        $validated['is_voter']  = $request->has('is_voter')  ? 1 : 0;

        Resident::create($validated);

        return redirect()->route('residents.index')
            ->with('success', 'Resident record added successfully.');
    }

    public function show($id)
    {
        $resident = Resident::findOrFail($id);
        return view('residents.show', compact('resident'));
    }

    public function edit($id)
    {
        $resident = Resident::findOrFail($id);
        return view('residents.edit', compact('resident'));
    }

    public function update(Request $request, $id)
    {
        $resident = Resident::findOrFail($id);

        $validated = $request->validate([
            'last_name'       => 'required|string|max:255',
            'first_name'      => 'required|string|max:255',
            'middle_name'     => 'nullable|string|max:255',
            'gender'          => 'required|in:Male,Female,Other',
            'birthdate'       => 'required|date',
            'age'             => 'required|integer|min:0|max:120',
            'civil_status'    => 'nullable|string|max:255',
            'nationality'     => 'nullable|string|max:255',
            'religion'        => 'nullable|string|max:255',
            'contact_number'  => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            'province'        => 'required|string|max:255',
            'city'            => 'required|string|max:255',
            'barangay'        => 'required|string|max:255',
            'address'         => 'required|string|max:255',
            'occupation'      => 'nullable|string|max:255',
            'employer'        => 'nullable|string|max:255',
            'monthly_income'  => 'nullable|numeric|min:0',
            'education_level' => 'nullable|string|max:255',
            'is_senior'       => 'nullable|boolean',
            'is_pwd'          => 'nullable|boolean',
            'is_voter'        => 'nullable|boolean',
            'latitude'        => 'nullable|numeric',
            'longitude'       => 'nullable|numeric',
            'is_deceased'     => 'nullable|boolean',
            'date_of_death'   => 'nullable|date',
        ]);

        $validated['is_senior']   = $request->has('is_senior')   ? 1 : 0;
        $validated['is_pwd']      = $request->has('is_pwd')      ? 1 : 0;
        $validated['is_voter']    = $request->has('is_voter')    ? 1 : 0;
        $validated['is_deceased'] = $request->has('is_deceased') ? 1 : 0;

        if (!$validated['is_deceased']) {
            $validated['date_of_death'] = null;
        }

        $resident->update($validated);

        return redirect()->route('residents.index')
            ->with('success', 'Resident record updated successfully.');
    }

    public function destroy($id)
    {
        $resident = Resident::findOrFail($id);
        $resident->delete();

        return redirect()->route('residents.index')
            ->with('success', 'Resident deleted successfully!');
    }

    public function location()
    {
        $households = \App\Models\Household::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('residents.location', compact('households'));
    }
}