<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Family;
use App\Models\Household;
use App\Models\Resident;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $sitio = $request->get('sitio', '');

        $query = Family::with(['household', 'members', 'headResident']);

        if ($search) {
            $s = strtolower($search);
            $query->where(function ($q) use ($s) {
                $q->whereRaw('LOWER(family_name) like ?', ["%{$s}%"])
                    ->orWhereRaw('LOWER(head_last_name) like ?', ["%{$s}%"])
                    ->orWhereRaw('LOWER(head_first_name) like ?', ["%{$s}%"]);
            });
        }
        if ($sitio === '__none__') {
            $query->whereNull('household_id');
        } elseif ($sitio) {
            $query->whereHas('household', function ($q) use ($sitio) {
                $q->whereRaw('LOWER(sitio) like ?', ['%'.strtolower($sitio).'%']);
            });
        }

        $totalFamilies = Family::count();
        $totalMembers = Family::sum('member_count');
        $totalLinked = Family::whereNotNull('household_id')->count();

        $families = $query->paginate(20)->withQueryString();
        $filters = compact('search', 'sitio');

        return view('families.index', compact(
            'families', 'filters',
            'totalFamilies', 'totalMembers', 'totalLinked'
        ));
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
            'family_name' => 'required|string|max:255',
            'head_resident_id' => 'required|exists:residents,id',
            'head_role' => 'nullable|string|max:100',
            'household_id' => 'nullable|exists:households,id',
            'notes' => 'nullable|string',
            'member_data' => 'nullable|array',
            'member_data.*' => 'nullable|string|max:100',
        ]);

        $resident = Resident::findOrFail($request->head_resident_id);
        // Strip the head from memberData to prevent double-counting
        $memberData = collect($request->input('member_data', []))
            ->reject(fn ($role, $residentId) => (int) $residentId === (int) $request->head_resident_id)
            ->all();
        $memberCount = count($memberData) + 1; // +1 for the head

        // Duplicate check: same head resident already heads a family
        $headDuplicate = Family::where('head_resident_id', $resident->id)->first();
        if ($headDuplicate) {
            return back()->withInput()->withErrors([
                'head_resident_id' => "{$resident->first_name} {$resident->last_name} is already the head of family \"{$headDuplicate->family_name}\".",
            ]);
        }

        // Duplicate check: same family name already exists
        $nameDuplicate = Family::whereRaw('LOWER(family_name) = ?', [strtolower($request->family_name)])->first();
        if ($nameDuplicate) {
            return back()->withInput()->withErrors([
                'family_name' => "A family named \"{$nameDuplicate->family_name}\" already exists in the system.",
            ]);
        }

        $family = Family::create([
            'family_name' => $request->family_name,
            'head_resident_id' => $resident->id,
            'head_last_name' => $resident->last_name,
            'head_first_name' => $resident->first_name,
            'head_middle_name' => $resident->middle_name,
            'head_role' => $request->head_role,
            'household_id' => $request->household_id,
            'member_count' => $memberCount,
            'notes' => $request->notes,
        ]);

        // Always set family_id on the head resident
        Resident::where('id', $resident->id)->update(['family_id' => $family->id]);

        foreach ($memberData as $residentId => $role) {
            Resident::where('id', $residentId)->update([
                'family_id' => $family->id,
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
            'family_name' => 'required|string|max:255',
            'head_resident_id' => 'required|exists:residents,id',
            'head_role' => 'nullable|string|max:100',
            'household_id' => 'nullable|exists:households,id',
            'notes' => 'nullable|string',
            'member_data' => 'nullable|array',
            'member_data.*' => 'nullable|string|max:100',
        ]);

        $resident = Resident::findOrFail($request->head_resident_id);
        // Strip the head from memberData to prevent double-counting
        $memberData = collect($request->input('member_data', []))
            ->reject(fn ($role, $residentId) => (int) $residentId === (int) $request->head_resident_id)
            ->all();
        $memberCount = count($memberData) + 1; // +1 for the head

        // Clear old member links
        Resident::where('family_id', $family->id)->update(['family_id' => null, 'family_role' => null]);

        // Always set family_id on the head resident
        Resident::where('id', $resident->id)->update(['family_id' => $family->id]);

        // Re-assign with roles
        foreach ($memberData as $residentId => $role) {
            Resident::where('id', $residentId)->update([
                'family_id' => $family->id,
                'family_role' => $role ?: null,
            ]);
        }

        $family->update([
            'family_name' => $request->family_name,
            'head_resident_id' => $resident->id,
            'head_last_name' => $resident->last_name,
            'head_first_name' => $resident->first_name,
            'head_middle_name' => $resident->middle_name,
            'head_role' => $request->head_role,
            'household_id' => $request->household_id,
            'member_count' => $memberCount,
            'notes' => $request->notes,
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

    public function bulkDestroy(Request $request)
    {
        if ($request->input('select_all')) {
            $families = Family::all();
        } else {
            $ids = $request->input('ids', []);
            if (empty($ids)) {
                return redirect()->back()->with('error', 'No families selected.');
            }
            $families = Family::whereIn('id', $ids)->get();
        }

        $count = $families->count();
        foreach ($families as $family) {
            Resident::where('family_id', $family->id)->update(['family_id' => null, 'family_role' => null]);
            ActivityLog::log('deleted', 'Family', "Bulk deleted family: {$family->family_name}");
            $family->delete();
        }

        return redirect()->route('families.index')
            ->with('success', $count.' family/families deleted successfully.');
    }
}
