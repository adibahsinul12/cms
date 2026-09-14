<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sitemap extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->model('Option_model');
    }

    public function index() {
        // Cek apakah fitur sitemap diaktifkan di SEO Settings
        $enable_sitemap = $this->Option_model->get('enable_sitemap', 1);
        if ($enable_sitemap == '0') {
            show_error('Sitemap dinonaktifkan oleh Administrator.', 404, 'Sitemap Disabled');
            return;
        }

        // Ambil semua artikel yang statusnya 'published'
        $this->db->select('id, slug, updated_at, created_at');
        $this->db->where('status', 'published');
        $this->db->order_by('created_at', 'DESC');
        $posts = $this->db->get('posts')->result_array();

        // Buat konten XML
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // 1. Halaman Utama (Homepage)
        $xml .= "  <url>\n";
        $xml .= "    <loc>" . htmlspecialchars(base_url('home')) . "</loc>\n";
        $xml .= "    <changefreq>daily</changefreq>\n";
        $xml .= "    <priority>1.0</priority>\n";
        $xml .= "  </url>\n";

        // 2. Setiap artikel berita yang sudah terbit
        foreach ($posts as $post) {
            $lastmod = !empty($post['updated_at']) ? date('Y-m-d', strtotime($post['updated_at'])) : date('Y-m-d', strtotime($post['created_at']));
            $loc = base_url('post/' . ($post['slug'] ? $post['slug'] : $post['id']));

            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($loc) . "</loc>\n";
            $xml .= "    <lastmod>" . $lastmod . "</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.8</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        // Jika request download
        if ($this->input->get('download') == '1') {
            header('Content-Disposition: attachment; filename="sitemap.xml"');
        }

        header('Content-Type: text/xml; charset=utf-8');
        echo $xml;
        exit;
    }
}

