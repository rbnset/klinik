{{-- resources/views/admin/suppliers/show.blade.php --}}

<div class="sp-wrapper">

    {{-- BREADCRUMB --}}
    <nav class="sp-breadcrumb" aria-label="breadcrumb">
        <a href="{{ filament()->getCurrentPanel()->getUrl() }}" class="sp-breadcrumb__link">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                <polyline points="9 22 9 12 15 12 15 22" />
            </svg>
            Dashboard
        </a>
        <span class="sp-breadcrumb__sep">›</span>
        <a href="{{ \App\Filament\Admin\Resources\Suppliers\SupplierResource::getUrl('index') }}"
            class="sp-breadcrumb__link">Master Supplier</a>
        <span class="sp-breadcrumb__sep">›</span>
        <span class="sp-breadcrumb__current">Detail</span>
    </nav>

    {{-- HEADER --}}
    <div class="sp-header">
        <div class="sp-header__icon-wrap">
            <svg class="sp-header__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
            </svg>
        </div>
        <div class="sp-header__text">
            <p class="sp-header__eyebrow">Master Data</p>
            <h1 class="sp-header__title">Detail Supplier</h1>
        </div>
        <div class="sp-header__actions">
            <a href="{{ \App\Filament\Admin\Resources\Suppliers\SupplierResource::getUrl('edit', ['record' => $record->id]) }}"
                class="sp-btn sp-btn--primary">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                </svg>
                Edit
            </a>
            <a href="{{ \App\Filament\Admin\Resources\Suppliers\SupplierResource::getUrl('index') }}"
                class="sp-btn sp-btn--ghost">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- MAIN GRID --}}
    <div class="sp-layout">

        {{-- KOLOM KIRI: identitas supplier --}}
        <div class="sp-col-main">

            {{-- Card hero: nama supplier --}}
            <div class="sp-card sp-animate-in">
                <div class="sp-card__accent-bar"></div>

                <div class="sp-card__status-row">
                    @if($record->pengguna)
                    <span class="sp-badge sp-badge--linked">
                        <span class="sp-badge__dot"></span>
                        Terhubung Portal
                    </span>
                    @else
                    <span class="sp-badge sp-badge--conventional">
                        Konvensional
                    </span>
                    @endif
                    <span class="sp-card__id-label">ID #{{ $record->id }}</span>
                </div>

                {{-- Nama supplier besar --}}
                <div class="sp-hero">
                    <div class="sp-hero__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                        </svg>
                    </div>
                    <div>
                        <p class="sp-hero__label">Nama Perusahaan / Supplier</p>
                        <h2 class="sp-hero__value">{{ $record->nama_supplier }}</h2>
                    </div>
                </div>

                {{-- Nomor Telepon --}}
                <div class="sp-info-row">
                    <div class="sp-info-row__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                        </svg>
                    </div>
                    <div>
                        <p class="sp-info-row__label">Nomor Telepon / WhatsApp</p>
                        <p class="sp-info-row__value">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $record->no_telp) }}" target="_blank"
                                class="sp-info-row__link">
                                {{ $record->no_telp }}
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6" />
                                    <polyline points="15 3 21 3 21 9" />
                                    <line x1="10" y1="14" x2="21" y2="3" />
                                </svg>
                            </a>
                        </p>
                    </div>
                </div>

                {{-- Alamat --}}
                <div class="sp-info-row">
                    <div class="sp-info-row__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="sp-info-row__label">Alamat Lengkap</p>
                        <p class="sp-info-row__value sp-info-row__value--address">{{ $record->alamat }}</p>
                    </div>
                </div>

            </div>
            {{-- end card identitas --}}

            {{-- Card timestamps --}}
            <div class="sp-card sp-card--slim sp-animate-in" style="animation-delay:.08s">
                <div class="sp-divider">
                    <span class="sp-divider__label">Informasi Waktu</span>
                </div>
                <div class="sp-time-grid">
                    <div class="sp-time-card">
                        <div class="sp-time-card__icon sp-time-card__icon--created">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                        </div>
                        <div>
                            <p class="sp-time-card__label">Didaftarkan Pada</p>
                            <p class="sp-time-card__date">{{ $record->created_at ?
                                $record->created_at->translatedFormat('d F Y') : '-' }}</p>
                            <p class="sp-time-card__time">{{ $record->created_at ? $record->created_at->format('H:i') .
                                ' WIB' : '' }}</p>
                            <p class="sp-time-card__relative">{{ $record->created_at ?
                                $record->created_at->diffForHumans() : '' }}</p>
                        </div>
                    </div>
                    <div class="sp-time-card">
                        <div class="sp-time-card__icon sp-time-card__icon--updated">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                        </div>
                        <div>
                            <p class="sp-time-card__label">Terakhir Diubah</p>
                            <p class="sp-time-card__date">{{ $record->updated_at ?
                                $record->updated_at->translatedFormat('d F Y') : '-' }}</p>
                            <p class="sp-time-card__time">{{ $record->updated_at ? $record->updated_at->format('H:i') .
                                ' WIB' : '' }}</p>
                            <p class="sp-time-card__relative">{{ $record->updated_at ?
                                $record->updated_at->diffForHumans() : '' }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        {{-- end kolom kiri --}}

        {{-- KOLOM KANAN: akun portal & stats --}}
        <div class="sp-col-side">

            {{-- Card: total transaksi/pembelian (contoh stat) --}}
            <div class="sp-card sp-card--stat sp-animate-in" style="animation-delay:.05s">
                <div class="sp-stat__icon sp-stat__icon--order">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                    </svg>
                </div>
                <p class="sp-stat__label">Total Pembelian</p>
                @php
                $totalPembelian = $record->pembelian_obat_count ?? $record->pembelian_obat()->count();
                @endphp
                <p class="sp-stat__value">{{ number_format($totalPembelian) }}</p>
                <p class="sp-stat__unit">transaksi tercatat</p>
            </div>

            {{-- Card: akun portal --}}
            <div class="sp-card sp-card--portal sp-animate-in" style="animation-delay:.10s">
                <div class="sp-divider">
                    <span class="sp-divider__label">Akses Portal Digital</span>
                </div>

                @if($record->pengguna)
                <div class="sp-portal-user">
                    <div class="sp-portal-user__avatar">
                        {{ strtoupper(substr($record->pengguna->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="sp-portal-user__name">{{ $record->pengguna->name }}</p>
                        <p class="sp-portal-user__email">{{ $record->pengguna->email }}</p>
                    </div>
                </div>
                <span class="sp-badge sp-badge--linked" style="width:100%; justify-content:center; margin-top:.75rem">
                    <span class="sp-badge__dot"></span>
                    Akun Aktif
                </span>
                @else
                <div class="sp-portal-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                    <p class="sp-portal-empty__text">Tidak ada akun portal yang ditautkan</p>
                    <p class="sp-portal-empty__hint">Supplier ini bersifat konvensional</p>
                </div>
                @endif
            </div>

        </div>
        {{-- end kolom kanan --}}

    </div>
    {{-- end layout --}}

</div>

@push('styles')
<style>
    :root {
        --sp-primary: #DF2228;
        --sp-primary-dim: rgba(223, 34, 40, .10);
        --sp-primary-glow: rgba(223, 34, 40, .22);
        --sp-primary-border: rgba(223, 34, 40, .28);
        --sp-primary-hover: #c41e23;

        --sp-green: #10b981;
        --sp-green-dim: rgba(16, 185, 129, .12);
        --sp-amber: #f59e0b;
        --sp-amber-dim: rgba(245, 158, 11, .12);
        --sp-blue: #3b82f6;
        --sp-blue-dim: rgba(59, 130, 246, .12);
        --sp-purple: #8b5cf6;
        --sp-purple-dim: rgba(139, 92, 246, .12);

        --sp-surface: #1a1012;
        --sp-surface-2: #221518;
        --sp-border: rgba(223, 34, 40, .13);

        --sp-text-primary: #faf5f5;
        --sp-text-muted: #7a6568;
        --sp-text-subtle: #a89295;

        --sp-radius: 16px;
        --sp-radius-sm: 10px;
        --sp-shadow: 0 20px 45px rgba(0, 0, 0, .5), 0 0 0 1px var(--sp-border);
    }

    /* ── Layout ── */
    .sp-wrapper {
        max-width: 960px;
        margin: 0 auto;
        padding: 2rem 1.5rem 4rem;
        font-family: 'Inter', system-ui, sans-serif;
        color: var(--sp-text-primary);
    }

    .sp-layout {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 1.25rem;
        align-items: start;
    }

    @media(max-width: 700px) {
        .sp-layout {
            grid-template-columns: 1fr;
        }

        .sp-col-side {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
    }

    @media(max-width: 480px) {
        .sp-col-side {
            grid-template-columns: 1fr;
        }
    }

    .sp-col-main {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .sp-col-side {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    /* ── Breadcrumb ── */
    .sp-breadcrumb {
        display: flex;
        align-items: center;
        gap: .45rem;
        margin-bottom: 1.75rem;
        font-size: .75rem;
        color: var(--sp-text-muted);
        letter-spacing: .04em;
    }

    .sp-breadcrumb__link {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        color: var(--sp-text-muted);
        text-decoration: none;
        transition: color .2s;
    }

    .sp-breadcrumb__link:hover {
        color: var(--sp-primary);
    }

    .sp-breadcrumb__sep {
        opacity: .3;
    }

    .sp-breadcrumb__current {
        color: var(--sp-text-subtle);
    }

    /* ── Header ── */
    .sp-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.75rem;
        flex-wrap: wrap;
    }

    .sp-header__icon-wrap {
        flex-shrink: 0;
        width: 50px;
        height: 50px;
        background: var(--sp-primary-dim);
        border: 1px solid var(--sp-primary-border);
        border-radius: var(--sp-radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sp-header__icon {
        width: 24px;
        height: 24px;
        stroke: var(--sp-primary);
    }

    .sp-header__text {
        flex: 1;
    }

    .sp-header__eyebrow {
        font-size: .68rem;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--sp-primary);
        margin: 0 0 .15rem;
        font-weight: 600;
    }

    .sp-header__title {
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0;
        color: var(--sp-text-primary);
        line-height: 1.2;
    }

    .sp-header__actions {
        display: flex;
        gap: .6rem;
        flex-shrink: 0;
    }

    /* ── Tombol ── */
    .sp-btn {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .48rem 1rem;
        border-radius: var(--sp-radius-sm);
        font-size: .82rem;
        font-weight: 600;
        text-decoration: none;
        transition: all .2s;
        cursor: pointer;
        border: none;
    }

    .sp-btn--primary {
        background: var(--sp-primary);
        color: #fff;
    }

    .sp-btn--primary:hover {
        background: var(--sp-primary-hover);
        box-shadow: 0 0 20px var(--sp-primary-glow);
    }

    .sp-btn--ghost {
        background: transparent;
        color: var(--sp-text-subtle);
        border: 1px solid var(--sp-border);
    }

    .sp-btn--ghost:hover {
        border-color: var(--sp-primary);
        color: var(--sp-primary);
    }

    /* ── Card base ── */
    .sp-card {
        position: relative;
        background: var(--sp-surface);
        border: 1px solid var(--sp-border);
        border-radius: var(--sp-radius);
        box-shadow: var(--sp-shadow);
        padding: 1.75rem 1.5rem;
        overflow: hidden;
    }

    .sp-card::before {
        content: '';
        position: absolute;
        top: -70px;
        right: -70px;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, var(--sp-primary-glow) 0%, transparent 68%);
        pointer-events: none;
    }

    .sp-card--slim {
        padding: 1.25rem 1.5rem;
    }

    .sp-card--slim::before {
        display: none;
    }

    .sp-card--portal {
        padding: 1.25rem 1.5rem;
    }

    .sp-card--portal::before {
        display: none;
    }

    /* Accent bar kiri */
    .sp-card__accent-bar {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(to bottom, var(--sp-primary), #7b0d10);
        border-radius: var(--sp-radius) 0 0 var(--sp-radius);
    }

    /* ── Status row ── */
    .sp-card__status-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .sp-badge {
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

    .sp-badge--linked {
        background: var(--sp-green-dim);
        color: var(--sp-green);
        border: 1px solid rgba(16, 185, 129, .25);
    }

    .sp-badge--conventional {
        background: var(--sp-amber-dim);
        color: var(--sp-amber);
        border: 1px solid rgba(245, 158, 11, .25);
    }

    .sp-badge__dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--sp-green);
        box-shadow: 0 0 6px var(--sp-green);
        animation: sp-pulse 2s infinite;
    }

    @keyframes sp-pulse {

        0%,
        100% {
            opacity: 1
        }

        50% {
            opacity: .3
        }
    }

    .sp-card__id-label {
        font-size: .7rem;
        font-family: 'JetBrains Mono', 'Fira Code', monospace;
        color: var(--sp-text-muted);
        letter-spacing: .08em;
    }

    /* ── Hero ── */
    .sp-hero {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1.25rem;
        background: var(--sp-primary-dim);
        border: 1px solid var(--sp-primary-border);
        border-radius: var(--sp-radius-sm);
        margin-bottom: 1.25rem;
    }

    .sp-hero__icon {
        flex-shrink: 0;
        width: 52px;
        height: 52px;
        background: rgba(223, 34, 40, .18);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sp-hero__icon svg {
        width: 26px;
        height: 26px;
        stroke: var(--sp-primary);
    }

    .sp-hero__label {
        font-size: .65rem;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--sp-primary);
        font-weight: 600;
        margin: 0 0 .25rem;
    }

    .sp-hero__value {
        font-size: 1.55rem;
        font-weight: 800;
        margin: 0;
        color: var(--sp-text-primary);
        line-height: 1.2;
        letter-spacing: -.01em;
    }

    /* ── Info rows ── */
    .sp-info-row {
        display: flex;
        align-items: flex-start;
        gap: .85rem;
        padding: .85rem 0;
        border-top: 1px solid var(--sp-border);
    }

    .sp-info-row__icon {
        flex-shrink: 0;
        width: 34px;
        height: 34px;
        background: var(--sp-primary-dim);
        border: 1px solid var(--sp-primary-border);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: .1rem;
    }

    .sp-info-row__icon svg {
        width: 16px;
        height: 16px;
        stroke: var(--sp-primary);
    }

    .sp-info-row__label {
        font-size: .67rem;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--sp-text-muted);
        margin: 0 0 .2rem;
    }

    .sp-info-row__value {
        font-size: .95rem;
        font-weight: 600;
        color: var(--sp-text-primary);
        margin: 0;
    }

    .sp-info-row__value--address {
        font-weight: 400;
        font-size: .875rem;
        line-height: 1.6;
        color: var(--sp-text-subtle);
    }

    .sp-info-row__link {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        color: var(--sp-green);
        text-decoration: none;
        font-weight: 600;
        transition: opacity .2s;
    }

    .sp-info-row__link:hover {
        opacity: .75;
    }

    /* ── Stat cards (kanan) ── */
    .sp-card--stat {
        text-align: center;
        padding: 1.5rem 1.25rem;
    }

    .sp-card--stat::before {
        display: none;
    }

    .sp-stat__icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .sp-stat__icon svg {
        width: 22px;
        height: 22px;
    }

    .sp-stat__icon--order {
        background: var(--sp-blue-dim);
        border: 1px solid rgba(59, 130, 246, .22);
    }

    .sp-stat__icon--order svg {
        stroke: var(--sp-blue);
    }

    .sp-stat__label {
        font-size: .68rem;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--sp-text-muted);
        margin: 0 0 .5rem;
        font-weight: 600;
    }

    .sp-stat__value {
        font-size: 2.1rem;
        font-weight: 800;
        color: var(--sp-text-primary);
        margin: 0 0 .2rem;
        line-height: 1;
    }

    .sp-stat__unit {
        font-size: .72rem;
        color: var(--sp-text-muted);
        margin: 0;
    }

    /* ── Portal user card ── */
    .sp-portal-user {
        display: flex;
        align-items: center;
        gap: .85rem;
        padding: .85rem;
        background: var(--sp-surface-2);
        border: 1px solid var(--sp-border);
        border-radius: var(--sp-radius-sm);
    }

    .sp-portal-user__avatar {
        flex-shrink: 0;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: var(--sp-primary-dim);
        border: 1px solid var(--sp-primary-border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        font-weight: 800;
        color: var(--sp-primary);
    }

    .sp-portal-user__name {
        font-size: .88rem;
        font-weight: 700;
        color: var(--sp-text-primary);
        margin: 0 0 .15rem;
    }

    .sp-portal-user__email {
        font-size: .72rem;
        color: var(--sp-text-muted);
        margin: 0;
        font-family: 'JetBrains Mono', 'Fira Code', monospace;
    }

    /* ── Portal empty state ── */
    .sp-portal-empty {
        text-align: center;
        padding: 1.25rem .5rem;
    }

    .sp-portal-empty svg {
        width: 32px;
        height: 32px;
        stroke: var(--sp-text-muted);
        margin: 0 auto .75rem;
        display: block;
    }

    .sp-portal-empty__text {
        font-size: .8rem;
        font-weight: 600;
        color: var(--sp-text-subtle);
        margin: 0 0 .3rem;
    }

    .sp-portal-empty__hint {
        font-size: .7rem;
        color: var(--sp-text-muted);
        margin: 0;
        font-style: italic;
    }

    /* ── Divider ── */
    .sp-divider {
        display: flex;
        align-items: center;
        gap: .75rem;
        margin-bottom: 1.1rem;
    }

    .sp-divider::before,
    .sp-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--sp-border);
    }

    .sp-divider__label {
        font-size: .67rem;
        text-transform: uppercase;
        letter-spacing: .12em;
        color: var(--sp-text-muted);
        white-space: nowrap;
        font-weight: 600;
    }

    /* ── Time grid ── */
    .sp-time-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .85rem;
    }

    @media(max-width: 560px) {
        .sp-time-grid {
            grid-template-columns: 1fr;
        }
    }

    .sp-time-card {
        display: flex;
        align-items: flex-start;
        gap: .85rem;
        padding: .95rem;
        background: var(--sp-surface-2);
        border: 1px solid var(--sp-border);
        border-radius: var(--sp-radius-sm);
        transition: border-color .2s, box-shadow .2s;
    }

    .sp-time-card:hover {
        border-color: var(--sp-primary-border);
        box-shadow: 0 0 16px var(--sp-primary-glow);
    }

    .sp-time-card__icon {
        flex-shrink: 0;
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sp-time-card__icon svg {
        width: 16px;
        height: 16px;
    }

    .sp-time-card__icon--created {
        background: var(--sp-green-dim);
        border: 1px solid rgba(16, 185, 129, .2);
    }

    .sp-time-card__icon--created svg {
        stroke: var(--sp-green);
    }

    .sp-time-card__icon--updated {
        background: var(--sp-amber-dim);
        border: 1px solid rgba(245, 158, 11, .2);
    }

    .sp-time-card__icon--updated svg {
        stroke: var(--sp-amber);
    }

    .sp-time-card__label {
        font-size: .65rem;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--sp-text-muted);
        margin: 0 0 .3rem;
    }

    .sp-time-card__date {
        font-size: .88rem;
        font-weight: 700;
        color: var(--sp-text-primary);
        margin: 0 0 .1rem;
    }

    .sp-time-card__time {
        font-size: .72rem;
        font-family: 'JetBrains Mono', 'Fira Code', monospace;
        color: var(--sp-text-subtle);
        margin: 0 0 .2rem;
    }

    .sp-time-card__relative {
        font-size: .68rem;
        color: var(--sp-text-muted);
        margin: 0;
        font-style: italic;
    }

    /* ── Animasi ── */
    @keyframes sp-fade-up {
        from {
            opacity: 0;
            transform: translateY(14px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .sp-animate-in {
        animation: sp-fade-up .4s cubic-bezier(.22, .68, 0, 1.2) both;
    }

    @media(prefers-reduced-motion: reduce) {
        .sp-animate-in {
            animation: none;
        }

        .sp-badge__dot {
            animation: none;
        }
    }
</style>
@endpush