<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class Post_meta extends Base_api {

    public function __construct() {
        parent::__construct();
        $this->require_role([1, 3, 4]); // Admin, Editor, Author
        $this->load->model('Post_meta_model');
    }

    // [GET] /api/post/meta/:post_id — Ambil semua meta untuk satu post
    public function detail($post_id = NULL) {
        if (!$post_id || !is_numeric($post_id)) {
            $this->response_error('Post ID wajib diisi dan harus berupa angka!', 400);
            return;
        }

        $meta = $this->Post_meta_model->get_meta_by_post($post_id);
        $this->response_success($meta, 'OK', 200);
    }

    // [POST] /api/post/meta/:post_id — Simpan / update meta untuk satu post
    public function save($post_id = NULL) {
        if (!$post_id || !is_numeric($post_id)) {
            $this->response_error('Post ID wajib diisi dan harus berupa angka!', 400);
            return;
        }

        $raw_input  = file_get_contents('php://input');
        $input_data = json_decode($raw_input, TRUE) ?: $this->input->post();

        // Terima format { "meta": { "key": "value" } } ATAU flat { "key": "value" }
        $meta_data = isset($input_data['meta']) ? $input_data['meta'] : $input_data;

        if (empty($meta_data) || !is_array($meta_data)) {
            $this->response_error('Data meta tidak valid atau kosong!', 400);
            return;
        }

        $result = $this->Post_meta_model->sp_save_post_meta($post_id, $meta_data);

        if ($result) {
            $this->response_success(null, 'Post meta berhasil disimpan!', 200);
        } else {
            $this->response_error('Gagal menyimpan post meta.', 500);
        }
    }

    // [DELETE] /api/post/meta/delete/:post_id — Hapus meta (semua atau key tertentu)
    // Query param: ?meta_key=subtitle  → hapus satu key saja
    // Tanpa query param              → hapus semua meta post tersebut
    public function delete($post_id = NULL) {
        if (!$post_id || !is_numeric($post_id)) {
            $this->response_error('Post ID wajib diisi dan harus berupa angka!', 400);
            return;
        }

        $meta_key = $this->input->get('meta_key') ?: NULL;

        $result = $this->Post_meta_model->delete_meta($post_id, $meta_key);

        if ($result) {
            $msg = $meta_key
                ? "Meta key '{$meta_key}' berhasil dihapus."
                : 'Semua meta post berhasil dihapus.';
            $this->response_success(null, $msg, 200);
        } else {
            $this->response_error('Gagal menghapus post meta.', 500);
        }
    }
}