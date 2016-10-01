<?php

// Image Box Shortcode
function imgbox($atts) {
	$a = shortcode_atts([
        'url' => '',
    	'title' => '',
    ], $atts);
	return '<div class="img-box"><img style="width: 100%;" src="' . $a['url'] . '" />' . $a['title'] . '</div>';
}
add_shortcode('img_box', 'imgbox');

// SVG shortcode (for ALiEM series')
function aliem_svg_generator($atts) {
    $a = shortcode_atts([
        'series' => 'aliem',
        'width' => 'auto',
        'height' => 'auto',
        'align' => 'none',
    ], $atts);
    extract($a);

    $filenames = [
        'aliem' => 'aliem-logo-horizontal.svg',
        'healthyinem' => 'stay-healthy-logo.svg',
        'bookclub' => 'bookclub-book.svg',
        'newsubmissions' => 'submissions-pencil.svg',
        'fincubator' => 'fincubator-logo.svg',
        'facubator' => 'facubator-logo.svg',
        'crincubator' => 'crincubator-logo.svg',
        'airseries' => 'air-logo.svg',
        'airseries-pro' => 'air-pro-logo.svg',
        'aliemu' => 'aliemu-logo-horizontal.svg',
        'aliemu-u' => 'aliemu-logo-u.svg',
        'capsules' => 'capsules-logo.svg',
        'capsules-pill' => 'capsules-logo-pill.svg',
    ];

    $url = '/wp-content/themes/aliem/assets/' . $filenames[$series];

    if ($align === 'center') {
        return "<div style='text-align: center; width: 100%;'><img src='$url' style='width: $width; height: $height;' /></div>";
    }

    return "<img src='$url' style='width: $width; height: $height; float: $align; padding: 10px;' />";

}
add_shortcode('svg', 'aliem_svg_generator');
