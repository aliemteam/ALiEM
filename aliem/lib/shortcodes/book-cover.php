<?php

/**
 * Book Cover Shortcode
 */
function aliem_bookclub_cover_image($atts) {
    $a = shortcode_atts([
        'isbn' => '',
        'size' => 'small',
        'align' => '',
    ], $atts);
    extract($a);

    if (!$isbn) {
        return "<h3 style='color: red;'>You forgot to provide a 10-digit ISBN!</h3>";
    }

    switch ($size) {
        case 'large':
            $size = 2;
            break;
        case 'small':
        default:
            $size = 1;
    }

    switch ($align) {
        case 'left':
            $align = 'alignleft';
            break;
        case 'right':
            $align = 'alignright';
            break;
        case 'center':
            $align = 'aligncenter';
    }

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://www.googleapis.com/books/v1/volumes?q=isbn%3A$isbn",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        return "<h3 style='color: red;'>An error occurred while attempting to get cover image</h3>";
    }

    $response = json_decode($response, true);
    $googleid = $response['items'][0]['id'];

    return "<img class='$align' src='https://books.google.com/books/content?id=$googleid&printsec=frontcover&img=1&zoom=$size&source=gbs_api' />";
}
add_shortcode('book-cover', 'aliem_bookclub_cover_image');
