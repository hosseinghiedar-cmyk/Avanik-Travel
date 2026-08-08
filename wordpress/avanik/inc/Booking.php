<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class Booking {
  public const STATUS_PENDING = 'pending';
  public const STATUS_CONFIRMED = 'confirmed';
  public const STATUS_CANCELLED = 'cancelled';

  public static function statuses(): array {
    return [
      self::STATUS_PENDING => 'در انتظار',
      self::STATUS_CONFIRMED => 'تأیید شده',
      self::STATUS_CANCELLED => 'لغو شده',
    ];
  }

  public static function generate_id(): string {
    return 'AVN-' . strtoupper(wp_generate_password(10, false, false));
  }

  public static function is_valid_status(string $status): bool {
    return array_key_exists($status, self::statuses());
  }
}
