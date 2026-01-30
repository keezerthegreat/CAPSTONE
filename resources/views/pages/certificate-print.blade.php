<!DOCTYPE html>
<html>
<head>
    <title>Certificate</title>

    <a href="{{ url()->previous() }}"
   style="
        display: inline-block;
        margin-bottom: 12px;
        padding: 6px 12px;
        background-color: #2c3e50;
        color: #ffffff;
        text-decoration: none;
        border-radius: 4px;
        font-size: 14px;
   ">
    ← Back
    
</a>
    <style>
        body {
            font-family: "Times New Roman", serif;
            margin: 50px;
        }
        .certificate {
            border: 2px solid #000;
            padding: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        h2 {
            text-transform: uppercase;
            margin-bottom: 30px;
        }
        p {
            font-size: 18px;
            line-height: 1.8;
            text-align: justify;
        }
        .footer {
            margin-top: 60px;
        }
    </style>
</head>
<body onload="window.print()">

<div class="certificate">

    <div class="header">
        <h2>{{ $certificate->certificate_type }}</h2>
    </div>

    {{-- GOOD MORAL CHARACTER --}}
    @if($certificate->certificate_type === 'Good Moral Character Clearance')
        <p>
            This is to certify that <strong>{{ $certificate->resident_name }}</strong>
            is a resident of this barangay and is known to be of
            <strong>good moral character</strong>, law-abiding, and has no derogatory
            record on file as of this date.
        </p>

        <p>
            This certification is issued upon request of the above-named person
            for the purpose stated below.
        </p>

    {{-- RESIDENCY --}}
    @elseif($certificate->certificate_type === 'Residency Certificate')
        <p>
            This is to certify that <strong>{{ $certificate->resident_name }}</strong>
            is a bona fide resident of this barangay.
        </p>

        <p>
            This certification is issued upon request for whatever
            legal purpose it may serve.
        </p>

    {{-- INDIGENCY --}}
    @elseif($certificate->certificate_type === 'Indigency Certificate')
        <p>
            This is to certify that <strong>{{ $certificate->resident_name }}</strong>
            is a resident of this barangay and belongs to an
            <strong>indigent family</strong>.
        </p>

        <p>
            This certification is issued to support the request for
            financial or medical assistance.
        </p>

    {{-- BUSINESS OPERATION --}}
    @elseif($certificate->certificate_type === 'Business Operation')
        <p>
            This is to certify that <strong>{{ $certificate->resident_name }}</strong>
            is a resident of this barangay and is hereby granted
            clearance to <strong>operate a business</strong> within the jurisdiction
            of this barangay, subject to existing rules and regulations.
        </p>

        <p>
            This certification is issued to support the application
            for business operation.
        </p>
    @endif

    <p>
        <strong>Purpose:</strong> {{ $certificate->purpose }}
    </p>

    <p>
        Issued this {{ \Carbon\Carbon::parse($certificate->issued_date)->format('jS day of F, Y') }}
        at Barangay Cogon.
    </p>

    <div class="footer">
        <p>
            ___________________________<br>
            Barangay Captain
        </p>
    </div>

</div>

</body>
</html>
