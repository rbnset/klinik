<x-filament-widgets::widget>
    <div class="space-y-5">
        {{-- Header --}}
        <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="relative overflow-hidden bg-gradient-to-br from-[#df2228] via-[#c91d23] to-[#9e171c] px-6 py-7 text-white sm:px-8 sm:py-8">
                <div class="pointer-events-none absolute -right-20 -top-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-32 -left-16 h-64 w-64 rounded-full bg-black/10 blur-3xl"></div>

                <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                    <div class="max-w-3xl">
                        <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-semibold backdrop-blur-sm">
                            <span class="h-2 w-2 rounded-full bg-emerald-300"></span>
                            Pusat Administrasi
                        </div>
                        <p class="text-sm font-medium text-white/75">Selamat datang kembali</p>
                        <h2 class="mt-1 text-3xl font-bold tracking-tight sm:text-4xl">{{ $this->getAdminName() }}</h2>
                        <p class="mt-3 max-w-2xl text-sm leading-6 text-white/80 sm:text-base">
                            Pantau hal-hal yang membutuhkan keputusan administrator: verifikasi supplier, pengelolaan akun,
                            pengawasan pengadaan, pembayaran, permintaan internal, dan kondisi persediaan.
                        </p>
                    </div>
                    <div class="hidden shrink-0 lg:flex">
                        <div class="flex h-28 w-28 items-center justify-center rounded-3xl border border-white/20 bg-white/10 backdrop-blur-sm">
                            <x-heroicon-o-cog-6-tooth class="h-14 w-14 text-white/90" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- KPI --}}
            <div class="grid grid-cols-2 divide-x divide-gray-200 dark:divide-gray-800 sm:grid-cols-3 lg:grid-cols-6">
                @foreach ([
                    ['Supplier Menunggu', $this->getPendingSupplierCount(), 'heroicon-o-building-storefront', 'supplier', 'warning'],
                    ['Stok Kritis', $this->getCriticalStockCount(), 'heroicon-o-exclamation-triangle', 'stock', 'danger'],
                    ['Permintaan', $this->getPendingRequestCount(), 'heroicon-o-clipboard-document-list', 'request', 'info'],
                    ['PO Perlu Tindakan', $this->getProcurementActionCount(), 'heroicon-o-shopping-cart', 'po', 'warning'],
                    ['Pembayaran', $this->getPendingPaymentCount(), 'heroicon-o-banknotes', 'payment', 'warning'],
                    ['Akun Pengguna', $this->getUserCount(), 'heroicon-o-users', 'users', 'gray'],
                ] as [$label, $value, $icon, $link, $tone])
                    <a href="{{ $this->url($link) }}" class="group border-t border-gray-200 p-4 transition hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-white/[0.03] lg:border-t-0">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-semibold leading-4 text-gray-500 dark:text-gray-400">{{ $label }}</span>
                            <x-filament::icon :icon="$icon" class="h-5 w-5 text-{{ $tone }}-500" />
                        </div>
                        <p class="mt-2 text-2xl font-bold tracking-tight text-gray-950 dark:text-white">{{ number_format($value, 0, ',', '.') }}</p>
                        <p class="mt-1 text-[11px] font-medium text-primary-600 dark:text-primary-400">Buka data →</p>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Queue + responsibility --}}
        <div class="grid grid-cols-1 gap-5 xl:grid-cols-[1.7fr_1fr]">
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="flex flex-col gap-2 border-b border-gray-200 px-5 py-4 dark:border-gray-800 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-base font-bold text-gray-950 dark:text-white">Yang Perlu Perhatian</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Prioritas administrasi yang dapat ditindaklanjuti sekarang.</p>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Maks. 10 item</span>
                </div>

                @php($rows = $this->getActionRows())
                @if (count($rows))
                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($rows as $row)
                            <a href="{{ $row['url'] }}" class="flex items-center gap-3 px-5 py-4 transition hover:bg-gray-50 dark:hover:bg-white/[0.03]">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl
                                    {{ $row['tone'] === 'danger' ? 'bg-danger-50 text-danger-600 dark:bg-danger-500/10 dark:text-danger-400' : ($row['tone'] === 'warning' ? 'bg-warning-50 text-warning-600 dark:bg-warning-500/10 dark:text-warning-400' : 'bg-info-50 text-info-600 dark:bg-info-500/10 dark:text-info-400') }}">
                                    <x-filament::icon :icon="$row['icon']" class="h-5 w-5" />
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="flex flex-wrap items-center gap-2">
                                        <span class="text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ $row['label'] }}</span>
                                    </span>
                                    <span class="mt-0.5 block text-sm font-semibold text-gray-900 dark:text-white">{{ $row['title'] }}</span>
                                    <span class="mt-0.5 block truncate text-xs text-gray-500 dark:text-gray-400">{{ $row['detail'] }}</span>
                                </span>
                                <span class="shrink-0 text-sm font-semibold text-primary-600 dark:text-primary-400">Periksa →</span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="px-5 py-12 text-center">
                        <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-success-50 text-success-600 dark:bg-success-500/10 dark:text-success-400">
                            <x-filament::icon icon="heroicon-o-check-circle" class="h-6 w-6" />
                        </div>
                        <p class="mt-3 text-sm font-semibold text-gray-900 dark:text-white">Tidak ada pekerjaan prioritas</p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kondisi administrasi dan operasional sedang terkendali.</p>
                    </div>
                @endif
            </div>

            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-800">
                    <p class="text-xs font-bold uppercase tracking-[0.08em] text-[#df2228]">Tanggung Jawab Admin</p>
                    <h3 class="mt-1 text-base font-bold text-gray-950 dark:text-white">Akses yang paling sering dibutuhkan</h3>
                </div>

                <div class="space-y-2 p-4">
                    @foreach ([
                        ['Kelola Akun & Hak Akses', 'Atur akun pengguna dan peran sistem.', 'users', 'heroicon-o-users'],
                        ['Verifikasi Supplier', 'Periksa pengajuan sebelum portal supplier aktif.', 'supplier', 'heroicon-o-building-storefront'],
                        ['Pantau Persediaan', 'Tinjau stok kritis dan riwayat stok per obat.', 'stock', 'heroicon-o-beaker'],
                        ['Awasi Pengadaan', 'Pantau PO, penerimaan, dan pembayaran.', 'po', 'heroicon-o-shopping-cart'],
                    ] as [$title, $description, $link, $icon])
                        <a href="{{ $this->url($link) }}" class="group flex gap-3 rounded-xl border border-gray-200 p-3 transition hover:border-primary-300 hover:bg-gray-50 dark:border-gray-800 dark:hover:border-primary-500/40 dark:hover:bg-white/[0.03]">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-300">
                                <x-filament::icon :icon="$icon" class="h-4 w-4" />
                            </span>
                            <span class="min-w-0">
                                <span class="block text-sm font-semibold text-gray-900 dark:text-white">{{ $title }}</span>
                                <span class="mt-0.5 block text-xs leading-5 text-gray-500 dark:text-gray-400">{{ $description }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
