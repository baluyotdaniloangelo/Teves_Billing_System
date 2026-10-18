@extends('layouts.layout')

@section('content')

<style>
    .lpg-report-theme {
        --lpg-primary: #AB77D3;
        --lpg-dark: #114447;
        --lpg-surface: #DEDBDB;
        --lpg-accent: #475767;
        --lpg-highlight: #ECECEC;
        --lpg-accent-soft: rgba(71, 87, 103, .10);
        --lpg-accent-border: rgba(71, 87, 103, .30);
        --lpg-border: rgba(17, 68, 71, .14);
        --lpg-text: var(--lpg-dark);
        --lpg-muted: rgba(17, 68, 71, .68);
        --bs-border-color: var(--lpg-border);
        color: var(--lpg-text);
        background-color: var(--lpg-surface);
    }

    .lpg-report-theme .card {
        --bs-card-bg: #FFFFFF;
        --bs-card-border-color: var(--lpg-border);
    }

    .lpg-report-theme .card-header.bg-light {
        background-color: var(--lpg-dark) !important;
    }

    .lpg-report-theme .text-body { color: var(--lpg-text) !important; }
    .lpg-report-theme .text-muted { color: var(--lpg-muted) !important; }

    .lpg-report-theme .text-primary,
    .lpg-report-theme .text-info {
        color: var(--lpg-dark) !important;
    }

    .lpg-report-theme .text-success {
        color: var(--lpg-primary) !important;
    }

    .lpg-report-theme .bg-primary.bg-opacity-10,
    .lpg-report-theme .bg-primary-subtle,
    .lpg-report-theme .bg-info.bg-opacity-10 {
        background-color: var(--lpg-surface) !important;
    }

    .lpg-report-theme .bg-success.bg-opacity-10 {
        background-color: rgba(171, 119, 211, .10) !important;
    }

    .lpg-report-theme .bg-success:not(.bg-opacity-10) {
        color: var(--lpg-surface) !important;
        background-color: var(--lpg-primary) !important;
    }

    .lpg-report-theme .btn-success,
    .lpg-report-theme .btn-primary {
        --bs-btn-color: var(--lpg-surface);
        --bs-btn-bg: var(--lpg-primary);
        --bs-btn-border-color: var(--lpg-primary);
        --bs-btn-hover-color: var(--lpg-surface);
        --bs-btn-hover-bg: var(--lpg-accent);
        --bs-btn-hover-border-color: var(--lpg-accent);
        --bs-btn-active-color: var(--lpg-surface);
        --bs-btn-active-bg: var(--lpg-dark);
        --bs-btn-active-border-color: var(--lpg-dark);
        --bs-btn-focus-shadow-rgb: 171, 119, 211;
    }

    .lpg-report-theme .btn-outline-success {
        --bs-btn-color: var(--lpg-dark);
        --bs-btn-border-color: var(--lpg-primary);
        --bs-btn-hover-color: var(--lpg-surface);
        --bs-btn-hover-bg: var(--lpg-primary);
        --bs-btn-hover-border-color: var(--lpg-primary);
        --bs-btn-active-color: var(--lpg-surface);
        --bs-btn-active-bg: var(--lpg-accent);
        --bs-btn-active-border-color: var(--lpg-accent);
        --bs-btn-focus-shadow-rgb: 171, 119, 211;
    }

    .lpg-report-theme .btn-danger {
        --bs-btn-color: var(--lpg-surface);
        --bs-btn-bg: var(--lpg-accent);
        --bs-btn-border-color: var(--lpg-accent);
        --bs-btn-hover-color: var(--lpg-surface);
        --bs-btn-hover-bg: var(--lpg-primary);
        --bs-btn-hover-border-color: var(--lpg-primary);
        --bs-btn-active-color: var(--lpg-surface);
        --bs-btn-active-bg: var(--lpg-dark);
        --bs-btn-active-border-color: var(--lpg-dark);
        --bs-btn-focus-shadow-rgb: 71, 87, 103;
    }

    .lpg-report-theme .btn-outline-danger {
        --bs-btn-color: var(--lpg-accent);
        --bs-btn-border-color: var(--lpg-accent);
        --bs-btn-hover-color: var(--lpg-surface);
        --bs-btn-hover-bg: var(--lpg-accent);
        --bs-btn-hover-border-color: var(--lpg-accent);
        --bs-btn-active-color: var(--lpg-surface);
        --bs-btn-active-bg: var(--lpg-primary);
        --bs-btn-active-border-color: var(--lpg-primary);
        --bs-btn-focus-shadow-rgb: 71, 87, 103;
    }

    .lpg-report-theme .btn-light {
        --bs-btn-color: var(--lpg-dark);
        --bs-btn-bg: var(--lpg-surface);
        --bs-btn-border-color: var(--lpg-border);
        --bs-btn-hover-color: var(--lpg-dark);
        --bs-btn-hover-bg: var(--lpg-highlight);
        --bs-btn-hover-border-color: var(--lpg-accent-border);
        --bs-btn-active-color: var(--lpg-dark);
        --bs-btn-active-bg: var(--lpg-accent-soft);
        --bs-btn-active-border-color: var(--lpg-accent);
    }

    .lpg-report-theme .btn-outline-secondary {
        --bs-btn-color: var(--lpg-dark);
        --bs-btn-border-color: var(--lpg-border);
        --bs-btn-hover-color: var(--lpg-dark);
        --bs-btn-hover-bg: var(--lpg-highlight);
        --bs-btn-hover-border-color: var(--lpg-accent-border);
        --bs-btn-active-color: var(--lpg-dark);
        --bs-btn-active-bg: var(--lpg-accent-soft);
        --bs-btn-active-border-color: var(--lpg-accent);
    }

    .lpg-report-theme .btn-secondary {
        --bs-btn-color: var(--lpg-dark);
        --bs-btn-bg: var(--lpg-highlight);
        --bs-btn-border-color: var(--lpg-highlight);
        --bs-btn-hover-color: var(--lpg-dark);
        --bs-btn-hover-bg: var(--lpg-accent);
        --bs-btn-hover-border-color: var(--lpg-accent);
        --bs-btn-active-color: var(--lpg-surface);
        --bs-btn-active-bg: var(--lpg-dark);
        --bs-btn-active-border-color: var(--lpg-dark);
    }

    .lpg-report-theme .modal-content {
        color: var(--lpg-dark);
        background-color: #FFFFF;
        border-color: var(--lpg-accent-border);
    }

    .lpg-report-theme .modal-header {
        color: var(--lpg-surface);
        background: linear-gradient(135deg, var(--lpg-primary), var(--lpg-accent)) !important;
    }

    .lpg-report-theme .modal-header .text-white {
        color: var(--lpg-surface) !important;
    }

    .lpg-report-theme .modal-header .text-white-50 {
        color: rgba(222, 219, 219, .78) !important;
    }

    .lpg-report-theme .modal-footer.bg-light,
    .lpg-report-theme .modal .bg-light {
        background-color: var(--lpg-surface) !important;
    }

    .lpg-report-theme .modal-header .btn-danger {
        --bs-btn-color: var(--lpg-surface);
        --bs-btn-bg: rgba(17, 68, 71, .20);
        --bs-btn-border-color: rgba(222, 219, 219, .35);
        --bs-btn-hover-color: var(--lpg-surface);
        --bs-btn-hover-bg: rgba(17, 68, 71, .35);
        --bs-btn-hover-border-color: rgba(222, 219, 219, .55);
    }

    .lpg-report-theme .form-control,
    .lpg-report-theme .form-select,
    .lpg-report-theme .input-group-text {
        color: var(--lpg-dark);
        background-color: #dedbdb2b;
        border-color: var(--lpg-border);
    }

    .lpg-report-theme .form-control::placeholder {
        color: var(--lpg-muted);
        opacity: 1;
    }

    .lpg-report-theme .form-control:disabled,
    .lpg-report-theme .form-select:disabled {
        color: var(--lpg-muted);
        background-color: var(--lpg-highlight);
    }

    .lpg-report-theme .form-check-input:checked {
        background-color: var(--lpg-primary);
        border-color: var(--lpg-primary);
    }

    .lpg-report-theme .dt-container {
        color: var(--lpg-dark);
    }

    .lpg-report-theme .dt-container .dt-input {
        color: var(--lpg-dark);
        background-color: var(--lpg-surface);
        border-color: var(--lpg-border);
    }

    .lpg-report-theme .dt-container .dt-paging-button {
        color: var(--lpg-dark) !important;
        background: var(--lpg-surface) !important;
        background-image: none !important;
        border-color: var(--lpg-border) !important;
    }

    .lpg-report-theme .dt-container .dt-paging-button:not(.current):not(.disabled):hover {
        color: var(--lpg-dark) !important;
        background: var(--lpg-highlight) !important;
        background-image: none !important;
        border-color: var(--lpg-accent-border) !important;
    }

    .lpg-report-theme .dt-container .dt-paging-button.current {
        color: var(--lpg-surface) !important;
        background: var(--lpg-primary) !important;
        background-image: none !important;
        border-color: var(--lpg-primary) !important;
    }

    .lpg-report-theme .table .btn-primary {
        --bs-btn-color: var(--lpg-primary);
        --bs-btn-bg: var(--lpg-surface);
        --bs-btn-border-color: var(--lpg-border);
        --bs-btn-hover-color: var(--lpg-surface);
        --bs-btn-hover-bg: var(--lpg-primary);
        --bs-btn-hover-border-color: var(--lpg-primary);
    }

    .lpg-report-theme .table .btn-danger {
        --bs-btn-color: var(--lpg-accent);
        --bs-btn-bg: var(--lpg-surface);
        --bs-btn-border-color: var(--lpg-border);
        --bs-btn-hover-color: var(--lpg-surface);
        --bs-btn-hover-bg: var(--lpg-accent);
        --bs-btn-hover-border-color: var(--lpg-accent);
    }

    .lpg-report-theme .table .btn-primary,
    .lpg-report-theme .table .btn-danger {
        border-radius: .5rem;
        box-shadow: 0 .125rem .25rem rgba(17, 68, 71, .12);
        transition: color .15s ease-in-out, background-color .15s ease-in-out,
            border-color .15s ease-in-out, box-shadow .15s ease-in-out;
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

    .lpg-report-info-card {
        position: relative;
        z-index: 2;
        overflow: visible;
    }

    .lpg-report-info-card .dropdown-menu {
        z-index: 1080;
    }

    .lpg-report-theme #LPGTab {
        background-color: var(--lpg-dark) !important;
        border: 1px solid var(--lpg-border);
    }

    .lpg-report-theme #LPGTab .nav-link {
        color: var(--lpg-muted);
        background-color: var(--lpg-highlight);
        border: 1px solid transparent;
    }

    .lpg-report-theme #LPGTab .nav-link:hover:not(.active) {
        color: var(--lpg-accent);
        background-color: var(--lpg-highlight);
    }

    .lpg-report-theme #LPGTab .nav-link.active {
        color: var(--lpg-surface);
        background-color: var(--lpg-primary);
        border-color: var(--lpg-primary);
    }

    .lpg-report-theme #lpgCollectionReportSubTabs,
    .lpg-report-theme #lpgInventorySubTabs {
        border-bottom-color: var(--lpg-accent-border);
    }

    .lpg-report-theme #lpgCollectionReportSubTabs .nav-link,
    .lpg-report-theme #lpgInventorySubTabs .nav-link {
        color: var(--lpg-muted);
        border-color: transparent;
        border-radius: .65rem .65rem 0 0;
    }

    .lpg-report-theme #lpgCollectionReportSubTabs .nav-link:hover:not(.active),
    .lpg-report-theme #lpgInventorySubTabs .nav-link:hover:not(.active) {
        color: var(--lpg-accent);
        background-color: var(--lpg-surface);
    }

    .lpg-report-theme #lpgCollectionReportSubTabs .nav-link.active,
    .lpg-report-theme #lpgInventorySubTabs .nav-link.active {
        color: var(--lpg-surface);
        background-color: var(--lpg-accent);
        border-color: var(--lpg-accent) var(--lpg-accent) var(--lpg-surface);
    }

    .lpg-report-theme .table {
        --bs-table-bg: var(--lpg-surface);
        --bs-table-color: var(--lpg-text);
        --bs-table-border-color: var(--lpg-border);
        --bs-table-striped-bg: rgba(17, 68, 71, .025);
        --bs-table-striped-color: var(--lpg-text);
    }
