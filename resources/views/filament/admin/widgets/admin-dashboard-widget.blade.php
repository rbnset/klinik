<x-filament-widgets::widget>
    <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
        <div class="border-b border-gray-200 px-6 py-6 dark:border-white/10 sm:px-8">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.12em] text-primary-600 dark:text-primary-400">Dashboard Administrator</p>
                    <h2 class="mt-1 text-2xl font-bold tracking-tight text-gray-950 dark:text-white">Pusat kontrol sistem</h2>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-gray-500 dark:text-gray-400">Kelola akses pengguna, verifikasi supplier, dan pantau kondisi proses utama tanpa mengambil alih pekerjaan operasional gudang.</p>
                </div>
                <div class="rounded-xl bg-gray-50 px-4 py-3 dark:bg-white/5">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Fokus administrator</p>
                    <p class="mt-1 text-sm font-bold text-gray-900 dark:text-white">Kontrol · Verifikasi · Pengawasan</p>
                </div>
            </div>
        </div>

        <div class="grid gap-3 border-b border-gray-200 p-5 dark:border-white/10 sm:grid-cols-2 xl:grid-cols-6">
            @foreach([
                ['Supplier Menunggu', $this->getSupplierMenunggu(), 'Perlu verifikasi', 'heroicon-o-building-storefront', 'warning'],
                ['Stok Kritis', $this->getStokKritis(), '≤ 10 unit', 'heroicon-o-exclamation-triangle', 'danger'],
                ['PO Perlu Tindakan', $this->getPoPerluTindakan(), 'Supplier / harga', 'heroicon-o-shopping-cart', 'info'],
                ['Permintaan Menunggu', $this->getPermintaanMenunggu(), 'Menunggu proses', 'heroicon-o-clipboard-document-list', 'warning'],
                ['Pembayaran Menunggu', $this->getPembayaranMenunggu(), 'Konfirmasi supplier', 'heroicon-o-banknotes', 'danger'],
                ['Pengguna', $this->getPengguna(), 'Seluruh akun', 'heroicon-o-users', 'success'],
            ] as [$label, $value, $desc, $icon, $tone])
                <div class="rounded-2xl border border-gray-200 bg-gray-50/70 p-4 dark:border-white/10 dark:bg-white/[0.03]">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{ $label }}</span>
                        <x-filament::icon :icon="$icon" class="h-5 w-5 text-{{ $tone }}-600 dark:text-{{ $tone }}-400" />
                    </div>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-gray-950 dark:text-white">{{ number_format($value, 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $desc }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid gap-0 lg:grid-cols-[1.4fr_1fr]">
            <div class="border-b border-gray-200 p-5 dark:border-white/10 lg:border-b-0 lg:border-r sm:p-6">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-bold text-gray-950 dark:text-white">Yang perlu perhatian</h3>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Prioritas yang dapat ditindaklanjuti administrator.</p>
                    </div>
                </div>

                <div class="mt-4 space-y-2">
                    @forelse($this->getPerhatian() as $item)
                        <a href="{{ $item['url'] }}" class="group flex items-center gap-3 rounded-2xl border border-gray-200 p-3 transition hover:bg-gray-50 dark:border-white/10 dark:hover:bg-white/[0.04]">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-{{ $item['tone'] }}-50 text-{{ $item['tone'] }}-600 dark:bg-{{ $item['tone'] }}-500/10 dark:text-{{ $item['tone'] }}-300">
                                <x-filament::icon :icon="$item['icon']" class="h-4.5 w-4.5" />
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block text-sm font-semibold text-gray-900 dark:text-white">{{ $item['title'] }}</span>
                                <span class="mt-0.5 block text-xs leading-5 text-gray-500 dark:text-gray-400">{{ $item['description'] }}</span>
                            </span>
                            <x-filament::icon icon="heroicon-m-chevron-right" class="h-4 w-4 text-gray-400 transition group-hover:translate-x-0.5" />
                        </a>
                    @empty
                        <div class="rounded-2xl border border-dashed border-gray-300 p-5 text-center dark:border-white/10">
                            <x-filament::icon icon="heroicon-o-check-circle" class="mx-auto h-7 w-7 text-success-500" />
                            <p class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">Tidak ada perhatian khusus</p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kondisi utama sistem saat ini terkendali.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="p-5 sm:p-6">
                <h3 class="text-sm font-bold text-gray-950 dark:text-white">Akses cepat administrator</h3>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Menu yang paling relevan dengan tanggung jawab administrator.</p>

                <div class="mt-4 grid gap-2 sm:grid-cols-2 lg:grid-cols-1">
                    @foreach($this->getQuickLinks() as $item)
                        <a href="{{ $item['url'] }}" class="group flex items-center gap-3 rounded-2xl border border-gray-200 p-3 transition hover:bg-gray-50 dark:border-white/10 dark:hover:bg-white/[0.04]">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gray-100 text-gray-700 dark:bg-white/10 dark:text-gray-200">
                                <x-filament::icon :icon="$item['icon']" class="h-4.5 w-4.5" />
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block text-sm font-semibold text-gray-900 dark:text-white">{{ $item['label'] }}</span>
                                <span class="block text-xs text-gray-500 dark:text-gray-400">{{ $item['description'] }}</span>
                            </span>
                            <x-filament::icon icon="heroicon-m-arrow-up-right" class="h-4 w-4 text-gray-400" />
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
