@extends('layouts.layout')

@section('content')

<main id="main" class="main">

    <section class="section">

        <div class="card rounded-4 p-3">

			@include('pages.cashiers_report_form.tabs.information')
			
            @include('pages.cashiers_report_form.tabs.navigation')

            <div class="tab-content pt-2">

               

                @include('pages.cashiers_report_form.tabs.fuel')

                @include('pages.cashiers_report_form.tabs.withdrawal')

                @include('pages.cashiers_report_form.tabs.payment')

            </div>

        </div>


	
	
	
	<!--Data List for Product-->	
	<datalist id="product_list">
		<span style="display: none;"><option label="All" data-price="All" data-id="All" value="All"></option></span>
	</datalist>	
	
@include('pages.cashiers_report_form.modals.update_cashier_report_modal')

@include('pages.user_account_settings.modals.logout_modal')	
    </section>


</main>

@include('pages.cashiers_report_form.scripts.plugins_script')
@include('pages.cashiers_report_form.scripts.information_script')
@include('pages.cashiers_report_form.scripts.update_purchase_order_script')

@endsection

