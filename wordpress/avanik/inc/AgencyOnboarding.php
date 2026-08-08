<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class AgencyOnboarding {
  public const PENDING = 'pending';
  public const APPROVED = 'approved';
  public const REJECTED = 'rejected';

  public static function can_sell(int $user_id): bool {
    global $wpdb;
    $table = $wpdb->prefix . 'avanik_supplier_profiles';
    $status = $wpdb->get_var($wpdb->prepare("SELECT status FROM {$table} WHERE user_id = %d", $user_id));
    return $status === self::APPROVED;
  }

  public static function set_status(int $user_id, string $status): bool {
    $allowed = [self::PENDING, self::APPROVED, self::REJECTED];
    if (!in_array($status, $allowed, true)) return false;
    global $wpdb;
    $table = $wpdb->prefix . 'avanik_supplier_profiles';
    return false !== $wpdb->update($table, ['status' => $status, 'updated_at' => current_time('mysql')], ['user_id' => $user_id], ['%s','%s'], ['%d']);
  }
}
