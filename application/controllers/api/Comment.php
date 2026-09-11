<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class Comment extends Base_api {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Comment_model');
    }
    
    public function index_get() {
        $status = $this->get('status');
        $post_id = $this->get('post_id');
        $this->response(['status' => 'success', 'data' => $this->Comment_model->get_all($status, $post_id)], 200);
    }
    
    public function add_post() {
        $this->form_validation->set_rules('post_id', 'Post ID', 'required|numeric');
        $this->form_validation->set_rules('content', 'Content', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->response(['status' => 'error', 'errors' => $this->form_validation->error_array()], 400);
            return;
        }
        
        $sql = "CALL sp_add_comment(?, ?, ?, ?, ?, ?)";
        $query = $this->db->query($sql, [
            $this->post('post_id'),
            $this->post('user_id') ?: null,
            $this->post('author_name'),
            $this->post('author_email'),
            $this->post('content'),
            $this->post('parent_id') ?: null
        ]);
        
        $result = $query->row_array();
        $query->next_result();
        $query->free_result();
        
        $this->response(['status' => 'success', 'message' => 'Komentar dikirim, menunggu moderasi', 'data' => ['comment_id' => $result['comment_id']]], 201);
    }
    
    public function update_put($id) {
        $existing = $this->Comment_model->get_by_id($id);
        if (!$existing) {
            $this->response(['status' => 'error', 'message' => 'Comment not found'], 404);
            return;
        }
        
        $this->Comment_model->update($id, ['status' => $this->put('status')]);
        $this->response(['status' => 'success', 'message' => 'Comment updated'], 200);
    }
    
    public function delete_delete($id) {
        $existing = $this->Comment_model->get_by_id($id);
        if (!$existing) {
            $this->response(['status' => 'error', 'message' => 'Comment not found'], 404);
            return;
        }
        
        $this->Comment_model->delete($id);
        $this->response(['status' => 'success', 'message' => 'Comment deleted'], 200);
    }
}