<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Memuat Base Controller secara eksplisit agar tidak menyebabkan 'Class MY_Controller not found'
if (!class_exists('MY_Controller')) {
    require_once APPPATH . 'core/MY_Controller.php';
}

class Post extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Post_model');
        $this->load->helper('url');
    }

    // [GET] /api/post - Menampilkan daftar semua post/page
    public function index() {
        $type = $this->input->get('type') ? $this->input->get('type') : 'post';
        $status = $this->input->get('status');

        $posts = $this->Post_model->get_all_posts($type, $status);
        $this->response_json([
            'status' => true,
            'data'   => $posts
        ], 200);
    }

    // [GET] /api/post/detail/$id - Mengambil detail 1 post
    public function detail($id = NULL) {
        if (!$id) {
            $this->response_json(['status' => false, 'message' => 'ID wajib diisi!'], 400);
        }

        $post = $this->Post_model->get_post_by_id($id);
        if ($post) {
            $this->response_json(['status' => true, 'data' => $post], 200);
        } else {
            $this->response_json(['status' => false, 'message' => 'Post tidak ditemukan!'], 404);
        }
    }

    // [POST] /api/post/create - Membuat post baru (via Stored Procedure)
    public function create() {
        $raw_input = file_get_contents('php://input');
        $data = json_decode($raw_input, TRUE);

        if (empty($data['title']) || empty($data['author_id'])) {
            $this->response_json(['status' => false, 'message' => 'Judul dan Author ID wajib diisi!'], 400);
        }

        // Generate Slug otomatis jika tidak diisi
        if (empty($data['slug'])) {
            $data['slug'] = url_title($data['title'], '-', TRUE);
        }

        $result = $this->Post_model->create_post_sp($data);

        if ($result) {
            $this->response_json([
                'status'  => true,
                'message' => 'Post berhasil dibuat!',
                'data'    => $result
            ], 201);
        } else {
            $this->response_json(['status' => false, 'message' => 'Gagal membuat post.'], 500);
        }
    }

    // [PUT/POST] /api/post/update/$id - Mengubah post
    public function update($id = NULL) {
        if (!$id) {
            $this->response_json(['status' => false, 'message' => 'ID wajib diisi!'], 400);
        }

        $raw_input = file_get_contents('php://input');
        $data = json_decode($raw_input, TRUE);

        if (empty($data)) {
            $this->response_json(['status' => false, 'message' => 'Data tidak boleh kosong!'], 400);
        }

        $updated = $this->Post_model->update_post($id, $data);

        if ($updated) {
            $this->response_json(['status' => true, 'message' => 'Post berhasil diperbarui!'], 200);
        } else {
            $this->response_json(['status' => false, 'message' => 'Gagal memperbarui post.'], 500);
        }
    }

    // [DELETE] /api/post/delete/$id - Menghapus post
    public function delete($id = NULL) {
        if (!$id) {
            $this->response_json(['status' => false, 'message' => 'ID wajib diisi!'], 400);
        }

        $deleted = $this->Post_model->delete_post($id);
        if ($deleted) {
            $this->response_json(['status' => true, 'message' => 'Post berhasil dihapus!'], 200);
        } else {
            $this->response_json(['status' => false, 'message' => 'Gagal menghapus post.'], 500);
        }
    }

    // [GET] /api/post/revisions/$post_id - Riwayat revisi post
    public function revisions($post_id = NULL) {
        if (!$post_id) {
            $this->response_json(['status' => false, 'message' => 'Post ID wajib diisi!'], 400);
        }

        $revisions = $this->Post_model->get_revisions($post_id);
        $this->response_json(['status' => true, 'data' => $revisions], 200);
    }
}