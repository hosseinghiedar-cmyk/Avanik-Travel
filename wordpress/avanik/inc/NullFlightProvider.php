<?php
namespace Avanik;

defined('ABSPATH') || exit;

/**
 * Safe placeholder until a real supplier/API is selected.
 */
final class NullFlightProvider implements FlightProviderInterface {
  public function search(array $criteria): array {
    return [];
  }

  public function book(array $offer, array $passengers): array {
    return [
      'success' => false,
      'error' => 'No live flight provider configured.',
    ];
  }
}
