<?php

namespace App\Http\Controllers;

use App\Imports\ResidentsImport;
use App\Models\ActivityLog;
use App\Models\Household;
use App\Models\Resident;
use App\Models\ResidentPendingEdit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ResidentController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $gender = $request->get('gender', '');
        $civil = $request->get('civil', '');
        $purok = $request->get('purok', '');
        $classification = $request->get('classification', '');
        $ageMin = $request->filled('age_min') ? (int) $request->age_min : null;
        $ageMax = $request->filled('age_max') ? (int) $request->age_max : null;
        $search = $request->get('search', '');

        $query = Resident::where('status', 'approved');

        if ($gender) {
            $query->where('gender', $gender);
        }
        if ($civil) {
            $query->whereRaw('LOWER(civil_status) = ?', [strtolower($civil)]);
        }
        if ($purok) {
            $query->where('address', 'like', ucfirst(strtolower($purok)).'%');
        }
        if ($classification === 'senior') {
            $query->where('age', '>=', 60);
        } elseif ($classification === 'pwd') {
            $query->where('is_pwd', true);
        } elseif ($classification === 'voter') {
            $query->where('is_voter', true);
        }
        if ($ageMin !== null) {
            $query->where('age', '>=', $ageMin);
        }
        if ($ageMax !== null) {
            $query->where('age', '<=', $ageMax);
        }
        if ($search) {
            $s = strtolower($search);
            $query->where(function ($q) use ($s) {
                $q->whereRaw('LOWER(first_name) like ?', ["%{$s}%"])
                    ->orWhereRaw('LOWER(last_name) like ?', ["%{$s}%"])
                    ->orWhereRaw('LOWER(address) like ?', ["%{$s}%"]);
            });
        }

        $totalResidents = (clone $query)->where('is_deceased', false)->count();
        $totalSeniors = (clone $query)->where('is_deceased', false)->where('age', '>=', 60)->count();
        $totalPwd = (clone $query)->where('is_deceased', false)->where('is_pwd', true)->count();
        $residents = (clone $query)->with('household')->orderBy('last_name')->orderBy('first_name')->paginate(50)->withQueryString();

        $pendingResidents = Resident::where('status', 'pending')->latest()->get();
        $pendingEdits = ResidentPendingEdit::with('resident')->latest()->get();

        $filters = compact('gender', 'civil', 'purok', 'classification', 'ageMin', 'ageMax', 'search');

        return view('residents.index', compact(
            'residents', 'pendingResidents', 'pendingEdits',
            'totalResidents', 'totalSeniors', 'totalPwd', 'filters'
        ));
    }

    public function create()
    {
        $sitios = ['Chrysanthemum', 'Dahlia', 'Dama de Noche', 'Ilang-Ilang 1', 'Ilang-Ilang 2', 'Jasmin', 'Rosal', 'Sampaguita'];

        return view('residents.create', compact('sitios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'household_id' => 'nullable|exists:households,id',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'birthdate' => 'required|date',
            'age' => 'required|integer|min:0|max:120',
            'civil_status' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'philsys_number' => 'nullable|string|max:50',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'sitio_name' => 'required|string|max:255',
            'purok' => 'nullable|string|max:100',
            'street_no' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'employer' => 'nullable|string|max:255',
            'monthly_income' => 'nullable|numeric|min:0|max:9999999',
            'education_level' => 'nullable|string|max:255',
            'is_senior' => 'nullable|boolean',
            'is_pwd' => 'nullable|boolean',
            'is_voter' => 'nullable|boolean',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // Build address from sub-fields then remove them from $validated
        $parts = array_filter([
            $validated['sitio_name'],
            ! empty($validated['purok']) ? 'Purok '.$validated['purok'] : null,
            $validated['street_no'] ?? null,
        ]);
        $validated['address'] = implode(', ', $parts);
        unset($validated['sitio_name'], $validated['purok'], $validated['street_no']);

        $validated['is_senior'] = ($request->has('is_senior') && $validated['age'] >= 60) ? 1 : 0;
        $validated['is_pwd'] = $request->has('is_pwd') ? 1 : 0;
        $validated['is_voter'] = $request->has('is_voter') ? 1 : 0;
        $validated['status'] = 'pending';

        // Duplicate check: same name + birthdate already in the system
        $duplicate = Resident::whereRaw('LOWER(first_name) = ?', [strtolower($validated['first_name'])])
            ->whereRaw('LOWER(last_name) = ?', [strtolower($validated['last_name'])])
            ->where('birthdate', $validated['birthdate'])
            ->first();

        if ($duplicate) {
            return back()->withInput()->withErrors([
                'first_name' => "A resident named {$duplicate->first_name} {$duplicate->last_name} with the same birthdate already exists in the system (ID #{$duplicate->id}).",
            ]);
        }

        $resident = Resident::create($validated);

        ActivityLog::log('submitted', 'Resident', "Submitted for verification: {$resident->first_name} {$resident->last_name} — awaiting admin approval");

        return redirect()->route('residents.index')
            ->with('success', 'Resident record submitted and is pending admin verification.');
    }

    public function show($id)
    {
        $resident = Resident::findOrFail($id);

        return view('residents.show', compact('resident'));
    }

    public function edit($id)
    {
        $resident = Resident::findOrFail($id);
        $sitios = ['Chrysanthemum', 'Dahlia', 'Dama de Noche', 'Ilang-Ilang 1', 'Ilang-Ilang 2', 'Jasmin', 'Rosal', 'Sampaguita'];

        return view('residents.edit', compact('resident', 'sitios'));
    }

    public function update(Request $request, $id)
    {
        $resident = Resident::findOrFail($id);

        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'birthdate' => 'required|date',
            'age' => 'required|integer|min:0|max:120',
            'civil_status' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'philsys_number' => 'nullable|string|max:50',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'sitio_name' => 'required|string|max:255',
            'purok' => 'nullable|string|max:100',
            'street_no' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'employer' => 'nullable|string|max:255',
            'monthly_income' => 'nullable|numeric|min:0|max:9999999',
            'education_level' => 'nullable|string|max:255',
            'is_senior' => 'nullable|boolean',
            'is_pwd' => 'nullable|boolean',
            'is_voter' => 'nullable|boolean',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_deceased' => 'nullable|boolean',
            'date_of_death' => 'nullable|date',
        ]);

        // Build address from sub-fields
        $parts = array_filter([
            $validated['sitio_name'],
            ! empty($validated['purok']) ? 'Purok '.$validated['purok'] : null,
            $validated['street_no'] ?? null,
        ]);
        $validated['address'] = implode(', ', $parts);
        unset($validated['sitio_name'], $validated['purok'], $validated['street_no']);

        $validated['is_senior'] = ($request->has('is_senior') && $validated['age'] >= 60) ? 1 : 0;
        $validated['is_pwd'] = $request->has('is_pwd') ? 1 : 0;
        $validated['is_voter'] = $request->has('is_voter') ? 1 : 0;
        $validated['is_deceased'] = $request->has('is_deceased') ? 1 : 0;

        if (! $validated['is_deceased']) {
            $validated['date_of_death'] = null;
        }

        // Admin applies changes directly; employee sends for verification
        if (auth()->user()->role === 'admin') {
            $resident->update($validated);
            ActivityLog::log('updated', 'Resident', "Updated resident: {$resident->first_name} {$resident->last_name}");

            return redirect()->route('residents.index')
                ->with('success', 'Resident record updated successfully.');
        }

        // Employee: store as pending edit — live record is unchanged
        ResidentPendingEdit::create([
            'resident_id' => $resident->id,
            'proposed_data' => $validated,
            'submitted_by_id' => auth()->id(),
            'submitted_by_name' => auth()->user()->name,
        ]);
        ActivityLog::log('proposed_edit', 'Resident', "Proposed edit submitted for: {$resident->first_name} {$resident->last_name} — awaiting admin approval");

        return redirect()->route('residents.index')
            ->with('success', 'Your changes have been submitted and are pending admin approval. The resident record will not change until approved.');
    }

    public function approveEdit($id)
    {
        $pendingEdit = ResidentPendingEdit::findOrFail($id);
        $resident = $pendingEdit->resident;

        $validator = \Illuminate\Support\Facades\Validator::make($pendingEdit->proposed_data, [
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'birthdate' => 'required|date',
            'age' => 'required|integer|min:0|max:120',
            'monthly_income' => 'nullable|numeric|min:0|max:9999999',
        ]);

        if ($validator->fails()) {
            return redirect()->route('residents.index')
                ->with('error', "Cannot apply edit for {$resident->first_name} {$resident->last_name}: the proposed data contains invalid values. Ask the employee to resubmit.");
        }

        $resident->update($pendingEdit->proposed_data);
        ActivityLog::log('approved_edit', 'Resident', "Approved proposed edit for: {$resident->first_name} {$resident->last_name} (submitted by {$pendingEdit->submitted_by_name})");
        $pendingEdit->delete();

        return redirect()->route('residents.index')
            ->with('success', "Edit for {$resident->first_name} {$resident->last_name} has been approved and applied.");
    }

    public function rejectEdit($id)
    {
        $pendingEdit = ResidentPendingEdit::findOrFail($id);
        $resident = $pendingEdit->resident;
        $name = "{$resident->first_name} {$resident->last_name}";
        ActivityLog::log('rejected_edit', 'Resident', "Rejected proposed edit for: {$name} (submitted by {$pendingEdit->submitted_by_name})");
        $pendingEdit->delete();

        return redirect()->route('residents.index')
            ->with('success', "Proposed edit for {$name} has been rejected.");
    }

    public function destroy($id)
    {
        $resident = Resident::findOrFail($id);
        $householdId = $resident->household_id;
        ActivityLog::log('deleted', 'Resident', "Deleted resident: {$resident->first_name} {$resident->last_name}");
        $resident->delete();

        if ($householdId) {
            $count = Resident::where('household_id', $householdId)->where('status', 'approved')->count();
            Household::where('id', $householdId)->update(['member_count' => $count]);
        }

        return redirect()->route('residents.index')
            ->with('success', 'Resident deleted successfully!');
    }

    public function approve($id)
    {
        $resident = Resident::where('status', 'pending')->findOrFail($id);
        $resident->update(['status' => 'approved']);

        if ($resident->household_id) {
            $count = Resident::where('household_id', $resident->household_id)->where('status', 'approved')->count();
            Household::where('id', $resident->household_id)->update(['member_count' => $count]);
        }

        ActivityLog::log('approved', 'Resident', "Approved resident record: {$resident->first_name} {$resident->last_name} (ID #{$resident->id})");

        return redirect()->route('residents.index')
            ->with('success', "Resident {$resident->first_name} {$resident->last_name} has been approved and added to records.");
    }

    public function reject($id)
    {
        $resident = Resident::where('status', 'pending')->findOrFail($id);
        $name = "{$resident->first_name} {$resident->last_name}";
        ActivityLog::log('rejected', 'Resident', "Rejected and removed pending resident record: {$name} (ID #{$resident->id})");
        $resident->delete();

        return redirect()->route('residents.index')
            ->with('success', "Pending record for {$name} has been rejected and removed.");
    }

    public function importForm()
    {
        return view('residents.import');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls,csv|max:10240']);

        $import = new ResidentsImport;

        try {
            Excel::import($import, $request->file('file'));
        } catch (\Throwable $e) {
            return redirect()->route('residents.import.form')
                ->with('error', 'Import failed: '.$e->getMessage().' — Try re-saving the file as .xlsx in Excel or Google Sheets first.');
        }

        // Get counts from the inner DataSheetImport
        $sheets = $import->sheets();
        $dataSheet = $sheets['DATA'] ?? null;
        $imported   = $dataSheet ? $dataSheet->imported   : 0;
        $skipped    = $dataSheet ? $dataSheet->skipped     : 0;
        $duplicates = $dataSheet ? $dataSheet->duplicates  : 0;

        ActivityLog::log('created', 'Resident', "Bulk imported {$imported} resident(s) via Excel.");

        $msg = "Import complete — {$imported} resident(s) added, {$skipped} row(s) skipped.";
        if ($duplicates > 0) {
            $msg .= " ({$duplicates} duplicate(s) skipped.)";
        }

        return redirect()->route('residents.index')->with('success', $msg);
    }

    public function location()
    {
        $households = \App\Models\Household::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with('members')
            ->get();

        return view('residents.location', compact('households'));
    }
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No residents selected.');
        }

        $residents = Resident::whereIn('id', $ids)->get();
        $householdIds = $residents->pluck('household_id')->filter()->unique();

        foreach ($residents as $resident) {
            ActivityLog::log('deleted', 'Resident', "Bulk deleted resident: {$resident->first_name} {$resident->last_name}");
            $resident->delete();
        }

        foreach ($householdIds as $householdId) {
            $count = Resident::where('household_id', $householdId)->where('status', 'approved')->count();
            Household::where('id', $householdId)->update(['member_count' => $count]);
        }

        return redirect()->route('residents.index')
            ->with('success', count($ids) . ' resident(s) deleted successfully.');
    }

}