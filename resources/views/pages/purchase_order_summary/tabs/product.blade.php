<!-- PRODUCT TAB -->
<div class="tab-pane fade"
     id="bordered-ph2"
     role="tabpanel"
     aria-labelledby="ph2-tab">

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <!-- HEADER -->
        <div class="card-header bg-white border-0 py-3 px-4">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <!-- TITLE -->
                <div class="d-flex align-items-center">

                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                         style="width:55px;height:55px;">

                        <i class="bi bi-box-seam text-primary fs-4"></i>

                    </div>

                    <div>

                        <h5 class="card-title fw-bold mb-1">
                            Purchase Order Product Summary
                        </h5>

                        <small class="text-muted">
                            Product-based purchase order transactions
                        </small>

                    </div>

                </div>

                <!-- ACTION BUTTONS -->
                <div class="d-flex align-items-center gap-2 flex-wrap">

                    <div id="download_options_P"></div>

                    <div id="save_options_P"></div>

                    <button type="button"
                            class="btn btn-primary rounded-3 shadow-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#CreateReportModal_P">

                        <i class="bi bi-sliders me-2"></i>
                        Report Options

                    </button>

                </div>

            </div>

        </div>

        <!-- BODY -->
        <div class="card-body p-4">

            <!-- OPTIONAL REPORT INFO -->
            <!--
            <div class="card border-0 bg-light rounded-4 mb-4">

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <div class="small text-muted">
                                Branch Name
                            </div>

                            <div class="fw-semibold"
                                 id="branch_name_report_p">
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="small text-muted">
                                Branch Code
                            </div>

                            <div class="fw-semibold"
                                 id="branch_code_report_p">
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="small text-muted">
                                TIN
                            </div>

                            <div class="fw-semibold"
                                 id="branch_tin_report_p">
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="small text-muted">
                                Report Period
                            </div>

                            <div class="fw-semibold"
                                 id="date_range_info_p">
                            </div>

                        </div>

                        <div class="col-12">

                            <div class="small text-muted">
                                Address
                            </div>

                            <div class="fw-semibold"
                                 id="branch_address_report_p">
                            </div>

                        </div>

                    </div>

                </div>

            </div>
            -->

            <!-- TABLE -->
            <div class="table-responsive">

                <table class="table table-hover align-middle nowrap w-100"
                       id="sale_order_product_summary_table">

                    <thead class="table-light">

                        <tr>

                            <th>No.</th>
                            <th>Date</th>
                            <th>Control Number</th>
                            <th>Supplier</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Amount</th>

                        </tr>

                    </thead>

                    <tbody></tbody>

                </table>

            </div>

        </div> <!-- card-body -->

    </div> <!-- card -->

</div> <!-- tab-pane -->