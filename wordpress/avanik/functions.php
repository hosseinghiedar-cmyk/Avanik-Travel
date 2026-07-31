<?php

defined('ABSPATH') || exit;

require_once get_template_directory() . '/inc/Theme.php';
require_once get_template_directory() . '/inc/Assets.php';
require_once get_template_directory() . '/inc/Menus.php';
require_once get_template_directory() . '/inc/Helpers.php';
require_once get_template_directory() . '/inc/Security.php';
require_once get_template_directory() . '/inc/Performance.php';
require_once get_template_directory() . '/inc/SEO.php';

Avanik\Theme::boot();
