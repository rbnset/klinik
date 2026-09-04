@extends('pdf.layout')
@section('content')
<h1>Ringkasan Proses Pengadaan</h1>
<div class="document-code">Dokumen Final · PO-{{ str_pad((string) $pembelian->id, 5, '0', STR_PAD_LEFT) }}</div>

<div class="note" style="margin-top:0;">
    <strong>Dokumen ini merangkum satu siklus pengadaan secara utuh.</strong><br>
    Mulai dari Purchase Order, konfirmasi supplier, seluruh penerimaan barang bertahap, sampai seluruh pembayaran yang telah disetujui supplier.
</div>

<table class="meta" style="margin-top:14px;">
    <tr><td class="label">Nomor PO</td><td><strong>PO-{{ str_pad((string) $pembelian->id, 5, '0', STR_PAD_LEFT) }}</strong></td></tr>
    <tr><td class="label">Tanggal Pesan</td><td>{{ optional($pembelian->tanggal_pesan)->format('d/m/Y') }}</td></tr>
    <tr><td class="label">Supplier</td><td>{{ $pembelian->supplier->nama_supplier ?? '-' }}</td></tr>
    <tr><td class="label">Dibuat Oleh</td><td>{{ $pembelian->pengguna->name ?? '-' }}</td></tr>
    <tr><td class="label">Status PO</td><td><strong>{{ $pembelian->status === 'selesai' ? 'Selesai' : ucfirst($pembelian->status) }}</strong></td></tr>
    <tr><td class="label">Status Penerimaan</td><td>{{ $pembelian->status_penerimaan === 'lengkap' ? 'Lengkap' : 'Sebagian / Belum Lengkap' }}</td></tr>
    <tr><td class="label">Status Pembayaran</td><td>{{ $pembelian->status_pembayaran === 'lunas' ? 'Lunas' : ucfirst(str_replace('_', ' ', $pembelian->status_pembayaran)) }}</td></tr>
</table>

