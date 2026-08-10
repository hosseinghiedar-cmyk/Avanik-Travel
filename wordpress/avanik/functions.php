<?php
defined('ABSPATH') || exit;
require_once __DIR__ . '/inc/NotificationProviderHealthSlaRiskNotificationPolicyAuditMonitor.php';
require_once __DIR__ . '/inc/NotificationProviderHealthSlaRiskNotificationPolicyAudit.php';
require_once __DIR__ . '/inc/NotificationProviderHealthSlaRiskNotificationPolicy.php';
\Avanik\NotificationProviderHealthSlaRiskNotificationPolicy::register();
\Avanik\NotificationProviderHealthSlaRiskNotificationPolicyAudit::register();
\Avanik\NotificationProviderHealthSlaRiskNotificationPolicyAuditMonitor::register();
