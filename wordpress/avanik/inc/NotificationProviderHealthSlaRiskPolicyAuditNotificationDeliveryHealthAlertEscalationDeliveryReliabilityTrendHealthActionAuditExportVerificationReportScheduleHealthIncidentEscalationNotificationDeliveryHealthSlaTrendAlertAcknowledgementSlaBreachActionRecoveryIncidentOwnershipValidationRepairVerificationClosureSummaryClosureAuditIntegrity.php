<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrity {
    private const OPTION = 'avanik_sla_ownership_closure_audit_integrity';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Ownership Closure Audit Integrity', 'SLA Ownership Closure Audit Integrity', self::CAPABILITY, 'avanik-sla-ownership-closure-audit-integrity', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $audit = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAudit::evaluate();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $fingerprint = hash('sha256', wp_json_encode([
            'summary_status'=>$audit['summary_status'],
            'transition'=>$audit['transition'],
            'last_recorded_transition'=>$audit['last_recorded_transition'],
            'recorded_at'=>$audit['recorded_at'],
        ]));
        $state = [
            'fingerprint'=>$fingerprint,
            'previous_fingerprint'=>(string)($previous['fingerprint'] ?? ''),
            'changed'=>$fingerprint !== (string)($previous['fingerprint'] ?? ''),
            'audit_transition'=>(string)$audit['transition'],
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Ownership Closure Audit Integrity</h1><p>Phase 128 fingerprints the Phase 127 lifecycle metadata to detect unexpected changes.</p><table class="widefat striped"><tbody>';
        foreach (['Audit transition'=>strtoupper($s['audit_transition']),'Changed'=>$s['changed']?'YES':'NO','Fingerprint'=>$s['fingerprint'],'Previous fingerprint'=>$s['previous_fingerprint'] ?: '—','Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
