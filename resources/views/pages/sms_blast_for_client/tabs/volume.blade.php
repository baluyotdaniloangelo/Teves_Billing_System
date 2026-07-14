<!-- VOLUME TAB -->
<div class="tab-pane fade"
     id="bordered-ph3"
     role="tabpanel"
     aria-labelledby="ph3-tab">

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <!-- HEADER -->
        <div class="card-header bg-white border-0 py-3 px-4">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <!-- TITLE -->
                <div class="d-flex align-items-center">

                    <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                         style="width:55px;height:55px;">

                        <i class="bi bi-bar-chart-line-fill text-success fs-4"></i>

                    </div>

                    <div>

                        <h5 class="card-title fw-bold mb-1">
                            Volume Summary
                        </h5>
						<!--
                        <small class="text-muted">
                            Dynamic monthly fuel volume monitoring
                        </small>
						-->
                    </div>

                </div>

                <!-- ACTION BUTTONS -->
                <div class="d-flex align-items-center gap-2 flex-wrap">

                    <div id="download_options_volume"></div>

                    <div id="save_options_volume"></div>

                    <button type="button"
                            class="btn btn-success rounded-3 shadow-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#CreateReportModal_volume">

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

                <!-- MAIN ICON -->
                <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                     style="width:55px;height:55px;">

                    <i class="bi bi-bar-chart-line-fill text-success fs-4"></i>

                </div>

                <!-- TITLE -->
                <div>

                    <h5 class="fw-bold mb-0">
                        Report Information
                    </h5>

                </div>

            </div>

            <!-- REPORT PERIOD -->
            <div class="text-md-end">

                <div class="small text-muted mb-1">

                    <i class="bi bi-calendar-range me-1"></i>
                    Report Period

                </div>

                <div class="fw-bold fs-6 text-success"
                     id="date_range_info_volume">
                </div>

            </div>

        </div>

        <!-- CONTENT -->
        <div class="row g-4">

            <!-- BRANCH -->
            <div class="col-md-6"
                 id="branch_volume_report">

                <!-- BRANCH NAME -->
                <div class="d-flex mb-4">

                    <div class="me-3">

                        <div class="bg-success bg-opacity-10 rounded-3 p-2">

                            <i class="bi bi-building text-success"></i>

                        </div>

                    </div>

                    <div>

                        <div class="small text-muted">
                            Branch Name
                        </div>

                        <div class="fw-semibold fs-6 text-success"
                             id="branch_name_volume_report">
                        </div>

                    </div>

                </div>

                <!-- BRANCH CODE -->
                <div class="d-flex mb-4">

                    <div class="me-3">

                        <div class="bg-primary bg-opacity-10 rounded-3 p-2">

                            <i class="bi bi-upc-scan text-primary"></i>

                        </div>

                    </div>

                    <div>

                        <div class="small text-muted">
                            Branch Code
                        </div>

                        <div class="fw-semibold fs-6 text-success"
                             id="branch_code_volume_report">
                        </div>

                    </div>

                </div>

                <!-- BRANCH TIN -->
                <div class="d-flex mb-4">

                    <div class="me-3">

                        <div class="bg-warning bg-opacity-10 rounded-3 p-2">

                            <i class="bi bi-file-earmark-text text-warning"></i>

                        </div>

                    </div>

                    <div>

                        <div class="small text-muted">
                            TIN
                        </div>

                        <div class="fw-semibold fs-6 text-success"
                             id="branch_tin_volume_report">
                        </div>

                    </div>

                </div>

                <!-- BRANCH ADDRESS -->
                <div class="d-flex">

                    <div class="me-3">

                        <div class="bg-danger bg-opacity-10 rounded-3 p-2">

                            <i class="bi bi-geo-alt text-danger"></i>

                        </div>

                    </div>

                    <div>

                        <div class="small text-muted">
                            Address
                        </div>

                        <div class="fw-semibold fs-6 text-success"
                             id="branch_address_volume_report">
                        </div>

                    </div>

                </div>

            </div>

            <!-- SUPPLIER -->
            <div class="col-md-6">

                <div id="supplier_volume_report"
                     style="display:none;">

                    <!-- SUPPLIER -->
                    <div class="d-flex mb-4">

                        <div class="me-3">

                            <div class="bg-info bg-opacity-10 rounded-3 p-2">

                                <i class="bi bi-truck text-info"></i>

                            </div>

                        </div>

                        <div>

                            <div class="small text-muted">
                                Supplier
                            </div>

                            <div class="fw-semibold fs-6 text-success"
                                 id="supplier_name_volume_report">
                            </div>

                        </div>

                    </div>

                    <!-- SUPPLIER TIN -->
                    <div class="d-flex mb-4">

                        <div class="me-3">

                            <div class="bg-warning bg-opacity-10 rounded-3 p-2">

                                <i class="bi bi-file-earmark-person text-warning"></i>

                            </div>

                        </div>

                        <div>

                            <div class="small text-muted">
                                TIN
                            </div>

                            <div class="fw-semibold fs-6 text-success"
                                 id="supplier_tin_volume_report">
                            </div>

                        </div>

                    </div>

                    <!-- SUPPLIER ADDRESS -->
                    <div class="d-flex">

                        <div class="me-3">

                            <div class="bg-danger bg-opacity-10 rounded-3 p-2">

                                <i class="bi bi-pin-map text-danger"></i>

                            </div>

                        </div>

                        <div>

                            <div class="small text-muted">
                                Address
                            </div>

                            <div class="fw-semibold fs-6 text-success"
                                 id="supplier_address_volume_report">
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

            <!-- TABLE -->
            <div class="table-responsive">

                <table class="table table-hover align-middle nowrap w-100"
                       id="POVolumeSummaryTable">

                    <thead class="table-light">
                    </thead>

                    <tbody></tbody>

                </table>

            </div>

        </div> <!-- card-body -->

    </div> <!-- card -->

</div> <!-- tab-pane -->