<!-- Information TAB
<div class="tab-pane fade show active"
     id="cashiers_report_info"
     role="tabpanel"
     aria-labelledby="cashiers_report_info">
 -->
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
			<!--
			<h5 class="fw-bold mb-0">
                <i class="bi bi-receipt-cutoff text-success me-2"></i>
                Control Number
            </h5>
			
            <span class="badge rounded-pill bg-success fs-6 px-4 py-2 shadow-sm"
                  id="control_no">
                
            </span>
			-->
            <button type="button"
                    class="btn btn-success rounded-3 shadow-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#UpdateCashiersReportModal">

                <i class="bi bi-pencil-fill me-1"></i>
                Edit
            </button> 
			<button type="button" 
					class="btn btn-dark rounded-3 shadow-sm"
					id="PrintCashiersReport"
					onclick="printCashierReportPDF()">
				<i class="bi-printer-fill me-1"></i>
					Print
			</button>
        </div>

    </div>

</div>

        <!-- BODY -->

		<div class="card-body p-4">



		<!-- =========================================================
		CASHIER REPORT TABS
		========================================================= -->

		<div class="card border-0 shadow-sm rounded-4">

			<!-- =====================================================
			TAB HEADER
			====================================================== -->

			<div class="card-header bg-white border-0 px-4 pt-3 pb-0">

				<ul class="nav nav-tabs nav-tabs-bordered"
					id="cashierReportTabs"
					role="tablist">


					<!-- ==============================================
					REPORT INFORMATION TAB
					=============================================== -->

					<li class="nav-item"
						role="presentation">

						<button class="nav-link active fw-semibold"
								id="cashier-info-tab"
								data-bs-toggle="tab"
								data-bs-target="#cashier-info"
								type="button"
								role="tab"
								aria-controls="cashier-info"
								aria-selected="true">

							<i class="bi bi-receipt me-2"></i>

							Report Information

						</button>

					</li>


					<!-- ==============================================
					SUMMARY TAB
					=============================================== -->

					<li class="nav-item"
						role="presentation">

						<button class="nav-link fw-semibold"
								id="cashier-summary-tab"
								data-bs-toggle="tab"
								data-bs-target="#cashier-summary"
								type="button"
								role="tab"
								aria-controls="cashier-summary"
								aria-selected="false">

							<i class="bi bi-bar-chart-line me-2"></i>

							Summary

						</button>

					</li>

				</ul>

			</div>


			<!-- =====================================================
			TAB CONTENT
			====================================================== -->

			<div class="card-body p-4">

				<div class="tab-content"
					 id="cashierReportTabContent">


					<!-- =================================================
					TAB 1
					REPORT INFORMATION
					================================================== -->

					<div class="tab-pane fade show active"
						 id="cashier-info"
						 role="tabpanel"
						 aria-labelledby="cashier-info-tab">


						<div class="row g-3">


							<!-- ======================================
							REPORT DATE
							======================================= -->

							<div class="col-md-6">

								<div class="border rounded-3 p-3 h-100">

									<small class="text-muted d-block mb-1">

										<i class="bi bi-calendar-event
												  me-1 text-primary">
										</i>

										Report Date

									</small>

									<div class="fw-semibold"
										 id="cashier_info_date">

										-

									</div>

								</div>

							</div>


							<!-- ======================================
							BRANCH
							======================================= -->

							<div class="col-md-6">

								<div class="border rounded-3 p-3 h-100">

									<small class="text-muted d-block mb-1">

										<i class="bi bi-building
												  me-1 text-success">
										</i>

										Branch

									</small>

									<div class="fw-semibold"
										 id="cashier_info_branch_name">

										-

									</div>

								</div>

							</div>


							<!-- ======================================
							CASHIER
							======================================= -->

							<div class="col-md-6">

								<div class="border rounded-3 p-3 h-100">

									<small class="text-muted d-block mb-1">

										<i class="bi bi-person-badge
												  me-1 text-info">
										</i>

										Cashier's on Duty

									</small>

									<div class="fw-semibold"
										 id="cashier_info_cashiers_name">

										-

									</div>

								</div>

							</div>


							<!-- ======================================
							EMPLOYEE ON DUTY
							======================================= -->

							<div class="col-md-6">

								<div class="border rounded-3 p-3 h-100">

									<small class="text-muted d-block mb-1">

										<i class="bi bi-person-workspace
												  me-1 text-info">
										</i>

										Employee's On-Duty

									</small>

									<div class="fw-semibold"
										 id="cashier_info_forecourt_attendant">

										-

									</div>

								</div>

							</div>


							<!-- ======================================
							SHIFT
							======================================= -->

							<div class="col-md-6">

								<div class="border rounded-3 p-3 h-100">

									<small class="text-muted d-block mb-1">

										<i class="bi bi-clock
												  me-1 text-warning">
										</i>

										Shift

									</small>

									<div class="fw-semibold"
										 id="cashier_info_shift">

										-

									</div>

								</div>

							</div>


							<!-- ======================================
							ENCODED BY
							======================================= -->

							<div class="col-md-6">

								<div class="border rounded-3 p-3 h-100">

									<small class="text-muted d-block mb-1">

										<i class="bi bi-person-check
												  me-1 text-secondary">
										</i>

										Encoded By

									</small>

									<div class="fw-semibold"
										 id="cashier_info_encoder_name">

										-

									</div>

								</div>

							</div>


							<!-- ======================================
							REMARKS
							======================================= -->

							<div class="col-12">

								<div class="border rounded-3 p-3">

									<small class="text-muted d-block mb-1">

										<i class="bi bi-chat-left-text
												  me-1 text-secondary">
										</i>

										Remarks

									</small>

									<div class="fw-semibold"
										 id="cashier_info_remarks">

										-

									</div>

								</div>

							</div>


						</div>

					</div>


					<!-- =================================================
					TAB 2
					SALES & PAYMENT SUMMARY
					================================================== -->

					<div class="tab-pane fade"
						 id="cashier-summary"
						 role="tabpanel"
						 aria-labelledby="cashier-summary-tab">


						<div class="row g-3">


							<!-- ======================================
							FUEL SALES
							======================================= -->

							<div class="col-md-6">

								<div class="border rounded-3 p-3 h-100">

									<small class="text-muted">

										<i class="bi bi-fuel-pump-fill
												  me-1 text-primary">
										</i>

										Fuel Sales

									</small>

									<div class="fw-bold fs-5 mt-1"
										 id="fuel_sales_total">

										0.00

									</div>

								</div>

							</div>


							<!-- ======================================
							OTHER SALES
							======================================= -->

							<div class="col-md-6">

								<div class="border rounded-3 p-3 h-100">

									<small class="text-muted">

										<i class="bi bi-cart-check
												  me-1 text-info">
										</i>

										Other Sales

									</small>

									<div class="fw-bold fs-5 mt-1"
										 id="other_sales_total">

										0.00

									</div>

								</div>

							</div>


							<!-- ======================================
							MISCELLANEOUS
							======================================= -->

							<div class="col-md-6">

								<div class="border rounded-3 p-3 h-100">

									<small class="text-muted">

										<i class="bi bi-dash-circle
												  me-1 text-danger">
										</i>

										Miscellaneous

									</small>

									<div class="fw-bold fs-5 text-danger mt-1"
										 id="miscellaneous_total">

										0.00

									</div>

								</div>

							</div>


							<!-- ======================================
							THEORETICAL SALES
							======================================= -->

							<div class="col-md-6">

								<div class="border rounded-3 p-3 h-100">

									<small class="text-muted">

										<i class="bi bi-calculator
												  me-1 text-secondary">
										</i>

										Theoretical Sales

									</small>

									<div class="fw-bold fs-5 mt-1"
										 id="theoretical_sales">

										0.00

									</div>

								</div>

							</div>


							<!-- ======================================
							CASH ON HAND
							======================================= -->

							<div class="col-md-6">

								<div class="border rounded-3 p-3 h-100">

									<small class="text-muted">

										<i class="bi bi-cash-stack
												  me-1 text-success">
										</i>

										Cash on Hand

									</small>

									<div class="fw-bold fs-5 text-success mt-1"
										 id="cash_on_hand">

										0.00

									</div>

								</div>

							</div>


							<!-- ======================================
							NON-CASH PAYMENT
							======================================= -->

							<div class="col-md-6">

								<div class="border rounded-3 p-3 h-100">

									<small class="text-muted">

										<i class="bi bi-credit-card
												  me-1 text-primary">
										</i>

										Non-Cash Payment

									</small>

									<div class="fw-bold fs-5 mt-1"
										 id="total_non_cash_payment">

										0.00

									</div>

								</div>

							</div>


							<!-- ======================================
							TOTAL CASH PAYMENT
							======================================= -->

							<div class="col-md-6">

								<div class="border rounded-3 p-3 h-100">

									<small class="text-muted">

										<i class="bi bi-wallet2
												  me-1 text-success">
										</i>

										Total Cash Payment

									</small>

									<div class="fw-bold fs-5 text-success mt-1"
										 id="total_cash_payment">

										0.00

									</div>

								</div>

							</div>


							<!-- ======================================
							TOTAL SALES
							======================================= -->

							<div class="col-md-6">

								<div class="border rounded-3 p-3 h-100">

									<small class="text-muted">

										<i class="bi bi-graph-up-arrow
												  me-1 text-primary">
										</i>

										Total Sales

									</small>

									<div class="fw-bold fs-4 text-primary mt-1"
										 id="total_sales">

										0.00

									</div>

								</div>

							</div>


							<!-- ======================================
							CASH SHORT / OVER
							======================================= -->

							<div class="col-12">

								<div class="border rounded-3 p-4 text-center">

									<small class="text-muted d-block">

										<i class="bi bi-clipboard-data me-1"></i>

										Cash Short / Over

									</small>

									<div class="fw-bold fs-2 mt-1"
										 id="cash_short_or_over">

										0.00

									</div>

								</div>

							</div>


						</div>

					</div>

				</div>

			</div>

		</div>
		</div>


    </div> <!-- card -->

<!-- </div> tab-pane -->