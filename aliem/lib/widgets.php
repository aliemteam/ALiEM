<?php

class AliemPopularPostWidget extends WP_Widget {

	public function __construct() {
		$widgetOps = [
            'classname' => 'fusion-tabs-widget pyre_tabs',
            'description' => 'ALiEM Version of Avada: Tabs - Popular posts, recent post and comments.',
        ];
		parent::__construct('aliem_tabs-widget', 'ALiEM: Popular Posts', $widgetOps);
	}

	public function widget($args, $instance) {
		extract($args);

		$posts = isset($instance['posts']) ? $instance['posts'] : 3;
		$comments = isset($instance['comments']) ? $instance['comments'] : '3';
		$tagsCount = isset($instance['tags']) ? $instance['tags'] : 3;
		$show_popular_posts = isset($instance['show_popular_posts']) ? 'true' : 'false';
		$show_recent_posts = isset($instance['show_recent_posts'])  ? 'true' : 'false';
		$show_comments = isset($instance['show_comments']) ? 'true' : 'false';

		echo $before_widget;
		?>
		<div class="tab-holder tabs-widget">

			<div class="tab-hold tabs-wrapper">

				<ul id="tabs" class="tabset tabs">

					<?php if ('true' == $show_popular_posts) : ?>
						<li>
                            <a href="#tab-popular"><?php esc_attr_e('Popular', 'Avada'); ?></a>
                        </li>
					<?php endif; ?>

					<?php if ('true' == $show_recent_posts) : ?>
						<li>
                            <a href="#tab-recent"><?php esc_attr_e('Recent', 'Avada'); ?></a>
                        </li>
					<?php endif; ?>

					<?php if ('true' == $show_comments) : ?>
						<li>
                            <a href="#tab-comments">
                                <span class="fusion-icon-bubbles"></span>
                                <span class="screen-reader-text"><?php esc_attr_e('Comments', 'Avada'); ?></span>
                            </a>
                        </li>
					<?php endif; ?>

				</ul>

				<div class="tab-box tabs-container">

					<?php if ('true' == $show_popular_posts) : ?>

						<div id="tab-popular" class="tab tab_content" style="display: none;">
							<?php
                            $queryArgs = [
                            	'date_query' => [
                            		[
                            			'column' => 'post_date_gmt',
                            			'after' => '30 days ago',
                            		],
                            	],
                                'meta_key' => 'avada_post_views_count',
                                'orderby' => 'meta_value_num',
                            	'posts_per_page' => $posts,
                                'order' => 'DESC',
                                'ignore_sticky_posts' => true,
                            ];
							$popular_posts = new WP_Query($queryArgs);
							?>
                            <div class="popular-post-heading">Most Popular (Last 30 Days)</div>
							<ul class="news-list">
								<?php if ($popular_posts->have_posts()) : ?>
									<?php while ($popular_posts->have_posts()) : $popular_posts->the_post(); ?>
										<li>
                                            <div class="popular-post-item-container">
    											<?php if (has_post_thumbnail()) : ?>
    												<div class="popular-post-image" style="background-image: url(<?php the_post_thumbnail_url('thumbnail') ?>)"></div>
                                                <?php else: ?>
                                                    <div class="popular-post-image" style="background-image: url('/wp-content/themes/aliem/assets/aliem-logo-cross.svg'); background-size: 50px;"></div>
                                                <?php endif; ?>
    											<div class="popular-post-holder">
    												<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    												<div class="fusion-meta"><?php the_time(Avada()->settings->get('date_format')); ?></div>
    												<div class="fusion-meta"><span style="font-weight: 600;">Views:</span> <?php echo get_post_meta(get_the_id(), 'avada_post_views_count', true); ?></div>
    											</div>
                                            </div>
										</li>
									<?php endwhile; ?>

									<?php wp_reset_postdata(); ?>
								<?php else : ?>
									<li><?php esc_attr_e('No posts have been published yet.', 'Avada'); ?></li>
								<?php endif; ?>
							</ul>
						</div>

					<?php endif; ?>

					<?php if ('true' == $show_recent_posts) : ?>

						<div id="tab-recent" class="tab tab_content" style="display: none;">
							<?php $recent_posts = new WP_Query('showposts=' . $tagsCount . '&ignore_sticky_posts=1'); ?>

							<ul class="news-list">
								<?php if ($recent_posts->have_posts()) : ?>
									<?php while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
                                        <li>
                                            <div class="popular-post-item-container">
    											<?php if (has_post_thumbnail()) : ?>
    												<div class="popular-post-image" style="background-image: url(<?php the_post_thumbnail_url('thumbnail') ?>)"></div>
                                                <?php else: ?>
                                                    <div class="popular-post-image" style="background-image: url('/wp-content/themes/aliem/assets/aliem-logo-cross.svg'); background-size: 50px;"></div>
    											<?php endif; ?>
    											<div class="popular-post-holder">
    												<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    												<div class="fusion-meta"><?php the_time(Avada()->settings->get('date_format')); ?></div>
    											</div>
                                            </div>
										</li>
									<?php endwhile; ?>
									<?php wp_reset_postdata(); ?>
								<?php else: ?>
									<li><?php esc_attr_e('No posts have been published yet.', 'Avada'); ?></li>
								<?php endif; ?>
							</ul>
						</div>
					<?php endif; ?>

					<?php if ('true' == $show_comments) : ?>

						<div id="tab-comments" class="tab tab_content" style="display: none;">
							<ul class="news-list">
								<?php
								global $wpdb;
								$number = $comments;

								$recent_comments = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_author_email, comment_date_gmt, comment_approved, comment_type, comment_author_url, SUBSTRING(comment_content,1,150) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' ORDER BY comment_date_gmt DESC LIMIT $number";
								$the_comments = $wpdb->get_results($recent_comments);
								?>

								<?php if ($the_comments) : ?>
									<?php foreach ($the_comments as $comment) : ?>
                                        <li>
                                            <div class="popular-post-item-container">
    											<div class="popular-post-holder comments">
                                                    <div class="comment-post-title"><?php echo wp_trim_words(strip_tags($comment->post_title), 7); ?></div>
    												<div><?php echo strip_tags($comment->comment_author); ?> <?php esc_attr_e('says:', 'Avada'); ?></div>
    												<div class="fusion-meta">
    													<a class="comment-text-side" href="<?php echo get_permalink($comment->ID); ?>#comment-<?php echo $comment->comment_ID; ?>" title="<?php printf(esc_attr__('%1$s on %2$s', 'Avada'), strip_tags($comment->comment_author), $comment->post_title); ?>"><?php echo wp_trim_words(strip_tags($comment->com_excerpt), 20); ?></a>
    												</div>
    											</div>
                                            </div>
										</li>
									<?php endforeach; ?>
								<?php else : ?>
									<li><?php esc_attr_e('No comments have been published yet.', 'Avada'); ?></li>
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

	public function update($new_instance, $old_instance) {

		$instance = $old_instance;

		$instance['posts'] = $new_instance['posts'];
		$instance['comments'] = $new_instance['comments'];
		$instance['tags'] = $new_instance['tags'];
		$instance['show_popular_posts'] = $new_instance['show_popular_posts'];
		$instance['show_recent_posts'] = $new_instance['show_recent_posts'];
		$instance['show_comments'] = $new_instance['show_comments'];
		$instance['orderby'] = $new_instance['orderby'];

		return $instance;

	}

	public function form($instance) {

		$defaults = array(
			'posts' => 3,
			'comments' => '3',
			'tags' => 3,
			'show_popular_posts' => 'on',
			'show_recent_posts' => 'on',
			'show_comments' => 'on',
		);

		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo $this->get_field_id('posts'); ?>"><?php esc_attr_e('Number of popular posts:', 'Avada'); ?></label>
			<input class="widefat" type="text" style="width: 30px;" id="<?php echo $this->get_field_id('posts'); ?>" name="<?php echo $this->get_field_name('posts'); ?>" value="<?php echo $instance['posts']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('tags'); ?>"><?php esc_attr_e('Number of recent posts:', 'Avada'); ?></label>
			<input class="widefat" type="text" style="width: 30px;" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>" value="<?php echo $instance['tags']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('comments'); ?>"><?php esc_attr_e('Number of comments:', 'Avada'); ?></label>
			<input class="widefat" type="text" style="width: 30px;" id="<?php echo $this->get_field_id('comments'); ?>" name="<?php echo $this->get_field_name('comments'); ?>" value="<?php echo $instance['comments']; ?>" />
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_popular_posts'], 'on'); ?> id="<?php echo $this->get_field_id('show_popular_posts'); ?>" name="<?php echo $this->get_field_name('show_popular_posts'); ?>" />
			<label for="<?php echo $this->get_field_id('show_popular_posts'); ?>"><?php esc_attr_e('Show popular posts', 'Avada'); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_recent_posts'], 'on'); ?> id="<?php echo $this->get_field_id('show_recent_posts'); ?>" name="<?php echo $this->get_field_name('show_recent_posts'); ?>" />
			<label for="<?php echo $this->get_field_id('show_recent_posts'); ?>"><?php esc_attr_e('Show recent posts', 'Avada'); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_comments'], 'on'); ?> id="<?php echo $this->get_field_id('show_comments'); ?>" name="<?php echo $this->get_field_name('show_comments'); ?>" />
			<label for="<?php echo $this->get_field_id('show_comments'); ?>"><?php esc_attr_e('Show comments', 'Avada'); ?></label>
		</p>
		<?php

	}
}


class BookclubWidget extends WP_Widget {

