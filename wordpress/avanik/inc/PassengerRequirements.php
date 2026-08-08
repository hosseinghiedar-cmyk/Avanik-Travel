<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class PassengerRequirements {
 public const DOMESTIC_FLIGHT='domestic_flight';
 public const INTERNATIONAL_FLIGHT='international_flight';
 public const DEFAULTS=['first_name','last_name','phone','email'];
 public const FIELDS=['first_name'=>'نام','last_name'=>'نام خانوادگی','phone'=>'موبایل','email'=>'ایمیل','national_id'=>'کد ملی','nationality'=>'ملیت','date_of_birth'=>'تاریخ تولد','passport_no'=>'شماره پاسپورت','passport_expiry'=>'تاریخ انقضای پاسپورت','gender'=>'جنسیت'];
 public static function register(): void { add_filter('avanik_passenger_requirements',[self::class,'requirements'],10,2); }
 public static function requirements(array $fields,string $product_type): array { $basic=self::DEFAULTS; if($product_type===self::DOMESTIC_FLIGHT)return self::normalize(array_merge($fields,$basic,['national_id'])); if($product_type===self::INTERNATIONAL_FLIGHT)return self::normalize(array_merge($fields,$basic,['nationality','date_of_birth','passport_no','passport_expiry'])); return self::normalize($fields); }
 public static function for_product(array $product): array { $type=sanitize_key($product['type']??$product['product_type']??''); $meta=json_decode((string)($product['metadata']??''),true)?:[]; $custom=(array)($meta['passenger_fields']??[]); $base=apply_filters('avanik_passenger_requirements',$custom,$type); return self::normalize($base); }
 public static function save(int $product_id,int $user_id,array $fields): bool { global $wpdb; foreach(ProductRepository::for_supplier($user_id) as $p){if((int)$p['id']!==$product_id)continue; $meta=json_decode((string)$p['metadata'],true)?:[]; $meta['passenger_fields']=self::normalize($fields); return false!==$wpdb->update(ProductRepository::table_name(),['metadata'=>wp_json_encode($meta),'updated_at'=>current_time('mysql'),'status'=>Product::DRAFT],['id'=>$product_id,'supplier_user_id'=>$user_id]);} return false; }
 public static function normalize(array $fields): array { $out=[]; foreach($fields as $f){$f=sanitize_key($f);if(isset(self::FIELDS[$f])&&!in_array($f,$out,true))$out[]=$f;} foreach(self::DEFAULTS as $f)if(!in_array($f,$out,true))$out[]=$f; return $out; }
 public static function labels(): array { return self::FIELDS; }
 public static function validate(array $data,array $fields): array { $errors=[]; foreach($fields as $field){if(in_array($field,['passport_no','passport_expiry'],true)&&empty($data[$field]))$errors[$field]='این فیلد برای این نوع محصول الزامی است.';if(in_array($field,['first_name','last_name'],true)&&empty($data[$field]))$errors[$field]='نام و نام خانوادگی الزامی است.';} return $errors; }
}