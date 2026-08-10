<?php
defined('ABSPATH') || exit;
require_once __DIR__ . '/inc/NotificationCenter.php';
require_once __DIR__ . '/inc/NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryAudit.php';
\Avanik\NotificationCenter::install();
\Avanik\NotificationCenter::register();
\Avanik\NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryAudit::register();
