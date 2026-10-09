<!--==================================================
CASHIER'S REPORT
LUBRICANTS & CAR CARE PRODUCTS MODAL
==================================================-->

<div class="modal fade"
     id="CashierReportLubeCarCareModal"
     tabindex="-1"
     aria-labelledby="CashierReportLubeCarCareModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- MODAL HEADER -->
            <div class="modal-header border-0 px-4 py-3"
                 style="background: linear-gradient(135deg, #198754, #157347);">

                <div class="d-flex align-items-center text-white">

                    <div class="bg-white bg-opacity-25 rounded-3
                                d-flex align-items-center justify-content-center me-3"
                         style="width:48px;height:48px;">

                        <i class="bi bi-droplet-half fs-4"></i>

                    </div>

                    <div>
                        <h5 class="modal-title fw-bold mb-0"
                            id="CashierReportLubeCarCareModalLabel">

                            Lubricants & Car Care

                        </h5>

                        <small class="text-white-50">
                            Add product sales
                        </small>
                    </div>

                </div>

                <button type="button"
                        class="btn btn-danger btn-sm rounded-circle"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        style="width:36px;height:36px;">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>


            <!-- MODAL BODY -->
            <div class="modal-body p-4">

                <form id="CashierReportLubeCarCareForm"
                      class="needs-validation"
                      novalidate>


                    <!-- PRODUCT -->
                    <div class="mb-4">

                        <label for="lube_car_care_product"
                               class="form-label fw-semibold">

                            <i class="bi bi-box-seam me-2 text-success"></i>
                            Product

                        </label>

                        <input type="text"
                               class="form-control form-control-lg rounded-3"
                               list="lubeCarCareProductList"
                               name="lube_car_care_product"
                               id="lube_car_care_product"
                               placeholder="Select or enter product"
                               autocomplete="off"
                               required>

                        <datalist id="lubeCarCareProductList">

                            @foreach ($product_data as $product_data_cols)

                                <option
                                    label="&#8369; {{ number_format($product_data_cols->product_price, 2) }}"
                                    data-id="{{ $product_data_cols->product_id }}"
                                    data-price="{{ $product_data_cols->product_price }}"
                                    value="{{ $product_data_cols->product_name }}">

                            @endforeach

                        </datalist>

                        <div class="invalid-feedback"
                             id="lube_car_care_productError">

                            Please select a product.

                        </div>

                    </div>


                    <!-- QUANTITY + UNIT PRICE -->
                    <div class="row g-3 mb-4">

                        <!-- QUANTITY -->
                        <div class="col-md-6">

                            <label for="lube_car_care_quantity"
                                   class="form-label fw-semibold">

                                <i class="bi bi-boxes me-2 text-success"></i>
                                Quantity

                            </label>

                            <input type="number"
                                   class="form-control form-control-lg rounded-3"
                                   name="lube_car_care_quantity"
                                   id="lube_car_care_quantity"
                                   min="0.01"
                                   step="0.01"
                                   placeholder="0.00"
                                   required>

                            <div class="invalid-feedback"
                                 id="lube_car_care_quantityError">

                                Please enter the quantity.

                            </div>

                        </div>


                        <!-- UNIT PRICE -->
                        <div class="col-md-6">

                            <label for="lube_car_care_unit_price"
                                   class="form-label fw-semibold">

                                <i class="bi bi-tag me-2 text-success"></i>
                                Unit Price

                            </label>

                            <div class="input-group input-group-lg">

                                <span class="input-group-text rounded-start-3">
                                    ₱
                                </span>

                                <input type="number"
                                       class="form-control rounded-end-3"
                                       name="lube_car_care_unit_price"
                                       id="lube_car_care_unit_price"
                                       min="0"
                                       step="0.01"
                                       placeholder="0.00">

                            </div>

                            <div class="invalid-feedback"
                                 id="lube_car_care_unit_priceError">

                                Please enter the unit price.

                            </div>

                        </div>

                    </div>


                    <!-- TOTAL AMOUNT -->
                    <div class="card border-0 bg-light rounded-4 mb-2">

                        <div class="card-body p-4">

                            <div class="d-flex justify-content-between
                                        align-items-center">

                                <div>

                                    <div class="text-muted small fw-semibold">
                                        TOTAL AMOUNT
                                    </div>

                                    <div class="text-muted small">
                                        Quantity × Unit Price
                                    </div>

                                </div>

                                <div class="text-end">

                                    <div class="text-success fw-bold fs-3">

                                        ₱ <span id="LubeCarCareTotalAmount">
                                            0.00
                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </form>

            </div>


            <!-- MODAL FOOTER -->
            <div class="modal-footer border-0 bg-light px-4 py-3">

                <button type="reset"
                        class="btn btn-light border rounded-3 px-4"
                        id="clear-lube-car-care">

                    <i class="bi bi-arrow-counterclockwise me-2"></i>
                    Reset

                </button>
				<input type="hidden"
					   id="lube_car_care_id"
					   name="lube_car_care_id"
					   value="0">
					   
                <button type="submit"
                        form="CashierReportLubeCarCareForm"
                        class="btn btn-success rounded-3 px-4 shadow-sm"
                        id="save-lube-car-care">

                    <i class="bi bi-save-fill me-2"></i>
                    Save Product

                </button>

            </div>

        </div>

    </div>

