<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class BookingRepository {
  public static function table_name(): string {
    global $wpdb;
    return $wpdb->prefix . 'avanik_bookings';
  }

  public static function create(array $data) {
    global $wpdb;

    $record = [
      'booking_id' => sanitize_text_field($data['booking_id'] ?? Booking::generate_id()),
      'customer_id' => absint($data['customer_id'] ?? get_current_user_id()),
      'booking_type' => sanitize_key($data['booking_type'] ?? 'flight'),
      'origin' => sanitize_text_field($data['origin'] ?? ''),
      'destination' => sanitize_text_field($data['destination'] ?? ''),
      'travel_date' => sanitize_text_field($data['travel_date'] ?? ''),
      'total_amount' => (float) ($data['total_amount'] ?? 0),
      'status' => Booking::STATUS_PENDING,
    ];

    $inserted = $wpdb->insert(
      self::table_name(),
      $record,
      ['%s', '%d', '%s', '%s', '%s', '%s', '%f', '%s']
    );

    return $inserted ? $record['booking_id'] : false;
  }

  public static function find_by_id(string $booking_id): ?array {
    global $wpdb;

    $row = $wpdb->get_row(
      $wpdb->prepare(
        'SELECT * FROM ' . self::table_name() . ' WHERE booking_id = %s LIMIT 1',
        $booking_id
      ),
      ARRAY_A
    );

    return is_array($row) ? $row : null;
  }
}
