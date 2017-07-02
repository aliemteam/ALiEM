<?php

namespace ALIEM\Shortcodes;

function button($atts = [], $content = null) {
    $atts = shortcode_atts([
        'href' => '',
    ], $atts);
    extract($atts);

    if ($href === '') {
        return "<h1 style='color: red'>Buttons requrie a 'href' attribute!</h1>";
    }

    return "
    <a href='$href' target='_blank' rel='noopener noreferrer' class='btn btn--primary'>
        $content
    </a>
    ";
}
add_shortcode('button', 'ALIEM\Shortcodes\button');
