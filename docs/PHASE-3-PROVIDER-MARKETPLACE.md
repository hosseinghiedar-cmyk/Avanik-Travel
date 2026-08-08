# Phase 3 — Provider & Agency Marketplace Foundation

## Vision
Avanik is not locked to external providers. It can consume external APIs, publish its own inventory, and later allow approved travel agencies/suppliers to sell through Avanik.

## Provider layer
- ProviderManager
- ProviderRepository
- configurable provider records
- enable/disable and priority fields
- provider adapters remain behind interfaces

## Marketplace layer
- Supplier/Agency roles
- Supplier profile schema
- approval status
- commission rate
- settings storage

## Intended marketplace flow
Agency registration → admin approval → supplier profile → create/import inventory → admin moderation → publish → customer booking → payment → commission settlement.

## Important security rule
Agency users must not receive WordPress administrator capabilities. Publishing, price changes, refunds, and settlement actions must be permission-controlled and audited.

## Future phases
- Provider admin UI
- Agency dashboard
- Inventory management for flights/tours/hotels
- moderation workflow
- commissions and settlement
- provider API credentials vault
- audit log
