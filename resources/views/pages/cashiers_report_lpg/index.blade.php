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


#getCashierReport_wrapper > .d-flex
{
    width: 100%;
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
}

#getCashierReport_wrapper .dataTables_info
{
    margin: 0;
}

#getCashierReport_wrapper .dataTables_paginate
{
    margin: 0;
}

@media (max-width: 768px)
{
    #getCashierReport_wrapper > .d-flex
    {
        flex-direction: column;
        align-items: center !important;
        gap: 10px;
    }

    #getCashierReport_wrapper .dataTables_info
    {
        text-align: center;
    }
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

							<i class="bi bi-bank text-success fs-4"></i>

						</div>

						<!-- TEXT -->
						<div>

							<h4 class="fw-bold mb-0">
								{{ $title }}
							</h4>
							<!-- --> 
							<small class="text-muted">
								For LPG
							</small>
							
						</div>

					</div>

				   <!-- ACTIONS -->
					<div class="d-flex align-items-center gap-2 flex-wrap"
						 id="bank_option">

						<!-- CREATE BUTTON -->
						<button type="button"
								class="btn btn-success rounded-3 shadow-sm px-3"
								data-bs-toggle="modal"
								data-bs-target="#CashierReportLPGModal">

							<i class="bi bi-plus-circle me-2"></i>
							Create Report

						</button>

					</div>

				</div>

			</div>

			<!-- BODY -->
			<div class="card-body p-2">



				<!-- TABLE -->
				<div class="table-responsive">
					<!-- =========================================================
					CASHIER REPORT FILTER TOOLBAR
					========================================================= -->

					<div class="card border-0 shadow-sm rounded-4 mb-3">

						<div class="card-body p-3">


							<!-- =================================================
							ROW 1 : BRANCH / SHIFT / DATE RANGE
							================================================= -->

							<div class="row g-3 align-items-end">


								<!-- =============================================
								BRANCH
								============================================== -->

								<div class="col-lg-3 col-md-6">

									<label for="filter_branch"
										   class="form-label small fw-semibold mb-1">

										<i class="bi bi-building text-success me-1"></i>

										Branch

									</label>

									<select id="filter_branch"
											class="form-select form-select-sm">

										<option value="">
											All Branches
										</option>

									@foreach ($teves_branch as $teves_branch_cols)
										<option value="{{$teves_branch_cols->branch_id}}">{{$teves_branch_cols->branch_code}}</option>
									@endforeach


									</select>

								</div>


								<!-- =============================================
								SHIFT
								============================================== -->

								<div class="col-lg-3 col-md-6">

									<label for="filter_shift"
										   class="form-label small fw-semibold mb-1">

										<i class="bi bi-clock text-warning me-1"></i>

										Shift

									</label>

									<select id="filter_shift"
											class="form-select form-select-sm">

										<option value="">
											All Shifts
										</option>

										<option value="1st Shift">1st Shift</option>
										<option value="2nd Shift">2nd Shift</option>
										<option value="3rd Shift">3rd Shift</option>
										<option value="4th Shift">4th Shift</option>
										<option value="5th Shift">5th Shift</option>
										<option value="6th Shift">6th Shift</option>

									</select>

								</div>


								<!-- =============================================
								DATE FROM
								============================================== -->

								<div class="col-lg-3 col-md-6">

									<label for="filter_date_from"
										   class="form-label small fw-semibold mb-1">

										<i class="bi bi-calendar-event text-primary me-1"></i>

										Date From

									</label>

									<input type="date"
										   id="filter_date_from"
										   class="form-control form-control-sm">

								</div>


								<!-- =============================================
								DATE TO
								============================================== -->

								<div class="col-lg-3 col-md-6">

									<label for="filter_date_to"
										   class="form-label small fw-semibold mb-1">

										<i class="bi bi-calendar-event text-primary me-1"></i>

										Date To

									</label>

									<input type="date"
										   id="filter_date_to"
										   class="form-control form-control-sm">

								</div>

							</div>


							<!-- =================================================
							ROW 2 : SEARCH / ROWS TO DISPLAY
							================================================= -->

							<div class="row g-3 align-items-end mt-1">


								<!-- =============================================
								SEARCH
								============================================== -->

								<div class="col-lg-9 col-md-8">

									<label for="cashier_report_search"
										   class="form-label small fw-semibold mb-1">

										<i class="bi bi-search text-primary me-1"></i>

										Search

									</label>

									<div class="input-group input-group-sm">

										<span class="input-group-text bg-white">

											<i class="bi bi-search text-muted"></i>

										</span>

										<input type="text"
											   id="cashier_report_search"
											   class="form-control"
											   placeholder="Search cashier, employee, branch, encoder, shift...">

									</div>

								</div>


								<!-- =============================================
								ROWS TO DISPLAY
								============================================== -->

								<div class="col-lg-3 col-md-4">

									<label for="cashier_report_length"
										   class="form-label small fw-semibold mb-1">

										<i class="bi bi-list-ol text-secondary me-1"></i>

										Rows to Display

									</label>

									<select id="cashier_report_length"
											class="form-select form-select-sm">

										<option value="10">
											10
										</option>

										<option value="20">
											20
										</option>

										<option value="30">
											30
										</option>

										<option value="40">
											40
										</option>

										<option value="50">
											50
										</option>

										<option value="100">
											100
										</option>

									</select>

								</div>

							</div>


							<!-- =================================================
							ROW 3 : RESET
							================================================= -->

							<div class="row mt-3">

								<div class="col-12 d-flex justify-content-end">

									<button type="button"
											id="resetCashierReportFilters"
											class="btn btn-light btn-sm rounded-3 px-3">

										<i class="bi bi-arrow-counterclockwise me-1"></i>

										Reset Filters

									</button>

								</div>

							</div>


						</div>

					</div>

					<table class="table dataTable display nowrap cell-border" id="getCashierReport" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th class="all">Cashier Report</th>
							</tr>
						</thead>			
											
						<tbody>
						</tbody>
					</table>

			
			</div>
		</div>
	</div>
	

@include('pages.cashiers_report_lpg.modals.cashiers_report_lpg_create_edit_modal')	
@include('pages.cashiers_report_lpg.modals.cashiers_report_lpg_delete_modal')	
@include('pages.reminders.modals.reminder_modal')	
@include('pages.user_account_settings.modals.logout_modal')	
    </section>


</main>

@include('pages.cashiers_report_lpg.scripts.plugins_script')
@include('pages.cashiers_report_lpg.scripts.customized_script')
@include('pages.cashiers_report_lpg.scripts.cashiers_report_lpg_script')

@endsection

