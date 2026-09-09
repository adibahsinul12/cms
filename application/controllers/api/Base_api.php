<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base API Controller - Semua API Controller extend dari sini
 */
class Base_api extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        // Set header JSON untuk semua response
        header('Content-Type: application/json');
        
        // Enable CORS untuk development
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        
        // Handle preflight OPTIONS request
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }
    
    /**
     * Kirim JSON response
     */
    protected function response($data, $code = 200) {
        http_response_code($code);
        echo json_encode($data);
        exit;
    }
    
    /**
     * Format response sukses
     */
    protected function response_success($data = null, $message = 'Success', $code = 200) {
        $this->response([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }
    
    /**
     * Format response error
     */
    protected function response_error($message = 'Error', $code = 400, $errors = null) {
        $response = [
            'status' => 'error',
            'message' => $message
        ];
        
        if ($errors) {
            $response['errors'] = $errors;
        }
        
        $this->response($response, $code);
    }
}