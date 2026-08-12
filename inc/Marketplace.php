<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class Marketplace {
  public const ROLE_SUPPLIER = 'avanik_supplier';
  public const ROLE_AGENT = 'avanik_agent';

  public static function register_roles(): void {
    add_role(self::ROLE_SUPPLIER, 'Avanik Supplier', ['read' => true]);
    add_role(self::ROLE_AGENT, 'Avanik Agency', ['read' => true]);
  }

  public static function can_publish(): bool {
    return current_user_can('manage_options') || current_user_can('publish_posts');
  }
}
