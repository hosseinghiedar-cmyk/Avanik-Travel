<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class PaymentRepository {
  public static function table_name(): string {
    global $wpdb;
    return $wpdb->prefix . 'avanik_payments';
  }

  public static function create(array $data) {
    global $wpdb;

    $record = [
      'transaction_id' => sanitize_text_field($data['transaction_id'] ?? Payment::generate_transaction_id()),
      'booking_id' => sanitize_text_field($data['booking_id'] ?? ''),
      'customer_id' => absint($data['customer_id'] ?? get_current_user_id()),
      'amount' => (float) ($data['amount'] ?? 0),
      'currency' => sanitize_text_field($data['currency'] ?? 'IRR'),
      'gateway' => sanitize_key($data['gateway'] ?? 'manual'),
      'status' => Payment::STATUS_PENDING,
    ];

    $inserted = $wpdb->insert(
      self::table_name(),
      $record,
      ['%s', '%s', '%d', '%f', '%s', '%s', '%s']
    );

    return $inserted ? $record['transaction_id'] : false;
  }

  public static function find(string $transaction_id): ?array {
    global $wpdb;
    $row = $wpdb->get_row(
      $wpdb->prepare('SELECT * FROM ' . self::table_name() . ' WHERE transaction_id = %s LIMIT 1', $transaction_id),
      ARRAY_A
    );
    return is_array($row) ? $row : null;
  }
}
