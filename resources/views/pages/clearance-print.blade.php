<!DOCTYPE html>
<html>
<head>
    <title>Barangay Clearance</title>

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
        <h2>Barangay Clearance</h2>
    </div>

    {{-- BARANGAY CLEARANCE --}}
    @if($clearance->certificate_type === 'Barangay Clearance')
        <p>
            This is to certify that <strong>{{ $clearance->resident_name }}</strong>
            is a bona fide resident of Barangay Cogon and is hereby granted
            <strong>Barangay Clearance</strong>. They have no derogatory record on file
            and are known to be of good standing in the community.
        </p>

    {{-- RESIDENCY CLEARANCE --}}
    @elseif($clearance->certificate_type === 'Residency Clearance')
        <p>
            This is to certify that <strong>{{ $clearance->resident_name }}</strong>
            is a bona fide resident of Barangay Cogon and is hereby cleared
            for the purpose stated below.
        </p>

    {{-- GOOD MORAL CLEARANCE --}}
    @elseif($clearance->certificate_type === 'Good Moral Clearance')
        <p>
            This is to certify that <strong>{{ $clearance->resident_name }}</strong>
            is a resident of Barangay Cogon and is known to be of
            <strong>good moral character</strong>, law-abiding, and has no derogatory
            record on file as of this date.
        </p>

    {{-- POLICE CLEARANCE ENDORSEMENT --}}
    @elseif($clearance->certificate_type === 'Police Clearance Endorsement')
        <p>
            This is to certify that <strong>{{ $clearance->resident_name }}</strong>
            is a bona fide resident of Barangay Cogon. This barangay hereby
            <strong>endorses</strong> the above-named person for the issuance of a
            Police Clearance, as they have no pending complaints or derogatory
            record in this barangay as of this date.
        </p>

    {{-- FIRST TIME JOB SEEKER CLEARANCE --}}
    @elseif($clearance->certificate_type === 'First Time Job Seeker Clearance')
        <p>
            This is to certify that <strong>{{ $clearance->resident_name }}</strong>
            is a bona fide resident of Barangay Cogon and is a
            <strong>first time job seeker</strong> pursuant to Republic Act No. 11261
            (First Time Jobseekers Assistance Act). They are entitled to the exemption
            from payment of fees for the issuance of this clearance.
        </p>

    {{-- LEGACY / FALLBACK --}}
    @else
        <p>
            This is to certify that <strong>{{ $clearance->resident_name }}</strong>
            is a bona fide resident of Barangay Cogon and is hereby cleared
            for the purpose stated below.
        </p>
    @endif

    <p>
        <strong>Purpose:</strong> {{ $clearance->purpose }}
    </p>

    <p>
        Issued this {{ \Carbon\Carbon::parse($clearance->date_issued)->format('jS day of F, Y') }}
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