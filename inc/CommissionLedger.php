<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class CommissionLedger {
  public static function table_name(): string {
    global $wpdb;
    return $wpdb->prefix . 'avanik_commissions';
  }

  public static function install(): void {
    global $wpdb;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $table = self::table_name();
    $charset = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE {$table} (
      id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      booking_id bigint(20) unsigned NOT NULL,
      supplier_user_id bigint(20) unsigned NOT NULL,
      gross_amount decimal(18,2) NOT NULL DEFAULT 0,
      commission_rate decimal(5,2) NOT NULL DEFAULT 0,
      commission_amount decimal(18,2) NOT NULL DEFAULT 0,
      supplier_amount decimal(18,2) NOT NULL DEFAULT 0,
      currency varchar(10) NOT NULL DEFAULT 'IRR',
      status varchar(20) NOT NULL DEFAULT 'pending',
      created_at datetime NOT NULL,
      settled_at datetime NULL,
      PRIMARY KEY (id), KEY booking_id (booking_id), KEY supplier_user_id (supplier_user_id), KEY status (status)
    ) {$charset};";
    dbDelta($sql);
  }

  public static function calculate(float $gross, float $rate): array {
    $rate = max(0, min(100, $rate));
    $commission = round($gross * ($rate / 100), 2);
    return ['commission_amount' => $commission, 'supplier_amount' => round($gross - $commission, 2)];
  }
}
