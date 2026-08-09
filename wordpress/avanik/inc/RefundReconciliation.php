<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class RefundReconciliation {
 public static function register(): void { add_action('admin_post_avanik_refund_reconcile',[self::class,'handle']); }
 public static function reconcile(string $refund_id): array|false { global $wpdb; $r=$wpdb->get_row($wpdb->prepare('SELECT * FROM '.RefundRepository::table_name().' WHERE refund_id=%s',$refund_id),ARRAY_A); if(!$r)return false; $expected=(float)$r['customer_refund']; $actual=(float)($r['settled_amount']??0); return ['refund_id'=>$refund_id,'expected'=>$expected,'actual'=>$actual,'difference'=>$actual-$expected,'status'=>abs($actual-$expected)<0.0001?'matched':'discrepancy']; }
 public static function handle(): void { if(!current_user_can('manage_options'))wp_die('Forbidden',403); check_admin_referer('avanik_refund_reconcile'); $id=sanitize_text_field(wp_unslash($_POST['refund_id']??'')); $result=self::reconcile($id); if($result) RefundAuditLog::record($id,get_current_user_id(),'reconciled',wp_json_encode($result)); wp_safe_redirect(wp_get_referer()?:admin_url('tools.php?page=avanik-refunds')); exit; }
}