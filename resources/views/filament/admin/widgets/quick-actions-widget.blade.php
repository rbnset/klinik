<x-filament-widgets::widget>
    <x-filament::section
        heading="Akses Cepat"
        description="Mulai aktivitas utama tanpa harus mencari menu terlebih dahulu."
        icon="heroicon-o-bolt"
    >
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($actions as $action)
                <a href="{{ $action['url'] }}" class="group flex items-center gap-3 rounded-xl border border-gray-200 bg-white p-4 transition hover:-translate-y-0.5 hover:border-primary-300 hover:shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:hover:border-primary-700">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary-600 dark:bg-primary-500/10 dark:text-primary-400">
                        <x-filament::icon :icon="'heroicon-o-' . $action['icon']" class="h-5 w-5" />
                    </div>
                    <div class="min-w-0">
                        <div class="text-sm font-semibold text-gray-950 dark:text-white">{{ $action['label'] }}</div>
                        <div class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ $action['description'] }}</div>
                    </div>
                    <x-heroicon-m-chevron-right class="ml-auto h-4 w-4 shrink-0 text-gray-400 transition group-hover:translate-x-0.5 group-hover:text-primary-500" />
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
