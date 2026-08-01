<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class Theme {
  public static function boot(): void {
    add_action('after_setup_theme', [Navigation::class, 'custom_logo']);
    add_action('after_setup_theme', [Navigation::class, 'register']);
  }
}
