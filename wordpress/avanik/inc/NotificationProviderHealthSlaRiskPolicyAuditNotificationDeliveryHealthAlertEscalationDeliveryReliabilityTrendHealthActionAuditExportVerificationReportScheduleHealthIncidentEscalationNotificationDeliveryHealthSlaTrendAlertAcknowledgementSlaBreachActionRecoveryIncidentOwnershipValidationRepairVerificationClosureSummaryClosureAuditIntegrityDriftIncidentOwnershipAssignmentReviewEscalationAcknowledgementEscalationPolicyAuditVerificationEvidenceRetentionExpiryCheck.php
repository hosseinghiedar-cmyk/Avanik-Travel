<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheck {
    private const OPTION = 'avanik_sla_drift_policy_audit_evidence_retention_expiry';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Policy Evidence Retention Expiry Check', 'SLA Drift Policy Evidence Retention Expiry Check', self::CAPABILITY, 'avanik-sla-drift-policy-evidence-retention-expiry', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $retention = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetention::evaluate();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $now = time();
        $expired = (int)$retention['expires_at'] <= $now;
        $state = [
            'evidence_hash'=>(string)$retention['evidence_hash'],
            'expires_at'=>(int)$retention['expires_at'],
            'checked_at'=>$now,
            'expired'=>$expired,
            'expiry_status'=>$expired ? 'expired' : 'active',
            'transition'=>$expired && empty($previous['expired']) ? 'opened' : ($expired ? 'steady' : (!empty($previous['expired']) ? 'resolved' : 'none')),
        ];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Policy Evidence Retention Expiry Check</h1><p>Phase 141 checks whether the Phase 140 retention window has expired without deleting evidence automatically.</p><table class="widefat striped"><tbody>';
        foreach (['Evidence hash'=>$s['evidence_hash'],'Expires at'=>wp_date('Y-m-d H:i:s',$s['expires_at']),'Checked at'=>wp_date('Y-m-d H:i:s',$s['checked_at']),'Expired'=>$s['expired']?'YES':'NO','Expiry status'=>strtoupper($s['expiry_status']),'Transition'=>strtoupper($s['transition'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
