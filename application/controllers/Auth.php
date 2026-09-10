<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
    }

    /**
     * Halaman Login
     */
    public function login() {
        // Kalau udah login, redirect ke dashboard
        if ($this->session->userdata('user_id')) {
            redirect('admin/dashboard');
        }
        $this->load->view('admin/auth/login');
    }

    /**
     * Halaman Register
     */
    public function register() {
        if ($this->session->userdata('user_id')) {
            redirect('admin/dashboard');
        }
        $this->load->view('admin/auth/register');
    }

    /**
     * Proses Logout
     */
    public function logout() {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}