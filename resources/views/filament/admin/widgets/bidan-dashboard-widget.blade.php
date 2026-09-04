<x-filament-widgets::widget>
    <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="relative overflow-hidden bg-gradient-to-br from-[#df2228] via-[#c91d23] to-[#9e171c] px-6 py-8 text-white sm:px-8 sm:py-10">
            <div class="pointer-events-none absolute -right-20 -top-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-32 -left-16 h-64 w-64 rounded-full bg-black/10 blur-3xl"></div>
            <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                <div class="max-w-2xl">
                    <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-semibold backdrop-blur-sm">
                        <span class="h-2 w-2 rounded-full bg-emerald-300"></span> Portal Bidan
                    </div>
                    <p class="text-sm font-medium text-white/75">Selamat datang kembali</p>
                    <h2 class="mt-1 text-3xl font-bold tracking-tight sm:text-4xl">{{ $this->getBidanName() }}</h2>
                    <p class="mt-3 max-w-xl text-sm leading-6 text-white/80 sm:text-base">Ajukan kebutuhan obat, pantau keputusan gudang, dan konfirmasi penerimaan dari satu tempat.</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ $this->getCreateUrl() }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-[#c91d23] shadow-sm transition hover:-translate-y-0.5 hover:bg-white/90">
                            Ajukan Permintaan <x-heroicon-m-arrow-right class="h-4 w-4" />
                        </a>
                        <a href="{{ $this->getRequestsUrl() }}" class="inline-flex items-center gap-2 rounded-xl border border-white/25 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/15">Lihat Riwayat</a>
                    </div>
                </div>
                <div class="hidden shrink-0 lg:block">
                    <div class="flex h-28 w-28 items-center justify-center rounded-3xl border border-white/20 bg-white/10 backdrop-blur-sm">
                        <x-heroicon-o-clipboard-document-list class="h-14 w-14 text-white/90" />
                    </div>
                </div>
            </div>
        </div>

        <div class="border-b border-gray-200 p-5 dark:border-gray-800 sm:p-6">
            <div class="mb-4">
                <p class="text-xs font-bold uppercase tracking-[0.08em] text-[#df2228]">Ringkasan Pengajuan Saya</p>
                <h3 class="mt-1 text-xl font-bold tracking-tight text-gray-950 dark:text-white">Status permintaan obat</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Seluruh angka di bawah hanya berasal dari permintaan yang Anda ajukan.</p>
            </div>
            <div class="grid grid-cols-2 gap-3 lg:grid-cols-5">
                @foreach ([
                    ['Total Pengajuan', $this->getTotalCount(), 'heroicon-o-clipboard-document-list', 'text-gray-600 dark:text-gray-300'],
                    ['Menunggu', $this->getPendingCount(), 'heroicon-o-clock', 'text-amber-500'],
                    ['Dalam Proses', $this->getProcessingCount(), 'heroicon-o-arrow-path', 'text-sky-500'],
                    ['Selesai', $this->getCompletedCount(), 'heroicon-o-check-circle', 'text-emerald-500'],
                    ['Ditolak', $this->getRejectedCount(), 'heroicon-o-x-circle', 'text-red-500'],
                ] as [$label, $value, $icon, $iconClass])
                    <a href="{{ $this->getRequestsUrl() }}" class="rounded-2xl border border-gray-200 bg-gray-50/70 p-4 transition hover:-translate-y-0.5 hover:shadow-sm dark:border-gray-800 dark:bg-gray-800/40">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{ $label }}</span>
                            <x-filament::icon :icon="$icon" class="h-5 w-5 {{ $iconClass }}" />
                        </div>
                        <div class="mt-2 text-2xl font-bold text-gray-950 dark:text-white">{{ $value }}</div>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="p-5 sm:p-6">
            <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.08em] text-[#df2228]">5 Pengajuan Terbaru</p>
                    <h3 class="mt-1 text-xl font-bold tracking-tight text-gray-950 dark:text-white">Permintaan saya</h3>
                </div>
                <a href="{{ $this->getRequestsUrl() }}" class="text-sm font-semibold text-[#df2228] hover:underline">Lihat semua</a>
            </div>
            <div class="overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-800">
                <div class="hidden grid-cols-[1.2fr_1fr_1.5fr_auto] gap-4 bg-gray-50 px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-800/50 dark:text-gray-400 sm:grid">
                    <div>Nomor</div><div>Tanggal</div><div>Status</div><div></div>
                </div>
                @forelse ($this->getLatestRequests() as $item)
                    <a href="{{ $item['url'] }}" class="grid grid-cols-1 gap-2 border-t border-gray-200 px-4 py-4 transition hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800/40 sm:grid-cols-[1.2fr_1fr_1.5fr_auto] sm:items-center sm:gap-4">
                        <div class="font-semibold text-gray-950 dark:text-white">{{ $item['number'] }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $item['date'] }}</div>
                        <div><x-filament::badge :color="$item['status_color']">{{ $item['status'] }}</x-filament::badge></div>
                        <div class="text-sm font-semibold text-[#df2228]">Lihat →</div>
                    </a>
                @empty
                    <div class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada permintaan obat.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
