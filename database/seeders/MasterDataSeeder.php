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
        foreach (['Tablet / Kaplet', 'Sirup', 'Kapsul', 'Salep / Krim', 'Injeksi / Ampul', 'Tetes Mata'] as $nama) {
            KategoriObat::create(['nama_kategori' => $nama]);
        }

        foreach ([
            ['nama_supplier' => 'PT. Kimia Farma', 'alamat' => 'Jl. Veteran No.1, Jakarta', 'no_telp' => '08111222333', 'id_pengguna' => 5],
            ['nama_supplier' => 'PT. Kalbe Farma', 'alamat' => 'Kawasan Industri Cikarang', 'no_telp' => '08222333444', 'id_pengguna' => null],
            ['nama_supplier' => 'PT. Sanbe Farma', 'alamat' => 'Jl. Tamansari, Bandung', 'no_telp' => '08333444555', 'id_pengguna' => null],
            ['nama_supplier' => 'PT. Dexa Medica', 'alamat' => 'Jl. Jend. Sudirman, Palembang', 'no_telp' => '08444555666', 'id_pengguna' => null],
            ['nama_supplier' => 'Bina San Prima', 'alamat' => 'Jl. Raya Bogor', 'no_telp' => '08555666777', 'id_pengguna' => null],
        ] as $supplier) {
            Supplier::create($supplier);
        }

        foreach ([
            [1, 'TAB-001', 'Paracetamol 500mg', 'Strip'],
            [1, 'TAB-002', 'Amoxicillin 500mg', 'Strip'],
            [1, 'TAB-003', 'Ibuprofen 400mg', 'Strip'],
            [2, 'SYR-001', 'Sanmol Sirup Anak', 'Botol'],
            [2, 'SYR-002', 'OBH Combi Plus', 'Botol'],
            [3, 'KAP-001', 'Omeprazole 20mg', 'Strip'],
            [4, 'SLP-001', 'Acyclovir Salep 5%', 'Tube'],
            [4, 'SLP-002', 'Betadine Antiseptik', 'Botol Kecil'],
            [5, 'INJ-001', 'Ondansetron Injeksi', 'Ampul'],
            [6, 'TTS-001', 'Insto Tetes Mata', 'Botol'],
        ] as [$kategori, $kode, $nama, $satuan]) {
            Obat::create([
                'id_kategori_obat' => $kategori,
                'kode_obat' => $kode,
                'nama_obat' => $nama,
                'satuan' => $satuan,
                'stok' => 0,
            ]);
        }
    }
}
