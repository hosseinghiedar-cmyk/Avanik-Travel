<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class MarketplaceSchema {
  public static function install(): void {
    global $wpdb;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $table = $wpdb->prefix . 'avanik_supplier_profiles';
    $charset = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE {$table} (
      id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      user_id bigint(20) unsigned NOT NULL,
      business_name varchar(160) NOT NULL,
      business_type varchar(30) NOT NULL DEFAULT 'agency',
      status varchar(20) NOT NULL DEFAULT 'pending',
      commission_rate decimal(5,2) NOT NULL DEFAULT 0.00,
      settings longtext NULL,
      created_at datetime NOT NULL,
      updated_at datetime NOT NULL,
      PRIMARY KEY (id), UNIQUE KEY user_id (user_id), KEY status (status)
    ) {$charset};";
    dbDelta($sql);
    Marketplace::register_roles();
  }
}
