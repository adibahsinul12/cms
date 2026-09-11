<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class Settings extends Base_api {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Option_model');
    }
    
    public function index_get() {
        $this->response(['status' => 'success', 'data' => $this->Option_model->get_all()], 200);
    }
    
    public function index_post() {
        $data = [
            'site_name' => $this->post('site_name'),
            'site_description' => $this->post('site_description'),
            'admin_email' => $this->post('admin_email'),
            'site_url' => $this->post('site_url'),
            'footer_text' => $this->post('footer_text'),
            'active_theme' => $this->post('active_theme')
        ];
        
        // Filter null values
        $data = array_filter($data, function($v) { return $v !== null; });
        
        $this->Option_model->set_multiple($data);
        $this->response(['status' => 'success', 'message' => 'Settings saved'], 200);
    }
}