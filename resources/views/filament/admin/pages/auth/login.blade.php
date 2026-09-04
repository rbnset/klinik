    <style>
        /* Remove Filament SimplePage width/card constraints so the custom login can use the full viewport. */
        .fi-simple-page,
        .fi-simple-main,
        .fi-simple-layout,
        .fi-simple-page > div,
        .fi-simple-main > div {
            width: 100% !important;
            max-width: none !important;
        }

        .fi-simple-page,
        .fi-simple-main {
            padding: 0 !important;
            margin: 0 !important;
        }

        .fi-simple-page > div,
        .fi-simple-main > div {
            min-height: 100vh !important;
        }

        .pbps-login-shell {
            min-height: 100vh;
            width: 100%;
            display: grid;
            place-items: center;
            box-sizing: border-box;
            padding: 48px 32px;
            background:
                radial-gradient(circle at 8% 8%, rgba(223, 34, 40, .08), transparent 28%),
                radial-gradient(circle at 92% 14%, rgba(59, 130, 246, .06), transparent 24%),
                #f8f9fb;
        }

        .dark .pbps-login-shell {
            background:
                radial-gradient(circle at 12% 12%, rgba(239, 68, 68, .11), transparent 30%),
                radial-gradient(circle at 88% 18%, rgba(59, 130, 246, .08), transparent 26%),
                #090b10;
        }

        .pbps-login-wrap {
            width: min(1080px, 100%);
            display: grid;
            grid-template-columns: minmax(0, 1fr) 420px;
            gap: clamp(48px, 7vw, 88px);
            align-items: center;
        }

        .pbps-login-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 30px;
        }

        .pbps-login-logo {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            object-fit: cover;
            background: white;
            border: 1px solid rgba(16, 24, 40, .10);
            box-shadow: 0 8px 24px rgba(16, 24, 40, .08);
        }

        .dark .pbps-login-logo {
            border-color: #2a2e36;
        }

        .pbps-login-brand strong {
            display: block;
            color: #17191d;
            font-size: 15px;
            font-weight: 800;
            letter-spacing: -.02em;
        }

        .dark .pbps-login-brand strong { color: #f4f5f7; }

        .pbps-login-brand span {
            display: block;
            color: #667085;
            font-size: 11px;
            margin-top: 1px;
        }

        .dark .pbps-login-brand span { color: #a3aab7; }

        .pbps-login-copy .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 10px;
            border: 1px solid rgba(223, 34, 40, .18);
            border-radius: 999px;
            background: rgba(223, 34, 40, .07);
            color: #df2228;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .10em;
            text-transform: uppercase;
        }

        .dark .pbps-login-copy .eyebrow {
            background: rgba(239, 68, 68, .11);
            color: #f87171;
            border-color: rgba(239, 68, 68, .20);
        }

        .pbps-login-copy h1 {
            margin: 18px 0 16px;
            color: #111827;
            font-size: clamp(40px, 5vw, 62px);
            line-height: 1.04;
            letter-spacing: -.055em;
            max-width: 650px;
        }

        .dark .pbps-login-copy h1 { color: #f4f5f7; }

        .pbps-login-copy h1 em {
            color: #df2228;
            font-style: normal;
        }

        .dark .pbps-login-copy h1 em { color: #f87171; }

        .pbps-login-copy > p {
            max-width: 580px;
            margin: 0;
            color: #667085;
            font-size: 15px;
            line-height: 1.75;
        }

        .dark .pbps-login-copy > p { color: #a3aab7; }

        .pbps-login-points {
            display: grid;
            gap: 12px;
            margin-top: 32px;
        }

        .pbps-login-point {
            display: flex;
            gap: 11px;
            align-items: center;
            color: #667085;
            font-size: 12px;
            font-weight: 650;
        }

        .dark .pbps-login-point { color: #a3aab7; }

        .pbps-login-check {
            width: 22px;
            height: 22px;
            flex: 0 0 22px;
            display: grid;
            place-items: center;
            border-radius: 7px;
            background: rgba(223, 34, 40, .09);
            color: #df2228;
            font-size: 12px;
            font-weight: 900;
        }

        .dark .pbps-login-check {
            background: rgba(239, 68, 68, .12);
            color: #f87171;
        }

        .pbps-login-card {
            position: relative;
            overflow: hidden;
            width: 100%;
            box-sizing: border-box;
            padding: 34px;
            border: 1px solid #e4e7ec;
            border-radius: 22px;
            background: rgba(255, 255, 255, .97);
            box-shadow: 0 24px 70px rgba(16, 24, 40, .12);
        }

        .dark .pbps-login-card {
            border-color: #292d34;
            background: rgba(19, 21, 25, .96);
            box-shadow: 0 30px 90px rgba(0, 0, 0, .35);
        }

        .pbps-login-card::before {
            content: "";
            position: absolute;
            inset: 0 0 auto;
            height: 3px;
            background: linear-gradient(90deg, #df2228, #ef4444, transparent);
        }

        .pbps-login-card-head {
            margin-bottom: 24px;
        }

        .pbps-login-card-head h2 {
            margin: 0 0 6px;
            color: #111827;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -.035em;
        }

        .dark .pbps-login-card-head h2 { color: #f4f5f7; }

        .pbps-login-card-head p {
            margin: 0;
            color: #667085;
            font-size: 12px;
            line-height: 1.6;
        }

        .dark .pbps-login-card-head p { color: #a3aab7; }

        .pbps-login-card .fi-sc-form {
            gap: 16px;
        }

        .pbps-login-card .fi-btn {
            min-height: 44px;
            border-radius: 12px;
        }

        .pbps-login-card .fi-input-wrp {
            border-radius: 11px;
        }

        .pbps-login-submit {
            margin-top: 18px;
        }

        .pbps-login-submit-button {
            width: 100%;
            min-height: 46px;
            border: 0;
            border-radius: 12px;
            background: #df2228;
            color: #fff;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: -.01em;
            cursor: pointer;
            box-shadow: 0 10px 24px rgba(223, 34, 40, .18);
            transition: transform .15s ease, box-shadow .15s ease, opacity .15s ease;
        }

        .pbps-login-submit-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 13px 28px rgba(223, 34, 40, .23);
        }

        .pbps-login-submit-button:disabled {
            opacity: .7;
            cursor: wait;
            transform: none;
        }

        .dark .pbps-login-submit-button {
            background: #ef4444;
            box-shadow: 0 12px 28px rgba(239, 68, 68, .20);
        }

        .pbps-login-footer {
            margin-top: 18px;
            padding-top: 18px;
            border-top: 1px solid #e4e7ec;
            text-align: center;
            color: #667085;
            font-size: 11px;
            line-height: 1.6;
        }

        .dark .pbps-login-footer { border-color: #292d34; color: #a3aab7; }

        .pbps-login-footer a {
            color: #111827;
            font-weight: 800;
            text-decoration: underline;
            text-underline-offset: 2px;
        }

        .dark .pbps-login-footer a { color: #f4f5f7; }

        .pbps-login-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 16px;
            color: #667085;
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
        }

        .dark .pbps-login-back { color: #a3aab7; }
        .pbps-login-back:hover { color: #df2228; }

        @media (max-width: 900px) {
            .pbps-login-shell { padding: 36px 22px; }
            .pbps-login-wrap { grid-template-columns: 1fr; gap: 34px; max-width: 620px; }
            .pbps-login-copy { text-align: center; }
            .pbps-login-brand { justify-content: center; margin-bottom: 26px; }
            .pbps-login-copy h1 { font-size: clamp(34px, 9vw, 48px); margin-inline: auto; }
            .pbps-login-copy > p { margin-inline: auto; }
            .pbps-login-points { justify-items: center; }
            .pbps-login-point { width: fit-content; }
        }

        @media (max-width: 480px) {
            .pbps-login-shell { padding: 24px 14px; }
            .pbps-login-card { padding: 24px 18px; border-radius: 18px; }
            .pbps-login-brand { margin-bottom: 22px; }
            .pbps-login-copy > p { font-size: 14px; }
        }
    </style>

    <div class="pbps-login-shell">
        <div class="pbps-login-wrap">
            <section class="pbps-login-copy" aria-label="Informasi sistem">
                <a href="{{ route('landing') }}" class="pbps-login-brand">
                    <img class="pbps-login-logo" src="{{ asset('images/logo.jpeg') }}" alt="Logo Praktek Bidan Puji Susanti">
                    <span>
                        <strong>Praktek Bidan Puji Susanti</strong>
                        Sistem Pengadaan &amp; Persediaan
                    </span>
                </a>

                <div class="eyebrow"><span aria-hidden="true">●</span> Sistem Internal</div>

                <h1>Kelola pengadaan dengan <em>lebih teratur.</em></h1>

                <p>
                    Masuk ke sistem untuk mengelola permintaan, pemesanan, penerimaan,
                    pembayaran, dan persediaan obat dalam satu alur yang terintegrasi.
                </p>

                <div class="pbps-login-points">
                    <div class="pbps-login-point"><span class="pbps-login-check">✓</span> Data pengadaan terdokumentasi dengan rapi</div>
                    <div class="pbps-login-point"><span class="pbps-login-check">✓</span> Persediaan tercatat berdasarkan transaksi</div>
                    <div class="pbps-login-point"><span class="pbps-login-check">✓</span> Akses sistem sesuai kewenangan pengguna</div>
                </div>
            </section>

            <section class="pbps-login-card" aria-label="Form masuk">
                <div class="pbps-login-card-head">
                    <h2>Masuk ke sistem</h2>
                    <p>Gunakan email dan password yang telah terdaftar.</p>
                </div>

                {{ \Filament\Support\Facades\FilamentView::renderHook('panels::auth.login.form.before') }}

                <form wire:submit="authenticate">
                    {{ $this->form }}

                    <div class="pbps-login-submit">
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:target="authenticate"
                            class="pbps-login-submit-button"
                        >
                            <span wire:loading.remove wire:target="authenticate">Masuk ke Sistem</span>
                            <span wire:loading wire:target="authenticate">Memproses...</span>
                        </button>
                    </div>
                </form>

                {{ \Filament\Support\Facades\FilamentView::renderHook('panels::auth.login.form.after') }}

                <div class="pbps-login-footer">
                    Supplier belum memiliki akun?
                    <a href="{{ route('supplier.register') }}">Ajukan akun supplier</a>
                </div>

                <a href="{{ route('landing') }}" class="pbps-login-back">← Kembali ke halaman utama</a>
            </section>
        </div>
    </div>
