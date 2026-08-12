<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class RefundDashboard {
 public static function register(): void { add_shortcode('avanik_refunds',[self::class,'customer']); add_shortcode('avanik_agency_refunds',[self::class,'agency']); }
 public static function customer(): string { if(!is_user_logged_in())return '<p>Please log in.</p>'; $uid=get_current_user_id(); $rows=RefundRepository::for_customer($uid); return self::table($rows,false); }
 public static function agency(): string { if(!is_user_logged_in())return '<p>Please log in.</p>'; $uid=get_current_user_id(); $rows=RefundRepository::for_agency_user($uid); return self::table($rows,true); }
 private static function table(array $rows,bool $agency): string { ob_start(); echo '<div class="avanik-refunds"><table><thead><tr><th>Refund</th><th>Booking</th><th>Amount</th><th>Status</th><th>Updated</th></tr></thead><tbody>'; foreach($rows as $r){echo '<tr><td>'.esc_html($r['refund_id']).'</td><td>'.esc_html($r['booking_id']).'</td><td>'.esc_html($r['customer_refund'].' '.$r['currency']).'</td><td>'.esc_html($r['status']).'</td><td>'.esc_html($r['updated_at']??'').'</td></tr>';} echo '</tbody></table></div>'; return ob_get_clean(); }
}