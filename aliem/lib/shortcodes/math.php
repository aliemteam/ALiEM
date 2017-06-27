<?php

function aliem_mathjax($atts = [], $content = null) {
    wp_enqueue_script('mathjax');
    $content = trim(preg_replace('/<\/?(?:p|br)>/', '', $content));
    return "
    <p style='text-align: center; width: 100%; overflow-x: auto; overflow-y: hidden; visibility: hidden;'>
    `$content`
    </p>
    ";
}
add_shortcode('math', 'aliem_mathjax');
