<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class Media extends Base_api {

    public function __construct() {
        parent::__construct();
        $this->load->model('Media_model');
        $this->load->helper(array('form', 'url'));
    }

    // [GET] /api/media - Ambil seluruh daftar media
    public function index() {
        $media = $this->Media_model->get_all_media();

        foreach ($media as &$item) {
            $item['file_url'] = base_url($item['file_path']);
        }

        $this->response([
            'status' => 'success',
            'data'   => $media
        ], 200);
    }

    // [GET] /api/media/show/$id - Ambil detail satu media
    // Catatan: tidak bisa pakai /api/media/$id langsung, karena Base_api
    // tidak melakukan dispatch berdasarkan HTTP method - routing CI murni
    // berdasarkan segment URL ke nama method. Makanya perlu segment eksplisit "show".
    public function show($id = NULL) {
        if (!$id) {
            $this->response_error('ID Media wajib diisi!', 400);
            return;
        }

        $media = $this->Media_model->get_media_by_id($id);
        if (!$media) {
            $this->response_error('Media tidak ditemukan!', 404);
            return;
        }

        $media['file_url'] = base_url($media['file_path']);

        $this->response([
            'status' => 'success',
            'data'   => $media
        ], 200);
    }

    // [POST] /api/media/upload - Upload file media baru
    public function upload() {
        $upload_path = './uploads/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc|docx';
        $config['max_size']      = 5120; // Max 5MB
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file')) {
            $error_msg = strip_tags($this->upload->display_errors());
            $this->response_error($error_msg, 400);
            return;
        }

        $upload_data = $this->upload->data();

        $media_data = array(
            'file_name'   => $upload_data['file_name'],
            'file_path'   => 'uploads/' . $upload_data['file_name'],
            'file_type'   => $upload_data['file_ext'],
            'file_size'   => $upload_data['file_size'],
            'uploaded_by' => $this->input->post('uploaded_by') ? $this->input->post('uploaded_by') : 1,
        );

        $media_id = $this->Media_model->insert_media($media_data);

        if (!$media_id) {
            $orphan_file = FCPATH . $media_data['file_path'];
            if (file_exists($orphan_file)) {
                unlink($orphan_file);
            }
            $this->response_error('Gagal menyimpan metadata media ke database.', 500);
            return;
        }

        $media_data['id']       = $media_id;
        $media_data['file_url'] = base_url($media_data['file_path']);

        $this->response([
            'status'  => 'success',
            'message' => 'Berkas berhasil diunggah!',
            'data'    => $media_data
        ], 201);
    }

    // [PUT] /api/media/update/$id - Update alt_text & caption
    public function update_meta($id = NULL) {
        if (!$id) {
            $this->response_error('ID Media wajib diisi!', 400);
            return;
        }

        $media = $this->Media_model->get_media_by_id($id);
        if (!$media) {
            $this->response_error('Media tidak ditemukan!', 404);
            return;
        }

        // PUT body tidak otomatis terbaca lewat $this->input->post(),
        // jadi ambil manual dari raw input (frontend mengirim JSON).
        $raw_input = file_get_contents('php://input');
        $payload   = json_decode($raw_input, true);

        if (!is_array($payload)) {
            $this->response_error('Payload tidak valid.', 400);
            return;
        }

        $update_data = array();
        if (array_key_exists('alt_text', $payload)) {
            $update_data['alt_text'] = $payload['alt_text'];
        }
        if (array_key_exists('caption', $payload)) {
            $update_data['caption'] = $payload['caption'];
        }

        if (empty($update_data)) {
            $this->response_error('Tidak ada data untuk diupdate.', 400);
            return;
        }

        $success = $this->Media_model->update_media_meta($id, $update_data);

        if (!$success) {
            $this->response_error('Gagal mengupdate metadata.', 500);
            return;
        }

        $updated = $this->Media_model->get_media_by_id($id);
        $updated['file_url'] = base_url($updated['file_path']);

        $this->response([
            'status'  => 'success',
            'message' => 'Metadata berhasil diupdate!',
            'data'    => $updated
        ], 200);
    }

    // [DELETE] /api/media/delete/$id - Hapus media dari DB dan storage
    public function delete($id = NULL) {
        if (!$id) {
            $this->response_error('ID Media wajib diisi!', 400);
            return;
        }

        $media = $this->Media_model->get_media_by_id($id);
        if (!$media) {
            $this->response_error('Media tidak ditemukan!', 404);
            return;
        }

        $file_full_path = FCPATH . $media['file_path'];
        if (file_exists($file_full_path)) {
            unlink($file_full_path);
        }

        $this->Media_model->delete_media($id);

        $this->response_success(null, 'Media berhasil dihapus!', 200);
    }
}