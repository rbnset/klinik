<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\PembelianObat;
use App\Models\PenerimaanObat;
use App\Models\PenyesuaianStok;
use App\Models\RiwayatStok;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class PdfController extends Controller
{
    public function pembelian(PembelianObat $pembelian): Response
    {
        Gate::authorize('view', $pembelian);
        $pembelian->load(['supplier', 'pengguna', 'detail_pembelian.obat']);

        return $this->render('pdf.pembelian', [
            'title' => 'Purchase Order',
            'pembelian' => $pembelian,
        ], 'PO-' . str_pad((string) $pembelian->id, 5, '0', STR_PAD_LEFT) . '.pdf');
    }

    public function ringkasanPembelian(PembelianObat $pembelian): Response
    {
        Gate::authorize('view', $pembelian);
        $pembelian->load([
            'supplier',
            'pengguna',
            'detail_pembelian.obat',
            'penerimaan_obat.detail_penerimaan.detail_pembelian.obat',
            'pembayaran',
        ]);

        return $this->render('pdf.pembelian-ringkasan', [
            'title' => 'Ringkasan Proses Pengadaan',
            'pembelian' => $pembelian,
        ], 'PO-' . str_pad((string) $pembelian->id, 5, '0', STR_PAD_LEFT) . '-RINGKASAN-LENGKAP.pdf');
    }

    public function penerimaan(PenerimaanObat $penerimaan): Response
    {
        Gate::authorize('view', $penerimaan);
        $penerimaan->load([
            'pembelian_obat.supplier',
            'detail_penerimaan.detail_pembelian.obat',
        ]);

        return $this->render('pdf.penerimaan', [
            'title' => 'Bukti Penerimaan Obat',
            'penerimaan' => $penerimaan,
        ], 'GR-' . str_pad((string) $penerimaan->id, 5, '0', STR_PAD_LEFT) . '.pdf');
    }

    public function pembayaran(Pembayaran $pembayaran): Response
    {
        Gate::authorize('view', $pembayaran);
        $pembayaran->load(['pembelian_obat.supplier']);

        return $this->render('pdf.pembayaran', [
            'title' => 'Bukti Pembayaran',
            'pembayaran' => $pembayaran,
        ], 'PAY-' . str_pad((string) $pembayaran->id, 5, '0', STR_PAD_LEFT) . '.pdf');
    }

    public function penyesuaian(PenyesuaianStok $penyesuaian): Response
    {
        Gate::authorize('view', $penyesuaian);
        $penyesuaian->load(['obat', 'pengguna']);

        return $this->render('pdf.penyesuaian', [
            'title' => 'Bukti Penyesuaian Stok',
            'penyesuaian' => $penyesuaian,
        ], 'ADJ-' . str_pad((string) $penyesuaian->id, 5, '0', STR_PAD_LEFT) . '.pdf');
    }

    public function riwayatStok(RiwayatStok $riwayat): Response
    {
        Gate::authorize('view', $riwayat);
        $riwayat->load('obat');

        return $this->render('pdf.riwayat-stok', [
            'title' => 'Riwayat Stok',
            'riwayat' => $riwayat,
        ], 'STOK-' . str_pad((string) $riwayat->id, 5, '0', STR_PAD_LEFT) . '.pdf');
    }

    private function render(string $view, array $data, string $filename): Response
    {
        $data['logo'] = $this->logoDataUri();
        $data['clinicName'] = 'PRAKTEK BIDAN PUJI SUSANTI';
        $data['clinicAddress'] = 'Karongan RT.03 / RW.11, Jogotirto, Kecamatan Berbah, Kabupaten Sleman, Daerah Istimewa Yogyakarta';
        $data['clinicPermit'] = 'Nomor Izin Usaha: 446/3280/7201/III-25';

        return Pdf::loadView($view, $data)
            ->setPaper('a4', 'portrait')
            ->setOption(['dpi' => 120, 'defaultFont' => 'DejaVu Sans'])
            ->download($filename);
    }

    private function logoDataUri(): ?string
    {
        $paths = [
            public_path('images/logo.jpeg'),
            public_path('images/logo.jpeg'),
        ];

        foreach ($paths as $path) {
            if (! is_file($path)) {
                continue;
            }

            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            $mime = $extension === 'svg' ? 'image/svg+xml' : 'image/png';

            return 'data:' . $mime . ';base64,' . base64_encode((string) file_get_contents($path));
        }

        return null;
    }
}
