<?php
namespace Avanik;
defined('ABSPATH') || exit;
interface TicketingProviderInterface {
 public function issue_ticket(array $booking): array;
 public function get_ticket(string $provider_reference): array;
 public function cancel_ticket(string $provider_reference): array;
}