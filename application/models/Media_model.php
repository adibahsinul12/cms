<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Media_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Simpan metadata berkas yang berhasil diunggah
    public function insert_media($data) {
        $success = $this->db->insert('media', $data);
        if ($success === FALSE) {
            log_message('error', 'Insert media failed: ' . $this->db->error()['message']);
            return FALSE;
        }
        return $this->db->insert_id();
    }

    // Ambil daftar seluruh media
    public function get_all_media() {
        $this->db->select('m.*, u.full_name as uploaded_by_name');
        $this->db->from('media m');
        $this->db->join('users u', 'u.id = m.uploaded_by', 'left');
        $this->db->order_by('m.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    // Ambil detail media berdasarkan ID
    public function get_media_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get('media')->row_array();
    }

    // Update alt_text / caption media
    public function update_media_meta($id, $data) {
        $this->db->where('id', $id);
        $success = $this->db->update('media', $data);
        if ($success === FALSE) {
            log_message('error', 'Update media meta failed: ' . $this->db->error()['message']);
            return FALSE;
        }
        return TRUE;
    }

    // Hapus record media dari database
    public function delete_media($id) {
        $this->db->where('id', $id);
        return $this->db->delete('media');
    }
}