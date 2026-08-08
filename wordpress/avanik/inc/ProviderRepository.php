<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class ProviderRepository {
  public static function table_name(): string {
    global $wpdb;
    return $wpdb->prefix . 'avanik_providers';
  }

  public static function install(): void {
    global $wpdb;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $table = self::table_name();
    $charset = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE {$table} (
      id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      provider_key varchar(64) NOT NULL,
      name varchar(120) NOT NULL,
      type varchar(30) NOT NULL DEFAULT 'flight',
      adapter varchar(120) NOT NULL DEFAULT '',
      enabled tinyint(1) NOT NULL DEFAULT 1,
      priority int NOT NULL DEFAULT 100,
      settings longtext NULL,
      created_at datetime NOT NULL,
      updated_at datetime NOT NULL,
      PRIMARY KEY (id), UNIQUE KEY provider_key (provider_key), KEY enabled_priority (enabled,priority)
    ) {$charset};";
    dbDelta($sql);
  }

  public static function all_enabled(): array {
    global $wpdb;
    return $wpdb->get_results('SELECT * FROM ' . self::table_name() . ' WHERE enabled = 1 ORDER BY priority ASC, id ASC', ARRAY_A) ?: [];
  }
}
