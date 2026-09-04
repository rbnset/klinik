<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#df2228">
    <meta name="description" content="Praktek Bidan Puji Susanti — pelayanan kesehatan ibu, kandungan, dan kesehatan umum di Jogotirto, Berbah, Sleman.">
    <title>Praktek Bidan Puji Susanti — Sistem Pengadaan & Persediaan</title>

    <script>
        (() => {
            const saved = localStorage.getItem('theme') || localStorage.getItem('filament_theme');
            const dark = saved === 'dark' || (!saved && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', dark);
        })();
    </script>

    <style>
        :root {
            --primary: #df2228;
            --primary-dark: #b91c22;
            --bg: #f7f8fa;
            --surface: #ffffff;
            --surface-soft: #f1f3f6;
            --text: #17191d;
            --muted: #667085;
            --border: #e4e7ec;
            --shadow: 0 20px 60px rgba(16, 24, 40, .08);
            --radius: 22px;
        }

        html.dark {
            --bg: #0c0d10;
            --surface: #131519;
            --surface-soft: #1a1d22;
            --text: #f4f5f7;
            --muted: #a3aab7;
            --border: #292d34;
            --shadow: 0 20px 60px rgba(0, 0, 0, .28);
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            line-height: 1.6;
            transition: background .2s ease, color .2s ease;
        }
        a { color: inherit; text-decoration: none; }
        button, input, textarea { font: inherit; }
        .container { width: min(1160px, calc(100% - 40px)); margin-inline: auto; }

        .nav-wrap {
            position: sticky; top: 0; z-index: 40;
            backdrop-filter: blur(18px);
            background: color-mix(in srgb, var(--bg) 84%, transparent);
            border-bottom: 1px solid color-mix(in srgb, var(--border) 75%, transparent);
        }
        .nav { min-height: 76px; display:flex; align-items:center; justify-content:space-between; gap:20px; }
        .brand { display:flex; align-items:center; gap:12px; min-width:0; }
        .brand-mark {
            width:44px; height:44px; flex:0 0 44px; border-radius:13px; overflow:hidden;
            display:grid; place-items:center; background:var(--surface); border:1px solid var(--border);
            box-shadow:0 8px 24px rgba(16,24,40,.08); font-weight:800; color:var(--primary);
        }
        .brand-mark img { width:100%; height:100%; object-fit:cover; }
        .brand-name { font-size:15px; font-weight:800; letter-spacing:-.02em; }
        .brand-sub { display:block; color:var(--muted); font-size:11px; margin-top:-2px; }
        .nav-links { display:flex; align-items:center; gap:26px; color:var(--muted); font-size:13px; font-weight:600; }
        .nav-links a:hover { color:var(--text); }
        .nav-actions { display:flex; align-items:center; gap:9px; }
        .icon-btn, .menu-btn {
            width:42px; height:42px; border:1px solid var(--border); border-radius:12px;
            background:var(--surface); color:var(--text); display:grid; place-items:center; cursor:pointer;
        }
        .menu-btn { display:none; }
        .btn { display:inline-flex; align-items:center; justify-content:center; gap:8px; min-height:44px; padding:0 18px; border-radius:12px; border:1px solid transparent; font-size:13px; font-weight:750; cursor:pointer; transition:.18s ease; }
        .btn-primary { background:var(--primary); color:white; box-shadow:0 8px 20px rgba(223,34,40,.20); }
        .btn-primary:hover { background:var(--primary-dark); transform:translateY(-1px); }
        .btn-secondary { background:var(--surface); border-color:var(--border); }
        .btn-secondary:hover { border-color:#c8cdd5; transform:translateY(-1px); }

        .hero { position:relative; overflow:hidden; padding:92px 0 74px; }
        .hero::before { content:""; position:absolute; width:520px; height:520px; border-radius:50%; background:rgba(223,34,40,.08); filter:blur(3px); right:-220px; top:-220px; pointer-events:none; }
        .hero-grid { display:grid; grid-template-columns:1.04fr .96fr; align-items:center; gap:70px; }
        .eyebrow { display:inline-flex; align-items:center; gap:8px; padding:7px 11px; border-radius:999px; background:var(--surface); border:1px solid var(--border); color:var(--primary); font-size:11px; font-weight:800; letter-spacing:.04em; text-transform:uppercase; }
        .dot { width:7px; height:7px; border-radius:50%; background:var(--primary); box-shadow:0 0 0 5px rgba(223,34,40,.10); }
        h1 { margin:18px 0 18px; font-size:clamp(40px, 5vw, 64px); line-height:1.06; letter-spacing:-.055em; max-width:760px; }
        .hero-copy { margin:0; max-width:650px; color:var(--muted); font-size:17px; }
        .hero-actions { display:flex; flex-wrap:wrap; gap:10px; margin-top:28px; }
        .hero-note { display:flex; gap:10px; align-items:flex-start; margin-top:18px; color:var(--muted); font-size:12px; max-width:620px; }
        .hero-note strong { color:var(--text); }
        .hero-card { position:relative; }
        .hero-panel { background:var(--surface); border:1px solid var(--border); border-radius:28px; padding:30px; box-shadow:var(--shadow); }
        .hero-panel-top { display:flex; align-items:flex-start; justify-content:space-between; gap:20px; }
        .mini-label { color:var(--muted); font-size:11px; font-weight:700; }
        .mini-title { margin-top:4px; font-size:22px; font-weight:800; letter-spacing:-.03em; }
        .status { display:inline-flex; align-items:center; gap:7px; padding:7px 10px; border-radius:999px; background:rgba(16,185,129,.10); color:#059669; font-size:11px; font-weight:800; }
        .status i { width:6px; height:6px; border-radius:50%; background:#10b981; }
        .hero-divider { height:1px; background:var(--border); margin:24px 0; }
        .hero-stat-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:12px; }
        .stat { padding:17px; border:1px solid var(--border); background:var(--surface-soft); border-radius:16px; }
        .stat-value { font-size:25px; font-weight:850; letter-spacing:-.04em; }
        .stat-label { color:var(--muted); font-size:11px; margin-top:2px; }
        .hero-list { margin:20px 0 0; display:grid; gap:10px; }
        .hero-list div { display:flex; align-items:center; gap:10px; font-size:12px; color:var(--muted); }
        .check { width:22px; height:22px; display:grid; place-items:center; flex:0 0 22px; border-radius:7px; background:rgba(223,34,40,.10); color:var(--primary); font-size:12px; font-weight:900; }

        .trust { border-block:1px solid var(--border); background:var(--surface); }
        .trust-grid { display:grid; grid-template-columns:repeat(3,1fr); }
        .trust-item { padding:20px 24px; display:flex; gap:12px; align-items:center; border-right:1px solid var(--border); }
        .trust-item:last-child { border-right:0; }
        .trust-icon { width:36px; height:36px; border-radius:11px; display:grid; place-items:center; background:rgba(223,34,40,.09); color:var(--primary); font-weight:900; }
        .trust-item strong { display:block; font-size:13px; }
        .trust-item span { color:var(--muted); font-size:11px; }

        section { scroll-margin-top:100px; }
        .section { padding:88px 0; }
        .section-head { max-width:720px; margin-bottom:34px; }
        .kicker { color:var(--primary); font-size:11px; font-weight:850; letter-spacing:.08em; text-transform:uppercase; }
        .section h2 { margin:8px 0 10px; font-size:clamp(28px,3vw,42px); line-height:1.12; letter-spacing:-.045em; }
        .section-desc { margin:0; color:var(--muted); font-size:15px; }

        .about-grid { display:grid; grid-template-columns:1.15fr .85fr; gap:22px; }
        .card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); padding:28px; box-shadow:0 10px 40px rgba(16,24,40,.04); }
        .card h3 { margin:0 0 10px; font-size:19px; letter-spacing:-.025em; }
        .card p { margin:0; color:var(--muted); font-size:14px; }
        .identity { display:grid; gap:18px; }
        .identity-row { padding-bottom:17px; border-bottom:1px solid var(--border); }
        .identity-row:last-child { padding-bottom:0; border-bottom:0; }
        .identity-label { color:var(--muted); font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; }
        .identity-value { margin-top:4px; font-size:14px; font-weight:700; }

        .benefit-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; }
        .benefit { padding:25px; border:1px solid var(--border); background:var(--surface); border-radius:20px; }
        .benefit-icon { width:42px; height:42px; border-radius:13px; display:grid; place-items:center; background:rgba(223,34,40,.09); color:var(--primary); font-weight:900; margin-bottom:18px; }
        .benefit h3 { margin:0 0 8px; font-size:16px; }
        .benefit p { margin:0; color:var(--muted); font-size:13px; }

        .mission-grid { display:grid; grid-template-columns:.78fr 1.22fr; gap:22px; }
        .vision-card { background:linear-gradient(145deg, var(--primary), #9e171c); color:#fff; border:0; }
        .vision-card .kicker, .vision-card p { color:rgba(255,255,255,.76); }
        .vision-card h3 { font-size:25px; line-height:1.25; }
        .mission-list { display:grid; gap:12px; }
        .mission-item { display:grid; grid-template-columns:30px 1fr; gap:12px; padding:15px; border:1px solid var(--border); border-radius:15px; background:var(--surface); }
        .mission-number { color:var(--primary); font-weight:850; }
        .mission-item p { margin:0; color:var(--muted); font-size:13px; }

        .service-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; }
        .service { padding:24px; background:var(--surface); border:1px solid var(--border); border-radius:20px; }
        .service h3 { margin:0 0 8px; font-size:16px; }
        .service p { margin:0; color:var(--muted); font-size:13px; }

        .history { display:grid; grid-template-columns:.85fr 1.15fr; gap:22px; align-items:stretch; }
        .history-highlight { padding:32px; border-radius:24px; background:var(--surface); border:1px solid var(--border); }
        .history-year { font-size:48px; font-weight:900; letter-spacing:-.06em; color:var(--primary); }
        .history-highlight h3 { margin:5px 0 10px; font-size:22px; }
        .history-highlight p, .history-copy p { margin:0; color:var(--muted); font-size:14px; }
        .history-copy { padding:32px; border-radius:24px; background:var(--surface); border:1px solid var(--border); }
        .history-copy p + p { margin-top:14px; }

        .org-wrap { display:grid; grid-template-columns:.72fr 1.28fr; gap:22px; align-items:center; }
        .org-info { padding:28px; background:var(--surface); border:1px solid var(--border); border-radius:22px; }
        .org-info ul { margin:18px 0 0; padding-left:18px; color:var(--muted); font-size:13px; }
        .org-image { padding:18px; background:var(--surface); border:1px solid var(--border); border-radius:22px; overflow:hidden; }
        .org-image img { display:block; width:100%; height:auto; border-radius:12px; }
        .org-placeholder { min-height:300px; display:grid; place-items:center; color:var(--muted); text-align:center; padding:30px; }

        .location { display:grid; grid-template-columns:1fr 1fr; gap:22px; }
        .map-embed { min-height:250px; overflow:hidden; border-radius:18px; background:var(--surface-soft); border:1px solid var(--border); box-shadow:0 12px 35px rgba(16,24,40,.07); aspect-ratio:16 / 9; }
        .map-embed iframe { display:block; width:100%; height:100%; border:0; }
        .address-box { min-height:250px; display:flex; flex-direction:column; justify-content:space-between; }
        .address-text { font-size:21px; line-height:1.45; font-weight:800; letter-spacing:-.025em; }
        .permit { color:var(--muted); font-size:12px; margin-top:10px; }
        .map-placeholder { min-height:250px; display:grid; place-items:center; border-radius:18px; background:var(--surface-soft); border:1px dashed var(--border); color:var(--muted); text-align:center; padding:25px; }

        .cta { padding:30px; border-radius:28px; background:var(--surface); border:1px solid var(--border); box-shadow:var(--shadow); display:flex; align-items:center; justify-content:space-between; gap:25px; }
        .cta h2 { margin:5px 0 6px; font-size:28px; }
        .cta p { margin:0; color:var(--muted); font-size:13px; }

        footer { border-top:1px solid var(--border); padding:28px 0; }
        .footer-grid { display:flex; justify-content:space-between; gap:20px; color:var(--muted); font-size:11px; }
        .footer-grid strong { color:var(--text); }

        @media (max-width: 900px) {
            .nav-links { display:none; position:absolute; top:76px; left:20px; right:20px; padding:15px; flex-direction:column; align-items:stretch; gap:0; background:var(--surface); border:1px solid var(--border); border-radius:16px; box-shadow:var(--shadow); }
            .nav-links.open { display:flex; }
            .nav-links a { padding:11px 8px; }
            .menu-btn { display:grid; }
            .nav .btn-primary { display:none; }
            .hero-grid, .about-grid, .mission-grid, .history, .org-wrap, .location { grid-template-columns:1fr; }
            .hero { padding-top:64px; }
            .benefit-grid, .service-grid { grid-template-columns:1fr 1fr; }
            .org-image { order:-1; }
        }
        @media (max-width: 620px) {
            .container { width:min(100% - 28px, 1160px); }
            .nav { min-height:68px; }
            .brand-sub { display:none; }
            .brand-mark { width:40px; height:40px; flex-basis:40px; }
            .hero { padding:50px 0 55px; }
            h1 { font-size:42px; }
            .hero-copy { font-size:15px; }
            .hero-panel { padding:22px; border-radius:22px; }
            .trust-grid { grid-template-columns:1fr; }
            .trust-item { border-right:0; border-bottom:1px solid var(--border); }
            .trust-item:last-child { border-bottom:0; }
            .section { padding:65px 0; }
            .benefit-grid, .service-grid { grid-template-columns:1fr; }
            .cta { flex-direction:column; align-items:flex-start; padding:24px; }
            .footer-grid { flex-direction:column; }
            .hero-actions .btn { width:100%; }
            .map-embed { aspect-ratio:4 / 3; min-height:0; }
        }
    </style>
</head>
<body>
    <header class="nav-wrap">
        <div class="container nav">
            <a class="brand" href="{{ route('landing') }}" aria-label="Praktek Bidan Puji Susanti">
                <div class="brand-mark">
                    @if(file_exists(public_path('images/logo.jpeg')))
                        <img src="{{ asset('images/logo.jpeg') }}" alt="Logo Praktek Bidan Puji Susanti">
                    @else
                        PBS
                    @endif
                </div>
                <div>
                    <div class="brand-name">Praktek Bidan Puji Susanti</div>
                    <span class="brand-sub">Sistem Pengadaan & Persediaan</span>
                </div>
            </a>

            <nav class="nav-links" id="navLinks" aria-label="Navigasi utama">
                <a href="#tentang">Tentang</a>
                <a href="#manfaat">Manfaat</a>
                <a href="#visi-misi">Visi & Misi</a>
                <a href="#struktur">Struktur</a>
                <a href="#lokasi">Lokasi</a>
            </nav>

            <div class="nav-actions">
                <button class="icon-btn" id="themeToggle" type="button" aria-label="Ganti mode tampilan">☾</button>
                <a href="{{ url('/admin/login') }}" class="btn btn-secondary">Masuk</a>
                <a href="{{ route('supplier.register') }}" class="btn btn-primary">Ajukan Akun Supplier</a>
                <button class="menu-btn" id="menuToggle" type="button" aria-label="Buka menu">☰</button>
            </div>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="container hero-grid">
                <div>
                    <div class="eyebrow"><span class="dot"></span> Mitra pengadaan resmi</div>
                    <h1>Pengadaan yang lebih tertata, transparan, dan mudah dikelola.</h1>
                    <p class="hero-copy">
                        Selamat datang di sistem pengadaan dan persediaan <strong>Praktek Bidan Puji Susanti</strong>.
                        Sistem ini membantu klinik mengelola pemesanan obat, penerimaan, pembayaran, serta persediaan secara terstruktur.
                    </p>
                    <div class="hero-actions">
                        <a href="{{ route('supplier.register') }}" class="btn btn-primary">Ajukan Akun Supplier <span>→</span></a>
                        <a href="#tentang" class="btn btn-secondary">Kenali Praktek Kami</a>
                    </div>
                    <div class="hero-note"><span class="check">✓</span><span><strong>Akun supplier diverifikasi terlebih dahulu.</strong> Pengajuan tidak langsung aktif sehingga akses sistem tetap dikelola oleh pihak klinik.</span></div>
                </div>

                <div class="hero-card">
                    <div class="hero-panel">
                        <div class="hero-panel-top">
                            <div>
                                <div class="mini-label">SISTEM PENGADAAN & PERSEDIAAN</div>
                                <div class="mini-title">Praktek Bidan Puji Susanti</div>
                            </div>
                            <span class="status"><i></i> Terstruktur</span>
                        </div>
                        <div class="hero-divider"></div>
                        <div class="hero-stat-grid">
                            <div class="stat"><div class="stat-value">01</div><div class="stat-label">Pemesanan terkelola</div></div>
                            <div class="stat"><div class="stat-value">02</div><div class="stat-label">Penerimaan tercatat</div></div>
                            <div class="stat"><div class="stat-value">03</div><div class="stat-label">Pembayaran terdokumentasi</div></div>
                            <div class="stat"><div class="stat-value">04</div><div class="stat-label">Persediaan terpantau</div></div>
                        </div>
                        <div class="hero-list">
                            <div><span class="check">✓</span> Riwayat harga beli tersimpan pada transaksi</div>
                            <div><span class="check">✓</span> Penerimaan memperbarui stok secara terkontrol</div>
                            <div><span class="check">✓</span> Dokumen transaksi dapat dicetak</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="trust">
            <div class="container trust-grid">
                <div class="trust-item"><span class="trust-icon">✓</span><div><strong>Pengelolaan terstruktur</strong><span>Setiap proses memiliki alur yang jelas.</span></div></div>
                <div class="trust-item"><span class="trust-icon">↗</span><div><strong>Data terdokumentasi</strong><span>Transaksi pengadaan tersimpan secara sistematis.</span></div></div>
                <div class="trust-item"><span class="trust-icon">⌁</span><div><strong>Akses supplier terverifikasi</strong><span>Akun diperiksa sebelum dapat digunakan.</span></div></div>
            </div>
        </div>

        <section class="section" id="tentang">
            <div class="container">
                <div class="section-head">
                    <div class="kicker">Tentang kami</div>
                    <h2>Pelayanan kesehatan yang tumbuh bersama kebutuhan masyarakat.</h2>
                    <p class="section-desc">Praktek Bidan Puji Susanti hadir sebagai fasilitas kesehatan yang mengutamakan pelayanan yang aman, nyaman, profesional, dan berorientasi pada kebutuhan pasien.</p>
                </div>
                <div class="about-grid">
                    <article class="card">
                        <h3>Profil Praktek</h3>
                        <p>
                            Praktek Mandiri Bidan Puji Susanti merupakan fasilitas pelayanan kesehatan yang beroperasi secara resmi dengan izin dari Dinas Kesehatan Kabupaten Sleman. Praktek ini berkedudukan di wilayah Jogotirto, Kecamatan Berbah, Kabupaten Sleman, dan menyediakan pelayanan kebidanan, kesehatan ibu dan kandungan, pemeriksaan kesehatan umum, serta konsultasi kesehatan.
                        </p>
                        <p style="margin-top:14px">
                            Dalam menjalankan pelayanan, Praktek Bidan Puji Susanti berkomitmen untuk memberikan layanan yang ramah, profesional, aman, dan terjangkau bagi masyarakat.
                        </p>
                    </article>
                    <aside class="card identity">
                        <div class="identity-row"><div class="identity-label">Nama</div><div class="identity-value">Praktek Mandiri Bidan Puji Susanti</div></div>
                        <div class="identity-row"><div class="identity-label">Wilayah</div><div class="identity-value">Kecamatan Berbah, Kabupaten Sleman</div></div>
                        <div class="identity-row"><div class="identity-label">Alamat</div><div class="identity-value">Karongan RT.03 / RW.11, Jogotirto, Berbah, Sleman, DIY</div></div>
                        <div class="identity-row"><div class="identity-label">Nomor Izin Usaha</div><div class="identity-value">446/3280/7201/III-25</div></div>
                    </aside>
                </div>
            </div>
        </section>

        <section class="section" id="manfaat" style="padding-top:20px">
            <div class="container">
                <div class="section-head">
                    <div class="kicker">Untuk supplier</div>
                    <h2>Mengapa supplier perlu mengajukan akun?</h2>
                    <p class="section-desc">Akun supplier bukan sekadar akses masuk. Akun menjadi sarana untuk membangun proses kerja sama pengadaan yang lebih tertata antara supplier dan pihak klinik.</p>
                </div>
                <div class="benefit-grid">
                    <article class="benefit"><div class="benefit-icon">01</div><h3>Terdaftar sebagai mitra</h3><p>Data perusahaan dan penanggung jawab tercatat sehingga proses administrasi kerja sama lebih mudah dikelola.</p></article>
                    <article class="benefit"><div class="benefit-icon">02</div><h3>Akses informasi pengadaan</h3><p>Setelah akun disetujui dan fitur supplier tersedia, akun dapat digunakan sebagai identitas resmi dalam proses pengadaan.</p></article>
                    <article class="benefit"><div class="benefit-icon">03</div><h3>Proses lebih terdokumentasi</h3><p>Aktivitas pengadaan dapat ditelusuri berdasarkan data transaksi dan dokumen yang tercatat dalam sistem.</p></article>
                </div>
            </div>
        </section>

        <section class="section" id="alur">
            <div class="container">
                <div class="section-head">
                    <div class="kicker">Alur pengajuan</div>
                    <h2>Pengajuan akun dibuat sederhana.</h2>
                    <p class="section-desc">Tidak ada langkah yang tidak perlu. Supplier mengirim data, klinik melakukan verifikasi, lalu akun dapat digunakan setelah disetujui.</p>
                </div>
                <div class="benefit-grid">
                    <article class="benefit"><div class="benefit-icon">1</div><h3>Isi data supplier</h3><p>Lengkapi nama perusahaan, PIC, kontak, alamat, email, dan password akun.</p></article>
                    <article class="benefit"><div class="benefit-icon">2</div><h3>Menunggu verifikasi</h3><p>Admin klinik memeriksa kelengkapan data sebelum memberikan akses.</p></article>
                    <article class="benefit"><div class="benefit-icon">3</div><h3>Akun disetujui</h3><p>Supplier dapat masuk menggunakan email dan password yang telah didaftarkan.</p></article>
                </div>
            </div>
        </section>

        <section class="section" id="visi-misi">
            <div class="container">
                <div class="section-head">
                    <div class="kicker">Arah pelayanan</div>
                    <h2>Visi dan misi</h2>
                    <p class="section-desc">Landasan pelayanan untuk menjaga mutu, kenyamanan, dan keberlanjutan layanan kesehatan.</p>
                </div>
                <div class="mission-grid">
                    <article class="card vision-card">
                        <div class="kicker">Visi</div>
                        <h3>“Menjadi pusat pelayanan kesehatan yang terkemuka dan terpercaya dalam memberikan perawatan kandungan yang berorientasi pada pasien serta pelayanan umum yang berkualitas bagi masyarakat.”</h3>
                    </article>
                    <div class="mission-list">
                        <article class="mission-item"><div class="mission-number">01</div><p>Memberikan pelayanan kesehatan kandungan yang baik, mulai dari konsultasi prakehamilan hingga pascapersalinan, dengan mengutamakan keamanan dan kenyamanan pasien.</p></article>
                        <article class="mission-item"><div class="mission-number">02</div><p>Menyediakan akses yang mudah dan fasilitas yang nyaman bagi pasien untuk memperoleh pelayanan kesehatan umum yang terjangkau dan berkualitas.</p></article>
                        <article class="mission-item"><div class="mission-number">03</div><p>Menjaga kerja sama dan kolaborasi yang erat dengan berbagai pihak terkait, termasuk rumah sakit, dokter spesialis, dan lembaga kesehatan lainnya, untuk mendukung pelayanan yang terintegrasi.</p></article>
                        <article class="mission-item"><div class="mission-number">04</div><p>Mengutamakan pengembangan sumber daya manusia dan peningkatan kompetensi tenaga medis guna memastikan pelayanan yang profesional, aman, dan berkualitas.</p></article>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" id="layanan">
            <div class="container">
                <div class="section-head">
                    <div class="kicker">Ruang lingkup pelayanan</div>
                    <h2>Layanan yang berorientasi pada kebutuhan pasien.</h2>
                </div>
                <div class="service-grid">
                    <article class="service"><h3>Kesehatan ibu & kandungan</h3><p>Konsultasi dan pemeriksaan terkait kehamilan, mulai dari persiapan kehamilan hingga masa setelah persalinan.</p></article>
                    <article class="service"><h3>Pemeriksaan kesehatan umum</h3><p>Pelayanan pemeriksaan dan konsultasi untuk berbagai keluhan kesehatan umum di masyarakat.</p></article>
                    <article class="service"><h3>Konsultasi kesehatan</h3><p>Pendekatan pelayanan yang mengutamakan keamanan, kenyamanan, dan kebutuhan setiap pasien.</p></article>
                </div>
            </div>
        </section>

        <section class="section" id="sejarah">
            <div class="container">
                <div class="section-head">
                    <div class="kicker">Sejarah singkat</div>
                    <h2>Berawal dari kepedulian, berkembang untuk melayani.</h2>
                </div>
                <div class="history">
                    <article class="history-highlight">
                        <div class="history-year">2010</div>
                        <h3>Awal perjalanan pelayanan</h3>
                        <p>Praktek ini berawal dari Klinik Bidan Praktek Swasta Leni Indrawati yang didirikan pada tahun 2010. Dalam perkembangannya, pengelolaan kemudian dilanjutkan oleh generasi berikutnya dan nama usaha berubah menjadi Praktek Mandiri Bidan Puji Susanti.</p>
                    </article>
                    <article class="history-copy">
                        <p>Sejak awal, pelayanan dibangun atas kepedulian untuk memberikan akses kesehatan yang lebih baik, berkualitas, dan terjangkau bagi masyarakat. Pengalaman dalam praktik kebidanan menjadi dasar untuk terus mengembangkan pelayanan yang profesional dan berorientasi pada pasien.</p>
                        <p>Seiring berjalannya waktu, Praktek Bidan Puji Susanti terus berupaya meningkatkan kualitas layanan dan mempertahankan kepercayaan masyarakat, khususnya bagi ibu hamil, pasien dengan kebutuhan kesehatan reproduksi, serta masyarakat yang membutuhkan pemeriksaan dan konsultasi kesehatan umum.</p>
                        <p>Komitmen tersebut menjadi bagian penting dalam menjaga pelayanan yang ramah, aman, profesional, dan berkelanjutan di wilayah Jogotirto, Sleman, Daerah Istimewa Yogyakarta.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="section" id="struktur">
            <div class="container">
                <div class="section-head">
                    <div class="kicker">Struktur organisasi</div>
                    <h2>Pembagian tugas dan tanggung jawab yang jelas.</h2>
                    <p class="section-desc">Struktur organisasi menggambarkan hubungan kerja, tanggung jawab, dan wewenang setiap bagian dalam mendukung pelayanan.</p>
                </div>
                <div class="org-wrap">
                    <article class="org-info">
                        <h3>Struktur dan tata kerja</h3>
                        <p>Pengelolaan Praktek Bidan Puji Susanti didukung oleh beberapa fungsi pelayanan yang saling berkoordinasi.</p>
                        <ul>
                            <li>Pemilik</li>
                            <li>Koordinator</li>
                            <li>Pelayanan Tenaga Medis</li>
                            <li>Pelayanan Kefarmasian</li>
                            <li>Pelayanan Administrasi</li>
                        </ul>
                    </article>
                    <div class="org-image">
                        @if(file_exists(public_path('images/struktur-organisasi.png')))
                            <img src="{{ asset('images/struktur-organisasi.png') }}" alt="Struktur organisasi Praktek Bidan Puji Susanti">
                        @else
                            <div class="org-placeholder">Struktur organisasi belum tersedia pada folder <strong>public/images</strong>.</div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section class="section" id="lokasi">
            <div class="container">
                <div class="section-head">
                    <div class="kicker">Lokasi</div>
                    <h2>Temukan lokasi Praktek Bidan Puji Susanti.</h2>
                </div>
                <div class="location">
                    <article class="card address-box">
                        <div>
                            <div class="identity-label">Alamat</div>
                            <div class="address-text">Karongan RT.03 / RW.11, Jogotirto, Kecamatan Berbah, Kabupaten Sleman, Daerah Istimewa Yogyakarta.</div>
                            <div class="permit">Nomor Izin Usaha: 446/3280/7201/III-25</div>
                        </div>
                        <div style="margin-top:24px"><a class="btn btn-secondary" target="_blank" rel="noopener" href="https://maps.app.goo.gl/o2nVGunrBZ4HiecX7">Buka di Google Maps ↗</a></div>
                    </article>
                    <div class="map-embed" aria-label="Peta lokasi Praktek Bidan Puji Susanti">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.7345140651987!2d110.4657622008882!3d-7.817901239116243!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a514c87aacc39%3A0xab18954e3873018!2sPraktek%20Bidan%20Puji%20Susanti!5e0!3m2!1sid!2sid!4v1788494283777!5m2!1sid!2sid"
                            title="Peta lokasi Praktek Bidan Puji Susanti"
                            loading="lazy"
                            referrerpolicy="strict-origin-when-cross-origin"
                            allowfullscreen
                        ></iframe>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" style="padding-top:20px">
            <div class="container">
                <div class="cta">
                    <div>
                        <div class="kicker">Untuk supplier</div>
                        <h2>Ingin menjadi mitra pengadaan?</h2>
                        <p>Ajukan akun supplier. Data akan diperiksa terlebih dahulu oleh admin sebelum akun diaktifkan.</p>
                    </div>
                    <a href="{{ route('supplier.register') }}" class="btn btn-primary">Ajukan Akun Supplier →</a>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container footer-grid">
            <span>© {{ date('Y') }} <strong>Praktek Bidan Puji Susanti</strong></span>
            <span>Karongan RT.03 / RW.11, Jogotirto, Berbah, Sleman, DIY</span>
        </div>
    </footer>

    <script>
        const root = document.documentElement;
        const themeToggle = document.getElementById('themeToggle');
        const menuToggle = document.getElementById('menuToggle');
        const navLinks = document.getElementById('navLinks');

        function applyTheme(theme) {
            const dark = theme === 'dark';
            root.classList.toggle('dark', dark);
            localStorage.setItem('theme', dark ? 'dark' : 'light');
            localStorage.setItem('filament_theme', dark ? 'dark' : 'light');
            themeToggle.textContent = dark ? '☀' : '☾';
            themeToggle.setAttribute('aria-label', dark ? 'Gunakan mode terang' : 'Gunakan mode gelap');
        }

        applyTheme(root.classList.contains('dark') ? 'dark' : 'light');

        themeToggle.addEventListener('click', () => applyTheme(root.classList.contains('dark') ? 'light' : 'dark'));
        menuToggle.addEventListener('click', () => navLinks.classList.toggle('open'));
        navLinks.querySelectorAll('a').forEach(link => link.addEventListener('click', () => navLinks.classList.remove('open')));
    </script>
</body>
</html>
