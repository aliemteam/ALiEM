<?php

namespace ALIEM\Widgets;

if (!defined('ABSPATH')) exit(1);

add_action('widgets_init', function() {
    foreach (glob(__DIR__ . '/*.php') as $widget) {
        require_once($widget);
    }
    register_widget(__NAMESPACE__ . '\Adsense');
    register_widget(__NAMESPACE__ . '\BookclubCountdown');
    register_widget(__NAMESPACE__ . '\PopularPosts');
});
