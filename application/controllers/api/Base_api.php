<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base API Controller - Semua API Controller extend dari sini
 *
 * PENTING: Class ini sendiri TIDAK otomatis memblokir akses.
 * Tiap controller turunan WAJIB memanggil require_login() atau
 * require_role([...]) di constructor-nya masing-masing, KECUALI
 * controller yang memang sengaja publik (mis. Auth::login/register,
 * Public_posts).
 */
class Base_api extends CI_Controller {

    protected $current_user = null;

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

        // Library session dibutuhkan oleh require_login/require_role
        $this->load->library('session');
    }

    /**
     * Panggil di constructor controller yang WAJIB login.
     * Kalau belum login, langsung stop request dengan 401.
     */
    protected function require_login() {
        if (!$this->session->userdata('logged_in')) {
            $this->response_error('Anda harus login terlebih dahulu.', 401);
        }

        $this->current_user = [
            'id'      => $this->session->userdata('user_id'),
            'role_id' => $this->session->userdata('role_id'),
        ];
    }

    /**
     * Panggil di constructor controller yang WAJIB login DAN
     * dibatasi role tertentu. Otomatis memanggil require_login()
     * terlebih dahulu.
     *
     * Contoh:
     *   $this->require_role([1]);       // hanya Admin
     *   $this->require_role([1, 3]);    // Admin & Editor
     *   $this->require_role([1, 3, 4]); // Admin, Editor, Author
     *
     * Sesuaikan angka role_id dengan isi tabel `roles` di database kamu.
     */
    protected function require_role(array $allowed_role_ids) {
        $this->require_login();

        if (!in_array((int) $this->current_user['role_id'], $allowed_role_ids, true)) {
            $this->response_error('Anda tidak punya akses ke resource ini.', 403);
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
            'status'  => 'success',
            'message' => $message,
            'data'    => $data
        ], $code);
    }

    /**
     * Format response error
     */
    protected function response_error($message = 'Error', $code = 400, $errors = null) {
        $response = [
            'status'  => 'error',
            'message' => $message
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        $this->response($response, $code);
    }
}