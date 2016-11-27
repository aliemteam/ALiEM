<?php

/**
 * Render the full meta data for blog archive and single layouts.
 *
 * @param string $layout    The blog layout (either single, standard, alternate or grid_timeline).
 * @param string $settings HTML markup to display the date and post format box.
 * @return  string
 */
function avada_render_post_metadata($layout, $settings = array()) {

    $html = $author = $date = $metadata = '';
    $settings = (is_array($settings)) ? $settings : array();

    $default_settings = array(
        'post_meta'          => Avada()->settings->get('post_meta'),
        'post_meta_author'   => Avada()->settings->get('post_meta_author'),
        'post_meta_date'     => Avada()->settings->get('post_meta_date'),
        'post_meta_cats'     => Avada()->settings->get('post_meta_cats'),
        'post_meta_tags'     => Avada()->settings->get('post_meta_tags'),
        'post_meta_comments' => Avada()->settings->get('post_meta_comments'),
    );

    $settings = wp_parse_args($settings, $default_settings);

    // Check if metadata is enabled.
    if (($settings['post_meta'] && 'no' != get_post_meta(get_queried_object_id(), 'pyre_post_meta', true)) || (! $settings['post_meta'] && 'yes' == get_post_meta(get_queried_object_id(), 'pyre_post_meta', true))) {

        // Render comments.
        $comments = "";
        if ($settings['post_meta_comments'] && 'grid_timeline' !== $layout) {
            ob_start();
            comments_popup_link(esc_html__('0 Comments', 'Avada'), esc_html__('1 Comment', 'Avada'), esc_html__('% Comments', 'Avada'));
            $comments = ob_get_clean();
            $comments = "<span class='fusion-inline-sep'>|</span><span>$comments</span>";
        }

        $metadata .= avada_render_rich_snippets_for_pages(false, false, true);
        $formatted_date = get_the_time(Avada()->settings->get('date_format'));
        ob_start();
        the_category(', ');
        $categories = ob_get_clean();

        $metadata .=
            "<div class='aliem-metadata__line-one'>
                <span>$formatted_date</span>
                <span class='fusion-inline-sep'>|</span>
                <span>$categories</span>
                $comments
            </div>";

        $coauthors = coauthors_posts_links(null, null, null, null, false);
        $editors = "";
        if (get_post_meta(get_the_id(), 'editors', true)) {
            $editors = sprintf('<span class="fusion-inline-sep">|</span><span>Editors: %s</span>', get_post_meta(get_the_id(), 'editors', true));
        }

        $metadata .=
            "<div class='aliem-metadata__line-two'>
                <span class='vcard'>
                    <span class='fn'>By: $coauthors</span>
                </span>
                $editors
            </div>";



        if ('single' == $layout) {
            $html .= '<div class="fusion-meta-info"><div class="fusion-meta-info-wrapper">' . $metadata . '</div></div>';
        }
        elseif (in_array($layout, array('alternate', 'grid_timeline'))) {
            $html .= '<div class="fusion-single-line-meta">' . $metadata . '</div>';
        }
        else {
            $html .= '<div class="fusion-alignleft">' . $metadata . '</div>';
        }

    }
    else {
        // Render author and updated rich snippets for grid and timeline layouts.
        if (Avada()->settings->get('disable_date_rich_snippet_pages')) {
            $html .= avada_render_rich_snippets_for_pages(false);
        }
    }
    return apply_filters('avada_post_metadata_markup', $html);
}

/**
 * Render the post title as linked h1 tag.
 *
 * @param  int|string $post_id      The post ID.
 * @param  bool       $linked       If we want it linked.
 * @param  string     $custom_title A Custom title.
 * @param  string|int $custom_size  A custom size.
 * @param  string|int $custom_link  A custom link.
 * @return string                   The post title as linked h1 tag.
 */
function avada_render_post_title( $post_id = '', $linked = true, $custom_title = '', $custom_size = '1', $custom_link = '' ) {

    $entry_title_class = '';

    // Add the entry title class if rich snippets are enabled.
    if ( Avada()->settings->get( 'disable_date_rich_snippet_pages' ) ) {
        $entry_title_class = ' class="entry-title fusion-post-title"';
    } else {
        $entry_title_class = ' class="fusion-post-title"';
    }

    // If we have a custom title, use it otherwise get post title.
    $title = ( $custom_title ) ? $custom_title : get_the_title( $post_id );
    $permalink = ( $custom_link ) ? $custom_link : get_permalink( $post_id );

    // If the post title should be linked at the markup.
    if ( $linked ) {
        $link_target = '';
        if ( 'yes' == fusion_get_page_option( 'link_icon_target', $post_id ) || 'yes' == fusion_get_page_option( 'post_links_target', $post_id ) ) {
            $link_target = ' target="_blank" rel="noopener noreferrer"';
        }
        $title = '<a href="' . $permalink . '"' . $link_target . '>' . $title . '</a>';
    }

    // Return the HTML markup of the post title.
    return '<h' . $custom_size . $entry_title_class . '>' . $title . '</h' . $custom_size . '>';

}
