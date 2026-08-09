<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class ManualRefundGateway implements RefundGatewayInterface {
 public function supports(array $refund): bool { return ($refund['method']??'')==='card_to_card'; }
 public function execute(array $refund): array { return ['status'=>'processing','reference'=>'MANUAL-'.strtoupper(wp_generate_password(10,false,false)),'requires_admin_transfer'=>true]; }
}