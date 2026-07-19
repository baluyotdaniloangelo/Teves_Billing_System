<!-- PRODUCT TAB -->
<div class="tab-pane fade show active"
     id="purchase_order_info"
     role="tabpanel"
     aria-labelledby="purchase_order_info">

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <!-- HEADER -->
<div class="card-header bg-white border-0 py-3 px-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap">

        <!-- LEFT -->
        <div>
			<!--
            <small class="text-muted text-uppercase fw-semibold">
                Purchase Order
            </small>
			
            <h5 class="fw-bold mb-0">
                <i class="bi bi-receipt-cutoff text-success me-2"></i>
                Control Number
            </h5>
			-->
        </div>

        <!-- RIGHT -->
        <div class="d-flex align-items-center gap-2">
			
			<h5 class="fw-bold mb-0">
                <i class="bi bi-receipt-cutoff text-success me-2"></i>
                Control Number
            </h5>
			
            <span class="badge rounded-pill bg-success fs-6 px-4 py-2 shadow-sm"
                  id="control_no">
                
            </span>

            <button type="button"
                    class="btn btn-success rounded-3 shadow-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#UpdatePurchaseOrderModal">

                <i class="bi bi-pencil-fill me-1"></i>
                Edit
            </button> 
			<button type="button" 
					class="btn btn-dark rounded-3 shadow-sm"
					id="PrintPurchaseOrder">
				<i class="bi-printer-fill me-1"></i>
					Print
			</button>
        </div>

    </div>

</div>

        <!-- BODY -->

		<div class="card-body p-4">



			<!-- CONTENT -->
		<div class="card-body">

			<div class="row g-3">

				<div class="col-md-6">
					<div class="border rounded-3 p-3 h-100">
						<small class="text-muted">
							<i class="bi bi-calendar-event me-1"></i>Date
						</small>
						<div class="fw-semibold fs-6" id="po_info_date">-</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="border rounded-3 p-3 h-100">
						<small class="text-muted">
							<i class="bi bi-building me-1"></i>Branch
						</small>
						<div class="fw-semibold" id="po_info_branch_name">-</div>
					</div>
				</div>

				<div class="col-12">
					<div class="border rounded-3 p-3">
						<small class="text-muted">
							<i class="bi bi-person-fill me-1"></i>Supplier
						</small>
						<div class="fw-semibold" id="po_info_suppliers_name">-</div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="border rounded-3 p-3 text-center">
						<small class="text-muted">Net Value</small>
						<h5 class="text-success mb-0" id="po_info_net_value">0.00</h5>
					</div>
				</div>

				<div class="col-md-4">
					<div class="border rounded-3 p-3 text-center">
						<small class="text-muted">Sales Invoice</small>
						<h5 class="text-primary mb-0" id="po_info_with_sales_invoice">Yes</h5>
					</div>
				</div>

				<div class="col-md-4">
					<div class="border rounded-3 p-3 text-center">
						<small class="text-muted">Less Value</small>
						<h5 class="text-danger mb-0" id="po_info_less_value">0.00</h5>
					</div>
				</div>

			</div>

			<hr class="my-4">

			<h6 class="fw-bold mb-3">
				<i class="bi bi-file-earmark-text me-2"></i>
				References
			</h6>

			<div class="row g-3">

				<div class="col-md-6">
					<label class="text-muted small">Sales Order #</label>
					<div class="fw-semibold" id="po_info_sales_order"></div>
				</div>

				<div class="col-md-6">
					<label class="text-muted small">Collection Receipt #</label>
					<div class="fw-semibold" id="po_info_collection_receipt"></div>
				</div>

				<div class="col-md-6">
					<label class="text-muted small">Sales Invoice #</label>
					<div class="fw-semibold" id="po_info_sales_invoice"></div>
				</div>

				<div class="col-md-6">
					<label class="text-muted small">Delivery Receipt #</label>
					<div class="fw-semibold" id="po_info_delivery_receipt"></div>
				</div>

			</div>

			<hr class="my-4">

			<h6 class="fw-bold mb-3">
				<i class="bi bi-truck me-2"></i>
				Delivery Information
			</h6>

			<div class="row g-3">

				<div class="col-md-6">
					<label class="text-muted small">Delivery Method</label>
					<div class="fw-semibold" id="po_info_delivery_method"></div>
				</div>

				<div class="col-md-6">
					<label class="text-muted small">Loading Terminal</label>
					<div class="fw-semibold" id="po_info_loading_terminal"></div>
				</div>

				<div class="col-md-6">
					<label class="text-muted small">Hauler</label>
					<div class="fw-semibold" id="po_info_haulers_name"></div>
				</div>

				<div class="col-md-6">
					<label class="text-muted small">Driver</label>
					<div class="fw-semibold" id="po_info_drivers_name"></div>
				</div>

				<div class="col-md-6">
					<label class="text-muted small">Plate Number</label>
					<div class="fw-semibold" id="po_info_plate_number"></div>
				</div>

				<div class="col-md-6">
					<label class="text-muted small">Destination</label>
					<div class="fw-semibold" id="po_info_destination"></div>
				</div>

			</div>

			<hr class="my-4">

			<h6 class="fw-bold mb-3">
				<i class="bi bi-journal-text me-2"></i>
				Notes
			</h6>

			<div class="mb-3">
				<label class="text-muted small">Instructions</label>
				<div class="border rounded-3 p-3 bg-light"
					 id="po_info_instructions">
				</div>
			</div>

			<div>
				<label class="text-muted small">Notes</label>
				<div class="border rounded-3 p-3 bg-light"
					 id="po_info_notes">
				</div>
			</div>

		</div>
		</div>


    </div> <!-- card -->

</div> <!-- tab-pane -->