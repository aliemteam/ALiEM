<?php

namespace ALIEM\Shortcodes;

function person( $atts = [], $content = null ) {
	$atts = shortcode_atts(
		[
			'name'    => '',
			'title'   => '',
			'image'   => '',
			'twitter' => '',
			'align'   => '',
		], $atts
	);

	if ( ! in_array( $atts['align'], [ 'left', 'right' ], true ) ) {
		$style = '';
	} else {
		$style = "float: {$atts['align']}; margin: 0 20px; margin-{$atts['align']}: 0;";
	}

	if ( ! $atts['name'] || ! $atts['image'] || ! $atts['title'] ) {
		return "<h1 style='color: red'>Person shortcode requires 'name', 'image', and 'title' attributes!</h1>";
	}

	ob_start(); ?>
	<div class="person" style="<?php echo esc_attr( $style ); ?>">
		<div class="person__img">
			<img src="<?php echo esc_attr( $atts['image'] ); ?>"/>
		</div>
		<div class="person__meta">
			<div class="person__name"><?php echo esc_html( $atts['name'] ); ?></div>
			<div class="person__title"><?php echo esc_html( $atts['title'] ); ?></div>
			<div class="person__background"><?php echo do_shortcode( $content ); ?></div>
		</div>
		<div class="person__social-media">
			<?php if ( '' !== $atts['twitter'] ) : ?>
				<a href="https://twitter.com/<?php echo esc_attr( $atts['twitter'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Link to Twitter profile">
					<svg xmlns="http://www.w3.org/2000/svg" role="img" viewBox="0 0 24 24" height="24" width="24">
						<path fill="#1da1f2" d="M23.954 4.569c-.885.389-1.83.654-2.825.775 1.014-.611 1.794-1.574 2.163-2.723-.951.555-2.005.959-3.127 1.184-.896-.959-2.173-1.559-3.591-1.559-2.717 0-4.92 2.203-4.92 4.917 0 .39.045.765.127 1.124C7.691 8.094 4.066 6.13 1.64 3.161c-.427.722-.666 1.561-.666 2.475 0 1.71.87 3.213 2.188 4.096-.807-.026-1.566-.248-2.228-.616v.061c0 2.385 1.693 4.374 3.946 4.827-.413.111-.849.171-1.296.171-.314 0-.615-.03-.916-.086.631 1.953 2.445 3.377 4.604 3.417-1.68 1.319-3.809 2.105-6.102 2.105-.39 0-.779-.023-1.17-.067 2.189 1.394 4.768 2.209 7.557 2.209 9.054 0 13.999-7.496 13.999-13.986 0-.209 0-.42-.015-.63.961-.689 1.8-1.56 2.46-2.548l-.047-.02z"/>
					</svg>
				</a>
			<?php endif; ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'person', 'ALIEM\Shortcodes\person' );
