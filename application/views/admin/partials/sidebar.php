<?php
// application/views/admin/partials/sidebar.php
//
// Partial sidebar bersama untuk semua halaman admin.
// Setiap controller admin WAJIB mengirim variabel $active_menu ke view,
// dengan salah satu nilai berikut: 'dashboard', 'posts', 'categories', 'tags', 'media'.
// Kalau $active_menu tidak dikirim, tidak ada menu yang di-highlight (tapi tetap aman, tidak error).

if (!isset($active_menu)) {
    $active_menu = '';
}

$menu_items = [
    'dashboard'  => ['label' => 'Dashboard',  'icon' => 'fa-home',     'url' => 'admin/dashboard'],
    'posts'      => ['label' => 'Posts',      'icon' => 'fa-file-alt', 'url' => 'admin/posts'],
    'categories' => ['label' => 'Categories', 'icon' => 'fa-tags',     'url' => 'admin/categories'],
    'tags'       => ['label' => 'Tags',       'icon' => 'fa-tag',      'url' => 'admin/tags'],
    'media'      => ['label' => 'Media',      'icon' => 'fa-images',   'url' => 'admin/media'],
];
?>
<nav class="col-md-2 d-md-block bg-dark sidebar" style="min-height:100vh;">
    <div class="position-sticky pt-3">
        <h5 class="text-white text-center py-3">📝 CMS Admin</h5>
        <ul class="nav flex-column">
            <?php foreach ($menu_items as $key => $item): ?>
                <li class="nav-item">
                    <a class="nav-link text-white<?= ($active_menu === $key) ? ' active' : '' ?>"
                       href="<?= base_url($item['url']) ?>">
                        <i class="fas <?= $item['icon'] ?>"></i> <?= $item['label'] ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</nav>