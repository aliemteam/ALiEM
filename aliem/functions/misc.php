<?php

//disable WordPress sanitization to allow more than just $allowedtags
remove_filter('pre_user_description', 'wp_filter_kses');

add_action('the_author', 'parseAuthors');
function parseAuthors($content) {
    $authors = coauthors(null, null, null, null, false);
    $content = $authors;
    return $content;
}
