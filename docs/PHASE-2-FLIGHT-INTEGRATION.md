# Phase 2 — Flight Provider Integration Foundation

The first Phase 2 implementation establishes a provider-agnostic flight layer.

## Added
- `FlightProviderInterface`
- `FlightOffer` normalized offer model
- `FlightSearchService`
- `NullFlightProvider` safe default adapter

## Normalized search inputs
- origin
- destination
- travel_date
- passengers (1–9)
- cabin

## Normalized offer fields
- provider
- flight number
- origin/destination
- departure/arrival timestamps
- cabin
- currency
- price
- available seats

## Important
No live supplier credentials or API calls are included yet. The Null provider deliberately returns no offers until a real supplier is selected and configured.

## Next implementation
Select the first real flight supplier/API, then add its adapter behind `FlightProviderInterface` without changing Booking or Search consumers.
