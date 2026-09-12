<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class Tag extends Base_api {

    public function __construct() {
        parent::__construct();
        $this->require_role([1, 3, 4]); // Admin, Editor, Author
        $this->load->model('Tag_model');
    }

    // [GET] /api/tag
    public function index() {
        $this->response_success($this->Tag_model->get_all(), 'OK', 200);
    }

    // [GET] /api/tag/(:num)
    public function detail($id) {
        $tag = $this->Tag_model->get_by_id($id);
        if (!$tag) {
            $this->response_error('Tag not found', 404);
            return;
        }
        $this->response_success($tag, 'OK', 200);
    }

    // [POST] /api/tag/create
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

        $existing_slug = $this->Tag_model->get_by_slug($input_data['slug']);
        if ($existing_slug) {
            $this->response_error('Slug sudah digunakan!', 400);
            return;
        }

        $data = [
            'name' => $input_data['name'],
            'slug' => $input_data['slug']
        ];

        $id = $this->Tag_model->create($data);
        $this->response_success(['id' => $id], 'Tag created', 201);
    }

    // [PUT] /api/tag/update/(:num)
    public function update($id) {
        $existing = $this->Tag_model->get_by_id($id);
        if (!$existing) {
            $this->response_error('Tag not found', 404);
            return;
        }

        $raw_input = file_get_contents('php://input');
        $input_data = json_decode($raw_input, TRUE) ?: [];

        $data = [
            'name' => $input_data['name'] ?? $existing['name'],
            'slug' => $input_data['slug'] ?? $existing['slug']
        ];

        $this->Tag_model->update($id, $data);
        $this->response_success(null, 'Tag updated', 200);
    }

    // [DELETE] /api/tag/delete/(:num)
    public function delete($id) {
        $existing = $this->Tag_model->get_by_id($id);
        if (!$existing) {
            $this->response_error('Tag not found', 404);
            return;
        }

        $this->Tag_model->delete($id);
        $this->response_success(null, 'Tag deleted', 200);
    }
}