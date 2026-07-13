@extends('layouts.layout')

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
<main id="main" class="main">

    <section class="section">
		<!-- PURCHASE ORDER LIST -->
		<div class="card border-0 shadow-sm rounded-4 overflow-hidden">

			<!-- HEADER -->
			<div class="card-header bg-white border-0 py-3 px-4">

				<div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

					<!-- TITLE -->
					<div class="d-flex align-items-center">

						<!-- ICON -->
						<div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
							 style="width:55px;height:55px;">

							<i class="bi bi-people-fill text-success fs-4"></i>

						</div>

						<!-- TEXT -->
						<div>

							<h4 class="fw-bold mb-0">
								{{ $title }}
							</h4>
							
							<small class="text-muted">
								Manage Account List
							</small>

						</div>

					</div>

				   <!-- ACTIONS -->
					<div class="d-flex align-items-center gap-2 flex-wrap"
						 id="client_option">

						<!-- CREATE BUTTON -->
						<button type="button"
								class="btn btn-success rounded-3 shadow-sm px-3"
								data-bs-toggle="modal"
								data-bs-target="#CreateClientModal">

							<i class="bi bi-plus-circle me-2"></i>
							New Account

						</button>

					</div>

				</div>

			</div>

			<!-- BODY -->
			<div class="card-body p-4">

				<!-- TOOLBAR -->
				<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">

					<!-- LEFT
					<div>

						<h6 class="fw-semibold mb-0">
							Purchase Order List
						</h6>

						<small class="text-muted">
							Monitor and manage purchase order transactions
						</small>

					</div> -->

					<!-- RIGHT -->
					<div class="d-flex align-items-center gap-2">

						<!-- OPTIONAL FILTER -->
						<!--
						<button class="btn btn-outline-success btn-sm rounded-3">

							<i class="bi bi-funnel me-1"></i>
							Filter

						</button>
						-->

						<!-- OPTIONAL EXPORT -->
						<!--
						<button class="btn btn-outline-primary btn-sm rounded-3">

							<i class="bi bi-download me-1"></i>
							Export

						</button>
						-->

					</div>

				</div>

				<!-- TABLE -->
				<div class="table-responsive">

					<table class="table dataTable display nowrap cell-border" id="getclientList" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th>#</th>
													<th class="all">Name</th>
													<th class="all">Type</th>
													<th class="all">Account Number</th>
													<th class="none">Address</th>
													<th class="none">TIN</th>
													<th>Referred By</th>
													<th title='Contact Number' class="none">Contact Number</th>
													<th title='Age' class="none">Age</th>
													<th title='Default Value' class="none">Discount(Less)</th>
													<th class="none">Net</th>
													<th class="none">Vat</th>
													<th class="none">Withhoding Tax</th>
													<th class="none">Payment Terms</th>
													<th class="all">Action</th>
												</tr>
											</thead>				
											
											<tbody>
												
											</tbody>
											
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

