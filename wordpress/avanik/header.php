<?php
defined('ABSPATH') || exit;
get_header();
wp_enqueue_style('avanik-theme',get_stylesheet_uri(),[],'0.4.6');
wp_enqueue_style('avanik-frontend',get_template_directory_uri().'/assets/css/avanik-theme.css',['avanik-theme'],'0.4.6');
wp_enqueue_style('avanik-modern',get_template_directory_uri().'/assets/css/avanik-modern.css',['avanik-frontend'],'0.4.6');
wp_enqueue_style('avanik-refactor',get_template_directory_uri().'/assets/css/avanik-refactor.css',['avanik-modern'],'0.4.6');
wp_enqueue_style('avanik-v045-modern',get_template_directory_uri().'/assets/css/avanik-v045-modern.css',['avanik-refactor'],'0.4.6');
wp_enqueue_style('avanik-reference',get_template_directory_uri().'/assets/css/avanik-reference.css',['avanik-v045-modern'],'0.4.6');
wp_enqueue_script('avanik-refactor',get_template_directory_uri().'/assets/js/avanik-refactor.js',[],'0.4.6',true);
wp_enqueue_script('avanik-v045-ui',get_template_directory_uri().'/assets/js/avanik-v045-ui.js',['avanik-refactor'],'0.4.6',true);
wp_enqueue_script('avanik-demo',get_template_directory_uri().'/assets/js/avanik-demo.js',['avanik-v045-ui'],'0.4.6',true);
wp_enqueue_script('avanik-modern',get_template_directory_uri().'/assets/js/avanik-modern.js',['avanik-demo'],'0.4.6',true);
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class('av-reference-body'); ?>>
<?php wp_body_open(); ?>
<?php get_template_part('template-parts/header/main'); ?>
<main id="primary" class="av-site-main">