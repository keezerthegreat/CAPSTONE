<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Resident;
use App\Models\ResidentPendingEdit;
use App\Models\Household;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    public function index()
    {
        $residents        = Resident::with('household')->where('status', 'approved')->latest()->get();
        $pendingResidents = Resident::where('status', 'pending')->latest()->get();
        $pendingEdits     = ResidentPendingEdit::with('resident')->latest()->get();
        return view('residents.index', compact('residents', 'pendingResidents', 'pendingEdits'));
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
        $validated['status']    = 'pending';

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

        // Admin applies changes directly; employee sends for verification
        if (auth()->user()->role === 'admin') {
            $resident->update($validated);
            ActivityLog::log('updated', 'Resident', "Updated resident: {$resident->first_name} {$resident->last_name}");

            return redirect()->route('residents.index')
                ->with('success', 'Resident record updated successfully.');
        }

        // Employee: store as pending edit — live record is unchanged
        ResidentPendingEdit::create([
            'resident_id'       => $resident->id,
            'proposed_data'     => $validated,
            'submitted_by_id'   => auth()->id(),
            'submitted_by_name' => auth()->user()->name,
        ]);
        ActivityLog::log('proposed_edit', 'Resident', "Proposed edit submitted for: {$resident->first_name} {$resident->last_name} — awaiting admin approval");

        return redirect()->route('residents.index')
            ->with('success', 'Your changes have been submitted and are pending admin approval. The resident record will not change until approved.');
    }

    public function approveEdit($id)
    {
        $pendingEdit = ResidentPendingEdit::findOrFail($id);
        $resident    = $pendingEdit->resident;
        $resident->update($pendingEdit->proposed_data);
        ActivityLog::log('approved_edit', 'Resident', "Approved proposed edit for: {$resident->first_name} {$resident->last_name} (submitted by {$pendingEdit->submitted_by_name})");
        $pendingEdit->delete();

        return redirect()->route('residents.index')
            ->with('success', "Edit for {$resident->first_name} {$resident->last_name} has been approved and applied.");
    }

    public function rejectEdit($id)
    {
        $pendingEdit = ResidentPendingEdit::findOrFail($id);
        $resident    = $pendingEdit->resident;
        $name        = "{$resident->first_name} {$resident->last_name}";
        ActivityLog::log('rejected_edit', 'Resident', "Rejected proposed edit for: {$name} (submitted by {$pendingEdit->submitted_by_name})");
        $pendingEdit->delete();

        return redirect()->route('residents.index')
            ->with('success', "Proposed edit for {$name} has been rejected.");
    }

    public function destroy($id)
    {
        $resident = Resident::findOrFail($id);
        ActivityLog::log('deleted', 'Resident', "Deleted resident: {$resident->first_name} {$resident->last_name}");
        $resident->delete();

        return redirect()->route('residents.index')
            ->with('success', 'Resident deleted successfully!');
    }

    public function approve($id)
    {
        $resident = Resident::where('status', 'pending')->findOrFail($id);
        $resident->update(['status' => 'approved']);
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

    public function location()
    {
        $households = \App\Models\Household::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with('members')
            ->get();

        return view('residents.location', compact('households'));
    }
}