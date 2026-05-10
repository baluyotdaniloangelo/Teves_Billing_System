<!-- CREATE PURCHASE ORDER MODAL -->
<div class="modal fade"
     id="CreatePurchaseOrderModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- HEADER -->
            <div class="modal-header bg-success text-white border-0 py-3 px-4">

                <div class="d-flex align-items-center">

                    <!-- MAIN ICON -->
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                         style="width:60px;height:60px;">

                        <i class="bi bi-receipt-cutoff fs-3"></i>

                    </div>

                    <!-- TITLE -->
                    <div>

                        <h4 class="modal-title fw-bold mb-0">
                            Create Purchase Order
                        </h4>

                        <small class="opacity-75">
                            Create and manage purchase order transaction
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
            <form class="needs-validation"
                  id="PurchaseOrderformNew"
                  novalidate>

                <!-- BODY -->
                <div class="modal-body p-4">

                    <!-- BRANCH -->
                    <div class="card border-0 bg-light rounded-4 mb-3">

                        <div class="card-body p-3">

                            <div class="row">

                                <div class="col-md-12">

                                    <label for="company_header_create"
                                           class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">

                                        <span class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                              style="width:34px;height:34px;padding-top:1px;">

                                            <i class="bi bi-building text-success"></i>

                                        </span>

                                        <span>
                                            Branch
                                        </span>

                                    </label>

                                    <select class="form-select form-select-lg rounded-3"
                                            required
                                            name="company_header"
                                            id="company_header_create">

                                        @foreach ($teves_branch as $teves_branch_cols)

                                            <option value="{{$teves_branch_cols->branch_id}}">
                                                {{$teves_branch_cols->branch_code}}
                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- PURCHASE DATE -->
                    <div class="card border-0 bg-light rounded-4 mb-3">

                        <div class="card-body p-3">

                            <div class="row">

                                <div class="col-md-12">

                                    <label for="purchase_order_date_create"
                                           class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">

                                        <span class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                              style="width:34px;height:34px;padding-top:1px;">

                                            <i class="bi bi-calendar-check text-primary"></i>

                                        </span>

                                        <span>
                                            Purchase Order Date
                                        </span>

                                    </label>

                                    <input type="date"
                                           class="form-control form-control-lg rounded-3"
                                           id="purchase_order_date_create"
                                           name="purchase_order_date"
                                           value="<?=date('Y-m-d');?>">

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- SUPPLIER -->
                    <div class="card border-0 bg-light rounded-4 mb-3">

                        <div class="card-body p-3">

                            <div class="row">

                                <div class="col-md-12">

                                    <label for="supplier_idx_create"
                                           class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">

                                        <span class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                              style="width:34px;height:34px;padding-top:1px;">

                                            <i class="bi bi-truck text-info"></i>

                                        </span>

                                        <span>
                                            Supplier's Name
                                        </span>

                                    </label>

                                    <input class="form-control form-control-lg rounded-3"
                                           list="supplier_name_create"
                                           name="supplier_name"
                                           id="supplier_idx_create"
                                           required
                                           autocomplete="off"
                                           onChange="SupplierInfo()"
                                           placeholder="Select Supplier">

                                    <datalist id="supplier_name_create">

                                        @foreach ($supplier_data as $supplier_data_cols)

                                            <option label="{{$supplier_data_cols->supplier_name}}"
                                                    data-id="{{$supplier_data_cols->supplier_id}}"
                                                    value="{{$supplier_data_cols->supplier_name}}">
                                            </option>

                                        @endforeach

                                    </datalist>

                                    <span class="valid-feedback"
                                          id="supplier_idxError_create">
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- FINANCIAL SETTINGS -->
                    <div class="card border-0 bg-light rounded-4">

                        <div class="card-body p-3">

                            <div class="row g-3">

                                <!-- NET VALUE -->
                                <div class="col-md-4">

                                    <label for="purchase_order_net_percentage_create"
                                           class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">

                                        <span class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                              style="width:34px;height:34px;padding-top:1px;">

                                            <i class="bi bi-cash-stack text-warning"></i>

                                        </span>

                                        <span>
                                            Net Value
                                        </span>

                                    </label>

                                    <input type="number"
                                           class="form-control form-control-lg rounded-3"
                                           id="purchase_order_net_percentage_create"
                                           name="purchase_order_net_percentage"
                                           placeholder="0.00">

                                </div>

                                <!-- SALES INVOICE -->
                                <div class="col-md-4">

                                    <label for="purchase_order_invoice_create"
                                           class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">

                                        <span class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                              style="width:34px;height:34px;padding-top:1px;">

                                            <i class="bi bi-receipt text-primary"></i>

                                        </span>

                                        <span>
                                            With Sales Invoice?
                                        </span>

                                    </label>

                                    <select class="form-select form-select-lg rounded-3"
                                            required
                                            name="purchase_order_invoice"
                                            id="purchase_order_invoice_create"
                                            onchange="check_withholding_tax()">

                                        <option value="1" selected>
                                            Yes
                                        </option>

                                        <option value="0">
                                            No
                                        </option>

                                    </select>

                                </div>

                                <!-- LESS VALUE -->
                                <div class="col-md-4">

                                    <label for="purchase_order_less_percentage_create"
                                           class="form-label fw-semibold d-flex align-items-center gap-2 mb-2">

                                        <span class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                              style="width:34px;height:34px;padding-top:1px;">

                                            <i class="bi bi-percent text-danger"></i>

                                        </span>

                                        <span>
                                            Less Value
                                        </span>

                                    </label>

                                    <input type="number"
                                           class="form-control form-control-lg rounded-3"
                                           id="purchase_order_less_percentage_create"
                                           name="purchase_order_less_percentage"
                                           placeholder="0.00">

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer border-0 px-4 pb-4">

                    <!-- LOADING -->
                    <div id="loading_data_create"
                         style="display:none;">

                        <div class="spinner-border spinner-border-sm text-success"
                             role="status">

                            <span class="visually-hidden">
                                Loading...
                            </span>

                        </div>

                    </div>

                    <!-- RESET -->
                    <button type="reset"
                            class="btn btn-light btn-sm rounded-3 px-3">

                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        Reset

                    </button>

                    <!-- SUBMIT -->
                    <button type="submit"
                            class="btn btn-success btn-sm rounded-3 shadow-sm px-3"
                            id="save-purchase-order">

                        <i class="bi bi-save-fill me-1"></i>
                        Save Purchase Order

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>