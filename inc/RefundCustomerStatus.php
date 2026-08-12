<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class RefundCustomerStatus {
 public static function get(string $refund_id,int $user_id): array|false { global $wpdb; $row=$wpdb->get_row($wpdb->prepare('SELECT r.* FROM '.RefundRepository::table_name().' r JOIN '.BookingRepository::table_name().' b ON b.booking_id=r.booking_id WHERE r.refund_id=%s LIMIT 1',$refund_id),ARRAY_A); if(!$row)return false; $owner=(int)($row['customer_user_id']??0); if($owner && $owner!==$user_id)return false; return ['refund_id'=>$row['refund_id'],'booking_id'=>$row['booking_id'],'status'=>$row['status'],'customer_refund'=>$row['customer_refund'],'currency'=>$row['currency'],'updated_at'=>$row['updated_at']]; }
}