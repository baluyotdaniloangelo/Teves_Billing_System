<div class="tab-content pt-3" id="borderedTabContent">

    <!-- SUMMARY TAB -->
    <div class="tab-pane fade "
         id="bordered-ph2"
         role="tabpanel"
         aria-labelledby="ph2-tab">

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

            <!-- HEADER -->
            <div class="card-header bg-white border-0 py-3 px-4">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                    <!-- TITLE -->
                    <div class="d-flex align-items-center">

                        <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                             style="width:55px;height:55px;">

                            <i class="bi bi-receipt-cutoff text-success fs-4"></i>

                        </div>

                        <div>

                            <h5 class="card-title fw-bold mb-1">
                                {{ $title }}
                            </h5>
							<!--
                            <small class="text-muted">
                                Purchase Order Summary Report
                            </small>
							-->
                        </div>

                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="d-flex align-items-center gap-2 flex-wrap">

                        <div id="download_options_summary"></div>

                        <div id="save_options"></div>

                        <button type="button"
                                class="btn btn-success rounded-3 shadow-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#PurchaseOrderSummaryModal">

                            <i class="bi bi-sliders me-2"></i>
                            Report Options

                        </button>

                    </div>

                </div>

            </div>

            <!-- BODY -->
            <div class="card-body p-4">

<!-- REPORT INFORMATION -->
<div class="card border-0 bg-white rounded-4 mb-4 shadow-sm">

    <div class="card-body p-4">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">

            <!-- LEFT HEADER -->
            <div class="d-flex align-items-center">

                <!-- MAIN ICON
                <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                     style="width:55px;height:55px;">

                    <i class="bi bi-clipboard-data text-success fs-4"></i>

                </div>
 -->
                <!-- TITLE -->
                <div>
					<!--
                    <h5 class="fw-bold mb-1">
                        Report Information
                    </h5>
						
						<small class="text-muted">
							Branch and Supplier reporting details
						</small>
					-->
                </div>

            </div>

            <!-- REPORT PERIOD -->
            <div class="text-md-end">

                <div class="small text-muted mb-1">

                    <i class="bi bi-calendar-range me-1"></i>
                    Report Period

                </div>

                <div class="fw-bold text-success fs-6"
                     id="date_range_info_summary">
                </div>

            </div>

        </div>



    </div>

</div>
                <!-- TABLE -->
                <div class="table-responsive">

                    <table class="table table-hover align-middle nowrap w-100"
                           id="SMSHistoryTable">

                        <thead class="table-light">

                            <tr>

                                <th class="all">No.</th>
								<th class="all">Date</th>
								<th class="all">Recipient Name</th>
								<th class="all">Contact Number</th>
								<th class="none">Message</th>

                            </tr>

                        </thead>

                        <tbody></tbody>

                       

                    </table>

                </div>

            </div>

        </div>

    </div>