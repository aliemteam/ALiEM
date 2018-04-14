<?php

namespace ALIEM\Scripts\Localizers;

defined( 'ABSPATH' ) || exit;

function header_main() {
	function build_tree( &$elements, $parent_id = '0' ) {
		$branch = [];
		foreach ( $elements as &$element ) {
			if ( $element->menu_item_parent === $parent_id ) {
				$children = build_tree( $elements, (string) $element->ID );
				if ( $children ) {
					$element->children = $children;
				}
				$branch[ $element->ID ] = $element;
				unset( $element );
			}
		}
		return wp_list_sort( $branch, 'menu_order' );
	}
	$items = wp_get_nav_menu_items( get_nav_menu_locations()['main_navigation'] );
	$tree  = build_tree( $items );
	return $tree;
}
