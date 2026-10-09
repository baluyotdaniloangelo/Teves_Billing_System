<!--==================================================
    MISCELLANEOUS SALES MODAL
==================================================-->

<div class="modal fade"
     id="miscellaneous_sales_Modal"
     tabindex="-1"
     aria-labelledby="miscellaneous_sales_ModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">


            <!-- ========================================== -->
            <!-- MODAL HEADER -->
            <!-- ========================================== -->

            <div class="modal-header border-0 px-4 py-2"
                 style="background: linear-gradient(135deg, #198754, #157347);">

                <div class="d-flex align-items-center text-white">

                    <div class="bg-white bg-opacity-25 rounded-3
                                d-flex align-items-center justify-content-center me-2"
                         style="width:40px;height:40px;">

                        <i class="bi bi-receipt fs-5"></i>

                    </div>

                    <div>

                        <h6 class="modal-title fw-bold mb-0"
                            id="miscellaneous_sales_ModalLabel">

                            Miscellaneous Sales

                        </h6>

                        <small class="text-white-50">

                            Add miscellaneous sales transaction

                        </small>

                    </div>

                </div>


                <button type="button"
                        class="btn btn-danger btn-sm rounded-circle"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        style="width:32px;height:32px;">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>


            <!-- ========================================== -->
            <!-- MODAL BODY -->
            <!-- ========================================== -->

            <div class="modal-body px-4 py-3">

                <form id="miscellaneous_sales_form"
                      class="needs-validation"
                      novalidate>


                    <!-- ========================================== -->
                    <!-- MISCELLANEOUS TYPE -->
                    <!-- ========================================== -->

                    <div class="mb-3">

                        <label for="miscellaneous_items_type"
                               class="form-label fw-semibold small mb-1">

                            <i class="bi bi-list-check me-1 text-success"></i>
                            Miscellaneous Type

                        </label>

                        <select class="form-select form-select-md rounded-3"
                                name="miscellaneous_items_type"
                                id="miscellaneous_items_type"
                                onchange="input_settings_create_miscellaneous_sales()"
                                required>

                            <option value="">
                                Please Select
                            </option>

                            <option value="SALES_CREDIT">
                                SALES ORDER - CREDIT SALES
                            </option>

                            <option value="DISCOUNTS">
                                DISCOUNTS
                            </option>

                            <option value="OTHERS">
                                OTHERS
                            </option>

                            <option value="CASHOUT">
                                CASH OUT
                            </option>

                            <option value="CASH_ADVANCE">
                                CASH ADVANCE
                            </option>

                        </select>

                        <div class="invalid-feedback"
                             id="miscellaneous_items_typeError">
                            Please select a miscellaneous type.
                        </div>

                    </div>


                    <!-- ========================================== -->
                    <!-- ACCOUNT NAME -->
                    <!-- ========================================== -->

                    <div class="mb-3">

                        <label for="sold_to_client_id"
                               class="form-label fw-semibold small mb-1">

                            <i class="bi bi-person-vcard me-1 text-success"></i>
                            Account Name

                        </label>

                        <input type="text"
                               class="form-control form-control-md rounded-3"
                               list="sold_to_client_name_list"
                               name="sold_to_client_name"
                               id="sold_to_client_id"
                               placeholder="Select or enter account name"
                               autocomplete="off"
                               onchange="load_so_reference_no(0)">


                        <datalist id="sold_to_client_name_list">

                            @foreach ($client_data as $client_data_cols)

                                <option
                                    label="{{ $client_data_cols->client_name }}"
                                    data-id="{{ $client_data_cols->client_id }}"
                                    value="{{ $client_data_cols->client_name }}">
                                </option>

                            @endforeach

                        </datalist>


                        <div class="invalid-feedback"
                             id="sold_to_client_idError">

                            Please select an account.

                        </div>

                    </div>


                    <!-- ========================================== -->
                    <!-- REFERENCE NO + TIME -->
                    <!-- ========================================== -->

                    <div class="row g-2 mb-3">


                        <!-- REFERENCE NUMBER -->
                        <div class="col-sm-6">

                            <label for="reference_no_miscellaneous_sales"
                                   class="form-label fw-semibold small mb-1">

                                <i class="bi bi-hash me-1 text-success"></i>
                                Reference No.

                            </label>

                            <input type="text"
                                   class="form-control form-control-md rounded-3"
                                   list="so_list_reference"
                                   name="reference_no_miscellaneous_sales"
                                   id="reference_no_miscellaneous_sales"
                                   placeholder="Reference Number"
                                   autocomplete="off">

                            <div class="invalid-feedback"
                                 id="reference_no_miscellaneous_salesError">
                                Please enter the reference number.
                            </div>
							<datalist id="so_list_reference">
							</datalist>
                        </div>


                        <!-- TIME -->
                        <div class="col-sm-6">

                            <label for="order_time_miscellaneous_sales"
                                   class="form-label fw-semibold small mb-1">

                                <i class="bi bi-clock me-1 text-success"></i>
                                Time

                            </label>

                            <input type="time"
                                   class="form-control form-control-md rounded-3"
                                   name="order_time_miscellaneous_sales"
                                   id="order_time_miscellaneous_sales"
                                   autocomplete="off">

                            <div class="invalid-feedback"
                                 id="order_time_miscellaneous_salesError">
                                Please enter the time.
                            </div>

                        </div>

                    </div>


                    <!-- ========================================== -->
                    <!-- PRODUCT / DESCRIPTION -->
                    <!-- ========================================== -->

                    <div class="mb-3">

                        <label for="product_idx_miscellaneous_sales"
                               class="form-label fw-semibold small mb-1">

                            <i class="bi bi-box-seam me-1 text-success"></i>
                            Product / Description

                        </label>

                        <input type="text"
                               class="form-control form-control-md rounded-3"
                               list="product_list_miscellaneous_sales"
                               name="product_name_miscellaneous_sales"
                               id="product_idx_miscellaneous_sales"
                               placeholder="Select or enter product / description"
                               autocomplete="off"
                               onchange="TotalAmount_miscellaneous_sales()"
                               required>
						<datalist id="product_list_miscellaneous_sales">
						</datalist>
                        <div class="invalid-feedback"
                             id="product_idx_miscellaneous_salesError">

                            Please enter the product or description.

                        </div>

                    </div>


                    <!-- ========================================== -->
                    <!-- QUANTITY + UNIT PRICE -->
                    <!-- ========================================== -->

                    <div class="row g-2 mb-3">


                        <!-- QUANTITY -->
                        <div class="col-md-6">

                            <label for="order_quantity_miscellaneous_sales"
                                   class="form-label fw-semibold small mb-1">

                                <i class="bi bi-boxes me-1 text-success"></i>

                                <span id="quantity_label_miscellaneous_sales">
                                    Quantity
                                </span>

                            </label>

                            <input type="number"
                                   class="form-control form-control-md rounded-3"
                                   name="order_quantity_miscellaneous_sales"
                                   id="order_quantity_miscellaneous_sales"
                                   min="0.01"
                                   step="0.01"
                                   placeholder="0.00"
                                   onchange="TotalAmount_miscellaneous_sales()"
                                   required>

                            <div class="invalid-feedback"
                                 id="order_quantity_miscellaneous_salesError">

                                Please enter the quantity.

                            </div>

                        </div>


                        <!-- UNIT PRICE -->
                        <div class="col-md-6">

                            <label for="product_manual_price_miscellaneous_sales"
                                   class="form-label fw-semibold small mb-1">

                                <i class="bi bi-tag me-1 text-success"></i>

                                <span id="manual_price_label_miscellaneous_sales">
                                    Unit Price
                                </span>

                            </label>

                            <div class="input-group input-group-md">

                                <span class="input-group-text">
                                    ₱
                                </span>

                                <input type="number"
                                       class="form-control form-control-md"
                                       name="product_manual_price_miscellaneous_sales"
                                       id="product_manual_price_miscellaneous_sales"
                                       min="0"
                                       step="0.01"
                                       placeholder="0.00"
                                       onchange="TotalAmount_miscellaneous_sales()">

                            </div>

                            <div class="invalid-feedback"
                                 id="product_manual_price_miscellaneous_salesError">

                                Please enter the unit price.

                            </div>

                        </div>

                    </div>


			<!-- ========================================== -->
			<!-- PRICE SUMMARY -->
			<!-- ========================================== -->

			<div class="row g-2 mb-3">

				<!-- PUMP PRICE -->
				<div class="col-md-4">

					<div class="card border-0 bg-light rounded-3 h-100">

						<div class="card-body py-2 px-3">

							<div class="text-muted small fw-semibold">
								<i class="bi bi-fuel-pump me-1"></i>
								PUMP PRICE
							</div>

							<div class="fw-semibold">
								₱
								<span id="pump_price_miscellaneous_sales">
									0.00
								</span>
							</div>

						</div>

					</div>

				</div>

				<!-- DISCOUNTED PRICE -->
				<div class="col-md-4">

					<div class="card border-0 bg-light rounded-3 h-100">

						<div class="card-body py-2 px-3">

							<div class="text-muted small fw-semibold">
								<i class="bi bi-tag me-1"></i>
								DISCOUNTED PRICE
							</div>

							<div class="fw-semibold">
								₱
								<span id="discounted_price_miscellaneous_sales">
									0.00
								</span>
							</div>

						</div>

					</div>

				</div>

				<!-- TOTAL AMOUNT -->
				<div class="col-md-4">

					<div class="card border-0 bg-light rounded-3 h-100">

						<div class="card-body py-2 px-3">

							<div class="text-muted small fw-semibold">
								<i class="bi bi-cash-stack me-1"></i>
								TOTAL AMOUNT
							</div>

							<div class="text-success fw-bold">
								₱
								<span id="TotalAmount_miscellaneous_sales">
									0.00
								</span>
							</div>

						</div>

					</div>

				</div>

			</div>


            <!-- ========================================== -->
            <!-- MODAL FOOTER -->
            <!-- ========================================== -->

            <div class="modal-footer border-0 bg-light px-4 py-2">

                <button type="reset"
                        form="miscellaneous_sales_form"
                        class="btn btn-light border btn-sm rounded-3 px-3"
                        id="clear-miscellaneous_sales-save">

                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                    Reset

                </button>


                <button type="submit"
                        form="miscellaneous_sales_form"
                        class="btn btn-success btn-sm rounded-3 px-3 shadow-sm"
                        id="save-miscellaneous_sales">

                    <i class="bi bi-save-fill me-1"></i>
                    Save

                </button>

            </div>

        </div>

    </div>

