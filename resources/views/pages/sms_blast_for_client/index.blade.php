@extends('layouts.layout')

@section('content')

<main id="main" class="main">

    <section class="section">

        <div class="card rounded-4 p-3">

            @include('pages.sms_blast_for_client.tabs.navigation')

            <div class="tab-content pt-2">

                @include('pages.sms_blast_for_client.tabs.compose_sms')

                @include('pages.sms_blast_for_client.tabs.sms_history')

            </div>

        </div>


	
	
	
	<!--Data List for Product-->	
	<datalist id="product_list">
		<span style="display: none;"><option label="All" data-price="All" data-id="All" value="All"></option></span>
	</datalist>	
	


@include('pages.user_account_settings.modals.logout_modal')	
    </section>


</main>

@include('pages.sms_blast_for_client.scripts.plugins_script')
@include('pages.sms_blast_for_client.scripts.customized_script')
@include('pages.sms_blast_for_client.scripts.sms_blast_script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

