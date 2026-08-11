<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReview {
    private const OPTION = 'avanik_sla_drift_post_expiry_review';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Post-Expiry Review', 'SLA Drift Post-Expiry Review', self::CAPABILITY, 'avanik-sla-drift-post-expiry-review', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $action = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryAction::evaluate();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $pending = $action['status'] === 'pending_review';
        $state = [
            'evidence_hash'=>(string)$action['evidence_hash'],
            'review_required'=>$pending,
            'review_status'=>$pending ? 'pending' : 'not_required',
            'review_decision'=>'unreviewed',
            'previous_review_status'=>(string)($previous['review_status'] ?? ''),
            'transition'=>$pending && empty($previous['review_required']) ? 'opened' : ($pending ? 'steady' : (!empty($previous['review_required']) ? 'resolved' : 'none')),
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Post-Expiry Review</h1><p>Phase 143 creates an explicit manual-review state for expired evidence; it does not delete or alter evidence.</p><table class="widefat striped"><tbody>';
        foreach (['Evidence hash'=>$s['evidence_hash'],'Review required'=>$s['review_required']?'YES':'NO','Review status'=>strtoupper($s['review_status']),'Review decision'=>strtoupper($s['review_decision']),'Previous review status'=>$s['previous_review_status'] ?: '—','Transition'=>strtoupper($s['transition']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
