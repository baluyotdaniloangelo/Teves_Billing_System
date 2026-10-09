@extends('layouts.layout')

@section('content')

<style>
    .fuel-report-theme {
        --fuel-report-brand: #198754;
        --fuel-report-brand-dark: #146C43;
        --fuel-report-soft-green: #EAF5EF;
        --fuel-report-subtab: #0F766E;
        --fuel-report-subtab-soft: #E8F5F3;
        --fuel-report-subtab-border: #CFE5E0;
        --fuel-report-page: #F5F7F5;
        --fuel-report-border: #DCE5DE;
        --fuel-report-text: #26332B;
        --fuel-report-muted: #6B756E;
        --bs-border-color: var(--fuel-report-border);
        color: var(--fuel-report-text);
        background-color: var(--fuel-report-page);
    }

    .fuel-report-theme .card {
        --bs-card-bg: #FFFFFF;
        --bs-card-border-color: var(--fuel-report-border);
    }

    .fuel-report-theme .card-header.bg-light {
        background-color: var(--fuel-report-soft-green) !important;
    }

    .fuel-report-theme .text-body {
        color: var(--fuel-report-text) !important;
    }

    .fuel-report-theme .text-muted {
        color: var(--fuel-report-muted) !important;
    }

    .fuel-report-theme .text-primary,
    .fuel-report-theme .text-info {
        color: var(--fuel-report-brand-dark) !important;
    }

    .fuel-report-theme .bg-primary.bg-opacity-10,
    .fuel-report-theme .bg-primary-subtle,
    .fuel-report-theme .bg-info.bg-opacity-10 {
        background-color: var(--fuel-report-soft-green) !important;
    }

    .fuel-report-theme .btn-success,
    .fuel-report-theme .btn-primary {
        --bs-btn-bg: var(--fuel-report-brand);
        --bs-btn-border-color: var(--fuel-report-brand);
        --bs-btn-hover-bg: var(--fuel-report-brand-dark);
        --bs-btn-hover-border-color: var(--fuel-report-brand-dark);
        --bs-btn-active-bg: var(--fuel-report-brand-dark);
        --bs-btn-active-border-color: var(--fuel-report-brand-dark);
        --bs-btn-focus-shadow-rgb: 25, 135, 84;
    }

    .fuel-report-theme #PurchaseOrderTab {
        background-color: var(--fuel-report-soft-green) !important;
        border: 1px solid var(--fuel-report-border);
    }

    .fuel-report-theme #PurchaseOrderTab .nav-link {
        color: var(--fuel-report-muted);
        background-color: transparent;
        border: 1px solid transparent;
    }

    .fuel-report-theme #PurchaseOrderTab .nav-link:hover:not(.active) {
        color: var(--fuel-report-brand-dark);
        background-color: #FFFFFF;
    }

    .fuel-report-theme #PurchaseOrderTab .nav-link.active {
        color: #FFFFFF;
        background-color: var(--fuel-report-brand);
        border-color: var(--fuel-report-brand);
    }

    .fuel-report-theme .fuel-report-subtabs {
        background-color: var(--fuel-report-page);
        border-color: var(--fuel-report-border);
    }

    .fuel-report-theme .fuel-report-subtabs .nav-link {
        color: var(--fuel-report-muted);
        border-color: var(--fuel-report-border);
    }

    .fuel-report-theme .fuel-report-subtabs .nav-link:hover:not(.active) {
        color: var(--fuel-report-subtab);
        background-color: var(--fuel-report-subtab-soft);
        border-color: var(--fuel-report-subtab-border);
    }

    .fuel-report-theme .fuel-report-subtabs .nav-link.active {
        color: #FFFFFF;
        background-color: var(--fuel-report-subtab);
        border-color: var(--fuel-report-subtab);
    }

    .fuel-report-theme .fuel-report-subtabs .subtab-icon {
        color: var(--fuel-report-subtab);
        background-color: var(--fuel-report-subtab-soft);
    }

    .fuel-report-theme .misc-sales-subtabs {
        border-bottom-color: var(--fuel-report-border);
    }

    .fuel-report-theme .misc-sales-subtabs .nav-link {
        color: var(--fuel-report-muted);
    }

    .fuel-report-theme .misc-sales-subtabs .nav-link:hover:not(.active) {
        color: var(--fuel-report-subtab);
        background-color: var(--fuel-report-page);
    }

    .fuel-report-theme .misc-sales-subtabs .nav-link.active {
        color: var(--fuel-report-subtab);
        background-color: var(--fuel-report-subtab-soft);
        border-bottom-color: var(--fuel-report-subtab);
    }

    .fuel-report-theme .misc-sales-subtabs .subtab-icon {
        color: var(--fuel-report-subtab);
        background-color: var(--fuel-report-subtab-soft);
    }

    .fuel-report-theme .misc-sales-subtabs .nav-link.active .subtab-icon {
        color: #FFFFFF;
        background-color: var(--fuel-report-subtab);
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
        color: var(--fuel-report-subtab);
        background-color: var(--fuel-report-page);
    }

    .fuel-report-theme #fuelCashReportSubTabs .nav-link.active {
        color: var(--fuel-report-subtab);
        background-color: var(--fuel-report-subtab-soft);
        border-color: var(--fuel-report-subtab-border) var(--fuel-report-subtab-border) #FFFFFF;
    }

    .fuel-report-theme .table {
        --bs-table-color: var(--fuel-report-text);
        --bs-table-border-color: var(--fuel-report-border);
        --bs-table-striped-bg: rgba(25, 135, 84, .035);
        --bs-table-striped-color: var(--fuel-report-text);
    }

    .fuel-report-theme .table thead th {
        color: var(--fuel-report-brand-dark);
        background-color: var(--fuel-report-soft-green) !important;
        border-bottom: 1px solid var(--fuel-report-subtab-border);
        font-weight: 600;
        vertical-align: middle;
        white-space: nowrap;
    }

    .fuel-report-theme .table thead th .bi {
        color: var(--fuel-report-brand);
        font-size: .85em;
        margin-right: .35rem;
        vertical-align: -.03em;
    }

    .fuel-report-theme .table-light {
        --bs-table-bg: var(--fuel-report-soft-green);
        --bs-table-color: var(--fuel-report-brand-dark);
        --bs-table-border-color: var(--fuel-report-border);
    }

    .fuel-report-theme .table-hover > tbody > tr:hover > * {
        --bs-table-bg: var(--fuel-report-page);
        color: var(--fuel-report-text);
    }

    .fuel-report-theme .fuel-cash-denomination-row:hover {
        background-color: var(--fuel-report-page);
    }

    .fuel-report-theme .fuel-cash-total-row,
    .fuel-report-theme .fuel-cash-actual-row {
        background-color: var(--fuel-report-soft-green);
        color: var(--fuel-report-brand-dark);
    }

    .fuel-report-theme .fuel-cash-actual-row {
        border-top: 2px solid var(--fuel-report-brand-dark) !important;
    }

    .fuel-report-theme .form-control:focus,
    .fuel-report-theme .form-select:focus {
        border-color: var(--fuel-report-brand);
        box-shadow: 0 0 0 .25rem rgba(25, 135, 84, .18);
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


@include('pages.validation.modals.validation_modal')

@include('pages.user_account_settings.modals.logout_modal')	
    </section>


</main>

@include('pages.cashiers_report_form.modals.dipstick_inventory_modal')
@include('pages.cashiers_report_form.modals.non_cash_payment_modal')

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

