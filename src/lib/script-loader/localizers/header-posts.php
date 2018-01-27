<?php

namespace ALIEM\Scripts\Localizers;

defined( 'ABSPATH' ) || exit;

function header_posts() {
	global $post;
	return [
		'title' => $post->post_title,
	];
}
