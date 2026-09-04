<x-filament-widgets::widget>
    <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="relative overflow-hidden bg-gradient-to-br from-[#df2228] via-[#c91d23] to-[#9e171c] px-6 py-8 text-white sm:px-8 sm:py-10">
            <div class="pointer-events-none absolute -right-20 -top-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-32 -left-16 h-64 w-64 rounded-full bg-black/10 blur-3xl"></div>
            <div class="relative flex flex-col gap-7 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-2xl">
                    <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-semibold backdrop-blur-sm">
                        <span class="h-2 w-2 rounded-full bg-emerald-300"></span> Portal Supplier
                    </div>
                    <p class="text-sm font-medium text-white/75">Selamat datang kembali</p>
                    <h2 class="mt-1 text-3xl font-bold tracking-tight sm:text-4xl">{{ $this->getSupplierName() }}</h2>
                    <p class="mt-3 max-w-xl text-sm leading-6 text-white/80 sm:text-base">
                        Kelola pesanan dari klinik dengan mudah. Periksa harga, berikan konfirmasi, dan pantau proses pesanan Anda dari satu tempat.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ $this->getOrdersUrl() }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-[#c91d23] shadow-sm transition hover:-translate-y-0.5 hover:bg-white/90">
                            Lihat Pesanan
                            <x-heroicon-m-arrow-right class="h-4 w-4" />
                        </a>
                        <a href="{{ $this->getProfileUrl() }}" class="inline-flex items-center gap-2 rounded-xl border border-white/25 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/15">
                            Profil Supplier
                        </a>
                    </div>
                </div>
                <div class="hidden shrink-0 lg:block">
                    <div class="flex h-32 w-32 items-center justify-center rounded-3xl border border-white/20 bg-white/10 backdrop-blur-sm">
                        <x-heroicon-o-building-storefront class="h-16 w-16 text-white/90" />
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 divide-y divide-gray-200 dark:divide-gray-800 sm:grid-cols-3 sm:divide-x sm:divide-y-0">
            <a href="{{ $this->getOrdersUrl() }}" class="group p-5 transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Pesanan Baru</span>
                    <x-heroicon-o-bell-alert class="h-5 w-5 text-[#df2228]" />
                </div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $this->getPendingCount() }}</div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Menunggu konfirmasi Anda</p>
            </a>
            <a href="{{ $this->getOrdersUrl() }}" class="group p-5 transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Perlu Ditinjau</span>
                    <x-heroicon-o-clock class="h-5 w-5 text-amber-500" />
                </div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $this->getWaitingCount() }}</div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Menunggu keputusan gudang</p>
            </a>
            <a href="{{ $this->getOrdersUrl() }}" class="group p-5 transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Sedang Diproses</span>
                    <x-heroicon-o-arrow-path class="h-5 w-5 text-emerald-500" />
                </div>
                <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $this->getProcessingCount() }}</div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pesanan yang berjalan</p>
            </a>
        </div>

        <div class="border-t border-gray-200 p-6 dark:border-gray-800 sm:p-8">
            <div class="mb-5">
                <p class="text-xs font-bold uppercase tracking-[0.08em] text-[#df2228]">Panduan Supplier</p>
                <h3 class="mt-1 text-xl font-bold tracking-tight text-gray-950 dark:text-white">Cara menangani pesanan</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ikuti empat langkah sederhana berikut saat menerima PO dari klinik.</p>
            </div>
            <div class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-4">
                @foreach ([
                    ['01', 'Periksa PO', 'Buka pesanan baru dan periksa obat serta jumlah yang dipesan.'],
                    ['02', 'Konfirmasi Harga', 'Centang Harga sesuai jika harga PO benar. Jika berbeda, masukkan harga Anda.'],
                    ['03', 'Kirim Respons', 'Kirim konfirmasi. Perubahan harga akan ditinjau terlebih dahulu oleh gudang.'],
                    ['04', 'Pantau Status', 'Cek kembali pesanan sampai status Diproses atau Selesai.'],
                ] as [$number, $title, $description])
                    <div class="rounded-2xl border border-gray-200 bg-gray-50/70 p-4 dark:border-gray-800 dark:bg-gray-800/40">
                        <div class="flex items-start gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#df2228]/10 text-xs font-bold text-[#df2228] dark:bg-[#df2228]/15">{{ $number }}</span>
                            <div>
                                <h4 class="text-sm font-bold text-gray-950 dark:text-white">{{ $title }}</h4>
                                <p class="mt-1 text-xs leading-5 text-gray-500 dark:text-gray-400">{{ $description }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