</div>

</div><!--NILAGAY KO KASE MAY TAG NA HINDI NAKA CLOSE, HINDI KO ALAM KUNG SAAN-->


<!--==================================================
DELETE MISCELLANEOUS SALES MODAL
SALES CREDIT / DISCOUNT / OTHERS
==================================================-->

<div class="modal fade"
     id="CRPH3DeleteModal"
     tabindex="-1"
     aria-labelledby="CRPH3DeleteModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- HEADER -->
            <div class="modal-header border-0 px-4 py-3"
                 style="background: linear-gradient(135deg, #dc3545, #b02a37);">

                <div class="d-flex align-items-center text-white">

                    <div class="bg-white bg-opacity-25 rounded-3
                                d-flex align-items-center justify-content-center me-3"
                         style="width:44px;height:44px;">

                        <i class="bi bi-trash3-fill fs-5"></i>

                    </div>

                    <div>
                        <h6 class="modal-title fw-bold mb-0"
                            id="CRPH3DeleteModalLabel">
                            Delete Transaction
                        </h6>

                        <small class="text-white-50">
                            Please confirm this deletion.
                        </small>
                    </div>

                </div>

                <button type="button"
                        class="btn btn-light btn-sm rounded-circle"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        style="width:32px;height:32px;">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>


            <!-- BODY -->
            <div class="modal-body px-4 py-3">

                <div class="alert alert-warning border-0 rounded-3
                            d-flex align-items-start mb-3">

                    <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>

                    <div class="small">

                        Are you sure you want to delete this transaction?

                        <strong>
                            This action cannot be undone.
                        </strong>

                        <div class="mt-1"
                             id="CRPH3DeleteModalDescription">
                            Please confirm this deletion.
                        </div>

                    </div>

                </div>


                <!-- DETAILS -->
                <div class="card border-0 bg-light rounded-3">

                    <div class="card-body py-2 px-3">

                        <div class="row g-2 small">

                            <!-- REFERENCE -->
                            <div class="col-5 text-muted">
                                Reference No.
                            </div>

                            <div class="col-7 fw-semibold text-end text-break"
                                 id="delete_reference_no_PH3">
                                -
                            </div>


                            <!-- PRODUCT / DESCRIPTION -->
                            <div class="col-5 text-muted">
                                Product / Description
                            </div>

                            <div class="col-7 fw-semibold text-end text-break"
                                 id="delete_product_idx_PH3">
                                -
                            </div>


                            <!-- QUANTITY -->
                            <div class="col-5 text-muted">
                                Quantity
                            </div>

                            <div class="col-7 fw-semibold text-end"
                                 id="delete_order_quantity_PH3">
                                0.00
                            </div>


                            <!-- UNIT PRICE -->
                            <div class="col-5 text-muted">
                                Unit Price / Amount
                            </div>

                            <div class="col-7 fw-semibold text-end">
                                ₱
                                <span id="delete_product_manual_price_PH3">
                                    0.00
                                </span>
                            </div>


                            <!-- DISCOUNTED PRICE -->
                            <div class="col-5 text-muted"
                                 id="delete_discounted_price_row">
                                Discounted Price
                            </div>

                            <div class="col-7 fw-semibold text-end"
                                 id="delete_discounted_price_row">

                                ₱
                                <span id="delete_discounted_price_PH3">
                                    0.00
                                </span>

                            </div>


                            <div class="col-12">
                                <hr class="my-2">
                            </div>


                            <!-- TOTAL -->
                            <div class="col-5 fw-semibold">
                                Total Amount
                            </div>

                            <div class="col-7 text-success fw-bold text-end">

                                ₱
                                <span id="delete_TotalAmount_PH3">
                                    0.00
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- FOOTER -->
            <div class="modal-footer border-0 bg-light px-4 py-2">

                <button type="button"
                        class="btn btn-light border btn-sm rounded-3 px-3"
                        data-bs-dismiss="modal">

                    <i class="bi bi-x-circle me-1"></i>
                    Cancel

                </button>


                <button type="button"
                        class="btn btn-danger btn-sm rounded-3 px-3"
                        id="deleteCRPH3Confirmed">

                    <i class="bi bi-trash3-fill me-2"></i>
                    Confirm Delete

                </button>

            </div>

        </div>

    </div>

</div>