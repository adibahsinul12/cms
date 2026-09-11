<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    public function dashboard() {
        $this->load->view('admin/dashboard');
    }

    public function posts() {
        $this->load->view('admin/posts/index');
    }

    public function create_post() {
        $this->load->view('admin/posts/create');
    }

    public function edit_post($id) {
        $data['post_id'] = $id;
        $this->load->view('admin/posts/edit', $data);
    }

    public function categories() {
        $this->load->view('admin/categories/index');
    }

    public function tags() {
        $this->load->view('admin/tags/index');
    }

    public function media() {
        $this->load->view('admin/media/index');
    }

    public function users() {
        $this->load->view('admin/users/index');
    }

    public function settings() {
        $this->load->view('admin/settings/index');
    }

    public function comments() {
        $this->load->view('admin/comments/index');
    }
}