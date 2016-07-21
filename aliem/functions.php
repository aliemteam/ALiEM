<?php
//disable WordPress sanitization to allow more than just $allowedtags from /wp-includes/kses.php
remove_filter('pre_user_description', 'wp_filter_kses');

// Reference Box Shortcode
function refbox( $atts, $content = null ) {
	return
	'<h3 style="background: #f5f5f5; display: inline-block; padding: 10px; margin-bottom: -1px; border-top-left-radius: 5px; border-top-right-radius: 5px; border: solid #D0D0D0 1px; border-bottom: none;">References</h3><div style="background: #f5f5f5; padding-top: 10px; padding-bottom: 10px; padding-right: 10px; padding-left: 10px; margin-bottom: 5px; border: solid #D0D0D0 1px; border-top-right-radius: 3px; border-bottom-right-radius: 3px; border-bottom-left-radius: 3px;">' . do_shortcode($content) . '</div>';
}
add_shortcode( 'refbox', 'refbox' );

add_action('the_author', 'parseAuthors');
function parseAuthors($content) {
    $authors = coauthors(null, null, null, null, false);
    $content = $authors;
    return $content;
}

// Image Box Shortcode
function imgbox($atts) {
	$a = shortcode_atts( array(
        	'url' => '',
       		'title' => '',
    	), $atts );
	return '<div class="img-box"><img style="width: 100%;" src="' . $a['url'] . '" />' . $a['title'] . '</div>';
}
add_shortcode('img_box', 'imgbox');


/*
 * * * * * * * * * * * * * * * * *
 *	In-text citation shortcode * *
 * * * * * * * * * * * * * * * * *
*/

function inline_citation ( $atts ) {
	$a = shortcode_atts( array(
		'num' => 1,
		'return' => FALSE,
	), $atts);

	// Handle depreciation
	if ($a['return'] != FALSE) {
		return;
	}

	// Split the string at each comma
	$nums = explode(',', $a['num']);

	$parsed_nums = array();
	foreach($nums as $key => $value) {

		$value = explode('-', $value);

		switch (count($value)) {
			case 1:
				array_push($parsed_nums, floor($value[0]));
				$nums[$key] = floor($value[0]);
				break;
			case 2:
				$lower_val = (int)$value[0];
				$upper_val = (int)$value[1];
				for ($i = $lower_val; $i <= $upper_val; $i++) {
					array_push($parsed_nums, $i);
				}
				break;
			default:
				return '<span style="font-weight: bold; color: red;">An error occurred while parsing your citation. Please try again</span>';
		}

	}
	$parsed_nums = array_unique($parsed_nums);
	sort($parsed_nums);
	$parsed_nums = json_encode($parsed_nums);

	$nums = implode(', ', $nums);

	return '<span class="abt_cite noselect" data-reflist="' . $parsed_nums . '">[' . $nums . ']</span>';

}
add_shortcode( 'cite', 'inline_citation' );
