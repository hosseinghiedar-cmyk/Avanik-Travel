# Phase 74 — Provider Health SLA Trend

## هدف
ارائه روند تاریخی Incident، SLA Check، Breach و Compliance در بازه‌های 7، 30، 90 و 365 روز.

## مسیر مدیریت
Settings → Provider Health SLA Trend

## منطق
- برای هر bucket زمانی Incidentهای بازشده بررسی می‌شوند.
- Policy مؤثر هر Provider از `NotificationProviderHealthSla::policy()` خوانده می‌شود.
- SLAهای با threshold صفر در محاسبه وارد نمی‌شوند.
- Compliance = `(checks - breaches) / checks * 100`.
- Incidentهای بدون داده SLA، Compliance را به `—` تبدیل می‌کنند.
- فاز فقط Read-only است و Incident، SLA یا Notification را تغییر نمی‌دهد.

## امنیت
هیچ Credential، API Key، Token، Request Body یا Response Body در Trend ذخیره یا نمایش داده نمی‌شود.

## Bootstrap
`NotificationProviderHealthSlaTrend::register()` در `wordpress/avanik/functions.php` ثبت شده است.
