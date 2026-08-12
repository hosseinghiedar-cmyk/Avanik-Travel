<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class Inventory {
  public const DRAFT = 'draft';
  public const PENDING_REVIEW = 'pending_review';
  public const PUBLISHED = 'published';
  public const REJECTED = 'rejected';

  public static function can_publish(int $user_id): bool {
    return AgencyOnboarding::can_sell($user_id) || user_can($user_id, 'manage_options');
  }
}
