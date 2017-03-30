<?php

function aliem_flex($atts = [], $content = null) {
    return "
    <div class='flex-grid'>
        ". do_shortcode($content) ."
    </div>
    ";

}
add_shortcode('flex', 'aliem_flex');

function aliem_flex_12($atts = [], $content = null) {
    return "
    <div class='flex-grid__item flex-grid__item--12'>
        ". do_shortcode($content) ."
    </div>
    ";
}
add_shortcode('col-12', 'aliem_flex_12');

function aliem_flex_25($atts = [], $content = null) {
    return "
    <div class='flex-grid__item flex-grid__item--25'>
        ". do_shortcode($content) ."
    </div>
    ";
}
add_shortcode('col-25', 'aliem_flex_25');

function aliem_flex_50($atts = [], $content = null) {
    return "
    <div class='flex-grid__item flex-grid__item--50'>
        ". do_shortcode($content) ."
    </div>
    ";
}
add_shortcode('col-50', 'aliem_flex_50');

function aliem_flex_fill($atts = [], $content = null) {
    return "
    <div class='flex-grid__item'>
        ". do_shortcode($content) ."
    </div>
    ";
}
add_shortcode('col', 'aliem_flex_fill');
