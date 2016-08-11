<?php

// Disable WordPress sanitization to allow more than just $allowedtags
remove_filter('pre_user_description', 'wp_filter_kses');

/**
 * Show all authors on author line.
 */
function parseAuthors($content) {
    $authors = coauthors(null, null, null, null, false);
    $content = $authors;
    return $content;
}
add_action('the_author', 'parseAuthors');


/**
 * Remove stockpile of image sizes created by Avada et al
 */
function unset_image_sizes() {
    foreach(get_intermediate_image_sizes() as $size) {
        if (!in_array($size, ['thumbnail', 'medium', 'medium-large', 'large'])) {
            remove_image_size($size);
        }
    }
}
add_action('init', 'unset_image_sizes');

/**
 * Adjust Avada options to allow for the use of SVG in logos.
 */
function adjust_avada_options() {
    $ops = get_option('avada_theme_options');

    $ops['logo']['url'] = '/wp-content/themes/aliem/assets/aliem-logo-horizontal-full.svg';
    $ops['logo_retina']['url'] = '/wp-content/themes/aliem/assets/aliem-logo-horizontal-full.svg';
    $ops['mobile_logo']['url'] = '/wp-content/themes/aliem/assets/aliem-logo-horizontal-full.svg';
    $ops['mobile_logo_retina']['url'] = '/wp-content/themes/aliem/assets/aliem-logo-horizontal-full.svg';

    update_option('avada_theme_options', $ops);
}
add_action('init', 'adjust_avada_options');

/**
 * Renders the social icons
 * @param  string $url   The permalink
 * @param  string $title The post title
 */
