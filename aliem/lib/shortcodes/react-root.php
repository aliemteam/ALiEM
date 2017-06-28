<?php

namespace ALIEM\Shortcodes;

function aliem_react_root($atts = [], $content = null) {
    $atts = shortcode_atts([
        'id' => 'react-root',
    ], $atts);
    extract($atts);
    return "<div id='$id'></div>";
}
add_shortcode('react-root', 'ALIEM\Shortcodes\aliem_react_root');
