<?php

namespace AliemScripts;

/**
* Master class to load and unload all scripts / styles
*/
class Loader {

    private $scripts;
    private $styles;
    private $localized;

    /**
    * ScriptLoader constructor
    *
    * Constructs the loadable scripts/styles into arrays and calles the script
    *   delegator.
    * @param string $request Server request string
    * @param string $query   Server query string
    */
    public function __construct() {
        global $ROOT_URI;

        $this->scripts = [
            'print-friendly' => ['printfriendly', 'https://pf-cdn.printfriendly.com/ssl/main.js'],
            'social-media-index' => ['social-media-index', $ROOT_URI . '/lib/js/social-media-index.js', [], false, true],
        ];

        $this->styles = [
            'aliem' => ['aliem', get_stylesheet_uri()],
        ];

        $this->localized = [
            'social-media-index' => ['__smi', 'social_media_index'],
        ];

        \add_action('wp_enqueue_scripts', [$this, 'init'], 999);
    }

    public function init() {
        global $current_user, $post;
        $this->delegate($post, $current_user);
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
                'fusion-popover',
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
            \wp_enqueue_style(...$this->styles[$style]);
        }
        foreach(array_reverse(array_unique($scripts)) as $script) {
            \wp_enqueue_script(...$this->scripts[$script]);
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
            \wp_dequeue_script($script);
        }
        foreach(array_unique($styles) as $style) {
            \wp_dequeue_style($style);
        }
    }

    private function localize($scripts) {
        foreach(array_unique($scripts) as $script) {
            if (array_key_exists($script, $this->localized)) {
                $fname = $this->localized[$script][1];
                $func = "\AliemScripts\Localize\\$fname";
                \wp_localize_script($script, $this->localized[$script][0], $func());
            }
        }
    }

}

require_once(dirname(__FILE__) . '/localizers/index.php');
