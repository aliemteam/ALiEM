<?php

namespace ALIEM\Shortcodes;

function box( $atts = [], $content = null ) {
	$secondary = is_array( $atts ) && in_array( 'secondary', $atts, true ) ? 'box--secondary' : '';

	ob_start();
	?>
	<div class="box <?php echo esc_attr( $secondary ); ?>">
		<?php echo do_shortcode( $content ); ?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'box', 'ALIEM\Shortcodes\box' );
