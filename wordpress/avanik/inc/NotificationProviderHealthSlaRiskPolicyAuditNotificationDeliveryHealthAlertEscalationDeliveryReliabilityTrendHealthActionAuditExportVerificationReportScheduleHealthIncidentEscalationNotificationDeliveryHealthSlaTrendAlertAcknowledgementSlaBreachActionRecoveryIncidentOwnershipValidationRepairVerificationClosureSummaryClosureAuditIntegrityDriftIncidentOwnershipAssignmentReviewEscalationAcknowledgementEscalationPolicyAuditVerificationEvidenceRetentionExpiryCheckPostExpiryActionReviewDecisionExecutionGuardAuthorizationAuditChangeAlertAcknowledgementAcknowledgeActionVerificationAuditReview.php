<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerificationAuditReview {
    private const OPTION = 'avanik_sla_drift_ack_verification_audit_review';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Acknowledgement Verification Audit Review', 'SLA Drift Acknowledgement Verification Audit Review', self::CAPABILITY, 'avanik-sla-drift-ack-audit-review', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $audit = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerificationAudit::evaluate();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $status = (string)($audit['audit_status'] ?? '');
        $valid = in_array($status, ['stable','changed'], true);
        $review_state = $valid ? 'reviewed' : 'attention_required';
        $result = [
            'audit_status'=>$status ?: 'unknown',
            'review_state'=>$review_state,
            'review_required'=>$status === 'changed',
            'fingerprint'=>(string)($audit['fingerprint'] ?? ''),
            'previous_fingerprint'=>(string)($audit['previous_fingerprint'] ?? ''),
            'reviewed_at'=>time(),
            'review_changed'=>!empty($previous['reviewed_at']) && (string)($previous['fingerprint'] ?? '') !== (string)($audit['fingerprint'] ?? ''),
        ];
        update_option(self::OPTION, $result, false);
        return $result;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Acknowledgement Verification Audit Review</h1><p>Phase 153 reviews the Phase 152 audit result and explicitly identifies whether a changed verification audit requires attention.</p><table class="widefat striped"><tbody>';
        foreach (['Audit status'=>strtoupper($s['audit_status']),'Review state'=>strtoupper(str_replace('_',' ',$s['review_state'])),'Review required'=>$s['review_required']?'YES':'NO','Fingerprint'=>$s['fingerprint'],'Previous fingerprint'=>$s['previous_fingerprint'] ?: '—','Review changed'=>$s['review_changed']?'YES':'NO','Reviewed at'=>wp_date('Y-m-d H:i:s',$s['reviewed_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
