<?php

namespace App\Http\Controllers;
use App\Models\ActivityLog;
use App\Models\Certificate;
use App\Models\Resident;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::orderBy('id', 'desc')->get();
        $residents = Resident::where('is_deceased', false)
            ->orderBy('last_name')->orderBy('first_name')
            ->get(['id', 'last_name', 'first_name', 'middle_name', 'address', 'barangay']);
        return view('pages.certificate', compact('certificates', 'residents'));
    }

    public function store(Request $request)
    {
        Certificate::create([
            'certificate_no'   => 'CERT-' . date('Y') . '-' . rand(1000, 9999),
            'resident_name'    => $request->resident_name,
            'certificate_type' => $request->certificate_type,
            'purpose'          => $request->purpose,
            'issued_date'      => now(),
        ]);
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
            'resident_name'    => $request->resident_name,
            'certificate_type' => $request->certificate_type,
            'purpose'          => $request->purpose,
        ]);
        ActivityLog::log('updated', 'Certificate', "Updated certificate for: {$request->resident_name}");

        return redirect('/certificate')->with('success', 'Certificate updated successfully.');
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
