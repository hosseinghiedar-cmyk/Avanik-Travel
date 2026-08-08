<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class BookingLifecycle {
  public static function register(): void {
    add_action('after_switch_theme', [BookingSchema::class, 'install']);
  }
}
