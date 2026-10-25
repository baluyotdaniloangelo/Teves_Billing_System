@extends('layouts.layout')
@section('body_class', 'fuel-client-shell')

@push('page_styles')
<link href="{{ asset('style/fuel-client.css') }}?v={{ filemtime(public_path('style/fuel-client.css')) }}" rel="stylesheet">
@endpush

@section('content')
<style>
div.dataTables_wrapper {
    overflow: visible !important;
}

.table-responsive {
    overflow-x: auto !important;
    overflow-y: visible !important;
}
</style>
<main id="main" class="main fuel-page fuel-client-page">
    <div class="fuel-client-page-heading">
        <div>
            <p class="fuel-client-page-eyebrow">Account Management</p>
            <h1>{{ $title }}</h1>
            <p class="fuel-client-page-subtitle">Manage client profiles, payment terms, and account settings.</p>
        </div>
        <span class="fuel-client-page-chip"><i class="bi bi-people" aria-hidden="true"></i> Client accounts</span>
    </div>

    <section class="section fuel-client-section">
		<!-- PURCHASE ORDER LIST -->
		<div class="card border-0 shadow-sm rounded-4 overflow-hidden fuel-client-panel">

			<!-- HEADER -->
			<div class="card-header bg-white border-0 fuel-client-heading">
				<div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
					<div class="d-flex align-items-center">
						<span class="fuel-client-heading-icon me-3"><i class="bi bi-people-fill" aria-hidden="true"></i></span>
						<h2 class="fuel-client-heading-title mb-0">Account directory</h2>
					</div>
					<div class="d-flex align-items-center gap-2 flex-wrap" id="client_option">
						<button type="button"
								class="btn btn-success rounded-3 shadow-sm px-3 fuel-button fuel-button-primary"
								data-bs-toggle="modal"
								data-bs-target="#CreateClientModal">
							<i class="bi bi-plus-circle me-2"></i>
							New Account
						</button>
					</div>
				</div>
			</div>
			<!-- BODY -->
			<div class="card-body p-4 fuel-client-body">

				<div class="fuel-client-toolbar">
					<div class="fuel-client-toolbar-copy">
						<span class="fuel-client-toolbar-kicker">DIRECTORY FILTERS</span>
						<strong>Find an account</strong>
						<small>Search client details or filter the list by customer type.</small>
					</div>
					<div class="fuel-client-toolbar-controls">
						<label class="fuel-client-control fuel-client-page-size" for="client-page-size">
							<span>SHOW</span>
							<select id="client-page-size" aria-label="Accounts per page"><option value="10">10</option><option value="25">25</option><option value="50">50</option></select>
						</label>
						<label class="fuel-client-search" for="client-search">
							<i class="bi bi-search" aria-hidden="true"></i>
							<input type="search" id="client-search" placeholder="Search name, TIN, address…" aria-label="Search client information">
						</label>
						<label class="fuel-client-type-filter" for="client-customer-type">
							<i class="bi bi-funnel" aria-hidden="true"></i>
							<select id="client-customer-type" aria-label="Filter by customer type">
								<option value="">All customer types</option>
								<option value="Commercial Account">Commercial Account</option>
								<option value="Vendor">Vendor</option>
								<option value="Household">Household</option>
								<option value="Outlet">Outlet</option>
								<option value="Fuel Retail Bulk">Fuel Retail Bulk</option>
								<option value="Industrial – Fuel">Industrial – Fuel</option>
								<option value="Industrial – LPG">Industrial – LPG</option>
							</select>
						</label>
						<button type="button" class="fuel-client-reset" id="client-reset-filters">Reset</button>
					</div>
				</div>


<style>


.client-avatar-placeholder{

    width:90px;
    height:90px;

    border-radius:50%;

    background:#198754;

    color:#fff;

    display:flex;

    justify-content:center;

    align-items:center;

    font-size:34px;

    font-weight:700;

    box-shadow:0 3px 10px rgba(0,0,0,.15);

}

.client-info{
    display:flex;
    flex-direction:column;
    justify-content:center;
}

.client-avatar{
    width:80px;
    height:80px;
    object-fit:cover;
    border-radius:50%;
    border:3px solid #e9ecef;
    box-shadow:0 2px 6px rgba(0,0,0,.10);
}

.client-card{

    padding:10px;

}

.client-avatar{

    width:70px;
    height:70px;

    border-radius:50%;
    object-fit:cover;

    border:3px solid #e9ecef;

}

.client-card hr{

    opacity:.12;

}

.client-actions{

   align-self:flex-start;

}

.client-actions .btn{

    margin-left:4px;

}

.client-name{

    font-size:1.15rem;
    font-weight:700;
    margin-bottom:8px;

}

.client-sub{

    color:#6c757d;
    margin-bottom:4px;

}

.client-card .badge{

    margin-right:5px;

    margin-top:4px;

}

.client-card small{

    color:#495057;

    display:block;

    margin-bottom:3px;

}

#getclientList tbody td{

    vertical-align:top;

    padding:16px;

}

#getclientList tbody tr:hover{

    background:#f8fff9;

    transition:.2s;

}

#getclientList thead th{

    background:#198754;

    color:#fff;

    border:none;

    font-weight:600;

}

#getclientList tbody tr{

    border-bottom:1px solid #edf2f7;

}
</style>
				<!-- TABLE -->
				<div class="table-responsive fuel-client-table-scroll">

					<table class="table table-hover align-middle fuel-client-table" id="getclientList" width="100%">
    <thead>
        <tr>
            <th width="5%">#</th>
            <th>Client Information</th>
        </tr>
    </thead>
</table>

				</div>

			</div>

		</div>
	

@include('pages.client.modals.client_create_edit_modal')	
@include('pages.client.modals.client_delete_modal')	
@include('pages.reminders.modals.reminder_modal')	
@include('pages.user_account_settings.modals.logout_modal')	
    </section>


</main>

@include('pages.client.scripts.plugins_script')
@include('pages.client.scripts.client_script')

@endsection

@section('skip_default_footer', '1')
