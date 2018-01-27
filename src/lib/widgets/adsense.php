<?php

namespace ALIEM\Widgets;

defined( 'ABSPATH' ) || exit;

class Adsense extends \WP_Widget {
	public function __construct() {
		$widgetOps = [
			'classname'   => 'widget_adsense adsbygoogle',
			'description' => 'Google Adsense widget',
		];
		parent::__construct( 'adsense_widget_responsive', 'ALiEM: Google Adsense Widget', $widgetOps );
	}

	public function widget( $args, $instance ) {
		extract( $args );
		echo $before_widget;
		$kind = isset( $instance['kind'] ) ? $instance['kind'] : 'responsive';
		if ( $kind === 'responsive' ) :
		?>
			<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
			<!-- Sidebar -->
			<ins class="adsbygoogle"
				style="display:block"
				data-ad-client="ca-pub-5143351525726802"
				data-ad-slot="3417528974"
				data-ad-format="auto"></ins>
			<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
		<?php else : ?>
			<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
			<!-- Sidebar (Fixed, 250x250) - Socks Fallback -->
			<ins class="adsbygoogle"
				style="display:inline-block;width:250px;height:250px"
				data-ad-client="ca-pub-5143351525726802"
				data-ad-slot="7714800970"></ins>
			<script>
			(adsbygoogle = window.adsbygoogle || []).push({});
			</script>
		<?php endif; ?>
		<p style="margin-top: -5px !important; font-size: 7px;">* Advertisement may not reflect the views of ALiEM</p>
		<?php
		echo $after_widget;
	}

	public function form( $instance ) {
		$defaults = [
			'kind' => 'responsive',
		];
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<label for="<?php echo $this->get_field_id( 'kind' ); ?>">Ad Type:
			<select id="<?php echo $this->get_field_id( 'kind' ); ?>" name="<?php echo $this->get_field_name( 'kind' ); ?>">
				<option value="responsive" <?php selected( $instance['kind'], 'responsive' ); ?>>Responsive</option>
				<option value="fixed" <?php selected( $instance['kind'], 'fixed' ); ?>>Fixed (250x250)</option>
			</select>
		</label>
		<?php
	}

	public function update( $new, $old ) {
		$instance         = $old;
		$instance['kind'] = $new['kind'];
		return $instance;
	}
}
