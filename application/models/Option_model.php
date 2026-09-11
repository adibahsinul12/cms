<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Option_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function get_all() {
        $options = $this->db->get('options')->result_array();
        $result = [];
        foreach ($options as $opt) {
            $result[$opt['option_name']] = $opt['option_value'];
        }
        return $result;
    }
    
    public function get($key, $default = null) {
        $this->db->where('option_name', $key);
        $row = $this->db->get('options')->row_array();
        return $row ? $row['option_value'] : $default;
    }
    
    public function set($key, $value) {
        $this->db->where('option_name', $key);
        $exists = $this->db->get('options')->row_array();
        
        if ($exists) {
            $this->db->where('option_name', $key);
            return $this->db->update('options', ['option_value' => $value]);
        } else {
            return $this->db->insert('options', [
                'option_name' => $key,
                'option_value' => $value
            ]);
        }
    }
    
    public function set_multiple($data) {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }
        return TRUE;
    }
}