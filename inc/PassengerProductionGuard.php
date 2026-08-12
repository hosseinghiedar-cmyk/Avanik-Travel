<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class PassengerProductionGuard {
 public static function require_key_for_sensitive_write(): void { if(defined('WP_ENVIRONMENT_TYPE')&&WP_ENVIRONMENT_TYPE==='production'&&!PassengerDataSecurity::encryption_ready())wp_die('Avanik passenger-data encryption key is not configured.'); }
}