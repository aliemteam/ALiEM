<?php

namespace ALIEM\Scripts\Localizers;

if (!defined('ABSPATH')) {
    exit(1);
}

function header_posts() {
    global $post;
    return [
        'title' => $post->post_title,
    ];
}
