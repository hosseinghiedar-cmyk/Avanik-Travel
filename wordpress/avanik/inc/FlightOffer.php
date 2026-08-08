<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class FlightOffer {
  public static function normalize(array $offer): array {
    return [
      'id' => sanitize_text_field($offer['id'] ?? ''),
      'provider' => sanitize_key($offer['provider'] ?? ''),
      'flight_number' => sanitize_text_field($offer['flight_number'] ?? ''),
      'origin' => sanitize_text_field($offer['origin'] ?? ''),
      'destination' => sanitize_text_field($offer['destination'] ?? ''),
      'departure_at' => sanitize_text_field($offer['departure_at'] ?? ''),
      'arrival_at' => sanitize_text_field($offer['arrival_at'] ?? ''),
      'cabin' => sanitize_key($offer['cabin'] ?? 'economy'),
      'currency' => sanitize_text_field($offer['currency'] ?? 'IRR'),
      'price' => (float) ($offer['price'] ?? 0),
      'available_seats' => max(0, (int) ($offer['available_seats'] ?? 0)),
    ];
  }
}
