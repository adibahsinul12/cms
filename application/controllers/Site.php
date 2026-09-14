<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Site Controller
 * Menampilkan website pribadi per-user di URL: /u/{username}
 * Contoh: http://localhost/cms/u/budi → Website milik Budi
 *         http://localhost/cms/u/toko-berkah → Toko Online milik user toko-berkah
 */
class Site extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('User_model');
        $this->load->model('Post_model');
    }

    /**
     * [GET] /u/{username}
     * Render halaman web pribadi milik user dengan username tersebut
     */
    public function view($username = null) {
        if (!$username) {
            show_404();
            return;
        }

        // Cari user berdasarkan username persis (bukan email)
        $owner = $this->User_model->get_by_username_exact($username);
        if (!$owner || $owner['status'] !== 'active') {
            show_404();
            return;
        }

        // Ambil semua postingan published milik user ini saja
        $posts = $this->Post_model->get_posts(20, 0, 'published', $owner['id']);

        // Data visitor (user yang sedang mengunjungi)
        $is_logged_in   = (bool) $this->session->userdata('logged_in');
        $visitor_role   = $this->session->userdata('role_id');
        $visitor_name   = $this->session->userdata('full_name') ?: $this->session->userdata('username');
        $visitor_id     = $this->session->userdata('user_id');
        $staff_roles    = [1, 3, 4];

        // Data site dari profil owner
        $site_name        = !empty($owner['site_title'])  ? $owner['site_title']  : $owner['full_name'] . "'s Site";
        $site_description = !empty($owner['site_bio'])    ? $owner['site_bio']    : 'Website milik ' . $owner['full_name'];
        $footer_text      = '© ' . date('Y') . ' ' . $site_name . '. All rights reserved.';
        $active_theme     = !empty($owner['site_theme'])  ? $owner['site_theme']  : 'dinas';
        $phone_wa         = $owner['phone_wa'] ?? '';

        $data = [
            // Info owner (pemilik web)
            'owner'           => $owner,
            'owner_username'  => $owner['username'],
            'phone_wa'        => $phone_wa,

            // Info visitor
            'is_logged_in'    => $is_logged_in,
            'current_role'    => $visitor_role,
            'current_name'    => $visitor_name,
            'current_user_id' => $visitor_id,
            'staff_roles'     => $staff_roles,

            // Site settings dari profil owner
            'site_name'        => $site_name,
            'site_description' => $site_description,
            'footer_text'      => $footer_text,
            'active_theme'     => $active_theme,

            // Postingan milik owner
            'posts'            => $posts,
        ];

        // Loader Template (sama persis dengan Home_view):
        // 1. Cek di views/themes/{active_theme}/home.php
        // 2. Cek di views/templates/{active_theme}/home.php
        // 3. Fallback ke views/home.php (Aman 100%)
        if (!empty($active_theme) && !in_array($active_theme, ['default', 'dinas'], true)) {
            if (file_exists(APPPATH . "views/themes/{$active_theme}/home.php")) {
                $this->load->view("themes/{$active_theme}/home", $data);
                return;
            } elseif (file_exists(APPPATH . "views/templates/{$active_theme}/home.php")) {
                $this->load->view("templates/{$active_theme}/home", $data);
                return;
            }
        }
        $this->load->view('home', $data);
    }
}

