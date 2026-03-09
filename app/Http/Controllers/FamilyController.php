<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Family;
use App\Models\Household;
use App\Models\Resident;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    public function index()
    {
        $families = Family::with(['household', 'members', 'headResident'])->get();
        return view('families.index', compact('families'));
    }

    public function create()
    {
        $households = Household::orderBy('head_last_name')->get();
        // Only residents not yet assigned to any family
        $residents = Resident::whereNull('family_id')
            ->orderBy('last_name')
            ->get(['id', 'last_name', 'first_name', 'middle_name']);
        return view('families.create', compact('households', 'residents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'family_name'      => 'required|string|max:255',
            'head_resident_id' => 'required|exists:residents,id',
            'household_id'     => 'nullable|exists:households,id',
            'notes'            => 'nullable|string',
            'member_data'      => 'nullable|array',
        ]);

        $resident    = Resident::findOrFail($request->head_resident_id);
        $memberData  = $request->input('member_data', []); // [id => role]
        $memberCount = count($memberData) + 1;

        $family = Family::create([
            'family_name'      => $request->family_name,
            'head_resident_id' => $resident->id,
            'head_last_name'   => $resident->last_name,
            'head_first_name'  => $resident->first_name,
            'head_middle_name' => $resident->middle_name,
            'head_role'        => $request->head_role,
            'household_id'     => $request->household_id,
            'member_count'     => $memberCount,
            'notes'            => $request->notes,
        ]);

        foreach ($memberData as $residentId => $role) {
            Resident::where('id', $residentId)->update([
                'family_id'   => $family->id,
                'family_role' => $role ?: null,
            ]);
        }

        ActivityLog::log('created', 'Family', "Added family: {$family->family_name}");
        return redirect()->route('families.index')->with('success', 'Family added.');
    }

    public function show(Family $family)
    {
        $family->load(['household', 'members', 'headResident']);
        return view('families.show', compact('family'));
    }

    public function edit(Family $family)
    {
        $households = Household::orderBy('head_last_name')->get();
        // Residents not in any family, OR already in THIS family
        $residents = Resident::where(function ($q) use ($family) {
                $q->whereNull('family_id')->orWhere('family_id', $family->id);
            })
            ->orderBy('last_name')
            ->get(['id', 'last_name', 'first_name', 'middle_name']);
        $family->load('members');
        return view('families.edit', compact('family', 'households', 'residents'));
    }

    public function update(Request $request, Family $family)
    {
        $request->validate([
            'family_name'      => 'required|string|max:255',
            'head_resident_id' => 'required|exists:residents,id',
            'household_id'     => 'nullable|exists:households,id',
            'notes'            => 'nullable|string',
            'member_data'      => 'nullable|array',
        ]);

        $resident   = Resident::findOrFail($request->head_resident_id);
        $memberData = $request->input('member_data', []);
        $memberCount = count($memberData) + 1;

        // Clear old member links
        Resident::where('family_id', $family->id)->update(['family_id' => null, 'family_role' => null]);

        // Re-assign with roles
        foreach ($memberData as $residentId => $role) {
            Resident::where('id', $residentId)->update([
                'family_id'   => $family->id,
                'family_role' => $role ?: null,
            ]);
        }

        $family->update([
            'family_name'      => $request->family_name,
            'head_resident_id' => $resident->id,
            'head_last_name'   => $resident->last_name,
            'head_first_name'  => $resident->first_name,
            'head_middle_name' => $resident->middle_name,
            'head_role'        => $request->head_role,
            'household_id'     => $request->household_id,
            'member_count'     => $memberCount,
            'notes'            => $request->notes,
        ]);

        ActivityLog::log('updated', 'Family', "Updated family: {$family->family_name}");
        return redirect()->route('families.index')->with('success', 'Family updated.');
    }

    public function destroy(Family $family)
    {
        Resident::where('family_id', $family->id)->update(['family_id' => null, 'family_role' => null]);
        ActivityLog::log('deleted', 'Family', "Deleted family: {$family->family_name}");
        $family->delete();
        return redirect()->route('families.index')->with('success', 'Family deleted.');
    }
}
