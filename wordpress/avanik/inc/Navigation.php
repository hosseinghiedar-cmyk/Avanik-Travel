<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class Navigation {
  public static function register(): void {
    register_nav_menus(['primary' => __('منوی اصلی آوانیک', 'avanik')]);
    add_filter('wp_nav_menu_objects', [self::class, 'removeBookingLink']);
  }
  public static function custom_logo(): void {
    add_theme_support('custom-logo', ['height'=>80,'width'=>220,'flex-height'=>true,'flex-width'=>true]);
  }
  public static function removeBookingLink(array $items): array {
    foreach ($items as $key => $item) {
      $label = trim(wp_strip_all_tags($item->title ?? ''));
      if (in_array($label, ['رزرو بلیت','رزرو بلیط'], true)) unset($items[$key]);
    }
    return array_values($items);
  }
}
