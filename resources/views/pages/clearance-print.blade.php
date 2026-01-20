<!DOCTYPE html>
<html>
<head>
    <title>Barangay Clearance</title>
    <style>
        body { font-family: Arial; padding: 40px; }
        h2 { text-align: center; }
    </style>
</head>
<body onload="window.print()">

<h2>BARANGAY CLEARANCE</h2>

<p>
This is to certify that <strong>{{ $clearance->resident_name }}</strong>
is a bonafide resident of Barangay Cogon and is hereby cleared for the purpose of
<strong>{{ $clearance->purpose }}</strong>.
</p>

<p>Date Issued: {{ $clearance->date_issued }}</p>
<p>Clearance No: {{ $clearance->clearance_no }}</p>

<br><br>
<p>_________________________<br>
Barangay Captain</p>

</body>
</html>
