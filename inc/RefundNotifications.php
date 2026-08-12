<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class RefundNotifications {
 public static function notify(string $refund_id,string $status,string $booking_id): void { do_action('avanik_refund_status_changed',$refund_id,$status,$booking_id); }
}