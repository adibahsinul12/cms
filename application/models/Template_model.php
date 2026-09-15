<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template_model extends CI_Model {

    protected $themes_path;

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->themes_path = APPPATH . 'views/themes/';
    }

    public function get_themes_path() {
        return $this->themes_path;
    }

    /**
     * Ambil semua template dari database
     */
    public function get_all($category = null) {
        $this->db->order_by('is_active', 'DESC');
        $this->db->order_by('id', 'ASC');
        $templates = $this->db->get('templates')->result_array();

        // Enrich dengan metadata dari theme.json jika ada
        foreach ($templates as &$tpl) {
            $meta = $this->get_theme_meta($tpl['folder']);
            $tpl['category'] = $meta['category'] ?? 'Umum';
            $tpl['features'] = $meta['features'] ?? [];
            $tpl['options_count'] = $this->count_options($tpl['id']);
        }

        if ($category && $category !== 'all') {
            $templates = array_values(array_filter($templates, function($t) use ($category) {
                return strtolower($t['category']) === strtolower($category);
            }));
        }

        return $templates;
    }

    public function get_by_id($id) {
        $this->db->where('id', $id);
        $tpl = $this->db->get('templates')->row_array();
        if ($tpl) {
            $tpl['meta'] = $this->get_theme_meta($tpl['folder']);
        }
        return $tpl;
    }

    public function get_by_slug($slug) {
        $this->db->where('slug', $slug);
        $tpl = $this->db->get('templates')->row_array();
        if ($tpl) {
            $tpl['meta'] = $this->get_theme_meta($tpl['folder']);
        }
        return $tpl;
    }

    public function get_active() {
        $this->db->where('is_active', 1);
        $tpl = $this->db->get('templates')->row_array();
        if (!$tpl) {
            // fallback default
            $this->db->where('is_default', 1);
            $tpl = $this->db->get('templates')->row_array();
        }
        if ($tpl) {
            $tpl['meta'] = $this->get_theme_meta($tpl['folder']);
        }
        return $tpl;
    }

    /**
     * Mengaktifkan template
     */
    public function activate($id) {
        $target = $this->get_by_id($id);
        if (!$target) {
            return false;
        }

        $this->db->trans_start();

        // 1. Set semua is_active = 0
        $this->db->update('templates', ['is_active' => 0]);

        // 2. Set target is_active = 1
        $this->db->where('id', $id)->update('templates', ['is_active' => 1]);

        // 3. Update active_theme di options
        $this->load->model('Option_model');
        $this->Option_model->set('active_theme', $target['slug']);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    /**
     * Membaca metadata theme.json
     */
    public function get_theme_meta($folder) {
        $file = $this->themes_path . $folder . '/theme.json';
        if (file_exists($file)) {
            $content = file_get_contents($file);
            return json_decode($content, true) ?: [];
        }
        return [];
    }

    /**
     * Ambil opsi template (merger default theme.json + template_options DB)
     */
    public function get_options($template_id) {
        $tpl = $this->get_by_id($template_id);
        if (!$tpl) return [];

        $defaults = $tpl['meta']['default_options'] ?? [];

        // Ambil dari DB
        $this->db->where('template_id', $template_id);
        $rows = $this->db->get('template_options')->result_array();

        $saved = [];
        foreach ($rows as $r) {
            $saved[$r['option_key']] = $r['option_value'];
        }

        // Gabungkan defaults dengan saved
        return array_merge($defaults, $saved);
    }

    /**
     * Simpan opsi template
     */
    public function save_options($template_id, array $options) {
        $this->db->trans_start();

        foreach ($options as $key => $value) {
            if ($value === null) continue;

            $this->db->where('template_id', $template_id);
            $this->db->where('option_key', $key);
            $exists = $this->db->get('template_options')->row_array();

            if ($exists) {
                $this->db->where('id', $exists['id']);
                $this->db->update('template_options', ['option_value' => $value]);
            } else {
                $this->db->insert('template_options', [
                    'template_id'  => $template_id,
                    'option_key'   => $key,
                    'option_value' => $value
                ]);
            }
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * Reset opsi template ke bawaan theme.json
     */
    public function reset_options($template_id) {
        $this->db->where('template_id', $template_id);
        return $this->db->delete('template_options');
    }

    public function count_options($template_id) {
        $this->db->where('template_id', $template_id);
        return $this->db->count_all_results('template_options');
    }

    /**
     * File Manager untuk Code Editor
     */
    public function get_files($template_id) {
        $tpl = $this->get_by_id($template_id);
        if (!$tpl) return [];

        $dir = $this->themes_path . $tpl['folder'];
        if (!is_dir($dir)) return [];

        return $this->scan_dir_tree($dir, $dir);
    }

    private function scan_dir_tree($base_dir, $current_dir) {
        $result = [];
        $items = scandir($current_dir);

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;

            $full_path = $current_dir . DIRECTORY_SEPARATOR . $item;
            $rel_path = str_replace($base_dir . DIRECTORY_SEPARATOR, '', $full_path);
            $rel_path = str_replace('\\', '/', $rel_path);

            if (is_dir($full_path)) {
                $result[] = [
                    'name'     => $item,
                    'path'     => $rel_path,
                    'type'     => 'directory',
                    'children' => $this->scan_dir_tree($base_dir, $full_path)
                ];
            } else {
                $ext = pathinfo($item, PATHINFO_EXTENSION);
                $result[] = [
                    'name'      => $item,
                    'path'      => $rel_path,
                    'type'      => 'file',
                    'extension' => strtolower($ext),
                    'size'      => filesize($full_path)
                ];
            }
        }

        return $result;
    }

    /**
     * Baca file template dengan proteksi Path Traversal
     */
    public function get_file_content($template_id, $rel_path) {
        $tpl = $this->get_by_id($template_id);
        if (!$tpl) return false;

        $base = realpath($this->themes_path . $tpl['folder']);
        $file = realpath($this->themes_path . $tpl['folder'] . '/' . $rel_path);

        if (!$file || strpos($file, $base) !== 0 || !file_exists($file)) {
            return false;
        }

        return [
            'file_path' => $rel_path,
            'content'   => file_get_contents($file),
            'extension' => strtolower(pathinfo($file, PATHINFO_EXTENSION))
        ];
    }

    /**
     * Simpan file template + buat catatan di template_revisions
     */
    public function save_file_content($template_id, $rel_path, $content, $revised_by) {
        $tpl = $this->get_by_id($template_id);
        if (!$tpl) return false;

        $dir = $this->themes_path . $tpl['folder'];
        $file = $dir . '/' . ltrim($rel_path, '/\\');

        // Pastikan direktori ada
        $parent = dirname($file);
        if (!is_dir($parent)) {
            mkdir($parent, 0755, true);
        }

        // Tulis file fisik
        if (file_put_contents($file, $content) === false) {
            return false;
        }

        // Simpan riwayat revisi ke tabel template_revisions
        $this->db->insert('template_revisions', [
            'template_id' => $template_id,
            'file_path'   => $rel_path,
            'content'     => $content,
            'revised_by'  => $revised_by,
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        return true;
    }

    /**
     * Riwayat revisi
     */
    public function get_revisions($template_id, $rel_path = null) {
        $this->db->select('template_revisions.*, users.full_name as revised_by_name, users.username');
        $this->db->from('template_revisions');
        $this->db->join('users', 'users.id = template_revisions.revised_by', 'left');
        $this->db->where('template_revisions.template_id', $template_id);

        if ($rel_path) {
            $this->db->where('template_revisions.file_path', $rel_path);
        }

        $this->db->order_by('template_revisions.created_at', 'DESC');
        $this->db->limit(30);
        return $this->db->get()->result_array();
    }

    public function get_revision($revision_id) {
        $this->db->select('template_revisions.*, users.full_name as revised_by_name');
        $this->db->from('template_revisions');
        $this->db->join('users', 'users.id = template_revisions.revised_by', 'left');
        $this->db->where('template_revisions.id', $revision_id);
        return $this->db->get()->row_array();
    }

    /**
     * Restore file dari revisi
     */
    public function restore_revision($revision_id, $user_id) {
        $rev = $this->get_revision($revision_id);
        if (!$rev) return false;

        return $this->save_file_content(
            $rev['template_id'],
            $rev['file_path'],
            $rev['content'],
            $user_id
        );
    }

    /**
     * Duplikasi template
     */
    public function duplicate($template_id, $new_name, $new_slug) {
        $tpl = $this->get_by_id($template_id);
        if (!$tpl) return false;

        $source_dir = $this->themes_path . $tpl['folder'];
        $dest_folder = $new_slug;
        $dest_dir = $this->themes_path . $dest_folder;

        if (is_dir($dest_dir)) return false;

        // Copy recursive
        $this->copy_dir($source_dir, $dest_dir);

        // Update theme.json jika ada
        $meta_file = $dest_dir . '/theme.json';
        if (file_exists($meta_file)) {
            $meta = json_decode(file_get_contents($meta_file), true) ?: [];
            $meta['name'] = $new_name;
            $meta['slug'] = $new_slug;
            file_put_contents($meta_file, json_encode($meta, JSON_PRETTY_PRINT));
        }

        // Insert ke DB
        $data = [
            'name'        => $new_name,
            'slug'        => $new_slug,
            'author'      => $this->session->userdata('full_name') ?: 'User',
            'version'     => '1.0.0',
            'description' => 'Salinan dari ' . $tpl['name'],
            'screenshot'  => $tpl['screenshot'],
            'folder'      => $dest_folder,
            'is_active'   => 0,
            'is_default'  => 0,
        ];
        $this->db->insert('templates', $data);
        $new_id = $this->db->insert_id();

        // Copy template_options
        $options = $this->get_options($template_id);
        if (!empty($options)) {
            $this->save_options($new_id, $options);
        }

        return $new_id;
    }

    private function copy_dir($src, $dst) {
        $dir = opendir($src);
        @mkdir($dst, 0755, true);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->copy_dir($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    /**
     * Hapus template (proteksi template aktif dan default)
     */
    public function delete_template($template_id) {
        $tpl = $this->get_by_id($template_id);
        if (!$tpl) return ['status' => false, 'message' => 'Template tidak ditemukan'];
        if ($tpl['is_active']) return ['status' => false, 'message' => 'Template sedang aktif, tidak dapat dihapus'];
        if ($tpl['is_default']) return ['status' => false, 'message' => 'Template default sistem tidak dapat dihapus'];

        $dir = $this->themes_path . $tpl['folder'];
        if (is_dir($dir)) {
            $this->delete_dir($dir);
        }

        $this->db->where('id', $template_id)->delete('templates');
        return ['status' => true, 'message' => 'Template berhasil dihapus'];
    }

    private function delete_dir($dirPath) {
        if (!is_dir($dirPath)) return;
        $files = scandir($dirPath);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $full = "$dirPath/$file";
                if (is_dir($full)) {
                    $this->delete_dir($full);
                } else {
                    unlink($full);
                }
            }
        }
        rmdir($dirPath);
    }

    /**
     * Export template ke file ZIP
     */
    public function export_zip($template_id) {
        $tpl = $this->get_by_id($template_id);
        if (!$tpl) return false;

        $dir = realpath($this->themes_path . $tpl['folder']);
        if (!$dir || !is_dir($dir)) return false;

        $zip_file = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'theme_' . $tpl['slug'] . '_' . time() . '.zip';
        $zip = new ZipArchive();

        if ($zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return false;
        }

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($dir) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();
        return file_exists($zip_file) ? $zip_file : false;
    }

    /**
     * Import template dari file ZIP yang diupload
     */
    public function import_zip($zip_path) {
        if (!class_exists('ZipArchive') || !file_exists($zip_path)) {
            return ['status' => false, 'message' => 'File ZIP tidak valid'];
        }

        $zip = new ZipArchive();
        if ($zip->open($zip_path) !== true) {
            return ['status' => false, 'message' => 'Gagal membuka file ZIP'];
        }

        // Cari theme.json untuk verifikasi
        $theme_json_content = null;
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            if (basename($filename) === 'theme.json') {
                $theme_json_content = $zip->getFromIndex($i);
                break;
            }
        }

        if (!$theme_json_content) {
            $zip->close();
            return ['status' => false, 'message' => 'File ZIP harus berisi berkas theme.json valid'];
        }

        $meta = json_decode($theme_json_content, true);
        if (!$meta || empty($meta['name']) || empty($meta['slug'])) {
            $zip->close();
            return ['status' => false, 'message' => 'Metadata theme.json tidak lengkap (wajib ada name & slug)'];
        }

        $slug = preg_replace('/[^a-z0-9_-]/', '', strtolower($meta['slug']));
        $folder = $slug;
        $dest_dir = $this->themes_path . $folder;

        // Cek jika slug sudah ada
        if (is_dir($dest_dir)) {
            $folder = $slug . '_' . time();
            $dest_dir = $this->themes_path . $folder;
        }

        mkdir($dest_dir, 0755, true);
        $zip->extractTo($dest_dir);
        $zip->close();

        // Daftarkan ke DB
        $data = [
            'name'        => $meta['name'],
            'slug'        => $slug,
            'author'      => $meta['author'] ?? 'Imported',
            'version'     => $meta['version'] ?? '1.0.0',
            'description' => $meta['description'] ?? 'Template hasil import',
            'screenshot'  => $meta['screenshot'] ?? null,
            'folder'      => $folder,
            'is_active'   => 0,
            'is_default'  => 0,
        ];
        $this->db->insert('templates', $data);
        $new_id = $this->db->insert_id();

        // Simpan default_options jika ada
        if (!empty($meta['default_options'])) {
            $this->save_options($new_id, $meta['default_options']);
        }

        return ['status' => true, 'message' => 'Template berhasil diimport', 'id' => $new_id];
    }
}

