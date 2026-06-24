{{-- resources/views/admin/kategori-obat/show.blade.php --}}

<div class="ko-wrapper">

    {{-- BREADCRUMB --}}
    <nav class="ko-breadcrumb" aria-label="breadcrumb">
        <a href="{{ filament()->getCurrentPanel()->getUrl() }}" class="ko-breadcrumb__link">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                <polyline points="9 22 9 12 15 12 15 22" />
            </svg>
            Dashboard
        </a>
        <span class="ko-breadcrumb__sep">›</span>
        <a href="{{ \App\Filament\Admin\Resources\KategoriObats\KategoriObatResource::getUrl('index') }}"
            class="ko-breadcrumb__link">Kategori Obat</a>
        <span class="ko-breadcrumb__sep">›</span>
        <span class="ko-breadcrumb__current">Detail</span>
    </nav>

    {{-- HEADER --}}
    <div class="ko-header">
        <div class="ko-header__icon-wrap">
            <svg class="ko-header__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12h6m-3-3v6m-7.5 3.75A2.25 2.25 0 002.25 18V6A2.25 2.25 0 014.5 3.75h15A2.25 2.25 0 0121.75 6v12a2.25 2.25 0 01-2.25 2.25h-15z" />
            </svg>
        </div>
        <div class="ko-header__text">
            <p class="ko-header__eyebrow">Manajemen Farmasi</p>
            <h1 class="ko-header__title">Detail Kategori Obat</h1>
        </div>
        <div class="ko-header__actions">
            <a href="{{ \App\Filament\Admin\Resources\KategoriObats\KategoriObatResource::getUrl('edit', ['record' => $record->id]) }}"
                class="ko-btn ko-btn--primary">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                </svg>
                Edit
            </a>
            <a href="{{ \App\Filament\Admin\Resources\KategoriObats\KategoriObatResource::getUrl('index') }}"
                class="ko-btn ko-btn--ghost">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- MAIN CARD --}}
    <div class="ko-card ko-animate-in">

        <div class="ko-card__accent-bar"></div>

        <div class="ko-card__status-row">
            <span class="ko-badge ko-badge--active">
                <span class="ko-badge__dot"></span>
                Aktif
            </span>
            <span class="ko-card__id-label">ID #{{ $record->id }}</span>
        </div>

        {{-- HERO --}}
        <div class="ko-hero">
            <div class="ko-hero__pill-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L9.568 3z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                </svg>
            </div>
            <div class="ko-hero__content">
                <p class="ko-hero__label">Nama Kategori</p>
                <h2 class="ko-hero__value">{{ $record->nama_kategori }}</h2>
            </div>
        </div>

        <div class="ko-divider">
            <span class="ko-divider__label">Informasi Waktu</span>
        </div>

        {{-- TIMESTAMP GRID --}}
        <div class="ko-time-grid">

            <div class="ko-time-card">
                <div class="ko-time-card__icon ko-time-card__icon--created">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
                <div class="ko-time-card__content">
                    <p class="ko-time-card__label">Didaftarkan Pada</p>
                    <p class="ko-time-card__date">
                        {{ $record->created_at ? $record->created_at->translatedFormat('d F Y') : '-' }}
                    </p>
                    <p class="ko-time-card__time">
                        {{ $record->created_at ? $record->created_at->format('H:i') . ' WIB' : '' }}
                    </p>
                    <p class="ko-time-card__relative">
                        {{ $record->created_at ? $record->created_at->diffForHumans() : '' }}
                    </p>
                </div>
            </div>

            <div class="ko-time-card">
                <div class="ko-time-card__icon ko-time-card__icon--updated">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                </div>
                <div class="ko-time-card__content">
                    <p class="ko-time-card__label">Terakhir Diubah</p>
                    <p class="ko-time-card__date">
                        {{ $record->updated_at ? $record->updated_at->translatedFormat('d F Y') : '-' }}
                    </p>
                    <p class="ko-time-card__time">
                        {{ $record->updated_at ? $record->updated_at->format('H:i') . ' WIB' : '' }}
                    </p>
                    <p class="ko-time-card__relative">
                        {{ $record->updated_at ? $record->updated_at->diffForHumans() : '' }}
                    </p>
                </div>
            </div>

        </div>

    </div>

</div>

