<?php

namespace ALIEM\Shortcodes;

function react_root($atts = [], $content = null) {
    $atts = shortcode_atts([
        'id' => 'react-root',
    ], $atts);
    extract($atts);
    return "<div id='$id'></div>";
}
add_shortcode('react-root', 'ALIEM\Shortcodes\react_root');
