<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>{{ $clearance->certificate_type }} — {{ $clearance->clearance_no }}</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      background: #d1d5db;
      font-family: "Times New Roman", Times, serif;
      font-size: 13pt;
      color: #000;
    }

    /* ── Toolbar ── */
    .toolbar {
      position: fixed;
      top: 0; left: 0; right: 0;
      height: 52px;
      background: #1e293b;
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 0 20px;
      z-index: 100;
      box-shadow: 0 2px 8px rgba(0,0,0,.3);
    }
    .toolbar a, .toolbar button {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 7px 14px;
      border-radius: 6px;
      font-family: -apple-system, sans-serif;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      border: none;
      transition: background .15s;
    }
    .toolbar .btn-back    { background: #334155; color: #e2e8f0; }
    .toolbar .btn-back:hover { background: #475569; }
    .toolbar .btn-print   { background: #2563eb; color: #fff; }
    .toolbar .btn-print:hover { background: #1d4ed8; }
    .toolbar .doc-info {
      margin-left: auto;
      font-family: -apple-system, sans-serif;
      font-size: 12px;
      color: #94a3b8;
    }
    .toolbar .doc-info strong { color: #e2e8f0; }

    /* ── Page wrapper ── */
    .page-wrap {
      padding: 72px 24px 40px;
      display: flex;
      justify-content: center;
    }

    /* ── Document / Paper ── */
    .document {
      background: #fff;
      width: 210mm;
      min-height: 297mm;
      padding: 20mm 22mm 24mm;
      box-shadow: 0 4px 24px rgba(0,0,0,.18);
      position: relative;
      display: flex;
      flex-direction: column;
    }
    .doc-body { flex: 1; }

    /* ── Letterhead ── */
    .lh { text-align: center; margin-bottom: 20px; }
    .lh img { width: 80px; height: 80px; object-fit: contain; display: block; margin: 0 auto 8px; }
    .lh .republic  { font-size: 11pt; font-style: italic; }
    .lh .office    { font-size: 12pt; font-weight: bold; text-transform: uppercase; margin-top: 2px; }
    .lh .location  { font-size: 11pt; margin-top: 2px; }
    .lh hr         { display: none; }
    .lh .sub-hr    { display: none; }

    /* ── Document title ── */
    .doc-title {
      text-align: center;
      font-size: 16pt;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      margin: 22px 0 20px;
    }

    /* ── Body ── */
    .doc-body { line-height: 1.9; }
    .doc-body p {
      margin: 0 0 14px;
      text-align: justify;
      font-size: 13pt;
      line-height: 1.9;
    }
    .doc-body p:last-child { margin-bottom: 0; }

    /* ── Issued line ── */
    .issued-line {
      margin-top: 18px;
      font-size: 12pt;
      line-height: 1.7;
    }

    /* ── Signature ── */
    .sig-block {
      margin-top: 48px;
      text-align: right;
    }
    .sig-block .sig-name {
      font-size: 13pt;
      font-weight: bold;
      display: inline-block;
      letter-spacing: .3px;
    }
    .sig-block .sig-title {
      font-size: 12pt;
      font-style: italic;
      margin-top: 4px;
    }

    /* ── Receipt footer ── */
    .receipt {
      position: absolute;
      bottom: 14mm;
      left: 22mm;
      font-size: 10pt;
      color: #333;
      line-height: 1.6;
    }

    /* ── Doc number badge (bottom right) ── */
    .doc-no {
      position: absolute;
      bottom: 14mm;
      right: 22mm;
      font-size: 9pt;
      color: #999;
      font-style: italic;
    }

    /* ── PRINT STYLES ── */
    @media print {
      body { background: white; }
      .toolbar { display: none !important; }
      .page-wrap { padding: 0; }
      .document {
        box-shadow: none;
        width: 100%;
        min-height: unset;
        padding: 15mm 20mm 20mm;
      }
      @page {
        size: A4 portrait;
        margin: 10mm;
      }
    }
  </style>
</head>
<body>

{{-- Toolbar --}}
<div class="toolbar">
  <a href="{{ url()->previous() }}" class="btn-back">
    &#8592; Back
  </a>
  <button onclick="window.print()" class="btn-print">
    &#128438; Print / Save as PDF
  </button>
  <div class="doc-info">
    <strong>{{ $clearance->clearance_no }}</strong>
    &nbsp;·&nbsp; {{ $clearance->certificate_type }}
    &nbsp;·&nbsp; {{ \Carbon\Carbon::parse($clearance->date_issued)->format('F d, Y') }}
  </div>
</div>

{{-- Paper --}}
<div class="page-wrap">
<div class="document">

  {{-- Letterhead --}}
  <div class="lh">
    <img src="{{ asset('images/cogon.png') }}" alt="Barangay Seal" onerror="this.style.display='none'">
    <div class="republic">Republic of the Philippines</div>
    <div class="office">Office of the Punong Barangay</div>
    <div class="location">Barangay Cogon, Ormoc City, Leyte</div>
    <hr>
    <div class="sub-hr"></div>
  </div>

  {{-- Title --}}
  <div class="doc-title">{{ $clearance->certificate_type }}</div>

  {{-- Body --}}
  <div class="doc-body">
    @if($clearance->body_content)
      {!! $clearance->body_content !!}
    @elseif($clearance->certificate_type === 'Barangay Clearance')
      <p>TO WHOM IT MAY CONCERN:</p>
      <p style="text-indent:2em">THIS IS TO CERTIFY that <strong>{{ $clearance->resident_name }}</strong>, of legal age, Filipino Citizen, is a bona fide resident of Barangay Cogon, Ormoc City. Based on the records of this office, the above-named person has no derogatory record on file and is hereby <strong>CLEARED</strong> for whatever purpose this may serve.</p>
    @else
      <p>TO WHOM IT MAY CONCERN:</p>
      <p style="text-indent:2em">THIS IS TO CERTIFY that <strong>{{ $clearance->resident_name }}</strong>, of legal age, Filipino Citizen, is a bona fide resident of Barangay Cogon, Ormoc City, and is hereby cleared for the purpose stated herein.</p>
    @endif
  </div>

  {{-- Issued line --}}
  <p class="issued-line">
    Issued this {{ \Carbon\Carbon::parse($clearance->date_issued)->format('j') }}<sup>{{ \Carbon\Carbon::parse($clearance->date_issued)->format('S') }}</sup> day of {{ \Carbon\Carbon::parse($clearance->date_issued)->format('F Y') }}
    at Barangay Cogon, Ormoc City, Philippines.
  </p>

  {{-- Signature --}}
  <div class="sig-block">
    <div class="sig-name">ATTY. MA. CASSANDRA T. CODILLA, RCE</div>
    <div class="sig-title">Punong Barangay</div>
  </div>

  {{-- Receipt --}}
  @if($clearance->or_number || $clearance->amount)
  <div class="receipt">
    @if($clearance->or_number) O.R. No.: <strong>{{ $clearance->or_number }}</strong>&emsp; @endif
    @if($clearance->amount) Amount Paid: <strong>₱{{ number_format($clearance->amount, 2) }}</strong> @endif
  </div>
  @endif

  {{-- Doc number --}}
  <div class="doc-no">{{ $clearance->clearance_no }}</div>

</div>
</div>

<script>
  window.addEventListener('load', function () {
    setTimeout(window.print, 600);
  });
</script>
</body>
</html>
