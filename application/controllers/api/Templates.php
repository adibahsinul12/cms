<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Base_api.php';

class Templates extends Base_api {

    public function __construct() {
        parent::__construct();
        $this->load->model('Template_model');
        // Hanya Admin (role 1) yang boleh mengelola template sistem secara penuh
        $this->require_role([1]);
    }

    // [GET] /api/templates
    public function index() {
        $category = $this->input->get('category');
        $templates = $this->Template_model->get_all($category);
        $this->response_success($templates, 'OK', 200);
    }

    // [GET] /api/templates/detail/(:num)
    public function detail($id = null) {
        if (!$id) {
            $this->response_error('ID Template wajib disertakan', 400);
            return;
        }

        $template = $this->Template_model->get_by_id($id);
        if (!$template) {
            $this->response_error('Template tidak ditemukan', 404);
            return;
        }

        $template['options'] = $this->Template_model->get_options($id);
        $this->response_success($template, 'OK', 200);
    }

    // [POST] /api/templates/activate/(:num)
    public function activate($id = null) {
        if (!$id) {
            $this->response_error('ID Template wajib disertakan', 400);
            return;
        }

        $res = $this->Template_model->activate($id);
        if ($res) {
            $this->response_success(null, 'Template berhasil diaktifkan!', 200);
        } else {
            $this->response_error('Gagal mengaktifkan template', 500);
        }
    }

    // [GET/POST] /api/templates/options/(:num)
    public function options($id = null) {
        if (!$id) {
            $this->response_error('ID Template wajib disertakan', 400);
            return;
        }

        if (strtoupper($this->input->server('REQUEST_METHOD')) === 'POST' || $this->input->method() === 'post') {
            $raw_input = file_get_contents('php://input');
            $options = json_decode($raw_input, true) ?: $this->input->post();

            if (!is_array($options)) {
                $this->response_error('Data opsi tidak valid', 400);
                return;
            }

            $ok = $this->Template_model->save_options($id, $options);
            if ($ok) {
                $this->response_success($options, 'Opsi kustomisasi template berhasil disimpan & dipublikasikan!', 200);
            } else {
                $this->response_error('Gagal menyimpan opsi template', 500);
            }
            return;
        }

        $options = $this->Template_model->get_options($id);
        $this->response_success($options, 'OK', 200);
    }

    // [POST] /api/templates/reset_options/(:num)
    public function reset_options($id = null) {
        if (!$id) {
            $this->response_error('ID Template wajib disertakan', 400);
            return;
        }

        $this->Template_model->reset_options($id);
        $defaults = $this->Template_model->get_options($id);
        $this->response_success($defaults, 'Opsi template berhasil direset ke pengaturan bawaan!', 200);
    }

    // [GET] /api/templates/files/(:num)
    public function files($id = null) {
        if (!$id) {
            $this->response_error('ID Template wajib disertakan', 400);
            return;
        }

        $tree = $this->Template_model->get_files($id);
        $this->response_success($tree, 'OK', 200);
    }

    // [GET] /api/templates/file_content?template_id=X&file_path=Y
    public function file_content() {
        $template_id = $this->input->get('template_id');
        $file_path   = $this->input->get('file_path');

        if (!$template_id || !$file_path) {
            $this->response_error('template_id dan file_path wajib disertakan', 400);
            return;
        }

        $res = $this->Template_model->get_file_content($template_id, $file_path);
        if ($res === false) {
            $this->response_error('File tidak ditemukan atau akses ditolak', 404);
            return;
        }

        $this->response_success($res, 'OK', 200);
    }

