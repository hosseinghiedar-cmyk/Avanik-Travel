<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class RefundSettlementFields {
 public static function install(): void { global $wpdb; $t=RefundRepository::table_name(); $cols=$wpdb->get_col("DESCRIBE {$t}",0); if(!in_array('settled_amount',$cols,true))$wpdb->query("ALTER TABLE {$t} ADD settled_amount decimal(20,4) NOT NULL DEFAULT 0 AFTER customer_refund"); }
 public static function set_settled_amount(string $refund_id,float $amount): bool { global $wpdb; return false!==$wpdb->update(RefundRepository::table_name(),['settled_amount'=>max(0,$amount),'updated_at'=>current_time('mysql')],['refund_id'=>$refund_id],['%f','%s'],['%s']); }
}