<h2>1. Rincian Purchase Order</h2>
<table class="items">
    <thead><tr><th style="width:28px">No</th><th>Obat</th><th style="width:58px">Qty PO</th><th style="width:92px">Harga Satuan</th><th style="width:105px">Nilai</th></tr></thead>
    <tbody>
    @foreach($pembelian->detail_pembelian as $i => $detail)
        <tr>
            <td class="center">{{ $i + 1 }}</td>
            <td><strong>{{ $detail->obat->nama_obat ?? '-' }}</strong><br><span class="muted">{{ $detail->obat->kode_obat ?? $detail->obat->sku ?? '-' }}</span></td>
            <td class="center">{{ number_format($detail->jumlah_pesan, 0, ',', '.') }} {{ $detail->obat->satuan ?? '' }}</td>
            <td class="right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
            <td class="right">Rp {{ number_format($detail->jumlah_pesan * $detail->harga_satuan, 0, ',', '.') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<table class="total-box">
    <tr><td>Total Nilai PO</td><td class="right grand">Rp {{ number_format($pembelian->total_pesanan, 0, ',', '.') }}</td></tr>
</table>

<h2>2. Rekap Penerimaan Barang</h2>
<table class="items">
    <thead><tr><th style="width:28px">No</th><th>Nomor Penerimaan</th><th>Faktur</th><th>Tanggal</th><th>Status Stok</th></tr></thead>
    <tbody>
    @forelse($pembelian->penerimaan_obat as $i => $penerimaan)
        <tr>
            <td class="center">{{ $i + 1 }}</td>
            <td><strong>GR-{{ str_pad((string) $penerimaan->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
            <td>{{ $penerimaan->nomor_faktur ?: '-' }}</td>
            <td>{{ optional($penerimaan->tanggal_terima)->format('d/m/Y') }}</td>
            <td>{{ $penerimaan->stok_diposting_at ? 'Diposting' : 'Belum Diposting' }}</td>
        </tr>
    @empty
        <tr><td colspan="5" class="center">Belum ada penerimaan.</td></tr>
    @endforelse
    </tbody>
</table>

@foreach($pembelian->penerimaan_obat as $penerimaan)
    <div class="subheading">GR-{{ str_pad((string) $penerimaan->id, 5, '0', STR_PAD_LEFT) }} · {{ optional($penerimaan->tanggal_terima)->format('d/m/Y') }} · Faktur: {{ $penerimaan->nomor_faktur ?: '-' }}</div>
    <table class="items compact">
        <thead><tr><th>Obat</th><th style="width:90px">Qty Diterima</th><th style="width:110px">Nilai Penerimaan</th></tr></thead>
        <tbody>
        @foreach($penerimaan->detail_penerimaan as $detail)
            @php $poDetail = $detail->detail_pembelian; $nilai = (int) $detail->jumlah_diterima * (int) ($poDetail->harga_satuan ?? 0); @endphp
            <tr>
                <td>{{ $poDetail->obat->nama_obat ?? '-' }}</td>
                <td class="center">{{ number_format($detail->jumlah_diterima, 0, ',', '.') }} {{ $poDetail->obat->satuan ?? '' }}</td>
                <td class="right">Rp {{ number_format($nilai, 0, ',', '.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endforeach

<h2>3. Rekap Pembayaran</h2>
<table class="items">
    <thead><tr><th style="width:28px">No</th><th>Nomor Pembayaran</th><th>Tanggal</th><th>Metode</th><th>Status</th><th style="width:105px">Jumlah</th></tr></thead>
    <tbody>
    @forelse($pembelian->pembayaran as $i => $pembayaran)
        <tr>
            <td class="center">{{ $i + 1 }}</td>
            <td><strong>PAY-{{ str_pad((string) $pembayaran->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
            <td>{{ optional($pembayaran->tanggal_bayar)->format('d/m/Y') }}</td>
            <td>{{ ucfirst($pembayaran->metode_pembayaran) }}</td>
            <td>{{ $pembayaran->status_label }}</td>
            <td class="right">Rp {{ number_format($pembayaran->total_bayar, 0, ',', '.') }}</td>
        </tr>
    @empty
        <tr><td colspan="6" class="center">Belum ada pembayaran.</td></tr>
    @endforelse
    </tbody>
</table>
<table class="meta" style="margin-top:12px;">
    <tr><td class="label">Total Nilai PO</td><td class="right">Rp {{ number_format($pembelian->total_pesanan, 0, ',', '.') }}</td></tr>
    <tr><td class="label">Total Dibayar Sah</td><td class="right"><strong>Rp {{ number_format($pembelian->total_dibayar, 0, ',', '.') }}</strong></td></tr>
    <tr><td class="label">Sisa Tagihan</td><td class="right"><strong>Rp {{ number_format($pembelian->sisa_tagihan, 0, ',', '.') }}</strong></td></tr>
</table>

<h2>4. Kesimpulan Proses</h2>
<div class="note">
    PO <strong>PO-{{ str_pad((string) $pembelian->id, 5, '0', STR_PAD_LEFT) }}</strong>
    telah melalui proses pemesanan kepada supplier, penerimaan barang sebanyak {{ $pembelian->penerimaan_obat->count() }} kali,
    dan pencatatan pembayaran sebanyak {{ $pembelian->pembayaran->count() }} kali.
    @if($pembelian->status === 'selesai' && $pembelian->status_pembayaran === 'lunas')
        Seluruh barang telah diterima dan seluruh nilai PO telah dibayar serta disetujui supplier. Proses pengadaan dinyatakan <strong>selesai dan lunas</strong>.
    @else
        Dokumen ini menggambarkan posisi proses saat dokumen dicetak; masih terdapat tahapan yang belum selesai.
    @endif
</div>

<table class="signature"><tr><td>Supplier<div class="space"></div>(____________________)</td><td>Petugas Klinik<div class="space"></div>{{ $pembelian->pengguna->name ?? '(____________________)' }}</td></tr></table>
@endsection
