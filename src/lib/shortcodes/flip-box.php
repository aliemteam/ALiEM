<?php

namespace ALIEM\Shortcodes;

function flip_box( $atts = [], $content = null ) {
	$atts = shortcode_atts(
		[
			'height' => '350',
		], $atts
	);

	$content = preg_split( '/\s+(?:<p>)?###(?:<\/p>)\s+/', $content );

	ob_start();
	?>
		<div class="flip-box" style="min-height: <?php echo esc_attr( $height ); ?>px">
			<div class="flip-box__container">
				<div
					class="flip-box__inner flip-box__inner--front"
					style="min-height: <?php echo esc_attr( $height ); ?>px"
				><?php echo esc_html( $content[0] ); ?></div>
				<div
					class="flip-box__inner flip-box__inner--back"
					style="min-height: <?php echo esc_attr( $height ); ?>px"
				><?php echo esc_html( $content[1] ); ?></div>
			</div>
		</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'flip-box', 'ALIEM\Shortcodes\flip_box' );
