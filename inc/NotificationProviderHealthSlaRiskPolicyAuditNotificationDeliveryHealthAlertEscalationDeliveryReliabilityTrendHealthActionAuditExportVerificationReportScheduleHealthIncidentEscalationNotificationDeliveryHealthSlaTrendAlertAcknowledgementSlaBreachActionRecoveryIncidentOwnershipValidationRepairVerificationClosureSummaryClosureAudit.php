<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAudit {
    private const OPTION = 'avanik_sla_ownership_closure_audit';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Ownership Closure Audit', 'SLA Ownership Closure Audit', self::CAPABILITY, 'avanik-sla-ownership-closure-audit', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $summary = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummary::evaluate();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $status = (string)$summary['summary_status'];
        $transition = (string)$summary['transition'];
        $recorded = $transition !== 'steady';
        $state = [
            'summary_status'=>$status,
            'transition'=>$transition,
            'recorded'=>$recorded,
            'last_recorded_transition'=>$recorded ? $transition : (string)($previous['last_recorded_transition'] ?? ''),
            'recorded_at'=>$recorded ? time() : (int)($previous['recorded_at'] ?? 0),
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Ownership Closure Audit</h1><p>Phase 127 records lifecycle transition metadata for the Phase 126 closure summary.</p><table class="widefat striped"><tbody>';
        foreach (['Summary status'=>strtoupper(str_replace('_',' ',$s['summary_status'])),'Transition'=>strtoupper($s['transition']),'Recorded'=>$s['recorded']?'YES':'NO','Last recorded transition'=>strtoupper($s['last_recorded_transition']),'Recorded at'=>$s['recorded_at'] ? wp_date('Y-m-d H:i:s',$s['recorded_at']) : '—','Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
