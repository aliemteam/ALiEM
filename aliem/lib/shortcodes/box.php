<?php

function aliem_box($atts = [], $content = null) {
    $secondary = is_array($atts) && in_array('secondary', $atts) ? 'box--secondary' : '';

    return "
    <div class='box $secondary'>
        $content
    </div>
    ";

}
add_shortcode('box', 'aliem_box');
