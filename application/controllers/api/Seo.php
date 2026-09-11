<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class Seo extends Base_api {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Option_model');
    }
    
    public function index_get() {
        $keys = ['meta_title', 'meta_description', 'meta_keywords', 'og_title', 'og_description', 'og_image', 'enable_sitemap', 'enable_robots'];
        $data = [];
        foreach ($keys as $key) {
            $data[$key] = $this->Option_model->get($key, '');
        }
        $this->response(['status' => 'success', 'data' => $data], 200);
    }
    
    public function index_post() {
        $data = [
            'meta_title' => $this->post('meta_title'),
            'meta_description' => $this->post('meta_description'),
            'meta_keywords' => $this->post('meta_keywords'),
            'og_title' => $this->post('og_title'),
            'og_description' => $this->post('og_description'),
            'og_image' => $this->post('og_image'),
            'enable_sitemap' => $this->post('enable_sitemap'),
            'enable_robots' => $this->post('enable_robots')
        ];
        
        $data = array_filter($data, function($v) { return $v !== null; });
        
        $this->Option_model->set_multiple($data);
        $this->response(['status' => 'success', 'message' => 'SEO settings saved'], 200);
    }
}