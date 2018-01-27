<?php

namespace ALIEM\Shortcodes;

function flex( $atts = [], $content = null ) {
	ob_start();
	?>
		<div
			class="flex-grid"
		><?php echo do_shortcode( $content ); ?></div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'flex', 'ALIEM\Shortcodes\flex' );
