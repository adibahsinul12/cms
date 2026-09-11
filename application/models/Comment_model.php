<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comment_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function get_all($status = null, $post_id = null) {
        $this->db->select('comments.*, users.username, posts.title as post_title');
        $this->db->from('comments');
        $this->db->join('users', 'users.id = comments.user_id', 'left');
        $this->db->join('posts', 'posts.id = comments.post_id', 'left');
        
        if ($status) $this->db->where('comments.status', $status);
        if ($post_id) $this->db->where('comments.post_id', $post_id);
        
        $this->db->order_by('comments.created_at', 'DESC');
        return $this->db->get()->result_array();
    }
    
    public function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get('comments')->row_array();
    }
    
    public function create($data) {
        $this->db->insert('comments', $data);
        return $this->db->insert_id();
    }
    
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('comments', $data);
    }
    
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('comments');
    }
}