	public function __construct() {
		$widgetOps = [
			'classname' => 'widget widget_text',
			'description' => 'Bookclub countdown widget',
		];
		parent::__construct('bookclub_widget', 'ALiEM: Bookclub Countdown', $widgetOps);
	}

	public function widget($args, $instance) {
        extract($args);

        $title = isset($instance['title']) ? $instance['title'] : '';
		$date = isset($instance['date']) ? $instance['date'] : '';

        echo $before_widget;
        ?>
        <div class="heading">
            <h4 class="widget-title">Book Club Countdown</h4>
        </div>
        <div class="bookclub-widget">
            <img class="alignright" alt="Book Logo" src="/wp-content/themes/aliem/assets/bookclub-book.svg" width="52" />
            <strong>Next book</strong>: <a href="/aliem-book-club/"><?php echo $title ?></a><br>

            <strong>Discussion: </strong><?php echo $date ?><br>
            <strong>Twitter:</strong> <a href="http://www.twitter.com/aliembook" target="_blank">@ALiEMbook</a>
        </div>
        <?php
        echo $after_widget;
	}

	public function form($instance) {
        $defaults = array(
			'title' => '',
			'date' => '',
		);
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Next book title:</label>
			<input class="large-text" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('date'); ?>">Date:</label>
			<input class="large-text" type="text" id="<?php echo $this->get_field_id('date'); ?>" name="<?php echo $this->get_field_name('date'); ?>" value="<?php echo $instance['date']; ?>" />
		</p>
		<?php
	}

