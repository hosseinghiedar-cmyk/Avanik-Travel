<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class ProductModeration {
  public static function approve(int $id): bool {
    return self::set_status($id, Product::PUBLISHED);
  }

  public static function reject(int $id): bool {
    return self::set_status($id, Product::REJECTED);
  }

  private static function set_status(int $id, string $status): bool {
    if (!current_user_can('manage_options')) return false;
    global $wpdb;
    return false !== $wpdb->update(ProductRepository::table_name(), ['status' => $status, 'updated_at' => current_time('mysql'), 'published_at' => $status === Product::PUBLISHED ? current_time('mysql') : null], ['id' => $id], ['%s','%s','%s'], ['%d']);
  }
}
