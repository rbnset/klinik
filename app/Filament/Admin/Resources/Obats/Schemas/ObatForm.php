<?php

namespace App\Filament\Admin\Resources\Obats\Schemas;

use App\Filament\Admin\Resources\RiwayatStoks\RiwayatStokResource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class ObatForm
{
    /**
     * Solusi: pakai ID unik per field + vanilla JS (focusin/focusout / mouseenter/mouseleave).
     * Alpine scope TIDAK dipakai karena hint dirender di luar wrapper x-data Filament,
     * sehingga x-show="focused" selalu melempar "focused is not defined".
     *
     * Mekanisme:
     *  - Setiap hint diberi ID unik  → mudah di-querySelector
     *  - extraInputAttributes menempel event listener JS langsung di <input>
     *  - Fungsi helper focusHintAttrs() / hoverHintAttrs() menghasilkan atribut siap pakai
     */

    // ── helpers ────────────────────────────────────────────────────────────

    /**
     * Atribut untuk <input> yang ingin menampilkan hint saat fokus.
     * @param  string $hintId  ID unik elemen hint (harus sama dengan yang dipakai di hint())
     */
    private static function focusHintAttrs(string $hintId): array
    {
        return [
            'onfocus' => "
                var h = document.getElementById('{$hintId}');
                if (h) { h.style.display = 'block'; }
            ",
            'onblur'  => "
                var h = document.getElementById('{$hintId}');
                if (h) { h.style.display = 'none'; }
            ",
        ];
    }

    /**
     * Atribut untuk wrapper field disabled (stok) yang ingin menampilkan hint saat hover.
     * Ditaruh di extraAttributes() (wrapper div), bukan di <input>.
     * @param  string $hintId  ID unik elemen hint
     */
    private static function hoverHintAttrs(string $hintId): array
    {
        /**
         * Masalah: kursor meninggalkan wrapper → tooltip hilang sebelum user sempat klik link.
         * Solusi : delay 200ms sebelum hide. Kalau kursor masuk ke tooltip dalam 200ms,
         *          timeout dibatalkan → tooltip tetap muncul dan link bisa diklik.
         *
         * Pakai window.__stokHideTimer sebagai slot timer global (aman karena hanya 1 field stok).
         */
        return [
            'onmouseenter' => "
                clearTimeout(window.__stokHideTimer);
                var h = document.getElementById('{$hintId}');
                if (h) { h.style.display = 'inline-flex'; }
            ",
            'onmouseleave' => "
                window.__stokHideTimer = setTimeout(function() {
                    var h = document.getElementById('{$hintId}');
                    if (h) { h.style.display = 'none'; }
                }, 200);
            ",
            'style' => 'cursor: not-allowed;',
        ];
    }

    /**
     * Atribut yang dipasang langsung pada elemen hint stok (span tooltip-nya).
     * Saat kursor masuk ke tooltip → batalkan timer hide dari wrapper.
     * Saat kursor keluar dari tooltip → mulai timer hide baru.
     * Dipanggil sebagai inline string di dalam HTML hint stok.
     */
    private static function stokTooltipMouseAttrs(string $hintId): string
    {
        return 'onmouseenter="clearTimeout(window.__stokHideTimer);" '
            . 'onmouseleave="window.__stokHideTimer = setTimeout(function() { '
            . "var h = document.getElementById('{$hintId}'); "
            . "if (h) { h.style.display = 'none'; } "
            . '}, 200);"';
    }

    /**
     * Render elemen hint yang awalnya tersembunyi (display:none).
     * Ditampilkan / disembunyikan oleh JS di atas.
     *
     * @param  string $id    ID unik — harus cocok dengan yang dipakai di focusHintAttrs/hoverHintAttrs
     * @param  string $text  Teks hint (boleh HTML)
     * @param  bool   $hover Mode hover → pakai inline-flex (untuk tooltip stok), default false (block)
     */
    private static function hint(string $id, string $text, bool $hover = false): HtmlString
    {
        $displayNone  = 'display:none;';
        $baseStyle    = 'font-size:.75rem;color:#6b7280;margin-top:4px;';

        if ($hover) {
            // Tooltip pill untuk stok
            $style = $displayNone
                . 'align-items:center;gap:.3rem;'
                . 'background:#f9fafb;border:1px solid #e5e7eb;'
                . 'padding:.3rem .65rem;border-radius:7px;'
                . $baseStyle;
        } else {
            // Helper text biasa
            $style = $displayNone . 'display:none;' . $baseStyle;
            // Catatan: display:none diset dua kali (redundan tapi aman) —
            // yang penting JS nanti set display='block'
            $style = $displayNone . $baseStyle;
        }

        return new HtmlString(
            '<span id="' . $id . '" style="' . $style . '">' . $text . '</span>'
        );
    }

    // ── form utama ──────────────────────────────────────────────────────────

    public static function configure(Schema $schema): Schema
    {
        $stokUrl = RiwayatStokResource::getUrl('index');

        return $schema
            ->components([

                // ── SECTION 1 : Identitas Produk ───────────────────────────
                Section::make('Identitas Produk')
                    ->description('Pastikan kode SKU unik dan kategori tepat — keduanya dipakai di laporan dan pencarian stok.')
                    ->icon('heroicon-o-identification')
                    ->schema([

                        TextInput::make('kode_obat')
                            ->label('Kode / SKU Obat')
                            ->placeholder('Contoh: PAR-001')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(30)
                            ->extraInputAttributes(array_merge(
                                ['style' => 'text-transform: uppercase;'],
                                self::focusHintAttrs('hint-kode-obat')
                            ))
                            ->dehydrateStateUsing(fn($state) => strtoupper($state))
                            ->hint(self::hint(
                                'hint-kode-obat',
                                '💡 Kode unik produk — tidak boleh sama dengan obat lain.'
                            )),

                        TextInput::make('nama_obat')
                            ->label('Nama Obat')
                            ->placeholder('Contoh: Paracetamol 500mg')
                            ->required()
                            ->maxLength(100)
                            ->extraInputAttributes(self::focusHintAttrs('hint-nama-obat'))
                            ->hint(self::hint(
                                'hint-nama-obat',
                                '💡 Sertakan dosis atau kekuatan jika ada, misal "500mg".'
                            )),

                        Select::make('id_kategori_obat')
                            ->label('Kategori Obat')
                            ->relationship('kategori_obat', 'nama_kategori')
                            ->searchable()
                            ->preload()
                            ->required()
                            /**
                             * Select Filament menggunakan Tom Select (bukan <select> biasa).
                             * Event fokus ditangkap via extraAttributes() di wrapper div,
                             * lalu kita dengarkan focusin/focusout (bubbling dari input Tom Select).
                             */
                            ->extraAttributes([
                                'onfocusin'  => "
                                    var h = document.getElementById('hint-kategori');
                                    if (h) { h.style.display = 'block'; }
                                ",
                                'onfocusout' => "
                                    var h = document.getElementById('hint-kategori');
                                    if (h) { h.style.display = 'none'; }
                                ",
                            ])
                            ->hint(self::hint(
                                'hint-kategori',
                                '💡 Kategori mempengaruhi pengelompokan di laporan &amp; filter.'
                            )),

                    ])->columns(1),

                // ── SECTION 2 : Persediaan & Harga ────────────────────────
                Section::make('Persediaan & Harga')
                    ->description('Tetapkan satuan dan harga awal. Stok akan bergerak otomatis saat ada pembelian atau pengeluaran.')
                    ->icon('heroicon-o-archive-box')
                    ->schema([

                        TextInput::make('satuan')
                            ->label('Satuan Kemasan')
                            ->placeholder('Contoh: Botol, Strip, Pcs, Tablet')
                            ->required()
                            ->maxLength(30)
                            ->extraInputAttributes(self::focusHintAttrs('hint-satuan'))
                            ->hint(self::hint(
                                'hint-satuan',
                                '💡 Satuan ini muncul di setiap transaksi yang melibatkan obat ini.'
                            )),

                        // ── Stok Awal: disabled + tooltip hover ────────────
                        TextInput::make('stok')
                            ->label('Stok Awal')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->dehydrated()
                            /**
                             * extraAttributes() → wrapper div field.
                             * onmouseenter/onmouseleave di wrapper berarti hover di mana saja
                             * (label, input, icon) akan memicu tooltip — lebih mudah dijangkau user.
                             * cursor:not-allowed pada wrapper agar seluruh area field terasa "terkunci".
                             *
                             * Input disabled secara default sudah pointer-events:none di browser,
                             * tapi wrapper belum — kita override di sini.
                             */
                            ->extraAttributes(self::hoverHintAttrs('hint-stok'))
                            ->hint(new HtmlString(
                                // Tooltip pill — display diatur JS (inline-flex saat hover)
                                // Mouse event pada span sendiri → batalkan timer hide saat kursor masuk tooltip
                                '<span id="hint-stok" '
                                    . self::stokTooltipMouseAttrs('hint-stok') . ' '
                                    . 'style="display:none;align-items:center;gap:.3rem;'
                                    . 'background:#f9fafb;border:1px solid #e5e7eb;'
                                    . 'padding:.3rem .65rem;border-radius:7px;'
                                    . 'font-size:.75rem;color:#6b7280;margin-top:4px;'
                                    . 'cursor:default;">'  // reset cursor di dalam tooltip → normal/pointer
                                    . '🔒 Stok dikelola otomatis. Tambah via '
                                    . '<a href="' . $stokUrl . '" target="_blank" '
                                    . 'style="color:#DF2228;font-weight:600;text-decoration:underline;cursor:pointer;">'
                                    . 'Riwayat Mutasi Stok</a>'
                                    . '</span>'
                            )),

                        // ── Harga Beli: format ribuan real-time ────────────
                        TextInput::make('harga_beli')
                            ->label('Harga Beli per Satuan')
                            ->prefix('Rp')
                            ->placeholder('0')
                            ->required()
                            ->minValue(0)
                            ->dehydrateStateUsing(
                                fn($state) => (int) str_replace('.', '', $state ?? '0')
                            )
                            ->afterStateHydrated(function ($component, $state) {
                                if ($state !== null && $state !== '') {
                                    $component->state(number_format((int) $state, 0, ',', '.'));
                                }
                            })
                            ->extraInputAttributes(array_merge(
                                [
                                    'inputmode' => 'numeric',
                                    // Format ribuan real-time
                                    'oninput'   => "
                                        var el    = this;
                                        var pos   = el.selectionStart;
                                        var before = el.value.length;
                                        var raw   = el.value.replace(/[^0-9]/g, '');
                                        if (raw === '') { el.value = ''; return; }
                                        el.value  = parseInt(raw, 10).toLocaleString('id-ID');
                                        var diff  = el.value.length - before;
                                        el.setSelectionRange(pos + diff, pos + diff);
                                    ",
                                ],
                                self::focusHintAttrs('hint-harga-beli')
                            ))
                            ->hint(self::hint(
                                'hint-harga-beli',
                                '💡 Titik pemisah ribuan ditambahkan otomatis saat mengetik.'
                            )),

                    ])->columns(1),

            ]);
    }
}
