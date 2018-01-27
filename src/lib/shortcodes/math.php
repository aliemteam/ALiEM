<?php

namespace ALIEM\Shortcodes;

function math( $atts = [], $content = null ) {
	wp_enqueue_script( 'mathjax' );
	$content = trim( preg_replace( '/<\/?(?:p|br)>/', '', $content ) );

	ob_start();
	?>
		<p
			style="text-align: center; width: 100%; overflow-x: auto; overflow-y: hidden; visibility: hidden;"
		><?php echo esc_html( $content ); ?></p>
	<?php
	return ob_get_clean();
}
add_shortcode( 'math', 'ALIEM\Shortcodes\math' );
