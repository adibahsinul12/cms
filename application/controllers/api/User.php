<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class User extends Base_api {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
    }

    // [GET] /api/user
    public function index() {
        $this->response_success($this->User_model->get_all(), 'OK', 200);
    }

    // [GET] /api/user/roles
    public function roles() {
        $this->response_success($this->User_model->get_roles(), 'OK', 200);
    }

    // [GET] /api/user/detail/(:num)
    public function detail($id) {
        $user = $this->User_model->get_by_id($id);
        if (!$user) {
            $this->response_error('User not found', 404);
            return;
        }
        $this->response_success($user, 'OK', 200);
    }

    // [POST] /api/user/create
    public function create() {
        $raw_input = file_get_contents('php://input');
        $input_data = json_decode($raw_input, TRUE) ?: $this->input->post();

        if (empty($input_data['username']) || empty($input_data['email']) || empty($input_data['password']) || empty($input_data['full_name']) || empty($input_data['role_id'])) {
            $this->response_error('Username, Email, Password, Full Name, dan Role wajib diisi!', 400);
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

        $this->db->where('username', $input_data['username']);
        $this->db->or_where('email', $input_data['email']);
        $check = $this->db->get('users')->num_rows();
        if ($check > 0) {
            $this->response_error('Username atau Email sudah terdaftar!', 400);
            return;
        }

        $data = [
            'role_id'   => $input_data['role_id'],
            'username'  => $input_data['username'],
            'email'     => $input_data['email'],
            'password'  => password_hash($input_data['password'], PASSWORD_BCRYPT),
            'full_name' => $input_data['full_name'],
            'status'    => $input_data['status'] ?? 'active'
        ];

        $id = $this->User_model->create($data);
        $this->response_success(['id' => $id], 'User created', 201);
    }

    // [PUT] /api/user/update/(:num)
    public function update($id) {
        $existing = $this->User_model->get_by_id($id);
        if (!$existing) {
            $this->response_error('User not found', 404);
            return;
        }

        $raw_input = file_get_contents('php://input');
        $input_data = json_decode($raw_input, TRUE) ?: [];

        $data = [
            'role_id'   => $input_data['role_id'] ?? $existing['role_id'],
            'username'  => $input_data['username'] ?? $existing['username'],
            'email'     => $input_data['email'] ?? $existing['email'],
            'full_name' => $input_data['full_name'] ?? $existing['full_name'],
            'status'    => $input_data['status'] ?? $existing['status']
        ];

        if (!empty($input_data['password'])) {
            $data['password'] = password_hash($input_data['password'], PASSWORD_BCRYPT);
        }

        $this->User_model->update($id, $data);
        $this->response_success(null, 'User updated', 200);
    }

    // [DELETE] /api/user/delete/(:num)
    public function delete($id) {
        $existing = $this->User_model->get_by_id($id);
        if (!$existing) {
            $this->response_error('User not found', 404);
            return;
        }

        $this->User_model->delete($id);
        $this->response_success(null, 'User deleted', 200);
    }

    // ==============================================
    // FITUR: PENGAJUAN UPGRADE ROLE (User -> Staff)
    // ==============================================

    // [POST] /api/user/request-role
    // Dipanggil oleh user yang SEDANG LOGIN (role User biasa) untuk mengajukan
    // jadi Editor atau Author. role_id diambil dari SESSION, bukan dari input,
    // supaya tidak bisa dimanipulasi orang lain.
    public function request_role() {
        if (!$this->session->userdata('logged_in')) {
            $this->response_error('Anda harus login terlebih dahulu!', 401);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $raw_input = file_get_contents('php://input');
        $input_data = json_decode($raw_input, TRUE) ?: $this->input->post();

        $requested_role_id = (int) ($input_data['requested_role_id'] ?? 0);

        // Hanya boleh mengajukan jadi Editor (3) atau Author (4).
        // Tidak boleh mengajukan jadi Admin (1) lewat jalur ini.
        if (!in_array($requested_role_id, [3, 4], true)) {
            $this->response_error('Role yang diajukan tidak valid!', 400);
            return;
        }

        $user = $this->User_model->get_by_id($user_id);
        if (!$user) {
            $this->response_error('User tidak ditemukan!', 404);
            return;
        }

        if ((int) $user['role_id'] !== 2) {
            $this->response_error('Hanya User biasa yang bisa mengajukan upgrade role!', 400);
            return;
        }

        if ($user['request_status'] === 'pending') {
            $this->response_error('Anda masih memiliki pengajuan yang sedang diproses!', 400);
            return;
        }

        $this->db->where('id', $user_id)->update('users', [
            'requested_role_id' => $requested_role_id,
            'request_status'    => 'pending'
        ]);

        $this->response_success(null, 'Pengajuan berhasil dikirim, tunggu persetujuan Admin.', 200);
    }

    // [GET] /api/user/pending-requests
    // Daftar user yang sedang mengajukan upgrade role (untuk Admin)
    public function pending_requests() {
        $this->db->where('request_status', 'pending');
        $pending = $this->db->get('users')->result_array();

        // Buang password dari hasil, tidak perlu dikirim ke frontend
        foreach ($pending as &$row) {
            unset($row['password']);
        }

        $this->response_success($pending, 'OK', 200);
    }

    // [POST] /api/user/approve-request/(:num)
    // $id = id user yang pengajuannya di-ACC
    public function approve_request($id) {
        $user = $this->User_model->get_by_id($id);
        if (!$user) {
            $this->response_error('User not found', 404);
            return;
        }
        if ($user['request_status'] !== 'pending' || empty($user['requested_role_id'])) {
            $this->response_error('User ini tidak memiliki pengajuan yang pending!', 400);
            return;
        }

        $this->db->where('id', $id)->update('users', [
            'role_id'           => $user['requested_role_id'],
            'requested_role_id' => null,
            'request_status'    => 'none'
        ]);

        $this->response_success(null, 'Pengajuan disetujui, role user berhasil diupdate.', 200);
    }

    // [POST] /api/user/reject-request/(:num)
    public function reject_request($id) {
        $user = $this->User_model->get_by_id($id);
        if (!$user) {
            $this->response_error('User not found', 404);
            return;
        }
        if ($user['request_status'] !== 'pending') {
            $this->response_error('User ini tidak memiliki pengajuan yang pending!', 400);
            return;
        }

        $this->db->where('id', $id)->update('users', [
            'requested_role_id' => null,
            'request_status'    => 'none'
        ]);

        $this->response_success(null, 'Pengajuan ditolak.', 200);
    }
}