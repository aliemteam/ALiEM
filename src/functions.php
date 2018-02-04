<?php

namespace ALIEM;

defined( 'ABSPATH' ) || exit;

define( 'ALIEM_VERSION', '0.8.3' );
define( 'ALIEM_ROOT_PATH', __DIR__ );
define( 'ALIEM_ROOT_URI', get_stylesheet_directory_uri() );

require_once __DIR__ . '/lib/redirects.php';
require_once __DIR__ . '/lib/script-loader/index.php';
require_once __DIR__ . '/lib/shortcodes/index.php';
require_once __DIR__ . '/lib/widgets/index.php';
require_once __DIR__ . '/lib/avada-overrides.php';
require_once __DIR__ . '/lib/class-svg-support.php';
require_once __DIR__ . '/lib/misc.php';
