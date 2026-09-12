<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class Media extends Base_api {

    public function __construct() {
        parent::__construct();
        $this->require_role([1, 3, 4]); // Admin, Editor, Author
        $this->load->model('Media_model');
        $this->load->helper('url');
    }

    private function add_file_url($item) {
        if (isset($item['file_path'])) {
            $item['file_url'] = base_url($item['file_path']);
        }
        return $item;
    }

    // [GET] /api/media
    public function index() {
        $data = $this->Media_model->get_all();
        $data = array_map([$this, 'add_file_url'], $data);
        $this->response_success($data, 'OK', 200);
    }

    // [GET] /api/media/show/(:num) dan /api/media/(:num)
    public function detail($id) {
        $media = $this->Media_model->get_by_id($id);
        if (!$media) {
            $this->response_error('Media not found', 404);
            return;
        }
        $this->response_success($this->add_file_url($media), 'OK', 200);
    }

    // [POST] /api/media/upload
    public function upload() {
        if (empty($_FILES['file']['name'])) {
            $this->response_error('No file uploaded', 400);
            return;
        }

        $upload_path = FCPATH . 'uploads/media/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, TRUE);
        }

        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc|docx|xls|xlsx|zip';
        $config['max_size'] = 10240;
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file')) {
            $this->response_error($this->upload->display_errors('', ''), 400);
            return;
        }

        $data = $this->upload->data();
        $file_ext = ltrim($data['file_ext'], '.');

        $sql = "CALL sp_upload_media(?, ?, ?, ?, ?, ?, ?)";
        $query = $this->db->query($sql, [
            $this->current_user['id'], // sekarang pakai id dari session, bukan hardcode 1
            $data['file_name'],
            'uploads/media/' . $data['file_name'],
            $file_ext,
            $data['file_size'],
            null,
            null
        ]);

        $result = $query->row_array();

        $this->response_success([
            'id'        => $result['media_id'] ?? null,
            'file_name' => $data['file_name'],
            'file_path' => 'uploads/media/' . $data['file_name'],
            'file_url'  => base_url('uploads/media/' . $data['file_name'])
        ], 'File uploaded', 201);
    }

    // [PUT] /api/media/update/(:num)
    public function update($id) {
        $existing = $this->Media_model->get_by_id($id);
        if (!$existing) {
            $this->response_error('Media not found', 404);
            return;
        }

        $raw_input = file_get_contents('php://input');
        $input_data = json_decode($raw_input, TRUE) ?: [];

        $data = [
            'alt_text' => $input_data['alt_text'] ?? $existing['alt_text'],
            'caption'  => $input_data['caption'] ?? $existing['caption']
        ];

        $this->Media_model->update($id, $data);
        $this->response_success(null, 'Media updated', 200);
    }

    // [DELETE] /api/media/delete/(:num)
    public function delete($id) {
        $existing = $this->Media_model->get_by_id($id);
        if (!$existing) {
            $this->response_error('Media not found', 404);
            return;
        }

        $file_path = FCPATH . $existing['file_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        $this->Media_model->delete($id);
        $this->response_success(null, 'Media deleted', 200);
    }
}