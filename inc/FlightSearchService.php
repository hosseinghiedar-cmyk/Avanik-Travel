<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class FlightSearchService {
  private FlightProviderInterface $provider;

  public function __construct(FlightProviderInterface $provider) {
    $this->provider = $provider;
  }

  public function search(array $criteria): array {
    $criteria = $this->sanitize_criteria($criteria);
    if ($criteria['origin'] === '' || $criteria['destination'] === '' || $criteria['travel_date'] === '') {
      return [];
    }

    $offers = $this->provider->search($criteria);
    return array_values(array_map([FlightOffer::class, 'normalize'], is_array($offers) ? $offers : []));
  }

  private function sanitize_criteria(array $criteria): array {
    return [
      'origin' => sanitize_text_field($criteria['origin'] ?? ''),
      'destination' => sanitize_text_field($criteria['destination'] ?? ''),
      'travel_date' => sanitize_text_field($criteria['travel_date'] ?? ''),
      'passengers' => max(1, min(9, (int) ($criteria['passengers'] ?? 1))),
      'cabin' => sanitize_key($criteria['cabin'] ?? 'economy'),
    ];
  }
}
