<?php
/**
 * Handles all permanent 301 redirects
 *
 * @package ALiEM
 */

defined( 'ABSPATH' ) || exit;

( function() {
	$url = wp_parse_url(
		isset( $_SERVER['REQUEST_URI'] ) // Input var okay.
		? wp_unslash( $_SERVER['REQUEST_URI'] ) // Input var okay; sanitization okay.
		: ''
	);
	if ( 'medic' === str_replace( '/', '', $url['path'] ) ) {
		wp_safe_redirect( '/category/non-clinical/medic-series/', 301 );
		exit;
	}
} )();
