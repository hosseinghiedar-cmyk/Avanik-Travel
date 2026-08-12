<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class TicketPdfRenderer {
 public static function render(array $ticket,array $booking=[]): string|\WP_Error { $filtered=apply_filters('avanik_render_ticket_pdf',null,$ticket,$booking); if(is_string($filtered)&&$filtered!=='')return $filtered; $html='<!doctype html><html><head><meta charset="utf-8"><title>Avanik Ticket</title><style>body{font-family:Arial,sans-serif;padding:32px}table{width:100%;border-collapse:collapse}td{padding:8px;border-bottom:1px solid #ddd}</style></head><body><h1>Avanik E-Ticket</h1><table>'; $fields=['ticket_id'=>'Ticket ID','pnr'=>'PNR','ticket_number'=>'Ticket Number','voucher_reference'=>'Voucher Reference','status'=>'Status','issued_at'=>'Issued At']; foreach($fields as $key=>$label)$html.='<tr><td><strong>'.esc_html($label).'</strong></td><td>'.esc_html((string)($ticket[$key]??'')).'</td></tr>'; return $html.'</table></body></html>'; }
}