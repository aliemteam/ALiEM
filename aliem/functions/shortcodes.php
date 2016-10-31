<?php

// SVG shortcode (for ALiEM series')
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
