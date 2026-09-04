@extends('pdf.layout')
@section('content')
<h1>Bukti Penerimaan Obat</h1>
<table class="meta">
    <tr><td class="label">Nomor Penerimaan</td><td><strong>GR-{{ str_pad($penerimaan->id, 5, '0', STR_PAD_LEFT) }}</strong></td></tr>
    <tr><td class="label">Nomor PO</td><td>PO-{{ str_pad($penerimaan->id_pembelian_obat, 5, '0', STR_PAD_LEFT) }}</td></tr>
    <tr><td class="label">Nomor Faktur</td><td><strong>{{ $penerimaan->nomor_faktur }}</strong></td></tr>
    <tr><td class="label">Tanggal Terima</td><td>{{ optional($penerimaan->tanggal_terima)->format('d/m/Y') }}</td></tr>
    <tr><td class="label">Supplier</td><td>{{ $penerimaan->pembelian_obat->supplier->nama_supplier ?? '-' }}</td></tr>
</table>
<table class="items">
    <thead><tr><th style="width:30px">No</th><th>Obat</th><th style="width:80px">Qty Diterima</th><th style="width:95px">Harga PO</th><th style="width:110px">Nilai</th></tr></thead>
    <tbody>
    @foreach($penerimaan->detail_penerimaan as $i => $detail)
        @php $poDetail = $detail->detail_pembelian; $nilai = $detail->jumlah_diterima * ($poDetail->harga_satuan ?? 0); @endphp
        <tr>
            <td class="center">{{ $i + 1 }}</td><td>{{ $poDetail->obat->nama_obat ?? '-' }}</td>
            <td class="center">{{ number_format($detail->jumlah_diterima, 0, ',', '.') }}</td>
            <td class="right">Rp {{ number_format($poDetail->harga_satuan ?? 0, 0, ',', '.') }}</td>
            <td class="right">Rp {{ number_format($nilai, 0, ',', '.') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="note"><strong>Status stok:</strong> penerimaan ini diposting ke stok setelah pencatatan penerimaan. Faktur pada dokumen ini adalah nomor faktur dari supplier.</div>
<table class="signature"><tr><td>Supplier<div class="space"></div>(____________________)</td><td>Penerima / Petugas Klinik<div class="space"></div>(____________________)</td></tr></table>
@endsection
