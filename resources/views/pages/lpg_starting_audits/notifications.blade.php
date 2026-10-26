@extends('layouts.layout')
@section('content')
<main id="main" class="main">
    <div class="pagetitle"><h1>{{ $title }}</h1><p>Updates about submitted, approved, and returned LPG starting audits.</p></div>
    <section class="section">
        <div class="card"><div class="card-body pt-4">
            @forelse($notifications as $notification)
                <div class="border rounded-3 p-3 mb-2 {{ $notification->read_at ? 'bg-light' : 'border-primary bg-primary-subtle' }}">
                    <div class="d-flex justify-content-between gap-3 align-items-start">
                        <div><div class="fw-semibold">{{ $notification->title }}</div><div class="text-muted small mt-1">{{ $notification->message }}</div>
                            <div class="small text-secondary mt-2">{{ $notification->branch_name ?? 'LPG branch' }} · Report {{ $notification->report_date }} · Shift {{ $notification->shift }} · {{ \Carbon\Carbon::parse($notification->created_at)->format('M d, Y h:i A') }}</div>
                            @if(in_array($notification->notification_type, ['SUBMITTED'], true) && in_array($data->user_type, ['Admin', 'SUAdmin', 'Accounting_Staff'], true))
                                <a class="btn btn-sm btn-outline-primary mt-2" href="{{ route('lpg.starting-audits.show', $notification->audit_id) }}">Review audit</a>
                            @elseif(in_array($notification->notification_type, ['APPROVED', 'RETURNED'], true))
                                <a class="btn btn-sm btn-outline-primary mt-2" href="{{ route('cashiers_report_form_lpg', $notification->report_id) }}">Open LPG report</a>
                            @endif
                        </div>
                        @if(!$notification->read_at)<button class="btn btn-sm btn-outline-secondary mark-lpg-audit-read" data-id="{{ $notification->notification_id }}">Mark read</button>@else<span class="badge text-bg-light">Read</span>@endif
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5"><i class="bi bi-bell-slash fs-2 d-block mb-2"></i>No LPG audit notifications yet.</div>
            @endforelse
        </div></div>
    </section>
</main>
<script>
$(function () {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" } });
    $('.mark-lpg-audit-read').on('click', function () {
        const button = $(this); $.post("/lpg-audit-notifications/" + button.data('id') + '/read').done(() => window.location.reload());
    });
});
</script>
@endsection
