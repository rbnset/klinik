<x-filament-widgets::widget>
    <div
        class="relative overflow-hidden rounded-2xl border border-gray-200/60 bg-gradient-to-br from-primary-600 via-primary-500 to-indigo-600 shadow-sm dark:border-white/10"
    >
        {{-- Dekorasi blob cahaya --}}
        <div class="pointer-events-none absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-20 -left-10 h-56 w-56 rounded-full bg-white/10 blur-3xl"></div>
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(255,255,255,0.15),_transparent_50%)]"></div>

        <div class="relative flex flex-col gap-6 p-6 sm:flex-row sm:items-center sm:justify-between sm:p-8">

            {{-- Kiri: Avatar + Sapaan --}}
            <div class="flex items-center gap-4">
                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/20 text-lg font-bold text-white ring-1 ring-white/30 backdrop-blur-md sm:h-16 sm:w-16 sm:text-xl"
                >
                    {{ $this->getInitials() }}
                </div>

                <div>
                    <p class="flex items-center gap-1.5 text-sm font-medium text-white/80">
                        <span>{{ $this->getGreeting() }}</span>
                        <span>{{ $this->getGreetingEmoji() }}</span>
                    </p>
                    <h2 class="mt-0.5 text-xl font-bold leading-tight tracking-tight text-white sm:text-2xl">
                        {{ $this->getUserName() }}
                    </h2>
                    <p class="mt-1 text-sm text-white/70">
                        {{ ucfirst(now('Asia/Jakarta')->translatedFormat('l, d F Y')) }}
                    </p>
                </div>
            </div>

            {{-- Kanan: Jam real-time --}}
            <div
                x-data="{ time: '' }"
                x-init="
                    const update = () => {
                        time = new Date().toLocaleTimeString('id-ID', {
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit',
                        });
                    };
                    update();
                    setInterval(update, 1000);
                "
                class="flex items-center gap-2 self-start rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-sm font-medium text-white backdrop-blur-md sm:self-auto"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white/80" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z" />
                </svg>
                <span x-text="time" class="tabular-nums"></span>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
