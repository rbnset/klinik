<x-filament-panels::page>

    @php
    $details = $record->detail_permintaan ?? collect();

    $statusConfig = match ($record->status) {
    'pending' => [
    'label' => 'Menunggu Persetujuan',
    'icon_color' => 'amber',
    'badge_class' => 'pr-badge--pending',
    'card_class' => 'pr-status-card--pending',
    ],
    'disetujui' => [
    'label' => 'Disetujui',
    'icon_color' => 'green',
    'badge_class' => 'pr-badge--disetujui',
    'card_class' => 'pr-status-card--disetujui',
    ],
    'ditolak' => [
    'label' => 'Ditolak',
    'icon_color' => 'red',
    'badge_class' => 'pr-badge--ditolak',
    'card_class' => 'pr-status-card--ditolak',
    ],
    'dibatalkan' => [
    'label' => 'Dibatalkan',
    'icon_color' => 'muted',
    'badge_class' => 'pr-badge--dibatalkan',
    'card_class' => 'pr-status-card--dibatalkan',
    ],
    default => [
    'label' => ucfirst($record->status),
    'icon_color' => 'muted',
    'badge_class' => 'pr-badge--dibatalkan',
    'card_class' => 'pr-status-card--dibatalkan',
    ],
    };

    $totalItem = $details->count();
    $totalDiminta = $details->sum('jumlah_diminta');
    $totalDisetujui = $details->sum(fn($d) => (int) ($d->jumlah_disetujui ?? 0));
    @endphp

    <div class="pr-wrapper">

        {{-- ── BREADCRUMB ── --}}
        <nav class="ob-breadcrumb" aria-label="breadcrumb">
            <a href="{{ filament()->getCurrentPanel()->getUrl() }}" class="ob-breadcrumb__link">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
                Dashboard
            </a>
            <span class="ob-breadcrumb__sep">›</span>
            <a href="{{ \App\Filament\Admin\Resources\PermintaanObats\PermintaanObatResource::getUrl('index') }}"
                class="ob-breadcrumb__link">
                Permintaan Obat
            </a>
            <span class="ob-breadcrumb__sep">›</span>
            <span class="ob-breadcrumb__current">
                REQ-{{ str_pad($record->id, 5, '0', STR_PAD_LEFT) }}
            </span>
        </nav>

        {{-- ── PAGE HEADER ── --}}
        <div class="ob-header">
            <div class="ob-header__icon-wrap">
                <svg class="ob-header__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
            </div>
            <div class="ob-header__text">
                <p class="ob-header__eyebrow">Manajemen Stok</p>
                <h1 class="ob-header__title">Detail Permintaan Obat</h1>
            </div>
            <div class="ob-header__actions">
                <a href="{{ \App\Filament\Admin\Resources\PermintaanObats\PermintaanObatResource::getUrl('index') }}"
                    class="ob-btn ob-btn--ghost">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        {{-- ── STATUS HERO CARD ── --}}
        <div class="ob-card pr-status-card {{ $statusConfig['card_class'] }} ob-animate-in">
            <div class="ob-card__accent-bar"></div>

            <div class="pr-status-card__inner">
                <div class="pr-status-card__left">
                    <div class="ob-card__status-row" style="margin-bottom:.75rem">
                        <span class="ob-badge {{ $statusConfig['badge_class'] }}">
                            <span class="ob-badge__dot"></span>
                            {{ $statusConfig['label'] }}
                        </span>
                        <span class="ob-card__id-label">ID #{{ $record->id }}</span>
                    </div>
                    <p class="pr-req__eyebrow">Nomor Permintaan</p>
                    <h2 class="pr-req__number">REQ-{{ str_pad($record->id, 5, '0', STR_PAD_LEFT) }}</h2>
                    <p class="pr-req__sub">Permintaan Obat Internal · {{ $record->tanggal_permintaan?->format('d F Y')
                        }}</p>
                </div>
                <div class="pr-status-card__icon-wrap">
                    @if($record->status === 'pending')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    @elseif($record->status === 'disetujui')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    @elseif($record->status === 'ditolak')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    @else
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── STAT CARDS ── --}}
        <div class="pr-stats-grid">

            <div class="ob-card ob-card--stat ob-animate-in" style="animation-delay:.04s">
                <div class="ob-stat__icon ob-stat__icon--stok">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                    </svg>
                </div>
                <p class="ob-stat__label">Total Item</p>
                <p class="ob-stat__value">{{ $totalItem }}</p>
                <p class="ob-stat__unit">jenis obat</p>
            </div>

            <div class="ob-card ob-card--stat ob-animate-in" style="animation-delay:.08s">
                <div class="ob-stat__icon ob-stat__icon--harga">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z" />
                    </svg>
                </div>
                <p class="ob-stat__label">Total Diminta</p>
                <p class="ob-stat__value">{{ number_format($totalDiminta) }}</p>
                <p class="ob-stat__unit">unit keseluruhan</p>
            </div>

            <div class="ob-card ob-card--stat ob-animate-in" style="animation-delay:.12s">
                <div class="ob-stat__icon ob-stat__icon--approved">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </div>
                <p class="ob-stat__label">Total Disetujui</p>
                <p class="ob-stat__value">{{ number_format($totalDisetujui) }}</p>
                <p class="ob-stat__unit">unit disetujui</p>
            </div>

        </div>

        {{-- ── TWO-COLUMN LAYOUT ── --}}
        <div class="pr-layout">

            {{-- KOLOM KIRI ── --}}
            <div class="pr-col-main">

                {{-- Info Permintaan --}}
                <div class="ob-card ob-animate-in" style="animation-delay:.16s">
                    <div class="ob-card__accent-bar"></div>

                    <div class="ob-divider" style="margin-bottom:1.25rem">
                        <span class="ob-divider__label">Informasi Permintaan</span>
                    </div>

                    <div class="pr-info-grid">

                        <div class="ob-info-row">
                            <div class="ob-info-row__icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                            <div>
                                <p class="ob-info-row__label">Diajukan Oleh</p>
                                <p class="ob-info-row__value">{{ $record->pengguna?->name ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="ob-info-row">
                            <div class="ob-info-row__icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                            </div>
                            <div>
                                <p class="ob-info-row__label">Tanggal Pengajuan</p>
                                <p class="ob-info-row__value">{{ $record->tanggal_permintaan?->translatedFormat('d F Y')
                                    ?? '-' }}</p>
                            </div>
                        </div>

                        @if($record->keterangan)
                        <div class="ob-info-row pr-info-row--full">
                            <div class="ob-info-row__icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                                </svg>
                            </div>
                            <div>
                                <p class="ob-info-row__label">Keterangan</p>
                                <p class="ob-info-row__value">{{ $record->keterangan }}</p>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>

                {{-- Detail Obat --}}
                <div class="ob-card ob-animate-in" style="animation-delay:.20s">
                    <div class="ob-card__accent-bar"></div>

                    <div class="ob-divider" style="margin-bottom:1.25rem">
                        <span class="ob-divider__label">Detail Obat</span>
                    </div>

                    <div class="pr-drug-list">

                        @foreach($record->detail_permintaan as $index => $detail)
                        @php
                        $approved = (int) ($detail->jumlah_disetujui ?? 0);
                        $requested = (int) $detail->jumlah_diminta;
                        $isPartial = $approved < $requested && $record->status === 'disetujui';
                            $pctFulfill = $requested > 0 ? min(100, ($approved / $requested) * 100) : 0;
                            @endphp

                            <div class="pr-drug-item ob-animate-in" style="animation-delay:{{ .20 + ($index * .06) }}s">

                                <div class="pr-drug-item__header">
                                    <div class="pr-drug-item__icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8 1.402 1.402c1 1 .03 2.798-1.348 2.798H6.148c-1.378 0-2.349-1.799-1.349-2.798L5 14.5" />
                                        </svg>
                                    </div>
                                    <div class="pr-drug-item__info">
                                        <h3 class="pr-drug-item__name">{{ $detail->obat?->nama_obat }}</h3>
                                        <p class="pr-drug-item__satuan">{{ $detail->obat?->satuan }}</p>
                                    </div>
                                    @if($isPartial)
                                    <span class="ob-badge pr-badge--partial">Sebagian</span>
                                    @endif
                                </div>

                                <div class="pr-drug-item__amounts">
                                    <div class="pr-amount-box pr-amount-box--diminta">
                                        <p class="pr-amount-box__label">Diminta</p>
                                        <p class="pr-amount-box__value">{{ number_format($requested) }}</p>
                                    </div>
                                    <div class="pr-amount-box__arrow">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path d="M5 12h14M12 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                    <div class="pr-amount-box pr-amount-box--disetujui">
                                        <p class="pr-amount-box__label">Disetujui</p>
                                        <p class="pr-amount-box__value">
                                            {{ isset($detail->jumlah_disetujui) ? number_format($approved) : '—' }}
                                        </p>
                                    </div>
                                </div>

                                @if($record->status === 'disetujui')
                                <div class="pr-fulfill-bar-wrap">
                                    <div class="pr-fulfill-bar">
                                        <div class="pr-fulfill-bar__fill {{ $pctFulfill >= 100 ? 'pr-fulfill-bar__fill--full' : ($pctFulfill >= 50 ? 'pr-fulfill-bar__fill--partial' : 'pr-fulfill-bar__fill--low') }}"
                                            style="width:{{ $pctFulfill }}%"></div>
                                    </div>
                                    <span class="pr-fulfill-bar__pct">{{ number_format($pctFulfill, 0) }}%</span>
                                </div>
                                @endif

                            </div>
                            @endforeach

                    </div>
                </div>

            </div>
            {{-- end kolom kiri --}}

            {{-- KOLOM KANAN ── --}}
            <div class="pr-col-side">

                {{-- Riwayat Status --}}
                <div class="ob-card ob-card--slim ob-animate-in" style="animation-delay:.18s">

                    <div class="ob-divider" style="margin-bottom:1.1rem">
                        <span class="ob-divider__label">Riwayat Status</span>
                    </div>

                    <div class="pr-timeline">

                        <div class="pr-timeline__item pr-timeline__item--done">
                            <div class="pr-timeline__dot pr-timeline__dot--primary"></div>
                            <div class="pr-timeline__connector"></div>
                            <div class="pr-timeline__content">
                                <p class="pr-timeline__title">Permintaan Dibuat</p>
                                <p class="pr-timeline__time">{{ $record->created_at->translatedFormat('d M Y') }}</p>
                                <p class="pr-timeline__time">{{ $record->created_at->format('H:i') }} WIB</p>
                            </div>
                        </div>

                        @if(in_array($record->status, ['disetujui', 'ditolak', 'dibatalkan']))
                        <div class="pr-timeline__item pr-timeline__item--done">
                            <div
                                class="pr-timeline__dot {{ $record->status === 'disetujui' ? 'pr-timeline__dot--green' : ($record->status === 'ditolak' ? 'pr-timeline__dot--red' : 'pr-timeline__dot--muted') }}">
                            </div>
                            <div class="pr-timeline__content">
                                <p class="pr-timeline__title">{{ ucfirst($record->status) }}</p>
                                @if($record->updated_at && $record->updated_at->ne($record->created_at))
                                <p class="pr-timeline__time">{{ $record->updated_at->translatedFormat('d M Y') }}</p>
                                <p class="pr-timeline__time">{{ $record->updated_at->format('H:i') }} WIB</p>
                                @endif
                            </div>
                        </div>
                        @else
                        <div class="pr-timeline__item pr-timeline__item--pending">
                            <div class="pr-timeline__dot pr-timeline__dot--pending"></div>
                            <div class="pr-timeline__content">
                                <p class="pr-timeline__title">Menunggu Tindakan</p>
                                <p class="pr-timeline__time">Belum diproses</p>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>

                {{-- Waktu --}}
                <div class="ob-card ob-card--slim ob-animate-in" style="animation-delay:.22s">
                    <div class="ob-divider" style="margin-bottom:1.1rem">
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
                                <p class="ob-time-card__label">Dibuat Pada</p>
                                <p class="ob-time-card__date">{{ $record->created_at->translatedFormat('d F Y') }}</p>
                                <p class="ob-time-card__time">{{ $record->created_at->format('H:i') }} WIB</p>
                                <p class="ob-time-card__relative">{{ $record->created_at->diffForHumans() }}</p>
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
                                <p class="ob-time-card__label">Diperbarui</p>
                                <p class="ob-time-card__date">{{ $record->updated_at->translatedFormat('d F Y') }}</p>
                                <p class="ob-time-card__time">{{ $record->updated_at->format('H:i') }} WIB</p>
                                <p class="ob-time-card__relative">{{ $record->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            {{-- end kolom kanan --}}

        </div>
        {{-- end layout --}}

    </div>

    @push('styles')
    <style>
        /* ══════════════════════════════════════════
       DESIGN TOKENS — inherit from ob- system
    ══════════════════════════════════════════ */
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

        /* ══ Wrapper ══ */
        .pr-wrapper {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1.5rem 1rem 4rem;
            font-family: 'Inter', system-ui, sans-serif;
            color: var(--ob-text-primary);
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        /* ══ Two-column layout ══ */
        .pr-layout {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 1.25rem;
            align-items: start;
        }

        .pr-col-main {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .pr-col-side {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        @media(max-width:900px) {
            .pr-layout {
                grid-template-columns: 1fr;
            }
        }

        /* ══ Stat grid (3-col) ══ */
        .pr-stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        @media(max-width:640px) {
            .pr-stats-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ── Reuse ob-card base ── */
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

        .ob-card--stat {
            text-align: center;
        }

        .ob-card__accent-bar {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(to bottom, var(--ob-primary), #7b0d10);
            border-radius: var(--ob-radius) 0 0 var(--ob-radius);
        }

        /* ══ Status Hero Card ══ */
        .pr-status-card__inner {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
        }

        .pr-req__eyebrow {
            font-size: .65rem;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: var(--ob-text-muted);
            margin: 0 0 .3rem;
            font-weight: 600;
        }

        .pr-req__number {
            font-size: 2rem;
            font-weight: 700;
            font-family: 'JetBrains Mono', 'Fira Code', monospace;
            color: var(--ob-text-primary);
            margin: 0 0 .35rem;
            line-height: 1.1;
        }

        .pr-req__sub {
            font-size: .78rem;
            color: var(--ob-text-muted);
            margin: 0;
        }

        .pr-status-card__icon-wrap {
            flex-shrink: 0;
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--ob-border);
            background: var(--ob-surface-2);
        }

        .pr-status-card__icon-wrap svg {
            width: 26px;
            height: 26px;
        }

        /* icon color per status */
        .pr-status-card--pending .pr-status-card__icon-wrap svg {
            stroke: var(--ob-amber);
        }

        .pr-status-card--disetujui .pr-status-card__icon-wrap svg {
            stroke: var(--ob-green);
        }

        .pr-status-card--ditolak .pr-status-card__icon-wrap svg {
            stroke: var(--ob-primary);
        }

        .pr-status-card--dibatalkan .pr-status-card__icon-wrap svg {
            stroke: var(--ob-text-muted);
        }

        /* ══ Badges ══ */
        .ob-card__status-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .ob-card__id-label {
            font-size: .68rem;
            color: var(--ob-text-muted);
            font-family: 'JetBrains Mono', 'Fira Code', monospace;
            letter-spacing: .06em;
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

        .ob-badge__dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            animation: ob-pulse 2s infinite;
        }

        .pr-badge--pending {
            background: var(--ob-amber-dim);
            color: var(--ob-amber);
            border: 1px solid rgba(245, 158, 11, .25);
        }

        .pr-badge--pending .ob-badge__dot {
            background: var(--ob-amber);
            box-shadow: 0 0 6px var(--ob-amber);
        }

        .pr-badge--disetujui {
            background: var(--ob-green-dim);
            color: var(--ob-green);
            border: 1px solid rgba(16, 185, 129, .25);
        }

        .pr-badge--disetujui .ob-badge__dot {
            background: var(--ob-green);
            box-shadow: 0 0 6px var(--ob-green);
        }

        .pr-badge--ditolak {
            background: var(--ob-primary-dim);
            color: var(--ob-primary);
            border: 1px solid var(--ob-primary-border);
        }

        .pr-badge--ditolak .ob-badge__dot {
            background: var(--ob-primary);
            box-shadow: 0 0 6px var(--ob-primary);
        }

        .pr-badge--dibatalkan {
            background: rgba(120, 110, 112, .10);
            color: var(--ob-text-subtle);
            border: 1px solid rgba(120, 110, 112, .18);
        }

        .pr-badge--dibatalkan .ob-badge__dot {
            background: var(--ob-text-muted);
            animation: none;
        }

        .pr-badge--partial {
            background: var(--ob-amber-dim);
            color: var(--ob-amber);
            border: 1px solid rgba(245, 158, 11, .25);
            font-size: .65rem;
        }

        /* ══ Stat cards ══ */
        .ob-stat__icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto .85rem;
        }

        .ob-stat__icon svg {
            width: 19px;
            height: 19px;
        }

        .ob-stat__icon--stok {
            background: var(--ob-blue-dim);
            border: 1px solid rgba(59, 130, 246, .2);
        }

        .ob-stat__icon--stok svg {
            stroke: var(--ob-blue);
        }

        .ob-stat__icon--harga {
            background: var(--ob-amber-dim);
            border: 1px solid rgba(245, 158, 11, .2);
        }

        .ob-stat__icon--harga svg {
            stroke: var(--ob-amber);
        }

        .ob-stat__icon--approved {
            background: var(--ob-green-dim);
            border: 1px solid rgba(16, 185, 129, .2);
        }

        .ob-stat__icon--approved svg {
            stroke: var(--ob-green);
        }

        .ob-stat__label {
            font-size: .68rem;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--ob-text-muted);
            margin: 0 0 .4rem;
            font-weight: 600;
        }

        .ob-stat__value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--ob-text-primary);
            line-height: 1;
            margin: 0 0 .3rem;
        }

        .ob-stat__unit {
            font-size: .7rem;
            color: var(--ob-text-muted);
            margin: 0;
        }

        /* ══ Info grid ══ */
        .pr-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .85rem;
        }

        .pr-info-row--full {
            grid-column: 1 / -1;
        }

        @media(max-width:560px) {
            .pr-info-grid {
                grid-template-columns: 1fr;
            }
        }

        .ob-info-row {
            display: flex;
            align-items: flex-start;
            gap: .85rem;
            padding: .95rem;
            background: var(--ob-surface-2);
            border: 1px solid var(--ob-border);
            border-radius: var(--ob-radius-sm);
            transition: border-color .2s, box-shadow .2s;
        }

        .ob-info-row:hover {
            border-color: var(--ob-primary-border);
            box-shadow: 0 0 16px var(--ob-primary-glow);
        }

        .ob-info-row__icon {
            flex-shrink: 0;
            width: 32px;
            height: 32px;
            background: var(--ob-primary-dim);
            border: 1px solid var(--ob-primary-border);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ob-info-row__icon svg {
            width: 15px;
            height: 15px;
            stroke: var(--ob-primary);
        }

        .ob-info-row__label {
            font-size: .65rem;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--ob-text-muted);
            margin: 0 0 .3rem;
            font-weight: 600;
        }

        .ob-info-row__value {
            font-size: .9rem;
            font-weight: 600;
            color: var(--ob-text-primary);
            margin: 0;
        }

        /* ══ Drug list ══ */
        .pr-drug-list {
            display: flex;
            flex-direction: column;
            gap: .85rem;
        }

        .pr-drug-item {
            background: var(--ob-surface-2);
            border: 1px solid var(--ob-border);
            border-radius: var(--ob-radius-sm);
            padding: 1rem;
            transition: border-color .2s, box-shadow .2s;
        }

        .pr-drug-item:hover {
            border-color: var(--ob-primary-border);
            box-shadow: 0 0 16px var(--ob-primary-glow);
        }

        .pr-drug-item__header {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: .9rem;
        }

        .pr-drug-item__icon {
            flex-shrink: 0;
            width: 36px;
            height: 36px;
            background: var(--ob-primary-dim);
            border: 1px solid var(--ob-primary-border);
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pr-drug-item__icon svg {
            width: 17px;
            height: 17px;
            stroke: var(--ob-primary);
        }

        .pr-drug-item__info {
            flex: 1;
            min-width: 0;
        }

        .pr-drug-item__name {
            font-size: .95rem;
            font-weight: 700;
            color: var(--ob-text-primary);
            margin: 0 0 .15rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .pr-drug-item__satuan {
            font-size: .7rem;
            color: var(--ob-text-muted);
            margin: 0;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        /* Amount boxes */
        .pr-drug-item__amounts {
            display: flex;
            align-items: center;
            gap: .6rem;
        }

        .pr-amount-box {
            flex: 1;
            padding: .65rem .85rem;
            border-radius: 8px;
            text-align: center;
            border: 1px solid var(--ob-border);
        }

        .pr-amount-box--diminta {
            background: var(--ob-blue-dim);
            border-color: rgba(59, 130, 246, .18);
        }

        .pr-amount-box--disetujui {
            background: var(--ob-green-dim);
            border-color: rgba(16, 185, 129, .18);
        }

        .pr-amount-box__label {
            font-size: .62rem;
            text-transform: uppercase;
            letter-spacing: .1em;
            font-weight: 600;
            margin: 0 0 .25rem;
            color: var(--ob-text-muted);
        }

        .pr-amount-box--diminta .pr-amount-box__label {
            color: var(--ob-blue);
        }

        .pr-amount-box--disetujui .pr-amount-box__label {
            color: var(--ob-green);
        }

        .pr-amount-box__value {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            color: var(--ob-text-primary);
            line-height: 1;
        }

        .pr-amount-box__arrow {
            flex-shrink: 0;
            color: var(--ob-text-muted);
            opacity: .5;
        }

        /* Fulfillment bar */
        .pr-fulfill-bar-wrap {
            display: flex;
            align-items: center;
            gap: .6rem;
            margin-top: .75rem;
        }

        .pr-fulfill-bar {
            flex: 1;
            height: 5px;
            background: var(--ob-border);
            border-radius: 999px;
            overflow: hidden;
        }

        .pr-fulfill-bar__fill {
            height: 100%;
            border-radius: 999px;
            transition: width .5s ease;
        }

        .pr-fulfill-bar__fill--full {
            background: var(--ob-green);
        }

        .pr-fulfill-bar__fill--partial {
            background: var(--ob-amber);
        }

        .pr-fulfill-bar__fill--low {
            background: var(--ob-primary);
        }

        .pr-fulfill-bar__pct {
            font-size: .65rem;
            font-weight: 700;
            color: var(--ob-text-muted);
            font-family: 'JetBrains Mono', 'Fira Code', monospace;
            min-width: 2.5rem;
            text-align: right;
        }

        /* ══ Timeline ══ */
        .pr-timeline {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .pr-timeline__item {
            display: grid;
            grid-template-columns: 12px 1px 1fr;
            gap: 0 .75rem;
            align-items: start;
        }

        .pr-timeline__item:last-child .pr-timeline__connector {
            display: none;
        }

        .pr-timeline__dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-top: 3px;
            flex-shrink: 0;
        }

        .pr-timeline__dot--primary {
            background: var(--ob-primary);
            box-shadow: 0 0 8px var(--ob-primary-glow);
        }

        .pr-timeline__dot--green {
            background: var(--ob-green);
            box-shadow: 0 0 8px rgba(16, 185, 129, .4);
        }

        .pr-timeline__dot--red {
            background: var(--ob-primary);
            box-shadow: 0 0 8px var(--ob-primary-glow);
        }

        .pr-timeline__dot--muted {
            background: var(--ob-text-muted);
        }

        .pr-timeline__dot--pending {
            background: transparent;
            border: 2px dashed var(--ob-text-muted);
        }

        .pr-timeline__connector {
            width: 1px;
            height: 100%;
            min-height: 28px;
            background: var(--ob-border);
            margin: 0 auto;
        }

        .pr-timeline__content {
            padding-bottom: 1rem;
        }

        .pr-timeline__title {
            font-size: .82rem;
            font-weight: 600;
            color: var(--ob-text-primary);
            margin: 0 0 .2rem;
        }

        .pr-timeline__time {
            font-size: .68rem;
            color: var(--ob-text-muted);
            margin: 0;
            font-family: 'JetBrains Mono', 'Fira Code', monospace;
        }

        /* ══ Reused ob- helpers ══ */
        .ob-breadcrumb {
            display: flex;
            align-items: center;
            gap: .45rem;
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

        .ob-header {
            display: flex;
            align-items: center;
            gap: 1rem;
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

        .ob-divider {
            display: flex;
            align-items: center;
            gap: .75rem;
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

        .ob-time-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: .75rem;
        }

        .ob-time-card {
            display: flex;
            align-items: center;
            gap: .85rem;
            padding: .85rem 1rem;
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
            font-size: .63rem;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--ob-text-muted);
            margin: 0 0 .2rem;
            font-weight: 600;
        }

        .ob-time-card__date {
            font-size: .85rem;
            font-weight: 700;
            color: var(--ob-text-primary);
            margin: 0 0 .1rem;
            white-space: nowrap;
        }

        .ob-time-card__time {
            font-size: .7rem;
            font-family: 'JetBrains Mono', 'Fira Code', monospace;
            color: var(--ob-text-subtle);
            margin: 0 0 .15rem;
        }

        .ob-time-card__relative {
            font-size: .66rem;
            color: var(--ob-text-muted);
            margin: 0;
            font-style: italic;
        }

        .ob-time-card>div:last-child {
            min-width: 0;
            flex: 1;
        }

        /* ══ Animation ══ */
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

        @keyframes ob-pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .4;
            }
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

</x-filament-panels::page>