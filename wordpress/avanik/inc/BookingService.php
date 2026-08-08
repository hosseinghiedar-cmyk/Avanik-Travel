<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class BookingService {
  public static function create(array $data) {
    if (!is_user_logged_in()) {
      return new \WP_Error('avanik_auth_required', 'ورود کاربر برای ثبت رزرو الزامی است.');
    }

    $required = ['origin', 'destination', 'travel_date'];
    foreach ($required as $field) {
      if (empty($data[$field])) {
        return new \WP_Error('avanik_missing_field', 'اطلاعات ضروری رزرو کامل نیست.', ['field' => $field]);
      }
    }

    $booking_id = BookingRepository::create($data);
    if (!$booking_id) {
      return new \WP_Error('avanik_booking_create_failed', 'ثبت رزرو انجام نشد.');
    }

    return BookingRepository::find_by_id($booking_id);
  }

  public static function cancel(string $booking_id) {
    global $wpdb;

    if (!is_user_logged_in()) {
      return new \WP_Error('avanik_auth_required', 'ورود کاربر الزامی است.');
    }

    $booking = BookingRepository::find_by_id($booking_id);
    if (!$booking || (int) $booking['customer_id'] !== get_current_user_id()) {
      return new \WP_Error('avanik_booking_not_found', 'رزرو موردنظر پیدا نشد.');
    }

    $updated = $wpdb->update(
      BookingRepository::table_name(),
      ['status' => Booking::STATUS_CANCELLED, 'updated_at' => current_time('mysql')],
      ['booking_id' => $booking_id],
      ['%s', '%s'],
      ['%s']
    );

    return $updated !== false ? BookingRepository::find_by_id($booking_id) : new \WP_Error('avanik_booking_update_failed', 'تغییر وضعیت رزرو انجام نشد.');
  }
}
