<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class PassengerCustomFields {
 public static function types(): array { return ['text'=>'Text','date'=>'Date','select'=>'Select']; }
 public static function get(array $product): array { $meta=json_decode((string)($product['metadata']??''),true)?:[]; $fields=$meta['passenger_custom_fields']??[]; return is_array($fields)?array_values(array_filter($fields,[self::class,'valid'])):[]; }
 private static function valid($f): bool { return is_array($f)&&!empty($f['key'])&&!empty($f['label'])&&isset(self::types()[$f['type']]); }
 public static function normalize(array $fields): array { $out=[]; foreach($fields as $f){if(!is_array($f))continue;$key=sanitize_key($f['key']??'');$label=sanitize_text_field($f['label']??'');$type=sanitize_key($f['type']??'text');if(!$key||!$label||!isset(self::types()[$type]))continue;$out[]=['key'=>$key,'label'=>$label,'type'=>$type,'required'=>!empty($f['required']),'options'=>array_values(array_filter(array_map('sanitize_text_field',(array)($f['options']??[]))))];} return $out; }
 public static function save(int $product_id,int $user_id,array $fields): bool { global $wpdb; foreach(ProductRepository::for_supplier($user_id) as $p){if((int)$p['id']!==$product_id)continue;$meta=json_decode((string)$p['metadata'],true)?:[];$meta['passenger_custom_fields']=self::normalize($fields);return false!==$wpdb->update(ProductRepository::table_name(),['metadata'=>wp_json_encode($meta),'updated_at'=>current_time('mysql'),'status'=>Product::DRAFT],['id'=>$product_id,'supplier_user_id'=>$user_id]);}return false; }
 public static function validate(array $data,array $fields): array { $errors=[]; foreach(self::normalize($fields) as $f){if($f['required']&&empty($data[$f['key']]))$errors[$f['key']]=$f['label'].' الزامی است.';} return $errors; }
}