<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Household;
use App\Models\Resident;
use Illuminate\Http\Request;

class HouseholdController extends Controller
{
    public function index()
    {
        $households = Household::with('members')->latest()->get();
        return view('households.index', compact('households'));
    }

    public function create()
    {
        $residents = Resident::where('status', 'approved')->orderBy('last_name')->get(['id', 'last_name', 'first_name', 'middle_name']);
        return view('households.create', compact('residents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'household_number'  => 'required|unique:households',
            'head_resident_id'  => 'required|exists:residents,id',
            'sitio'             => 'required',
            'members'           => 'nullable|array',
            'members.*'         => 'exists:residents,id',
        ]);

        $resident    = Resident::findOrFail($request->head_resident_id);
        $memberCount = count($request->members ?? []) + 1;

        // Duplicate check: same head resident already heads a household
        $headDuplicate = Household::where('head_resident_id', $resident->id)->first();
        if ($headDuplicate) {
            return back()->withInput()->withErrors([
                'head_resident_id' => "{$resident->first_name} {$resident->last_name} is already the head of household #{$headDuplicate->household_number}.",
            ]);
        }

        $data = $request->only([
            'household_number', 'head_resident_id', 'sitio', 'street',
            'barangay', 'city', 'province', 'residency_type', 'latitude', 'longitude', 'notes',
        ]);
        $data['head_last_name']   = $resident->last_name;
        $data['head_first_name']  = $resident->first_name;
        $data['head_middle_name'] = $resident->middle_name;
        $data['member_count']     = $memberCount;

        $household = Household::create($data);

        if ($request->filled('members')) {
            Resident::whereIn('id', $request->members)->update(['household_id' => $household->id]);
        }

        ActivityLog::log('created', 'Household', "Added household: #{$household->household_number}");

        return redirect()->route('households.index')
            ->with('success', 'Household added successfully.');
    }

    public function show($id)
    {
        $household = Household::with('members')->findOrFail($id);
        return view('households.show', compact('household'));
    }

    public function edit($id)
    {
        $household = Household::with('members')->findOrFail($id);
        $residents = Resident::where('status', 'approved')->orderBy('last_name')->get(['id', 'last_name', 'first_name', 'middle_name']);
        return view('households.edit', compact('household', 'residents'));
    }

    public function update(Request $request, $id)
    {
        $household = Household::findOrFail($id);

        $request->validate([
            'household_number' => 'required|unique:households,household_number,' . $id,
            'head_resident_id' => 'required|exists:residents,id',
            'sitio'            => 'required',
            'members'          => 'nullable|array',
            'members.*'        => 'exists:residents,id',
        ]);

        $resident    = Resident::findOrFail($request->head_resident_id);
        $memberCount = count($request->members ?? []) + 1;

        // Clear old member links then reassign
        Resident::where('household_id', $household->id)->update(['household_id' => null]);
        if ($request->filled('members')) {
            Resident::whereIn('id', $request->members)->update(['household_id' => $household->id]);
        }

        $data = $request->only([
            'household_number', 'head_resident_id', 'sitio', 'street',
            'barangay', 'city', 'province', 'residency_type', 'latitude', 'longitude', 'notes',
        ]);
        $data['head_last_name']   = $resident->last_name;
        $data['head_first_name']  = $resident->first_name;
        $data['head_middle_name'] = $resident->middle_name;
        $data['member_count']     = $memberCount;

        $household->update($data);
        ActivityLog::log('updated', 'Household', "Updated household: #{$household->household_number}");

        return redirect()->route('households.index')
            ->with('success', 'Household updated successfully.');
    }

    public function destroy($id)
    {
        $household = Household::findOrFail($id);
        Resident::where('household_id', $household->id)->update(['household_id' => null]);
        ActivityLog::log('deleted', 'Household', "Deleted household: #{$household->household_number}");
        $household->delete();

        return redirect()->route('households.index')
            ->with('success', 'Household deleted successfully.');
    }

    public function search(Request $request)
    {
        $barangay = trim($request->input('barangay', ''));
        if (strlen($barangay) < 2) {
            return response()->json([]);
        }

        $households = Household::whereRaw('LOWER(barangay) LIKE ?', ['%' . strtolower($barangay) . '%'])
            ->get(['id', 'household_number', 'head_first_name', 'head_last_name', 'sitio', 'street', 'barangay', 'member_count']);

        return response()->json($households);
    }

    public function map()
    {
        $households = Household::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('households.map', compact('households'));
    }
}
