<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class Comment extends Base_api {

    public function __construct() {
        parent::__construct();
        $this->load->model('Comment_model');
        // TIDAK ada require_role() di sini karena add() harus tetap publik.
        // Proteksi dipasang per-method di bawah untuk index/update/delete.
    }

    // [GET] /api/comments - Moderasi, khusus Admin & Editor
    public function index() {
        $this->require_role([1, 3, 4]); // Admin, Editor, Author

        $status = $this->input->get('status');
        $post_id = $this->input->get('post_id');
        $this->response_success($this->Comment_model->get_all($status, $post_id), 'OK', 200);
    }

    // [POST] /api/comments/add - PUBLIK, siapa saja (termasuk tanpa login) boleh kirim komentar
    public function add() {
        $raw_input = file_get_contents('php://input');
        $input_data = json_decode($raw_input, TRUE) ?: $this->input->post();

        if (empty($input_data['post_id']) || !is_numeric($input_data['post_id'])) {
            $this->response_error('Post ID wajib diisi dan harus berupa angka!', 400);
            return;
        }
        if (empty($input_data['content'])) {
            $this->response_error('Content wajib diisi!', 400);
            return;
        }

        $sql = "CALL sp_add_comment(?, ?, ?, ?, ?, ?)";
        $query = $this->db->query($sql, [
            $input_data['post_id'],
            $input_data['user_id'] ?? null,
            $input_data['author_name'] ?? null,
            $input_data['author_email'] ?? null,
            $input_data['content'],
            $input_data['parent_id'] ?? null
        ]);

        $result = $query->row_array();

        $this->response_success(['comment_id' => $result['comment_id']], 'Komentar dikirim, menunggu moderasi', 201);
    }

    // [POST/PUT] /api/comments/(:num) - Moderasi (ubah status approve/reject), khusus Admin & Editor
    public function update($id) {
        $this->require_role([1, 3, 4]); // Admin, Editor, Author

        $existing = $this->Comment_model->get_by_id($id);
        if (!$existing) {
            $this->response_error('Comment not found', 404);
            return;
        }

        $raw_input = file_get_contents('php://input');
        $input_data = json_decode($raw_input, TRUE) ?: $this->input->post();

        $this->Comment_model->update($id, ['status' => $input_data['status'] ?? $existing['status']]);
        $this->response_success(null, 'Comment updated', 200);
    }

    // [POST/DELETE] /api/comments/delete/(:num) - Moderasi, khusus Admin & Editor
    public function delete($id) {
        $this->require_role([1, 3, 4]); // Admin, Editor, Author

        $existing = $this->Comment_model->get_by_id($id);
        if (!$existing) {
            $this->response_error('Comment not found', 404);
            return;
        }

        $this->Comment_model->delete($id);
        $this->response_success(null, 'Comment deleted', 200);
    }
}