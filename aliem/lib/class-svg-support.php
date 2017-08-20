<?php

class SVGSupport {
    public function __construct() {
        add_action('admin_init', [$this, 'enable_svg']);
        add_action('after_setup_theme', [$this, 'theme_logo_support'], 99);
    }

    public function theme_logo_support() {
        $existing = get_theme_support('custom-logo');
        if ($existing) {
            $existing = current($existing);
            $existing['flex-width'] = true;
            $existing['flex-height'] = true;
            add_theme_support('custom-logo', $existing);
        }
    }

    public function enable_svg() {
        ob_start();
        add_action('wp_ajax_adminlc_mce_svg.css', [$this, 'enable_media_gallery_render']);
        add_filter('image_send_to_editor', [$this, 'set_default_dimensions'], 10);
        add_filter('upload_mimes', [$this, 'enable_svg_mime']);
        add_action('shutdown', [$this, 'on_shutdown'], 0);
        add_filter('final_output', [$this, 'postfix']);
    }

    public function enable_media_gallery_render() {
        header('Content-type: text/css');
        echo 'img[src$=".svg"] { width: 100%; height: auto; }';
        wp_die();
    }

    public function set_default_dimensions($html = '') {
        return str_ireplace([' width="1"', ' height="1"'], [' width="200"', ''], $html);
    }

    public function enable_svg_mime($mimes = []) {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }

    public function on_shutdown() {
        $final = '';
        $ob_levels = count(ob_get_level());
        for ($i = 0; $i < $ob_levels; $i++) {
            $final .= ob_get_clean();
        }
        echo apply_filters('final_output', $final);
    }

    public function postfix($content = '') {
        $content = str_replace(
            '<# } else if ( \'image\' === data.type && data.sizes && data.sizes.full ) { #>',
            '<# } else if ( \'svg+xml\' === data.subtype ) { #>
                <img class="details-image" src="{{ data.url }}" draggable="false" />
            <# } else if ( \'image\' === data.type && data.sizes && data.sizes.full ) { #>',
            $content
        );
        $content = str_replace(
            '<# } else if ( \'image\' === data.type && data.sizes ) { #>',
            '<# } else if ( \'svg+xml\' === data.subtype ) { #>
                <div class="centered">
                    <img src="{{ data.url }}" class="thumbnail" draggable="false" />
                </div>
            <# } else if ( \'image\' === data.type && data.sizes ) { #>',
            $content
        );
        return $content;
    }
}
new SVGSupport;
