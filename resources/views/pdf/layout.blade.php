<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page { margin: 32px 38px 42px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #111827; line-height: 1.45; }
        .kop { border-bottom: 2.5px solid #111827; padding-bottom: 10px; margin-bottom: 18px; }
        .kop-table, .meta, .summary, .signature { width: 100%; border-collapse: collapse; }
        .logo { width: 58px; height: 58px; object-fit: contain; }
        .clinic { padding-left: 12px; vertical-align: middle; }
        .clinic-name { font-size: 18px; font-weight: 700; letter-spacing: .5px; }
        .address { font-size: 9px; color: #4b5563; }
        h1 { margin: 0 0 16px; font-size: 15px; text-align: center; text-transform: uppercase; }
        .meta td { padding: 3px 0; vertical-align: top; }
        .meta .label { width: 115px; color: #6b7280; }
        table.items { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .items th { background: #eef2f7; border: 1px solid #d1d5db; padding: 7px; text-align: left; font-size: 9px; }
        .items td { border: 1px solid #d1d5db; padding: 7px; vertical-align: top; }
        .right { text-align: right; }
        .center { text-align: center; }
        .total-box { margin-top: 14px; margin-left: auto; width: 45%; }
        .total-box td { padding: 5px; }
        .total-box .grand { font-size: 12px; font-weight: 700; border-top: 1px solid #111827; }
        .document-code { text-align:right; font-size:8px; color:#6b7280; margin-top:-8px; margin-bottom:12px; }
        .note { margin-top: 16px; padding: 9px; border: 1px solid #d1d5db; background: #f9fafb; }
        .signature { margin-top: 42px; }
        .signature td { width: 50%; text-align: center; vertical-align: top; }
        .space { height: 58px; }
        .muted { color: #6b7280; }
        .badge { font-weight: 700; }
    </style>
</head>
<body>
    <div class="kop">
        <table class="kop-table">
            <tr>
                <td style="width:70px; vertical-align:middle;">
                    @if($logo)<img class="logo" src="{{ $logo }}">@endif
                </td>
                <td class="clinic">
                    <div class="clinic-name">{{ $clinicName }}</div>
                    <div class="address">{{ $clinicAddress }}</div>
                </td>
            </tr>
        </table>
    </div>

    @yield('content')
    <div style="position:fixed; bottom:-24px; left:0; right:0; text-align:center; font-size:8px; color:#9ca3af;">{{ $clinicName }} · Dokumen dicetak dari Sistem Pengadaan & Persediaan</div>
</body>
</html>
