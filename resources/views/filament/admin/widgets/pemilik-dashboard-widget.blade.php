<x-filament-widgets::widget>
    <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
        <div class="border-b border-gray-200 px-6 py-6 dark:border-white/10 sm:px-8">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.12em] text-primary-600 dark:text-primary-400">Dashboard Pemilik</p>
                    <h2 class="mt-1 text-2xl font-bold tracking-tight text-gray-950 dark:text-white">Ringkasan kondisi usaha</h2>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-gray-500 dark:text-gray-400">Pantau pengeluaran, pengadaan, distribusi obat, dan kewajiban pembayaran berdasarkan periode yang dipilih.</p>
                </div>
                <div class="rounded-xl bg-gray-50 px-4 py-3 text-right dark:bg-white/5">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Periode laporan</p>
                    <p class="mt-1 text-sm font-bold text-gray-900 dark:text-white">{{ $this->getPeriodLabel() }}</p>
                </div>
            </div>
        </div>

        <div class="grid gap-3 border-b border-gray-200 p-5 dark:border-white/10 sm:grid-cols-2 xl:grid-cols-6">
            @foreach([
                ['Nilai Pengadaan', 'Rp '.number_format($this->getTotalPembelian(), 0, ',', '.'), 'PO pada periode', 'heroicon-o-shopping-cart', 'text-sky-600 dark:text-sky-400'],
                ['Pengeluaran Dibayar', 'Rp '.number_format($this->getTotalDibayar(), 0, ',', '.'), 'Pembayaran disetujui', 'heroicon-o-banknotes', 'text-emerald-600 dark:text-emerald-400'],
                ['Obat Didistribusikan', number_format($this->getTotalDistribusi(), 0, ',', '.').' unit', 'Realisasi internal', 'heroicon-o-cube', 'text-violet-600 dark:text-violet-400'],
                ['Permintaan Internal', number_format($this->getPermintaan(), 0, ',', '.'), 'Pengajuan pada periode', 'heroicon-o-clipboard-document-list', 'text-amber-600 dark:text-amber-400'],
                ['PO Aktif', number_format($this->getPoAktif(), 0, ',', '.'), 'Masih berjalan', 'heroicon-o-truck', 'text-cyan-600 dark:text-cyan-400'],
                ['Tagihan Aktif', 'Rp '.number_format($this->getTagihanAktif(), 0, ',', '.'), 'Kewajiban saat ini', 'heroicon-o-credit-card', 'text-rose-600 dark:text-rose-400'],
            ] as [$label, $value, $desc, $icon, $iconClass])
                <div class="rounded-2xl border border-gray-200 bg-gray-50/70 p-4 dark:border-white/10 dark:bg-white/[0.03]">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{ $label }}</span>
                        <x-filament::icon :icon="$icon" class="h-5 w-5 {{ $iconClass }}" />
                    </div>
                    <p class="mt-2 text-xl font-bold tracking-tight text-gray-950 dark:text-white">{{ $value }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $desc }}</p>
                </div>
            @endforeach
        </div>

        <div class="flex items-center justify-between gap-3 px-5 py-4 sm:px-6">
            <div>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">Persediaan</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $this->getStokKritis() }} obat berada pada atau di bawah batas kritis 10 unit.</p>
            </div>
            <span class="inline-flex items-center rounded-full px-3 py-1.5 text-xs font-semibold {{ $this->getStokKritis() > 0 ? 'bg-danger-50 text-danger-700 dark:bg-danger-500/10 dark:text-danger-300' : 'bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-300' }}">
                {{ $this->getStokKritis() > 0 ? 'Perlu perhatian' : 'Terkendali' }}
            </span>
        </div>
    </div>
</x-filament-widgets::widget>
