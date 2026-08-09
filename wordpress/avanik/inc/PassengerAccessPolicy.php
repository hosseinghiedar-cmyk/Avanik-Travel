<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class PassengerAccessPolicy {
 public static function can_view(array $booking): bool { if(current_user_can('manage_options'))return true; if((int)($booking['customer_id']??0)===get_current_user_id())return true; $supplier=(int)($booking['supplier_user_id']??0); return $supplier>0&&$supplier===get_current_user_id(); }
 public static function fields_for_view(array $passenger,bool $privileged=false): array { $out=$passenger; if(!$privileged){foreach(['passport_no','national_id'] as $f)if(isset($out[$f]))$out[$f]=PassengerDataSecurity::display((string)$out[$f],$f);} return $out; }
}