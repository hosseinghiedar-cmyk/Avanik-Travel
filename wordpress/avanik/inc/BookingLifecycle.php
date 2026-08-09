<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class BookingLifecycle {
 public static function register(): void { add_action('after_switch_theme',[BookingSchema::class,'install']); add_action('avanik_booking_created',[BookingAvailability::class,'hold'],10,2); add_action('avanik_booking_confirmed',[BookingAvailability::class,'confirm'],10,1); add_action('avanik_booking_cancelled',[BookingAvailability::class,'release'],10,1); }
 public static function transition(string $booking_id,string $to): bool { $booking=BookingRepository::find_by_id($booking_id); if(!$booking||!Booking::can_transition((string)$booking['status'],$to))return false; global $wpdb; $now=current_time('mysql'); $updates=['status'=>$to,'updated_at'=>$now]; $map=[Booking::STATUS_CONFIRMED=>'confirmed_at',Booking::STATUS_TICKETED=>'ticketed_at',Booking::STATUS_CANCELLED=>'cancelled_at',Booking::STATUS_REFUNDED=>'refunded_at']; if(isset($map[$to]))$updates[$map[$to]]=$now; $ok=false!==$wpdb->update(BookingRepository::table_name(),$updates,['booking_id'=>$booking_id]); if($ok)do_action('avanik_booking_status_changed',$booking_id,(string)$booking['status'],$to); return $ok; }
}