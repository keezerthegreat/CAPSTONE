<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Clearance;
use App\Models\Resident;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ClearanceController extends Controller
{
    public function index()
    {
        $clearances = Clearance::latest()->get();
        $residents = Resident::where('is_deceased', false)
            ->orderBy('last_name')->orderBy('first_name')
            ->get(['id', 'last_name', 'first_name', 'middle_name', 'address', 'barangay']);
        return view('pages.clearance', compact('clearances', 'residents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'resident_name' => 'required|string|max:255',
            'certificate_type' => 'required|string|max:255',
            'purpose' => 'required|string|max:255',
        ]);

        Clearance::create([
            'clearance_no' => 'CLR-' . now()->format('Y') . '-' . rand(1000, 9999),
            'resident_name' => $request->resident_name,
            'certificate_type' => $request->certificate_type,
            'purpose' => $request->purpose,
            'date_issued' => Carbon::now(),
        ]);
        ActivityLog::log('created', 'Clearance', "Issued clearance for: {$request->resident_name} ({$request->certificate_type})");

        return redirect()->back()->with('success', 'Barangay Clearance issued successfully.');
    }

    public function destroy($id)
    {
        $clearance = Clearance::findOrFail($id);
        ActivityLog::log('deleted', 'Clearance', "Deleted clearance for: {$clearance->resident_name}");
        $clearance->delete();
        return redirect()->back()->with('success', 'Clearance deleted.');
    }

    public function print($id)
    {
        $clearance = Clearance::findOrFail($id);
        return view('pages.clearance-print', compact('clearance'));
    }

    // ✅ ADD THIS
    public function edit($id)
    {
        $clearance = Clearance::findOrFail($id);
        return view('pages.clearance-edit', compact('clearance'));
    }

    // ✅ ADD THIS
    public function update(Request $request, $id)
    {
        $request->validate([
            'resident_name' => 'required|string|max:255',
            'certificate_type' => 'required|string|max:255',
            'purpose' => 'required|string|max:255',
        ]);

        $clearance = Clearance::findOrFail($id);

        $clearance->update([
            'resident_name' => $request->resident_name,
            'certificate_type' => $request->certificate_type,
            'purpose' => $request->purpose,
        ]);
        ActivityLog::log('updated', 'Clearance', "Updated clearance for: {$request->resident_name}");

        return redirect()->route('clearance.index')
            ->with('success', 'Clearance updated successfully.');
    }
}