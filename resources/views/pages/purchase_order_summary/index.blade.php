@extends('layouts.layout')

@section('content')

<main id="main" class="main">

    <section class="section">

        <div class="card rounded-4">

            @include('pages.purchase_order_summary.tabs.navigation')

            <div class="tab-content pt-2">

                @include('pages.purchase_order_summary.tabs.summary')

                @include('pages.purchase_order_summary.tabs.product')

                @include('pages.purchase_order_summary.tabs.volume')

            </div>

        </div>


	
	
	
	<!--Data List for Product-->	
	<datalist id="product_list">
		<span style="display: none;"><option label="All" data-price="All" data-id="All" value="All"></option></span>
	</datalist>	
	
@include('pages.purchase_order_summary.modals.summary_modal')
@include('pages.purchase_order_summary.modals.product_modal')
@include('pages.purchase_order_summary.modals.volume_modal')	

    </section>

</main>



@endsection