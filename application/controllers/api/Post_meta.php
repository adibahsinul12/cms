<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class Post_meta extends Base_api {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function index_post() {
        $post_id = $this->post('post_id');
        $meta_key = $this->post('meta_key');
        $meta_value = $this->post('meta_value');
        
        if (!$post_id || !$meta_key) {
            $this->response(['status' => 'error', 'message' => 'post_id dan meta_key wajib diisi'], 400);
            return;
        }
        
        $sql = "CALL sp_save_post_meta(?, ?, ?)";
        $this->db->query($sql, [$post_id, $meta_key, $meta_value]);
        
        $this->response(['status' => 'success', 'message' => 'Meta saved'], 200);
    }
    
    public function detail_get($post_id) {
        $this->db->where('post_id', $post_id);
        $this->response(['status' => 'success', 'data' => $this->db->get('post_meta')->result_array()], 200);
    }
}