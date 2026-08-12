<?php
namespace Avanik;

defined('ABSPATH') || exit;

interface PaymentGatewayInterface {
  public function initiate(array $payment): array;
  public function verify(array $payload): array;
}
