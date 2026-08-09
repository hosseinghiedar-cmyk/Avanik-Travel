<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class PassengerDataSecurity {
 public static function register(): void { add_filter('avanik_passenger_display_value',[self::class,'display'],10,2); }
 public static function can_view(array $passenger,int $booking_customer_id=0): bool { if(current_user_can('manage_options'))return true; if($booking_customer_id && get_current_user_id()===$booking_customer_id)return true; return false; }
 public static function display(string $value,string $field): string { if(!$value)return ''; if(in_array($field,['passport_no','national_id'],true)){ $len=mb_strlen($value); if($len<=4)return str_repeat('*',$len); return mb_substr($value,0,2).'*****'.mb_substr($value,-2); } return $value; }
 public static function encryption_ready(): bool { return defined('AVANIK_DATA_KEY') && strlen((string)AVANIK_DATA_KEY)>=32; }
 public static function encrypt(string $value): string { if($value===''||!self::encryption_ready())return $value; $key=hash('sha256',(string)AVANIK_DATA_KEY,true); $iv=random_bytes(16); $cipher=openssl_encrypt($value,'AES-256-CBC',$key,OPENSSL_RAW_DATA,$iv); return base64_encode($iv.$cipher); }
 public static function decrypt(string $value): string { if($value===''||!self::encryption_ready())return $value; $raw=base64_decode($value,true); if($raw===false||strlen($raw)<=16)return $value; $key=hash('sha256',(string)AVANIK_DATA_KEY,true); $plain=openssl_decrypt(substr($raw,16),'AES-256-CBC',$key,OPENSSL_RAW_DATA,substr($raw,0,16)); return $plain===false?$value:$plain; }
}