<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($site_name ?? 'Portofolio Kreatif') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #8B5CF6;
            --dark: #09090B;
            --surface: #18181B;
            --border: #27272A;
            --text: #FAFAFA;
            --muted: #A1A1AA;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--dark); color: var(--text); line-height: 1.6; }
        .container { max-width: 1180px; margin: 0 auto; padding: 0 24px; }
        a { text-decoration: none; color: inherit; }

        /* Navbar */
        .port-nav { border-bottom: 1px solid var(--border); padding: 24px 0; background: rgba(9, 9, 11, 0.85); backdrop-filter: blur(8px); position: sticky; top: 0; z-index: 50; }
        .nav-inner { display: flex; justify-content: space-between; align-items: center; }
        .port-logo { font-size: 20px; font-weight: 800; letter-spacing: -0.5px; }
        .port-logo span { color: var(--primary); }
        .nav-links { display: flex; gap: 24px; align-items: center; font-size: 14px; font-weight: 600; }
        .nav-links a:hover { color: var(--primary); }
        .btn-hire { background: var(--primary); color: #fff; padding: 8px 18px; border-radius: 8px; transition: opacity 0.2s; }
        .btn-hire:hover { opacity: 0.9; }

        /* Hero */
        .port-hero { padding: 90px 0 70px; text-align: center; }
        .hero-tag { display: inline-block; background: rgba(139, 92, 246, 0.15); color: #C4B5FD; font-size: 12.5px; font-weight: 700; padding: 5px 16px; border-radius: 50px; margin-bottom: 20px; border: 1px solid rgba(139, 92, 246, 0.3); }
        .port-hero h1 { font-size: 46px; font-weight: 800; line-height: 1.2; max-width: 800px; margin: 0 auto 16px; letter-spacing: -1px; }
        .port-hero p { color: var(--muted); font-size: 18px; max-width: 600px; margin: 0 auto; }

        /* Showcase Grid */
        .showcase { padding: 40px 0 100px; }
        .grid-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; }
        .grid-header h2 { font-size: 24px; font-weight: 700; }
        .works-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 28px; }
        .work-card { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; transition: transform 0.25s, border-color 0.25s; }
        .work-card:hover { transform: translateY(-5px); border-color: var(--primary); }
        .work-thumb { height: 220px; background: #27272A; display: flex; align-items: center; justify-content: center; font-size: 42px; color: var(--muted); }
        .work-body { padding: 24px; }
        .work-cat { color: var(--primary); font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 6px; }
        .work-title { font-size: 18px; font-weight: 700; margin-bottom: 8px; }
        .work-desc { color: var(--muted); font-size: 14px; line-height: 1.6; }

        /* Footer */
        footer { border-top: 1px solid var(--border); padding: 40px 0; text-align: center; color: var(--muted); font-size: 14px; }
    </style>
</head>
<body>

    <nav class="port-nav">
        <div class="container nav-inner">
            <a href="<?= base_url('home') ?>" class="port-logo">
                <span>✦</span> <?= htmlspecialchars($site_name ?? 'Portofolio') ?>
            </a>
            <div class="nav-links">
                <a href="#karya">Karya</a>
                <?php if ($is_logged_in): ?>
                    <a href="<?= base_url('admin/dashboard') ?>" class="btn-hire"><i class="fas fa-th-large"></i> Dashboard</a>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>">Masuk</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <header class="port-hero">
        <div class="container">
            <span class="hero-tag">Showcase Karya & Desain</span>
            <h1><?= htmlspecialchars($site_description ?? 'Menciptakan Pengalaman Visual yang Berkesan & Bermakna') ?></h1>
            <p>Eksplorasi karya pilihan, proyek digital, dan solusi desain kreatif masa kini.</p>
        </div>
    </header>

    <main class="showcase" id="karya">
        <div class="container">
            <div class="grid-header">
                <h2>Galeri Proyek</h2>
                <span id="work-count" style="color:var(--muted); font-size:14px;"></span>
            </div>
            <div class="works-grid" id="portfolio-container">
                <div style="grid-column:1/-1; text-align:center; padding:50px; color:var(--muted);">
                    <i class="fas fa-spinner fa-spin fa-2x"></i><br><br>Memuat portofolio...
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
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
                    const items = res.data || [];
                    $('#work-count').text(`${items.length} Karya`);
                    if (!items.length) {
                        $('#portfolio-container').html('<div style="grid-column:1/-1; text-align:center; padding:40px; color:var(--muted);">Belum ada karya yang diunggah.</div>');
                        return;
                    }
                    let html = '';
                    items.forEach(function(item) {
                        html += `
                            <div class="work-card">
                                <div class="work-thumb">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                <div class="work-body">
                                    <div class="work-cat">${item.category_name || 'PROJECT'}</div>
                                    <h3 class="work-title">${item.title}</h3>
                                    <p class="work-desc">${item.excerpt || item.content || 'Eksplorasi konsep dan eksekusi visual untuk proyek ini.'}</p>
                                </div>
                            </div>
                        `;
                    });
                    $('#portfolio-container').html(html);
                },
                error: function() {
                    $('#portfolio-container').html('<div style="grid-column:1/-1; text-align:center; color:#EF4444;">Gagal memuat karya.</div>');
                }
            });
        });
    </script>
</body>
</html>

