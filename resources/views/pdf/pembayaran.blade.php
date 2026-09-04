@extends('pdf.layout')
@section('content')
<h1>Bukti Pembayaran</h1>
<table class="meta">
    <tr><td class="label">Nomor Pembayaran</td><td><strong>PAY-{{ str_pad($pembayaran->id, 5, '0', STR_PAD_LEFT) }}</strong></td></tr>
    <tr><td class="label">Nomor PO</td><td>PO-{{ str_pad($pembayaran->id_pembelian_obat, 5, '0', STR_PAD_LEFT) }}</td></tr>
    <tr><td class="label">Tanggal Bayar</td><td>{{ optional($pembayaran->tanggal_bayar)->format('d/m/Y') }}</td></tr>
    <tr><td class="label">Supplier</td><td>{{ $pembayaran->pembelian_obat->supplier->nama_supplier ?? '-' }}</td></tr>
    <tr><td class="label">Metode</td><td>{{ ucfirst($pembayaran->metode_pembayaran) }}</td></tr>
</table>
<div class="note" style="margin-top:25px; text-align:center; font-size:13px;">
    Telah dicatat pembayaran sebesar<br>
    <strong style="font-size:19px;">Rp {{ number_format($pembayaran->total_bayar, 0, ',', '.') }}</strong>
</div>
<table class="meta" style="margin-top:20px;">
    <tr><td class="label">Total PO</td><td>Rp {{ number_format($pembayaran->pembelian_obat->total_pesanan, 0, ',', '.') }}</td></tr>
    <tr><td class="label">Total Dibayar Setelah Transaksi</td><td>Rp {{ number_format($pembayaran->pembelian_obat->total_dibayar, 0, ',', '.') }}</td></tr>
    <tr><td class="label">Sisa Tagihan</td><td><strong>Rp {{ number_format($pembayaran->pembelian_obat->sisa_tagihan, 0, ',', '.') }}</strong></td></tr>
</table>
<table class="signature"><tr><td>Penerima Pembayaran<div class="space"></div>(____________________)</td><td>Pembayar / Petugas Klinik<div class="space"></div>(____________________)</td></tr></table>
@endsection
