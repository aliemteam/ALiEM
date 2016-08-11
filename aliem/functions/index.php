<?php

function aliem_js() {
    wp_enqueue_script('printfriendly', 'https://pf-cdn.printfriendly.com/ssl/main.js');
}
add_action('wp_enqueue_scripts', 'aliem_js');

require_once(dirname(__FILE__) . '/legacy.php');
require_once(dirname(__FILE__) . '/shortcodes.php');
require_once(dirname(__FILE__) . '/misc.php');
require_once(dirname(__FILE__) . '/widgets.php');
