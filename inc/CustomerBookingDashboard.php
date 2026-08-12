<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class CustomerBookingDashboard {
 public static function register(): void { add_shortcode('avanik_customer_bookings',[self::class,'render']); }
 public static function render(): string { if(!is_user_logged_in())return '<p>برای مشاهده رزروها وارد حساب شوید.</p>'; $rows=BookingRepository::for_customer(get_current_user_id()); ob_start(); ?><section class="avanik-bookings" dir="rtl"><h2>رزروهای من</h2><table><thead><tr><th>رزرو</th><th>تاریخ</th><th>مبلغ</th><th>وضعیت</th><th>پرداخت</th></tr></thead><tbody><?php foreach($rows as $b): ?><tr><td>#<?php echo esc_html($b['booking_id']); ?></td><td><?php echo esc_html($b['travel_date']??''); ?></td><td><?php echo esc_html(number_format((float)$b['total_amount']).' '.$b['currency']); ?></td><td><?php echo esc_html($b['status']); ?></td><td><a href="<?php echo esc_url(home_url('/payment/?booking_id='.(int)$b['booking_id'])); ?>">پرداخت</a></td></tr><?php endforeach; if(!$rows): ?><tr><td colspan="5">رزروی ثبت نشده است.</td></tr><?php endif; ?></tbody></table></section><?php return (string)ob_get_clean(); }
}