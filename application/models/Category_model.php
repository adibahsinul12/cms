<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function get_all() {
        $query = $this->db->get('categories');
        return $query->result_array();
    }
    
    public function get_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('categories');
        return $query->row_array();
    }
    
    public function create($data) {
        $this->db->insert('categories', $data);
        return $this->db->insert_id();
    }
    
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('categories', $data);
    }
    
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('categories');
    }
    
    public function count_all() {
        return $this->db->count_all('categories');
    }
}