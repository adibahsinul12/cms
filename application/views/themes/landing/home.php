<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($site_name ?? 'Modern SaaS & Startup') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #06B6D4;
            --primary-dark: #0891B2;
            --dark: #0F172A;
            --body: #475569;
            --bg: #FFFFFF;
            --surface: #F8FAFC;
            --border: #E2E8F0;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--body); line-height: 1.6; }
        .container { max-width: 1160px; margin: 0 auto; padding: 0 24px; }
        a { text-decoration: none; color: inherit; }

        /* Navbar */
        .saas-nav { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 50; }
        .nav-wrap { display: flex; justify-content: space-between; align-items: center; height: 74px; }
        .saas-logo { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 20px; font-weight: 800; color: var(--dark); display: flex; align-items: center; gap: 8px; }
        .saas-logo i { color: var(--primary); }
        .btn-cta { background: var(--dark); color: #fff !important; font-size: 14px; font-weight: 600; padding: 10px 20px; border-radius: 8px; transition: 0.2s; }
        .btn-cta:hover { opacity: 0.9; }

        /* Hero */
        .saas-hero { padding: 90px 0 80px; text-align: center; background: radial-gradient(circle at 50% 10%, #ECFEFF 0%, #FFFFFF 60%); }
        .badge-pill { display: inline-flex; align-items: center; gap: 8px; background: #CFFAFE; color: #0891B2; font-size: 13px; font-weight: 700; padding: 6px 16px; border-radius: 50px; margin-bottom: 24px; }
        .saas-hero h1 { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 48px; font-weight: 800; color: var(--dark); line-height: 1.2; max-width: 820px; margin: 0 auto 20px; letter-spacing: -1px; }
        .saas-hero p { font-size: 18px; max-width: 620px; margin: 0 auto 36px; }
        .hero-actions { display: flex; justify-content: center; gap: 14px; }
        .btn-primary-hero { background: var(--primary); color: #fff; font-size: 15px; font-weight: 700; padding: 12px 28px; border-radius: 8px; box-shadow: 0 10px 20px rgba(6, 182, 212, 0.25); }
        .btn-sec-hero { background: #fff; border: 1px solid var(--border); color: var(--dark); font-size: 15px; font-weight: 600; padding: 12px 24px; border-radius: 8px; }

        /* Features */
        .features-sec { padding: 80px 0; background: var(--surface); border-top: 1px solid var(--border); }
        .sec-head { text-align: center; margin-bottom: 50px; }
        .sec-head h2 { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 32px; font-weight: 800; color: var(--dark); margin-bottom: 10px; }
        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; }
        .feat-box { background: #fff; border: 1px solid var(--border); border-radius: 14px; padding: 32px; }
        .feat-icon { width: 48px; height: 48px; border-radius: 10px; background: #ECFEFF; color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 18px; }
        .feat-box h3 { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 18px; font-weight: 700; color: var(--dark); margin-bottom: 8px; }

        /* Pricing Table */
        .pricing-sec { padding: 90px 0; }
        .pricing-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 28px; max-width: 900px; margin: 0 auto; }
        .price-card { border: 1px solid var(--border); border-radius: 16px; padding: 36px; background: #fff; position: relative; }
        .price-card.popular { border-color: var(--primary); box-shadow: 0 16px 32px rgba(6, 182, 212, 0.12); }
        .badge-pop { position: absolute; top: -12px; right: 24px; background: var(--primary); color: #fff; font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 50px; }
        .price-num { font-size: 36px; font-weight: 800; color: var(--dark); margin: 16px 0; }
        .price-features { list-style: none; margin: 24px 0; display: flex; flex-direction: column; gap: 12px; font-size: 14px; }
        .price-features li i { color: var(--primary); margin-right: 8px; }

        /* Footer */
        footer { border-top: 1px solid var(--border); padding: 40px 0; text-align: center; font-size: 13.5px; }
    </style>
</head>
<body>

    <nav class="saas-nav">
        <div class="container nav-wrap">
            <a href="<?= base_url('home') ?>" class="saas-logo">
                <i class="fas fa-bolt"></i> <?= htmlspecialchars($site_name ?? 'SaaS Platform') ?>
            </a>
            <div>
                <?php if ($is_logged_in): ?>
                    <a href="<?= base_url('admin/dashboard') ?>" class="btn-cta"><i class="fas fa-th-large"></i> Dashboard</a>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>" style="margin-right:16px; font-weight:600;">Masuk</a>
                    <a href="<?= base_url('register') ?>" class="btn-cta">Coba Gratis</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <header class="saas-hero">
        <div class="container">
            <span class="badge-pill"><i class="fas fa-sparkles"></i> Solusi Modern Generasi Baru</span>
            <h1><?= htmlspecialchars($site_description ?? 'Otomatisasi & Skalakan Bisnis Anda Lebih Cepat Tanpa Batas') ?></h1>
            <p>Platform all-in-one yang dirancang untuk mempercepat alur kerja, meningkatkan konversi, dan memberikan hasil maksimal.</p>
            <div class="hero-actions">
                <a href="#fitur" class="btn-primary-hero">Mulai Sekarang</a>
                <a href="#harga" class="btn-sec-hero">Lihat Paket</a>
            </div>
        </div>
    </header>

    <section class="features-sec" id="fitur">
        <div class="container">
            <div class="sec-head">
                <h2>Fitur Yang Anda Butuhkan</h2>
                <p>Dibangun dengan arsitektur cloud berkinerja tinggi untuk skalabilitas tanpa kompromi.</p>
            </div>
            <div class="features-grid">
                <div class="feat-box">
                    <div class="feat-icon"><i class="fas fa-rocket"></i></div>
                    <h3>Performa Cepat</h3>
                    <p>Waktu muat ultra cepat dengan optimasi aset modern untuk retensi pengguna optimal.</p>
                </div>
                <div class="feat-box">
                    <div class="feat-icon"><i class="fas fa-shield-halved"></i></div>
                    <h3>Keamanan Teruji</h3>
                    <p>Enkripsi end-to-end, proteksi data berlapis, dan backup berkala otomatis.</p>
                </div>
                <div class="feat-box">
                    <div class="feat-icon"><i class="fas fa-chart-line"></i></div>
                    <h3>Analitik Terpadu</h3>
                    <p>Pantau metrik penting, lalu lintas pengguna, dan performa konten secara real-time.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="pricing-sec" id="harga">
        <div class="container">
            <div class="sec-head">
                <h2>Pilihan Paket Fleksibel</h2>
                <p>Pilih paket yang paling sesuai dengan kebutuhan pertumbuhan Anda.</p>
            </div>
            <div class="pricing-grid">
                <div class="price-card">
                    <h3 style="font-size:20px; font-weight:700; color:var(--dark);">Starter</h3>
                    <p style="font-size:13px;">Cocok untuk perorangan atau proyek baru.</p>
                    <div class="price-num">Gratis</div>
                    <ul class="price-features">
                        <li><i class="fas fa-check"></i> Hingga 5 Proyek Aktif</li>
                        <li><i class="fas fa-check"></i> Akses Fitur Inti</li>
                        <li><i class="fas fa-check"></i> Dukungan Komunitas</li>
                    </ul>
                    <a href="<?= base_url('register') ?>" class="btn-sec-hero" style="display:block; text-align:center;">Daftar Sekarang</a>
                </div>

                <div class="price-card popular">
                    <span class="badge-pop">Paling Populer</span>
                    <h3 style="font-size:20px; font-weight:700; color:var(--dark);">Professional</h3>
                    <p style="font-size:13px;">Untuk bisnis berkembang & agensi.</p>
                    <div class="price-num">Rp 199k<span style="font-size:14px; font-weight:500; color:var(--body);">/bulan</span></div>
                    <ul class="price-features">
                        <li><i class="fas fa-check"></i> Proyek Tanpa Batas</li>
                        <li><i class="fas fa-check"></i> Kustomisasi Domain & Tema</li>
                        <li><i class="fas fa-check"></i> Dukungan Prioritas 24/7</li>
                    </ul>
                    <a href="<?= base_url('register') ?>" class="btn-primary-hero" style="display:block; text-align:center;">Pilih Professional</a>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p><?= htmlspecialchars($footer_text ?? '&copy; ' . date('Y') . ' ' . $site_name . '. All rights reserved.') ?></p>
        </div>
    </footer>

</body>
</html>

