<?php

namespace ALIEM\Scripts;

if (!defined('ABSPATH')) exit(1);

/**
* Master class to load and unload all scripts / styles
*/
class Loader {
    private $localized;

    public function __construct() {
        $this->prepare_localizers();
        add_action('admin_enqueue_scripts', [$this, 'init_admin']);
        add_action('wp_enqueue_scripts', [$this, 'init'], 999);
    }

    public function init_admin($hook) {
        if (in_array($hook, ['post-new.php', 'post.php', 'page-new.php', 'page.php'])) {
            wp_enqueue_style( 'aliem_admin_style', ROOT_URI . '/admin.css' );
        }
    }

    public function init() {
        global $current_user, $post;
        wp_register_style('aliem', get_stylesheet_uri());
        wp_register_script('printfriendly', 'https://pf-cdn.printfriendly.com/ssl/main.js');
        wp_register_script('social-media-index', ROOT_URI . '/js/social-media-index.js', [], false, true);
        wp_register_script('mathjax', 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=AM_HTMLorMML', [], false, true);
        $this->delegate($post, $current_user);
    }

    private function prepare_localizers() {
        $this->localized = [
            'social-media-index' => ['__smi', 'social_media_index'],
        ];
        foreach (glob(__DIR__ . '/localizers/*') as $localizer) {
            require_once($localizer);
        }
    }

    /**
    * Master delegator for the script loader.
    *
    * Loads/Unloads scripts and styles based on the current page.
    * @param  string $req   Server request string
    * @param  string $query Server query string
    * @param  object $user  Current WordPress user
    * @return void
    */
    private function delegate($post, $user) {
        // Always load these
        $load = [
            ['print-friendly'],
            ['aliem'],
        ];
        // Always unload these
        $unload = [
            [
                'avada-comments',
                'avada-drop-down',
                'avada-faqs',
                'avada-general-footer',
                'avada-header',
                // 'avada-menu',
                'avada-portfolio',
                'avada-quantity',
                'avada-scrollspy',
                'avada-select',
                'avada-sidebars',
                // 'avada-tabs-widget',
                // 'avada-to-top',
                'bootstrap-scrollspy',
                'fusion-alert',
                'fusion-animations',
                'fusion-blog',
                'fusion-button',
                // 'fusion-carousel',
                'fusion-column',
                'fusion-column-bg-image',
                'fusion-container',
                'fusion-content-boxes',
                'fusion-equal-heights',
                'fusion-flexslider',
                'fusion-flip-boxes',
                'fusion-general-global',
                'fusion-ie1011',
                'fusion-lightbox',
                'fusion-parallax',
                // 'fusion-popover',
                'fusion-scroll-to-anchor',
                'fusion-sharing-box',
                'fusion-testimonials',
                'fusion-title',
                'fusion-tooltip',
                'fusion-video',
                'fusion-video-bg',
                'fusion-video-general',
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
                'jquery-to-top',
                'jquery-touch-swipe',
                'jquery-waypoints',
            ],
            [
                'fusion-core-style',
                // 'avada-stylesheet',
                // 'fusion-builder-shortcodes',
                'fusion-builder-animations',
                'fusion-builder-ilightbox',
            ],
        ];

        switch ($post->ID) {
            case 12480:
                $load[0][] = 'social-media-index';
                break;
        }

        $this->load(...$load);
        $this->unload(...$unload);
        $this->localize($load[0]);
    }

    /**
    * Helper function that loads scripts/styles from an array of handles.
    * @param  array $scripts Array of script handles.
    * @param  array $styles  Array of style handles.
    * @return void
    */
    private function load($scripts, $styles) {
        foreach(array_reverse(array_unique($styles)) as $style) {
            wp_enqueue_style($style);
        }
        foreach(array_reverse(array_unique($scripts)) as $script) {
            wp_enqueue_script($script);
        }
    }

    /**
    * Helper function that unloads scripts/styles from an array of handles.
    * @param  array $scripts Array of script handles.
    * @param  array $styles  Array of style handles.
    * @return void
    */
    private function unload($scripts, $styles) {
        foreach(array_unique($scripts) as $script) {
            wp_dequeue_script($script);
        }
        foreach(array_unique($styles) as $style) {
            wp_dequeue_style($style);
        }
    }

    /**
     * Helper function that localizes any scripts that require localization
     * by calling its associated "localizer" function.
     * @param array $scripts  Array of script handles.
     * @return void
     */
    private function localize($scripts) {
        foreach(array_unique($scripts) as $script) {
            if (array_key_exists($script, $this->localized)) {
                $fname = $this->localized[$script][1];
                $func = "\ALIEM\Scripts\Localizers\\$fname";
                wp_localize_script($script, $this->localized[$script][0], $func());
            }
        }
    }
}

new Loader;
