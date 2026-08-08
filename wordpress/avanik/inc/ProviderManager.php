<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class ProviderManager {
  private array $providers = [];

  public function register(string $key, object $provider): void {
    if ($provider instanceof FlightProviderInterface) {
      $this->providers[$key] = $provider;
    }
  }

  public function get(string $key): ?FlightProviderInterface {
    return isset($this->providers[$key]) ? $this->providers[$key] : null;
  }

  public function search(array $criteria, array $enabled = []): array {
    $results = [];
    $keys = $enabled ?: array_keys($this->providers);
    foreach ($keys as $key) {
      $provider = $this->get($key);
      if (!$provider) continue;
      foreach ($provider->search($criteria) as $offer) {
        $offer['provider'] = $offer['provider'] ?: $key;
        $results[] = FlightOffer::normalize($offer);
      }
    }
    usort($results, static fn($a, $b) => $a['price'] <=> $b['price']);
    return $results;
  }
}
