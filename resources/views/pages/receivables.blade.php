@extends('layouts.layout')
@section('body_class', 'br-receivables-shell')

@push('page_styles')
<link href="{{ asset('style/billing-receivables.css') }}?v={{ filemtime(public_path('style/billing-receivables.css')) }}" rel="stylesheet">
@endpush

@section('content')
<main id="main" class="main br-receivables-page" data-detail-url="{{ route('receivable_from_billing_form') }}">
    <header class="br-page-heading">
        <div>
            <p class="br-eyebrow">BILLING / COLLECTIONS</p>
            <h1>Billing <span>Receivables</span></h1>
            <p>Track invoices, payments, and outstanding balances in one place.</p>
        </div>
        @if(in_array($data->user_type, ['Admin', 'SUAdmin', 'Accounting_Staff'], true))
            <a class="btn br-btn-primary" href="{{ route('create_recievable') }}">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> New receivable
            </a>
        @endif
    </header>

    @include('pages.receivables.partials.summary')

    <section class="br-list-panel" aria-labelledby="br-list-title">
        <div class="br-list-heading">
            <div class="br-list-title">
                <span class="br-list-icon"><i class="bi bi-list-ul" aria-hidden="true"></i></span>
                <div>
                    <h2 id="br-list-title">Receivable history</h2>
                    <p>Billing records only</p>
                </div>
            </div>
        </div>

        <div class="br-list-body">
            <div class="br-filters" role="search" aria-label="Filter billing receivables">
                <div class="br-filter-field">
                    <label for="br-filter-branch">Branch</label>
                    <select id="br-filter-branch" class="form-select">
                        <option value="">All branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->branch_id }}">{{ $branch->branch_code }} — {{ $branch->branch_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="br-filter-field">
                    <label for="br-filter-status">Status</label>
                    <select id="br-filter-status" class="form-select">
                        <option value="">All statuses</option>
                        <option value="pending">Pending</option>
                        <option value="partial">Partial</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>
                <div class="br-filter-field">
                    <label for="br-filter-period">Period</label>
                    <select id="br-filter-period" class="form-select">
                        <option value="">All dates</option>
                        <option value="today">Today</option>
                        <option value="last_7_days">Last 7 days</option>
                        <option value="this_month">This month</option>
                    </select>
                </div>
                <div class="br-filter-field br-filter-search">
                    <label for="br-filter-search">Search</label>
                    <input id="br-filter-search" class="form-control" type="search" placeholder="Control number or account">
                </div>
                <button type="button" class="btn br-btn-secondary br-reset" id="br-reset-filters">
                    <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i> Reset
                </button>
            </div>

            <div class="br-table-scroll">
                <table class="table br-table" id="getReceivablesListBilling" data-list-url="{{ route('getReceivablesList_billing') }}">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Control number</th>
                            <th>Account name</th>
                            <th class="br-numeric">Amount due</th>
                            <th class="br-numeric">Balance</th>
                            <th>Status</th>
                            <th>Generate</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="br-list-feedback" id="br-list-feedback" role="status" hidden></div>
        </div>
    </section>

    @include('pages.receivables.modals.view')
    @include('pages.receivables.modals.delete')
    @include('pages.receivables.modals.lock')
</main>
@endsection

@push('page_scripts')
<script src="{{ asset('Datatables/2.0.8/js/dataTables.js') }}"></script>
<script src="{{ asset('Datatables/responsive/3.0.2/js/dataTables.responsive.js') }}"></script>
<script src="{{ asset('Datatables/responsive/3.0.2/js/responsive.dataTables.js') }}"></script>
@include('pages.receivables.scripts.actions')
<script src="{{ asset('js/billing-receivables.js') }}?v={{ filemtime(public_path('js/billing-receivables.js')) }}"></script>
@endpush
