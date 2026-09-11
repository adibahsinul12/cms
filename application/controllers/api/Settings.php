<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class Settings extends Base_api {

    public function __construct() {
        parent::__construct();
        $this->load->model('Option_model');
    }

    // [GET] Ambil semua settings, [POST] Simpan settings — satu URL dua fungsi
    public function index() {
        if ($this->input->method() === 'post') {
            $raw_input = file_get_contents('php://input');
            $input_data = json_decode($raw_input, TRUE) ?: $this->input->post();

            $allowed_keys = ['site_name', 'site_description', 'admin_email', 'site_url', 'footer_text', 'active_theme'];
            $data = array_intersect_key($input_data, array_flip($allowed_keys));
            $data = array_filter($data, function($v) { return $v !== null; });

            $this->Option_model->set_multiple($data);
            $this->response_success(null, 'Settings saved', 200);
            return;
        }

        $this->response_success($this->Option_model->get_all(), 'OK', 200);
    }
}