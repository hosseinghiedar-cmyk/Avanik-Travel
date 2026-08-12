<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class BookingProviderBridge {
 public static function register(): void { add_action('avanik_payment_paid',[self::class,'on_paid'],20,2); }
 public static function on_paid(string $transaction_id,array $payment=[]): void { $booking_id=sanitize_text_field($payment['booking_id']??''); if($booking_id==='')return; $result=ProviderConfirmationService::confirm($booking_id); if(is_wp_error($result))do_action('avanik_provider_confirmation_failed',$booking_id,$result,$transaction_id); }
}