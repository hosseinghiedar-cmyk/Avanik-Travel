<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummary {
    private const OPTION = 'avanik_sla_ownership_repair_closure_summary';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Ownership Closure Summary', 'SLA Ownership Closure Summary', self::CAPABILITY, 'avanik-sla-ownership-closure-summary', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $closure = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosure::evaluate();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $summary_status = !empty($closure['closed']) ? 'closed' : 'in_progress';
        $transition = $summary_status !== ($previous['summary_status'] ?? '') ? ($summary_status === 'closed' ? 'closed' : 'opened') : 'steady';
        $state = [
            'summary_status'=>$summary_status,
            'transition'=>$transition,
            'verification_status'=>(string)$closure['verification_status'],
            'incident'=>(string)$closure['incident'],
            'closed_at'=>(int)$closure['closed_at'],
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Ownership Closure Summary</h1><p>Phase 126 exposes a compact lifecycle summary after the Phase 125 closure decision.</p><table class="widefat striped"><tbody>';
        foreach (['Summary status'=>strtoupper(str_replace('_',' ',$s['summary_status'])),'Transition'=>strtoupper($s['transition']),'Verification status'=>strtoupper($s['verification_status']),'Incident'=>strtoupper($s['incident']),'Closed at'=>$s['closed_at'] ? wp_date('Y-m-d H:i:s',$s['closed_at']) : '—','Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
