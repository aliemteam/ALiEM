<?php

namespace ALIEM\Widgets;

defined( 'ABSPATH' ) || exit;

class PopularPosts extends \WP_Widget {
	public function __construct() {
		$widget_ops = [
			'classname'   => 'fusion-tabs-widget pyre_tabs',
			'description' => 'ALiEM Version of Avada: Tabs - Popular posts, recent post and comments.',
		];
		parent::__construct( 'aliem_tabs-widget', 'ALiEM: Popular Posts', $widget_ops );
	}

	public function widget( $args, $instance ) {
		global $is_IE;
		extract( $args );

		$posts              = isset( $instance['posts'] ) ? $instance['posts'] : 3;
		$comments           = isset( $instance['comments'] ) ? $instance['comments'] : '3';
		$tags_count         = isset( $instance['tags'] ) ? $instance['tags'] : 3;
		$show_popular_posts = isset( $instance['show_popular_posts'] ) ? 'true' : 'false';
		$show_recent_posts  = isset( $instance['show_recent_posts'] ) ? 'true' : 'false';
		$show_comments      = isset( $instance['show_comments'] ) ? 'true' : 'false';

		echo $before_widget; ?>
		<div class="tab-holder tabs-widget">

			<div class="tab-hold tabs-wrapper">

				<ul id="tabs" class="tabset tabs">

					<?php if ( 'true' === $show_popular_posts ) : ?>
						<li>
							<a href="#tab-popular"><?php esc_attr_e( 'Popular', 'Avada' ); ?></a>
						</li>
					<?php endif; ?>

					<?php if ( 'true' === $show_recent_posts ) : ?>
						<li>
							<a href="#tab-recent"><?php esc_attr_e( 'Recent', 'Avada' ); ?></a>
						</li>
					<?php endif; ?>

					<?php if ( 'true' === $show_comments ) : ?>
						<li>
							<a href="#tab-comments">
								<span class="fusion-icon-bubbles"></span>
								<span class="screen-reader-text"><?php esc_attr_e( 'Comments', 'Avada' ); ?></span>
							</a>
						</li>
					<?php endif; ?>

				</ul>

				<div class="tab-box tabs-container">

					<?php if ( 'true' === $show_popular_posts ) : ?>

						<div id="tab-popular" class="tab tab_content" style="display: none;">
							<?php
							$queryArgs     = [
								'date_query'          => [
									[
										'column' => 'post_date_gmt',
										'after'  => '30 days ago',
									],
								],
								'meta_key'            => 'avada_post_views_count',
								'orderby'             => 'meta_value_num',
								'posts_per_page'      => $posts,
								'order'               => 'DESC',
								'ignore_sticky_posts' => true,
							];
							$popular_posts = new \WP_Query( $queryArgs );
							?>
							<div class="popular-post-heading">Most Popular (Last 30 Days)</div>
							<ul class="news-list">
								<?php if ( $popular_posts->have_posts() ) : ?>
									<?php
									while ( $popular_posts->have_posts() ) :
										$popular_posts->the_post();
									?>
										<li>
											<div class="popular-post-item-container">
												<?php if ( has_post_thumbnail() ) : ?>
													<?php if ( $is_IE ) : ?>
														<div class="popular-post-image" style="background-image: url(<?php the_post_thumbnail_url( 'thumbnail' ); ?>)"></div>
													<?php else : ?>
														<div class="popular-post-image">
															<img data-lazy-src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" />
														</div>
													<?php endif; ?>
												<?php else : ?>
													<div class="popular-post-image">
														<svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 40 40">
															<path d="M7.547 24.716a41.09 41.09 0 0 1 1.491-1.897 2.76 2.76 0 0 1-.317-1.279c-3.236-.704-6.243-.58-8.721-.155V26.8h6.104c.453-.695.931-1.39 1.443-2.084m6.717-3.236c.506.24 1.006.497 1.499.773a2.767 2.767 0 0 1 4.068.288 25.94 25.94 0 0 1 4.244-.932c.695-.09 1.386-.142 2.073-.17a2.77 2.77 0 0 1 .652-1.047 32.29 32.29 0 0 0-2.67-4.917 2.764 2.764 0 0 1-2.776-.47 35.839 35.839 0 0 0-7.38 5.29c.179.358.283.76.29 1.184m.626 2.791c0-.12.01-.237.025-.353a23.461 23.461 0 0 0-1.255-.657 2.768 2.768 0 0 1-3.25.825c-.464.564-.92 1.14-1.362 1.739-.24.324-.47.65-.697.976h2.644a39.163 39.163 0 0 1 3.908-2.266 2.845 2.845 0 0 1-.014-.264m12.314 9.274V26.8h1.679a38.42 38.42 0 0 0-.427-1.698 2.772 2.772 0 0 1-2.289-1.796 21.11 21.11 0 0 0-1.851.153 23.744 23.744 0 0 0-3.88.852 2.764 2.764 0 0 1-.229 1.064c2.532 2.168 4.867 4.894 6.997 8.17m-8.273-6.81a2.763 2.763 0 0 1-3.222-.498c-.72.365-1.422.753-2.107 1.16V40h13.602v-2.87c-2.458-4.326-5.22-7.797-8.273-10.395m1.777-18.198c.545.515 1.1 1.08 1.658 1.688a2.766 2.766 0 0 1 2.998 1.006 42.08 42.08 0 0 1 1.84-.637V0H13.6v3.365a34.357 34.357 0 0 1 7.106 5.172m10.469 15.181a2.783 2.783 0 0 1-.928.972 40.9 40.9 0 0 1 .526 2.11h7.8c-2.39-1.518-4.862-2.548-7.398-3.082M9.351 19.77a2.767 2.767 0 0 1 3.303-.756 38.371 38.371 0 0 1 7.777-5.542 2.767 2.767 0 0 1 .417-2.144c-.459-.49-.93-.97-1.422-1.436A32.454 32.454 0 0 0 13.6 5.518v7.681H0v6.277c2.67-.425 5.883-.505 9.351.293m17.852-6.57v-.722a39.13 39.13 0 0 0-1.29.458c-.009.49-.145.948-.376 1.345a34.935 34.935 0 0 1 2.9 5.315 2.772 2.772 0 0 1 3.06 2.28c3.214.665 6.324 2.06 9.307 4.177V13.199H27.203" fill="#00b092"/>
														</svg>
													</div>
												<?php endif; ?>
												<div class="popular-post-holder">
													<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
													<div class="fusion-meta"><?php the_time( \Avada()->settings->get( 'date_format' ) ); ?></div>
													<div class="fusion-meta"><span style="font-weight: 600;">Views:</span> <?php echo get_post_meta( get_the_id(), 'avada_post_views_count', true ); ?></div>
												</div>
											</div>
										</li>
									<?php endwhile; ?>

									<?php wp_reset_postdata(); ?>
								<?php else : ?>
									<li><?php esc_attr_e( 'No posts have been published yet.', 'Avada' ); ?></li>
								<?php endif; ?>
							</ul>
						</div>

					<?php endif; ?>

					<?php if ( 'true' === $show_recent_posts ) : ?>

						<div id="tab-recent" class="tab tab_content" style="display: none;">
							<?php $recent_posts = new \WP_Query( 'showposts=' . $tags_count . '&ignore_sticky_posts=1' ); ?>

							<ul class="news-list">
								<?php if ( $recent_posts->have_posts() ) : ?>
									<?php
									while ( $recent_posts->have_posts() ) :
										$recent_posts->the_post();
									?>
											<li>
												<div class="popular-post-item-container">
													<?php if ( has_post_thumbnail() ) : ?>
														<div class="popular-post-image" style="background-image: url(<?php the_post_thumbnail_url( 'thumbnail' ); ?>)"></div>
												<?php else : ?>
													<div class="popular-post-image" style="background-image: url('/wp-content/themes/aliem/assets/aliem-logo-cross.svg'); background-size: 50px;"></div>
												<?php endif; ?>
												<div class="popular-post-holder">
													<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
													<div class="fusion-meta"><?php the_time( \Avada()->settings->get( 'date_format' ) ); ?></div>
												</div>
											</div>
										</li>
									<?php endwhile; ?>
									<?php wp_reset_postdata(); ?>
								<?php else : ?>
									<li><?php esc_attr_e( 'No posts have been published yet.', 'Avada' ); ?></li>
								<?php endif; ?>
							</ul>
						</div>
					<?php endif; ?>

					<?php if ( 'true' === $show_comments ) : ?>

						<div id="tab-comments" class="tab tab_content" style="display: none;">
							<ul class="news-list">
								<?php
								global $wpdb;
								$number = $comments;

								$recent_comments = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_author_email, comment_date_gmt, comment_approved, comment_type, comment_author_url, SUBSTRING(comment_content,1,150) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' ORDER BY comment_date_gmt DESC LIMIT $number";
								$the_comments    = $wpdb->get_results( $recent_comments );
								?>

								<?php if ( $the_comments ) : ?>
									<?php foreach ( $the_comments as $comment ) : ?>
										<li>
											<div class="popular-post-item-container">
												<div class="popular-post-holder comments">
													<div class="comment-post-title"><?php echo wp_trim_words( strip_tags( $comment->post_title ), 7 ); ?></div>
													<div><?php echo strip_tags( $comment->comment_author ); ?> <?php esc_attr_e( 'says:', 'Avada' ); ?></div>
													<div class="fusion-meta">
														<a class="comment-text-side" href="<?php echo get_permalink( $comment->ID ); ?>#comment-<?php echo $comment->comment_ID; ?>" title="<?php printf( esc_attr__( '%1$s on %2$s', 'Avada' ), strip_tags( $comment->comment_author ), $comment->post_title ); ?>"><?php echo wp_trim_words( strip_tags( $comment->com_excerpt ), 20 ); ?></a>
													</div>
												</div>
											</div>
										</li>
									<?php endforeach; ?>
								<?php else : ?>
									<li><?php esc_attr_e( 'No comments have been published yet.', 'Avada' ); ?></li>
								<?php endif; ?>
							</ul>
						</div>

					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance                       = $old_instance;
		$instance['posts']              = $new_instance['posts'];
		$instance['comments']           = $new_instance['comments'];
		$instance['tags']               = $new_instance['tags'];
		$instance['show_popular_posts'] = $new_instance['show_popular_posts'];
		$instance['show_recent_posts']  = $new_instance['show_recent_posts'];
		$instance['show_comments']      = $new_instance['show_comments'];
		$instance['orderby']            = $new_instance['orderby'];
		return $instance;
	}

