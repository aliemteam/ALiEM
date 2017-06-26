<?php

$ROOT_URI = get_stylesheet_directory_uri();
define('ALIEM_VERSON', '0.4.0');

class ALiEM {
    public function __construct() {
        add_action('admin_enqueue_scripts', [$this, 'load_admin_scripts']);
        require_once(dirname(__FILE__) . '/lib/index.php');
        require_once(dirname(__FILE__) . '/lib/script-loader/script-loader.php');
        new \AliemScripts\Loader();
    }

    public function load_admin_scripts($hook) {
        if (in_array($hook, ['post-new.php', 'post.php', 'page-new.php', 'page.php'])) {
            wp_enqueue_style( 'aliem_admin_style', get_stylesheet_directory_uri() . '/admin.css' );
        }
    }
}

new ALiEM;
