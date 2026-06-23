<?php

namespace Database\Seeders;

use App\Models\KategoriObat;
use App\Models\Obat;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Kategori Obat (6 Data)
        $kategori = ['Tablet / Kaplet', 'Sirup', 'Kapsul', 'Salep / Krim', 'Injeksi / Ampul', 'Tetes Mata'];
        foreach ($kategori as $kat) {
            KategoriObat::create(['nama_kategori' => $kat]);
        }

        // 2. Supplier (5 Data)
        $suppliers = [
            ['nama_supplier' => 'PT. Kimia Farma', 'alamat' => 'Jl. Veteran No.1, Jakarta', 'no_telp' => '08111222333', 'id_pengguna' => 5], // Nyambung ke akun supplier
            ['nama_supplier' => 'PT. Kalbe Farma', 'alamat' => 'Kawasan Industri Cikarang', 'no_telp' => '08222333444', 'id_pengguna' => null],
            ['nama_supplier' => 'PT. Sanbe Farma', 'alamat' => 'Jl. Tamansari, Bandung', 'no_telp' => '08333444555', 'id_pengguna' => null],
            ['nama_supplier' => 'PT. Dexa Medica', 'alamat' => 'Jl. Jend. Sudirman, Palembang', 'no_telp' => '08444555666', 'id_pengguna' => null],
            ['nama_supplier' => 'Bina San Prima', 'alamat' => 'Jl. Raya Bogor', 'no_telp' => '08555666777', 'id_pengguna' => null],
        ];
        foreach ($suppliers as $sup) {
            Supplier::create($sup);
        }

        // 3. Obat (10 Data - Stok Awal 0)
        $obats = [
            ['id_kategori_obat' => 1, 'kode_obat' => 'TAB-001', 'nama_obat' => 'Paracetamol 500mg', 'satuan' => 'Strip', 'stok' => 0, 'harga_beli' => 3500],
            ['id_kategori_obat' => 1, 'kode_obat' => 'TAB-002', 'nama_obat' => 'Amoxicillin 500mg', 'satuan' => 'Strip', 'stok' => 0, 'harga_beli' => 6000],
            ['id_kategori_obat' => 1, 'kode_obat' => 'TAB-003', 'nama_obat' => 'Ibuprofen 400mg', 'satuan' => 'Strip', 'stok' => 0, 'harga_beli' => 4500],
            ['id_kategori_obat' => 2, 'kode_obat' => 'SYR-001', 'nama_obat' => 'Sanmol Sirup Anak', 'satuan' => 'Botol', 'stok' => 0, 'harga_beli' => 15000],
            ['id_kategori_obat' => 2, 'kode_obat' => 'SYR-002', 'nama_obat' => 'OBH Combi Plus', 'satuan' => 'Botol', 'stok' => 0, 'harga_beli' => 18000],
            ['id_kategori_obat' => 3, 'kode_obat' => 'KAP-001', 'nama_obat' => 'Omeprazole 20mg', 'satuan' => 'Strip', 'stok' => 0, 'harga_beli' => 5000],
            ['id_kategori_obat' => 4, 'kode_obat' => 'SLP-001', 'nama_obat' => 'Acyclovir Salep 5%', 'satuan' => 'Tube', 'stok' => 0, 'harga_beli' => 8000],
            ['id_kategori_obat' => 4, 'kode_obat' => 'SLP-002', 'nama_obat' => 'Betadine Antiseptik', 'satuan' => 'Botol Kecil', 'stok' => 0, 'harga_beli' => 12000],
            ['id_kategori_obat' => 5, 'kode_obat' => 'INJ-001', 'nama_obat' => 'Ondansetron Injeksi', 'satuan' => 'Ampul', 'stok' => 0, 'harga_beli' => 20000],
            ['id_kategori_obat' => 6, 'kode_obat' => 'TTS-001', 'nama_obat' => 'Insto Tetes Mata', 'satuan' => 'Botol', 'stok' => 0, 'harga_beli' => 14000],
        ];
        foreach ($obats as $obat) {
            Obat::create($obat);
        }
    }
}
