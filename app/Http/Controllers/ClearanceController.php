<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Clearance;
use App\Models\Resident;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClearanceController extends Controller
{
    public function index()
    {
        $clearances = Clearance::latest()->paginate(20)->withQueryString();
        $totalClearances = Clearance::count();
        $monthClearances = Clearance::whereMonth('date_issued', now()->month)
            ->whereYear('date_issued', now()->year)->count();
        $residents = Resident::where('status', 'approved')->where('is_deceased', false)
            ->orderBy('last_name')->orderBy('first_name')
            ->get(['id', 'last_name', 'first_name', 'middle_name', 'address', 'barangay']);

        return view('pages.clearance', compact('clearances', 'residents', 'totalClearances', 'monthClearances'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'resident_name' => 'required|string|max:255',
            'certificate_type' => 'required|string|max:255',
            'civil_status' => 'nullable|string|max:50',
            'purok' => 'nullable|string|max:255',
            'requestor' => 'nullable|string|max:255',
            'purpose' => 'nullable|string|max:500',
            'body_content' => 'nullable|string',
            'or_number' => 'nullable|string|max:100',
            'amount' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $year = now()->format('Y');
            $seq = Clearance::whereYear('date_issued', $year)->count() + 1;
            $clrNo = 'CLR-'.$year.'-'.str_pad($seq, 4, '0', STR_PAD_LEFT);

            Clearance::create([
                'clearance_no' => $clrNo,
                'resident_name' => $request->resident_name,
                'civil_status' => $request->civil_status,
                'purok' => $request->purok,
                'requestor' => $request->requestor,
                'certificate_type' => $request->certificate_type,
                'purpose' => $request->purpose,
                'body_content' => $request->body_content,
                'or_number' => $request->or_number,
                'amount' => $request->amount,
                'date_issued' => Carbon::now(),
            ]);
        });
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
            'civil_status' => 'nullable|string|max:50',
            'purok' => 'nullable|string|max:255',
            'requestor' => 'nullable|string|max:255',
            'purpose' => 'nullable|string|max:500',
            'body_content' => 'nullable|string',
            'or_number' => 'nullable|string|max:100',
            'amount' => 'nullable|numeric|min:0',
        ]);

        $clearance = Clearance::findOrFail($id);

        $clearance->update([
            'resident_name' => $request->resident_name,
            'civil_status' => $request->civil_status,
            'purok' => $request->purok,
            'requestor' => $request->requestor,
            'certificate_type' => $request->certificate_type,
            'purpose' => $request->purpose,
            'body_content' => $request->body_content,
            'or_number' => $request->or_number,
            'amount' => $request->amount,
        ]);
        ActivityLog::log('updated', 'Clearance', "Updated clearance for: {$request->resident_name}");

        return redirect()->route('clearance.index')
            ->with('success', 'Clearance updated successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        if ($request->input('select_all')) {
            $clearances = Clearance::all();
        } else {
            $ids = $request->input('ids', []);
            if (empty($ids)) {
                return redirect()->back()->with('error', 'No clearances selected.');
            }
            $clearances = Clearance::whereIn('id', $ids)->get();
        }

        $count = $clearances->count();
        foreach ($clearances as $clr) {
            ActivityLog::log('deleted', 'Clearance', "Bulk deleted clearance: {$clr->clearance_no} for {$clr->resident_name}");
            $clr->delete();
        }

        return redirect()->route('clearance.index')
            ->with('success', $count.' clearance(s) deleted successfully.');
    }
}
