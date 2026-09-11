<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');

        // 1. Cek apakah user sudah login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth_view/login'); // sesuaikan dengan route halaman login kamu
            exit;
        }

        // 2. Cek role: hanya Admin (1) dan Editor (3) yang boleh akses dashboard
        $role_id = $this->session->userdata('role_id');
        if (!in_array($role_id, [1, 3])) {
            show_error('Akses Ditolak: Anda tidak memiliki izin mengakses Halaman Admin.', 403, 'Forbidden');
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