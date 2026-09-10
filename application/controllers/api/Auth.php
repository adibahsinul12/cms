<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class Auth extends Base_api {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
    }

    // [POST] /api/auth/login - Proses Login
    public function login() {
        $raw_input = file_get_contents('php://input');
        $input_data = json_decode($raw_input, TRUE) ?: $this->input->post();

        $username = isset($input_data['username']) ? trim($input_data['username']) : '';
        $password = isset($input_data['password']) ? trim($input_data['password']) : '';

        if (empty($username) || empty($password)) {
            $this->response_error('Username dan Password wajib diisi!', 400);
            return;
        }

        // Cari user berdasarkan username/email di DB
        $this->db->where('username', $username);
        $this->db->or_where('email', $username);
        $user = $this->db->get('users')->row_array();

        if (!$user) {
            $this->response_error('Username atau Email tidak ditemukan!', 404);
            return;
        }

        // Verifikasi Password Hash
        if (!password_verify($password, $user['password'])) {
            $this->response_error('Password yang kamu masukkan salah!', 401);
            return;
        }

        // Set Session login CI3
        $session_data = [
            'user_id'   => $user['id'],
            'username'  => $user['username'],
            'full_name' => $user['full_name'],
            'role_id'   => 2,
            'logged_in' => TRUE
        ];
        $this->session->set_userdata($session_data);

        // Abaikan password dari response JSON demi keamanan
        unset($user['password']);

        $this->response_success([
            'user' => $user
        ], 'Login berhasil! Selamat datang.');
    }

    // [POST] /api/auth/register - Pendaftaran Akun Baru
public function register() {
    $raw_input = file_get_contents('php://input');
    $input_data = json_decode($raw_input, TRUE) ?: $this->input->post();

    if (empty($input_data['username']) || empty($input_data['email']) || empty($input_data['password'])) {
        $this->response_error('Username, Email, dan Password wajib diisi!', 400);
        return;
    }

    // Cek duplikasi username / email
    $this->db->where('username', $input_data['username']);
    $this->db->or_where('email', $input_data['email']);
    $check = $this->db->get('users')->num_rows();

    if ($check > 0) {
        $this->response_error('Username atau Email sudah terdaftar!', 400);
        return;
    }

    $data = [
        'role_id'   => 2, // Pastikan ID 2 sudah ada di tabel 'roles'
        'username'  => $input_data['username'],
        'email'     => $input_data['email'],
        'password'  => password_hash($input_data['password'], PASSWORD_BCRYPT),
        'full_name' => !empty($input_data['full_name']) ? $input_data['full_name'] : $input_data['username'],
        'status'    => 'active'
    ];

    $insert = $this->db->insert('users', $data);

    if ($insert) {
        $user_id = $this->db->insert_id();
        $this->response_success(['user_id' => $user_id], 'Registrasi berhasil! Silakan login.', 201);
    } else {
        $db_error = $this->db->error();
        $this->response_error('Database Error: ' . $db_error['message'], 500);
    }
}

    // [POST/GET] /api/auth/logout - Logout Session
    public function logout() {
        $this->session->sess_destroy();
        $this->response_success(null, 'Berhasil logout!');
    }
}