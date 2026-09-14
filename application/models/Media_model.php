<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Media_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function get_all($uploaded_by = null) {
        if ($uploaded_by) {
            $this->db->where('uploaded_by', $uploaded_by);
        }
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('media')->result_array();
    }
    
    public function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get('media')->row_array();
    }
    
    public function create($data) {
        $this->db->insert('media', $data);
        return $this->db->insert_id();
    }
    
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('media', $data);
    }
    
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('media');
    }
}