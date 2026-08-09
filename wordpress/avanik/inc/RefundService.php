<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class RefundService {
 public static function calculate(array $payment,array $policy=[]): array { $gross=max(0,(float)($payment['amount']??0)); $provider_fee=max(0,(float)($policy['provider_fee']??0)); $avanik_fee=max(0,(float)($policy['avanik_fee']??0)); $agency_adjustment=(float)($policy['agency_adjustment']??0); $refund=max(0,$gross-$provider_fee-$avanik_fee-$agency_adjustment); return ['gross_amount'=>$gross,'provider_fee'=>$provider_fee,'avanik_fee'=>$avanik_fee,'agency_adjustment'=>$agency_adjustment,'customer_refund'=>$refund,'currency'=>sanitize_text_field($payment['currency']??'IRR')]; }
 public static function request(string $booking_id,array $payment,array $policy=[],string $reason=''): string|false { $calc=self::calculate($payment,$policy); return RefundRepository::create(array_merge($calc,['booking_id'=>$booking_id,'payment_id'=>$payment['payment_id']??'','provider_reference'=>$payment['provider_reference']??'','reason'=>$reason,'status'=>'requested'])); }
}