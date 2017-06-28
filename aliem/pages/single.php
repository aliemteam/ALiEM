<?php get_header(); ?>

<div id="content" <?php Avada()->layout->add_style( 'content_style' ); ?>>

	<?php if ( ( Avada()->settings->get( 'blog_pn_nav' ) && 'no' != get_post_meta( $post->ID, 'pyre_post_pagination', true ) ) || ( ! Avada()->settings->get( 'blog_pn_nav' ) && 'yes' == get_post_meta( $post->ID, 'pyre_post_pagination', true ) ) ): ?>
		<div class="single-navigation clearfix">
			<?php previous_post_link( '%link', esc_attr__( 'Previous', 'Avada' ) ); ?>
			<?php next_post_link( '%link', esc_attr__( 'Next', 'Avada' ) ); ?>
		</div>
	<?php endif; ?>

	<?php while( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class( 'post' ); ?>>
			<?php $full_image = ''; ?>
			<?php if ( 'above' == Avada()->settings->get( 'blog_post_title' ) ) : ?>
				<?php echo avada_render_post_title( $post->ID, false, '', '1' ); ?>
			<?php elseif ( 'disabled' == Avada()->settings->get( 'blog_post_title' ) && Avada()->settings->get( 'disable_date_rich_snippet_pages' ) ) : ?>
				<span class="entry-title" style="display: none;"><?php the_title(); ?></span>
			<?php endif; ?>

            <?php echo aliem_social_icons(get_permalink($post->ID), get_the_title($post->ID)); ?>
			<?php if ( 'below' == Avada()->settings->get( 'blog_post_title' ) ) : ?>
				<?php echo avada_render_post_title( $post->ID, false, '', '1' ); ?>
			<?php endif; ?>
            <?php echo avada_render_post_metadata( 'single' ); ?>
			<div class="post-content">
				<?php the_content(); ?>
				<?php avada_link_pages(); ?>
			</div>

			<?php if ( ! post_password_required( $post->ID ) ) : ?>
				<?php avada_render_social_sharing(); ?>
				<?php if ( ( Avada()->settings->get( 'author_info' ) && 'no' != get_post_meta( $post->ID, 'pyre_author_info', true ) ) || ( ! Avada()->settings->get( 'author_info' ) && 'yes' == get_post_meta( $post->ID, 'pyre_author_info', true ) ) ) : ?>
					<div class="about-author">
						<?php ob_start(); ?>
						<?php the_author_posts_link(); ?>
						<?php $title = sprintf( __( 'About the Author: %s', 'Avada' ), ob_get_clean() ); ?>
						<?php echo Avada()->template->title_template( $title, '3' ); ?>
						<div class="about-author-container">
							<div class="avatar">
								<?php echo get_avatar( get_the_author_meta( 'email' ), '72' ); ?>
							</div>
							<div class="description">
								<?php the_author_meta( 'description' ); ?>
							</div>
						</div>
					</div>
				<?php endif; ?>
				<?php
				/**
				 * Render Related Posts
				 */
				echo avada_render_related_posts( get_post_type() );
				?>

				<?php if ( ( Avada()->settings->get( 'blog_comments' ) && 'no' != get_post_meta($post->ID, 'pyre_post_comments', true ) ) || ( ! Avada()->settings->get( 'blog_comments' ) && 'yes' == get_post_meta( $post->ID, 'pyre_post_comments', true ) ) ) : ?>
					<?php wp_reset_query(); ?>
					<?php comments_template(); ?>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	<?php endwhile; ?>
	<?php wp_reset_query(); ?>
</div>
<?php do_action( 'avada_after_content' ); ?>
<?php get_footer();

// Omit closing PHP tag to avoid "Headers already sent" issues.
