@extends('pdf.layout')
@section('content')
<h1>Riwayat Stok</h1>
<table class="meta">
    <tr><td class="label">Tanggal</td><td>{{ optional($riwayat->tanggal_mutasi)->format('d/m/Y') ?: optional($riwayat->created_at)->format('d/m/Y H:i') }}</td></tr>
    <tr><td class="label">Obat</td><td><strong>{{ $riwayat->obat->nama_obat ?? '-' }}</strong></td></tr>
    <tr><td class="label">Aktivitas</td><td>{{ $riwayat->sumber_label }}</td></tr>
    <tr><td class="label">Referensi</td><td>{{ $riwayat->referensi_transaksi ?: '-' }}</td></tr>
    <tr><td class="label">Mutasi</td><td>{{ $riwayat->jenis_transaksi === 'masuk' ? '+' : '-' }}{{ number_format($riwayat->jumlah, 0, ',', '.') }}</td></tr>
    <tr><td class="label">Stok Sebelum</td><td>{{ number_format($riwayat->stok_sebelum, 0, ',', '.') }}</td></tr>
    <tr><td class="label">Stok Sesudah</td><td><strong>{{ number_format($riwayat->stok_sesudah, 0, ',', '.') }}</strong></td></tr>
</table>
<div class="note"><strong>Catatan:</strong><br>{{ $riwayat->keterangan ?: '-' }}</div>
@endsection
