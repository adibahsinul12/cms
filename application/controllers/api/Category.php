<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class Category extends Base_api {

    public function __construct() {
        parent::__construct();
        $this->load->model('Category_model');
    }

    // [GET] /api/category
    public function index() {
        $categories = $this->Category_model->get_all();
        $this->response_success($categories, 'OK', 200);
    }

    // [GET] /api/category/(:num)
    public function detail($id) {
        $category = $this->Category_model->get_by_id($id);
        if (!$category) {
            $this->response_error('Category not found', 404);
            return;
        }
        $this->response_success($category, 'OK', 200);
    }

    // [POST] /api/category
    public function create() {
        $raw_input = file_get_contents('php://input');
        $input_data = json_decode($raw_input, TRUE) ?: $this->input->post();

        if (empty($input_data['name'])) {
            $this->response_error('Name wajib diisi!', 400);
            return;
        }
        if (empty($input_data['slug'])) {
            $this->response_error('Slug wajib diisi!', 400);
            return;
        }

        // Cek duplikasi slug
        $existing_slug = $this->Category_model->get_by_slug($input_data['slug']);
        if ($existing_slug) {
            $this->response_error('Slug sudah digunakan!', 400);
            return;
        }

        $data = [
            'name'      => $input_data['name'],
            'slug'      => $input_data['slug'],
            'parent_id' => $input_data['parent_id'] ?? null
        ];

        $id = $this->Category_model->create($data);
        $this->response_success(['id' => $id], 'Category created', 201);
    }

    // [PUT] /api/category/(:num)
    public function update($id) {
        $existing = $this->Category_model->get_by_id($id);
        if (!$existing) {
            $this->response_error('Category not found', 404);
            return;
        }

        $raw_input = file_get_contents('php://input');
        $input_data = json_decode($raw_input, TRUE) ?: [];

        $data = [
            'name'      => $input_data['name'] ?? $existing['name'],
            'slug'      => $input_data['slug'] ?? $existing['slug'],
            'parent_id' => $input_data['parent_id'] ?? $existing['parent_id']
        ];

        $this->Category_model->update($id, $data);
        $this->response_success(null, 'Category updated', 200);
    }

    // [DELETE] /api/category/(:num)
    public function delete($id) {
        $existing = $this->Category_model->get_by_id($id);
        if (!$existing) {
            $this->response_error('Category not found', 404);
            return;
        }

        $this->Category_model->delete($id);
        $this->response_success(null, 'Category deleted', 200);
    }
}