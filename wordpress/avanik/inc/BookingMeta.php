<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class BookingMeta {
  public static function fields(): array {
    return [
      'booking_id',
      'customer_id',
      'booking_type',
      'origin',
      'destination',
      'travel_date',
      'total_amount',
    ];
  }
}
