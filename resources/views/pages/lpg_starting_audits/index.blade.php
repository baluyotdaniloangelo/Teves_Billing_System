@extends('layouts.layout')
@section('content')
<main id="main" class="main">
    <div class="pagetitle"><h1>{{ $title }}</h1><p>Review submitted LPG physical counts. Pending audits do not carry forward until approved.</p></div>
    <section class="section">
        <div class="card"><div class="card-body pt-4">
            @if($audits->isEmpty())
                <div class="alert alert-success mb-0"><i class="bi bi-check-circle me-2"></i>No pending starting audits.</div>
            @else
                <div class="table-responsive"><table class="table table-hover align-middle">
                    <thead><tr><th>Branch</th><th>Report Date</th><th>Shift</th><th>Audit Date</th><th>Submitted By</th><th>Submitted At</th><th></th></tr></thead>
                    <tbody>
                    @foreach($audits as $audit)
                        <tr>
                            <td>{{ $audit->branch_name ?? 'Branch '.$audit->teves_branch }}</td>
                            <td>{{ $audit->report_date }}</td><td>{{ $audit->shift }}</td><td>{{ $audit->audit_date }}</td>
                            <td>{{ $audit->encoder_name ?? '—' }}</td><td>{{ $audit->submitted_at ? \Carbon\Carbon::parse($audit->submitted_at)->format('M d, Y h:i A') : '—' }}</td>
                            <td class="text-end"><a class="btn btn-sm btn-primary" href="{{ route('lpg.starting-audits.show', $audit->audit_id) }}"><i class="bi bi-eye me-1"></i>Review</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table></div>
            @endif
        </div></div>
        <div class="card mt-4"><div class="card-body pt-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                <div><h5 class="mb-1">Approved Audits</h5><p class="text-muted mb-0">The latest 100 approved audits. Open one to inspect it or revoke its approval for correction.</p></div>
            </div>
            @if($approvedAudits->isEmpty())
                <div class="alert alert-light mb-0">No approved starting audits found.</div>
            @else
                <div class="table-responsive"><table class="table table-hover align-middle">
                    <thead><tr><th>Branch</th><th>Report Date</th><th>Shift</th><th>Audit Date</th><th>Encoder</th><th>Approved At</th><th></th></tr></thead>
                    <tbody>
                    @foreach($approvedAudits as $audit)
                        <tr>
                            <td>{{ $audit->branch_name ?? 'Branch '.$audit->teves_branch }}</td>
                            <td>{{ $audit->report_date }}</td><td>{{ $audit->shift }}</td><td>{{ $audit->audit_date }}</td>
                            <td>{{ $audit->encoder_name ?? '—' }}</td><td>{{ $audit->approved_at ? \Carbon\Carbon::parse($audit->approved_at)->format('M d, Y h:i A') : '—' }}</td>
                            <td class="text-end"><a class="btn btn-sm {{ (int) $audit->approved_by !== (int) $data->user_id ? 'btn-outline-danger' : 'btn-outline-primary' }}" href="{{ route('lpg.starting-audits.show', $audit->audit_id) }}"><i class="bi bi-{{ (int) $audit->approved_by !== (int) $data->user_id ? 'arrow-counterclockwise' : 'eye' }} me-1"></i>{{ (int) $audit->approved_by !== (int) $data->user_id ? 'View / Revoke' : 'View' }}</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table></div>
            @endif
        </div></div>
        <div class="card mt-4"><div class="card-body pt-4">
            <div class="mb-3"><h5 class="mb-1">Inventory Revoke Requests</h5><p class="text-muted mb-0">Requests are submitted from an LPG report. Approval voids the transaction while preserving its activity history.</p></div>
            @if($inventoryRevokeRequests->isEmpty())
                <div class="alert alert-light mb-0">No pending inventory revoke requests.</div>
            @else
                <div class="table-responsive"><table class="table table-hover align-middle">
                    <thead><tr><th>Transaction No.</th><th>Branch / Report</th><th>Movement</th><th>Item</th><th>Qty</th><th>Requested By</th><th>Reason</th><th></th></tr></thead>
                    <tbody>
                    @foreach($inventoryRevokeRequests as $request)
                        <tr>
                            <td class="fw-semibold text-nowrap">{{ $request->transaction_no }}</td>
                            <td>{{ $request->branch_name ?? 'Branch '.$request->teves_branch }}<br><small class="text-muted">{{ $request->report_date }} · Shift {{ $request->shift }}</small></td>
                            <td>{{ str_replace('_', ' ', $request->inventory_mode) }}</td>
                            <td>{{ trim(($request->product_name ?? '—').' '.($request->product_unit_measurement ?? '')) }}</td>
                            <td>{{ number_format((float) $request->quantity, 2) }}</td>
                            <td>{{ $request->requester_name ?? '—' }}</td>
                            <td class="text-wrap" style="min-width:180px">{{ $request->request_reason }}</td>
                            <td class="text-end text-nowrap">
                                @if((int) $request->requested_by !== (int) $data->user_id)
                                    <button type="button" class="btn btn-sm btn-success js-approve-inventory-revoke" data-url="{{ route('lpg.inventory.revoke-requests.approve', $request->request_id) }}">Approve</button>
                                    <button type="button" class="btn btn-sm btn-outline-danger js-reject-inventory-revoke" data-url="{{ route('lpg.inventory.revoke-requests.reject', $request->request_id) }}">Decline</button>
                                @else
                                    <span class="text-muted">Your request</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table></div>
            @endif
        </div></div>
    </section>
</main>
<script>
$(function () {
    $(document).on('click', '.js-approve-inventory-revoke', function () {
        if (!window.confirm('Approve this request and void the inventory transaction?')) return;
        $.post($(this).data('url')).done(function (response) { window.alert(response.message); window.location.reload(); }).fail(function (xhr) { window.alert(xhr.responseJSON?.message || 'Unable to approve the request.'); });
    });
    $(document).on('click', '.js-reject-inventory-revoke', function () {
        const reason = window.prompt('Enter a reason for declining this request:');
        if (!reason || !reason.trim()) return;
        $.post($(this).data('url'), { reason: reason.trim() }).done(function (response) { window.alert(response.message); window.location.reload(); }).fail(function (xhr) { window.alert(xhr.responseJSON?.message || 'Unable to decline the request.'); });
    });
});
</script>
@endsection
