<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class Media extends Base_api {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Media_model');
    }
    
    public function index_get() {
        $this->response(['status' => 'success', 'data' => $this->Media_model->get_all()], 200);
    }
    
    public function detail_get($id) {
        $media = $this->Media_model->get_by_id($id);
        if (!$media) {
            $this->response(['status' => 'error', 'message' => 'Media not found'], 404);
            return;
        }
        $this->response(['status' => 'success', 'data' => $media], 200);
    }
    
    public function upload_post() {
        if (empty($_FILES['files']['name'][0])) {
            $this->response(['status' => 'error', 'message' => 'No files uploaded'], 400);
            return;
        }
        
        $upload_path = FCPATH . 'uploads/media/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, TRUE);
        }
        
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc|docx|xls|xlsx|zip';
        $config['max_size'] = 10240;
        $this->load->library('upload', $config);
        
        $uploaded = [];
        $files = $_FILES['files'];
        $count = count($files['name']);
        
        for ($i = 0; $i < $count; $i++) {
            $_FILES['file']['name'] = $files['name'][$i];
            $_FILES['file']['type'] = $files['type'][$i];
            $_FILES['file']['tmp_name'] = $files['tmp_name'][$i];
            $_FILES['file']['error'] = $files['error'][$i];
            $_FILES['file']['size'] = $files['size'][$i];
            
            if ($this->upload->do_upload('file')) {
                $data = $this->upload->data();
                
                $sql = "CALL sp_upload_media(?, ?, ?, ?, ?, ?, ?)";
                $query = $this->db->query($sql, [
                    1, // uploaded_by sementara
                    $data['file_name'],
                    'uploads/media/' . $data['file_name'],
                    $data['file_type'],
                    $data['file_size'],
                    null,
                    null
                ]);
                
                $result = $query->row_array();
                $query->next_result();
                $query->free_result();
                
                $uploaded[] = [
                    'id' => $result['media_id'] ?? null,
                    'file_name' => $data['file_name'],
                    'file_path' => 'uploads/media/' . $data['file_name']
                ];
            }
        }
        
        $this->response(['status' => 'success', 'message' => 'Files uploaded', 'data' => $uploaded], 201);
    }
    
    public function update_put($id) {
        $existing = $this->Media_model->get_by_id($id);
        if (!$existing) {
            $this->response(['status' => 'error', 'message' => 'Media not found'], 404);
            return;
        }
        
        $data = [
            'alt_text' => $this->put('alt_text'),
            'caption' => $this->put('caption')
        ];
        
        $this->Media_model->update($id, $data);
        $this->response(['status' => 'success', 'message' => 'Media updated'], 200);
    }
    
    public function delete_delete($id) {
        $existing = $this->Media_model->get_by_id($id);
        if (!$existing) {
            $this->response(['status' => 'error', 'message' => 'Media not found'], 404);
            return;
        }
        
        $file_path = FCPATH . $existing['file_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        
        $this->Media_model->delete($id);
        $this->response(['status' => 'success', 'message' => 'Media deleted'], 200);
    }
}