/**/
    .lpg-report-theme .table thead th {
        color: #ECECEC !important;
        background-color: #DEDBDB !important;
        border-bottom: 1px solid rgba(222, 219, 219, .22);
        font-weight: 600;
        vertical-align: middle;
        white-space: nowrap;
    }

    .lpg-report-theme .table thead th .bi {
        color: #ECECEC;
        font-size: .85em;
        margin-right: .35rem;
        vertical-align: -.03em;
    }

    .lpg-report-theme .table-light {
        --bs-table-bg: var(--lpg-highlight);
        --bs-table-color: var(--lpg-dark);
        --bs-table-border-color: var(--lpg-border);
    }

    .lpg-report-theme .table-hover > tbody > tr:hover > * {
        --bs-table-bg: rgba(71, 87, 103, .08);
        color: var(--lpg-text);
    }

    .lpg-report-theme .lpg-cash-denomination-row:hover {
        background-color: var(--lpg-surface);
    }

    .lpg-report-theme .lpg-cash-total-row,
    .lpg-report-theme .lpg-cash-actual-row {
        background-color: var(--lpg-highlight);
        color: var(--lpg-dark);
    }

    .lpg-report-theme .lpg-cash-actual-row {
        border-top: 2px solid var(--lpg-accent) !important;
    }

    .lpg-report-theme .form-control:focus,
    .lpg-report-theme .form-select:focus {
        border-color: var(--lpg-primary);
        box-shadow: 0 0 0 .25rem rgba(171, 119, 211, .18);
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
				@include('pages.cashiers_report_form_lpg.tabs.cashier_report_lpg_inventory_tab')
				@include('pages.cashiers_report_form_lpg.tabs.cashier_report_lpg_expenses_tab')
				@include('pages.cashiers_report_form_lpg.tabs.cashier_report_lpg_collection_report_tab')


            </div>

        </div>

	
	@include('pages.cashiers_report_form_lpg.modals.update_cashier_report_modal')

	@include('pages.cashiers_report_form_lpg.modals.lpg_accounts_receivable_modal')

	@include('pages.cashiers_report_form_lpg.modals.cashier_report_lpg_beginning_inventory_form_modal')
	@include('pages.cashiers_report_form_lpg.modals.cashier_report_lpg_swapping_form_modal')
	@include('pages.cashiers_report_form_lpg.modals.cashier_report_lpg_on_loan_form_modal')
	
	@include('pages.cashiers_report_form_lpg.modals.cashier_report_lpg_ar_so_form_modal')
	@include('pages.cashiers_report_form_lpg.modals.cashier_report_lpg_ar_so_delete_modal')

	@include('pages.cashiers_report_form_lpg.modals.cashier_report_lpg_expenses_form_modal')
	@include('pages.cashiers_report_form_lpg.modals.cashier_report_lpg_expenses_delete_modal')	
	
	@include('pages.cashiers_report_form_lpg.modals.cashier_report_lpg_non_cash_payment_form_modal')
	@include('pages.cashiers_report_form_lpg.modals.cashier_report_lpg_non_cash_payment_delete_modal')	

	@if(in_array(session('UserType'), ['Admin', 'SUAdmin', 'Encoder'], true))
		@include('pages.client.modals.client_create_edit_modal', ['includeClientFeedbackModals' => false])
	@endif

	@include('pages.validation.modals.validation_modal')
	@include('pages.user_account_settings.modals.logout_modal')	
    </section>


</main>

	@include('pages.cashiers_report_form_lpg.scripts.plugins_script')
	@include('pages.cashiers_report_form_lpg.scripts.information_script')
	@include('pages.cashiers_report_form_lpg.scripts.cashier_report_lpg_update_modal_script')

	@include('pages.cashiers_report_form_lpg.scripts.lpg_accounts_receivable_script')	
	@include('pages.cashiers_report_form_lpg.scripts.cashier_report_lpg_ar_so_script')
	@include('pages.cashiers_report_form_lpg.scripts.cashier_report_lpg_inventory_script')
	@include('pages.cashiers_report_form_lpg.scripts.cashier_report_lpg_container_script')
	@include('pages.cashiers_report_form_lpg.scripts.cashier_report_lpg_expenses_script')
	@include('pages.cashiers_report_form_lpg.scripts.cashier_report_lpg_non_cash_payment_script')
	@include('pages.cashiers_report_form_lpg.scripts.cashier_report_lpg_cash_script')

	@include('pages.validation.scripts.validation_script')
	@if(in_array(session('UserType'), ['Admin', 'SUAdmin', 'Encoder'], true))
		@include('pages.cashiers_report_form_lpg.scripts.lpg_client_add_script')
	@endif


@endsection

@section('skip_default_footer', '1')

