<?php

// Inline citation shortcode
function inline_citation($atts) {
	$att = shortcode_atts([
		'num' => 1,
		'return' => false,
	], $atts);

	// Handle depreciation
	if ($att['return'] != false) return;

	// Split the string at each comma
	$nums = explode(',', $att['num']);

	$parsedNums = [];
	foreach($nums as $key => $value) {

		$value = explode('-', $value);

		switch (count($value)) {
			case 1:
				array_push($parsedNums, floor($value[0]));
				$nums[$key] = floor($value[0]);
				break;
			case 2:
				$lowerVal = (int)$value[0];
				$upperVal = (int)$value[1];
				for ($i = $lowerVal; $i <= $upperVal; $i++) {
					array_push($parsedNums, $i);
				}
				break;
			default:
				return '<span style="font-weight: bold; color: red;">An error occurred while parsing your citation. Please try again</span>';
		}

	}
	$parsedNums = array_unique($parsedNums);
	sort($parsedNums);
	$parsedNums = json_encode($parsedNums);
	$nums = implode(', ', $nums);
	return '<span class="abt_cite noselect" data-reflist="' . $parsedNums . '">[' . $nums . ']</span>';
}
add_shortcode( 'cite', 'inline_citation' );
