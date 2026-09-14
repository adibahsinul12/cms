<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($site_name ?? 'Company Profile Resmi') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --brand: #2563EB;
            --brand-dark: #1D4ED8;
            --heading: #0F172A;
            --body: #475569;
            --light: #F8FAFC;
            --border: #E2E8F0;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; color: var(--body); background: #fff; line-height: 1.6; }
        .container { max-width: 1140px; margin: 0 auto; padding: 0 24px; }

        /* Header */
        .cp-header { border-bottom: 1px solid var(--border); background: #fff; position: sticky; top: 0; z-index: 40; }
        .cp-nav { display: flex; justify-content: space-between; align-items: center; height: 74px; }
        .cp-brand { font-size: 20px; font-weight: 800; color: var(--heading); text-decoration: none; display: flex; align-items: center; gap: 10px; }
        .cp-brand span { color: var(--brand); }
        .cp-menu { display: flex; align-items: center; gap: 24px; list-style: none; }
        .cp-menu a { text-decoration: none; color: var(--body); font-size: 14.5px; font-weight: 500; transition: 0.2s; }
        .cp-menu a:hover { color: var(--brand); }
        .btn-action { background: var(--brand); color: #fff !important; padding: 9px 18px; border-radius: 8px; font-weight: 600; transition: 0.2s; }
        .btn-action:hover { background: var(--brand-dark); }

        /* Hero */
        .cp-hero { padding: 90px 0 80px; text-align: center; background: radial-gradient(circle at 50% 0%, #EFF6FF 0%, #fff 70%); }
        .pill { display: inline-block; background: #DBEAFE; color: var(--brand); font-size: 12.5px; font-weight: 700; padding: 5px 16px; border-radius: 50px; margin-bottom: 20px; }
        .cp-hero h1 { font-size: 44px; font-weight: 800; color: var(--heading); line-height: 1.25; max-width: 800px; margin: 0 auto 16px; letter-spacing: -1px; }
        .cp-hero p { font-size: 17px; max-width: 640px; margin: 0 auto 32px; color: var(--body); }
        .hero-btns { display: flex; gap: 14px; justify-content: center; }
        .btn-sec { background: #fff; border: 1px solid var(--border); color: var(--heading); text-decoration: none; padding: 11px 22px; border-radius: 8px; font-size: 14.5px; font-weight: 600; transition: 0.2s; }
        .btn-sec:hover { background: #F1F5F9; }

        /* Section Layanan */
        .services-sec { padding: 80px 0; background: var(--light); border-top: 1px solid var(--border); }
        .sec-title { text-align: center; margin-bottom: 48px; }
        .sec-title h2 { font-size: 30px; font-weight: 800; color: var(--heading); margin-bottom: 8px; }
        .sec-title p { font-size: 15.5px; color: var(--body); }
        .services-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; }
        .svc-card { background: #fff; border: 1px solid var(--border); border-radius: 14px; padding: 32px; transition: transform 0.2s, box-shadow 0.2s; }
        .svc-card:hover { transform: translateY(-4px); box-shadow: 0 16px 30px rgba(0,0,0,0.06); }
        .svc-icon { width: 52px; height: 52px; border-radius: 12px; background: #EFF6FF; color: var(--brand); display: flex; align-items: center; justify-content: center; font-size: 22px; margin-bottom: 20px; }
        .svc-card h3 { font-size: 18px; font-weight: 700; color: var(--heading); margin-bottom: 10px; }
        .svc-card p { font-size: 14px; color: var(--body); line-height: 1.6; }

        /* Section Berita / Kabar */
        .news-sec { padding: 80px 0; }
        .news-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 28px; }
        .news-card { border: 1px solid var(--border); border-radius: 14px; overflow: hidden; background: #fff; display: flex; flex-direction: column; transition: 0.2s; }
        .news-card:hover { box-shadow: 0 10px 24px rgba(0,0,0,0.05); }
        .news-body { padding: 24px; flex: 1; display: flex; flex-direction: column; }
        .news-tag { font-size: 11px; font-weight: 700; color: var(--brand); text-transform: uppercase; margin-bottom: 8px; }
        .news-title { font-size: 17px; font-weight: 700; color: var(--heading); margin-bottom: 10px; line-height: 1.4; }
        .news-desc { font-size: 13.5px; color: var(--body); line-height: 1.6; margin-bottom: 18px; flex: 1; }
        .news-date { font-size: 12px; color: #94A3B8; }

        /* Footer */
        footer { background: #0F172A; color: #94A3B8; padding: 50px 0 30px; border-top: 1px solid #1E293B; }
        .ft-bottom { text-align: center; font-size: 13.5px; }
    </style>
</head>
<body>

    <!-- NAVBAR COMPANY -->
    <header class="cp-header">
        <div class="container cp-nav">
            <a href="<?= base_url('home') ?>" class="cp-brand">
                <i class="fas fa-building text-primary"></i> <?= htmlspecialchars($site_name ?? 'Company Profile') ?>
            </a>
            <ul class="cp-menu">
                <li><a href="#tentang">Tentang Kami</a></li>
                <li><a href="#layanan">Layanan</a></li>
                <li><a href="#berita">Publikasi</a></li>
                <?php if ($is_logged_in): ?>
                    <li><a href="<?= base_url('admin/dashboard') ?>" class="btn-action"><i class="fas fa-th-large"></i> Dashboard</a></li>
                <?php else: ?>
                    <li><a href="<?= base_url('login') ?>">Masuk</a></li>
                    <li><a href="<?= base_url('register') ?>" class="btn-action">Daftar Akun</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </header>

    <!-- HERO COMPANY -->
    <section class="cp-hero">
        <div class="container">
            <span class="pill"><i class="fas fa-certificate"></i> Lembaga Resmi & Terpercaya</span>
            <h1><?= htmlspecialchars($site_description ?? 'Membangun Masa Depan Lebih Baik Bersama Kami') ?></h1>
            <p>Berkomitmen memberikan transparansi, kinerja profesional, serta layanan prima yang berorientasi pada hasil nyata.</p>
            <div class="hero-btns">
                <a href="#berita" class="btn-action" style="padding:11px 24px; font-size:15px;">Lihat Publikasi</a>
                <a href="#layanan" class="btn-sec">Layanan Unggulan</a>
            </div>
        </div>
    </section>

    <!-- SECTION LAYANAN -->
    <section class="services-sec" id="layanan">
        <div class="container">
            <div class="sec-title">
                <h2>Pilar Layanan Utama</h2>
                <p>Standar mutu terdepan yang dirancang untuk mendukung kebutuhan masyarakat dan mitra.</p>
            </div>
            <div class="services-grid">
                <div class="svc-card">
                    <div class="svc-icon"><i class="fas fa-award"></i></div>
                    <h3>Kualitas & Integritas</h3>
                    <p>Menjunjung tinggi standar profesionalitas, keterbukaan informasi, dan kepuasan pemangku kepentingan.</p>
                </div>
                <div class="svc-card">
                    <div class="svc-icon"><i class="fas fa-bolt"></i></div>
                    <h3>Respon Cepat & Tanggap</h3>
                    <p>Penyampaian informasi dan penanganan aspirasi yang terintegrasi secara cepat, tepat, dan transparan.</p>
                </div>
                <div class="svc-card">
                    <div class="svc-icon"><i class="fas fa-shield-alt"></i></div>
                    <h3>Keamanan & Akuntabilitas</h3>
                    <p>Sistem operasional berstandar tinggi yang memastikan seluruh kegiatan terdokumentasi dengan rapi.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION ARTIKEL / KABAR TERBARU -->
    <section class="news-sec" id="berita">
        <div class="container">
            <div class="sec-title">
                <h2>Kabar & Publikasi Terkini</h2>
                <p>Informasi terbaru seputar kegiatan, laporan kegiatan, dan berita penting.</p>
            </div>
            <div class="news-grid" id="cp-news-container">
                <div style="grid-column:1/-1; text-align:center; color:var(--body); padding:30px;">
                    <i class="fas fa-spinner fa-spin"></i> Memuat artikel terbaru...
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="container ft-bottom">
            <p><?= htmlspecialchars($footer_text ?? '&copy; ' . date('Y') . ' ' . $site_name . '. All rights reserved.') ?></p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: '<?= base_url("api/public/posts") ?>',
                method: 'GET',
                success: function(res) {
                    const posts = res.data || [];
                    if (!posts.length) {
                        $('#cp-news-container').html('<div style="grid-column:1/-1; text-align:center; color:var(--body);">Belum ada publikasi atau artikel yang diterbitkan.</div>');
                        return;
                    }
                    let html = '';
                    posts.forEach(function(p) {
                        html += `
                            <div class="news-card">
                                <div class="news-body">
                                    <span class="news-tag">${p.category_name || 'INFORMASI'}</span>
                                    <h3 class="news-title">${p.title}</h3>
                                    <p class="news-desc">${p.excerpt || p.content || 'Informasi selengkapnya mengenai publikasi ini.'}</p>
                                    <span class="news-date"><i class="far fa-calendar-alt"></i> ${new Date(p.created_at).toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'})}</span>
                                </div>
                            </div>
                        `;
                    });
                    $('#cp-news-container').html(html);
                },
                error: function() {
                    $('#cp-news-container').html('<div style="grid-column:1/-1; text-align:center; color:#EF4444;">Gagal memuat publikasi.</div>');
                }
            });
        });
    </script>
</body>
</html>

