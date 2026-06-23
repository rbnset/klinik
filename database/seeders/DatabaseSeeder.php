<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,               // 1. Buat User dulu
            MasterDataSeeder::class,         // 2. Kategori, Supplier, & Obat (Stok 0)
            PengadaanBarangSeeder::class,    // 3. Transaksi PO -> Stok Masuk
            SirkulasiInternalSeeder::class,  // 4. Transaksi Bidan & Rusak -> Stok Keluar
        ]);
    }
}
