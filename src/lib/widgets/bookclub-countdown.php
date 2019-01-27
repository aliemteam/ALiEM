<?php

namespace ALIEM\Widgets;

defined( 'ABSPATH' ) || exit;

class BookclubCountdown extends \WP_Widget {
	public function __construct() {
		$widgetOps = [
			'classname'   => 'widget widget_text',
			'description' => 'Bookclub countdown widget',
		];
		parent::__construct( 'bookclub_widget', 'ALiEM: Bookclub Countdown', $widgetOps );
	}

	public function widget( $args, $instance ) {
		extract( $args );

		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$date  = isset( $instance['date'] ) ? $instance['date'] : '';

		echo $before_widget; ?>
		<div class="heading">
			<h4 class="widget-title">Book Club Countdown</h4>
		</div>
		<div class="bookclub-widget">
			<img class="alignright" alt="Book Logo" src="/wp-content/themes/aliem.com/assets/bookclub-book.svg" width="52" />
			<strong>Next book</strong>: <a href="/aliem-book-club/"><?php echo $title; ?></a><br>

			<strong>Discussion: </strong><?php echo $date; ?><br>
			<strong>Twitter:</strong> <a href="http://www.twitter.com/aliembook" target="_blank">@ALiEMbook</a>
		</div>
		<?php
		echo $after_widget;
	}

	public function form( $instance ) {
		$defaults = [
			'title' => '',
			'date'  => '',
		];
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Next book title:</label>
			<input class="large-text" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'date' ); ?>">Date:</label>
			<input class="large-text" type="text" id="<?php echo $this->get_field_id( 'date' ); ?>" name="<?php echo $this->get_field_name( 'date' ); ?>" value="<?php echo $instance['date']; ?>" />
		</p>
		<?php
	}

	public function update( $new, $old ) {
		$instance          = $old;
		$instance['title'] = $new['title'];
		$instance['date']  = $new['date'];
		return $instance;
	}
}
