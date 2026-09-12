<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    // Mapping nama method -> menu key (dipakai untuk cek permission)
    private $method_menu_map = [
        'dashboard'   => 'dashboard',
        'posts'       => 'posts',
        'create_post' => 'posts',
        'edit_post'   => 'posts',
        'categories'  => 'categories',
        'tags'        => 'tags',
        'media'       => 'media',
        'users'       => 'users',
        'settings'    => 'settings',
        'comments'    => 'comments',
        'logs'        => 'logs',
        'seo'         => 'seo',
    ];

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('permissions');
        $this->load->library('session');

        // 1. Cek apakah user sudah login
        if (!$this->session->userdata('logged_in')) {
            redirect('login'); // sesuaikan dengan route halaman login kamu
            exit;
        }

        $role_id = $this->session->userdata('role_id');

        // 2. Role User biasa (2) sama sekali tidak boleh masuk area admin
        if (!in_array($role_id, [1, 3, 4])) {
            show_error('Akses Ditolak: Anda tidak memiliki izin mengakses Halaman Admin.', 403, 'Forbidden');
            exit;
        }

        // 3. Cek permission spesifik per-halaman (menu) sesuai role
        $current_method = $this->router->fetch_method();
        $menu_key = $this->method_menu_map[$current_method] ?? null;

        if ($menu_key && !role_can($role_id, $menu_key)) {
            show_error('Akses Ditolak: Role Anda tidak memiliki izin untuk mengakses halaman ini.', 403, 'Forbidden');
            exit;
        }
    }

    public function dashboard() { $this->load->view('admin/dashboard'); }
    public function posts() { $this->load->view('admin/posts/index'); }
    public function create_post() { $this->load->view('admin/posts/create'); }
    public function edit_post($id) { $data['post_id'] = $id; $this->load->view('admin/posts/edit', $data); }
    public function categories() { $this->load->view('admin/categories/index'); }
    public function tags() { $this->load->view('admin/tags/index'); }
    public function media() { $this->load->view('admin/media/index'); }
    public function users() { $this->load->view('admin/users/index'); }
    public function settings() { $this->load->view('admin/settings/index'); }
    public function comments() { $this->load->view('admin/comments/index'); }
    public function logs() { $this->load->view('admin/logs/index'); }
    public function seo() { $this->load->view('admin/seo/index'); }
}