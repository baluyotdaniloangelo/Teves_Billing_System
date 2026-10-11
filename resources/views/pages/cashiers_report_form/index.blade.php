@extends('layouts.layout')

@section('content')

<style>


    .fuel-report-theme {
        --fuel-report-primary: #1B9BCF;
        --fuel-report-dark: #0A5664;
        --fuel-report-surface: #F7F8E0;
        --fuel-report-accent: #C37196;
        --fuel-report-highlight: #B9C6C7;
        --fuel-report-accent-soft: rgba(195, 113, 150, .10);
        --fuel-report-accent-border: rgba(195, 113, 150, .28);
        --fuel-report-border: rgba(10, 86, 100, .14);
        --fuel-report-text: var(--fuel-report-dark);
        --fuel-report-muted: rgba(10, 86, 100, .68);
        --bs-border-color: var(--fuel-report-border);
        color: var(--fuel-report-text);
        background-color: var(--fuel-report-surface);
    }

    .fuel-report-theme .card {
        --bs-card-bg: #FFFFFF;
        --bs-card-border-color: var(--fuel-report-border);
    }

    .fuel-report-theme .card-header.bg-light {
        background-color: #0A5664 !important;
        color: #F7F8E0 !important;
    }

    .fuel-report-theme .text-body {
        color: var(--fuel-report-text) !important;
    }

    .fuel-report-theme .text-muted {
        color: var(--fuel-report-accent) !important;
    }

    .fuel-report-theme .text-primary,
    .fuel-report-theme .text-info {
        color: var(--fuel-report-dark) !important;
    }

    .fuel-report-theme .text-success {
        color: var(--fuel-report-primary) !important;
    }

    .fuel-report-theme .bg-primary.bg-opacity-10,
    .fuel-report-theme .bg-primary-subtle,
    .fuel-report-theme .bg-info.bg-opacity-10 {
        background-color: var(--fuel-report-surface) !important;
    }

    .fuel-report-theme .bg-success.bg-opacity-10 {
        background-color: rgba(27, 155, 207, .12) !important;
    }

    .fuel-report-theme .btn-success,
    .fuel-report-theme .btn-primary {
        --bs-btn-color: var(--fuel-report-dark);
        --bs-btn-bg: var(--fuel-report-primary);
        --bs-btn-border-color: var(--fuel-report-primary);
        --bs-btn-hover-color: var(--fuel-report-dark);
        --bs-btn-hover-bg: var(--fuel-report-accent);
        --bs-btn-hover-border-color: var(--fuel-report-accent);
        --bs-btn-active-color: var(--fuel-report-highlight);
        --bs-btn-active-bg: var(--fuel-report-dark);
        --bs-btn-active-border-color: var(--fuel-report-dark);
        --bs-btn-focus-shadow-rgb: 27, 155, 207;
    }

    .fuel-report-theme .btn-outline-success {
        --bs-btn-color: var(--fuel-report-dark);
        --bs-btn-border-color: var(--fuel-report-primary);
        --bs-btn-hover-color: var(--fuel-report-dark);
        --bs-btn-hover-bg: var(--fuel-report-primary);
        --bs-btn-hover-border-color: var(--fuel-report-primary);
        --bs-btn-active-color: var(--fuel-report-dark);
        --bs-btn-active-bg: var(--fuel-report-accent);
        --bs-btn-active-border-color: var(--fuel-report-accent);
        --bs-btn-focus-shadow-rgb: 27, 155, 207;
    }

    .fuel-report-theme .btn-danger {
        --bs-btn-color: var(--fuel-report-dark);
        --bs-btn-bg: var(--fuel-report-accent);
        --bs-btn-border-color: var(--fuel-report-accent);
        --bs-btn-hover-color: var(--fuel-report-surface);
        --bs-btn-hover-bg: var(--fuel-report-dark);
        --bs-btn-hover-border-color: var(--fuel-report-dark);
        --bs-btn-active-color: var(--fuel-report-surface);
        --bs-btn-active-bg: var(--fuel-report-dark);
        --bs-btn-active-border-color: var(--fuel-report-dark);
        --bs-btn-focus-shadow-rgb: 195, 113, 150;
    }

    .fuel-report-theme .btn-secondary {
        --bs-btn-color: var(--fuel-report-dark);
        --bs-btn-bg: var(--fuel-report-highlight);
        --bs-btn-border-color: var(--fuel-report-highlight);
        --bs-btn-hover-color: var(--fuel-report-dark);
        --bs-btn-hover-bg: var(--fuel-report-surface);
        --bs-btn-hover-border-color: var(--fuel-report-border);
        --bs-btn-active-color: var(--fuel-report-surface);
        --bs-btn-active-bg: var(--fuel-report-dark);
        --bs-btn-active-border-color: var(--fuel-report-dark);
    }

    .fuel-report-theme #PurchaseOrderTab {
        background-color: var(--fuel-report-surface) !important;
        border: 1px solid var(--fuel-report-border);
    }

    .fuel-report-theme #PurchaseOrderTab .nav-link {
        color: var(--fuel-report-muted);
        background-color: transparent;
        border: 1px solid transparent;
    }

	.modal .modal-footer.bg-dark, .modal .bg-dark {
    background-color: var(--fuel-report-dark) !important;
		color:var(--fuel-report-surface);
		}

    .fuel-report-theme #PurchaseOrderTab .nav-link:hover:not(.active) {
        color: var(--fuel-report-accent);
        background-color: var(--fuel-report-highlight);
    }

    .fuel-report-theme #PurchaseOrderTab .nav-link.active {
        color: var(--fuel-report-dark);
        background-color: var(--fuel-report-primary);
        border-color: var(--fuel-report-primary);
    }

    .fuel-report-theme .fuel-report-subtabs {
        background-color: var(--fuel-report-surface);
        border-color: var(--fuel-report-border);
    }

    .fuel-report-theme .fuel-report-subtabs .nav-link {
        color: var(--fuel-report-muted);
        border-color: var(--fuel-report-border);
    }

    .fuel-report-theme .fuel-report-subtabs .nav-link:hover:not(.active) {
        color: var(--fuel-report-accent);
        background-color: var(--fuel-report-accent-soft);
        border-color: var(--fuel-report-accent-border);
    }

    .fuel-report-theme .fuel-report-subtabs .nav-link.active {
        color: var(--fuel-report-dark);
        background-color: var(--fuel-report-accent);
        border-color: var(--fuel-report-accent);
    }

    .fuel-report-theme .fuel-report-subtabs .subtab-icon {
        color: var(--fuel-report-accent);
        background-color: var(--fuel-report-accent-soft);
    }

    .fuel-report-theme .misc-sales-subtabs {
        border-bottom-color: var(--fuel-report-border);
    }

    .fuel-report-theme .misc-sales-subtabs .nav-link {
        color: var(--fuel-report-muted);
    }

    .fuel-report-theme .misc-sales-subtabs .nav-link:hover:not(.active) {
        color: var(--fuel-report-accent);
        background-color: var(--fuel-report-surface);
    }

    .fuel-report-theme .misc-sales-subtabs .nav-link.active {
        color: var(--fuel-report-dark);
        background-color: var(--fuel-report-accent-soft);
        border-bottom-color: var(--fuel-report-accent);
    }

    .fuel-report-theme .misc-sales-subtabs .subtab-icon {
        color: var(--fuel-report-accent);
        background-color: var(--fuel-report-accent-soft);
    }

    .fuel-report-theme .misc-sales-subtabs .nav-link.active .subtab-icon {
        color: var(--fuel-report-highlight);
        background-color: var(--fuel-report-accent);
    }

    .fuel-report-theme #fuelCashReportSubTabs {
        border-bottom-color: var(--fuel-report-border);
    }

    .fuel-report-theme #fuelCashReportSubTabs .nav-link {
        color: var(--fuel-report-muted);
        border-color: transparent;
        border-radius: .65rem .65rem 0 0;
    }

    .fuel-report-theme #fuelCashReportSubTabs .nav-link:hover:not(.active) {
        color: var(--fuel-report-accent);
        background-color: var(--fuel-report-surface);
    }

    .fuel-report-theme #fuelCashReportSubTabs .nav-link.active {
        color: var(--fuel-report-dark);
        background-color: var(--fuel-report-accent-soft);
        border-color: var(--fuel-report-accent-border) var(--fuel-report-accent-border) var(--fuel-report-highlight);
    }

    .fuel-report-theme .table {
        --bs-table-color: var(--fuel-report-text);
        --bs-table-border-color: var(--fuel-report-border);
        --bs-table-striped-bg: rgba(10, 86, 100, .025);
        --bs-table-striped-color: var(--fuel-report-text);
    }

    .fuel-report-theme .table thead th {
        color: var(--fuel-report-surface) !important;
        background-color: var(--fuel-report-dark) !important;
        border-bottom: 1px solid var(--fuel-report-accent-border);
        font-weight: 600;
        vertical-align: middle;
        white-space: nowrap;
    }

    .fuel-report-theme .table thead th .bi {
        color: var(--fuel-report-surface);
        font-size: .85em;
        margin-right: .35rem;
        vertical-align: -.03em;
    }

    .fuel-report-theme .table-light {
        --bs-table-bg: var(--fuel-report-surface);
        --bs-table-color: var(--fuel-report-dark);
        --bs-table-border-color: var(--fuel-report-border);
    }

    .fuel-report-theme .table-hover > tbody > tr:hover > * {
        --bs-table-bg: rgba(27, 155, 207, .06);
        color: var(--fuel-report-text);
    }

    .fuel-report-theme .dt-container {
        color: var(--fuel-report-dark);
    }

    .fuel-report-theme .dt-container .dt-input {
        color: var(--fuel-report-dark);
        background-color: var(--fuel-report-surface);
        border-color: var(--fuel-report-border);
    }

    .fuel-report-theme .dt-container .dt-paging-button {
        color: var(--fuel-report-dark) !important;
        background: var(--fuel-report-surface) !important;
        background-image: none !important;
        border-color: var(--fuel-report-border) !important;
    }

    .fuel-report-theme .dt-container .dt-paging-button:not(.current):not(.disabled):hover {
        color: var(--fuel-report-dark) !important;
        background: var(--fuel-report-highlight) !important;
        background-image: none !important;
        border-color: var(--fuel-report-accent-border) !important;
    }

    .fuel-report-theme .dt-container .dt-paging-button.current {
        color: var(--fuel-report-dark) !important;
        background: var(--fuel-report-primary) !important;
        background-image: none !important;
        border-color: var(--fuel-report-primary) !important;
    }

    .fuel-report-theme .table .btn_icon_table_edit,
    .fuel-report-theme .table .btn_icon_table_view {
        --bs-btn-color: var(--fuel-report-primary);
        --bs-btn-bg: var(--fuel-report-surface);
        --bs-btn-border-color: var(--fuel-report-border);
        --bs-btn-hover-color: var(--fuel-report-surface);
        --bs-btn-hover-bg: var(--fuel-report-primary);
        --bs-btn-hover-border-color: var(--fuel-report-primary);
    }

    .fuel-report-theme .table .btn_icon_table_delete {
        --bs-btn-color: var(--fuel-report-accent);
        --bs-btn-bg: var(--fuel-report-surface);
        --bs-btn-border-color: var(--fuel-report-border);
        --bs-btn-hover-color: var(--fuel-report-surface);
        --bs-btn-hover-bg: var(--fuel-report-accent);
        --bs-btn-hover-border-color: var(--fuel-report-accent);
    }

    .fuel-report-theme .fuel-cash-denomination-row:hover {
        background-color: var(--fuel-report-surface);
    }

    .fuel-report-theme .fuel-cash-total-row,
    .fuel-report-theme .fuel-cash-actual-row {
        background-color: var(--fuel-report-surface);
        color: var(--fuel-report-dark);
    }

    .fuel-report-theme .fuel-cash-actual-row {
        border-top: 2px solid var(--fuel-report-primary) !important;
    }

    .fuel-report-theme .form-control:focus,
    .fuel-report-theme .form-select:focus,
    .modal .form-control:focus,
    .modal .form-select:focus {
        border-color: var(--fuel-report-primary);
        box-shadow: 0 0 0 .25rem rgba(27, 155, 207, .18);
    }

    .fuel-report-theme .form-control,
    .fuel-report-theme .form-select,
    .fuel-report-theme .input-group-text,
    .modal .form-control,
    .modal .form-select,
    .modal .input-group-text {
        color: var(--fuel-report-dark);
        background-color: #f7f8e054;
        border-color: var(--fuel-report-border);
    }

    .fuel-report-theme .form-control::placeholder,
    .modal .form-control::placeholder {
        color: var(--fuel-report-muted);
        opacity: 1;
    }

    .fuel-report-theme .form-control:disabled,
    .fuel-report-theme .form-select:disabled,
    .modal .form-control:disabled,
    .modal .form-select:disabled {
        color: var(--fuel-report-muted);
        background-color: var(--fuel-report-highlight);
    }

    .modal .modal-content {
        color: var(--fuel-report-dark);
        background-color: var(--fuel-report-surface);
        border-color: var(--fuel-report-border);
    }

    .modal .modal-header {
        color: var(--fuel-report-surface);
        background-color: var(--fuel-report-dark);
    }

    .modal .modal-header .text-white {
        color: var(--fuel-report-surface) !important;
    }

    .modal .modal-header .text-white-50 {
        color: rgba(247, 248, 224, .78) !important;
    }

    .modal .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }

    .modal .btn-primary,
    .modal .btn-success {
        --bs-btn-color: var(--fuel-report-dark);
        --bs-btn-bg: var(--fuel-report-primary);
        --bs-btn-border-color: var(--fuel-report-primary);
        --bs-btn-hover-color: var(--fuel-report-dark);
        --bs-btn-hover-bg: var(--fuel-report-accent);
        --bs-btn-hover-border-color: var(--fuel-report-accent);
        --bs-btn-active-color: var(--fuel-report-surface);
        --bs-btn-active-bg: var(--fuel-report-dark);
        --bs-btn-active-border-color: var(--fuel-report-dark);
    }

    .modal .btn-secondary {
        --bs-btn-color: var(--fuel-report-dark);
        --bs-btn-bg: var(--fuel-report-highlight);
        --bs-btn-border-color: var(--fuel-report-highlight);
        --bs-btn-hover-color: var(--fuel-report-dark);
        --bs-btn-hover-bg: var(--fuel-report-surface);
        --bs-btn-hover-border-color: var(--fuel-report-border);
    }

    .modal .modal-footer.bg-light,
    .modal .bg-light {
        background-color: var(--fuel-report-surface) !important;
    }

    .modal .btn-danger {
        --bs-btn-color: var(--fuel-report-dark);
        --bs-btn-bg: var(--fuel-report-accent);
        --bs-btn-border-color: var(--fuel-report-accent);
        --bs-btn-hover-color: var(--fuel-report-surface);
        --bs-btn-hover-bg: var(--fuel-report-dark);
        --bs-btn-hover-border-color: var(--fuel-report-dark);
    }
