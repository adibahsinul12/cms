<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post_meta_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Ambil semua custom meta berdasarkan post_id
    public function get_meta_by_post($post_id) {
        $this->db->where('post_id', $post_id);
        $query = $this->db->get('post_meta');
        
        $result = [];
        foreach ($query->result_array() as $row) {
            $result[$row['meta_key']] = $row['meta_value'];
        }
        return $result; // Mengembalikan array key-value ['subtitle' => '...', 'source_url' => '...']
    }

    // Simpan atau Update Custom Meta (sp_save_post_meta)
    public function sp_save_post_meta($post_id, $meta_data) {
        if (empty($meta_data) || !is_array($meta_data)) {
            return false;
        }

        foreach ($meta_data as $key => $value) {
            // Cek apakah meta_key sudah ada untuk post_id ini
            $this->db->where('post_id', $post_id);
            $this->db->where('meta_key', $key);
            $check = $this->db->get('post_meta')->row_array();

            if ($check) {
                // Update jika sudah ada
                $this->db->where('id', $check['id']);
                $this->db->update('post_meta', ['meta_value' => $value]);
            } else {
                // Insert jika belum ada
                $this->db->insert('post_meta', [
                    'post_id'    => $post_id,
                    'meta_key'   => $key,
                    'meta_value' => $value
                ]);
            }
        }
        return true;
    }

    // Hapus meta spesifik
    public function delete_meta($post_id, $meta_key = NULL) {
        $this->db->where('post_id', $post_id);
        if ($meta_key) {
            $this->db->where('meta_key', $meta_key);
        }
        return $this->db->delete('post_meta');
    }
}