<x-filament-widgets::widget>
    <x-filament::section class="border-l-4 border-l-danger-600 ring-danger-600/50">
        <div class="flex items-center gap-x-3 mb-4">
            <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-danger-600" />
            <h2 class="text-lg font-bold tracking-tight text-danger-600 dark:text-danger-400">
                Peringatan: Stok Obat Kritis
            </h2>
        </div>

        @if($obatKritis->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($obatKritis as $obat)
            <div
                class="p-4 rounded-xl bg-danger-50 dark:bg-danger-500/10 border border-danger-100 dark:border-danger-500/20">
                <div class="text-sm font-semibold text-gray-900 dark:text-white">
                    {{ $obat->nama_obat }}
                </div>
                <div class="flex justify-between items-end mt-2">
                    <span class="text-xs font-medium text-danger-600 dark:text-danger-400">
                        Sisa: <span class="text-lg font-bold">{{ $obat->stok }}</span> {{ $obat->satuan }}
                    </span>
                    <span class="text-xs text-gray-500">{{ $obat->kode_obat }}</span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Semua persediaan obat dalam kondisi aman. Tidak ada yang perlu dikhawatirkan saat ini.
        </p>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>