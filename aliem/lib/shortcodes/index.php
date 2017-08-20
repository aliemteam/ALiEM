<?php

namespace ALIEM\Shortcodes;

if (!defined('ABSPATH')) {
    exit(1);
}

add_action('wp_loaded', function () {
    // Only run this on the frontend
    if (is_admin()) {
        return;
    }

    // Stub out all outdated shortcodes
    remove_shortcode('su_youtube');
    remove_shortcode('su_youtube_advanced');
    remove_shortcode('su_vimeo');
    remove_shortcode('su_screenr');
    remove_shortcode('su_dailymotion');
    remove_shortcode('su_dailymotion');
    remove_shortcode('su_audio');
    remove_shortcode('su_video');
    remove_shortcode('su_gmap');
    remove_shortcode('su_divider');

    // Require all aliem shortcodes
    foreach (glob(__DIR__ . '/*.php') as $shortcode) {
        require_once $shortcode;
    }
});
