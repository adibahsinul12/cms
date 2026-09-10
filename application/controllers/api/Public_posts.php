<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class Public_posts extends Base_api {

    public function __construct() {
        parent::__construct();
        $this->load->model('Post_model');
    }

    // [GET] /api/public/posts
    public function index() {
        $posts = $this->Post_model->get_posts(6, 0, 'published');
        $total = $this->Post_model->count_posts('published');

        $this->response([
            'status' => 'success',
            'data'   => $posts ? $posts : [],
            'pagination' => [
                'total'  => (int)$total,
                'limit'  => 6,
                'offset' => 0
            ]
        ], 200);
    }
}