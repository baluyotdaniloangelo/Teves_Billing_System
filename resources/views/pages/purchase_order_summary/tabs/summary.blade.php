<div class="tab-content pt-3" id="borderedTabContent">

    <!-- SUMMARY TAB -->
    <div class="tab-pane fade show active"
         id="bordered-ph1"
         role="tabpanel"
         aria-labelledby="ph1-tab">

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

                        <div id="download_options"></div>

                        <div id="save_options"></div>

                        <button type="button"
                                class="btn btn-success rounded-3 shadow-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#CreateReportModal">

                            <i class="bi bi-sliders me-2"></i>
                            Report Options

                        </button>

                    </div>

                </div>

            </div>

            <!-- BODY -->
            <div class="card-body p-4">

                <!-- REPORT INFO -->
                <div class="card border-0 bg-light rounded-4 mb-4">

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <div class="small text-muted">
                                    Branch Name
                                </div>

                                <div class="fw-semibold"
                                     id="branch_name_report">
                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="small text-muted">
                                    Branch Code
                                </div>

                                <div class="fw-semibold"
                                     id="branch_code_report">
                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="small text-muted">
                                    TIN
                                </div>

                                <div class="fw-semibold"
                                     id="branch_tin_report">
                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="small text-muted">
                                    Report Period
                                </div>

                                <div class="fw-semibold"
                                     id="date_range_info">
                                </div>

                            </div>

                            <div class="col-12">

                                <div class="small text-muted">
                                    Address
                                </div>

                                <div class="fw-semibold"
                                     id="branch_address_report">
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- TABLE -->
                <div class="table-responsive">

                    <table class="table table-hover align-middle nowrap w-100"
                           id="sale_sorder_summary_table">

                        <thead class="table-light">

                            <tr>

                                <th class="all">No.</th>
								<th class="all">Date</th>
								<th class="all">Control Number</th>
								<th class="all">Supplier</th>
								<th class="none">Sales Order #</th>
								<th class="none">Sales Invoice #</th>
								<th class="all">Total Sales</th>
								<th class="all">VATable Sales</th>
								<th class="all">Withholding Tax</th>
								<th class="all">Total Payable</th>
								<th class="all">Withdrawal Status</th>
								<th class="all">Payment Status</th>

                            </tr>

                        </thead>

                        <tbody></tbody>

                        <tfoot>
												<tr>
													<td align="left" ></td>
													<td align="left"></td>
													<td align="left" ></td>
													<td align="left" ></td>
													<td align="left" ></td>
													<td align="left" >TOTAL:</td>
													<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_gross_amount" style="font-weight: normal;">0.00</span></td>
													
													<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_net_amount" style="font-weight: normal;">0.00</span></td>
													<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_withholding_tax" style="font-weight: normal;">0.00</span></td>
													
													<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_amount_due" style="font-weight: normal;">0.00</span></td>
													<td align="left" colspan="2"></td>
												</tr>
											</tfoot>

                    </table>

                </div>

            </div>

        </div>

    </div>