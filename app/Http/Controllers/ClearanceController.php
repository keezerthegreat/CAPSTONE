<?php

namespace App\Http\Controllers;

use App\Models\Clearance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ClearanceController extends Controller
{
    public function index()
    {
        $clearances = Clearance::latest()->get();
        return view('pages.clearance', compact('clearances'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'resident_name' => 'required|string|max:255',
            'purpose' => 'required|string|max:255',
        ]);

        Clearance::create([
            'clearance_no' => 'CLR-' . now()->format('Y') . '-' . rand(1000, 9999),
            'resident_name' => $request->resident_name,
            'purpose' => $request->purpose,
            'date_issued' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Barangay Clearance issued successfully.');
    }

    public function destroy($id)
    {
        Clearance::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Clearance deleted.');
    }

    public function print($id)
    {
        $clearance = Clearance::findOrFail($id);
        return view('pages.clearance-print', compact('clearance'));
    }
}
