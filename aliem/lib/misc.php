<?php

namespace ALIEM\Misc;

if (!defined('ABSPATH')) {
    exit(1);
}

// Use SMTP for email
add_action('phpmailer_init', function ($phpmailer) {
    $phpmailer->isSMTP();
    $phpmailer->Host = 'smtp-relay.gmail.com';
    $phpmailer->Port = 465;
    $phpmailer->SMTPSecure = 'ssl';
});

// Load the custom editor style
add_action('after_setup_theme', function () {
    add_editor_style([ROOT_URI . '/editor.css']);
});


// Remove stockpile of image sizes created by Avada
add_action('init', function () {
    foreach (get_intermediate_image_sizes() as $size) {
        if (!in_array($size, ['thumbnail', 'medium', 'medium-large', 'large'])) {
            remove_image_size($size);
        }
    }
});

// Remove the trove of unnecessary admin menus created by lovely Avada
add_action('admin_menu', function () {
    remove_menu_page('edit.php?post_type=avada_faq');
    remove_menu_page('edit.php?post_type=avada_portfolio');
});

// Disable Avada dynamic css
add_filter('fusion_dynamic_css_cached', function ($css) {
    return '';
});

// Remove stuff from admin bar that we don't need
add_action('admin_bar_menu', function ($bar) {
    $bar->remove_node('search');
    $account_node = $bar->get_node('my-account');
    $bar->remove_node('my-account');
    $account_node->title = '';
    $bar->add_node($account_node);
});

// Disable WordPress sanitization to allow more than just $allowedtags
remove_filter('pre_user_description', 'wp_filter_kses');

// Append "Bottom Leaderboard" Adense ad to post content
add_filter('the_content', function ($content) {
    if (!is_single()) {
        return $content;
    }
    $content .= "
    <script async src='//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js'></script>
    <ins class='adsbygoogle'
         style='display:block; text-align:center;'
         data-ad-layout='in-article'
         data-ad-format='fluid'
         data-ad-client='ca-pub-5143351525726802'
         data-ad-slot='6412752031'></ins>
    <script>
         (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
    ";
    return $content;
});

// Set default hidden metaboxes
add_filter('hidden_meta_boxes', function ($hidden) {
    array_push($hidden,
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
}, 10, 1);

function filter_lazy_images($content) {
    $content = preg_replace_callback('/(<img.*)(src="(.+?)")(.*?\/?>)/', function ($matches) {
        $cleaned = "$matches[1] data-lazy-src='$matches[3]' $matches[4]";
        $cleaned = preg_replace('/(?:sizes=".*?"|srcset=".*?")/', '', $cleaned);
        return $cleaned;
    }, $content);
    return $content;
}
add_filter('the_content', 'ALIEM\Misc\filter_lazy_images');
add_filter('widget_text_content', 'ALIEM\Misc\filter_lazy_images');
