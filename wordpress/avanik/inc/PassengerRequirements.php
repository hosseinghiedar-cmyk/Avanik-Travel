<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class PassengerRequirements {
 public const DOMESTIC_FLIGHT='domestic_flight';
 public const INTERNATIONAL_FLIGHT='international_flight';
 public static function register(): void { add_filter('avanik_passenger_requirements',[self::class,'requirements'],10,2); }
 public static function requirements(array $fields,string $product_type): array { $basic=['first_name','last_name','phone','email']; if($product_type===self::DOMESTIC_FLIGHT)return array_merge($fields,$basic,['national_id']); if($product_type===self::INTERNATIONAL_FLIGHT)return array_merge($fields,$basic,['nationality','date_of_birth','passport_no','passport_expiry']); return $fields; }
 public static function for_product(array $product): array { $type=sanitize_key($product['product_type']??''); $custom=apply_filters('avanik_passenger_requirements',[], $type); $custom=apply_filters('avanik_product_passenger_requirements',$custom,$product); return array_values(array_unique($custom)); }
 public static function validate(array $data,array $fields): array { $errors=[]; foreach($fields as $field){ if(in_array($field,['passport_no','passport_expiry'],true)&&empty($data[$field]))$errors[$field]='این فیلد برای این نوع محصول الزامی است.'; if(in_array($field,['first_name','last_name'],true)&&empty($data[$field]))$errors[$field]='نام و نام خانوادگی الزامی است.'; } return $errors; }
}