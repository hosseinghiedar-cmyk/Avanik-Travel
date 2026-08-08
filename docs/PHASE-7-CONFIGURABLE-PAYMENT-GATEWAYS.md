# Phase 7 — Configurable Payment Gateways

Payment integration is now configuration-driven instead of hard-coded.

## WordPress settings

Admin → Avanik → Payment Settings

Available configuration:
- Enable/disable payments
- Default gateway
- ZarinPal mode: disabled / sandbox / production / plugin
- Merchant ID
- Custom endpoint
- Custom callback URL
- ZarinPal plugin compatibility flag

## Architecture

The payment core uses `PaymentGatewayInterface` and separate gateway adapters. ZarinPal is represented by `ZarinPalGateway` and does not embed production credentials or assume a fixed API version.

The `plugin` mode intentionally leaves the final bridge to the installed ZarinPal WordPress plugin for the integration step, so the Avanik core can remain independent of the plugin vendor/version.

Next:
- build a small adapter/bridge that detects supported ZarinPal plugin APIs/hooks on the actual WordPress installation
- implement real request/verify only after the installed plugin/version or official API contract is known
- add booking availability locking and customer/agency booking dashboards.