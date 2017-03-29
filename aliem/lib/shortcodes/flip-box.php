<?php

function aliem_flip_box($atts = [], $content = null) {

    $content = preg_split("/\s+(?:<p>)?###(?:<\/p>)\s+/", $content);

    return "
    <div class='flip-box'>
        <div class='flip-box__container'>
            <div class='flip-box__inner flip-box__inner--front'>$content[0]</div>
            <div class='flip-box__inner flip-box__inner--back'>$content[1]</div>
        </div>
    </div>
    ";

}
add_shortcode('flip-box', 'aliem_flip_box');
