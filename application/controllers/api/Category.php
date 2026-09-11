<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class Category extends Base_api {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Category_model');
    }
    
    public function index_get() {
        $categories = $this->Category_model->get_all();
        $this->response([
            'status' => 'success',
            'data' => $categories
        ], 200);
    }
    
    public function detail_get($id) {
        $category = $this->Category_model->get_by_id($id);
        if (!$category) {
            $this->response(['status' => 'error', 'message' => 'Category not found'], 404);
            return;
        }
        $this->response(['status' => 'success', 'data' => $category], 200);
    }
    
    public function index_post() {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('slug', 'Slug', 'required|is_unique[categories.slug]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->response(['status' => 'error', 'errors' => $this->form_validation->error_array()], 400);
            return;
        }
        
        $data = [
            'name' => $this->post('name'),
            'slug' => $this->post('slug'),
            'parent_id' => $this->post('parent_id') ?: null
        ];
        
        $id = $this->Category_model->create($data);
        $this->response(['status' => 'success', 'message' => 'Category created', 'data' => ['id' => $id]], 201);
    }
    
    public function index_put($id) {
        $existing = $this->Category_model->get_by_id($id);
        if (!$existing) {
            $this->response(['status' => 'error', 'message' => 'Category not found'], 404);
            return;
        }
        
        $data = [
            'name' => $this->put('name'),
            'slug' => $this->put('slug'),
            'parent_id' => $this->put('parent_id') ?: null
        ];
        
        $this->Category_model->update($id, $data);
        $this->response(['status' => 'success', 'message' => 'Category updated'], 200);
    }
    
    public function index_delete($id) {
        $existing = $this->Category_model->get_by_id($id);
        if (!$existing) {
            $this->response(['status' => 'error', 'message' => 'Category not found'], 404);
            return;
        }
        
        $this->Category_model->delete($id);
        $this->response(['status' => 'success', 'message' => 'Category deleted'], 200);
    }
}