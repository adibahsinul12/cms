<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class Tag extends Base_api {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Tag_model');
    }
    
    public function index_get() {
        $this->response(['status' => 'success', 'data' => $this->Tag_model->get_all()], 200);
    }
    
    public function detail_get($id) {
        $tag = $this->Tag_model->get_by_id($id);
        if (!$tag) {
            $this->response(['status' => 'error', 'message' => 'Tag not found'], 404);
            return;
        }
        $this->response(['status' => 'success', 'data' => $tag], 200);
    }
    
    public function index_post() {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('slug', 'Slug', 'required|is_unique[tags.slug]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->response(['status' => 'error', 'errors' => $this->form_validation->error_array()], 400);
            return;
        }
        
        $data = [
            'name' => $this->post('name'),
            'slug' => $this->post('slug')
        ];
        
        $id = $this->Tag_model->create($data);
        $this->response(['status' => 'success', 'message' => 'Tag created', 'data' => ['id' => $id]], 201);
    }
    
    public function index_put($id) {
        $existing = $this->Tag_model->get_by_id($id);
        if (!$existing) {
            $this->response(['status' => 'error', 'message' => 'Tag not found'], 404);
            return;
        }
        
        $data = [
            'name' => $this->put('name'),
            'slug' => $this->put('slug')
        ];
        
        $this->Tag_model->update($id, $data);
        $this->response(['status' => 'success', 'message' => 'Tag updated'], 200);
    }
    
    public function index_delete($id) {
        $existing = $this->Tag_model->get_by_id($id);
        if (!$existing) {
            $this->response(['status' => 'error', 'message' => 'Tag not found'], 404);
            return;
        }
        
        $this->Tag_model->delete($id);
        $this->response(['status' => 'success', 'message' => 'Tag deleted'], 200);
    }
}