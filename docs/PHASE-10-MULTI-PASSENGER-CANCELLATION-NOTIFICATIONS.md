# Phase 10 — Multi-Passenger, Cancellation & Notifications

Implemented:
- Dedicated booking passenger table foundation
- Multiple passenger records per booking
- Customer/admin cancellation foundation
- Availability release on cancellation
- Confirmation/cancellation email hooks using WordPress mail
- Passenger model separated from the existing booking record without removing legacy passenger fields

Important: refund calculation and payment reversal are intentionally policy-driven and are not automatically performed by cancellation yet. This avoids issuing an incorrect refund before the agency's cancellation policy and gateway capabilities are configured.

Next: connect passenger editor to the booking UI, define cancellation/refund policy rules, agency-side booking actions, notification templates/settings, and the configurable payment gateway bridge.