@push('styles')
<style>
    :root {
        /* ── Brand Bidan Delima ── */
        --ko-primary: #DF2228;
        --ko-primary-dim: rgba(223, 34, 40, 0.10);
        --ko-primary-glow: rgba(223, 34, 40, 0.22);
        --ko-primary-border: rgba(223, 34, 40, 0.30);
        --ko-primary-hover: #c41e23;

        /* ── Accent sekunder (tetap netral) ── */
        --ko-green: #10b981;
        --ko-green-dim: rgba(16, 185, 129, 0.12);
        --ko-amber: #f59e0b;
        --ko-amber-dim: rgba(245, 158, 11, 0.12);

        /* ── Surface (gelap elegan) ── */
        --ko-surface: #1a1012;
        /* hitam hangat — hint merah */
        --ko-surface-2: #221518;
        /* sedikit lebih terang */
        --ko-border: rgba(223, 34, 40, 0.12);

        /* ── Tipografi ── */
        --ko-text-primary: #faf5f5;
        --ko-text-muted: #7a6568;
        --ko-text-subtle: #a89295;

        --ko-radius: 16px;
        --ko-radius-sm: 10px;
        --ko-shadow: 0 25px 50px rgba(0, 0, 0, .55), 0 0 0 1px var(--ko-border);
    }

    /* ── Layout ── */
    .ko-wrapper {
        max-width: 820px;
        margin: 0 auto;
        padding: 2rem 1.5rem 4rem;
        font-family: 'Inter', system-ui, sans-serif;
        color: var(--ko-text-primary);
    }

    /* ── Breadcrumb ── */
    .ko-breadcrumb {
        display: flex;
        align-items: center;
        gap: .45rem;
        margin-bottom: 2rem;
        font-size: .75rem;
        color: var(--ko-text-muted);
        letter-spacing: .04em;
    }

    .ko-breadcrumb__link {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        color: var(--ko-text-muted);
        text-decoration: none;
        transition: color .2s;
    }

    .ko-breadcrumb__link:hover {
        color: var(--ko-primary);
    }

    .ko-breadcrumb__sep {
        color: var(--ko-border);
    }

    .ko-breadcrumb__current {
        color: var(--ko-text-subtle);
    }

    /* ── Header ── */
    .ko-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .ko-header__icon-wrap {
        flex-shrink: 0;
        width: 52px;
        height: 52px;
        background: var(--ko-primary-dim);
        border: 1px solid var(--ko-primary-border);
        border-radius: var(--ko-radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ko-header__icon {
        width: 26px;
        height: 26px;
        stroke: var(--ko-primary);
    }

    .ko-header__text {
        flex: 1;
    }

    .ko-header__eyebrow {
        font-size: .7rem;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--ko-primary);
        margin: 0 0 .2rem;
        font-weight: 600;
    }

    .ko-header__title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        color: var(--ko-text-primary);
        line-height: 1.2;
    }

    .ko-header__actions {
        display: flex;
        gap: .6rem;
        flex-shrink: 0;
    }

    /* ── Tombol ── */
    .ko-btn {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .5rem 1.1rem;
        border-radius: var(--ko-radius-sm);
        font-size: .82rem;
        font-weight: 600;
        text-decoration: none;
        transition: all .2s;
        cursor: pointer;
        border: none;
    }

    .ko-btn--primary {
        background: var(--ko-primary);
        color: #fff;
    }

    .ko-btn--primary:hover {
        background: var(--ko-primary-hover);
        box-shadow: 0 0 22px var(--ko-primary-glow);
    }

    .ko-btn--ghost {
        background: transparent;
        color: var(--ko-text-subtle);
        border: 1px solid var(--ko-border);
    }

    .ko-btn--ghost:hover {
        border-color: var(--ko-primary);
        color: var(--ko-primary);
    }

    /* ── Card utama ── */
    .ko-card {
        position: relative;
        background: var(--ko-surface);
        border: 1px solid var(--ko-border);
        border-radius: var(--ko-radius);
        box-shadow: var(--ko-shadow);
        padding: 2.25rem 2rem;
        overflow: hidden;
    }

    /* Glow merah subtle di kanan atas */
    .ko-card::before {
        content: '';
        position: absolute;
        top: -90px;
        right: -90px;
        width: 320px;
        height: 320px;
        background: radial-gradient(circle, var(--ko-primary-glow) 0%, transparent 68%);
        pointer-events: none;
    }

    /* Accent bar merah kiri */
    .ko-card__accent-bar {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(to bottom, var(--ko-primary), #7b0d10);
        border-radius: var(--ko-radius) 0 0 var(--ko-radius);
    }

    /* Status row */
    .ko-card__status-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.75rem;
    }

    .ko-badge {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .3rem .8rem;
        border-radius: 999px;
        font-size: .72rem;
        font-weight: 600;
        letter-spacing: .06em;
        text-transform: uppercase;
    }

    .ko-badge--active {
        background: var(--ko-green-dim);
        color: var(--ko-green);
        border: 1px solid rgba(16, 185, 129, .25);
    }

    .ko-badge__dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--ko-green);
        box-shadow: 0 0 6px var(--ko-green);
        animation: ko-pulse 2s infinite;
    }

    @keyframes ko-pulse {

        0%,
        100% {
            opacity: 1
        }

        50% {
            opacity: .35
        }
    }

    .ko-card__id-label {
        font-size: .72rem;
        font-family: 'JetBrains Mono', 'Fira Code', monospace;
        color: var(--ko-text-muted);
        letter-spacing: .08em;
    }

    /* ── Hero (nama kategori) ── */
    .ko-hero {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        padding: 1.5rem;
        background: var(--ko-primary-dim);
        border: 1px solid var(--ko-primary-border);
        border-radius: var(--ko-radius-sm);
        margin-bottom: 2rem;
    }

    .ko-hero__pill-icon {
        flex-shrink: 0;
        width: 56px;
        height: 56px;
        background: rgba(223, 34, 40, .18);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ko-hero__pill-icon svg {
        width: 28px;
        height: 28px;
        stroke: var(--ko-primary);
    }

    .ko-hero__label {
        font-size: .7rem;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--ko-primary);
        font-weight: 600;
        margin: 0 0 .35rem;
    }

    .ko-hero__value {
        font-size: 1.85rem;
        font-weight: 800;
        margin: 0;
        color: var(--ko-text-primary);
        letter-spacing: -.01em;
        line-height: 1.1;
    }

    /* ── Divider ── */
    .ko-divider {
        display: flex;
        align-items: center;
        gap: .75rem;
        margin-bottom: 1.5rem;
    }

    .ko-divider::before,
    .ko-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--ko-border);
    }

    .ko-divider__label {
        font-size: .68rem;
        text-transform: uppercase;
        letter-spacing: .12em;
        color: var(--ko-text-muted);
        white-space: nowrap;
        font-weight: 600;
    }

    /* ── Time grid ── */
    .ko-time-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    @media(max-width:560px) {
        .ko-time-grid {
            grid-template-columns: 1fr
        }
    }

    .ko-time-card {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1.25rem;
        background: var(--ko-surface-2);
        border: 1px solid var(--ko-border);
        border-radius: var(--ko-radius-sm);
        transition: border-color .2s, box-shadow .2s;
    }

    .ko-time-card:hover {
        border-color: var(--ko-primary-border);
        box-shadow: 0 0 18px var(--ko-primary-glow);
    }

    .ko-time-card__icon {
        flex-shrink: 0;
        width: 38px;
        height: 38px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ko-time-card__icon svg {
        width: 18px;
        height: 18px;
    }

    .ko-time-card__icon--created {
        background: var(--ko-green-dim);
        border: 1px solid rgba(16, 185, 129, .2);
    }

    .ko-time-card__icon--created svg {
        stroke: var(--ko-green);
    }

    .ko-time-card__icon--updated {
        background: var(--ko-amber-dim);
        border: 1px solid rgba(245, 158, 11, .2);
    }

    .ko-time-card__icon--updated svg {
        stroke: var(--ko-amber);
    }

    .ko-time-card__label {
        font-size: .68rem;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--ko-text-muted);
        font-weight: 600;
        margin: 0 0 .4rem;
    }

    .ko-time-card__date {
        font-size: .95rem;
        font-weight: 700;
        color: var(--ko-text-primary);
        margin: 0 0 .15rem;
    }

    .ko-time-card__time {
        font-size: .78rem;
        font-family: 'JetBrains Mono', 'Fira Code', monospace;
        color: var(--ko-text-subtle);
        margin: 0 0 .3rem;
    }

    .ko-time-card__relative {
        font-size: .7rem;
        color: var(--ko-text-muted);
        margin: 0;
        font-style: italic;
    }

    /* ── Animasi ── */
    @keyframes ko-fade-up {
        from {
            opacity: 0;
            transform: translateY(16px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .ko-animate-in {
        animation: ko-fade-up .45s cubic-bezier(.22, .68, 0, 1.2) both;
    }

    @media(prefers-reduced-motion:reduce) {
        .ko-animate-in {
            animation: none
        }

        .ko-badge__dot {
            animation: none
        }
    }
</style>
@endpush