<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDrift {
    private const OPTION = 'avanik_sla_ownership_closure_audit_integrity_drift';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Closure Audit Integrity Drift', 'SLA Closure Audit Integrity Drift', self::CAPABILITY, 'avanik-sla-closure-audit-integrity-drift', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $integrity = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrity::evaluate();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $drift = !empty($integrity['changed']) && !empty($integrity['previous_fingerprint']);
        $state = [
            'drift_detected'=>$drift,
            'current_fingerprint'=>(string)$integrity['fingerprint'],
            'previous_fingerprint'=>(string)$integrity['previous_fingerprint'],
            'severity'=>$drift ? 'warning' : 'none',
            'transition'=>$drift && empty($previous['drift_detected']) ? 'opened' : ($drift ? 'steady' : (!empty($previous['drift_detected']) ? 'resolved' : 'none')),
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Closure Audit Integrity Drift</h1><p>Phase 129 detects a persisted fingerprint change after Phase 128 integrity verification.</p><table class="widefat striped"><tbody>';
        foreach (['Drift detected'=>$s['drift_detected']?'YES':'NO','Severity'=>strtoupper($s['severity']),'Transition'=>strtoupper($s['transition']),'Current fingerprint'=>$s['current_fingerprint'],'Previous fingerprint'=>$s['previous_fingerprint'] ?: '—','Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
