# Phase 15 — Agency Passenger Custom Fields

Added a protected WordPress admin endpoint for saving per-product passenger custom fields. It uses capability and nonce checks and delegates normalization/validation to `PassengerCustomFields`.

Supported field types remain text, date and select. Select values are validated against the configured options.

The next integration step is rendering the editor UI inside the agency product editor and persisting custom values into each booking passenger record during checkout. Sensitive values must continue to follow the passenger-data security policy.