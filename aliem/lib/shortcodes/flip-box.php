<?php

function aliem_flip_box($atts = [], $content = null) {
    $atts = shortcode_atts([
        'height' => '350'
    ], $atts);
    extract($atts);

    $content = preg_split("/\s+(?:<p>)?###(?:<\/p>)\s+/", $content);

    return "
    <div class='flip-box' style='min-height: ${height}px'>
        <div class='flip-box__container'>
            <div class='flip-box__inner flip-box__inner--front' style='min-height: ${height}px'>$content[0]</div>
            <div class='flip-box__inner flip-box__inner--back'  style='min-height: ${height}px'>$content[1]</div>
        </div>
    </div>
    ";

}
add_shortcode('flip-box', 'aliem_flip_box');
