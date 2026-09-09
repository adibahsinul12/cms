<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Membersihkan buffer MySQLi setelah pemanggilan Stored Procedure
    private function clear_mysqli_buffer() {
        if ($this->db->conn_id && mysqli_more_results($this->db->conn_id)) {
            mysqli_next_result($this->db->conn_id);
        }
    }

    // 1. Eksekusi Stored Procedure: sp_create_post
    public function create_post_sp($data) {
        $sql = "CALL sp_create_post(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = array(
            isset($data['author_id']) ? $data['author_id'] : NULL,
            !empty($data['featured_image_id']) ? $data['featured_image_id'] : NULL,
            isset($data['type']) ? $data['type'] : 'post',
            isset($data['title']) ? $data['title'] : '',
            isset($data['slug']) ? $data['slug'] : '',
            isset($data['content']) ? $data['content'] : NULL,
            isset($data['excerpt']) ? $data['excerpt'] : NULL,
            isset($data['status']) ? $data['status'] : 'draft',
            !empty($data['scheduled_at']) ? $data['scheduled_at'] : NULL,
            !empty($data['category_id']) ? $data['category_id'] : NULL,
            !empty($data['tag_id']) ? $data['tag_id'] : NULL
        );

        $query = $this->db->query($sql, $params);
        $result = $query ? $query->row_array() : FALSE;
        $this->clear_mysqli_buffer();

        return $result;
    }

    // 2. Ambil Semua Post / Page
    public function get_all_posts($type = 'post', $status = NULL) {
        $this->db->select('p.*, u.full_name as author_name, m.file_path as featured_image_url');
        $this->db->from('posts p');
        $this->db->join('users u', 'u.id = p.author_id', 'left');
        $this->db->join('media m', 'm.id = p.featured_image_id', 'left');
        $this->db->where('p.type', $type);
        
        if ($status) {
            $this->db->where('p.status', $status);
        }

        $this->db->order_by('p.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    // 3. Ambil Detail Single Post
    public function get_post_by_id($id) {
        $this->db->select('p.*, u.full_name as author_name');
        $this->db->from('posts p');
        $this->db->join('users u', 'u.id = p.author_id', 'left');
        $this->db->where('p.id', $id);
        return $this->db->get()->row_array();
    }

    // 4. Update Post (Memicu Trigger trg_posts_after_update_revision secara otomatis)
    public function update_post($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('posts', $data);
    }

    // 5. Hapus Post (Memicu Trigger trg_posts_after_delete_log secara otomatis)
    public function delete_post($id) {
        $this->db->where('id', $id);
        return $this->db->delete('posts');
    }

    // 6. Ambil Riwayat Revisi Konten
    public function get_revisions($post_id) {
        $this->db->select('pr.*, u.full_name as revised_by_name');
        $this->db->from('post_revisions pr');
        $this->db->join('users u', 'u.id = pr.revised_by', 'left');
        $this->db->where('pr.post_id', $post_id);
        $this->db->order_by('pr.created_at', 'DESC');
        return $this->db->get()->result_array();
    }
}