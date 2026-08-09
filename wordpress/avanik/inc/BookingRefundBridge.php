<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class BookingRefundBridge {
 public static function register(): void { add_action('avanik_ticket_cancelled',[self::class,'on_ticket_cancelled'],20,3); }
 public static function on_ticket_cancelled(string $booking_id,string $provider_reference,array $result): void { $payment=self::payment_for_booking($booking_id); if(!$payment)return; $policy=apply_filters('avanik_refund_policy',$result,$booking_id,$payment); RefundService::request($booking_id,$payment,is_array($policy)?$policy:[],'Ticket cancelled by provider'); }
 private static function payment_for_booking(string $booking_id): array|false { $rows=PaymentRepository::find_by_booking($booking_id); return !empty($rows)?(array)$rows[0]:false; }
}