<?php
namespace Avanik;

defined('ABSPATH') || exit;

interface FlightProviderInterface {
  /**
   * @param array $criteria origin, destination, travel_date, passengers, cabin
   * @return array normalized flight offers
   */
  public function search(array $criteria): array;

  /** @return array normalized booking result */
  public function book(array $offer, array $passengers): array;
}
