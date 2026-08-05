# Sprint 013 Integration Notes

## SCSS
`src/scss/pages/_index.scss` is the page-level aggregation point.

The existing page partials are:
- dashboard
- flight-details
- flight-search
- home
- hotel-booking
- hotel-details
- hotel-search
- payment
- tour-details

Additive integration should preserve the existing partials and avoid duplicate imports.

## WordPress
`wordpress/avanik/inc/integration.php` contains non-destructive helper functions for page detection and navigation URLs.

It is intentionally not auto-required by this sprint because the existing `functions.php` should be reviewed before adding another include.

## QA
Check:
1. Home page loads.
2. Flight search page loads.
3. Flight details page loads.
4. Payment page loads.
5. Booking confirmation page loads.
6. Dashboard page loads.
7. Hotel search/details/booking pages load.
8. Navigation links do not produce 404 responses.
9. RTL layout remains intact.
10. Mobile layout remains usable.
