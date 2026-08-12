<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class PaymentLifecycle {
  public static function register(): void {
    add_action('after_switch_theme', [PaymentSchema::class, 'install']);
  }
}
