@extends('layouts.layout')

@section('content')

@include('pages.cashiers_report_form_lpg.partials.report_theme')

<main id="main" class="main lpg-report-theme">

    <section class="section">

        <div class="card rounded-4 p-3">

			@include('pages.cashiers_report_form_lpg.tabs.information')
			
            @include('pages.cashiers_report_form_lpg.tabs.navigation')

            <div class="tab-content pt-2">

                @include('pages.cashiers_report_form_lpg.tabs.cashier_report_lpg_sell_out_tab')
                @include('pages.cashiers_report_form_lpg.tabs.cashier_report_lpg_kitchen_tab')
                @include('pages.cashiers_report_form_lpg.tabs.cashier_report_lpg_empty_inventory_tab')
                @include('pages.cashiers_report_form_lpg.tabs.lpg_accounts_receivable')
				@include('pages.cashiers_report_form_lpg.tabs.cashier_report_lpg_inventory_tab')
				@include('pages.cashiers_report_form_lpg.tabs.cashier_report_lpg_expenses_tab')
				@include('pages.cashiers_report_form_lpg.tabs.cashier_report_lpg_collection_report_tab')


            </div>

        </div>

	
	@include('pages.cashiers_report_form_lpg.modals.update_cashier_report_modal')

	@include('pages.cashiers_report_form_lpg.modals.lpg_accounts_receivable_modal')

	@include('pages.cashiers_report_form_lpg.modals.cashier_report_lpg_beginning_inventory_form_modal')
	@include('pages.cashiers_report_form_lpg.modals.cashier_report_lpg_sell_out_sources_modal')
	@include('pages.cashiers_report_form_lpg.modals.cashier_report_lpg_kitchen_modals')
	@include('pages.cashiers_report_form_lpg.modals.cashier_report_lpg_swapping_form_modal')
    @include('pages.cashiers_report_form_lpg.modals.cashier_report_lpg_swapping_entries_modal')
    @include('pages.cashiers_report_form_lpg.modals.cashier_report_lpg_regulator_sources_modal')
	@include('pages.cashiers_report_form_lpg.modals.cashier_report_lpg_on_loan_form_modal')
	@include('pages.cashiers_report_form_lpg.modals.cashier_report_lpg_empty_inventory_modal')
	
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
	@include('pages.cashiers_report_form_lpg.scripts.lpg_product_autocomplete_script')
	@include('pages.cashiers_report_form_lpg.scripts.information_script')
	@include('pages.cashiers_report_form_lpg.scripts.cashier_report_lpg_update_modal_script')

	@include('pages.cashiers_report_form_lpg.scripts.lpg_accounts_receivable_script')	
	@include('pages.cashiers_report_form_lpg.scripts.cashier_report_lpg_ar_so_script')
	@include('pages.cashiers_report_form_lpg.scripts.cashier_report_lpg_empty_receipt_script')
	@include('pages.cashiers_report_form_lpg.scripts.cashier_report_lpg_inventory_script')
    @include('pages.cashiers_report_form_lpg.scripts.cashier_report_lpg_actual_inventory_script')
	@include('pages.cashiers_report_form_lpg.scripts.cashier_report_lpg_sell_out_script')
    @include('pages.cashiers_report_form_lpg.scripts.cashier_report_lpg_swapping_returns_script')
	@include('pages.cashiers_report_form_lpg.scripts.cashier_report_lpg_container_script')
    @include('pages.cashiers_report_form_lpg.scripts.cashier_report_lpg_regulator_script')
	@include('pages.cashiers_report_form_lpg.scripts.cashier_report_lpg_empty_inventory_script')
	@include('pages.cashiers_report_form_lpg.scripts.cashier_report_lpg_expenses_script')
	@include('pages.cashiers_report_form_lpg.scripts.cashier_report_lpg_kitchen_script')
	@include('pages.cashiers_report_form_lpg.scripts.cashier_report_lpg_non_cash_payment_script')
	@include('pages.cashiers_report_form_lpg.scripts.cashier_report_lpg_cash_script')

	@include('pages.validation.scripts.validation_script')
	@if(in_array(session('UserType'), ['Admin', 'SUAdmin', 'Encoder'], true))
		@include('pages.cashiers_report_form_lpg.scripts.lpg_client_add_script')
	@endif


@endsection

@section('skip_default_footer', '1')

