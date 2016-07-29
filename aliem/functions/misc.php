<?php

// Disable WordPress sanitization to allow more than just $allowedtags
remove_filter('pre_user_description', 'wp_filter_kses');

/**
 * Show all authors on author line.
 */
function parseAuthors($content) {
    $authors = coauthors(null, null, null, null, false);
    $content = $authors;
    return $content;
}
add_action('the_author', 'parseAuthors');


/**
 * Remove stockpile of image sizes created by Avada et al
 */
function unset_image_sizes() {
    foreach(get_intermediate_image_sizes() as $size) {
        if (!in_array($size, ['thumbnail', 'medium', 'medium-large', 'large'])) {
            remove_image_size($size);
        }
    }
}
add_action('init', 'unset_image_sizes');

/**
 * Adjust Avada options to allow for the use of SVG in logos.
 */
function adjust_avada_options() {
    $ops = get_option('avada_theme_options');

    $ops['logo']['url'] = '/wp-content/themes/aliem/assets/aliem-logo-horizontal-full.svg';
    $ops['logo_retina']['url'] = '/wp-content/themes/aliem/assets/aliem-logo-horizontal-full.svg';
    $ops['mobile_logo']['url'] = '/wp-content/themes/aliem/assets/aliem-logo-horizontal-full.svg';
    $ops['mobile_logo_retina']['url'] = '/wp-content/themes/aliem/assets/aliem-logo-horizontal-full.svg';

    update_option('avada_theme_options', $ops);
}
add_action('init', 'adjust_avada_options');
