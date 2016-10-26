<?php

function aliem_scripts() {
    wp_enqueue_script('printfriendly', 'https://pf-cdn.printfriendly.com/ssl/main.js');
    wp_enqueue_style('aliem', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'aliem_scripts', 999);

require_once(dirname(__FILE__) . '/legacy.php');
require_once(dirname(__FILE__) . '/shortcodes.php');
require_once(dirname(__FILE__) . '/misc.php');
require_once(dirname(__FILE__) . '/widgets.php');
require_once(dirname(__FILE__) . '/avada.php');
