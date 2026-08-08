<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class Payment {
  public const STATUS_PENDING = 'pending';
  public const STATUS_PAID = 'paid';
  public const STATUS_FAILED = 'failed';
  public const STATUS_CANCELLED = 'cancelled';

  public static function statuses(): array {
    return [
      self::STATUS_PENDING => 'در انتظار پرداخت',
      self::STATUS_PAID => 'پرداخت موفق',
      self::STATUS_FAILED => 'پرداخت ناموفق',
      self::STATUS_CANCELLED => 'لغو شده',
    ];
  }

  public static function generate_transaction_id(): string {
    return 'PAY-' . strtoupper(wp_generate_password(12, false, false));
  }

  public static function is_valid_status(string $status): bool {
    return array_key_exists($status, self::statuses());
  }
}
