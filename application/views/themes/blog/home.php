<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($site_name ?? 'Blog Pribadi & Tulisan') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,400;0,700;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #EA580C;
            --dark: #0F172A;
            --body: #334155;
            --muted: #64748B;
            --bg: #FAFAF9;
            --card-bg: #FFFFFF;
            --border: #E7E5E4;
            --font-heading: 'Merriweather', serif;
            --font-body: 'Inter', sans-serif;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: var(--font-body); background: var(--bg); color: var(--body); line-height: 1.7; }
        .container { max-width: 900px; margin: 0 auto; padding: 0 24px; }
        a { text-decoration: none; color: inherit; transition: color 0.2s; }
        a:hover { color: var(--primary); }

        /* Header Blog */
        .blog-header { border-bottom: 1px solid var(--border); background: #fff; padding: 32px 0; text-align: center; }
        .site-title { font-family: var(--font-heading); font-size: 32px; font-weight: 700; color: var(--dark); margin-bottom: 6px; letter-spacing: -0.5px; }
        .site-tagline { font-size: 15px; color: var(--muted); max-width: 500px; margin: 0 auto 20px; }
        .blog-nav { display: flex; justify-content: center; gap: 20px; font-size: 13.5px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

        /* Main Content */
        .main-content { padding: 48px 0 80px; }
        .posts-list { display: flex; flex-direction: column; gap: 40px; }
        .post-entry { background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 32px; transition: transform 0.2s, box-shadow 0.2s; }
        .post-entry:hover { transform: translateY(-3px); box-shadow: 0 12px 24px rgba(0,0,0,0.04); }
        .post-meta { font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--primary); letter-spacing: 0.5px; margin-bottom: 8px; }
        .post-title { font-family: var(--font-heading); font-size: 24px; font-weight: 700; color: var(--dark); margin-bottom: 12px; line-height: 1.35; }
        .post-excerpt { color: #475569; font-size: 15px; line-height: 1.7; margin-bottom: 18px; }
        .post-footer { display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: var(--muted); border-top: 1px solid #F5F5F4; padding-top: 14px; }
        .btn-read { font-weight: 700; color: var(--primary); display: inline-flex; align-items: center; gap: 6px; font-size: 13.5px; }

        /* Footer */
        footer { border-top: 1px solid var(--border); background: #fff; padding: 40px 0; text-align: center; font-size: 13.5px; color: var(--muted); }
    </style>
</head>
<body>

    <header class="blog-header">
        <div class="container">
            <h1 class="site-title"><a href="<?= base_url('home') ?>"><?= htmlspecialchars($site_name ?? 'Blog Minimalis') ?></a></h1>
            <p class="site-tagline"><?= htmlspecialchars($site_description ?? 'Kumpulan pemikiran, catatan, dan artikel pilihan.') ?></p>
            <nav class="blog-nav">
                <a href="<?= base_url('home') ?>">Beranda</a>
                <?php if ($is_logged_in): ?>
                    <a href="<?= base_url('admin/dashboard') ?>" style="color:var(--primary);"><i class="fas fa-edit"></i> Tulis Artikel</a>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>">Masuk</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <div class="posts-list" id="blog-posts-container">
                <div style="text-align:center; padding: 40px; color:var(--muted);">
                    <i class="fas fa-spinner fa-spin fa-2x"></i><br><br>Memuat artikel blog...
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
                    const posts = res.data || [];
                    if (!posts.length) {
                        $('#blog-posts-container').html('<div style="text-align:center; padding:40px; color:var(--muted);">Belum ada tulisan di blog ini.</div>');
                        return;
                    }
                    let html = '';
                    posts.forEach(function(p) {
                        const dateStr = new Date(p.created_at).toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'});
                        html += `
                            <article class="post-entry">
                                <div class="post-meta">${p.category_name || 'ARTIKEL'}</div>
                                <h2 class="post-title"><a href="#">${p.title}</a></h2>
                                <p class="post-excerpt">${p.excerpt || p.content || 'Baca selengkapnya artikel ini untuk mengetahui wawasan mendalam.'}</p>
                                <div class="post-footer">
                                    <span><i class="far fa-user"></i> ${p.author_name || 'Penulis'} • <i class="far fa-calendar-alt"></i> ${dateStr}</span>
                                    <a href="#" class="btn-read">Baca Artikel <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </article>
                        `;
                    });
                    $('#blog-posts-container').html(html);
                },
                error: function() {
                    $('#blog-posts-container').html('<div style="text-align:center; color:#EF4444;">Gagal memuat artikel blog.</div>');
                }
            });
        });
    </script>
</body>
</html>

