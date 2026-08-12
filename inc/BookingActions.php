<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class BookingActions {
  public static function register(): void {
    add_action('admin_post_avanik_create_booking', [self::class, 'create']);
    add_action('admin_post_avanik_cancel_booking', [self::class, 'cancel']);
  }

  public static function create(): void {
    if (!is_user_logged_in()) {
      wp_safe_redirect(wp_login_url());
      exit;
    }

    check_admin_referer('avanik_create_booking');

    $result = BookingService::create(wp_unslash($_POST));
    $url = wp_get_referer() ?: home_url('/booking/');

    if (is_wp_error($result)) {
      $url = add_query_arg('booking_error', $result->get_error_code(), $url);
    } else {
      $url = add_query_arg('booking_id', $result['booking_id'], $url);
    }

    wp_safe_redirect($url);
    exit;
  }

  public static function cancel(): void {
    if (!is_user_logged_in()) {
      wp_safe_redirect(wp_login_url());
      exit;
    }

    check_admin_referer('avanik_cancel_booking');

    $booking_id = sanitize_text_field(wp_unslash($_POST['booking_id'] ?? ''));
    BookingService::cancel($booking_id);

    wp_safe_redirect(wp_get_referer() ?: home_url('/dashboard/'));
    exit;
  }
}
