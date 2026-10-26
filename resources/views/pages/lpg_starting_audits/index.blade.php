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
    </section>
</main>
@endsection
