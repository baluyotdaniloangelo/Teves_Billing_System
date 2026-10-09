@extends('layouts.layout')

@section('content')

<style>
    .lpg-report-theme {
        --lpg-brand: #198754;
        --lpg-brand-dark: #146C43;
        --lpg-soft-green: #EAF5EF;
        --lpg-subtab: #0F766E;
        --lpg-subtab-soft: #E8F5F3;
        --lpg-subtab-border: #CFE5E0;
        --lpg-page: #F5F7F5;
        --lpg-border: #DCE5DE;
        --lpg-text: #26332B;
        --lpg-muted: #6B756E;
        --bs-border-color: var(--lpg-border);
        color: var(--lpg-text);
        background-color: var(--lpg-page);
    }

    .lpg-report-theme .card {
        --bs-card-bg: #FFFFFF;
        --bs-card-border-color: var(--lpg-border);
    }

    .lpg-report-theme .card-header.bg-light {
        background-color: var(--lpg-soft-green) !important;
    }

    .lpg-report-theme .text-body { color: var(--lpg-text) !important; }
    .lpg-report-theme .text-muted { color: var(--lpg-muted) !important; }

    .lpg-report-theme .text-primary,
    .lpg-report-theme .text-info {
        color: var(--lpg-brand-dark) !important;
    }

    .lpg-report-theme .bg-primary.bg-opacity-10,
    .lpg-report-theme .bg-primary-subtle,
    .lpg-report-theme .bg-info.bg-opacity-10 {
        background-color: var(--lpg-soft-green) !important;
    }

    .lpg-report-theme .btn-success,
    .lpg-report-theme .btn-primary {
        --bs-btn-bg: var(--lpg-brand);
        --bs-btn-border-color: var(--lpg-brand);
        --bs-btn-hover-bg: var(--lpg-brand-dark);
        --bs-btn-hover-border-color: var(--lpg-brand-dark);
        --bs-btn-active-bg: var(--lpg-brand-dark);
        --bs-btn-active-border-color: var(--lpg-brand-dark);
        --bs-btn-focus-shadow-rgb: 25, 135, 84;
    }

    .lpg-report-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .5rem;
        width: 12rem;
        max-width: 100%;
        height: 2.5rem;
        padding: 0 .75rem;
        border-radius: .6rem;
        font-size: .9rem;
        font-weight: 600;
        line-height: 1;
        white-space: nowrap;
    }

    .lpg-report-action-btn > .bi {
        margin: 0 !important;
        line-height: 1;
    }

    .lpg-report-header-actions { flex-wrap: wrap; }

    .lpg-report-theme #LPGTab {
        background-color: var(--lpg-soft-green) !important;
        border: 1px solid var(--lpg-border);
    }

    .lpg-report-theme #LPGTab .nav-link {
        color: var(--lpg-muted);
        background-color: transparent;
        border: 1px solid transparent;
    }

    .lpg-report-theme #LPGTab .nav-link:hover:not(.active) {
        color: var(--lpg-brand-dark);
        background-color: #FFFFFF;
    }

    .lpg-report-theme #LPGTab .nav-link.active {
        color: #FFFFFF;
        background-color: var(--lpg-brand);
        border-color: var(--lpg-brand);
    }

    .lpg-report-theme #lpgCollectionReportSubTabs {
        border-bottom-color: var(--lpg-subtab-border);
    }

    .lpg-report-theme #lpgCollectionReportSubTabs .nav-link {
        color: var(--lpg-muted);
        border-color: transparent;
        border-radius: .65rem .65rem 0 0;
    }

    .lpg-report-theme #lpgCollectionReportSubTabs .nav-link:hover:not(.active) {
        color: var(--lpg-subtab);
        background-color: var(--lpg-page);
    }

    .lpg-report-theme #lpgCollectionReportSubTabs .nav-link.active {
        color: var(--lpg-subtab);
        background-color: var(--lpg-subtab-soft);
        border-color: var(--lpg-subtab-border) var(--lpg-subtab-border) #FFFFFF;
    }

    .lpg-report-theme .table {
        --bs-table-color: var(--lpg-text);
        --bs-table-border-color: var(--lpg-border);
        --bs-table-striped-bg: rgba(25, 135, 84, .035);
        --bs-table-striped-color: var(--lpg-text);
    }

    .lpg-report-theme .table thead th {
        color: var(--lpg-brand-dark);
        background-color: var(--lpg-soft-green) !important;
        border-bottom: 1px solid var(--lpg-subtab-border);
        font-weight: 600;
        vertical-align: middle;
        white-space: nowrap;
    }

    .lpg-report-theme .table thead th .bi {
        color: var(--lpg-brand);
        font-size: .85em;
        margin-right: .35rem;
        vertical-align: -.03em;
    }

    .lpg-report-theme .table-light {
        --bs-table-bg: var(--lpg-soft-green);
        --bs-table-color: var(--lpg-brand-dark);
        --bs-table-border-color: var(--lpg-border);
    }

    .lpg-report-theme .table-hover > tbody > tr:hover > * {
        --bs-table-bg: var(--lpg-page);
        color: var(--lpg-text);
    }

    .lpg-report-theme .lpg-cash-denomination-row:hover {
        background-color: var(--lpg-page);
    }

    .lpg-report-theme .lpg-cash-total-row,
    .lpg-report-theme .lpg-cash-actual-row {
        background-color: var(--lpg-soft-green);
        color: var(--lpg-brand-dark);
    }

    .lpg-report-theme .lpg-cash-actual-row {
        border-top: 2px solid var(--lpg-brand-dark) !important;
    }

    .lpg-report-theme .form-control:focus,
    .lpg-report-theme .form-select:focus {
        border-color: var(--lpg-brand);
        box-shadow: 0 0 0 .25rem rgba(25, 135, 84, .18);
    }
