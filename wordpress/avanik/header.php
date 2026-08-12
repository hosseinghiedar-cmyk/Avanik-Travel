<?php
defined('ABSPATH') || exit;
wp_enqueue_style('avanik-theme', get_stylesheet_uri(), [], '0.4.0');
wp_enqueue_style('avanik-frontend', get_template_directory_uri() . '/assets/css/avanik-theme.css', ['avanik-theme'], '0.4.0');
get_template_part('template-parts/header/main');
?>
<main id="primary" class="av-site-main">