	public function update($new, $old) {
        $instance = $old;

		$instance['title'] = $new['title'];
		$instance['date'] = $new['date'];

		return $instance;
	}
}

class AliemAdsenseWidget extends WP_Widget {
	public function __construct() {
		$widgetOps = [
			'classname' => 'widget_adsense adsbygoogle',
			'description' => 'Google Adsense widget',
		];
		parent::__construct('adsense_widget_responsive', 'ALiEM: Google Adsense Widget', $widgetOps);
	}

	public function widget($args, $instance) {
        extract($args);
        echo $before_widget;
        $kind = isset($instance['kind']) ? $instance['kind'] : 'responsive';
        if ($kind == 'responsive'):
        ?>
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- Sidebar -->
            <ins class="adsbygoogle"
                style="display:block"
                data-ad-client="ca-pub-5143351525726802"
                data-ad-slot="3417528974"
                data-ad-format="auto"></ins>
            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
        <?php else: ?>
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- Sidebar (Fixed, 250x250) - Socks Fallback -->
            <ins class="adsbygoogle"
                style="display:inline-block;width:250px;height:250px"
                data-ad-client="ca-pub-5143351525726802"
                data-ad-slot="7714800970"></ins>
            <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        <?php endif;
        echo $after_widget;
	}

    public function form($instance) {
        $defaults = array(
			'kind' => 'responsive',
		);
		$instance = wp_parse_args((array) $instance, $defaults); ?>
        <label for="<?php echo $this->get_field_id('kind'); ?>">Ad Type:
            <select id="<?php echo $this->get_field_id('kind'); ?>" name="<?php echo $this->get_field_name('kind'); ?>">
                <option value="responsive" <?php selected( $instance['kind'], 'responsive' ); ?>>Responsive</option>
                <option value="fixed" <?php selected( $instance['kind'], 'fixed' ); ?>>Fixed (250x250)</option>
            </select>
        </label>
		<?php
	}

    public function update($new, $old) {
        $instance = $old;
		$instance['kind'] = $new['kind'];
		return $instance;
	}

}

add_action('widgets_init', function () {
    register_widget('AliemPopularPostWidget');
    register_widget('BookclubWidget');
    register_widget('AliemAdsenseWidget');
});
