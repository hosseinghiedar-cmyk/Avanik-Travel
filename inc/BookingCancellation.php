<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class BookingCancellation {
 public static function register(): void { add_action('admin_post_avanik_cancel_booking',[self::class,'cancel']); }
 public static function cancel(): void { if(!is_user_logged_in()||!check_admin_referer('avanik_cancel_booking'))wp_die('Unauthorized'); $id=sanitize_text_field(wp_unslash($_POST['booking_id']??'')); $b=BookingRepository::find_by_id($id); if(!$b||((int)$b['customer_id']!==get_current_user_id()&&!current_user_can('manage_options')))wp_die('Forbidden'); if(in_array($b['status'],['cancelled','completed'],true))wp_die('Booking cannot be cancelled'); global $wpdb; $wpdb->update(BookingRepository::table_name(),['status'=>Booking::STATUS_CANCELLED,'updated_at'=>current_time('mysql')],['booking_id'=>$id]); do_action('avanik_booking_cancelled',$id); do_action('avanik_booking_cancelled_customer',$id,$b); wp_safe_redirect(home_url('/booking/?booking_id='.rawurlencode($id))); exit; }
}