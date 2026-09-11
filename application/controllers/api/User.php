<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class User extends Base_api {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
    }
    
    public function index_get() {
        $this->response(['status' => 'success', 'data' => $this->User_model->get_all()], 200);
    }
    
    public function roles_get() {
        $this->response(['status' => 'success', 'data' => $this->User_model->get_roles()], 200);
    }
    
    public function detail_get($id) {
        $user = $this->User_model->get_by_id($id);
        if (!$user) {
            $this->response(['status' => 'error', 'message' => 'User not found'], 404);
            return;
        }
        $this->response(['status' => 'success', 'data' => $user], 200);
    }
    
    public function create_post() {
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('full_name', 'Full Name', 'required');
        $this->form_validation->set_rules('role_id', 'Role', 'required|numeric');
        
        if ($this->form_validation->run() == FALSE) {
            $this->response(['status' => 'error', 'errors' => $this->form_validation->error_array()], 400);
            return;
        }
        
        $data = [
            'role_id' => $this->post('role_id'),
            'username' => $this->post('username'),
            'email' => $this->post('email'),
            'password' => password_hash($this->post('password'), PASSWORD_BCRYPT),
            'full_name' => $this->post('full_name'),
            'status' => $this->post('status') ?: 'active'
        ];
        
        $id = $this->User_model->create($data);
        $this->response(['status' => 'success', 'message' => 'User created', 'data' => ['id' => $id]], 201);
    }
    
    public function update_put($id) {
        $existing = $this->User_model->get_by_id($id);
        if (!$existing) {
            $this->response(['status' => 'error', 'message' => 'User not found'], 404);
            return;
        }
        
        $data = [
            'role_id' => $this->put('role_id'),
            'username' => $this->put('username'),
            'email' => $this->put('email'),
            'full_name' => $this->put('full_name'),
            'status' => $this->put('status') ?: 'active'
        ];
        
        if ($this->put('password')) {
            $data['password'] = password_hash($this->put('password'), PASSWORD_BCRYPT);
        }
        
        $this->User_model->update($id, $data);
        $this->response(['status' => 'success', 'message' => 'User updated'], 200);
    }
    
    public function delete_delete($id) {
        $existing = $this->User_model->get_by_id($id);
        if (!$existing) {
            $this->response(['status' => 'error', 'message' => 'User not found'], 404);
            return;
        }
        
        $this->User_model->delete($id);
        $this->response(['status' => 'success', 'message' => 'User deleted'], 200);
    }
}