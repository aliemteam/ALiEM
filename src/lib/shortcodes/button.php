<?php

namespace ALIEM\Shortcodes;

function button( $atts = [], $content = null ) {
	$atts = shortcode_atts(
		[
			'href' => '',
		], $atts
	);

	ob_start();
	?>
	<?php if ( ! $atts['href'] ) : ?>
		<h1 style="color: red">Buttons require a 'href' attribute!</h1>
	<?php else : ?>
		<a
			href="<?php echo esc_attr( $atts['href'] ); ?>"
			target="_blank"
			rel="noopener noreferrer"
			class="btn btn--primary"
		><?php echo esc_html( $content ); ?></a>
	<?php endif; ?>
	<?php
	return ob_get_clean();
}
add_shortcode( 'button', 'ALIEM\Shortcodes\button' );
