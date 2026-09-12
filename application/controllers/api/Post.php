<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class Post extends Base_api {

    public function __construct() {
        parent::__construct();
        $this->require_role([1, 3, 4]); // Admin, Editor, Author
        $this->load->model('Post_model');
    }

    public function index() {
        $limit  = $this->input->get('limit') ? $this->input->get('limit') : 10;
        $offset = $this->input->get('offset') ? $this->input->get('offset') : 0;
        $status = $this->input->get('status');

        $posts = $this->Post_model->get_posts($limit, $offset, $status);
        $total = $this->Post_model->count_posts($status);

        $this->response([
            'status' => 'success',
            'data'   => $posts,
            'pagination' => [
                'total'  => (int)$total,
                'limit'  => (int)$limit,
                'offset' => (int)$offset
            ]
        ], 200);
    }

    public function detail($id = NULL) {
        if (!$id) {
            $this->response_error('ID Post wajib diisi', 400);
            return;
        }

        $post = $this->Post_model->get_post_by_id($id);

        if (!$post) {
            $this->response_error('Post not found', 404);
            return;
        }

        $revisions = $this->Post_model->get_post_revisions($id);
        $post['revisions'] = $revisions;

        $this->response([
            'status' => 'success',
            'data'   => $post
        ], 200);
    }

    public function create() {
        $raw_input = file_get_contents('php://input');
        $input_data = json_decode($raw_input, TRUE) ?: $this->input->post();

        if (empty($input_data['title']) || empty($input_data['slug']) || empty($input_data['author_id'])) {
            $this->response_error('Title, Slug, dan Author ID wajib diisi!', 400);
            return;
        }

        $data = [
            'author_id'         => $input_data['author_id'],
            'type'              => isset($input_data['type']) ? $input_data['type'] : 'post',
            'title'             => $input_data['title'],
            'slug'              => $input_data['slug'],
            'content'           => isset($input_data['content']) ? $input_data['content'] : '',
            'excerpt'           => isset($input_data['excerpt']) ? $input_data['excerpt'] : '',
            'status'            => isset($input_data['status']) ? $input_data['status'] : 'draft',
            'scheduled_at'      => isset($input_data['scheduled_at']) ? $input_data['scheduled_at'] : NULL,
            'category_id'       => isset($input_data['category_id']) ? $input_data['category_id'] : NULL,
            'tag_id'            => isset($input_data['tag_id']) ? $input_data['tag_id'] : NULL,
            'featured_image_id' => isset($input_data['featured_image_id']) ? $input_data['featured_image_id'] : NULL
        ];

        $post_id = $this->Post_model->create_post($data);

        if ($post_id) {
            $this->response_success(['post_id' => $post_id], 'Post created successfully', 201);
        } else {
            $this->response_error('Failed to create post', 500);
        }
    }

    public function update($id = NULL) {
        if (!$id) {
            $this->response_error('ID Post wajib diisi', 400);
            return;
        }

        $existing = $this->Post_model->get_post_by_id($id);
        if (!$existing) {
            $this->response_error('Post not found', 404);
            return;
        }

        $raw_input = file_get_contents('php://input');
        $input_data = json_decode($raw_input, TRUE) ?: $this->input->post();

        $data = [
            'title'             => isset($input_data['title']) ? $input_data['title'] : $existing['title'],
            'slug'              => isset($input_data['slug']) ? $input_data['slug'] : $existing['slug'],
            'content'           => isset($input_data['content']) ? $input_data['content'] : $existing['content'],
            'excerpt'           => isset($input_data['excerpt']) ? $input_data['excerpt'] : $existing['excerpt'],
            'status'            => isset($input_data['status']) ? $input_data['status'] : $existing['status'],
            'scheduled_at'      => isset($input_data['scheduled_at']) ? $input_data['scheduled_at'] : $existing['scheduled_at'],
            'category_id'       => isset($input_data['category_id']) ? $input_data['category_id'] : $existing['category_id'],
            'tag_id'            => isset($input_data['tag_id']) ? $input_data['tag_id'] : $existing['tag_id'],
            'featured_image_id' => isset($input_data['featured_image_id']) ? $input_data['featured_image_id'] : $existing['featured_image_id']
        ];

        $result = $this->Post_model->update_post($id, $data);

        if ($result) {
            $this->response_success(null, 'Post updated successfully', 200);
        } else {
            $this->response_error('Failed to update post', 500);
        }
    }

    public function delete($id = NULL) {
        if (!$id) {
            $this->response_error('ID Post wajib diisi', 400);
            return;
        }

        $existing = $this->Post_model->get_post_by_id($id);
        if (!$existing) {
            $this->response_error('Post not found', 404);
            return;
        }

        $result = $this->Post_model->delete_post($id);

        if ($result) {
            $this->response_success(null, 'Post deleted successfully', 200);
        } else {
            $this->response_error('Failed to delete post', 500);
        }
    }

    public function revisions($post_id = NULL) {
        if (!$post_id) {
            $this->response_error('Post ID wajib diisi', 400);
            return;
        }

        $revisions = $this->Post_model->get_post_revisions($post_id);
        $this->response_success($revisions, 'Success', 200);
    }

    public function save_meta($post_id = NULL) {
        if (!$post_id) {
            $this->response_error('ID Post wajib diisi!', 400);
            return;
        }

        $raw_input = file_get_contents('php://input');
        $input_data = json_decode($raw_input, TRUE) ?: $this->input->post();

        $meta_data = isset($input_data['meta']) ? $input_data['meta'] : $input_data;

        if (empty($meta_data) || !is_array($meta_data)) {
            $this->response_error('Data meta tidak valid atau kosong!', 400);
            return;
        }

        $this->load->model('Post_meta_model');

        $result = $this->Post_meta_model->sp_save_post_meta($post_id, $meta_data);

        if ($result) {
            $this->response_success(null, 'Custom meta berhasil disimpan!');
        } else {
            $db_error = $this->db->error();
            $this->response_error('Gagal menyimpan custom meta: ' . $db_error['message'], 500);
        }
    }

    public function get_meta($post_id = NULL) {
        if (!$post_id) {
            $this->response_error('ID Post wajib diisi!', 400);
            return;
        }

        $this->load->model('Post_meta_model');
        $meta = $this->Post_meta_model->get_meta_by_post($post_id);

        $this->response([
            'status' => 'success',
            'data'   => $meta
        ], 200);
    }
}