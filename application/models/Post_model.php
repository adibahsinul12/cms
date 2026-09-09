<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * CREATE POST - Panggil Stored Procedure sp_create_post
     */
    public function create_post($data) {
        $author_id = $data['author_id'];
        $type = $data['type'] ?? 'post';
        $title = $data['title'];
        $slug = $data['slug'];
        $content = $data['content'] ?? '';
        $excerpt = $data['excerpt'] ?? '';
        $status = $data['status'] ?? 'draft';
        $scheduled_at = $data['scheduled_at'] ?? null;
        $category_id = $data['category_id'] ?? null;
        $tag_id = $data['tag_id'] ?? null;
        $featured_image_id = $data['featured_image_id'] ?? null;
        
        $sql = "CALL sp_create_post(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $query = $this->db->query($sql, [
            $author_id,
            $featured_image_id,
            $type,
            $title,
            $slug,
            $content,
            $excerpt,
            $status,
            $scheduled_at,
            $category_id,
            $tag_id
        ]);
        
        $result = $query->row_array();
        $query->next_result();
        $query->free_result();
        
        return $result['created_post_id'] ?? false;
    }
    
    /**
     * READ POST - Get all posts
     */
    public function get_posts($limit = null, $offset = 0, $status = null) {
        $this->db->select('
            posts.*,
            users.full_name as author_name,
            categories.name as category_name,
            tags.name as tag_name,
            media.file_path as featured_image_url
        ');
        $this->db->from('posts');
        $this->db->join('users', 'users.id = posts.author_id', 'left');
        $this->db->join('post_categories', 'post_categories.post_id = posts.id', 'left');
        $this->db->join('categories', 'categories.id = post_categories.category_id', 'left');
        $this->db->join('post_tags', 'post_tags.post_id = posts.id', 'left');
        $this->db->join('tags', 'tags.id = post_tags.tag_id', 'left');
        $this->db->join('media', 'media.id = posts.featured_image_id', 'left');
        
        if ($status) {
            $this->db->where('posts.status', $status);
        }
        
        $this->db->order_by('posts.created_at', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * READ POST - Get single post by ID
     */
    public function get_post_by_id($post_id) {
        $this->db->select('
            posts.*,
            users.full_name as author_name,
            categories.name as category_name,
            tags.name as tag_name,
            media.file_path as featured_image_url
        ');
        $this->db->from('posts');
        $this->db->join('users', 'users.id = posts.author_id', 'left');
        $this->db->join('post_categories', 'post_categories.post_id = posts.id', 'left');
        $this->db->join('categories', 'categories.id = post_categories.category_id', 'left');
        $this->db->join('post_tags', 'post_tags.post_id = posts.id', 'left');
        $this->db->join('tags', 'tags.id = post_tags.tag_id', 'left');
        $this->db->join('media', 'media.id = posts.featured_image_id', 'left');
        $this->db->where('posts.id', $post_id);
        
        $query = $this->db->get();
        return $query->row_array();
    }
    
    /**
     * UPDATE POST
     */
    public function update_post($post_id, $data) {
        $update_data = [
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'],
            'excerpt' => $data['excerpt'] ?? '',
            'status' => $data['status'] ?? 'draft',
            'scheduled_at' => $data['scheduled_at'] ?? null,
            'featured_image_id' => $data['featured_image_id'] ?? null,
        ];
        
        $this->db->where('id', $post_id);
        $result = $this->db->update('posts', $update_data);
        
        if (isset($data['category_id']) && $data['category_id']) {
            $this->db->where('post_id', $post_id)->delete('post_categories');
            $this->db->insert('post_categories', [
                'post_id' => $post_id,
                'category_id' => $data['category_id']
            ]);
        }
        
        if (isset($data['tag_id']) && $data['tag_id']) {
            $this->db->where('post_id', $post_id)->delete('post_tags');
            $this->db->insert('post_tags', [
                'post_id' => $post_id,
                'tag_id' => $data['tag_id']
            ]);
        }
        
        return $result;
    }
    
    /**
     * DELETE POST
     */
    public function delete_post($post_id) {
        $this->db->where('id', $post_id);
        return $this->db->delete('posts');
    }
    
    /**
     * GET POST REVISIONS
     */
    public function get_post_revisions($post_id) {
        $this->db->select('
            post_revisions.*,
            users.full_name as revised_by_name
        ');
        $this->db->from('post_revisions');
        $this->db->join('users', 'users.id = post_revisions.revised_by', 'left');
        $this->db->where('post_revisions.post_id', $post_id);
        $this->db->order_by('post_revisions.created_at', 'DESC');
        
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * COUNT POSTS
     */
    public function count_posts($status = null) {
        if ($status) {
            $this->db->where('status', $status);
        }
        return $this->db->count_all_results('posts');
    }
}