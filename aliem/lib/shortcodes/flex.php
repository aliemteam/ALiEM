<?php

namespace ALIEM\Shortcodes;

function flex($atts = [], $content = null) {
    return "
    <div class='flex-grid'>
        ". do_shortcode($content) ."
    </div>
    ";

}
add_shortcode('flex', 'ALIEM\Shortcodes\flex');

function col_12($atts = [], $content = null) {
    return "
    <div class='flex-grid__item flex-grid__item--12'>
        ". do_shortcode($content) ."
    </div>
    ";
}
add_shortcode('col-12', 'ALIEM\Shortcodes\col_12');

function col_25($atts = [], $content = null) {
    return "
    <div class='flex-grid__item flex-grid__item--25'>
        ". do_shortcode($content) ."
    </div>
    ";
}
add_shortcode('col-25', 'ALIEM\Shortcodes\col_25');

function col_50($atts = [], $content = null) {
    return "
    <div class='flex-grid__item flex-grid__item--50'>
        ". do_shortcode($content) ."
    </div>
    ";
}
add_shortcode('col-50', 'ALIEM\Shortcodes\col_50');

function col($atts = [], $content = null) {
    return "
    <div class='flex-grid__item'>
        ". do_shortcode($content) ."
    </div>
    ";
}
add_shortcode('col', 'ALIEM\Shortcodes\col');
