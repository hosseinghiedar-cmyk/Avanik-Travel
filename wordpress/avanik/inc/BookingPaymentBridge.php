<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class BookingPaymentBridge {
 public static function register(): void { add_action('avanik_payment_paid',[self::class,'on_paid'],10,2); }
 public static function on_paid(string $transaction_id,array $payment=[]): void { $booking_id=sanitize_text_field($payment['booking_id']??''); if(!$booking_id)return; $booking=BookingRepository::find_by_id($booking_id); if(!$booking)return; $status=$booking['status']??Booking::STATUS_PENDING; if($status===Booking::STATUS_PENDING||$status===Booking::STATUS_AWAITING_PAYMENT) BookingLifecycle::transition($booking_id,Booking::STATUS_PAID,['transaction_id'=>$transaction_id]); }
}