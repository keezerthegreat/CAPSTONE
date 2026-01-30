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

    <p>
        This is to certify that <strong>{{ $clearance->resident_name }}</strong>
        is a bona fide resident of Barangay Cogon and is hereby cleared
        for the purpose stated below.
    </p>

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
