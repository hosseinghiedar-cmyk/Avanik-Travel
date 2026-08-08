<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class BookingAvailability {
 public static function register(): void { add_action('avanik_booking_created',[self::class,'hold'],10,2); add_action('avanik_booking_confirmed',[self::class,'confirm'],10,1); add_action('avanik_booking_cancelled',[self::class,'release'],10,1); }
 public static function hold(int $booking_id,array $booking=[]): void { $meta=json_decode((string)($booking['metadata']??''),true)?:[]; $product_id=(int)($meta['product_id']??0); $qty=max(1,(int)($meta['quantity']??1)); if($product_id && !Availability::hold($product_id,$booking_id,$qty,15)){ global $wpdb; $wpdb->update(BookingRepository::table_name(),['status'=>'availability_failed','updated_at'=>current_time('mysql')],['booking_id'=>$booking_id]); } }
 public static function confirm(int $booking_id): void { Availability::confirm($booking_id); }
 public static function release(int $booking_id): void { Availability::release($booking_id); }
}