    // [POST] /api/templates/save_file
    public function save_file() {
        $raw_input = file_get_contents('php://input');
        $data = json_decode($raw_input, true) ?: $this->input->post();

        $template_id = $data['template_id'] ?? null;
        $file_path   = $data['file_path'] ?? null;
        $content     = $data['content'] ?? null;

        if (!$template_id || !$file_path || $content === null) {
            $this->response_error('template_id, file_path, dan content wajib diisi', 400);
            return;
        }

        $user_id = $this->current_user['id'];
        $ok = $this->Template_model->save_file_content($template_id, $file_path, $content, $user_id);

        if ($ok) {
            $this->response_success(null, 'File berhasil disimpan dan revisi baru telah dibuat!', 200);
        } else {
            $this->response_error('Gagal menyimpan file', 500);
        }
    }

    // [GET] /api/templates/revisions/(:num)
    public function revisions($id = null) {
        if (!$id) {
            $this->response_error('ID Template wajib disertakan', 400);
            return;
        }

        $file_path = $this->input->get('file_path');
        $revs = $this->Template_model->get_revisions($id, $file_path);
        $this->response_success($revs, 'OK', 200);
    }

    // [POST] /api/templates/restore_revision/(:num)
    public function restore_revision($rev_id = null) {
        if (!$rev_id) {
            $this->response_error('ID Revisi wajib disertakan', 400);
            return;
        }

        $user_id = $this->current_user['id'];
        $ok = $this->Template_model->restore_revision($rev_id, $user_id);

        if ($ok) {
            $this->response_success(null, 'File berhasil direstore ke versi revisi tersebut!', 200);
        } else {
            $this->response_error('Gagal merestore revisi', 500);
        }
    }

    // [POST] /api/templates/duplicate/(:num)
    public function duplicate($id = null) {
        if (!$id) {
            $this->response_error('ID Template wajib disertakan', 400);
            return;
        }

        $raw_input = file_get_contents('php://input');
        $data = json_decode($raw_input, true) ?: $this->input->post();

        $new_name = trim($data['name'] ?? '');
        $new_slug = preg_replace('/[^a-z0-9_-]/', '', strtolower($data['slug'] ?? ''));

        if (!$new_name || !$new_slug) {
            $this->response_error('Nama baru dan slug baru wajib diisi!', 400);
            return;
        }

        $new_id = $this->Template_model->duplicate($id, $new_name, $new_slug);
        if ($new_id) {
            $this->response_success(['id' => $new_id], 'Template berhasil digandakan!', 201);
        } else {
            $this->response_error('Gagal menggandakan template (mungkin slug sudah ada)', 400);
        }
    }

    // [DELETE] /api/templates/delete/(:num)
    public function delete($id = null) {
        if (!$id) {
            $this->response_error('ID Template wajib disertakan', 400);
            return;
        }

        $res = $this->Template_model->delete_template($id);
        if ($res['status']) {
            $this->response_success(null, $res['message'], 200);
        } else {
            $this->response_error($res['message'], 400);
        }
    }

    // [GET] /api/templates/export/(:num)
    public function export($id = null) {
        if (!$id) {
            $this->response_error('ID Template wajib disertakan', 400);
            return;
        }

        $template = $this->Template_model->get_by_id($id);
        if (!$template) {
            $this->response_error('Template tidak ditemukan', 404);
            return;
        }

        $zip_path = $this->Template_model->export_zip($id);
        if (!$zip_path || !file_exists($zip_path)) {
            $this->response_error('Gagal membuat berkas ZIP template', 500);
            return;
        }

        $filename = 'theme_' . $template['slug'] . '.zip';
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($zip_path));
        header('Pragma: no-cache');
        readfile($zip_path);
        @unlink($zip_path);
        exit;
    }

    // [POST] /api/templates/import
    public function import() {
        if (empty($_FILES['theme_zip']['name'])) {
            $this->response_error('File ZIP template wajib diupload', 400);
            return;
        }

        $file = $_FILES['theme_zip']['tmp_name'];
        $res = $this->Template_model->import_zip($file);

        if ($res['status']) {
            $this->response_success(['id' => $res['id']], $res['message'], 201);
        } else {
            $this->response_error($res['message'], 400);
        }
    }
}

