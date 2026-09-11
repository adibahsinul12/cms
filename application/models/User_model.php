<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function get_all() {
        $this->db->select('id, role_id, username, email, full_name, status, created_at');
        return $this->db->get('users')->result_array();
    }
    
    public function get_by_id($id) {
        $this->db->select('id, role_id, username, email, full_name, status, created_at');
        $this->db->where('id', $id);
        return $this->db->get('users')->row_array();
    }
    
    public function get_by_username($username) {
        $this->db->where('username', $username);
        $this->db->or_where('email', $username);
        return $this->db->get('users')->row_array();
    }
    
    public function create($data) {
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }
    
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }
    
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('users');
    }
    
    public function get_roles() {
        return $this->db->get('roles')->result_array();
    }
}