<div class="space-y-6">
    <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900">
        <div class="relative z-10 flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-semibold text-primary-600 dark:text-primary-400">Pusat Operasional Gudang</p>
                <h2 class="mt-1 text-2xl font-bold tracking-tight text-gray-950 dark:text-white">Selamat datang, {{ auth()->user()?->name ?? 'Petugas Gudang' }} 👋</h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-gray-600 dark:text-gray-300">Pantau pekerjaan gudang, proses permintaan obat, pemesanan supplier, dan penerimaan barang dari satu halaman.</p>
            </div>
            <a href="{{ $this->url('po') }}" class="inline-flex items-center justify-center rounded-xl bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-500">Lihat Pemesanan</a>
        </div>
    </div>
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
        @foreach([
            ['Stok Kritis',$this->getStokKritis(),'Perlu diperiksa','obat','heroicon-o-exclamation-triangle'],
            ['Permintaan Baru',$this->getPermintaanPending(),'Menunggu diproses','permintaan','heroicon-o-inbox-arrow-down'],
            ['PO Menunggu',$this->getPoMenunggu(),'Menunggu supplier','po','heroicon-o-shopping-cart'],
            ['Konfirmasi Harga',$this->getPoKonfirmasiHarga(),'Perlu keputusan gudang','po','heroicon-o-currency-dollar'],
            ['Penerimaan Berjalan',$this->getPenerimaanBerjalan(),'Belum lengkap','penerimaan','heroicon-o-truck'],
        ] as [$label,$value,$desc,$link,$icon])
            <a href="{{ $this->url($link) }}" class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-white/10 dark:bg-gray-900">
                <div class="flex items-center justify-between"><span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ $label }}</span><x-filament::icon :icon="$icon" class="h-5 w-5 text-primary-600" /></div>
                <div class="mt-3 text-2xl font-bold text-gray-950 dark:text-white">{{ $value }}</div><div class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $desc }}</div>
            </a>
        @endforeach
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900">
        <h3 class="text-base font-bold text-gray-950 dark:text-white">Alur Kerja Gudang</h3>
        <div class="mt-5 grid gap-5 md:grid-cols-4">
            @foreach([['01','Periksa Permintaan','Tinjau dan proses permintaan obat internal.'],['02','Buat PO','Pesan obat ke supplier sesuai kebutuhan stok.'],['03','Konfirmasi Harga','Tinjau perubahan harga yang diajukan supplier.'],['04','Terima Barang','Periksa fisik, faktur, lalu posting stok.']] as [$no,$title,$desc])
                <div class="flex gap-3"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary-50 text-sm font-bold text-primary-700 dark:bg-primary-500/10 dark:text-primary-300">{{ $no }}</span><div><p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $title }}</p><p class="mt-1 text-xs leading-5 text-gray-500 dark:text-gray-400">{{ $desc }}</p></div></div>
            @endforeach
        </div>
    </div>
</div>
