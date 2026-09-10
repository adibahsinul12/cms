<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class User extends Base_api {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
    }

    // [GET] /api/user - Ambil semua user
    public function index() {
        $users = $this->User_model->get_all_users();
        $this->response([
            'status' => 'success',
            'data'   => $users
        ], 200);
    }

    // [GET] /api/user/detail/{id} - Detail user
    public function detail($id = NULL) {
        if (!$id) {
            $this->response_error('ID User wajib diisi!', 400);
            return;
        }

        $user = $this->User_model->get_user_by_id($id);
        if (!$user) {
            $this->response_error('User tidak ditemukan!', 404);
            return;
        }

        $this->response([
            'status' => 'success',
            'data'   => $user
        ], 200);
    }

    // [POST] /api/user/create - Tambah user baru
    public function create() {
        $raw_input = file_get_contents('php://input');
        $input_data = json_decode($raw_input, TRUE) ?: $this->input->post();

        if (empty($input_data['username']) || empty($input_data['email']) || empty($input_data['password'])) {
            $this->response_error('Username, Email, dan Password wajib diisi!', 400);
            return;
        }

        $data = [
            'username'  => $input_data['username'],
            'email'     => $input_data['email'],
            'password'  => $input_data['password'],
            'full_name' => isset($input_data['full_name']) ? $input_data['full_name'] : '',
            'role_id'   => isset($input_data['role_id']) ? $input_data['role_id'] : 2 // Default role 2 (misal Editor)
        ];

        $user_id = $this->User_model->create_user($data);
        if ($user_id) {
            $this->response_success(['user_id' => $user_id], 'User berhasil dibuat!', 201);
        } else {
            $this->response_error('Gagal membuat user!', 500);
        }
    }

    // [PUT/POST] /api/user/update/{id} - Update user
    public function update($id = NULL) {
        if (!$id) {
            $this->response_error('ID User wajib diisi!', 400);
            return;
        }

        $raw_input = file_get_contents('php://input');
        $input_data = json_decode($raw_input, TRUE) ?: $this->input->post();

        $data = [];
        if (isset($input_data['username']))  $data['username']  = $input_data['username'];
        if (isset($input_data['email']))     $data['email']     = $input_data['email'];
        if (isset($input_data['password']))  $data['password']  = $input_data['password'];
        if (isset($input_data['full_name'])) $data['full_name'] = $input_data['full_name'];
        if (isset($input_data['role_id']))   $data['role_id']   = $input_data['role_id'];

        $result = $this->User_model->update_user($id, $data);
        if ($result) {
            $this->response_success(null, 'User berhasil diperbarui!', 200);
        } else {
            $this->response_error('Gagal memperbarui user!', 500);
        }
    }

    // [DELETE] /api/user/delete/{id} - Hapus user
    public function delete($id = NULL) {
        if (!$id) {
            $this->response_error('ID User wajib diisi!', 400);
            return;
        }

        $result = $this->User_model->delete_user($id);
        if ($result) {
            $this->response_success(null, 'User berhasil dihapus!', 200);
        } else {
            $this->response_error('Gagal menghapus user!', 500);
        }
    }

    // [GET] /api/user/roles - Ambil daftar role
    public function roles() {
        $roles = $this->User_model->get_all_roles();
        $this->response([
            'status' => 'success',
            'data'   => $roles
        ], 200);
    }
}