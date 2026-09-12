<?php
// application/views/admin/partials/sidebar.php
//
// Partial sidebar bersama untuk semua halaman admin.
// Setiap controller admin WAJIB mengirim variabel $active_menu ke view,
// dengan salah satu nilai berikut: 'dashboard', 'posts', 'categories', 'tags',
// 'media', 'users', 'settings', 'seo', 'comments', 'logs'.
// Kalau $active_menu tidak dikirim, tidak ada menu yang di-highlight (tapi tetap aman, tidak error).

if (!isset($active_menu)) {
    $active_menu = '';
}

$menu_items = [
    'dashboard'  => ['label' => 'Dashboard',  'icon' => 'fa-home',        'url' => 'admin/dashboard'],
    'posts'      => ['label' => 'Posts',      'icon' => 'fa-file-alt',    'url' => 'admin/posts'],
    'categories' => ['label' => 'Categories', 'icon' => 'fa-tags',        'url' => 'admin/categories'],
    'tags'       => ['label' => 'Tags',       'icon' => 'fa-tag',         'url' => 'admin/tags'],
    'media'      => ['label' => 'Media',      'icon' => 'fa-images',     'url' => 'admin/media'],
    'comments'   => ['label' => 'Comments',   'icon' => 'fa-comments',    'url' => 'admin/comments'],
    'users'      => ['label' => 'Users',      'icon' => 'fa-users',       'url' => 'admin/users'],
    'seo'        => ['label' => 'SEO',        'icon' => 'fa-search',      'url' => 'admin/seo'],
    'settings'   => ['label' => 'Settings',   'icon' => 'fa-cog',         'url' => 'admin/settings'],
    'logs'       => ['label' => 'Audit Log',  'icon' => 'fa-history',     'url' => 'admin/logs'],
];

// === AMBIL TEMA AKTIF DARI DATABASE ===
// Pakai get_instance() karena di dalam view/partial, $this bukan controller.
$CI =& get_instance();
$CI->load->helper('permissions');
$CI->load->library('session');

// Filter menu sesuai permission role yang sedang login.
// Kalau role tidak terdaftar / belum login, tampilkan menu apa adanya (fallback aman).
$current_role_id = $CI->session->userdata('role_id');
if ($current_role_id) {
    $menu_items = array_filter($menu_items, function ($key) use ($current_role_id) {
        return role_can($current_role_id, $key);
    }, ARRAY_FILTER_USE_KEY);
}

$CI->load->model('Option_model');
$all_options  = $CI->Option_model->get_all();
$active_theme = (is_array($all_options) && !empty($all_options['active_theme']))
    ? $all_options['active_theme']
    : 'theme-1'; // fallback default kalau belum pernah diset

// === PETA WARNA TIAP TEMA (samain sama gradient di halaman Settings > Tema) ===
$theme_colors = [
    'theme-1' => ['from' => '#667eea', 'to' => '#764ba2'], // Purple
    'theme-2' => ['from' => '#11998e', 'to' => '#38ef7d'], // Green
    'theme-3' => ['from' => '#f12711', 'to' => '#f5af19'], // Orange
    'theme-4' => ['from' => '#232526', 'to' => '#414345'], // Dark
    'theme-5' => ['from' => '#2193b0', 'to' => '#6dd5ed'], // Blue
    'theme-6' => ['from' => '#ee9ca7', 'to' => '#ffdde1'], // Pink
];

$colors = $theme_colors[$active_theme] ?? $theme_colors['theme-1'];
?>
<style>
    :root {
        --sidebar-from: <?= $colors['from'] ?>;
        --sidebar-to: <?= $colors['to'] ?>;
    }
    .sidebar-themed {
        background: linear-gradient(180deg, var(--sidebar-from), var(--sidebar-to));
        min-height: 100vh;
    }
    .sidebar-themed .nav-link {
        color: rgba(255, 255, 255, 0.85);
        transition: all 0.2s;
    }
    .sidebar-themed .nav-link:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 6px;
    }
    .sidebar-themed .nav-link.active {
        color: #fff;
        background: rgba(255, 255, 255, 0.25);
        border-radius: 6px;
        font-weight: 600;
    }
    .sidebar-themed .logout-section {
        margin-top: 20px;
        padding-top: 12px;
        border-top: 1px solid rgba(255, 255, 255, 0.15);
    }
    .sidebar-themed .logout-link {
        color: rgba(255, 255, 255, 0.85);
        background: none;
        border: none;
        width: 100%;
        text-align: left;
        padding: 8px 16px;
        font-size: 1rem;
        cursor: pointer;
        display: block;
        border-radius: 6px;
        transition: all 0.2s;
    }
    .sidebar-themed .logout-link:hover {
        color: #fff;
        background: rgba(220, 53, 69, 0.35);
    }
</style>

<nav class="col-md-2 d-md-block sidebar-themed sidebar">
    <div class="position-sticky pt-3">
        <h5 class="text-white text-center py-3">📝 CMS Admin</h5>
        <ul class="nav flex-column">
            <?php foreach ($menu_items as $key => $item): ?>
                <li class="nav-item">
                    <a class="nav-link<?= ($active_menu === $key) ? ' active' : '' ?>"
                       href="<?= base_url($item['url']) ?>">
                        <i class="fas <?= $item['icon'] ?>"></i> <?= $item['label'] ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="logout-section px-2">
            <button type="button" class="logout-link" id="btn-sidebar-logout">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </div>
    </div>
</nav>

<script>
    document.getElementById('btn-sidebar-logout').addEventListener('click', function () {
        if (!confirm('Yakin mau logout?')) return;

        fetch('<?= base_url("api/auth/logout") ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        })
        .then(function () {
            window.location.href = '<?= base_url("login") ?>';
        })
        .catch(function () {
            // Tetap redirect ke login walau request gagal,
            // supaya user tidak stuck di halaman admin.
            window.location.href = '<?= base_url("login") ?>';
        });
    });
</script>