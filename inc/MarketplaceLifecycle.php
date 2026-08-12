<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class MarketplaceLifecycle {
  public static function register(): void {
    add_action('after_switch_theme', [MarketplaceSchema::class, 'install']);
    add_action('after_switch_theme', [ProviderRepository::class, 'install']);
    add_action('init', [Marketplace::class, 'register_roles']);
    ProviderAdmin::register();
    AgencyDashboard::register();
  }
}
