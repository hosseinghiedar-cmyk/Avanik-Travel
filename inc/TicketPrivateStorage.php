<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class TicketPrivateStorage {
 public static function directory(): string { $u=wp_upload_dir(); return trailingslashit($u['basedir']).'avanik-private-tickets'; }
 public static function save(string $ticket_id,string $content): string|false { $dir=self::directory(); if(!wp_mkdir_p($dir))return false; if(!file_exists($dir.'/.htaccess'))file_put_contents($dir.'/.htaccess',"Deny from all\n"); if(!file_exists($dir.'/index.php'))file_put_contents($dir.'/index.php','<?php // Silence is golden'); $name=TicketDocumentService::build_filename(['ticket_id'=>$ticket_id]); $path=trailingslashit($dir).$name; return file_put_contents($path,$content,LOCK_EX)!==false?$path:false; }
 public static function read(string $ticket_id): string|false { $path=trailingslashit(self::directory()).TicketDocumentService::build_filename(['ticket_id'=>$ticket_id]); return is_readable($path)?file_get_contents($path):false; }
}