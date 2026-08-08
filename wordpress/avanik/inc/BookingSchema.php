<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class BookingSchema {
  public static function install(): void {
    global $wpdb;

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $table = BookingRepository::table_name();
    $charset = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE {$table} (
      id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      booking_id varchar(32) NOT NULL,
      customer_id bigint(20) unsigned NOT NULL DEFAULT 0,
      booking_type varchar(20) NOT NULL DEFAULT 'flight',
      origin varchar(120) NOT NULL DEFAULT '',
      destination varchar(120) NOT NULL DEFAULT '',
      travel_date date NULL,
      total_amount decimal(14,2) NOT NULL DEFAULT 0.00,
      status varchar(20) NOT NULL DEFAULT 'pending',
      created_at datetime NOT NULL,
      updated_at datetime NOT NULL,
      PRIMARY KEY  (id),
      UNIQUE KEY booking_id (booking_id),
      KEY customer_id (customer_id),
      KEY status (status)
    ) {$charset};";

    dbDelta($sql);
    update_option('avanik_booking_schema_version', '0.1.0');
  }
}
