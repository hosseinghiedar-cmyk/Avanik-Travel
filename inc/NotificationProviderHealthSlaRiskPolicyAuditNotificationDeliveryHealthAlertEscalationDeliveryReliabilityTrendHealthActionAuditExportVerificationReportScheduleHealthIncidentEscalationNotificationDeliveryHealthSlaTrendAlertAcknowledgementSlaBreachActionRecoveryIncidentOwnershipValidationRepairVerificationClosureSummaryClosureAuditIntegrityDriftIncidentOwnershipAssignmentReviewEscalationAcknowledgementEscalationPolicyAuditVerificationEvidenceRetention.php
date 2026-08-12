<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetention {
    private const OPTION = 'avanik_sla_drift_policy_audit_evidence_retention';
    private const CAPABILITY = 'manage_options';
    private const RETENTION_DAYS = 90;

    public static function register(): void {
        add_options_page('SLA Drift Policy Evidence Retention', 'SLA Drift Policy Evidence Retention', self::CAPABILITY, 'avanik-sla-drift-policy-evidence-retention', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $evidence = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidence::evaluate();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $now = time();
        $expires_at = $now + (self::RETENTION_DAYS * DAY_IN_SECONDS);
        $state = [
            'evidence_hash'=>(string)$evidence['evidence_hash'],
            'retention_days'=>self::RETENTION_DAYS,
            'created_at'=>$now,
            'expires_at'=>$expires_at,
            'retention_status'=>'retained',
            'transition'=>empty($previous['evidence_hash']) ? 'opened' : ((string)$previous['evidence_hash'] !== (string)$evidence['evidence_hash'] ? 'refreshed' : 'steady'),
            'evaluated_at'=>$now,
        ];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Policy Evidence Retention</h1><p>Phase 140 establishes a 90-day retention window for the Phase 139 verification evidence metadata.</p><table class="widefat striped"><tbody>';
        foreach (['Evidence hash'=>$s['evidence_hash'],'Retention days'=>$s['retention_days'],'Retention status'=>strtoupper($s['retention_status']),'Created at'=>wp_date('Y-m-d H:i:s',$s['created_at']),'Expires at'=>wp_date('Y-m-d H:i:s',$s['expires_at']),'Transition'=>strtoupper($s['transition']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
