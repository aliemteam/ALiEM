<?php

/**
 * SVG shortcode (for ALiEM series')
 */
function aliem_svg_generator($atts) {
    $a = shortcode_atts([
        'series' => 'aliem',
        'width' => 'auto',
        'height' => 'auto',
        'padding' => '10px',
        'align' => 'none',
    ], $atts);
    extract($a);

    $filenames = [
        'airseries-pro' => 'air-pro-logo.svg',
        'airseries' => 'air-logo.svg',
        'aliem' => 'aliem-logo-horizontal.svg',
        'aliemu-u' => 'aliemu-logo-u.svg',
        'aliemu' => 'aliemu-logo-horizontal.svg',
        'bookclub' => 'bookclub-book.svg',
        'capsules-pill' => 'capsules-logo-pill.svg',
        'capsules' => 'capsules-logo.svg',
        'crincubator' => 'crincubator-logo.svg',
        'facubator' => 'facubator-logo.svg',
        'fincubator' => 'fincubator-logo.svg',
        'healthyinem' => 'stay-healthy-logo.svg',
        'newsubmissions' => 'submissions-pencil.svg',
        'usftw' => 'usftw-logo.svg',
        'us4tw' => 'usftw-logo.svg',
        'wellness' => 'wellness-logo.svg',
        'wellness-think-tank' => 'wellness-think-tank-logo.svg',
    ];

    if (array_key_exists($series, $filenames)) {
        $url = '/wp-content/themes/aliem/assets/' . $filenames[$series];
    }
    else {
        $url = '/wp-content/themes/aliem/assets/' . $series . '.svg';
    }

    if ($align === 'center') {
        return "<div style='text-align: center; width: 100%;'><img src='$url' style='width: $width; height: $height;' /></div>";
    }

    return "<img src='$url' style='width: $width; height: $height; float: $align; padding: $padding; vertical-align: middle;' />";
}
add_shortcode('svg', 'aliem_svg_generator');

/**
 * Book Cover Shortcode
 */
function aliem_bookclub_cover_image($atts) {
    $a = shortcode_atts([
        'isbn' => '',
        'size' => 'small',
        'align' => '',
    ], $atts);
    extract($a);

    if (!$isbn) {
        return "<h3 style='color: red;'>You forgot to provide a 10-digit ISBN!</h3>";
    }

    switch ($size) {
        case 'large':
            $size = 2;
            break;
        case 'small':
        default:
            $size = 1;
    }

    switch ($align) {
        case 'left':
            $align = 'alignleft';
            break;
        case 'right':
            $align = 'alignright';
            break;
        case 'center':
            $align = 'aligncenter';
    }

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://www.googleapis.com/books/v1/volumes?q=isbn%3A$isbn",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        return "<h3 style='color: red;'>An error occurred while attempting to get cover image</h3>";
    }

    $response = json_decode($response, true);
    $googleid = $response['items'][0]['id'];

    return "<img class='$align' src='https://books.google.com/books/content?id=$googleid&printsec=frontcover&img=1&zoom=$size&source=gbs_api' />";
}
add_shortcode('book-cover', 'aliem_bookclub_cover_image');


/**
 * CME Button Shortcode
 */
function aliem_cme_button($atts) {
    $a = shortcode_atts([
        'url' => '',
    ], $atts);
    extract($a);

    if (!$url) {
        return "<h3 style='color: red;'>You forgot to provide a CME URL!</h3>";
    }

    return "<a href='$url' target='_blank' rel='noopener noreferrer'><img src='/wp-content/themes/aliem/assets/cme-button.svg' class='aligncenter' style='width: 200px'/></a>";
}
add_shortcode('cme-button', 'aliem_cme_button');


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
}
add_action('wp_loaded', 'call_aliem_shortcode_stub');

// Remove tablepress button
function aliem_remove_extra_tinymce_buttons($buttons) {
    $buttons = array_filter($buttons, function($button) {
        return $button !== 'tablepress_insert_table';
    });
    return $buttons;
}
add_filter('mce_buttons', 'aliem_remove_extra_tinymce_buttons', 999);

// Remove mailpoet button
function mailpoet_remove_tinymce_subscription_form_icon(){
    if(defined('WYSIJA')){
        $helper_back = WYSIJA::get( 'back' , 'helper' );
        remove_action('admin_head-post-new.php',array($helper_back,'addCodeToPagePost'));
        remove_action('admin_head-post.php',array($helper_back,'addCodeToPagePost'));
    }
}
add_action('admin_init', 'mailpoet_remove_tinymce_subscription_form_icon');

// Hide "Add Poll" button for polldaddy
function stub_polldaddy_button() {
    echo "<style type='text/css'>a#add_poll {display: none;}</style>";
}
add_action('edit_form_before_permalink', 'stub_polldaddy_button');
