<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnership {
    private const OPTION = 'avanik_sla_breach_recovery_incident_ownership';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Recovery Incident Ownership', 'SLA Recovery Incident Ownership', self::CAPABILITY, 'avanik-sla-recovery-incident-ownership', [self::class, 'render']);
    }

    public static function assign(): void {
        if (!current_user_can(self::CAPABILITY) || !check_admin_referer('avanik_sla_incident_owner')) return;
        $user_id = isset($_POST['avanik_owner_id']) ? absint($_POST['avanik_owner_id']) : 0;
        $incident = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncident::evaluate();
        update_option(self::OPTION, ['owner_id'=>$user_id,'incident'=>$incident['incident'],'assigned_at'=>time(),'assigned_by'=>get_current_user_id()], false);
    }

    public static function state(): array {
        $s = get_option(self::OPTION, []);
        return is_array($s) ? $s : [];
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        if (isset($_POST['avanik_assign']) && $_POST['avanik_assign'] === '1') self::assign();
        $s = self::state();
        echo '<div class="wrap"><h1>SLA Recovery Incident Ownership</h1><p>Assigns an administrator owner to the current Phase 119 incident state.</p><table class="widefat striped"><tbody>';
        foreach (['Incident'=>strtoupper((string)($s['incident'] ?? 'none')),'Owner user ID'=>(int)($s['owner_id'] ?? 0),'Assigned at'=>!empty($s['assigned_at']) ? wp_date('Y-m-d H:i:s',(int)$s['assigned_at']) : '—','Assigned by user ID'=>(int)($s['assigned_by'] ?? 0)] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table><form method="post" style="margin-top:16px">'.wp_nonce_field('avanik_sla_incident_owner','_wpnonce',true,false).'<input type="number" min="0" name="avanik_owner_id" value="'.esc_attr((int)($s['owner_id'] ?? 0)).'" placeholder="Administrator user ID"><input type="hidden" name="avanik_assign" value="1"> <button class="button button-primary">Assign owner</button></form></div>';
    }
}