</style>

<main id="main" class="main fuel-report-theme">

    <section class="section">

        <div class="card rounded-4 p-3">

			@include('pages.cashiers_report_form.tabs.information')
			
            @include('pages.cashiers_report_form.tabs.navigation')

            <div class="tab-content pt-2">

                @include('pages.cashiers_report_form.tabs.fuel_sales')
				
				@include('pages.cashiers_report_form.tabs.lubricants_and_car_care_products')

                @include('pages.cashiers_report_form.tabs.withdrawal')

                @include('pages.cashiers_report_form.tabs.cashier_report_fuel_collection_report_tab')

            </div>

        </div>


	
	
	
	<!--Data List for Product-->	
	<datalist id="product_list">
		<span style="display: none;"><option label="All" data-price="All" data-id="All" value="All"></option></span>
	</datalist>	
	
	@include('pages.cashiers_report_form.modals.update_cashier_report_modal')
	@include('pages.cashiers_report_form.modals.fuel_sales_products_modal')
	@include('pages.cashiers_report_form.modals.lube_and_car_care_products_modal')
	@include('pages.cashiers_report_form.modals.miscellaneous_items_products_modal')
	@include('pages.cashiers_report_form.modals.dipstick_inventory_modal')
	@include('pages.cashiers_report_form.modals.non_cash_payment_modal')

	@include('pages.validation.modals.validation_modal')

	@include('pages.user_account_settings.modals.logout_modal')	
    </section>


</main>



@include('pages.cashiers_report_form.scripts.plugins_script')
@include('pages.cashiers_report_form.scripts.information_script')
@include('pages.cashiers_report_form.scripts.cashier_report_update_modal_script')

@include('pages.cashiers_report_form.scripts.load_option_script')



@include('pages.cashiers_report_form.scripts.fuel_sales_products_script')
@include('pages.cashiers_report_form.scripts.lube_and_car_care_products_script')
@include('pages.cashiers_report_form.scripts.miscellaneous_items_products_script')

<!--For Revision-->
@include('pages.cashiers_report_form.scripts.dipstick_inventory_script')
@include('pages.cashiers_report_form.scripts.cash_on_hand_script')



@include('pages.validation.scripts.validation_script')

@include('pages.cashiers_report_form.scripts.non_cash_payment_script')


@endsection

@section('page_footer')
    <script src="{{ asset('NiceAdmin-pro/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
@endsection
