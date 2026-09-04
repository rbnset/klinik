@extends('pdf.layout')
@section('content')
<h1>Purchase Order</h1>
<table class="meta">
    <tr><td class="label">Nomor PO</td><td><strong>PO-{{ str_pad($pembelian->id, 5, '0', STR_PAD_LEFT) }}</strong></td></tr>
    <tr><td class="label">Tanggal Pesan</td><td>{{ $pembelian->tanggal_pesan ? \Carbon\Carbon::parse($pembelian->tanggal_pesan)->format('d/m/Y') : '-' }}</td></tr>
    <tr><td class="label">Supplier</td><td>{{ $pembelian->supplier->nama_supplier ?? '-' }}</td></tr>
    <tr><td class="label">Dibuat Oleh</td><td>{{ $pembelian->pengguna->name ?? '-' }}</td></tr>
    <tr><td class="label">Status</td><td>{{ ucfirst($pembelian->status) }}</td></tr>
</table>

<table class="items">
    <thead><tr><th style="width:30px">No</th><th>Obat</th><th style="width:65px">Qty</th><th style="width:105px">Harga Satuan</th><th style="width:115px">Subtotal</th></tr></thead>
    <tbody>
    @foreach($pembelian->detail_pembelian as $i => $detail)
        <tr>
            <td class="center">{{ $i + 1 }}</td>
            <td><strong>{{ $detail->obat->nama_obat ?? '-' }}</strong><br><span class="muted">{{ $detail->obat->kode_obat ?? $detail->obat->sku ?? '-' }}</span></td>
            <td class="center">{{ number_format($detail->jumlah_pesan, 0, ',', '.') }}</td>
            <td class="right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
            <td class="right">Rp {{ number_format($detail->jumlah_pesan * $detail->harga_satuan, 0, ',', '.') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<table class="total-box">
    <tr><td>Total Pesanan</td><td class="right grand">Rp {{ number_format($pembelian->total_pesanan, 0, ',', '.') }}</td></tr>
</table>
<div class="note"><strong>Catatan:</strong> Harga pada dokumen ini merupakan harga supplier yang dikonfirmasi saat PO dibuat. Harga beli tidak mengubah master obat dan menjadi riwayat transaksi.</div>
<table class="signature"><tr><td>Supplier<div class="space"></div>(____________________)</td><td>Petugas Klinik<div class="space"></div>{{ $pembelian->pengguna->name ?? '(____________________)' }}</td></tr></table>
@endsection
