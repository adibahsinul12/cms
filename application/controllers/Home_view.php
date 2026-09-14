<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_view extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Option_model');
    }

    public function index() {
        // Ambil session user
        $is_logged_in    = (bool) $this->session->userdata('logged_in');
        $current_role    = $this->session->userdata('role_id');
        $current_name    = $this->session->userdata('full_name') ?: $this->session->userdata('username');
        $current_user_id = $this->session->userdata('user_id');

        // Ambil pengaturan situs dari database
        $options = $this->Option_model->get_all();

        $site_name = !empty($options['site_name']) ? $options['site_name'] : 'Portal Resmi Daerah';
        $site_description = !empty($options['site_description']) ? $options['site_description'] : 'Berita dan pengumuman resmi daerah, satu tempat terpercaya.';
        $footer_text = !empty($options['footer_text']) ? $options['footer_text'] : '© ' . date('Y') . ' ' . $site_name . '. Seluruh hak cipta dilindungi.';
        $active_theme = !empty($options['active_theme']) ? $options['active_theme'] : 'default';

        $data = [
            'is_logged_in'     => $is_logged_in,
            'current_role'     => $current_role,
            'current_name'     => $current_name,
            'current_user_id'  => $current_user_id,
            'staff_roles'      => [1, 3, 4], // Admin, Editor, Author
            'site_name'        => $site_name,
            'site_description' => $site_description,
            'footer_text'      => $footer_text,
            'active_theme'     => $active_theme,
        ];

        // Loader Template Modular (Mirip WordPress Theme Switcher):
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