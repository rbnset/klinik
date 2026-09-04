<!doctype html>
<html lang="id" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pengajuan Akun Supplier — Praktek Bidan Puji Susanti</title>
    <meta name="description" content="Ajukan akun supplier untuk terhubung dengan sistem pengadaan Praktek Bidan Puji Susanti.">
    <script>
        (() => {
            const stored = localStorage.getItem('theme');
            const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (stored === 'dark' || (!stored && systemDark)) document.documentElement.classList.add('dark');
        })();
    </script>
    <style>
        :root{color-scheme:light;--bg:#f7f8fa;--surface:#fff;--surface-2:#f9fafb;--text:#111827;--muted:#667085;--line:#e5e7eb;--brand:#df2228;--brand-dark:#b91c22;--soft:#fff1f2;--shadow:0 24px 70px rgba(15,23,42,.10)}
        html.dark{color-scheme:dark;--bg:#090b10;--surface:#11141b;--surface-2:#171b23;--text:#f4f4f5;--muted:#a1a1aa;--line:#272b35;--brand:#ef4444;--brand-dark:#dc2626;--soft:#2a1518;--shadow:0 30px 80px rgba(0,0,0,.34)}
        *{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;background:var(--bg);color:var(--text);font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}a{color:inherit}.page{min-height:100vh;overflow:hidden;background:radial-gradient(circle at 10% 5%,rgba(223,34,40,.10),transparent 30%),radial-gradient(circle at 90% 20%,rgba(59,130,246,.07),transparent 25%),var(--bg)}
        .nav{width:min(1180px,calc(100% - 36px));margin:auto;padding:20px 0;display:flex;align-items:center;justify-content:space-between;gap:20px}.brand{display:flex;align-items:center;gap:12px;text-decoration:none;font-weight:800;letter-spacing:-.02em}.brand-mark{width:40px;height:40px;border-radius:13px;display:grid;place-items:center;background:var(--brand);color:#fff;font-size:18px;font-weight:900;box-shadow:0 8px 24px rgba(223,34,40,.20)}.brand-text small{display:block;color:var(--muted);font-size:11px;font-weight:600;letter-spacing:.02em;margin-top:2px}.nav-actions{display:flex;align-items:center;gap:10px}.theme-toggle,.login-link{height:40px;border:1px solid var(--line);background:var(--surface);border-radius:11px;padding:0 13px;display:inline-flex;align-items:center;gap:8px;text-decoration:none;font-size:13px;font-weight:700;cursor:pointer;color:var(--text)}.theme-toggle{width:40px;justify-content:center;padding:0}.login-link:hover,.theme-toggle:hover{border-color:rgba(223,34,40,.35);background:var(--surface-2)}
        .hero{width:min(1180px,calc(100% - 36px));margin:30px auto 80px;display:grid;grid-template-columns:minmax(0,1.05fr) minmax(420px,.95fr);gap:70px;align-items:center}.eyebrow{display:inline-flex;align-items:center;gap:8px;padding:7px 10px;border:1px solid rgba(223,34,40,.18);border-radius:999px;background:var(--soft);color:var(--brand);font-size:11px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.dot{width:6px;height:6px;border-radius:50%;background:currentColor}.hero h1{font-size:clamp(38px,5vw,66px);line-height:1.02;letter-spacing:-.055em;margin:20px 0 18px;max-width:760px}.hero h1 span{color:var(--brand)}.hero-copy{font-size:17px;line-height:1.7;color:var(--muted);max-width:650px;margin:0}.hero-actions{display:flex;gap:12px;margin-top:30px;flex-wrap:wrap}.primary{display:inline-flex;align-items:center;justify-content:center;gap:8px;border:0;border-radius:12px;background:var(--brand);color:#fff;padding:12px 17px;text-decoration:none;font-size:13px;font-weight:800;box-shadow:0 10px 25px rgba(223,34,40,.20)}.primary:hover{background:var(--brand-dark);transform:translateY(-1px)}.secondary{display:inline-flex;align-items:center;justify-content:center;border:1px solid var(--line);border-radius:12px;background:var(--surface);padding:12px 17px;text-decoration:none;font-size:13px;font-weight:800}.secondary:hover{background:var(--surface-2)}
        .trust{display:flex;gap:22px;flex-wrap:wrap;margin-top:35px;color:var(--muted);font-size:12px;font-weight:650}.trust span{display:inline-flex;gap:7px;align-items:center}.check{width:18px;height:18px;border-radius:50%;display:grid;place-items:center;background:var(--soft);color:var(--brand);font-size:11px}
        .register-card{position:relative;background:var(--surface);border:1px solid var(--line);border-radius:24px;padding:26px;box-shadow:var(--shadow)}.register-card:before{content:"";position:absolute;inset:-1px;border-radius:25px;pointer-events:none;background:linear-gradient(140deg,rgba(223,34,40,.18),transparent 35%,transparent 70%,rgba(59,130,246,.10));mask:linear-gradient(#000 0 0) content-box,linear-gradient(#000 0 0);mask-composite:exclude;padding:1px}.card-head{display:flex;justify-content:space-between;gap:16px;align-items:flex-start;margin-bottom:22px}.card-head h2{font-size:21px;letter-spacing:-.03em;margin:0 0 5px}.card-head p{font-size:12px;color:var(--muted);line-height:1.55;margin:0}.step{width:34px;height:34px;border-radius:11px;background:var(--soft);color:var(--brand);display:grid;place-items:center;font-size:12px;font-weight:900;flex:none}.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:15px}.field.full{grid-column:1/-1}.field label{display:flex;justify-content:space-between;gap:8px;font-size:11px;font-weight:800;margin:0 0 7px}.required{color:var(--brand)}input,textarea{width:100%;border:1px solid var(--line);border-radius:11px;background:var(--surface-2);color:var(--text);font:inherit;font-size:13px;padding:11px 12px;outline:none;transition:.15s}textarea{min-height:84px;resize:vertical}input:focus,textarea:focus{border-color:var(--brand);box-shadow:0 0 0 3px rgba(223,34,40,.10)}.error{margin-top:5px;font-size:11px;color:#ef4444}.hint{margin-top:5px;font-size:10px;color:var(--muted);line-height:1.45}.terms{display:flex;gap:9px;align-items:flex-start;margin:17px 0;font-size:11px;line-height:1.5;color:var(--muted)}.terms input{width:15px;height:15px;accent-color:var(--brand);margin-top:1px}.terms a{color:var(--text);font-weight:750}.submit{width:100%;border:0;border-radius:12px;background:var(--brand);color:#fff;padding:13px;font-size:13px;font-weight:850;cursor:pointer;box-shadow:0 10px 24px rgba(223,34,40,.18)}.submit:hover{background:var(--brand-dark)}.card-foot{text-align:center;color:var(--muted);font-size:11px;line-height:1.5;margin-top:13px}.card-foot a{color:var(--text);font-weight:800}
        .success{border:1px solid rgba(34,197,94,.25);background:rgba(34,197,94,.08);border-radius:15px;padding:16px;margin-bottom:18px}.success strong{display:block;font-size:13px;margin-bottom:5px}.success p{font-size:11px;line-height:1.55;color:var(--muted);margin:0}.success-actions{display:flex;gap:9px;margin-top:12px;flex-wrap:wrap}.success-actions a{font-size:11px;font-weight:800;text-decoration:none;border:1px solid var(--line);padding:8px 10px;border-radius:9px;background:var(--surface)}
        .features{width:min(1180px,calc(100% - 36px));margin:0 auto 80px}.section-kicker{text-align:center;color:var(--brand);font-size:11px;font-weight:850;letter-spacing:.12em;text-transform:uppercase}.features h2{text-align:center;font-size:32px;letter-spacing:-.04em;margin:8px 0 10px}.section-desc{text-align:center;color:var(--muted);font-size:14px;line-height:1.6;max-width:600px;margin:0 auto 28px}.feature-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:15px}.feature{padding:21px;border:1px solid var(--line);background:var(--surface);border-radius:18px}.feature-icon{width:36px;height:36px;border-radius:10px;display:grid;place-items:center;background:var(--soft);color:var(--brand);font-weight:900;margin-bottom:15px}.feature h3{font-size:14px;margin:0 0 7px}.feature p{font-size:12px;color:var(--muted);line-height:1.6;margin:0}
        .footer{border-top:1px solid var(--line);padding:25px 0;color:var(--muted);font-size:11px}.footer-inner{width:min(1180px,calc(100% - 36px));margin:auto;display:flex;justify-content:space-between;gap:15px;flex-wrap:wrap}.footer strong{color:var(--text)}
        @media(max-width:950px){.hero{grid-template-columns:1fr;gap:40px;margin-top:20px}.hero-copy{max-width:700px}.register-card{max-width:680px}.hero h1{max-width:700px}.feature-grid{grid-template-columns:1fr 1fr}}
        @media(max-width:620px){.nav,.hero,.features,.footer-inner{width:min(100% - 24px,1180px)}.nav{padding:14px 0}.brand-text{font-size:13px}.login-link{padding:0 10px}.login-link span{display:none}.hero{margin-top:24px;margin-bottom:55px}.hero h1{font-size:39px}.hero-copy{font-size:15px}.hero-actions{margin-top:23px}.form-grid{grid-template-columns:1fr}.field.full{grid-column:auto}.register-card{padding:20px;border-radius:20px}.feature-grid{grid-template-columns:1fr}.features{margin-bottom:55px}.features h2{font-size:27px}.trust{gap:13px}.card-head h2{font-size:19px}}
        @media(prefers-reduced-motion:reduce){*{scroll-behavior:auto!important;transition:none!important}}
    </style>
</head>
<body>
<div class="page">
    <header class="nav">
        <a href="{{ route('landing') }}" class="brand" aria-label="Praktek Bidan Puji Susanti">
            <div class="brand-mark">@if(file_exists(public_path('images/logo.jpeg')))<img src="{{ asset('images/logo.jpeg') }}" alt="Logo Praktek Bidan Puji Susanti" style="width:100%;height:100%;object-fit:cover;border-radius:13px">@else KR @endif</div>
            <div class="brand-text">Praktek Bidan Puji Susanti<small>Sistem Pengadaan & Persediaan</small></div>
        </a>
        <div class="nav-actions">
            <button class="theme-toggle" id="themeToggle" type="button" aria-label="Ubah mode tampilan" title="Ubah mode tampilan">☾</button>
            <a class="login-link" href="{{ url('/admin/login') }}"><span>Sudah punya akun?</span> Masuk</a>
        </div>
    </header>

    <main>
        <section class="hero">
            <div>
                <span class="eyebrow"><span class="dot"></span> Portal Supplier</span>
                <h1>Mulai terhubung dengan <span>pengadaan Praktek Bidan Puji Susanti.</span></h1>
                <p class="hero-copy">Ajukan akun supplier melalui formulir resmi. Setelah data diperiksa dan disetujui oleh admin klinik, Anda dapat menggunakan akun untuk mengakses sistem.</p>
                <div class="hero-actions">
                    <a class="primary" href="#daftar">Ajukan Akun Supplier <span>→</span></a>
                    <a class="secondary" href="#alur">Lihat alur pengajuan</a>
                </div>
                <div class="trust">
                    <span><b class="check">✓</b> Pengajuan terdata</span>
                    <span><b class="check">✓</b> Verifikasi oleh klinik</span>
                    <span><b class="check">✓</b> Akses setelah disetujui</span>
                </div>
            </div>

            <div class="register-card" id="daftar">
                @if(session('supplier_application_success'))
                    <div class="success">
                        <strong>Pengajuan akun berhasil dikirim.</strong>
                        <p>{{ session('supplier_application_resubmitted') ? 'Pengajuan ulang Anda sudah tercatat dan kembali menunggu pemeriksaan admin Praktek Bidan Puji Susanti.' : 'Data Anda sudah tercatat dan menunggu pemeriksaan admin Praktek Bidan Puji Susanti.' }} Anda belum dapat masuk sampai pengajuan disetujui.</p>
                        <div class="success-actions">
                            <a href="{{ route('landing') }}">Kembali ke beranda</a>
                            <a href="{{ url('/admin/login') }}">Ke halaman masuk</a>
                        </div>
                    </div>
                @else
                    <div class="card-head">
                        <div><h2>Ajukan akun supplier</h2><p>Isi data perusahaan dan penanggung jawab dengan benar.</p></div>
                        
                    </div>

                    @if($errors->any())
                        <div class="success" style="border-color:rgba(239,68,68,.25);background:rgba(239,68,68,.08);margin-bottom:18px">
                            <strong>Periksa kembali data Anda.</strong>
                            <p>Ada beberapa bagian yang belum sesuai. Silakan perbaiki lalu kirim kembali.</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('supplier.register.store') }}" novalidate>
                        @csrf
                        <div class="form-grid">
                            <div class="field full">
                                <label for="nama_supplier">Nama Perusahaan / Supplier <span class="required">*</span></label>
                                <input id="nama_supplier" name="nama_supplier" value="{{ old('nama_supplier') }}" placeholder="Contoh: PT. Kimia Farma" autocomplete="organization" required>
                                @error('nama_supplier')<div class="error">{{ $message }}</div>@enderror
                            </div>
                            <div class="field">
                                <label for="nama_pic">Nama Penanggung Jawab <span class="required">*</span></label>
                                <input id="nama_pic" name="nama_pic" value="{{ old('nama_pic') }}" placeholder="Nama PIC" autocomplete="name" required>
                                @error('nama_pic')<div class="error">{{ $message }}</div>@enderror
                            </div>
                            <div class="field">
                                <label for="no_telp">Nomor Telepon / WhatsApp <span class="required">*</span></label>
                                <input id="no_telp" name="no_telp" value="{{ old('no_telp') }}" placeholder="08xxxxxxxxxx" autocomplete="tel" required>
                                @error('no_telp')<div class="error">{{ $message }}</div>@enderror
                            </div>
                            <div class="field full">
                                <label for="email">Email Login <span class="required">*</span></label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="nama@perusahaan.com" autocomplete="email" required>
                                <div class="hint">Email ini akan digunakan untuk masuk setelah akun disetujui.</div>
                                @error('email')<div class="error">{{ $message }}</div>@enderror
                            </div>
                            <div class="field full">
                                <label for="alamat">Alamat Perusahaan <span class="required">*</span></label>
                                <textarea id="alamat" name="alamat" placeholder="Alamat lengkap perusahaan / supplier" autocomplete="street-address" required>{{ old('alamat') }}</textarea>
                                @error('alamat')<div class="error">{{ $message }}</div>@enderror
                            </div>
                            <div class="field">
                                <label for="password">Password <span class="required">*</span></label>
                                <input id="password" type="password" name="password" placeholder="Minimal 8 karakter" autocomplete="new-password" required>
                                @error('password')<div class="error">{{ $message }}</div>@enderror
                            </div>
                            <div class="field">
                                <label for="password_confirmation">Konfirmasi Password <span class="required">*</span></label>
                                <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Ulangi password" autocomplete="new-password" required>
                            </div>
                        </div>
                        <label class="terms">
                            <input type="checkbox" name="terms" value="1" {{ old('terms') ? 'checked' : '' }} required>
                            <span>Saya menyatakan bahwa data supplier yang saya masukkan benar dan bersedia menunggu proses verifikasi dari pihak Praktek Bidan Puji Susanti.</span>
                        </label>
                        @error('terms')<div class="error" style="margin:-8px 0 12px">{{ $message }}</div>@enderror
                        <button class="submit" type="submit">Kirim Pengajuan Akun</button>
                        <div class="card-foot">Sudah memiliki akun? <a href="{{ url('/admin/login') }}">Masuk ke sistem</a></div>
                    </form>
                @endif
            </div>
        </section>

        <section class="features" id="alur">
            <div class="section-kicker">Alur sederhana</div>
            <h2>Dibuat untuk proses yang jelas.</h2>
            <p class="section-desc">Akun supplier tidak langsung aktif. Klinik tetap memiliki kendali atas siapa yang dapat memperoleh akses ke sistem.</p>
            <div class="feature-grid">
                <article class="feature"><div class="feature-icon">01</div><h3>Ajukan akun</h3><p>Supplier mengisi identitas perusahaan, kontak, email login, alamat, dan password.</p></article>
                <article class="feature"><div class="feature-icon">02</div><h3>Verifikasi klinik</h3><p>Admin memeriksa data pengajuan sebelum memberikan akses ke sistem.</p></article>
                <article class="feature"><div class="feature-icon">03</div><h3>Akun aktif</h3><p>Setelah disetujui, supplier dapat menggunakan akun yang telah didaftarkan.</p></article>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="footer-inner"><span>© {{ date('Y') }} <strong>Praktek Bidan Puji Susanti</strong></span><span>Karongan RT.03 / RW.11, Jogotirto, Kec. Berbah, Kabupaten Sleman, DIY</span></div>
    </footer>
</div>
<script>
    const root=document.documentElement;
    const toggle=document.getElementById('themeToggle');
    const syncTheme=()=>{const dark=root.classList.contains('dark');toggle.textContent=dark?'☀':'☾';toggle.setAttribute('aria-label',dark?'Gunakan mode terang':'Gunakan mode gelap');};
    toggle.addEventListener('click',()=>{const dark=!root.classList.contains('dark');root.classList.toggle('dark',dark);localStorage.setItem('theme',dark?'dark':'light');syncTheme();});
    syncTheme();
</script>
</body>
</html>
