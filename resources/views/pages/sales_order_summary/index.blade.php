@extends('layouts.layout')
@section('content')
@include('pages.sales_order_summary.styles')
<main id="main" class="main">
    <section class="section sales-summary-theme">
        <div class="card summary-card">
            <div class="summary-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div><h4 class="mb-1"><i class="bi bi-bar-chart-line me-2"></i>{{ $title }}</h4><p class="mb-0">Review sales orders across your available branches.</p></div>
                <div class="d-flex gap-2">
                    <button class="btn btn-light" type="button" data-bs-toggle="modal" data-bs-target="#CreateReportModal"><i class="bi bi-funnel me-1"></i>Report Filters</button>
                    <button class="btn btn-light" type="button" id="summary_download" disabled><i class="bi bi-file-earmark-pdf me-1"></i>Export PDF</button>
                </div>
            </div>
            <div class="card-body p-3 p-lg-4">
                <div id="summary_error" class="alert alert-danger {{ session('sales_summary_error') ? '' : 'd-none' }}" role="alert">{{ session('sales_summary_error') }}</div>
                <div class="summary-scope mb-3" aria-live="polite"><strong id="summary_scope">Choose filters to generate a report.</strong><div class="small mt-1" id="summary_period">All Branches includes only branches available to your account.</div></div>
                <div class="row g-3 mb-4">
                    @foreach (['gross_amount' => 'Gross Amount', 'withholding_tax' => 'Withholding Tax', 'net_amount' => 'Net Amount', 'total_due' => 'Total Due'] as $key => $label)
                    <div class="col-6 col-xl-3"><div class="summary-stat"><div class="small">{{ $label }}</div><strong id="stat_{{ $key }}">₱0.00</strong></div></div>
                    @endforeach
                </div>
                <ul class="nav nav-pills summary-tabs mb-3" id="salesSummaryTabs" role="tablist">
                    <li class="nav-item" role="presentation"><button class="nav-link active" id="branch-summary-tab" data-bs-toggle="pill" data-bs-target="#branch-summary-pane" type="button" role="tab" aria-controls="branch-summary-pane" aria-selected="true">Branch Summary</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" id="order-details-tab" data-bs-toggle="pill" data-bs-target="#order-details-pane" type="button" role="tab" aria-controls="order-details-pane" aria-selected="false">Sales Order Details</button></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="branch-summary-pane" role="tabpanel" aria-labelledby="branch-summary-tab">@include('pages.sales_order_summary.tabs.branch_summary')</div>
                    <div class="tab-pane fade" id="order-details-pane" role="tabpanel" aria-labelledby="order-details-tab">@include('pages.sales_order_summary.tabs.order_details')</div>
                </div>
                <div class="small text-muted mt-3" id="summary_count">No report generated yet.</div>
                <div class="small text-muted mt-1 d-none" id="summary_export_note">PDF options: combine branches in one report, or start each branch on a new page.</div>
            </div>
        </div>
        @include('pages.sales_order_summary.modals.report_filters')
        @include('pages.sales_order_summary.modals.export_options')
    </section>
</main>
@endsection
@push('page_scripts')
    @include('pages.sales_order_summary.scripts.summary_script')
@endpush
