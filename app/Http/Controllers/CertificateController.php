<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Certificate;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::orderBy('id', 'desc')->paginate(20)->withQueryString();
        $totalCertificates = Certificate::count();
        $monthCertificates = Certificate::whereMonth('issued_date', now()->month)
            ->whereYear('issued_date', now()->year)->count();
        $residents = Resident::where('status', 'approved')->where('is_deceased', false)
            ->orderBy('last_name')->orderBy('first_name')
            ->get(['id', 'last_name', 'first_name', 'middle_name', 'address', 'barangay']);

        return view('pages.certificate', compact('certificates', 'residents', 'totalCertificates', 'monthCertificates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'resident_name' => 'required|string|max:255',
            'certificate_type' => 'required|in:Good Moral Character Clearance,Certificate of Residency,Certificate of Indigency,Certificate of Unemployment,Certificate of Residency for Voters,Certificate of Guardianship',
            'purpose' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $year = date('Y');
            $seq = Certificate::whereYear('issued_date', $year)->count() + 1;
            $certNo = 'CERT-'.$year.'-'.str_pad($seq, 4, '0', STR_PAD_LEFT);

            Certificate::create([
                'certificate_no' => $certNo,
                'resident_name' => $request->resident_name,
                'certificate_type' => $request->certificate_type,
                'purpose' => $request->purpose,
                'issued_date' => now(),
            ]);
        });
        ActivityLog::log('created', 'Certificate', "Issued certificate for: {$request->resident_name} ({$request->certificate_type})");

        return redirect()->back()->with('success', 'Certificate issued successfully.');
    }

    // 🔹 EDIT VIEW
    public function edit($id)
    {
        $certificate = Certificate::findOrFail($id);

        return view('pages.certificate-edit', compact('certificate'));
    }

    // 🔹 UPDATE
    public function update(Request $request, $id)
    {
        $certificate = Certificate::findOrFail($id);

        $certificate->update([
            'resident_name' => $request->resident_name,
            'certificate_type' => $request->certificate_type,
            'purpose' => $request->purpose,
        ]);
        ActivityLog::log('updated', 'Certificate', "Updated certificate for: {$request->resident_name}");

        return redirect()->route('certificate.index')->with('success', 'Certificate updated successfully.');
    }

    // 🔹 DELETE
    public function destroy($id)
    {
        $certificate = Certificate::findOrFail($id);
        ActivityLog::log('deleted', 'Certificate', "Deleted certificate for: {$certificate->resident_name}");
        $certificate->delete();

        return redirect()->back()->with('success', 'Certificate deleted successfully.');
    }

    public function print($id)
    {
        $certificate = Certificate::findOrFail($id);

        return view('pages.certificate-print', compact('certificate'));
    }
}
