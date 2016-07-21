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
