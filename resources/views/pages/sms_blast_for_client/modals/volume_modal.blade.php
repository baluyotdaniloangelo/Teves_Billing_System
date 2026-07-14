<!-- VOLUME SUMMARY MODAL -->
<div class="modal fade"
     id="CreateReportModal_volume"
     tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- HEADER -->
            <div class="modal-header bg-success text-white border-0 py-3 px-4">

                <div class="d-flex align-items-center">

                    <!-- ICON -->
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3"
                         style="width:60px;height:60px;">

                        <i class="bi bi-bar-chart-line-fill text-white fs-4"></i>

                    </div>

                    <!-- TITLE -->
                    <div>

                        <h4 class="modal-title fw-bold mb-1">
                            Volume Summary Report
                        </h4>

                        <small class="opacity-75">
                            Dynamic monthly fuel volume monitoring report
                        </small>

                    </div>

                </div>

                <!-- CLOSE -->
                <button type="button"
                        class="btn btn-light btn-sm rounded-circle shadow-sm"
                        data-bs-dismiss="modal">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>

            <!-- FORM -->
            <form id="generate_report_form_volume">

                <!-- BODY -->
                <div class="modal-body p-4">

                    <!-- BRANCH -->
                    <div class="card border-0 shadow-sm mb-3 rounded-4">

                        <div class="card-body">

                            <label class="form-label fw-semibold">

                                <i class="bi bi-building me-2 text-success"></i>
                                Branch

                            </label>

                            <select class="form-select form-select-lg rounded-3"
                                    name="company_header_volume"
                                    id="company_header_volume"
                                    required>

                                <option value="All">
                                    All Branches
                                </option>

                                @foreach ($teves_branch as $teves_branch_cols)

                                    <option value="{{$teves_branch_cols->branch_id}}">
                                        {{$teves_branch_cols->branch_code}}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>

                    <!-- SUPPLIER -->
                    <div class="card border-0 shadow-sm mb-3 rounded-4">

                        <div class="card-body">

                            <label class="form-label fw-semibold">

                                <i class="bi bi-truck me-2 text-success"></i>
                                Supplier

                            </label>

                            <input class="form-control form-control-lg rounded-3"
                                   list="supplier_name_volume"
                                   name="supplier_name_volume"
                                   id="supplier_idx_volume"
                                   autocomplete="off"
                                   placeholder="All Suppliers">

                            <datalist id="supplier_name_volume">

                                <option value="All">
                                    All Suppliers
                                </option>

                                @foreach ($supplier_data as $supplier_data_cols)

                                    <option
                                        data-id="{{$supplier_data_cols->supplier_id}}"
                                        value="{{$supplier_data_cols->supplier_name}}">
                                    </option>

                                @endforeach

                            </datalist>

                            <small class="text-muted">
                                Leave blank to include all suppliers
                            </small>

                            <span class="valid-feedback"
                                  id="supplier_idxError_volume">
                            </span>

                        </div>

                    </div>

                    <!-- PRODUCT
                    <div class="card border-0 shadow-sm mb-3 rounded-4">

                        <div class="card-body">

                            <label class="form-label fw-semibold">

                                <i class="bi bi-fuel-pump me-2 text-success"></i>
                                Product Filter

                            </label>

                            <input class="form-control form-control-lg rounded-3"
                                   list="product_list_product"
                                   name="product_name_volume"
                                   id="product_idx_volume"
                                   autocomplete="off"
                                   placeholder="All Fuel Products"> -->
							<!--
                            <small class="text-muted">
                                Leave blank to include all products
                            </small>
							-->
                       <!-- </div>

                    </div>-->

                    <!-- DATE RANGE -->
                    <div class="card border-0 shadow-sm mb-3 rounded-4">

                        <div class="card-body">

                            <!-- HEADER -->
                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

                                <label class="form-label fw-semibold mb-0">

                                    <i class="bi bi-calendar-range me-2 text-success"></i>
                                    Date Range

                                </label>

                                <!-- QUICK FILTER -->
                                <div class="btn-group btn-group-sm">

                                    <button type="button"
                                            class="btn btn-outline-success"
                                            onclick="setCurrentMonth('volume')">

                                        Current Month

                                    </button>

                                    <button type="button"
                                            class="btn btn-outline-success"
                                            onclick="setCurrentYear('volume')">

                                        Current Year

                                    </button>

                                    <button type="button"
                                            class="btn btn-outline-success"
                                            onclick="setLast12Months('volume')">

                                        Last 12 Months

                                    </button>

                                </div>

                            </div>

                            <!-- DATE INPUTS -->
                            <div class="row">

                                <!-- START DATE -->
                                <div class="col-md-6 mb-3 mb-md-0">

                                    <label class="small text-muted mb-1">
                                        Start Date
                                    </label>

                                    <input type="date"
                                           class="form-control form-control-lg rounded-3"
                                           name="start_date_volume"
                                           id="start_date_volume"
										   onclick="setMaxonEndDate('volume')"
                                           value="<?=date('Y-m-01');?>"
                                           required>

                                    <span class="valid-feedback"
                                          id="start_dateError_volume">
                                    </span>

                                </div>

                                <!-- END DATE -->
                                <div class="col-md-6">

                                    <label class="small text-muted mb-1">
                                        End Date
                                    </label>

                                    <input type="date"
                                           class="form-control form-control-lg rounded-3"
                                           name="end_date_volume"
                                           id="end_date_volume"
										   onclick="CheckEndDateValidity('volume')"
                                           value="<?=date('Y-m-d');?>"
                                           required>

                                    <span class="valid-feedback"
                                          id="end_dateError_volume">
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- INFO -->
                    <div class="alert alert-success border-0 rounded-4 mb-0">

                        <div class="d-flex align-items-start">

                            <i class="bi bi-info-circle-fill me-3 fs-5"></i>

                            <div>

                                <strong>
                                    Dynamic Volume Monitoring
                                </strong>

                                <div class="small mt-1">

                                    Monthly columns will automatically
                                    generate based on your selected
                                    date range and filters.

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer border-0 px-4 pb-4">

                    <!-- LOADING -->
                    <div id="loading_data_volume"
                         style="display:none;">

                        <div class="spinner-border text-success"
                             role="status">

                            <span class="visually-hidden">
                                Loading...
                            </span>

                        </div>

                    </div>

                    <!-- BUTTONS -->
                    <button type="button"
                            class="btn btn-light rounded-3 px-4"
                            data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit"
                            class="btn btn-success rounded-3 px-4 shadow-sm"
                            id="generate_report_volume">

                        <i class="bi bi-file-earmark-bar-graph-fill me-2"></i>
                        Generate Report

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>