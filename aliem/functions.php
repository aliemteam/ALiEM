<?php

function aliem_client_scripts() {
    wp_enqueue_script('printfriendly', 'https://pf-cdn.printfriendly.com/ssl/main.js');
    wp_enqueue_style('aliem', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'aliem_client_scripts', 999);

function aliem_admin_scripts($hook) {
    if (in_array($hook, ['post-new.php', 'post.php', 'page-new.php', 'page.php'])) {
        wp_enqueue_style( 'aliem_admin_style', get_stylesheet_directory_uri() . '/admin.css' );
    }
}
add_action('admin_enqueue_scripts', 'aliem_admin_scripts');

require_once(dirname(__FILE__) . '/lib/index.php');
