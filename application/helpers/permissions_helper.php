<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * permissions_helper.php
 *
 * Aturan akses menu admin berdasarkan role_id.
 * role_id: 1 = Admin, 2 = User (tidak boleh masuk admin sama sekali), 3 = Editor, 4 = Author
 *
 * Ubah array di bawah ini kalau mau menyesuaikan aturan akses.
 */

if (!function_exists('get_role_permissions')) {
    function get_role_permissions($role_id) {
        $permissions = [
            // Admin: akses semua menu
            1 => ['dashboard', 'posts', 'categories', 'tags', 'media', 'comments', 'users', 'seo', 'settings', 'logs'],

            // Editor: kelola semua konten, tapi tidak bisa kelola user/settings/logs
            3 => ['dashboard', 'posts', 'categories', 'tags', 'media', 'comments', 'seo'],

            // Author: cuma kelola post & media, tidak bisa kelola taksonomi/komentar/dll
            4 => ['dashboard', 'posts', 'media'],
        ];

        return $permissions[$role_id] ?? [];
    }
}

if (!function_exists('role_can')) {
    function role_can($role_id, $menu_key) {
        return in_array($menu_key, get_role_permissions($role_id), true);
    }
}