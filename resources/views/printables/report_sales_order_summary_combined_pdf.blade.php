<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>{{ $title }}</title>
<style>
@page { margin:28px; }
body { font-family:DejaVu Sans,sans-serif; font-size:9px; color:#263B38; }
h1 { color:#441827; font-size:19px; margin:0 0 8px; }
.header { border-bottom:3px solid #5C8374; padding-bottom:12px; margin-bottom:16px; }
.logo { float:right; width:112px; height:auto; }
.scope { font-size:12px; font-weight:bold; color:#1B4242; margin-bottom:5px; }
table { width:100%; border-collapse:collapse; margin-bottom:18px; }
th { background:#1B4242; color:#F5F2B8; padding:8px 5px; text-align:left; }
td { border-bottom:1px solid #dbe3df; padding:7px 5px; overflow-wrap:break-word; }
tr { page-break-inside:avoid; }
thead { display:table-header-group; }
.number { text-align:right; white-space:nowrap; }
.total td { background:#FBF9E8; font-weight:bold; border-top:2px solid #5C8374; }
.muted { color:#60736d; }
.details { font-size:8px; }
.details th,.details td { padding:6px 4px; }
.prepared { margin-top:24px; }
</style></head><body>
<div class="header">
    @if($printable_header && $printable_header->branch_logo)
    <img class="logo" src="{{ public_path('client_logo/' . $printable_header->branch_logo) }}" alt="Branch logo">
    @endif
    <h1>{{ $title }}</h1>
    <div class="scope">{{ $report['branch_label'] }} — {{ $report['client'] ? $report['client']->client_name : 'All Clients' }}</div>
    <div>Period: {{ $report['filters']['start_date'] }} to {{ $report['filters']['end_date'] }}</div>
    @if($report['branch_header'])<div>{{ $report['branch_header']->branch_address }} · TIN: {{ $report['branch_header']->branch_tin }}</div>@endif
    @if($report['client'])<div>Client Address: {{ $report['client']->client_address }} · TIN: {{ $report['client']->client_tin }}</div>@endif
    <div class="muted">{{ $report['available_branch_count'] }} available branch(es) · {{ $report['totals']['order_count'] }} sales order(s)</div>
</div>
<h3>Branch Summary</h3>
<table>
    <thead><tr><th>Branch Code</th><th>Branch Name</th><th class="number">Orders</th><th class="number">Gross Amount</th><th class="number">Withholding Tax</th><th class="number">Net Amount</th><th class="number">Total Due</th></tr></thead>
    <tbody>
        @forelse($report['branch_summary'] as $branch)
        <tr><td>{{ $branch['branch_code'] }}</td><td>{{ $branch['branch_name'] }}</td><td class="number">{{ $branch['order_count'] }}</td>
            @foreach(['gross_amount','withholding_tax','net_amount','total_due'] as $key)<td class="number">₱{{ number_format($branch[$key], 2) }}</td>@endforeach
        </tr>
        @empty<tr><td colspan="7">No branches are available to your account.</td></tr>@endforelse
        <tr class="total"><td colspan="2">Grand Total</td><td class="number">{{ $report['totals']['order_count'] }}</td>
            @foreach(['gross_amount','withholding_tax','net_amount','total_due'] as $key)<td class="number">₱{{ number_format($report['totals'][$key], 2) }}</td>@endforeach
        </tr>
    </tbody>
</table>
@if($report_mode === 'details')
<h3>Sales Order Details</h3>
<table class="details">
    <thead><tr><th>#</th><th>Branch</th><th>Date</th><th>S.O. No.</th><th>Account Name</th><th>Payment Term</th><th class="number">Gross Amount</th><th class="number">Withholding Tax</th><th class="number">Net Amount</th><th class="number">Total Due</th><th>Delivery Status</th><th>Payment Status</th></tr></thead>
    <tbody>
        @forelse($report['data'] as $order)
        <tr><td>{{ $loop->iteration }}</td><td>{{ $order->branch_code }}</td><td>{{ $order->sales_order_date }}</td><td>{{ $order->sales_order_control_number }}</td><td>{{ $order->client_name }}</td><td>{{ $order->sales_order_payment_term }}</td>
            @foreach(['sales_order_gross_amount','report_withholding_amount','sales_order_net_amount','sales_order_total_due'] as $key)<td class="number">₱{{ number_format($order->$key, 2) }}</td>@endforeach
            <td>{{ $order->sales_order_delivery_status }}</td><td>{{ $order->sales_order_payment_status }}</td>
        </tr>
        @empty<tr><td colspan="12">No sales orders match these filters.</td></tr>@endforelse
        <tr class="total"><td colspan="6">Grand Total</td>@foreach(['gross_amount','withholding_tax','net_amount','total_due'] as $key)<td class="number">₱{{ number_format($report['totals'][$key], 2) }}</td>@endforeach<td colspan="2"></td></tr>
    </tbody>
</table>
@endif
<div class="prepared">Prepared by: <strong>{{ $user_data->user_real_name }}</strong><br>{{ $user_data->user_job_title }}<br><span class="muted">Generated: {{ date('Y-m-d H:i') }}</span></div>
</body></html>
