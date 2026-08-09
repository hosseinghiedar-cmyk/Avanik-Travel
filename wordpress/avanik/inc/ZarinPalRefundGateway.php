<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class ZarinPalRefundGateway implements RefundGatewayInterface {
 public function supports(array $refund): bool { return ($refund['method']??'')==='zarinpal'; }
 public function execute(array $refund): array { return ['status'=>'unsupported','reference'=>'','message'=>'ZarinPal refund API is not configured; use the adapter when the production API contract is confirmed.']; }
}