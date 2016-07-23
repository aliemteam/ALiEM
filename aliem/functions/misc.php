<?php

//disable WordPress sanitization to allow more than just $allowedtags
remove_filter('pre_user_description', 'wp_filter_kses');

add_action('the_author', 'parseAuthors');
function parseAuthors($content) {
    $authors = coauthors(null, null, null, null, false);
    $content = $authors;
    return $content;
}


// Remove stockpile of image sizes created by Avada et al
function unset_image_sizes() {
    foreach(get_intermediate_image_sizes() as $size) {
        if (!in_array($size, ['thumbnail', 'medium', 'medium-large', 'large'])) {
            remove_image_size($size);
        }
    }
}
add_action('init', 'unset_image_sizes');
