<?php

// Shortcode Stubs
function aliem_shortcode_stub() {
    return '<h1 style="color: red;">Please do not use this shortcode</h1>';
}

function call_aliem_shortcode_stub() {
    add_shortcode('su_youtube', 'aliem_shortcode_stub');
    add_shortcode('su_youtube_advanced', 'aliem_shortcode_stub');
    add_shortcode('su_vimeo', 'aliem_shortcode_stub');
    add_shortcode('su_screenr', 'aliem_shortcode_stub');
    add_shortcode('su_dailymotion', 'aliem_shortcode_stub');
    add_shortcode('su_dailymotion', 'aliem_shortcode_stub');
    add_shortcode('su_audio', 'aliem_shortcode_stub');
    add_shortcode('su_video', 'aliem_shortcode_stub');
    add_shortcode('su_gmap', 'aliem_shortcode_stub');
    add_shortcode('su_divider', 'aliem_shortcode_stub');
}
add_action('wp_loaded', 'call_aliem_shortcode_stub');
