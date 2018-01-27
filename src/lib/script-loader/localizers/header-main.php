<?php

namespace ALIEM\Scripts\Localizers;

defined( 'ABSPATH' ) || exit;

function header_main() {
	function buildTree( &$elements, $parent_id = '0' ) {
		$branch = [];
		foreach ( $elements as &$element ) {
			if ( $element->menu_item_parent === $parent_id ) {
				$children = buildTree( $elements, (string) $element->ID );
				if ( $children ) {
					$element->children = $children;
				}
				$branch[ $element->ID ] = $element;
				unset( $element );
			}
		}
		return wp_list_sort( $branch, 'menu_order' );
	}
	$items = wp_get_nav_menu_items( 'next' );
	$tree  = buildTree( $items );
	return $tree;
}
