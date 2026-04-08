<?php

namespace App\Http\Controllers;

use App\Exports\ResidentsExport;
use App\Exports\ResidentsRbiExport;
use App\Imports\ResidentsImport;
use App\Models\ActivityLog;
use App\Models\Household;
use App\Models\Resident;
use App\Models\ResidentPendingEdit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ResidentController extends Controller
{
    public function index(Request $request)
    {
        $gender = $request->get('gender', '');
        $civil = $request->get('civil', '');
        $purok = $request->get('purok', '');
        $classification = $request->get('classification', '');
        $sector = $request->get('sector', '');
        $citizenship = $request->get('citizenship', '');
        $residentStatus = $request->get('resident_status', '');
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
        } elseif ($classification === 'solo_parent') {
            $query->where('is_solo_parent', true);
        }
        if ($sector) {
            if ($sector === 'out_of_school_child') {
                $query->where('is_out_of_school_child', true);
            } elseif ($sector === 'out_of_school_youth') {
                $query->where('is_out_of_school_youth', true);
            } elseif ($sector === 'labor_force') {
                // Labor Force filter shows unemployed labor force members only
                $query->where('is_labor_force', true)->where('is_unemployed', true);
            } else {
                $query->where("is_{$sector}", true);
            }
        }
        if ($citizenship) {
            $query->whereRaw('LOWER(nationality) = ?', [strtolower($citizenship)]);
        }
        if ($residentStatus === 'deceased') {
            $query->where('is_deceased', true);
        } elseif ($residentStatus === 'transferred') {
            $query->whereNotNull('transferred_to');
        } elseif ($residentStatus === 'no_household') {
            $query->whereNull('household_id');
        } elseif ($residentStatus === 'no_family') {
            $query->whereNull('family_id');
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
                    ->orWhereRaw('LOWER(middle_name) like ?', ["%{$s}%"])
                    ->orWhereRaw("LOWER(first_name || ' ' || last_name) like ?", ["%{$s}%"])
                    ->orWhereRaw("LOWER(last_name || ' ' || first_name) like ?", ["%{$s}%"])
                    ->orWhereRaw("LOWER(first_name || ' ' || middle_name) like ?", ["%{$s}%"])
                    ->orWhereRaw("LOWER(last_name || ', ' || first_name || ' ' || COALESCE(middle_name, '')) like ?", ["%{$s}%"])
                    ->orWhereRaw("LOWER(first_name || ' ' || COALESCE(middle_name, '') || ' ' || last_name) like ?", ["%{$s}%"])
                    ->orWhereRaw('LOWER(address) like ?', ["%{$s}%"]);
            });
        }

        // When no status filter is active, exclude deceased/transferred from counts.
        // When a status filter is active, the query is already scoped — count as-is.
        $countQuery = $residentStatus
            ? clone $query
            : (clone $query)->where('is_deceased', false)->whereNull('transferred_to');

        $totalResidents = (clone $countQuery)->count();
        $totalSeniors = (clone $countQuery)->whereRaw("CAST((julianday('now') - julianday(birthdate)) / 365.25 AS INTEGER) >= 60")->count();
        $totalPwd = (clone $countQuery)->where('is_pwd', true)->count();
        $residents = (clone $query)->with(['household', 'family'])->orderBy('last_name')->orderBy('first_name')->paginate(20)->withQueryString();

        $pendingResidents = Resident::where('status', 'pending')->latest()->get();
        $pendingEdits = ResidentPendingEdit::with('resident')->latest()->get();

        $filters = compact('gender', 'civil', 'purok', 'classification', 'sector', 'citizenship', 'residentStatus', 'ageMin', 'ageMax', 'search');

        return view('residents.index', compact(
            'residents', 'pendingResidents', 'pendingEdits',
            'totalResidents', 'totalSeniors', 'totalPwd', 'filters'
        ));
    }

    public function create()
    {
        $sitios = ['Chrysanthemum', 'Dahlia', 'Dama de Noche', 'Ilang-Ilang', 'Jasmin', 'Rosal', 'Sampaguita'];

        return view('residents.create', compact('sitios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'household_id' => 'nullable|exists:households,id',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:20',
            'gender' => 'required|in:Male,Female,Other',
            'birthdate' => 'required|date',
            'age' => 'required|integer|min:0|max:120',
            'civil_status' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'resident_type' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
            'place_of_birth' => 'nullable|string|max:255',
            'contact_number' => ['nullable', 'digits:11', 'regex:/^09\d{9}$/'],
            'email' => 'nullable|email|max:255',
            'philsys_number' => 'nullable|string|max:50',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'sitio_name' => 'required|string|max:255',
            'purok' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'employer' => 'nullable|string|max:255',
            'monthly_income' => 'nullable|numeric|min:0|max:9999999',
            'education_level' => 'nullable|string|max:255',
            'education_sub_level' => 'nullable|string|in:Undergraduate,Graduate',
            'is_senior' => 'nullable|boolean',
            'is_pwd' => 'nullable|boolean',
            'is_voter' => 'nullable|boolean',
            'is_solo_parent' => 'nullable|boolean',
            'solo_parent_id_number' => 'nullable|string|max:100',
            'sector' => 'nullable|in:labor_force,unemployed,ofw,indigenous,student',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // Build address from sub-fields then remove them from $validated
        $parts = array_filter([
            $validated['sitio_name'],
            $validated['purok'] ?? null,
        ]);
        $validated['address'] = implode(', ', $parts);
        unset($validated['sitio_name'], $validated['purok']);

        $validated['is_senior'] = ($request->has('is_senior') && $validated['age'] >= 60) ? 1 : 0;
        $validated['is_pwd'] = $request->has('is_pwd') ? 1 : 0;
        $validated['is_voter'] = $request->has('is_voter') ? 1 : 0;
        $validated['is_solo_parent'] = $request->has('is_solo_parent') ? 1 : 0;

        $sector = $validated['sector'] ?? null;
        unset($validated['sector']);
        $validated['is_labor_force'] = $sector === 'labor_force' ? 1 : 0;
        $validated['is_unemployed'] = ($sector === 'unemployed' || $sector === 'labor_force') ? 1 : 0;
        $validated['is_ofw'] = $sector === 'ofw' ? 1 : 0;
        $validated['is_indigenous'] = $sector === 'indigenous' ? 1 : 0;
        $validated['is_student'] = $sector === 'student' ? 1 : 0;
        $validated['is_out_of_school_child'] = (! $validated['is_student'] && $validated['age'] >= 6 && $validated['age'] <= 14) ? 1 : 0;
        $validated['is_out_of_school_youth'] = (! $validated['is_student'] && $validated['age'] >= 15 && $validated['age'] <= 24) ? 1 : 0;
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
        $resident = Resident::with(['household', 'family'])->findOrFail($id);

        return view('residents.show', compact('resident'));
    }

    public function json($id): JsonResponse
    {
        $resident = Resident::with(['household', 'family'])->findOrFail($id);

        return response()->json($resident);
    }

    public function edit($id)
    {
        $resident = Resident::findOrFail($id);
        $sitios = ['Chrysanthemum', 'Dahlia', 'Dama de Noche', 'Ilang-Ilang', 'Jasmin', 'Rosal', 'Sampaguita'];

        return view('residents.edit', compact('resident', 'sitios'));
    }

    public function update(Request $request, $id)
    {
        $resident = Resident::findOrFail($id);

        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:20',
            'gender' => 'required|in:Male,Female,Other',
            'birthdate' => 'required|date',
            'age' => 'required|integer|min:0|max:120',
            'civil_status' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'resident_type' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
            'place_of_birth' => 'nullable|string|max:255',
            'contact_number' => ['nullable', 'digits:11', 'regex:/^09\d{9}$/'],
            'email' => 'nullable|email|max:255',
            'philsys_number' => 'nullable|string|max:50',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'sitio_name' => 'required|string|max:255',
            'purok' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'employer' => 'nullable|string|max:255',
            'monthly_income' => 'nullable|numeric|min:0|max:9999999',
            'education_level' => 'nullable|string|max:255',
            'education_sub_level' => 'nullable|string|in:Undergraduate,Graduate',
            'is_senior' => 'nullable|boolean',
            'is_pwd' => 'nullable|boolean',
            'is_voter' => 'nullable|boolean',
            'is_solo_parent' => 'nullable|boolean',
            'solo_parent_id_number' => 'nullable|string|max:100',
            'sector' => 'nullable|in:labor_force,unemployed,ofw,indigenous,student',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_deceased' => 'nullable|boolean',
            'date_of_death' => 'nullable|date',
            'transferred_to' => 'nullable|string|max:255',
        ]);

        // Build address from sub-fields
        $parts = array_filter([
            $validated['sitio_name'],
            $validated['purok'] ?? null,
        ]);
        $validated['address'] = implode(', ', $parts);
        unset($validated['sitio_name'], $validated['purok']);

        $validated['is_senior'] = ($request->has('is_senior') && $validated['age'] >= 60) ? 1 : 0;
        $validated['is_pwd'] = $request->has('is_pwd') ? 1 : 0;
        $validated['is_voter'] = $request->has('is_voter') ? 1 : 0;
        $validated['is_solo_parent'] = $request->has('is_solo_parent') ? 1 : 0;

        $sector = $validated['sector'] ?? null;
        unset($validated['sector']);
        $validated['is_labor_force'] = $sector === 'labor_force' ? 1 : 0;
        $validated['is_unemployed'] = ($sector === 'unemployed' || $sector === 'labor_force') ? 1 : 0;
        $validated['is_ofw'] = $sector === 'ofw' ? 1 : 0;
        $validated['is_indigenous'] = $sector === 'indigenous' ? 1 : 0;
        $validated['is_student'] = $sector === 'student' ? 1 : 0;
        $validated['is_out_of_school_child'] = (! $validated['is_student'] && $validated['age'] >= 6 && $validated['age'] <= 14) ? 1 : 0;
        $validated['is_out_of_school_youth'] = (! $validated['is_student'] && $validated['age'] >= 15 && $validated['age'] <= 24) ? 1 : 0;
        $validated['is_deceased'] = $request->has('is_deceased') ? 1 : 0;

        if (! $validated['is_deceased']) {
            $validated['date_of_death'] = null;
        }

        if (empty($validated['transferred_to'])) {
            $validated['transferred_to'] = null;
        }

        // Duplicate check: same name + birthdate as another resident
        $duplicate = Resident::whereRaw('LOWER(first_name) = ?', [strtolower($validated['first_name'])])
            ->whereRaw('LOWER(last_name) = ?', [strtolower($validated['last_name'])])
            ->where('birthdate', $validated['birthdate'])
            ->where('id', '!=', $resident->id)
            ->first();

        if ($duplicate) {
            return back()->withInput()->withErrors([
                'first_name' => "A resident named {$duplicate->first_name} {$duplicate->last_name} with the same birthdate already exists in the system (ID #{$duplicate->id}).",
            ]);
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

        $validator = Validator::make($pendingEdit->proposed_data, [
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'birthdate' => 'required|date',
            'age' => 'required|integer|min:0|max:120',
            'monthly_income' => 'nullable|numeric|min:0|max:9999999',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', "Cannot apply edit for {$resident->first_name} {$resident->last_name}: the proposed data contains invalid values. Ask the employee to resubmit.");
        }

        $resident->update($pendingEdit->proposed_data);
        ActivityLog::log('approved_edit', 'Resident', "Approved proposed edit for: {$resident->first_name} {$resident->last_name} (submitted by {$pendingEdit->submitted_by_name})");
        $pendingEdit->delete();

        return redirect()->back()
            ->with('success', "Edit for {$resident->first_name} {$resident->last_name} has been approved and applied.");
    }

    public function rejectEdit($id)
    {
        $pendingEdit = ResidentPendingEdit::findOrFail($id);
        $resident = $pendingEdit->resident;
        $name = "{$resident->first_name} {$resident->last_name}";
        ActivityLog::log('rejected_edit', 'Resident', "Rejected proposed edit for: {$name} (submitted by {$pendingEdit->submitted_by_name})");
        $pendingEdit->delete();

        return redirect()->back()
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

    public function pending()
    {
        $pendingResidents = Resident::where('status', 'pending')->latest()->get();
        $pendingEdits = ResidentPendingEdit::with('resident')->latest()->get();

        return view('residents.pending', compact('pendingResidents', 'pendingEdits'));
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

        return redirect()->back()
            ->with('success', "Resident {$resident->first_name} {$resident->last_name} has been approved and added to records.");
    }

    public function reject($id)
    {
        $resident = Resident::where('status', 'pending')->findOrFail($id);
        $name = "{$resident->first_name} {$resident->last_name}";
        ActivityLog::log('rejected', 'Resident', "Rejected and removed pending resident record: {$name} (ID #{$resident->id})");
        $resident->delete();

        return redirect()->back()
            ->with('success', "Pending record for {$name} has been rejected and removed.");
    }

    public function importForm()
    {
        return view('residents.import');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls,csv|max:10240']);

        set_time_limit(300);

        $import = new ResidentsImport;

        try {
            Excel::import($import, $request->file('file'));
        } catch (\Throwable $e) {
            return redirect()->route('residents.import.form')
                ->with('error', 'Import failed: '.$e->getMessage().' — Try re-saving the file as .xlsx in Excel or Google Sheets first.');
        }

        $imported = $import->dataSheet->imported;
        $updated = $import->dataSheet->updated;
        $skipped = $import->dataSheet->skipped;
        $duplicates = $import->dataSheet->duplicates;

        ActivityLog::log('created', 'Resident', "Bulk imported {$imported} resident(s), updated {$updated} record(s) via Excel.");

        $msg = "Import complete — {$imported} resident(s) added";
        if ($updated > 0) {
            $msg .= ", {$updated} existing record(s) updated from newer spreadsheet data";
        }
        $msg .= ", {$skipped} row(s) skipped.";
        if ($duplicates > 0) {
            $msg .= " ({$duplicates} already up-to-date.)";
        }

        return redirect()->route('residents.index')->with('success', $msg);
    }

    public function export(Request $request)
    {
        $gender = $request->get('gender', '');
        $civil = $request->get('civil', '');
        $purok = $request->get('purok', '');
        $classification = $request->get('classification', '');
        $sector = $request->get('sector', '');
        $citizenship = $request->get('citizenship', '');
        $residentStatus = $request->get('resident_status', '');
        $ageMin = $request->filled('age_min') ? (int) $request->age_min : null;
        $ageMax = $request->filled('age_max') ? (int) $request->age_max : null;
        $search = $request->get('search', '');

        $query = Resident::where('status', 'approved')->with('household');

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
        } elseif ($classification === 'solo_parent') {
            $query->where('is_solo_parent', true);
        }
        if ($sector) {
            if ($sector === 'out_of_school_child') {
                $query->where('is_out_of_school_child', true);
            } elseif ($sector === 'out_of_school_youth') {
                $query->where('is_out_of_school_youth', true);
            } elseif ($sector === 'labor_force') {
                $query->where('is_labor_force', true)->where('is_unemployed', true);
            } else {
                $query->where("is_{$sector}", true);
            }
        }
        if ($citizenship) {
            $query->whereRaw('LOWER(nationality) = ?', [strtolower($citizenship)]);
        }
        if ($residentStatus === 'deceased') {
            $query->where('is_deceased', true);
        } elseif ($residentStatus === 'transferred') {
            $query->whereNotNull('transferred_to');
        } elseif ($residentStatus === 'no_household') {
            $query->whereNull('household_id');
        } elseif ($residentStatus === 'no_family') {
            $query->whereNull('family_id');
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
                    ->orWhereRaw('LOWER(middle_name) like ?', ["%{$s}%"])
                    ->orWhereRaw('LOWER(address) like ?', ["%{$s}%"]);
            });
        }

        $query->orderBy('last_name')->orderBy('first_name');

        $format = $request->get('format', 'xlsx');
        if ($format === 'csv') {
            $filename = 'residents_'.now()->format('Y-m-d_His').'.csv';
            ActivityLog::log('exported', 'Resident', 'Exported residents list to CSV.');

            return Excel::download(new ResidentsExport($query), $filename, \Maatwebsite\Excel\Excel::CSV);
        }

        $filename = 'residents_'.now()->format('Y-m-d_His').'.xlsx';
        ActivityLog::log('exported', 'Resident', 'Exported residents list to Excel.');

        return Excel::download(new ResidentsExport($query), $filename);
    }

    public function exportRbi(Request $request)
    {
        $gender = $request->get('gender', '');
        $civil = $request->get('civil', '');
        $purok = $request->get('purok', '');
        $classification = $request->get('classification', '');
        $sector = $request->get('sector', '');
        $citizenship = $request->get('citizenship', '');
        $residentStatus = $request->get('resident_status', '');
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
        } elseif ($classification === 'solo_parent') {
            $query->where('is_solo_parent', true);
        }
        if ($sector) {
            if ($sector === 'out_of_school_child') {
                $query->where('is_out_of_school_child', true);
            } elseif ($sector === 'out_of_school_youth') {
                $query->where('is_out_of_school_youth', true);
            } elseif ($sector === 'labor_force') {
                $query->where('is_labor_force', true)->where('is_unemployed', true);
            } else {
                $query->where("is_{$sector}", true);
            }
        }
        if ($citizenship) {
            $query->whereRaw('LOWER(nationality) = ?', [strtolower($citizenship)]);
        }
        if ($residentStatus === 'deceased') {
            $query->where('is_deceased', true);
        } elseif ($residentStatus === 'transferred') {
            $query->whereNotNull('transferred_to');
        } elseif ($residentStatus === 'no_household') {
            $query->whereNull('household_id');
        } elseif ($residentStatus === 'no_family') {
            $query->whereNull('family_id');
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
                    ->orWhereRaw('LOWER(middle_name) like ?', ["%{$s}%"])
                    ->orWhereRaw('LOWER(address) like ?', ["%{$s}%"]);
            });
        }

        $query->orderBy('household_id')->orderBy('last_name')->orderBy('first_name');

        ActivityLog::log('exported', 'Resident', 'Exported residents list in RBI format.');

        return (new ResidentsRbiExport($query))->download();
    }

    public function suggest(Request $request): JsonResponse
    {
        $q = strtolower(trim($request->get('q', '')));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $residents = Resident::where('status', 'approved')
            ->where(function ($query) use ($q) {
                $query->whereRaw('LOWER(first_name) like ?', ["%{$q}%"])
                    ->orWhereRaw('LOWER(last_name) like ?', ["%{$q}%"])
                    ->orWhereRaw('LOWER(address) like ?', ["%{$q}%"]);
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->limit(8)
            ->get(['first_name', 'last_name', 'address']);

        $suggestions = $residents->map(fn ($r) => [
            'label' => $r->last_name.', '.$r->first_name,
            'value' => $r->last_name.', '.$r->first_name,
            'meta' => $r->address,
        ]);

        return response()->json($suggestions);
    }

    public function location()
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
            $query = Resident::query();
        } else {
            $ids = $request->input('ids', []);
            if (empty($ids)) {
                return redirect()->back()->with('error', 'No residents selected.');
            }
            $query = Resident::whereIn('id', $ids);
        }

        $residents = $query->get(['id', 'household_id', 'first_name', 'last_name']);
        $householdIds = $residents->pluck('household_id')->filter()->unique();
        $count = $residents->count();

        if ($count === 0) {
            return redirect()->back()->with('error', 'No residents found.');
        }

        $residentIds = $residents->pluck('id')->all();
        Resident::whereIn('id', $residentIds)->delete();

        ActivityLog::log('deleted', 'Resident', "Bulk deleted {$count} resident(s).");

        foreach ($householdIds as $householdId) {
            $remaining = Resident::where('household_id', $householdId)->where('status', 'approved')->count();
            Household::where('id', $householdId)->update(['member_count' => $remaining]);
        }

        return redirect()->route('residents.index')
            ->with('success', $count.' resident(s) deleted successfully.');
    }
}
