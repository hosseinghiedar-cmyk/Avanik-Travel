<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class CardToCardGateway implements PaymentGatewayInterface {
  public function initiate(array $payment): array {
    return [
      'method' => 'card_to_card',
      'status' => Payment::STATUS_PENDING,
      'transaction_id' => $payment['transaction_id'] ?? Payment::generate_transaction_id(),
    ];
  }

  public function verify(array $payload): array {
    return [
      'verified' => false,
      'status' => Payment::STATUS_PENDING,
      'reason' => 'Manual operator verification required.',
    ];
  }
}
