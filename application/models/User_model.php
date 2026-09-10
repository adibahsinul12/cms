<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // --- USERS ---
    public function get_all_users() {
        $this->db->select('u.id, u.username, u.email, u.full_name, u.role_id, r.role_name, u.created_at');
        $this->db->from('users u');
        $this->db->join('roles r', 'r.id = u.role_id', 'left');
        return $this->db->get()->result_array();
    }

    public function get_user_by_id($id) {
        $this->db->select('u.id, u.username, u.email, u.full_name, u.role_id, r.role_name');
        $this->db->from('users u');
        $this->db->join('roles r', 'r.id = u.role_id', 'left');
        $this->db->where('u.id', $id);
        return $this->db->get()->row_array();
    }

    public function create_user($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    public function update_user($id, $data) {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        } else {
            unset($data['password']);
        }
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    public function delete_user($id) {
        $this->db->where('id', $id);
        return $this->db->delete('users');
    }

    // --- ROLES ---
    public function get_all_roles() {
        return $this->db->get('roles')->result_array();
    }
}