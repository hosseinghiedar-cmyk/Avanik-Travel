<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class BookingLifecycle {
 public static function register(): void {
  add_action('after_switch_theme',[BookingSchema::class,'install']);
  add_action('avanik_booking_created',[BookingAvailability::class,'hold'],10,2);
  add_action('avanik_booking_confirmed',[BookingAvailability::class,'confirm'],10,1);
  add_action('avanik_booking_cancelled',[BookingAvailability::class,'release'],10,1);
 }
}