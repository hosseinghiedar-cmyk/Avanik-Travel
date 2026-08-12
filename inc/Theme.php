<?php
namespace Avanik;
defined('ABSPATH') || exit;
require_once __DIR__ . '/PhaseLoader.php';

final class Theme {
  public static function boot(): void {
    add_action('after_setup_theme', [Navigation::class, 'custom_logo']);
    add_action('after_setup_theme', [Navigation::class, 'register']);
    add_action('after_setup_theme', [self::class, 'boot_phase_loader'], 20);
  }

  public static function boot_phase_loader(): void {
    if (class_exists(PhaseLoader::class)) {
      PhaseLoader::boot();
    }
  }
}
