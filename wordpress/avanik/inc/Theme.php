<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class Theme {
  public static function boot(): void {
    add_action('after_setup_theme', [Navigation::class, 'custom_logo']);
    add_action('after_setup_theme', [Navigation::class, 'register']);
    add_action('wp_enqueue_scripts', [__CLASS__, 'assets'], 5);
  }
  public static function assets(): void {
    $dir = get_template_directory_uri();
    $ver = wp_get_theme()->get('Version') ?: '0.6.1';
    wp_enqueue_style('avanik-style', get_stylesheet_uri(), [], $ver);
    $ui = get_template_directory() . '/assets/css/avanik-ui-v042.css';
    if (file_exists($ui)) wp_enqueue_style('avanik-ui', $dir . '/assets/css/avanik-ui-v042.css', ['avanik-style'], (string) filemtime($ui));
    $home = get_template_directory() . '/assets/css/home-ui.css';
    if (file_exists($home)) wp_enqueue_style('avanik-home', $dir . '/assets/css/home-ui.css', ['avanik-ui'], (string) filemtime($home));
  }
}