@extends('layouts.layout')

@section('content')

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

							<i class="bi bi-receipt-cutoff text-success fs-4"></i>

						</div>

						<!-- TEXT -->
						<div>

							<h4 class="fw-bold mb-0">
								{{ $title }}
							</h4>
							
							<small class="text-muted">
								Purchase order management and monitoring
							</small>

						</div>

					</div>

				   <!-- ACTIONS -->
					<div class="d-flex align-items-center gap-2 flex-wrap"
						 id="purchase_order_option">

						<!-- CREATE BUTTON -->
						<button type="button"
								class="btn btn-success rounded-3 shadow-sm px-3"
								data-bs-toggle="modal"
								data-bs-target="#CreatePurchaseOrderModal">

							<i class="bi bi-plus-circle me-2"></i>
							New Purchase Order

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

					<table class="table table-hover align-middle nowrap w-100"
						   id="getPurchaseOrderList">

						<thead class="table-light">

							<tr>

								<th class="all">#</th>
								<th class="all">Date</th>
								<th class="all">Control Number</th>
								<th class="all">Supplier</th>
								<th class="all">Sales Order #</th>
								<th class="all">Sales Invoice #</th>
								<th class="none">Total Payable</th>
								<th class="all">Withdrawal Status</th>
								<th class="all">Payment Status</th>
								<th class="all text-center">Action</th>
								

							</tr>

						</thead>

						<tbody></tbody>

					</table>

				</div>

			</div>

		</div>
	

@include('pages.purchase_order.modals.purchase_order_create_modal')	
@include('pages.purchase_order.modals.purchase_order_delete_modal')	
@include('pages.purchase_order.modals.purchase_order_gallery_modal')	
@include('pages.user_account_settings.modals.logout_modal')	

</section>


</main>

@include('pages.purchase_order.scripts.plugins_script')
@include('pages.purchase_order.scripts.customized_script')
@include('pages.purchase_order.scripts.purchase_order_script')

@endsection

