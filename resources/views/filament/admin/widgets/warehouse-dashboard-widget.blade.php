<div class="space-y-6">
    <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900">
        <div class="relative z-10 flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-semibold text-primary-600 dark:text-primary-400">Pusat Operasional Gudang</p>
                <h2 class="mt-1 text-2xl font-bold tracking-tight text-gray-950 dark:text-white">Selamat datang, {{ auth()->user()?->name ?? 'Petugas Gudang' }} 👋</h2>
                <p class="mt-2 max-w-3xl text-sm leading-6 text-gray-600 dark:text-gray-300">Dashboard ini memprioritaskan pekerjaan yang membutuhkan keputusan atau tindakan. Klik ringkasan untuk langsung membuka daftar yang sudah difilter.</p>
            </div>
        </div>
    </div>

    <div class="grid gap-3 sm:grid-cols-3">
        @foreach([
            ['Perlu Tindakan', $this->getPoPerluTindakan(), 'PO yang menunggu respons supplier atau keputusan harga', 'po_perlu_tindakan', 'heroicon-o-bell-alert'],
            ['Belum Selesai', $this->getPoBelumSelesai(), 'PO yang masih dalam proses penerimaan', 'po_belum_selesai', 'heroicon-o-truck'],
            ['Sudah Diterima · Belum Lunas', $this->getPoSelesaiBelumLunas(), 'Barang lengkap tetapi tagihan belum lunas', 'po_belum_lunas', 'heroicon-o-banknotes'],
        ] as [$label, $value, $desc, $link, $icon])
            <a href="{{ $this->url($link) }}" class="group rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-primary-300 hover:shadow-md dark:border-white/10 dark:bg-gray-900 dark:hover:border-primary-500/50">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ $label }}</p>
                        <p class="mt-1 text-2xl font-bold tracking-tight text-gray-950 dark:text-white">{{ number_format($value, 0, ',', '.') }}</p>
                    </div>
                    <span class="rounded-lg bg-primary-50 p-2 text-primary-600 transition group-hover:bg-primary-100 dark:bg-primary-500/10 dark:text-primary-400">
                        <x-filament::icon :icon="$icon" class="h-5 w-5" />
                    </span>
                </div>
                <p class="mt-2 text-xs leading-5 text-gray-500 dark:text-gray-400">{{ $desc }}</p>
                <p class="mt-3 text-xs font-semibold text-primary-600 dark:text-primary-400">Buka daftar terfilter →</p>
            </a>
        @endforeach
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
        <div class="flex flex-col gap-2 border-b border-gray-200 px-5 py-4 dark:border-white/10 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-950 dark:text-white">Yang Perlu Ditindaklanjuti</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Urutan diprioritaskan dari kondisi yang paling membutuhkan tindakan petugas gudang.</p>
            </div>
            <span class="text-xs text-gray-500 dark:text-gray-400">Maks. 10 pekerjaan</span>
        </div>

        @php($rows = $this->getTindakLanjutRows())
        @if (count($rows))
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500 dark:bg-white/5 dark:text-gray-400">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Prioritas</th>
                            <th class="px-5 py-3 font-semibold">Yang perlu dilakukan</th>
                            <th class="px-5 py-3 font-semibold">Supplier</th>
                            <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        @foreach($rows as $row)
                            <tr class="transition hover:bg-gray-50 dark:hover:bg-white/[0.03]">
                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $row['tone'] === 'danger' ? 'bg-danger-50 text-danger-700 dark:bg-danger-500/10 dark:text-danger-300' : 'bg-warning-50 text-warning-700 dark:bg-warning-500/10 dark:text-warning-300' }}">{{ $row['prioritas'] }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $row['judul'] }}</p>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $row['detail'] }}</p>
                                </td>
                                <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $row['supplier'] }}</td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ $row['url'] }}" class="inline-flex items-center rounded-lg px-3 py-2 text-xs font-semibold text-primary-600 hover:bg-primary-50 dark:text-primary-400 dark:hover:bg-primary-500/10">Periksa →</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-5 py-10 text-center">
                <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-success-50 text-success-600 dark:bg-success-500/10 dark:text-success-400">
                    <x-filament::icon icon="heroicon-o-check-circle" class="h-6 w-6" />
                </div>
                <p class="mt-3 text-sm font-semibold text-gray-900 dark:text-white">Tidak ada pekerjaan mendesak</p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Semua proses utama gudang dalam kondisi terkendali.</p>
            </div>
        @endif
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-gray-900">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-sm font-bold text-gray-950 dark:text-white">Akses cepat</h3>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Gunakan hanya saat perlu membuka data operasional secara manual.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ $this->url('stok') }}" class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-white/10 dark:text-gray-200 dark:hover:bg-white/5">Stok Obat</a>
                <a href="{{ $this->url('permintaan') }}" class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-white/10 dark:text-gray-200 dark:hover:bg-white/5">Permintaan</a>
                <a href="{{ $this->url('pembayaran_pending') }}" class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-white/10 dark:text-gray-200 dark:hover:bg-white/5">Pembayaran Menunggu</a>
            </div>
        </div>
    </div>
</div>
