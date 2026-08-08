<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class BookingTemplate {
  public static function render_form(): void {
    get_template_part('template-parts/booking-form');
  }

  public static function render_summary(): void {
    get_template_part('template-parts/booking-summary');
  }
}
