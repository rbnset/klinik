{{-- resources/views/admin/obat/show.blade.php --}}

<div class="ob-wrapper">

    {{-- BREADCRUMB --}}
    <nav class="ob-breadcrumb" aria-label="breadcrumb">
        <a href="{{ filament()->getCurrentPanel()->getUrl() }}" class="ob-breadcrumb__link">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                <polyline points="9 22 9 12 15 12 15 22" />
            </svg>
            Dashboard
        </a>
        <span class="ob-breadcrumb__sep">›</span>
        <a href="{{ \App\Filament\Admin\Resources\Obats\ObatResource::getUrl('index') }}"
            class="ob-breadcrumb__link">Master Obat</a>
        <span class="ob-breadcrumb__sep">›</span>
        <span class="ob-breadcrumb__current">Detail</span>
    </nav>

    {{-- HEADER --}}
    <div class="ob-header">
        <div class="ob-header__icon-wrap">
            <svg class="ob-header__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8 1.402 1.402c1 1 .03 2.798-1.348 2.798H6.148c-1.378 0-2.349-1.799-1.349-2.798L5 14.5" />
            </svg>
        </div>
        <div class="ob-header__text">
            <p class="ob-header__eyebrow">Master Data</p>
            <h1 class="ob-header__title">Detail Obat</h1>
        </div>
        <div class="ob-header__actions">
            <a href="{{ \App\Filament\Admin\Resources\Obats\ObatResource::getUrl('edit', ['record' => $record->id]) }}"
                class="ob-btn ob-btn--primary">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                </svg>
                Edit
            </a>
            <a href="{{ \App\Filament\Admin\Resources\Obats\ObatResource::getUrl('index') }}"
                class="ob-btn ob-btn--ghost">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- MAIN GRID --}}
    <div class="ob-layout">

        {{-- KOLOM KIRI: identitas produk --}}
        <div class="ob-col-main">

            {{-- Card hero: nama & kode --}}
            <div class="ob-card ob-animate-in">
                <div class="ob-card__accent-bar"></div>

                <div class="ob-card__status-row">
                    <span class="ob-badge ob-badge--active">
                        <span class="ob-badge__dot"></span>
                        Aktif
                    </span>
                    <span class="ob-card__id-label">ID #{{ $record->id }}</span>
                </div>

                {{-- Nama obat besar --}}
                <div class="ob-hero">
                    <div class="ob-hero__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8 1.402 1.402c1 1 .03 2.798-1.348 2.798H6.148c-1.378 0-2.349-1.799-1.349-2.798L5 14.5" />
                        </svg>
                    </div>
                    <div>
                        <p class="ob-hero__label">Nama Obat</p>
                        <h2 class="ob-hero__value">{{ $record->nama_obat }}</h2>
                        <span class="ob-hero__sku">{{ $record->kode_obat }}</span>
                    </div>
                </div>

                {{-- Kategori --}}
                <div class="ob-info-row">
                    <div class="ob-info-row__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L9.568 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                        </svg>
                    </div>
                    <div>
                        <p class="ob-info-row__label">Kategori Obat</p>
                        <p class="ob-info-row__value">{{ $record->kategori_obat?->nama_kategori ?? '-' }}</p>
                    </div>
                </div>

                {{-- Satuan --}}
                <div class="ob-info-row">
                    <div class="ob-info-row__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                    </div>
                    <div>
                        <p class="ob-info-row__label">Satuan Kemasan</p>
                        <p class="ob-info-row__value">{{ $record->satuan ?? '-' }}</p>
                    </div>
                </div>

            </div>
            {{-- end card identitas --}}

            {{-- Card timestamps --}}
            <div class="ob-card ob-card--slim ob-animate-in" style="animation-delay:.08s">
                <div class="ob-divider">
                    <span class="ob-divider__label">Informasi Waktu</span>
                </div>
                <div class="ob-time-grid">
                    <div class="ob-time-card">
                        <div class="ob-time-card__icon ob-time-card__icon--created">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                        </div>
                        <div>
                            <p class="ob-time-card__label">Didaftarkan Pada</p>
                            <p class="ob-time-card__date">{{ $record->created_at ?
                                $record->created_at->translatedFormat('d F Y') : '-' }}</p>
                            <p class="ob-time-card__time">{{ $record->created_at ? $record->created_at->format('H:i') .
                                ' WIB' : '' }}</p>
                            <p class="ob-time-card__relative">{{ $record->created_at ?
                                $record->created_at->diffForHumans() : '' }}</p>
                        </div>
                    </div>
                    <div class="ob-time-card">
                        <div class="ob-time-card__icon ob-time-card__icon--updated">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                        </div>
                        <div>
                            <p class="ob-time-card__label">Terakhir Diubah</p>
                            <p class="ob-time-card__date">{{ $record->updated_at ?
                                $record->updated_at->translatedFormat('d F Y') : '-' }}</p>
                            <p class="ob-time-card__time">{{ $record->updated_at ? $record->updated_at->format('H:i') .
                                ' WIB' : '' }}</p>
                            <p class="ob-time-card__relative">{{ $record->updated_at ?
                                $record->updated_at->diffForHumans() : '' }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        {{-- end kolom kiri --}}

        {{-- KOLOM KANAN: stok & harga --}}
        <div class="ob-col-side">

            {{-- Stok --}}
            <div class="ob-card ob-card--stat ob-animate-in" style="animation-delay:.05s">
                <div class="ob-stat__icon ob-stat__icon--stok">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 5.625c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                    </svg>
                </div>
                <p class="ob-stat__label">Stok Tersedia</p>
                <p class="ob-stat__value">{{ number_format($record->stok ?? 0) }}</p>
                <p class="ob-stat__unit">{{ $record->satuan ?? 'unit' }}</p>

                {{-- Progress bar visual stok --}}
                @php
                $stok = $record->stok ?? 0;
                $stokMax = 500; // referensi visual
                $pct = min(100, ($stok / $stokMax) * 100);
                $stokStatus = $stok <= 0 ? 'empty' : ($stok <=20 ? 'low' : ($stok <=100 ? 'medium' : 'good' )); @endphp
                    <div class="ob-stok-bar">
                    <div class="ob-stok-bar__fill ob-stok-bar__fill--{{ $stokStatus }}" style="width: {{ $pct }}%">
                    </div>
            </div>
            <p class="ob-stok-bar__hint ob-stok-hint--{{ $stokStatus }}">
                @if($stokStatus === 'empty') Stok habis
                @elseif($stokStatus === 'low') Stok menipis
                @elseif($stokStatus === 'medium') Stok cukup
                @else Stok aman
                @endif
            </p>
        </div>

        {{-- Harga Beli --}}
        <div class="ob-card ob-card--stat ob-animate-in" style="animation-delay:.1s">
            <div class="ob-stat__icon ob-stat__icon--harga">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                </svg>
            </div>
            <p class="ob-stat__label">Harga Beli / Satuan</p>
            <p class="ob-stat__value ob-stat__value--harga">
                <span class="ob-stat__currency">Rp</span>{{ number_format($record->harga_beli ?? 0, 0, ',', '.') }}
            </p>
            <p class="ob-stat__unit">per {{ $record->satuan ?? 'unit' }}</p>
        </div>

        {{-- Kode SKU card kecil --}}
        <div class="ob-card ob-card--sku ob-animate-in" style="animation-delay:.14s">
            <p class="ob-sku__label">Kode / SKU</p>
            <p class="ob-sku__value">{{ $record->kode_obat }}</p>
        </div>

    </div>
    {{-- end kolom kanan --}}

</div>
{{-- end layout --}}

</div>

@push('styles')
<style>
    :root {
        --ob-primary: #DF2228;
        --ob-primary-dim: rgba(223, 34, 40, .10);
        --ob-primary-glow: rgba(223, 34, 40, .22);
        --ob-primary-border: rgba(223, 34, 40, .28);
        --ob-primary-hover: #c41e23;

        --ob-green: #10b981;
        --ob-green-dim: rgba(16, 185, 129, .12);
        --ob-amber: #f59e0b;
        --ob-amber-dim: rgba(245, 158, 11, .12);
        --ob-blue: #3b82f6;
        --ob-blue-dim: rgba(59, 130, 246, .12);

        --ob-surface: #1a1012;
        --ob-surface-2: #221518;
        --ob-border: rgba(223, 34, 40, .13);

        --ob-text-primary: #faf5f5;
        --ob-text-muted: #7a6568;
        --ob-text-subtle: #a89295;

        --ob-radius: 16px;
        --ob-radius-sm: 10px;
        --ob-shadow: 0 20px 45px rgba(0, 0, 0, .5), 0 0 0 1px var(--ob-border);
    }

    /* ── Layout ── */
    .ob-wrapper {
        max-width: 960px;
        margin: 0 auto;
        padding: 2rem 1.5rem 4rem;
        font-family: 'Inter', system-ui, sans-serif;
        color: var(--ob-text-primary);
    }

    .ob-layout {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 1.25rem;
        align-items: start;
    }

    @media(max-width: 700px) {
        .ob-layout {
            grid-template-columns: 1fr;
        }

        .ob-col-side {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
    }

    @media(max-width: 480px) {
        .ob-col-side {
            grid-template-columns: 1fr;
        }
    }

    .ob-col-main {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .ob-col-side {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    /* ── Breadcrumb ── */
    .ob-breadcrumb {
        display: flex;
        align-items: center;
        gap: .45rem;
        margin-bottom: 1.75rem;
        font-size: .75rem;
        color: var(--ob-text-muted);
        letter-spacing: .04em;
    }

    .ob-breadcrumb__link {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        color: var(--ob-text-muted);
        text-decoration: none;
        transition: color .2s;
    }

    .ob-breadcrumb__link:hover {
        color: var(--ob-primary);
    }

    .ob-breadcrumb__sep {
        opacity: .3;
    }

    .ob-breadcrumb__current {
        color: var(--ob-text-subtle);
    }

    /* ── Header ── */
    .ob-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.75rem;
        flex-wrap: wrap;
    }

    .ob-header__icon-wrap {
        flex-shrink: 0;
        width: 50px;
        height: 50px;
        background: var(--ob-primary-dim);
        border: 1px solid var(--ob-primary-border);
        border-radius: var(--ob-radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ob-header__icon {
        width: 24px;
        height: 24px;
        stroke: var(--ob-primary);
    }

    .ob-header__text {
        flex: 1;
    }

    .ob-header__eyebrow {
        font-size: .68rem;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--ob-primary);
        margin: 0 0 .15rem;
        font-weight: 600;
    }

    .ob-header__title {
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0;
        color: var(--ob-text-primary);
        line-height: 1.2;
    }

    .ob-header__actions {
        display: flex;
        gap: .6rem;
        flex-shrink: 0;
    }

    /* ── Tombol ── */
    .ob-btn {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .48rem 1rem;
        border-radius: var(--ob-radius-sm);
        font-size: .82rem;
        font-weight: 600;
        text-decoration: none;
        transition: all .2s;
        cursor: pointer;
        border: none;
    }

    .ob-btn--primary {
        background: var(--ob-primary);
        color: #fff;
    }

    .ob-btn--primary:hover {
        background: var(--ob-primary-hover);
        box-shadow: 0 0 20px var(--ob-primary-glow);
    }

    .ob-btn--ghost {
        background: transparent;
        color: var(--ob-text-subtle);
        border: 1px solid var(--ob-border);
    }

    .ob-btn--ghost:hover {
        border-color: var(--ob-primary);
        color: var(--ob-primary);
    }

    /* ── Card base ── */
    .ob-card {
        position: relative;
        background: var(--ob-surface);
        border: 1px solid var(--ob-border);
        border-radius: var(--ob-radius);
        box-shadow: var(--ob-shadow);
        padding: 1.75rem 1.5rem;
        overflow: hidden;
    }

    .ob-card::before {
        content: '';
        position: absolute;
        top: -70px;
        right: -70px;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, var(--ob-primary-glow) 0%, transparent 68%);
        pointer-events: none;
    }

    .ob-card--slim {
        padding: 1.25rem 1.5rem;
    }

    .ob-card--slim::before {
        display: none;
    }

    /* Accent bar kiri */
    .ob-card__accent-bar {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(to bottom, var(--ob-primary), #7b0d10);
        border-radius: var(--ob-radius) 0 0 var(--ob-radius);
    }

    /* ── Status row ── */
    .ob-card__status-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .ob-badge {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .28rem .75rem;
        border-radius: 999px;
        font-size: .7rem;
        font-weight: 600;
        letter-spacing: .06em;
        text-transform: uppercase;
    }

    .ob-badge--active {
        background: var(--ob-green-dim);
        color: var(--ob-green);
        border: 1px solid rgba(16, 185, 129, .25);
    }

    .ob-badge__dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--ob-green);
        box-shadow: 0 0 6px var(--ob-green);
        animation: ob-pulse 2s infinite;
    }

    @keyframes ob-pulse {

        0%,
        100% {
            opacity: 1
        }

        50% {
            opacity: .3
        }
    }

    .ob-card__id-label {
        font-size: .7rem;
        font-family: 'JetBrains Mono', 'Fira Code', monospace;
        color: var(--ob-text-muted);
        letter-spacing: .08em;
    }

    /* ── Hero ── */
    .ob-hero {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1.25rem;
        background: var(--ob-primary-dim);
        border: 1px solid var(--ob-primary-border);
        border-radius: var(--ob-radius-sm);
        margin-bottom: 1.25rem;
    }

    .ob-hero__icon {
        flex-shrink: 0;
        width: 52px;
        height: 52px;
        background: rgba(223, 34, 40, .18);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ob-hero__icon svg {
        width: 26px;
        height: 26px;
        stroke: var(--ob-primary);
    }

    .ob-hero__label {
        font-size: .65rem;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--ob-primary);
        font-weight: 600;
        margin: 0 0 .25rem;
    }

    .ob-hero__value {
        font-size: 1.55rem;
        font-weight: 800;
        margin: 0 0 .4rem;
        color: var(--ob-text-primary);
        line-height: 1.15;
        letter-spacing: -.01em;
    }

    .ob-hero__sku {
        display: inline-block;
        padding: .2rem .65rem;
        background: var(--ob-surface-2);
        border: 1px solid var(--ob-border);
        border-radius: 6px;
        font-size: .72rem;
        font-family: 'JetBrains Mono', 'Fira Code', monospace;
        color: var(--ob-text-subtle);
        letter-spacing: .06em;
    }

    /* ── Info rows ── */
    .ob-info-row {
        display: flex;
        align-items: center;
        gap: .85rem;
        padding: .85rem 0;
        border-top: 1px solid var(--ob-border);
    }

    .ob-info-row__icon {
        flex-shrink: 0;
        width: 34px;
        height: 34px;
        background: var(--ob-primary-dim);
        border: 1px solid var(--ob-primary-border);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ob-info-row__icon svg {
        width: 16px;
        height: 16px;
        stroke: var(--ob-primary);
    }

    .ob-info-row__label {
        font-size: .67rem;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--ob-text-muted);
        margin: 0 0 .2rem;
    }

    .ob-info-row__value {
        font-size: .95rem;
        font-weight: 600;
        color: var(--ob-text-primary);
        margin: 0;
    }

    /* ── Stat cards (kanan) ── */
    .ob-card--stat {
        text-align: center;
        padding: 1.5rem 1.25rem;
    }

    .ob-card--stat::before {
        display: none;
    }

    .ob-stat__icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .ob-stat__icon svg {
        width: 22px;
        height: 22px;
    }

    .ob-stat__icon--stok {
        background: var(--ob-blue-dim);
        border: 1px solid rgba(59, 130, 246, .22);
    }

    .ob-stat__icon--stok svg {
        stroke: var(--ob-blue);
    }

    .ob-stat__icon--harga {
        background: var(--ob-primary-dim);
        border: 1px solid var(--ob-primary-border);
    }

    .ob-stat__icon--harga svg {
        stroke: var(--ob-primary);
    }

    .ob-stat__label {
        font-size: .68rem;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--ob-text-muted);
        margin: 0 0 .5rem;
        font-weight: 600;
    }

    .ob-stat__value {
        font-size: 2.1rem;
        font-weight: 800;
        color: var(--ob-text-primary);
        margin: 0 0 .2rem;
        line-height: 1;
    }

    .ob-stat__value--harga {
        font-size: 1.5rem;
    }

    .ob-stat__currency {
        font-size: 1rem;
        font-weight: 600;
        margin-right: .1rem;
        color: var(--ob-text-subtle);
    }

    .ob-stat__unit {
        font-size: .72rem;
        color: var(--ob-text-muted);
        margin: 0 0 .9rem;
    }

    /* Stok bar */
    .ob-stok-bar {
        height: 5px;
        background: var(--ob-surface-2);
        border-radius: 999px;
        overflow: hidden;
        margin-bottom: .5rem;
    }

    .ob-stok-bar__fill {
        height: 100%;
        border-radius: 999px;
        transition: width .6s ease;
    }

    .ob-stok-bar__fill--empty {
        background: var(--ob-primary);
        width: 0% !important;
    }

    .ob-stok-bar__fill--low {
        background: var(--ob-primary);
    }

    .ob-stok-bar__fill--medium {
        background: var(--ob-amber);
    }

    .ob-stok-bar__fill--good {
        background: var(--ob-green);
    }

    .ob-stok-bar__hint {
        font-size: .68rem;
        font-weight: 600;
        letter-spacing: .05em;
    }

    .ob-stok-hint--empty {
        color: var(--ob-primary);
    }

    .ob-stok-hint--low {
        color: var(--ob-primary);
    }

    .ob-stok-hint--medium {
        color: var(--ob-amber);
    }

    .ob-stok-hint--good {
        color: var(--ob-green);
    }

    /* SKU card kecil */
    .ob-card--sku {
        padding: 1rem 1.25rem;
        text-align: center;
    }

    .ob-card--sku::before {
        display: none;
    }

    .ob-sku__label {
        font-size: .65rem;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--ob-text-muted);
        margin: 0 0 .4rem;
    }

    .ob-sku__value {
        font-size: 1.1rem;
        font-weight: 700;
        font-family: 'JetBrains Mono', 'Fira Code', monospace;
        color: var(--ob-primary);
        letter-spacing: .08em;
    }

    /* ── Divider ── */
    .ob-divider {
        display: flex;
        align-items: center;
        gap: .75rem;
        margin-bottom: 1.1rem;
    }

    .ob-divider::before,
    .ob-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--ob-border);
    }

    .ob-divider__label {
        font-size: .67rem;
        text-transform: uppercase;
        letter-spacing: .12em;
        color: var(--ob-text-muted);
        white-space: nowrap;
        font-weight: 600;
    }

    /* ── Time grid ── */
    .ob-time-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .85rem;
    }

    @media(max-width: 560px) {
        .ob-time-grid {
            grid-template-columns: 1fr;
        }
    }

    .ob-time-card {
        display: flex;
        align-items: flex-start;
        gap: .85rem;
        padding: .95rem;
        background: var(--ob-surface-2);
        border: 1px solid var(--ob-border);
        border-radius: var(--ob-radius-sm);
        transition: border-color .2s, box-shadow .2s;
    }

    .ob-time-card:hover {
        border-color: var(--ob-primary-border);
        box-shadow: 0 0 16px var(--ob-primary-glow);
    }

    .ob-time-card__icon {
        flex-shrink: 0;
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ob-time-card__icon svg {
        width: 16px;
        height: 16px;
    }

    .ob-time-card__icon--created {
        background: var(--ob-green-dim);
        border: 1px solid rgba(16, 185, 129, .2);
    }

    .ob-time-card__icon--created svg {
        stroke: var(--ob-green);
    }

    .ob-time-card__icon--updated {
        background: var(--ob-amber-dim);
        border: 1px solid rgba(245, 158, 11, .2);
    }

    .ob-time-card__icon--updated svg {
        stroke: var(--ob-amber);
    }

    .ob-time-card__label {
        font-size: .65rem;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--ob-text-muted);
        margin: 0 0 .3rem;
    }

    .ob-time-card__date {
        font-size: .88rem;
        font-weight: 700;
        color: var(--ob-text-primary);
        margin: 0 0 .1rem;
    }

    .ob-time-card__time {
        font-size: .72rem;
        font-family: 'JetBrains Mono', 'Fira Code', monospace;
        color: var(--ob-text-subtle);
        margin: 0 0 .2rem;
    }

    .ob-time-card__relative {
        font-size: .68rem;
        color: var(--ob-text-muted);
        margin: 0;
        font-style: italic;
    }

    /* ── Animasi ── */
    @keyframes ob-fade-up {
        from {
            opacity: 0;
            transform: translateY(14px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .ob-animate-in {
        animation: ob-fade-up .4s cubic-bezier(.22, .68, 0, 1.2) both;
    }

    @media(prefers-reduced-motion:reduce) {
        .ob-animate-in {
            animation: none;
        }

        .ob-badge__dot {
            animation: none;
        }
    }
</style>
@endpush