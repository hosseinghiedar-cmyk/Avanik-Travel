<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class AgencyCommissionReversal {
 public static function reverse(string $refund_id,string $booking_id,float $amount): void { do_action('avanik_reverse_agency_commission',$refund_id,$booking_id,max(0,$amount)); }
}