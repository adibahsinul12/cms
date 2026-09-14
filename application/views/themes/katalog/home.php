<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($site_name ?? 'Katalog Toko Online') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #10B981;
            --primary-dark: #059669;
            --dark: #0F172A;
            --muted: #64748B;
            --bg: #F8FAFC;
            --card-bg: #FFFFFF;
            --border: #E2E8F0;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--dark); line-height: 1.6; }
        .container { max-width: 1180px; margin: 0 auto; padding: 0 20px; }

        /* Navbar */
        .shop-nav { background: #fff; border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 50; }
        .nav-wrap { display: flex; justify-content: space-between; align-items: center; height: 70px; }
        .shop-logo { font-size: 20px; font-weight: 700; color: var(--dark); text-decoration: none; display: flex; align-items: center; gap: 8px; }
        .shop-logo span { color: var(--primary); }
        .nav-right { display: flex; align-items: center; gap: 12px; }
        .btn-dash { background: var(--dark); color: #fff; text-decoration: none; font-size: 13.5px; font-weight: 600; padding: 8px 16px; border-radius: 8px; transition: 0.2s; }
        .btn-dash:hover { opacity: 0.9; }
        .btn-auth { text-decoration: none; font-size: 14px; font-weight: 600; color: var(--muted); padding: 8px 14px; }
        .btn-auth:hover { color: var(--dark); }

        /* Banner Hero Toko */
        .shop-hero { background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); color: #fff; padding: 60px 0 70px; text-align: center; }
        .hero-badge { display: inline-block; background: rgba(16, 185, 129, 0.2); color: #34D399; font-size: 12px; font-weight: 700; padding: 4px 14px; border-radius: 50px; margin-bottom: 16px; border: 1px solid rgba(16, 185, 129, 0.3); }
        .shop-hero h1 { font-size: 36px; font-weight: 700; margin-bottom: 12px; letter-spacing: -0.5px; }
        .shop-hero p { color: #94A3B8; font-size: 16px; max-width: 600px; margin: 0 auto 30px; }
        
        .search-bar { max-width: 480px; margin: 0 auto; display: flex; background: #fff; border-radius: 10px; padding: 5px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); }
        .search-bar input { flex: 1; border: none; outline: none; padding: 10px 14px; font-size: 14px; font-family: inherit; }
        .search-bar button { background: var(--primary); color: #fff; border: none; padding: 0 20px; border-radius: 7px; font-weight: 600; cursor: pointer; }
        .search-bar button:hover { background: var(--primary-dark); }

        /* Fitur Unggulan Toko */
        .features-bar { background: #fff; border-bottom: 1px solid var(--border); padding: 18px 0; }
        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; text-align: center; }
        .feat-item { display: flex; align-items: center; justify-content: center; gap: 10px; font-size: 13.5px; font-weight: 600; color: var(--dark); }
        .feat-item i { color: var(--primary); font-size: 18px; }

        /* Katalog Produk */
        .catalog-section { padding: 50px 0 80px; }
        .section-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px; }
        .section-header h2 { font-size: 24px; font-weight: 700; }
        .section-header p { color: var(--muted); font-size: 14px; }

        .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 24px; }
        .product-card { background: #fff; border: 1px solid var(--border); border-radius: 14px; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s; display: flex; flex-direction: column; }
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 14px 28px rgba(0,0,0,0.06); }
        .product-thumb { height: 180px; background: #E2E8F0; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; }
        .product-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .product-thumb .no-img { color: #94A3B8; font-size: 32px; }
        .badge-cat { position: absolute; top: 12px; left: 12px; background: rgba(15, 23, 42, 0.75); color: #fff; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 6px; backdrop-filter: blur(4px); }

        .product-body { padding: 18px; flex: 1; display: flex; flex-direction: column; }
        .product-title { font-size: 16px; font-weight: 700; margin-bottom: 6px; color: var(--dark); cursor: pointer; }
        .product-title:hover { color: var(--primary); }
        .product-desc { color: var(--muted); font-size: 13px; line-height: 1.5; margin-bottom: 16px; flex: 1; }

        .product-footer { display: flex; flex-direction: column; gap: 8px; padding-top: 14px; border-top: 1px solid #F1F5F9; }
        .btn-wa { display: flex; align-items: center; justify-content: center; gap: 8px; background: #25D366; color: #fff; text-decoration: none; padding: 9px 14px; border-radius: 8px; font-size: 13px; font-weight: 700; transition: 0.2s; }
        .btn-wa:hover { background: #1EBE5D; }
        .btn-detail { background: #F1F5F9; color: var(--dark); border: none; padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; transition: 0.2s; }
        .btn-detail:hover { background: #E2E8F0; }

        /* Modal Detail */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.65); z-index: 100; align-items: center; justify-content: center; padding: 20px; }
        .modal-overlay.show { display: flex; }
        .modal-box { background: #fff; border-radius: 16px; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto; padding: 28px; }

        /* Footer */
        footer { background: #fff; border-top: 1px solid var(--border); padding: 30px 0; text-align: center; color: var(--muted); font-size: 13.5px; }
    </style>
</head>
<body>

    <!-- NAVBAR TOKO -->
    <nav class="shop-nav">
        <div class="container nav-wrap">
            <a href="<?= base_url('home') ?>" class="shop-logo">
                <i class="fas fa-store"></i> <?= htmlspecialchars($site_name ?? 'Katalog UMKM') ?>
            </a>
            <div class="nav-right">
                <?php if ($is_logged_in): ?>
                    <a href="<?= base_url('admin/dashboard') ?>" class="btn-dash">
                        <i class="fas fa-box"></i> Kelola Toko / Dashboard
                    </a>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>" class="btn-auth">Masuk</a>
                    <a href="<?= base_url('register') ?>" class="btn-dash">Buka Toko</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- HERO TOKO -->
    <header class="shop-hero">
        <div class="container">
            <span class="hero-badge"><i class="fas fa-check-circle"></i> Etalase Resmi & Terpercaya</span>
            <h1><?= htmlspecialchars($site_description ?? 'Katalog Produk & Penawaran Terbaik') ?></h1>
            <p>Temukan aneka pilihan produk unggulan berkualitas langsung dari penjual.</p>
            <div class="search-bar">
                <input type="text" id="search-input" placeholder="Cari nama produk atau barang...">
                <button type="button" id="search-btn"><i class="fas fa-search"></i> Cari</button>
            </div>
        </div>
    </header>

    <!-- HIGHLIGHT FITUR -->
    <div class="features-bar">
        <div class="container features-grid">
            <div class="feat-item"><i class="fas fa-shield-alt"></i> Transaksi Aman & Terpercaya</div>
            <div class="feat-item"><i class="fab fa-whatsapp"></i> Fast Respon Chat WhatsApp</div>
            <div class="feat-item"><i class="fas fa-truck-fast"></i> Siap Kirim ke Seluruh Wilayah</div>
        </div>
    </div>

    <!-- KATALOG PRODUK -->
    <main class="catalog-section">
        <div class="container">
            <div class="section-header">
                <div>
                    <h2>Daftar Produk & Layanan</h2>
                    <p>Pilih barang yang kamu suka dan pesan langsung via WhatsApp</p>
                </div>
                <span id="product-count" style="font-size:13.5px; color:var(--muted); font-weight:600;"></span>
            </div>

            <div class="products-grid" id="catalog-container">
                <div style="grid-column: 1/-1; text-align:center; padding: 40px; color: var(--muted);">
                    <i class="fas fa-spinner fa-spin fa-2x"></i><br><br>Memuat katalog produk...
                </div>
            </div>
        </div>
    </main>

    <!-- MODAL DETAIL PRODUK -->
    <div class="modal-overlay" id="product-modal">
        <div class="modal-box">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px;">
                <span id="modal-cat" style="background:var(--primary); color:#fff; font-size:11px; font-weight:700; padding:3px 10px; border-radius:6px;">PRODUK</span>
                <button type="button" id="btn-close-modal" style="border:none; background:none; font-size:24px; cursor:pointer; color:var(--muted);">&times;</button>
            </div>
            <h2 id="modal-title" style="font-size:22px; font-weight:700; margin-bottom:12px;"></h2>
            <div id="modal-desc" style="font-size:14.5px; color:var(--muted); line-height:1.7; margin-bottom:24px; border-bottom:1px solid var(--border); padding-bottom:18px;"></div>
            
            <a href="#" id="modal-btn-wa" target="_blank" class="btn-wa" style="font-size:14px; padding:12px; margin-bottom:24px;">
                <i class="fab fa-whatsapp fa-lg"></i> Hubungi Penjual via WhatsApp
            </a>

            <h4 style="font-size:16px; font-weight:700; margin-bottom:12px;">💬 Ulasan & Pertanyaan (<span id="modal-comment-count">0</span>)</h4>
            <div id="modal-comments" style="display:flex; flex-direction:column; gap:8px; margin-bottom:20px;"></div>

            <!-- Form Pertanyaan -->
            <div style="background:var(--bg); padding:16px; border-radius:10px; border:1px solid var(--border);">
                <h5 style="font-size:13px; font-weight:700; margin-bottom:8px;">Tanya Penjual / Beri Ulasan</h5>
                <input type="hidden" id="comment-post-id">
                <input type="text" id="comment-name" placeholder="Nama Anda *" style="width:100%; padding:8px 10px; border:1px solid var(--border); border-radius:6px; font-size:13px; margin-bottom:8px;">
                <textarea id="comment-text" rows="2" placeholder="Tulis pertanyaan atau ulasan... *" style="width:100%; padding:8px 10px; border:1px solid var(--border); border-radius:6px; font-size:13px; margin-bottom:8px;"></textarea>
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <small id="comment-status" style="font-size:12px; font-weight:600;"></small>
                    <button type="button" id="btn-send-comment" style="background:var(--dark); color:#fff; border:none; padding:7px 16px; border-radius:6px; font-size:12.5px; font-weight:600; cursor:pointer;">Kirim</button>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <?= htmlspecialchars($footer_text ?? '&copy; ' . date('Y') . ' ' . $site_name . '. All rights reserved.') ?>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let allProducts = [];

        $(document).ready(function() {
            loadCatalog();

            $('#search-input').on('input', function() {
                const q = $(this).val().toLowerCase();
                const filtered = allProducts.filter(p => p.title.toLowerCase().includes(q));
                renderProducts(filtered);
            });

            $(document).on('click', '.btn-detail, .product-title', function() {
                const id = $(this).closest('.product-card').data('id');
                openProductDetail(id);
            });

            $('#btn-close-modal').on('click', function() {
                $('#product-modal').removeClass('show');
            });

            $(document).on('click', function(e) {
                if ($(e.target).is('#product-modal')) $('#product-modal').removeClass('show');
            });

            $('#btn-send-comment').on('click', function() {
                const postId = $('#comment-post-id').val();
                const name = $('#comment-name').val().trim();
                const text = $('#comment-text').val().trim();
                if (!text) {
                    $('#comment-status').css('color', '#EF4444').text('Isi ulasan wajib diisi!');
                    return;
                }

                $('#btn-send-comment').prop('disabled', true).text('Mengirim...');
                $.ajax({
                    url: '<?= base_url("api/comments/add") ?>',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ post_id: parseInt(postId), author_name: name || 'Pelanggan', content: text }),
                    success: function() {
                        $('#comment-status').css('color', '#10B981').text('Ulasan terkirim, menunggu moderasi penjual.');
                        $('#comment-text').val('');
                        $('#btn-send-comment').prop('disabled', false).text('Kirim');
                    },
                    error: function() {
                        $('#comment-status').css('color', '#EF4444').text('Gagal mengirim ulasan.');
                        $('#btn-send-comment').prop('disabled', false).text('Kirim');
                    }
                });
            });
        });

        function loadCatalog() {
            $.ajax({
                url: '<?= base_url("api/public/posts") ?>',
                method: 'GET',
                success: function(res) {
                    allProducts = res.data || [];
                    renderProducts(allProducts);
                },
                error: function() {
                    $('#catalog-container').html('<div style="grid-column:1/-1; text-align:center; color:#EF4444;">Gagal memuat katalog produk.</div>');
                }
            });
        }

        function renderProducts(items) {
            $('#product-count').text(items.length ? `${items.length} Produk Tersedia` : '');
            if (!items.length) {
                $('#catalog-container').html('<div style="grid-column:1/-1; text-align:center; padding:40px; color:var(--muted);">Belum ada produk yang dipajang di katalog ini.</div>');
                return;
            }

            let html = '';
            items.forEach(function(item) {
                const waText = encodeURIComponent(`Halo, saya tertarik memesan produk: "${item.title}". Apakah stok masih tersedia?`);
                const waUrl = `https://wa.me/?text=${waText}`;

                html += `
                    <div class="product-card" data-id="${item.id}">
                        <div class="product-thumb">
                            <span class="badge-cat">${item.category_name || 'Katalog'}</span>
                            <div class="no-img"><i class="fas fa-box-open"></i></div>
                        </div>
                        <div class="product-body">
                            <h3 class="product-title">${item.title}</h3>
                            <p class="product-desc">${item.excerpt || item.content || 'Barang siap dipesan dengan kualitas terbaik.'}</p>
                            <div class="product-footer">
                                <a href="${waUrl}" target="_blank" class="btn-wa">
                                    <i class="fab fa-whatsapp"></i> Pesan via WA
                                </a>
                                <button type="button" class="btn-detail">Lihat Rincian</button>
                            </div>
                        </div>
                    </div>
                `;
            });
            $('#catalog-container').html(html);
        }

        function openProductDetail(id) {
            const p = allProducts.find(x => x.id == id);
            if (!p) return;

            $('#modal-title').text(p.title);
            $('#modal-cat').text(p.category_name || 'PRODUK');
            $('#modal-desc').html(p.content || p.excerpt || 'Tidak ada deskripsi rinci.');
            $('#comment-post-id').val(p.id);
            $('#comment-status').text('');

            const waText = encodeURIComponent(`Halo, saya ingin pesan produk "${p.title}".`);
            $('#modal-btn-wa').attr('href', `https://wa.me/?text=${waText}`);

            // Ambil ulasan
            $('#modal-comments').html('<small style="color:var(--muted)">Memuat ulasan...</small>');
            $.ajax({
                url: `<?= base_url("api/comments/post") ?>/${p.id}`,
                method: 'GET',
                success: function(res) {
                    const cList = res.data || [];
                    $('#modal-comment-count').text(cList.length);
                    if (!cList.length) {
                        $('#modal-comments').html('<small style="color:var(--muted)">Belum ada ulasan untuk produk ini.</small>');
                        return;
                    }
                    let cHtml = '';
                    cList.forEach(c => {
                        cHtml += `
                            <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:8px; padding:8px 12px; font-size:13px;">
                                <strong>${c.author_name || 'Pembeli'}</strong>
                                <p style="margin:2px 0 0; color:var(--dark);">${c.content}</p>
                            </div>
                        `;
                    });
                    $('#modal-comments').html(cHtml);
                }
            });

            $('#product-modal').addClass('show');
        }
    </script>
</body>
</html>

