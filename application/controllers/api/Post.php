<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class Post extends Base_api {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Post_model');
    }
    
    /**
     * GET /api/post - List semua post
     */
    public function index_get() {
        $limit = $this->get('limit') ?: 10;
        $offset = $this->get('offset') ?: 0;
        $status = $this->get('status');
        
        $posts = $this->Post_model->get_posts($limit, $offset, $status);
        $total = $this->Post_model->count_posts($status);
        
        $this->response([
            'status' => 'success',
            'data' => $posts,
            'pagination' => [
                'total' => (int)$total,
                'limit' => (int)$limit,
                'offset' => (int)$offset
            ]
        ], 200);
    }
    
    /**
     * GET /api/post/{id} - Detail post
     */
    public function detail_get($id) {
        $post = $this->Post_model->get_post_by_id($id);
        
        if (!$post) {
            $this->response([
                'status' => 'error',
                'message' => 'Post not found'
            ], 404);
            return;
        }
        
        $revisions = $this->Post_model->get_post_revisions($id);
        $post['revisions'] = $revisions;
        
        $this->response([
            'status' => 'success',
            'data' => $post
        ], 200);
    }
    
    /**
     * POST /api/post - Create new post
     */
    public function index_post() {
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('slug', 'Slug', 'required|is_unique[posts.slug]');
        $this->form_validation->set_rules('author_id', 'Author ID', 'required|numeric');
        
        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => 'error',
                'errors' => $this->form_validation->error_array()
            ], 400);
            return;
        }
        
        $data = [
            'author_id' => $this->post('author_id'),
            'type' => $this->post('type') ?: 'post',
            'title' => $this->post('title'),
            'slug' => $this->post('slug'),
            'content' => $this->post('content'),
            'excerpt' => $this->post('excerpt'),
            'status' => $this->post('status') ?: 'draft',
            'scheduled_at' => $this->post('scheduled_at'),
            'category_id' => $this->post('category_id'),
            'tag_id' => $this->post('tag_id'),
            'featured_image_id' => $this->post('featured_image_id')
        ];
        
        $post_id = $this->Post_model->create_post($data);
        
        if ($post_id) {
            $this->response([
                'status' => 'success',
                'message' => 'Post created successfully',
                'data' => ['post_id' => $post_id]
            ], 201);
        } else {
            $this->response([
                'status' => 'error',
                'message' => 'Failed to create post'
            ], 500);
        }
    }
    
    /**
     * PUT /api/post/{id} - Update post
     */
    public function index_put($id) {
        $existing = $this->Post_model->get_post_by_id($id);
        if (!$existing) {
            $this->response([
                'status' => 'error',
                'message' => 'Post not found'
            ], 404);
            return;
        }
        
        $data = [
            'title' => $this->put('title'),
            'slug' => $this->put('slug'),
            'content' => $this->put('content'),
            'excerpt' => $this->put('excerpt'),
            'status' => $this->put('status') ?: 'draft',
            'scheduled_at' => $this->put('scheduled_at'),
            'category_id' => $this->put('category_id'),
            'tag_id' => $this->put('tag_id'),
            'featured_image_id' => $this->put('featured_image_id')
        ];
        
        $result = $this->Post_model->update_post($id, $data);
        
        if ($result) {
            $this->response([
                'status' => 'success',
                'message' => 'Post updated successfully'
            ], 200);
        } else {
            $this->response([
                'status' => 'error',
                'message' => 'Failed to update post'
            ], 500);
        }
    }
    
    /**
     * DELETE /api/post/{id} - Delete post
     */
    public function index_delete($id) {
        $existing = $this->Post_model->get_post_by_id($id);
        if (!$existing) {
            $this->response([
                'status' => 'error',
                'message' => 'Post not found'
            ], 404);
            return;
        }
        
        $result = $this->Post_model->delete_post($id);
        
        if ($result) {
            $this->response([
                'status' => 'success',
                'message' => 'Post deleted successfully'
            ], 200);
        } else {
            $this->response([
                'status' => 'error',
                'message' => 'Failed to delete post'
            ], 500);
        }
    }
    
    /**
     * GET /api/post/revisions/{post_id} - Ambil revisi
     */
    public function revisions_get($post_id) {
        $revisions = $this->Post_model->get_post_revisions($post_id);
        
        $this->response([
            'status' => 'success',
            'data' => $revisions
        ], 200);
    }
}