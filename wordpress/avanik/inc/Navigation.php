<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class Navigation {
  public static function register(): void {
    register_nav_menus(['primary' => __('منوی اصلی آوانیک', 'avanik')]);
  }
  public static function custom_logo(): void {
    add_theme_support('custom-logo', ['height'=>80,'width'=>220,'flex-height'=>true,'flex-width'=>true]);
  }
}
