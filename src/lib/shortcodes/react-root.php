<?php

namespace ALIEM\Shortcodes;

function react_root( $atts = [], $content = null ) {
	$atts = shortcode_atts(
		[
			'id' => 'react-root',
		], $atts
	);
	return "<div id='{$atts['id']}'></div>";
}
add_shortcode( 'react-root', 'ALIEM\Shortcodes\react_root' );
