@php
    $trend = collect($report['trend']);
    $maxTrend = max(1, (float) $trend->flatMap(fn ($day) => [$day['orders'], $day['billing'], $day['collections']])->max());
    $money = fn ($amount) => '₱' . number_format((float) $amount, 2);
    $startLabel = \Carbon\Carbon::parse($report['start_date'])->format('M d, Y');
    $endLabel = \Carbon\Carbon::parse($report['end_date'])->format('M d, Y');
@endphp
<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Sales &amp; Collections Summary</title></head>
<body style="margin:0;padding:24px 10px;background:#edf1f4;color:#263941;font-family:Arial,Helvetica,sans-serif;font-size:14px;line-height:1.5;">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="center">
  <table role="presentation" width="680" cellspacing="0" cellpadding="0" border="0" style="width:100%;max-width:680px;background:#ffffff;border:1px solid #e2e9e5;border-radius:9px;overflow:hidden;">
    <tr><td style="padding:21px 28px;background:#17363a;color:#ffffff;">
      <div style="font-size:17px;font-weight:bold;letter-spacing:2px;"><span style="color:#f2a16b;"></span></div>
      <div style="padding-top:4px;color:#b7c9c3;font-size:9px;letter-spacing:2px;text-transform:uppercase;">Billing &amp; Operations</div>
    </td></tr>
    <tr><td style="padding:26px 28px 8px;">
      <div style="color:#bd6944;font-size:10px;font-weight:bold;letter-spacing:1.5px;text-transform:uppercase;">Daily commercial snapshot</div>
      <h1 style="margin:5px 0 7px;color:#17363a;font-size:23px;line-height:1.25;">Sales &amp; Collections · 30-Day Trend</h1>
      <p style="margin:0;color:#6f7e7d;font-size:13px;">A rolling view of sales activity, collections, and current balances.</p>
      <div style="display:inline-block;margin-top:14px;padding:6px 10px;border-radius:4px;background:#f1f5ef;color:#49614d;font-size:11px;font-weight:bold;">{{ $startLabel }} – {{ $endLabel }}</div>
    </td></tr>
    <tr><td style="padding:14px 20px 0;">
      <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
          <td width="50%" style="padding:7px;"><div style="padding:13px;border:1px solid #e6ebe5;border-top:3px solid #de7950;border-radius:6px;"><div style="color:#819087;font-size:10px;font-weight:bold;text-transform:uppercase;">Sales Orders</div><div style="padding-top:5px;color:#203c3d;font-size:19px;font-weight:bold;">{{ $money($report['order_sales']) }}</div><div style="color:#929e97;font-size:10px;">{{ number_format($report['order_count']) }} approved orders · 30 days</div></div></td>
          <td width="50%" style="padding:7px;"><div style="padding:13px;border:1px solid #e6ebe5;border-top:3px solid #5285a2;border-radius:6px;"><div style="color:#819087;font-size:10px;font-weight:bold;text-transform:uppercase;">Direct Billing</div><div style="padding-top:5px;color:#203c3d;font-size:19px;font-weight:bold;">{{ $money($report['direct_billing_amount']) }}</div><div style="color:#929e97;font-size:10px;">{{ number_format($report['direct_billing_count']) }} standalone billings · 30 days</div></div></td>
        </tr><tr>
          <td width="50%" style="padding:7px;"><div style="padding:13px;border:1px solid #e6ebe5;border-top:3px solid #61a37e;border-radius:6px;"><div style="color:#819087;font-size:10px;font-weight:bold;text-transform:uppercase;">Collections</div><div style="padding-top:5px;color:#203c3d;font-size:19px;font-weight:bold;">{{ $money($report['collections']) }}</div><div style="color:#929e97;font-size:10px;">{{ number_format($report['collection_count']) }} payments · 30 days</div></div></td>
          <td width="50%" style="padding:7px;"><div style="padding:13px;border:1px solid #e6ebe5;border-top:3px solid #c69a42;border-radius:6px;"><div style="color:#819087;font-size:10px;font-weight:bold;text-transform:uppercase;">Open Receivables</div><div style="padding-top:5px;color:#203c3d;font-size:19px;font-weight:bold;">{{ $money($report['open_ar_amount']) }}</div><div style="color:#929e97;font-size:10px;">{{ number_format($report['open_ar_count']) }} unpaid/partial invoices · current</div></div></td>
        </tr>
      </table>
    </td></tr>
    <tr><td style="padding:22px 28px 0;">
      <h2 style="margin:0;color:#203b3d;font-size:15px;">Sales and collection trend</h2>
      <p style="margin:3px 0 10px;color:#929e97;font-size:10px;">Daily values · PHP</p>
      <div style="margin-bottom:8px;color:#70817b;font-size:10px;">
        <span style="color:#de7950;">●</span> Sales Orders&nbsp;&nbsp;&nbsp;
        <span style="color:#5285a2;">●</span> Direct Billing&nbsp;&nbsp;&nbsp;
        <span style="color:#61a37e;">●</span> Collections
      </div>
      <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:1px solid #ebefeb;background:#fcfdfb;">
        <tr><td style="padding:8px;">
          <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
            @foreach([['orders', 'Sales Orders', '#de7950'], ['billing', 'Direct Billing', '#5285a2'], ['collections', 'Collections', '#61a37e']] as [$metric, $label, $color])
              <tr>
                <td width="82" style="padding:3px 5px 3px 0;color:#70817b;font-size:9px;white-space:nowrap;">{{ $label }}</td>
                @foreach($trend as $day)
                  @php $barHeight = max(2, (int) round(54 * (float) $day[$metric] / $maxTrend)); @endphp
                  <td height="62" valign="bottom" align="center" style="padding:0 1px;border-bottom:1px solid #e8eeea;">
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="height:{{ $barHeight }}px;"><tr><td height="{{ $barHeight }}" bgcolor="{{ $color }}" style="height:{{ $barHeight }}px;background:{{ $color }};font-size:1px;line-height:1px;">&nbsp;</td></tr></table>
                  </td>
                @endforeach
              </tr>
            @endforeach
            <tr><td></td>
              @foreach($trend as $index => $day)
                <td align="center" style="padding-top:4px;color:#8c9b93;font-size:7px;white-space:nowrap;">{{ $index % 6 === 0 || $loop->last ? \Carbon\Carbon::parse($day['date'])->format('M d') : '' }}</td>
              @endforeach
            </tr>
          </table>
        </td></tr>
      </table>
      <p style="margin:5px 0 0;color:#929e97;font-size:9px;">Sales Orders, Direct Billing, and Collections use the same scale and are shown separately.</p>
    </td></tr>
    <tr><td style="padding:22px 28px 0;">
      <h2 style="margin:0 0 9px;color:#203b3d;font-size:15px;">Branch snapshot</h2>
      <table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse;font-size:10px;">
        <thead><tr style="background:#edf3ee;color:#5c7163;text-transform:uppercase;font-size:9px;">
          <th align="left" style="padding:9px 7px;">Branch</th><th align="right" style="padding:9px 7px;">Sales Orders</th><th align="right" style="padding:9px 7px;">Direct Billing</th><th align="right" style="padding:9px 7px;">Collections</th>
        </tr></thead>
        <tbody>
          @forelse($report['branches'] as $branch)
          <tr>
            <td style="padding:8px 7px;border-bottom:1px solid #edf0ed;color:#284347;font-weight:bold;">{{ $branch['branch_code'] }}<div style="color:#87948e;font-size:9px;font-weight:normal;">{{ $branch['branch_name'] }}</div></td>
            <td align="right" style="padding:8px 7px;border-bottom:1px solid #edf0ed;color:#455957;">{{ $money($branch['orders']) }}</td>
            <td align="right" style="padding:8px 7px;border-bottom:1px solid #edf0ed;color:#455957;">{{ $money($branch['billing']) }}</td>
            <td align="right" style="padding:8px 7px;border-bottom:1px solid #edf0ed;color:#455957;">{{ $money($branch['collections']) }}</td>
          </tr>
          @empty
          <tr><td colspan="4" style="padding:12px 7px;color:#87948e;">No branch activity in the selected period.</td></tr>
          @endforelse
        </tbody>
        @if($report['branches']->isNotEmpty())
        <tfoot><tr style="background:#f7f9f6;font-weight:bold;color:#203b3d;"><td style="padding:9px 7px;">ALL BRANCHES</td><td align="right" style="padding:9px 7px;">{{ $money($report['order_sales']) }}</td><td align="right" style="padding:9px 7px;">{{ $money($report['direct_billing_amount']) }}</td><td align="right" style="padding:9px 7px;">{{ $money($report['collections']) }}</td></tr></tfoot>
        @endif
      </table>
    </td></tr>
    <tr><td style="padding:18px 28px 23px;">
      <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#fff8ef;border:1px solid #f1e5d4;border-radius:6px;"><tr>
        <td style="padding:12px 14px;"><div style="color:#8f775f;font-size:10px;font-weight:bold;letter-spacing:1px;text-transform:uppercase;">Current unbilled balance</div><div style="padding-top:3px;color:#a96537;font-size:17px;font-weight:bold;">{{ $money($report['unbilled_amount']) }}</div><div style="color:#9b8c7b;font-size:10px;">{{ number_format($report['unbilled_count']) }} unbilled billing records</div></td>
        <td align="right" style="padding:12px 14px;color:#8d7f6b;font-size:10px;">Current balance<br>as of {{ $endLabel }}</td>
      </tr></table>
    </td></tr>
    <tr><td style="padding:14px 28px;background:#f7f9f6;border-top:1px solid #e8ede8;color:#8b9890;font-size:10px;"><strong style="color:#60766a;">Teves Billing System</strong> · Automated daily summary<br>Generated {{ $report['generated_at']->format('M d, Y h:i A') }}. Please do not reply to this email.</td></tr>
  </table>
  <p style="margin:14px 0 0;color:#9ba7aa;font-size:10px;">This report is sent to active SUAdmin accounts only.</p>
</td></tr></table>
</body>
</html>
