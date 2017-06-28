<?php

namespace ALIEM;

if (!defined('ABSPATH')) exit(1);

// TODO: add script to bump this on version
define('ALIEM_VERSON', '0.4.0');
define('ROOT_URI', get_stylesheet_directory_uri());

require_once(__DIR__ . '/lib/script-loader/index.php');
require_once(__DIR__ . '/lib/shortcodes/index.php');
require_once(__DIR__ . '/lib/widgets/index.php');
require_once(__DIR__ . '/lib/avada-overrides.php');
require_once(__DIR__ . '/lib/class-svg-support.php');
require_once(__DIR__ . '/lib/misc.php');

// TODO: add lines to deploy script to clean out entire old directory