function aliem_social_icons($url, $title) {
    $url = urlencode($url);
    $title = urlencode($title);
    ?>
    <div class='sharing-icons'>
        <!-- Sharingbutton Facebook -->
        <div>
            <a class='resp-sharing-button__link' href='https://facebook.com/sharer/sharer.php?u=<?php echo $url; ?>' target='_blank' aria-label='Share on Facebook'>
                <div class='resp-sharing-button resp-sharing-button--facebook resp-sharing-button--small'>
                    <div aria-hidden='true' class='resp-sharing-button__icon resp-sharing-button__icon--solid'>
                        <svg version='1.1' x='0px' y='0px' width='24px' height='24px' viewBox='0 0 24 24' enable-background='new 0 0 24 24' xml:space='preserve'>
                            <g>
                                <path d='M18.768,7.465H14.5V5.56c0-0.896,0.594-1.105,1.012-1.105s2.988,0,2.988,0V0.513L14.171,0.5C10.244,0.5,9.5,3.438,9.5,5.32 v2.145h-3v4h3c0,5.212,0,12,0,12h5c0,0,0-6.85,0-12h3.851L18.768,7.465z' />
                            </g>
                        </svg>
                    </div>
                </div>
            </a>
        </div>
        <!-- Sharingbutton Twitter -->
        <div>
            <a class='resp-sharing-button__link' href='https://twitter.com/intent/tweet/?text=<?php echo $title; ?>&amp;url=<?php echo $url; ?>&amp;via=ALiEMteam' target='_blank' aria-label='Share on Twitter'>
                <div class='resp-sharing-button resp-sharing-button--twitter resp-sharing-button--small'>
                    <div aria-hidden='true' class='resp-sharing-button__icon resp-sharing-button__icon--solid'>
                        <svg version='1.1' x='0px' y='0px' width='24px' height='24px' viewBox='0 0 24 24' enable-background='new 0 0 24 24' xml:space='preserve'>
                            <g>
                                <path d='M23.444,4.834c-0.814,0.363-1.5,0.375-2.228,0.016c0.938-0.562,0.981-0.957,1.32-2.019c-0.878,0.521-1.851,0.9-2.886,1.104 C18.823,3.053,17.642,2.5,16.335,2.5c-2.51,0-4.544,2.036-4.544,4.544c0,0.356,0.04,0.703,0.117,1.036 C8.132,7.891,4.783,6.082,2.542,3.332C2.151,4.003,1.927,4.784,1.927,5.617c0,1.577,0.803,2.967,2.021,3.782 C3.203,9.375,2.503,9.171,1.891,8.831C1.89,8.85,1.89,8.868,1.89,8.888c0,2.202,1.566,4.038,3.646,4.456 c-0.666,0.181-1.368,0.209-2.053,0.079c0.579,1.804,2.257,3.118,4.245,3.155C5.783,18.102,3.372,18.737,1,18.459 C3.012,19.748,5.399,20.5,7.966,20.5c8.358,0,12.928-6.924,12.928-12.929c0-0.198-0.003-0.393-0.012-0.588 C21.769,6.343,22.835,5.746,23.444,4.834z'/>
                            </g>
                        </svg>
                    </div>
                </div>
            </a>
        </div>
        <!-- Sharingbutton Google+ -->
        <div>
            <a class='resp-sharing-button__link' href='https://plus.google.com/share?url=<?php echo $url; ?>' target='_blank' aria-label='Share on Google Plus'>
                <div class='resp-sharing-button resp-sharing-button--google resp-sharing-button--small'>
                    <div aria-hidden='true' class='resp-sharing-button__icon resp-sharing-button__icon--solid'>
                        <svg version='1.1' x='0px' y='0px' width='24px' height='24px' viewBox='0 0 24 24' enable-background='new 0 0 24 24' xml:space='preserve'>
                            <g>
                                <path d='M11.366,12.928c-0.729-0.516-1.393-1.273-1.404-1.505c0-0.425,0.038-0.627,0.988-1.368 c1.229-0.962,1.906-2.228,1.906-3.564c0-1.212-0.37-2.289-1.001-3.044h0.488c0.102,0,0.2-0.033,0.282-0.091l1.364-0.989 c0.169-0.121,0.24-0.338,0.176-0.536C14.102,1.635,13.918,1.5,13.709,1.5H7.608c-0.667,0-1.345,0.118-2.011,0.347 c-2.225,0.766-3.778,2.66-3.778,4.605c0,2.755,2.134,4.845,4.987,4.91c-0.056,0.22-0.084,0.434-0.084,0.645 c0,0.425,0.108,0.827,0.33,1.216c-0.026,0-0.051,0-0.079,0c-2.72,0-5.175,1.334-6.107,3.32C0.623,17.06,0.5,17.582,0.5,18.098 c0,0.501,0.129,0.984,0.382,1.438c0.585,1.046,1.843,1.861,3.544,2.289c0.877,0.223,1.82,0.335,2.8,0.335 c0.88,0,1.718-0.114,2.494-0.338c2.419-0.702,3.981-2.482,3.981-4.538C13.701,15.312,13.068,14.132,11.366,12.928z M3.66,17.443 c0-1.435,1.823-2.693,3.899-2.693h0.057c0.451,0.005,0.892,0.072,1.309,0.2c0.142,0.098,0.28,0.192,0.412,0.282 c0.962,0.656,1.597,1.088,1.774,1.783c0.041,0.175,0.063,0.35,0.063,0.519c0,1.787-1.333,2.693-3.961,2.693 C5.221,20.225,3.66,19.002,3.66,17.443z M5.551,3.89c0.324-0.371,0.75-0.566,1.227-0.566l0.055,0 c1.349,0.041,2.639,1.543,2.876,3.349c0.133,1.013-0.092,1.964-0.601,2.544C8.782,9.589,8.363,9.783,7.866,9.783H7.865H7.844 c-1.321-0.04-2.639-1.6-2.875-3.405C4.836,5.37,5.049,4.462,5.551,3.89z'/>
                                <polygon points='23.5,9.5 20.5,9.5 20.5,6.5 18.5,6.5 18.5,9.5 15.5,9.5 15.5,11.5 18.5,11.5 18.5,14.5 20.5,14.5 20.5,11.5  23.5,11.5 	' />
                            </g>
                        </svg>
                    </div>
                </div>
            </a>
        </div>
        <!-- Sharingbutton Reddit -->
        <div>
            <a class='resp-sharing-button__link' href='https://reddit.com/submit/?url=<?php echo $url; ?>&amp;title=<?php echo $title; ?>' target='_blank' aria-label='Share on Reddit'>
                <div class='resp-sharing-button resp-sharing-button--reddit resp-sharing-button--small'>
                    <div aria-hidden='true' class='resp-sharing-button__icon resp-sharing-button__icon--solid'>
                        <svg version='1.1' x='0px' y='0px' width='24px' height='24px' viewBox='0 0 24 24' enable-background='new 0 0 24 24' xml:space='preserve'>
                            <path d='M24,11.5c0-1.654-1.346-3-3-3c-0.964,0-1.863,0.476-2.422,1.241c-1.639-1.006-3.747-1.64-6.064-1.723 c0.064-1.11,0.4-3.049,1.508-3.686c0.72-0.414,1.733-0.249,3.01,0.478C17.189,6.317,18.452,7.5,20,7.5c1.654,0,3-1.346,3-3 s-1.346-3-3-3c-1.382,0-2.536,0.944-2.883,2.217C15.688,3,14.479,2.915,13.521,3.466c-1.642,0.945-1.951,3.477-2.008,4.551 C9.186,8.096,7.067,8.731,5.422,9.741C4.863,8.976,3.964,8.5,3,8.5c-1.654,0-3,1.346-3,3c0,1.319,0.836,2.443,2.047,2.844 C2.019,14.56,2,14.778,2,15c0,3.86,4.486,7,10,7s10-3.14,10-7c0-0.222-0.019-0.441-0.048-0.658C23.148,13.938,24,12.795,24,11.5z  M2.286,13.366C1.522,13.077,1,12.351,1,11.5c0-1.103,0.897-2,2-2c0.635,0,1.217,0.318,1.59,0.816 C3.488,11.17,2.683,12.211,2.286,13.366z M6,13.5c0-1.103,0.897-2,2-2s2,0.897,2,2c0,1.103-0.897,2-2,2S6,14.603,6,13.5z  M15.787,18.314c-1.063,0.612-2.407,0.949-3.787,0.949c-1.387,0-2.737-0.34-3.803-0.958c-0.239-0.139-0.321-0.444-0.182-0.683 c0.139-0.24,0.444-0.322,0.683-0.182c1.828,1.059,4.758,1.062,6.59,0.008c0.239-0.138,0.545-0.055,0.683,0.184 C16.108,17.871,16.026,18.177,15.787,18.314z M16,15.5c-1.103,0-2-0.897-2-2c0-1.103,0.897-2,2-2s2,0.897,2,2 C18,14.603,17.103,15.5,16,15.5z M21.713,13.365c-0.397-1.155-1.201-2.195-2.303-3.048C19.784,9.818,20.366,9.5,21,9.5 c1.103,0,2,0.897,2,2C23,12.335,22.468,13.073,21.713,13.365z'/>
                        </svg>
                    </div>
                </div>
            </a>
        </div>
        <div>
            <a class='resp-sharing-button__link' href='http://www.printfriendly.com' onclick='window.print();return false;' target='_blank' aria-label='Print'>
                <div class='resp-sharing-button resp-sharing-button--print resp-sharing-button--small'>
                    <div aria-hidden='true' class='resp-sharing-button__icon resp-sharing-button__icon--solid'>
                        <svg version='1.1' x='0px' y='0px' width='24px' height='24px' viewBox='0 0 24 24' enable-background='new 0 0 24 24' xml:space='preserve'>
                            <path d='m6.41463,2l12.82927,0l0,3.20732l-12.82927,0l0,-3.20732z' />
                            <path d='m24.05488,6.81098l-22.45122,0c-0.88201,0 -1.60366,0.72165 -1.60366,1.60366l0,8.01829c0,0.88201 0.72165,1.60366 1.60366,1.60366l4.81098,0l0,6.41463l12.82927,0l0,-6.41463l4.81098,0c0.88201,0 1.60366,-0.72165 1.60366,-1.60366l0,-8.01829c0,-0.88201 -0.72165,-1.60366 -1.60366,-1.60366zm-20.84756,4.81098c-0.88602,0 -1.60366,-0.71764 -1.60366,-1.60366s0.71764,-1.60366 1.60366,-1.60366s1.60366,0.71764 1.60366,1.60366s-0.71764,1.60366 -1.60366,1.60366zm14.43293,11.22561l-9.62195,0l0,-8.01829l9.62195,0l0,8.01829z'/>
                        </svg>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <?php
}
