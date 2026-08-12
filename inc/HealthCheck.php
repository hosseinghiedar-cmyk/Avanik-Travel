<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class HealthCheck {
  public static function run(): array {
    global $wpdb;

    $checks = [
      'wordpress' => defined('ABSPATH'),
      'database' => (bool) $wpdb->check_connection(false),
      'booking_table' => self::table_exists(BookingRepository::table_name()),
      'payment_table' => self::table_exists(PaymentRepository::table_name()),
    ];

    return [
      'ok' => !in_array(false, $checks, true),
      'checks' => $checks,
    ];
  }

  private static function table_exists(string $table): bool {
    global $wpdb;
    return $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $table)) === $table;
  }
}
