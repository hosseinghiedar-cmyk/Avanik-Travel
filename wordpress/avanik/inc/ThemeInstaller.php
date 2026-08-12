<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class ThemeInstaller {
  private static bool $running = false;

  public static function register(): void {
    add_action('after_switch_theme', [self::class, 'install'], 5);
  }

  public static function install(): void {
    if (self::$running) return;
    self::$running = true;

    if (is_admin()) {
      $upgrade = ABSPATH . 'wp-admin/includes/upgrade.php';
      if (file_exists($upgrade)) require_once $upgrade;
    }

    $tasks = [
      [TicketRepository::class, 'install'],
      [TicketingIdempotency::class, 'install'],
      [RefundRepository::class, 'install'],
      [RefundIdempotency::class, 'install'],
      [RefundAuditLog::class, 'install'],
      [RefundSettlementFields::class, 'install'],
      [NotificationCenter::class, 'install'],
      [NotificationInbox::class, 'install'],
      [NotificationProviderTestLog::class, 'install'],
    ];

    foreach ($tasks as [$class, $method]) {
      try {
        if (class_exists($class) && method_exists($class, $method)) {
          $class::$method();
        }
      } catch (\Throwable $e) {
        error_log('[Avanik] Installer task failed: ' . $class . '::' . $method . ' — ' . $e->getMessage());
      }
    }

    update_option('avanik_theme_install_version', '0.4.0-safe', false);
    flush_rewrite_rules(false);
    self::$running = false;
  }
}