</style>

<main id="main" class="main lpg-report-theme">

    <section class="section">

        <div class="card rounded-4 p-3">

			@include('pages.cashiers_report_form_lpg.tabs.information')
			
            @include('pages.cashiers_report_form_lpg.tabs.navigation')

            <div class="tab-content pt-2">

                @include('pages.cashiers_report_form_lpg.tabs.lpg_accounts_receivable')
				@include('pages.cashiers_report_form_lpg.tabs.cashier_report_lpg_ar_so_tab')
				@include('pages.cashiers_report_form_lpg.tabs.cashier_report_lpg_expenses_tab')
				@include('pages.cashiers_report_form_lpg.tabs.cashier_report_lpg_collection_report_tab')


            </div>

        </div>

	
	@include('pages.cashiers_report_form_lpg.modals.update_cashier_report_modal')

	@include('pages.cashiers_report_form_lpg.modals.lpg_accounts_receivable_modal')

	@include('pages.cashiers_report_form_lpg.modals.cashier_report_lpg_ar_so_form_modal')
	@include('pages.cashiers_report_form_lpg.modals.cashier_report_lpg_ar_so_delete_modal')

	@include('pages.cashiers_report_form_lpg.modals.cashier_report_lpg_expenses_form_modal')
	@include('pages.cashiers_report_form_lpg.modals.cashier_report_lpg_expenses_delete_modal')	
	
	@include('pages.cashiers_report_form_lpg.modals.cashier_report_lpg_non_cash_payment_form_modal')
	@include('pages.cashiers_report_form_lpg.modals.cashier_report_lpg_non_cash_payment_delete_modal')	

	@include('pages.validation.modals.validation_modal')
	@include('pages.user_account_settings.modals.logout_modal')	
    </section>


</main>

	@include('pages.cashiers_report_form_lpg.scripts.plugins_script')
	@include('pages.cashiers_report_form_lpg.scripts.information_script')
	@include('pages.cashiers_report_form_lpg.scripts.cashier_report_lpg_update_modal_script')

	@include('pages.cashiers_report_form_lpg.scripts.lpg_accounts_receivable_script')	
	@include('pages.cashiers_report_form_lpg.scripts.cashier_report_lpg_ar_so_script')
	@include('pages.cashiers_report_form_lpg.scripts.cashier_report_lpg_expenses_script')
	@include('pages.cashiers_report_form_lpg.scripts.cashier_report_lpg_non_cash_payment_script')
	@include('pages.cashiers_report_form_lpg.scripts.cashier_report_lpg_cash_script')

	@include('pages.validation.scripts.validation_script')


@endsection

