<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class Log extends Base_api {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function index_get() {
        $this->db->select('activity_logs.*, users.username as user_name');
        $this->db->from('activity_logs');
        $this->db->join('users', 'users.id = activity_logs.user_id', 'left');
        $this->db->order_by('activity_logs.created_at', 'DESC');
        $this->db->limit(100);
        
        $this->response(['status' => 'success', 'data' => $this->db->get()->result_array()], 200);
    }
}