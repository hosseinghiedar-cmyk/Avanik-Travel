<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecision {
    private const OPTION = 'avanik_sla_drift_post_expiry_review_decision';
    private const CAPABILITY = 'manage_options';
    private const ALLOWED = ['retain', 'archive', 'escalate'];

    public static function register(): void {
        add_options_page('SLA Drift Post-Expiry Review Decision', 'SLA Drift Post-Expiry Review Decision', self::CAPABILITY, 'avanik-sla-drift-post-expiry-review-decision', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $review = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReview::evaluate();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $decision = sanitize_key((string)($previous['decision'] ?? 'unreviewed'));
        if (!in_array($decision, self::ALLOWED, true)) $decision = 'unreviewed';
        $required = !empty($review['review_required']);
        $state = [
            'evidence_hash'=>(string)$review['evidence_hash'],
            'review_required'=>$required,
            'decision'=>$required ? $decision : 'not_required',
            'decision_valid'=>$required ? in_array($decision, self::ALLOWED, true) : true,
            'decision_state'=>$required && $decision === 'unreviewed' ? 'awaiting_decision' : ($required ? 'decided' : 'not_applicable'),
            'allowed_decisions'=>self::ALLOWED,
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Post-Expiry Review Decision</h1><p>Phase 144 defines the controlled decision vocabulary for manual review. It records no automatic decision.</p><table class="widefat striped"><tbody>';
        foreach (['Evidence hash'=>$s['evidence_hash'],'Review required'=>$s['review_required']?'YES':'NO','Decision'=>strtoupper(str_replace('_',' ',$s['decision'])),'Decision valid'=>$s['decision_valid']?'YES':'NO','Decision state'=>strtoupper(str_replace('_',' ',$s['decision_state'])),'Allowed decisions'=>implode(', ', $s['allowed_decisions']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
