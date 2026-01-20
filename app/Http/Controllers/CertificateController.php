<?php

namespace App\Http\Controllers;
use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::orderBy('id', 'desc')->get();
        return view('pages.certificate', compact('certificates'));
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

        return redirect('/certificate')->with('success', 'Certificate updated successfully.');
    }

    // 🔹 DELETE
    public function destroy($id)
    {
        Certificate::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Certificate deleted successfully.');
    }


public function print($id)
{
    $certificate = Certificate::findOrFail($id);

    return view('pages.certificate-print', compact('certificate'));
}

}
