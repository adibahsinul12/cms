<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class Post_meta extends Base_api {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * POST /api/post/meta
     * Simpan meta (panggil sp_save_post_meta)
     */
    public function save_post() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $post_id = $input['post_id'] ?? null;
        $meta_key = $input['meta_key'] ?? null;
        $meta_value = $input['meta_value'] ?? null;
        
        if (!$post_id || !$meta_key) {
            $this->response([
                'status' => 'error',
                'message' => 'post_id dan meta_key wajib diisi'
            ], 400);
            return;
        }
        
        // Panggil stored procedure
        $sql = "CALL sp_save_post_meta(?, ?, ?)";
        $this->db->query($sql, [$post_id, $meta_key, $meta_value]);
        
        $this->response([
            'status' => 'success',
            'message' => 'Meta saved successfully'
        ], 200);
    }
    
    /**
     * GET /api/post/meta/{post_id}
     * Ambil semua meta berdasarkan post_id
     */
    public function get_get($post_id) {
        $this->db->where('post_id', $post_id);
        $query = $this->db->get('post_meta');
        
        $this->response([
            'status' => 'success',
            'data' => $query->result_array()
        ], 200);
    }
}