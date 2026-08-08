<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class Product {
  public const TOUR = 'tour';
  public const HOTEL = 'hotel';
  public const FLIGHT = 'flight';
  public const PACKAGE = 'package';

  public const DRAFT = 'draft';
  public const PENDING_REVIEW = 'pending_review';
  public const PUBLISHED = 'published';
  public const REJECTED = 'rejected';
}
