<?php
defined('ABSPATH') || exit;
require_once __DIR__ . '/inc/NotificationProviderHealthSlaReport.php';
require_once __DIR__ . '/inc/NotificationProviderHealthSlaSettings.php';
\Avanik\NotificationProviderHealthSlaReport::register();
\Avanik\NotificationProviderHealthSlaSettings::register();
