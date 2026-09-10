<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class Settings extends Base_api {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * GET /api/settings
     * Ambil semua pengaturan
     */
    public function index_get() {
        $this->db->select('option_name, option_value');
        $query = $this->db->get('options');
        $result = $query->result_array();
        
        // Convert ke format key-value
        $data = [];
        foreach ($result as $row) {
            $data[$row['option_name']] = $row['option_value'];
        }
        
        $this->response([
            'status' => 'success',
            'data' => $data
        ], 200);
    }
    
    /**
     * POST /api/settings
     * Simpan pengaturan
     */
    public function index_post() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $this->response([
                'status' => 'error',
                'message' => 'No data provided'
            ], 400);
            return;
        }
        
        foreach ($input as $key => $value) {
            // Cek apakah option udah ada
            $this->db->where('option_name', $key);
            $exists = $this->db->get('options')->row_array();
            
            if ($exists) {
                // Update
                $this->db->where('option_name', $key);
                $this->db->update('options', ['option_value' => $value]);
            } else {
                // Insert
                $this->db->insert('options', [
                    'option_name' => $key,
                    'option_value' => $value
                ]);
            }
        }
        
        $this->response([
            'status' => 'success',
            'message' => 'Settings saved successfully'
        ], 200);
    }
}