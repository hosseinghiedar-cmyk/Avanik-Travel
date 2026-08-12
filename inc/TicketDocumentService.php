<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class TicketDocumentService {
 public static function create_download_token(string $ticket_id,int $user_id): string { $token=wp_generate_password(48,false,false); set_transient('avanik_ticket_dl_'.hash('sha256',$token),['ticket_id'=>$ticket_id,'user_id'=>$user_id],10*MINUTE_IN_SECONDS); return $token; }
 public static function consume_download_token(string $token,int $user_id): string|false { if($token==='')return false; $key='avanik_ticket_dl_'.hash('sha256',$token); $data=get_transient($key); if(!is_array($data)||((int)($data['user_id']??0)!==$user_id))return false; delete_transient($key); return sanitize_text_field($data['ticket_id']??'')?:false; }
 public static function build_filename(array $ticket): string { $id=preg_replace('/[^A-Za-z0-9_-]/','-',(string)($ticket['ticket_id']??'ticket')); return 'Avanik-'.$id.'.pdf'; }
}