	public function form( $instance ) {
		$defaults = [
			'posts'              => 3,
			'comments'           => '3',
			'tags'               => 3,
			'show_popular_posts' => 'on',
			'show_recent_posts'  => 'on',
			'show_comments'      => 'on',
		];
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'posts' ); ?>"><?php esc_attr_e( 'Number of popular posts:', 'Avada' ); ?></label>
			<input class="widefat" type="text" style="width: 30px;" id="<?php echo $this->get_field_id( 'posts' ); ?>" name="<?php echo $this->get_field_name( 'posts' ); ?>" value="<?php echo $instance['posts']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'tags' ); ?>"><?php esc_attr_e( 'Number of recent posts:', 'Avada' ); ?></label>
			<input class="widefat" type="text" style="width: 30px;" id="<?php echo $this->get_field_id( 'tags' ); ?>" name="<?php echo $this->get_field_name( 'tags' ); ?>" value="<?php echo $instance['tags']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'comments' ); ?>"><?php esc_attr_e( 'Number of comments:', 'Avada' ); ?></label>
			<input class="widefat" type="text" style="width: 30px;" id="<?php echo $this->get_field_id( 'comments' ); ?>" name="<?php echo $this->get_field_name( 'comments' ); ?>" value="<?php echo $instance['comments']; ?>" />
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_popular_posts'], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_popular_posts' ); ?>" name="<?php echo $this->get_field_name( 'show_popular_posts' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_popular_posts' ); ?>"><?php esc_attr_e( 'Show popular posts', 'Avada' ); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_recent_posts'], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_recent_posts' ); ?>" name="<?php echo $this->get_field_name( 'show_recent_posts' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_recent_posts' ); ?>"><?php esc_attr_e( 'Show recent posts', 'Avada' ); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_comments'], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_comments' ); ?>" name="<?php echo $this->get_field_name( 'show_comments' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_comments' ); ?>"><?php esc_attr_e( 'Show comments', 'Avada' ); ?></label>
		</p>
		<?php
	}
}
