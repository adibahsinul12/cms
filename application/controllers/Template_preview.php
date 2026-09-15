<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template_preview extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Template_model');
        $this->load->model('Option_model');
        $this->load->model('Post_model');
    }

    /**
     * [GET] /template/preview/(:num)
     * Render template di dalam iframe customizer
     */
    public function index($template_id = null) {
        if (!$template_id) {
            $active = $this->Template_model->get_active();
            $template_id = $active['id'] ?? 1;
        }

        $template = $this->Template_model->get_by_id($template_id);
        if (!$template) {
            show_404();
            return;
        }

        $options = $this->Template_model->get_options($template_id);
        $site_options = $this->Option_model->get_all();

        // Data dummy atau nyata untuk preview
        $posts = $this->Post_model->get_posts(10, 0, 'published');
        if (empty($posts)) {
            $posts = [
                [
                    'id' => 999,
                    'title' => 'Contoh Postingan Demo di Preview Template',
                    'slug' => 'contoh-post-demo',
                    'excerpt' => 'Ini adalah ringkasan konten demo yang otomatis tampil pada mode preview untuk memperlihatkan tata letak template.',
                    'content' => '<p>Konten lengkap artikel demo yang menguji tampilan paragraf, tipografi, dan perataan teks.</p>',
                    'category_name' => 'Demo',
                    'author_name' => 'Admin',
                    'created_at' => date('Y-m-d H:i:s'),
                    'featured_image_url' => null
                ]
            ];
        }

        $site_name = !empty($options['site_title']) ? $options['site_title'] : (!empty($site_options['site_name']) ? $site_options['site_name'] : 'Demo Template Preview');
        $site_description = !empty($options['site_tagline']) ? $options['site_tagline'] : (!empty($site_options['site_description']) ? $site_options['site_description'] : 'Slogan website untuk pratinjau');
        $footer_text = !empty($options['footer_text']) ? $options['footer_text'] : '© ' . date('Y') . ' ' . $site_name . '. All rights reserved.';

        $data = [
            'is_logged_in'     => true,
            'current_role'     => 1,
            'current_name'     => 'Preview Admin',
            'current_user_id'  => 1,
            'staff_roles'      => [1, 3, 4],
            'site_name'        => $site_name,
            'site_description' => $site_description,
            'footer_text'      => $footer_text,
            'active_theme'     => $template['folder'],
            'posts'            => $posts,
            'tpl_options'      => $options,
            'is_preview_mode'  => true,
        ];

        // Buffer tampilan template
        $view_file = "themes/{$template['folder']}/home";
        if (!file_exists(APPPATH . "views/{$view_file}.php")) {
            $view_file = 'home';
        }

        $output = $this->load->view($view_file, $data, true);

        // Inject script live listener postMessage agar customizer bisa ubah warna/font instan
        $injection = $this->generate_customizer_injection($options);
        $output = str_ireplace('</head>', $injection['head'] . '</head>', $output);
        $output = str_ireplace('</body>', $injection['body'] . '</body>', $output);

        $this->output->set_output($output);
    }

    private function generate_customizer_injection($options) {
        $primary = $options['color_primary'] ?? '#0D6EFD';
        $secondary = $options['color_secondary'] ?? '#6C757D';
        $font_heading = $options['font_heading'] ?? 'Plus Jakarta Sans';
        $font_body = $options['font_body'] ?? 'Inter';
        $custom_css = $options['custom_css'] ?? '';
        $custom_js = $options['custom_js'] ?? '';

        $head = "
        <link rel='preconnect' href='https://fonts.googleapis.com'>
        <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
        <link id='customizer-fonts' href='https://fonts.googleapis.com/css2?family=" . urlencode($font_heading) . ":wght@600;700;800&family=" . urlencode($font_body) . ":wght@400;500;600&display=swap' rel='stylesheet'>
        <style id='customizer-vars'>
            :root {
                --primary: {$primary} !important;
                --primary-color: {$primary} !important;
                --brand: {$primary} !important;
                --secondary: {$secondary} !important;
                --font-heading: '{$font_heading}', sans-serif !important;
                --font-body: '{$font_body}', sans-serif !important;
            }
            h1, h2, h3, h4, h5, h6, .hero h1, .section-head h2 {
                font-family: var(--font-heading) !important;
            }
            body, p, span, li, a {
                font-family: var(--font-body) !important;
            }
        </style>
        <style id='customizer-custom-css'>
            {$custom_css}
        </style>
        ";

        $body = "
        <script id='customizer-listener'>
            window.addEventListener('message', function(event) {
                if (!event.data || event.data.type !== 'CUSTOMIZER_UPDATE') return;
                const d = event.data.options;

                // Update CSS variables
                const styleEl = document.getElementById('customizer-vars');
                if (styleEl) {
                    styleEl.innerHTML = `
                        :root {
                            --primary: \${d.color_primary || '#0D6EFD'} !important;
                            --primary-color: \${d.color_primary || '#0D6EFD'} !important;
                            --brand: \${d.color_primary || '#0D6EFD'} !important;
                            --secondary: \${d.color_secondary || '#6C757D'} !important;
                            --font-heading: '\${d.font_heading || 'Plus Jakarta Sans'}', sans-serif !important;
                            --font-body: '\${d.font_body || 'Inter'}', sans-serif !important;
                        }
                        h1, h2, h3, h4, h5, h6, .hero h1, .section-head h2 {
                            font-family: var(--font-heading) !important;
                        }
                        body, p, span, li, a {
                            font-family: var(--font-body) !important;
                        }
                    `;
                }

                // Update Custom CSS
                const cssEl = document.getElementById('customizer-custom-css');
                if (cssEl && d.custom_css !== undefined) {
                    cssEl.innerHTML = d.custom_css;
                }

                // Update text elements if present
                if (d.site_title) {
                    document.querySelectorAll('.brand, .shop-logo, .cp-brand, .site-title').forEach(el => {
                        el.lastChild.textContent = ' ' + d.site_title;
                    });
                }
            });
        </script>
        <script id='customizer-custom-js'>
            {$custom_js}
        </script>
        ";

        return ['head' => $head, 'body' => $body];
    }
}

