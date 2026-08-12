<?php
defined('ABSPATH') || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php
  wp_enqueue_style('avanik-theme', get_stylesheet_uri(), [], '0.4.2');
  wp_enqueue_style('avanik-frontend', get_template_directory_uri() . '/assets/css/avanik-theme.css', ['avanik-theme'], '0.4.2');
  wp_enqueue_style('avanik-modern', get_template_directory_uri() . '/assets/css/avanik-modern.css', ['avanik-frontend'], '0.4.2');
  wp_enqueue_script('avanik-demo', get_template_directory_uri() . '/assets/js/avanik-demo.js', [], '0.4.2', true);
  wp_enqueue_script('avanik-modern', get_template_directory_uri() . '/assets/js/avanik-modern.js', ['avanik-demo'], '0.4.2', true);
  wp_head();
  ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php get_template_part('template-parts/header/main'); ?>
<main id="primary" class="av-site-main">
