<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class Auth extends Base_api {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
    }

    public function register() {
        $raw_input = file_get_contents('php://input');
        $input_data = json_decode($raw_input, TRUE) ?: $this->input->post();

        if (empty($input_data['username']) || empty($input_data['email']) || empty($input_data['password']) || empty($input_data['full_name'])) {
            $this->response_error('Username, Email, Password, dan Nama Lengkap wajib diisi!', 400);
            return;
        }
        if (!filter_var($input_data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->response_error('Format email tidak valid!', 400);
            return;
        }
        if (strlen($input_data['password']) < 6) {
            $this->response_error('Password minimal 6 karakter!', 400);
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

        $sql = "CALL sp_register_user(?, ?, ?, ?, ?)";
        $query = $this->db->query($sql, [
            4, // UNIVERSAL: role_id 4 (Author) untuk registrasi publik.
               // Siapapun yang mendaftar langsung bisa membuat konten & postingan sendiri di dashboard.
               // Nilai ini tetap di-hardcode di server (bukan dari input) agar tidak bisa disalahgunakan jadi Admin (role 1).
            $input_data['username'],
            $input_data['email'],
            password_hash($input_data['password'], PASSWORD_BCRYPT),
            $input_data['full_name']
        ]);

        $result = $query ? $query->row_array() : [];
        if ($query) {
            $query->free_result();
        }
        if ($this->db->conn_id && mysqli_more_results($this->db->conn_id)) {
            mysqli_next_result($this->db->conn_id);
        }

        if ($result && isset($result['user_id'])) {
            $this->response_success(['user_id' => $result['user_id']], 'Registrasi berhasil!', 201);
        } else {
            $this->response_error('Registrasi gagal!', 500);
        }
    }

    public function login() {
        $raw_input = file_get_contents('php://input');
        $input_data = json_decode($raw_input, TRUE) ?: $this->input->post();

        $username = isset($input_data['username']) ? trim($input_data['username']) : '';
        $password = isset($input_data['password']) ? trim($input_data['password']) : '';

        if (empty($username) || empty($password)) {
            $this->response_error('Username dan Password wajib diisi!', 400);
            return;
        }

        $user = $this->User_model->get_by_username($username);

        if (!$user) {
            $this->response_error('Username atau password salah!', 401);
            return;
        }

        $password_valid = password_verify($password, $user['password']);

        // Kompatibilitas: jika akun lama masih plain text, verifikasi dan otomatis upgrade ke bcrypt
        if (!$password_valid && $password === $user['password']) {
            $password_valid = true;
            $this->User_model->update($user['id'], [
                'password' => password_hash($password, PASSWORD_BCRYPT)
            ]);
        }

        if (!$password_valid) {
            $this->response_error('Username atau password salah!', 401);
            return;
        }

        if ($user['status'] !== 'active') {
            $this->response_error('Akun tidak aktif!', 403);
            return;
        }

        $this->session->set_userdata([
            'user_id'   => $user['id'],
            'username'  => $user['username'],
            'full_name' => $user['full_name'],
            'role_id'   => $user['role_id'],
            'logged_in' => TRUE
        ]);

        $this->response_success([
            'user_id'   => $user['id'],
            'username'  => $user['username'],
            'full_name' => $user['full_name'],
            'role_id'   => $user['role_id']
        ], 'Login berhasil!', 200);
    }

    public function logout() {
        $this->session->sess_destroy();
        $this->response_success(null, 'Logout berhasil', 200);
    }
}