</div>


<!--==================================================
DELETE LUBRICANTS & CAR CARE PRODUCT MODAL
==================================================-->

<div class="modal fade"
     id="CashierReportLubeCarCareDeleteModal"
     tabindex="-1"
     aria-labelledby="CashierReportLubeCarCareDeleteModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">


            <!-- MODAL HEADER -->
            <div class="modal-header border-0 pb-0 px-4 pt-4">

                <div class="w-100 text-center">

                    <div class="bg-danger bg-opacity-10
                                rounded-circle
                                d-inline-flex
                                align-items-center
                                justify-content-center
                                mb-3"
                         style="width:75px;height:75px;">

                        <i class="bi bi-trash3-fill text-danger fs-1"></i>

                    </div>

                    <h4 class="fw-bold text-danger mb-1"
                        id="CashierReportLubeCarCareDeleteModalLabel">

                        Delete Product

                    </h4>

                    <p class="text-muted mb-0">

                        This action cannot be undone.

                    </p>

                </div>

            </div>


            <!-- MODAL BODY -->
            <div class="modal-body px-4 py-4">


                <!-- WARNING -->
                <div class="alert alert-danger border-0 rounded-4 mb-4">

                    <div class="d-flex align-items-start">

                        <i class="bi bi-exclamation-triangle-fill
                                  text-danger
                                  me-3
                                  fs-5"></i>

                        <div>

                            <div class="fw-semibold mb-1">

                                Are you sure you want to delete this product?

                            </div>

                            <small class="text-muted">

                                The selected Lubricants & Car Care product
                                will be removed from this Cashier's Report.

                            </small>

                        </div>

                    </div>

                </div>


                <!-- PRODUCT DETAILS -->
                <div class="card border-0 bg-light rounded-4">

                    <div class="card-body p-3">


                        <!-- PRODUCT -->
                        <div class="mb-3">

                            <div class="text-muted small fw-semibold mb-1">

                                <i class="bi bi-box-seam me-2"></i>
                                PRODUCT

                            </div>

                            <div class="fw-semibold"
                                 id="delete_lube_car_care_product">

                                -

                            </div>

                        </div>


                        <div class="row g-3">


                            <!-- QUANTITY -->
                            <div class="col-6">

                                <div class="text-muted small fw-semibold mb-1">

                                    <i class="bi bi-boxes me-2"></i>
                                    QUANTITY

                                </div>

                                <div class="fw-semibold"
                                     id="delete_lube_car_care_quantity">

                                    0

                                </div>

                            </div>


                            <!-- UNIT PRICE -->
                            <div class="col-6">

                                <div class="text-muted small fw-semibold mb-1">

                                    <i class="bi bi-tag me-2"></i>
                                    UNIT PRICE

                                </div>

                                <div class="fw-semibold">

                                    ₱ <span id="delete_lube_car_care_unit_price">
                                        0.00
                                    </span>

                                </div>

                            </div>


                        </div>


                        <!-- TOTAL -->
                        <div class="border-top mt-3 pt-3">

                            <div class="d-flex
                                        justify-content-between
                                        align-items-center">

                                <span class="text-muted fw-semibold">

                                    TOTAL AMOUNT

                                </span>

                                <span class="text-danger fw-bold fs-5">

                                    ₱ <span id="deleteLubeCarCareTotalAmount">
                                        0.00
                                    </span>

                                </span>

                            </div>

                        </div>


                    </div>

                </div>

            </div>


            <!-- MODAL FOOTER -->
            <div class="modal-footer border-0 px-4 pb-4">


                <button type="button"
                        class="btn btn-light border rounded-3 px-4"
                        data-bs-dismiss="modal">

                    <i class="bi bi-x-circle me-2"></i>
                    Cancel

                </button>


                <button type="button"
                        class="btn btn-danger rounded-3 shadow-sm px-4"
                        id="deleteLubeCarCareConfirmed"
                        value="">

                    <i class="bi bi-trash3-fill me-2"></i>
                    Delete Product

                </button>


            </div>

        </div>

    </div>

</div>