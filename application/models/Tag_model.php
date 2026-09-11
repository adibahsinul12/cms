<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tag_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function get_all() {
        return $this->db->get('tags')->result_array();
    }
    
    public function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get('tags')->row_array();
    }
    
    public function create($data) {
        $this->db->insert('tags', $data);
        return $this->db->insert_id();
    }
    
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('tags', $data);
    }
    
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('tags');
    }
}