<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class Auth extends Base_api {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
    }
    
    public function register_post() {
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('full_name', 'Full Name', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->response(['status' => 'error', 'errors' => $this->form_validation->error_array()], 400);
            return;
        }
        
        $sql = "CALL sp_register_user(?, ?, ?, ?, ?)";
        $query = $this->db->query($sql, [
            $this->post('role_id') ?: 4,
            $this->post('username'),
            $this->post('email'),
            password_hash($this->post('password'), PASSWORD_BCRYPT),
            $this->post('full_name')
        ]);
        
        $result = $query->row_array();
        $query->next_result();
        $query->free_result();
        
        if ($result && isset($result['user_id'])) {
            $this->response(['status' => 'success', 'message' => 'Registrasi berhasil!', 'data' => ['user_id' => $result['user_id']]], 201);
        } else {
            $this->response(['status' => 'error', 'message' => 'Registrasi gagal!'], 500);
        }
    }
    
    public function login_post() {
        $username = $this->post('username');
        $password = $this->post('password');
        
        $user = $this->User_model->get_by_username($username);
        
        if (!$user || !password_verify($password, $user['password'])) {
            $this->response(['status' => 'error', 'message' => 'Username atau password salah!'], 401);
            return;
        }
        
        if ($user['status'] !== 'active') {
            $this->response(['status' => 'error', 'message' => 'Akun tidak aktif!'], 403);
            return;
        }
        
        $this->session->set_userdata([
            'user_id' => $user['id'],
            'username' => $user['username'],
            'full_name' => $user['full_name'],
            'role_id' => $user['role_id'],
            'logged_in' => TRUE
        ]);
        
        $this->response([
            'status' => 'success',
            'message' => 'Login berhasil!',
            'data' => [
                'user_id' => $user['id'],
                'username' => $user['username'],
                'full_name' => $user['full_name'],
                'role_id' => $user['role_id']
            ]
        ], 200);
    }
    
    public function logout_get() {
        $this->session->sess_destroy();
        $this->response(['status' => 'success', 'message' => 'Logout berhasil'], 200);
    }
}