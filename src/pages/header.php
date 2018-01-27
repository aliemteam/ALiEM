<?php
/**
 * Header file
 */

defined( 'ABSPATH' ) || exit;

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<link rel="manifest" href="/wp-content/themes/aliem/manifest.json">

	<?php
	echo Avada()->settings->get( 'google_analytics' );
	echo Avada()->settings->get( 'space_head' );
	wp_head();
	?>
</head>

<?php
$wrapper_class = ( is_page_template( 'blank.php' ) ) ? 'wrapper_blank' : '';

?>
<body <?php body_class(); ?>>
	<a id="a11y-skip-link" href="#content">Skip to content</a>
	<div id="wrapper" class="<?php echo esc_attr( $wrapper_class ); ?>">
		<div id="home" style="position:relative;top:-1px;"></div>

		<?php if ( is_single() ) : ?>
			<div id="header__posts__root"></div>
		<?php endif; ?>
		<header id="header__main"></header>

		<!-- FIXME: This should go in script loader -->
		<?php if ( is_page_template( 'contact.php' ) && Avada()->settings->get( 'recaptcha_public' ) && Avada()->settings->get( 'recaptcha_private' ) ) : ?>
			<script type="text/javascript">var RecaptchaOptions = { theme : '<?php echo esc_attr( Avada()->settings->get( 'recaptcha_color_scheme' ) ); ?>' };</script>
		<?php endif; ?>

		<main id="main" role="main">
