<?php

namespace ALIEM\Scripts;

defined( 'ABSPATH' ) || exit;

/**
 * Master class to load and unload all scripts / styles.
 */
class Loader {
	private $localized;

	public function __construct() {
		$this->prepare_localizers();
		add_action( 'admin_enqueue_scripts', [ $this, 'init_admin' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'init' ], 999 );
		add_filter( 'script_loader_tag', [ $this, 'handle_async_defer' ], 10, 3 );
	}

	public function init_admin( $hook ) {
		if ( is_admin() ) {
			wp_enqueue_style( 'aliem_admin_style', ALIEM_ROOT_URI . '/admin.css' );
		}
	}

	public function init() {
		global $current_user, $post;
		wp_register_style( 'aliem', get_stylesheet_uri() );
		wp_register_script( 'mathjax', 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=AM_HTMLorMML', [], '2.7.1', true );
		wp_register_script( 'react', ALIEM_ROOT_URI . '/vendor/react.js', [], ALIEM_VERSION );
		wp_register_script( 'react-dom', ALIEM_ROOT_URI . '/vendor/react-dom.js', [], ALIEM_VERSION );
		wp_register_script( 'social-media-index', ALIEM_ROOT_URI . '/js/social-media-index.js', [ 'react', 'react-dom' ], ALIEM_VERSION, true );
		wp_register_script( 'header-main', ALIEM_ROOT_URI . '/js/header-main.js', [ 'react', 'react-dom' ], ALIEM_VERSION );
		wp_register_script( 'header-posts', ALIEM_ROOT_URI . '/js/header-posts.js', [ 'react', 'react-dom' ], ALIEM_VERSION );
		wp_register_script( 'image-lazy-load', ALIEM_ROOT_URI . '/js/image-lazy-load.js', [], ALIEM_VERSION, true );
		$this->delegate( $post, $current_user );
	}

	/**
	 * Modifies the script tag of select scripts to add async and defer attributes.
	 *
	 * @param string $tag    The unmodified raw html script tag
	 * @param string $handle The scripts registered handle
	 * @param string $src    The script's src url
	 */
	public function handle_async_defer( string $tag, string $handle, string $src ) : string {
		$async_scripts = [
			'header-main',
		];

		$defer_scripts = [
			'underscore',
			'wp-util',
			'algolia-search',
			'algolia-autocomplete',
			'algolia-autocomplete-noconflict',
		];

		if ( in_array( $handle, $async_scripts, true ) ) {
			return "<script async type='text/javascript' src='$src'></script>";
		}

		if ( in_array( $handle, $defer_scripts, true ) ) {
			return "<script defer type='text/javascript' src='$src'></script>";
		}

		return $tag;
	}

	/**
	 * Loads all localizers from ./localizers into execution context.
	 *
	 * `$this->localized` is structured in the following way:
	 *
	 *   [
	 *       'localizer-filename' => ['__global_js_variable_name', 'localizer_function_name'],
	 *   ]
	 */
	private function prepare_localizers() {
		$this->localized = [
			'header-main'        => [ '__header', 'header_main' ],
			'header-posts'       => [ '__header_posts', 'header_posts' ],
			'social-media-index' => [ '__smi', 'social_media_index' ],
		];
		foreach ( glob( __DIR__ . '/localizers/*' ) as $localizer ) {
			require_once $localizer;
		}
	}

	/**
	 * Master delegator for the script loader.
	 *
	 * Loads/Unloads scripts and styles based on the current page.
	 *
	 * @param object $user Current WordPress user
	 * @param mixed  $post
	 */
	private function delegate( $post, $user ) {
		// Always load these
		$load = [
			[
				'header-main',
				'image-lazy-load',
			],
			[
				'aliem',
			],
		];
		// Always unload these
		$unload = [
			[
				// 'avada-tabs-widget',
				'avada-comments',
				'avada-drop-down',
				'avada-faqs',
				'avada-general-footer',
				'avada-header',
				'avada-menu',
				'avada-portfolio',
				'avada-quantity',
				'avada-scrollspy',
				'avada-select',
				'avada-sidebars',
				'avada-to-top',
				'bootstrap-collapse',
				'bootstrap-modal',
				'bootstrap-popover',
				'bootstrap-scrollspy',
				'bootstrap-tab',
				'bootstrap-tooltip',
				'bootstrap-transition',
				'cssua',
				'froogaloop',
				'fusion-alert',
				'fusion-animations',
				'fusion-blog',
				'fusion-button',
				'fusion-carousel',
				'fusion-column-bg-image',
				'fusion-column',
				'fusion-container',
				'fusion-content-boxes',
				'fusion-equal-heights',
				'fusion-flexslider',
				'fusion-flip-boxes',
				'fusion-general-global',
				'fusion-ie1011',
				'fusion-ie9',
				'fusion-lightbox',
				'fusion-parallax',
				'fusion-popover',
				'fusion-scroll-to-anchor',
				'fusion-sharing-box',
				'fusion-testimonials',
				'fusion-title',
				'fusion-tooltip',
				'fusion-video-bg',
				'fusion-video-general',
				'fusion-video',
				'fusion-waypoints',
				'isotope',
				'jquery-appear',
				'jquery-caroufredsel',
				'jquery-count-down',
				'jquery-count-to',
				'jquery-cycle',
				'jquery-easing',
				'jquery-easy-pie-chart',
				'jquery-fade',
				'jquery-fitvids',
				'jquery-flexslider',
				'jquery-fusion-maps',
				'jquery-hover-flow',
				'jquery-hover-intent',
				'jquery-infinite-scroll',
				'jquery-lightbox',
				'jquery-mousewheel',
				'jquery-placeholder',
				'jquery-request-animation-frame',
				'jquery-sticky-kit',
				'jquery-to-top',
				'jquery-touch-swipe',
				'jquery-waypoints',
				'images-loaded',
				'modernizr',
				'packery',
			],
			[
				'fusion-core-style',
				'avada-stylesheet',
				'fusion-builder-shortcodes',
				'fusion-builder-animations',
				'fusion-builder-ilightbox',
			],
		];

		switch ( $post->ID ) {
			case 12480:
				$load[0][] = 'social-media-index';
				break;
		}

		if ( is_single() ) {
			$load[0][] = 'header-posts';
		}

		$this->load( ...$load );
		$this->unload( ...$unload );
		$this->localize( $load[0] );
	}

	/**
	 * Helper function that loads scripts/styles from an array of handles.
	 *
	 * @param array $scripts array of script handles
	 * @param array $styles  array of style handles
	 */
	private function load( $scripts, $styles ) {
		foreach ( array_reverse( array_unique( $styles ) ) as $style ) {
			wp_enqueue_style( $style );
		}
		foreach ( array_reverse( array_unique( $scripts ) ) as $script ) {
			wp_enqueue_script( $script );
		}
	}

	/**
	 * Helper function that unloads scripts/styles from an array of handles.
	 *
	 * @param array $scripts array of script handles
	 * @param array $styles  array of style handles
	 */
	private function unload( $scripts, $styles ) {
		foreach ( array_unique( $scripts ) as $script ) {
			wp_dequeue_script( $script );
		}
		foreach ( array_unique( $styles ) as $style ) {
			wp_dequeue_style( $style );
		}
	}

	/**
	 * Helper function that localizes any scripts that require localization
	 * by calling its associated "localizer" function.
	 *
	 * @param array $scripts array of script handles
	 */
	private function localize( $scripts ) {
		foreach ( array_unique( $scripts ) as $script ) {
			if ( array_key_exists( $script, $this->localized ) ) {
				$fname = $this->localized[ $script ][1];
				$func  = "\ALIEM\Scripts\Localizers\\$fname";
				wp_localize_script( $script, $this->localized[ $script ][0], $func() );
			}
		}
	}
}

new Loader();
