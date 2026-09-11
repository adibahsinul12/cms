<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class Seo extends Base_api {

    public function __construct() {
        parent::__construct();
        $this->load->model('Option_model');
    }

    // [GET] Ambil semua SEO settings, [POST] Simpan SEO settings — satu URL dua fungsi
    public function index() {
        if ($this->input->method() === 'post') {
            $raw_input = file_get_contents('php://input');
            $input_data = json_decode($raw_input, TRUE) ?: $this->input->post();

            $allowed_keys = ['meta_title', 'meta_description', 'meta_keywords', 'og_title', 'og_description', 'og_image', 'enable_sitemap', 'enable_robots'];
            $data = array_intersect_key($input_data, array_flip($allowed_keys));
            $data = array_filter($data, function($v) { return $v !== null; });

            $this->Option_model->set_multiple($data);
            $this->response_success(null, 'SEO settings saved', 200);
            return;
        }

        $keys = ['meta_title', 'meta_description', 'meta_keywords', 'og_title', 'og_description', 'og_image', 'enable_sitemap', 'enable_robots'];
        $data = [];
        foreach ($keys as $key) {
            $data[$key] = $this->Option_model->get($key, '');
        }
        $this->response_success($data, 'OK', 200);
    }
}