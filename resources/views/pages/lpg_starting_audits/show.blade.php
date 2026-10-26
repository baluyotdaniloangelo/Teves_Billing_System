@extends('layouts.layout')
@section('content')
<main id="main" class="main">
    <div class="pagetitle d-flex justify-content-between align-items-start gap-3">
        <div><h1>{{ $title }}</h1><p>{{ $audit->branch_name ?? 'Branch '.$audit->teves_branch }} · Report {{ $audit->report_date }} · Shift {{ $audit->shift }} · Audit date {{ $audit->audit_date }}</p></div>
        <a href="{{ route('lpg.starting-audits.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Approval Queue</a>
    </div>
    <section class="section">
        <div class="card"><div class="card-header d-flex justify-content-between align-items-center"><strong>Physical Count Details</strong><span class="badge text-bg-warning">{{ $audit->status }}</span></div>
            <div class="card-body pt-3">
                <div class="table-responsive"><table class="table table-bordered align-middle">
                    <thead><tr><th>Product</th><th class="text-end">Filled Cylinders</th><th class="text-end">B.O. Returned from Customers</th><th class="text-end">B.O. During Delivery</th><th>Remarks</th></tr></thead>
                    <tbody>
                    @forelse($items as $item)
                        <tr><td>{{ $item->product_name ?? 'Unavailable product' }} {{ $item->product_capacity ? $item->product_capacity.$item->product_capacity_unit : $item->product_unit_measurement }}</td>
                            <td class="text-end">{{ number_format($item->filled_quantity, 2) }}</td><td class="text-end">{{ number_format($item->bo_returned_customer_quantity, 2) }}</td><td class="text-end">{{ number_format($item->bo_during_delivery_quantity, 2) }}</td><td>{{ $item->remarks ?: '—' }}</td></tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">No count items are attached to this audit.</td></tr>
                    @endforelse
                    </tbody>
                </table></div>
                @if($audit->status === 'PENDING' && (int) $audit->submitted_by !== (int) $data->user_id)
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button type="button" class="btn btn-outline-danger" id="return-lpg-audit"><i class="bi bi-arrow-return-left me-1"></i>Return for Correction</button>
                        <button type="button" class="btn btn-success" id="approve-lpg-audit"><i class="bi bi-check-circle me-1"></i>Approve Audit</button>
                    </div>
                @elseif($audit->status === 'PENDING')
                    <div class="alert alert-info mb-0">You submitted this audit. Another approver must review it.</div>
                @elseif($audit->status === 'RETURNED')
                    <div class="alert alert-warning mb-0">Returned: {{ $audit->return_reason }}</div>
                @endif
                <div class="alert d-none mt-3" id="lpg-audit-action-result" role="alert"></div>
            </div>
        </div>
    </section>
</main>
<script>
$(function () {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" } });
    const auditId = {{ (int) $audit->audit_id }};
    $('#approve-lpg-audit').on('click', function () {
        if (!window.confirm('Approve this physical count? Approved counts will carry forward to subsequent LPG reports.')) return;
        $.post("{{ route('lpg.starting-audits.approve', $audit->audit_id) }}").done(showResult).fail(showError);
    });
    $('#return-lpg-audit').on('click', function () {
        const reason = window.prompt('Reason for returning this audit for correction:');
        if (reason === null) return;
        if (!reason.trim()) { window.alert('Please enter a reason.'); return; }
        $.post("{{ route('lpg.starting-audits.return', $audit->audit_id) }}", { reason: reason }).done(showResult).fail(showError);
    });
    function showResult(response) { $('#lpg-audit-action-result').removeClass('d-none alert-danger').addClass('alert-success').text(response.message); window.setTimeout(() => window.location.href = "{{ route('lpg.starting-audits.index') }}", 700); }
    function showError(xhr) { $('#lpg-audit-action-result').removeClass('d-none alert-success').addClass('alert-danger').text(xhr.responseJSON?.message || 'Unable to save the audit decision.'); }
});
</script>
@endsection
