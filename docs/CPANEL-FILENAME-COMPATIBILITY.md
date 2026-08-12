# cPanel Filename Compatibility Fix

The deepest PHP filename in the Avanik notification SLA chain exceeded the practical Windows checkout path budget when combined with the repository/theme directory path.

The implementation file was moved to:

`wordpress/avanik/inc/sla_escalation_notification_delivery_health.php`

The PHP class name remains unchanged for runtime compatibility. The parent notification class explicitly loads the new short filename.

This is a hosting-package compatibility fix; it does not claim that cPanel runtime tests or production deployment have been executed.
