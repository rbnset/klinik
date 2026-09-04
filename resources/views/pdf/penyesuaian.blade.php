@extends('pdf.layout')
@section('content')
<h1>Bukti Penyesuaian Stok</h1>
<table class="meta">
    <tr><td class="label">Nomor Penyesuaian</td><td><strong>ADJ-{{ str_pad($penyesuaian->id, 5, '0', STR_PAD_LEFT) }}</strong></td></tr>
    <tr><td class="label">Tanggal</td><td>{{ $penyesuaian->tanggal ? \Carbon\Carbon::parse($penyesuaian->tanggal)->format('d/m/Y') : '-' }}</td></tr>
    <tr><td class="label">Obat</td><td>{{ $penyesuaian->obat->nama_obat ?? '-' }}</td></tr>
    <tr><td class="label">Tindakan</td><td>{{ ucfirst($penyesuaian->jenis) }}</td></tr>
    <tr><td class="label">Alasan</td><td>{{ ucfirst(str_replace('_', ' ', $penyesuaian->alasan)) }}</td></tr>
    <tr><td class="label">Jumlah</td><td>{{ number_format($penyesuaian->jumlah, 0, ',', '.') }}</td></tr>
    <tr><td class="label">Petugas</td><td>{{ $penyesuaian->pengguna->name ?? '-' }}</td></tr>
</table>
<div class="note"><strong>Keterangan:</strong><br>{{ $penyesuaian->keterangan ?: '-' }}</div>
<table class="signature"><tr><td>Petugas Pencatat<div class="space"></div>{{ $penyesuaian->pengguna->name ?? '(____________________)' }}</td><td>Penanggung Jawab<div class="space"></div>(____________________)</td></tr></table>
@endsection
