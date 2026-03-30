<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Household;
use App\Models\Resident;
use Illuminate\Http\Request;

class HouseholdController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $sitio = $request->get('sitio', '');
        $residency = $request->get('residency', '');

        $query = Household::with('members');

        if ($search) {
            $s = strtolower($search);
            $query->where(function ($q) use ($s) {
                $q->whereRaw('LOWER(head_last_name) like ?', ["%{$s}%"])
                    ->orWhereRaw('LOWER(head_first_name) like ?', ["%{$s}%"])
                    ->orWhereRaw('LOWER(household_number) like ?', ["%{$s}%"]);
            });
        }
        if ($sitio) {
            $query->whereRaw('LOWER(sitio) like ?', ['%'.strtolower($sitio).'%']);
        }
        if ($residency) {
            $query->where('residency_type', $residency);
        }

        $totalHouseholds = Household::count();
        $totalResidential = Household::where('residency_type', 'Residential')->count();
        $totalCommercial = Household::where('residency_type', 'Commercial')->count();
        $totalRented = Household::where('residency_type', 'Rented')->count();
        $totalMembers = Household::sum('member_count');

        $households = $query->latest()->paginate(20)->withQueryString();
        $filters = compact('search', 'sitio', 'residency');

        return view('households.index', compact(
            'households', 'filters',
            'totalHouseholds', 'totalResidential', 'totalCommercial', 'totalRented', 'totalMembers'
        ));
    }

    public function create()
    {
        $residents = Resident::where('status', 'approved')->orderBy('last_name')->get(['id', 'last_name', 'first_name', 'middle_name']);

        return view('households.create', compact('residents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'household_number' => 'required|unique:households',
            'head_resident_id' => 'required|exists:residents,id',
            'sitio' => 'required',
            'members' => 'nullable|array',
            'members.*' => 'exists:residents,id',
        ]);

        $resident = Resident::findOrFail($request->head_resident_id);

        // Strip the head from the members array to prevent double-counting
        $memberIds = collect($request->members ?? [])
            ->filter(fn ($id) => (int) $id !== (int) $request->head_resident_id)
            ->values()
            ->all();
        $memberCount = count($memberIds) + 1; // +1 for the head

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
        $data['head_last_name'] = $resident->last_name;
        $data['head_first_name'] = $resident->first_name;
        $data['head_middle_name'] = $resident->middle_name;
        $data['member_count'] = $memberCount;

        $household = Household::create($data);

        // Always link household_id to the head and all members
        Resident::where('id', $request->head_resident_id)->update(['household_id' => $household->id]);
        if (! empty($memberIds)) {
            Resident::whereIn('id', $memberIds)->update(['household_id' => $household->id]);
        }

        ActivityLog::log('created', 'Household', "Added household: #{$household->household_number}");

        return redirect()->route('households.index')
            ->with('success', 'Household added successfully.');
    }

    public function show($id)
    {
        $household = Household::with(['members', 'members.family'])->findOrFail($id);

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
            'household_number' => 'required|unique:households,household_number,'.$id,
            'head_resident_id' => 'required|exists:residents,id',
            'sitio' => 'required',
            'members' => 'nullable|array',
            'members.*' => 'exists:residents,id',
        ]);

        $resident = Resident::findOrFail($request->head_resident_id);

        // Strip the head from the members array to prevent double-counting
        $memberIds = collect($request->members ?? [])
            ->filter(fn ($id) => (int) $id !== (int) $request->head_resident_id)
            ->values()
            ->all();
        $memberCount = count($memberIds) + 1; // +1 for the head

        // Find residents being transferred in from other households, so we can fix those households' counts
        $newResidentIds = array_merge([$request->head_resident_id], $memberIds);
        $affectedHouseholdIds = Resident::whereIn('id', $newResidentIds)
            ->whereNotNull('household_id')
            ->where('household_id', '!=', $household->id)
            ->pluck('household_id')
            ->unique()
            ->all();

        // Clear old member links for this household, then reassign head + members
        Resident::where('household_id', $household->id)->update(['household_id' => null]);
        Resident::where('id', $request->head_resident_id)->update(['household_id' => $household->id]);
        if (! empty($memberIds)) {
            Resident::whereIn('id', $memberIds)->update(['household_id' => $household->id]);
        }

        // Recalculate member counts for any other households that lost residents
        foreach ($affectedHouseholdIds as $affectedId) {
            $count = Resident::where('household_id', $affectedId)->count();
            Household::where('id', $affectedId)->update(['member_count' => $count]);
        }

        $data = $request->only([
            'household_number', 'head_resident_id', 'sitio', 'street',
            'barangay', 'city', 'province', 'residency_type', 'latitude', 'longitude', 'notes',
        ]);
        $data['head_last_name'] = $resident->last_name;
        $data['head_first_name'] = $resident->first_name;
        $data['head_middle_name'] = $resident->middle_name;
        $data['member_count'] = $memberCount;

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

        $households = Household::whereRaw('LOWER(barangay) LIKE ?', ['%'.strtolower($barangay).'%'])
            ->get(['id', 'household_number', 'head_first_name', 'head_last_name', 'sitio', 'street', 'barangay', 'member_count']);

        return response()->json($households);
    }

    public function map()
    {
        $households = Household::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with('members')
            ->get();

        return view('residents.location', compact('households'));
    }

    public function bulkDestroy(Request $request)
    {
        if ($request->input('select_all')) {
            $households = Household::all();
        } else {
            $ids = $request->input('ids', []);
            if (empty($ids)) {
                return redirect()->back()->with('error', 'No households selected.');
            }
            $households = Household::whereIn('id', $ids)->get();
        }

        $count = $households->count();
        foreach ($households as $household) {
            Resident::where('household_id', $household->id)->update(['household_id' => null]);
            ActivityLog::log('deleted', 'Household', "Bulk deleted household: #{$household->household_number}");
            $household->delete();
        }

        return redirect()->route('households.index')
            ->with('success', $count.' household(s) deleted successfully.');
    }
}
