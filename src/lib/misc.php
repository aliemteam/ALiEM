<?php
/**
 * Miscellaneous one-off functionality
 *
 * @package ALiEM
 */

namespace ALIEM\Misc;

defined( 'ABSPATH' ) || exit;

// Disable WordPress sanitization to allow more than just $allowedtags.
remove_filter( 'pre_user_description', 'wp_filter_kses' );

/**
 * Use gmail SMTP rather than built-in PHP sendmail
 *
 * @param mixed $phpmailer The PHPMailer instance.
 */
function enable_gmail( $phpmailer ) {
	$phpmailer->isSMTP();
	$phpmailer->Host       = 'smtp-relay.gmail.com';
	$phpmailer->Port       = 465;
	$phpmailer->SMTPSecure = 'ssl';
}
add_action( 'phpmailer_init', __NAMESPACE__ . '\enable_gmail' );

// Load the custom editor style
add_action(
	'after_setup_theme', function () {
		add_editor_style( [ ALIEM_ROOT_URI . '/editor.css' ] );
	}
);

/**
 * Remove stockpile of image sizes created by Avada
 */
function remove_avada_image_sizes() {
	foreach ( get_intermediate_image_sizes() as $size ) {
		if ( ! in_array( $size, [ 'thumbnail', 'medium', 'medium-large', 'large' ], true ) ) {
			remove_image_size( $size );
		}
	}
}
add_action( 'init', __NAMESPACE__ . '\remove_avada_image_sizes' );

/**
 * Remove the trove of unnecessary admin menus created by lovely Avada
 */
function remove_useless_avada_menus() {
	remove_menu_page( 'edit.php?post_type=avada_faq' );
	remove_menu_page( 'edit.php?post_type=avada_portfolio' );
}
add_action( 'admin_menu', __NAMESPACE__ . '\remove_useless_avada_menus' );

/**
 * Disable Avada dynamic css
 */
function disable_avada_dynamic_css() {
	return '';
}
add_filter( 'fusion_dynamic_css_cached', __NAMESPACE__ . '\disable_avada_dynamic_css' );

/**
 * Remove stuff from admin bar that we don't need
 *
 * @param object $bar The bar instance.
 */
function remove_admin_bar_fluff( $bar ) {
	$bar->remove_node( 'search' );
	$account_node = $bar->get_node( 'my-account' );
	$bar->remove_node( 'my-account' );
	$account_node->title = '';
	$bar->add_node( $account_node );
}
add_action( 'admin_bar_menu', __NAMESPACE__ . '\remove_admin_bar_fluff' );

/**
 * Add Google AutoAds tracking scripts to single posts
 */
function google_ads_script() {
	if ( is_single() || is_page() ) {
		?>
			<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
			<script>
				(adsbygoogle = window.adsbygoogle || []).push({
					google_ad_client: "ca-pub-5143351525726802",
					enable_page_level_ads: true
				});
			</script>
		<?php
	}
}
add_action( 'wp_head', __NAMESPACE__ . '\google_ads_script' );

/**
 * Set default hidden metaboxes
 *
 * @param string[] $hidden Array of hidden metabox ids.
 */
function default_hidden_metaboxes( $hidden ) {
	array_push(
		$hidden,
		'featured-image-2_post',
		'featured-image-2_page',
		'featured-image-3_post',
		'featured-image-3_page',
		'featured-image-4_post',
		'featured-image-4_page',
		'featured-image-5_post',
		'featured-image-5_page',
		'pyre_post_options',
		'pyre_page_options',
		'formatdiv',
		'slugdiv',
		'revisionsdiv',
		'postcustom',
		'commentstatusdiv',
		'commentsdiv',
		'hidepostdivpost',
		'hidepostdivpage'
	);
	return $hidden;
}
add_filter( 'hidden_meta_boxes', __NAMESPACE__ . '\default_hidden_metaboxes' );

/**
 * Apply lazy-load attributes to images
 *
 * @param string $content The content.
 */
function filter_lazy_images( $content ) {
	$content = preg_replace_callback(
		'/(<img.*)(src="(.+?)")(.*?\/?>)/', function ( $matches ) {
			$cleaned = "$matches[1] data-lazy-src='$matches[3]' $matches[4]";
			$cleaned = preg_replace( '/(?:sizes=".*?"|srcset=".*?")/', '', $cleaned );
			return $cleaned;
		}, $content
	);
	return $content;
}
add_filter( 'the_content', __NAMESPACE__ . '\filter_lazy_images' );
add_filter( 'widget_text_content', __NAMESPACE__ . '\filter_lazy_images' );

