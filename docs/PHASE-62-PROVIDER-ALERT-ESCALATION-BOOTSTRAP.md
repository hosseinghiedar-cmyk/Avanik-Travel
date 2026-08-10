# Phase 62 Bootstrap Registration

The new `NotificationProviderHealthAlertEscalation` class must be loaded and registered in the Avanik bootstrap alongside the existing provider-health alert classes.

Required bootstrap calls:

```php
require_once __DIR__ . '/inc/NotificationProviderHealthAlertEscalation.php';
\\Avanik\\NotificationProviderHealthAlertEscalation::register();
```

This is intentionally documented separately because the repository bootstrap changed after the Phase 59/61 snapshots and must be updated using its current blob SHA before applying a sequential contents update.
