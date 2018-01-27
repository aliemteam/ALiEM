<?php
/**
 * The theme's index.php file.
 */

defined( 'ABSPATH' ) || exit;

?>
<?php get_header(); ?>
	<section id="content">
	<?php get_template_part( 'templates/blog', 'layout' ); ?>
	</section>
	<?php do_action( 'avada_after_content' ); ?>
<?php
get_footer();

// Omit closing PHP tag to avoid "Headers already sent" issues.
