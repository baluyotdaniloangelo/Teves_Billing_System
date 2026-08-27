@extends('layouts.layout')

@section('content')

<main id="main" class="main">

    <section class="section">

        <div class="card rounded-4 p-3">

            @include('pages.purchase_order_form.tabs.navigation')

            <div class="tab-content pt-2">

                @include('pages.purchase_order_form.tabs.information')

                @include('pages.purchase_order_form.tabs.product')

                @include('pages.purchase_order_form.tabs.withdrawal')

                @include('pages.purchase_order_form.tabs.payment')

            </div>

        </div>


	
	
	
	<!--Data List for Product-->	
	<datalist id="product_list">
		<span style="display: none;"><option label="All" data-price="All" data-id="All" value="All"></option></span>
	</datalist>	
	
@include('pages.purchase_order_form.modals.update_purchase_order_modal')
@include('pages.purchase_order_form.modals.purchase_order_product_modal')
@include('pages.purchase_order_form.modals.purchase_order_withdrawal_modal')
@include('pages.purchase_order_form.modals.purchase_order_payment_modal')

@include('pages.user_account_settings.modals.logout_modal')	
    </section>


</main>

@include('pages.purchase_order_form.scripts.plugins_script')
@include('pages.purchase_order_form.scripts.information_script')
@include('pages.purchase_order_form.scripts.update_purchase_order_script')
@include('pages.purchase_order_form.scripts.product_script')
@include('pages.purchase_order_form.scripts.withdrawal_script')
@include('pages.purchase_order_form.scripts.payment_script')

@endsection

