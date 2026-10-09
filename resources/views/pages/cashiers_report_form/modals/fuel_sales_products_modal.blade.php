<!--==================================================
CASHIER'S REPORT
PH1 - FUEL SALES
ADD / EDIT MODAL
==================================================-->

<div class="modal fade"
     id="CashierReportFuelSalesModal"
     tabindex="-1"
     aria-labelledby="CashierReportFuelSalesModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">


            <!--==================================================
            MODAL HEADER
            ==================================================-->

            <div class="modal-header border-0 px-3 py-2"
                 style="background: linear-gradient(135deg, #198754, #157347);">

                <div class="d-flex align-items-center text-white">

                    <!-- Fuel Icon -->
                    <div class="bg-white bg-opacity-25 rounded-3
                                d-flex align-items-center
                                justify-content-center
                                me-3"
                         style="width:48px;height:48px;">

                        <i class="bi bi-fuel-pump-fill fs-4"></i>

                    </div>


                    <!-- Title -->
                    <div>

                        <h5 class="modal-title fw-bold mb-0"
                            id="CashierReportFuelSalesModalLabel">

                            Add Fuel Sales

                        </h5>

                        <small class="text-white-50">

                            Enter fuel dispenser sales information

                        </small>

                    </div>

                </div>


                <!-- Close Button -->
                <button type="button"
                        class="btn btn-danger btn-sm rounded-circle"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        style="width:36px;height:36px;">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>


            <!--==================================================
            MODAL BODY
            ==================================================-->

            <div class="modal-body p-3">

                <form class="needs-validation"
                      id="CashierReportFuelSalesForm"
                      novalidate>

                    <section class="rounded-4 border bg-white p-3 mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-success bg-opacity-10 text-success me-2" style="width:38px;height:38px"><i class="bi bi-fuel-pump-fill"></i></span>
                            <div><h6 class="fw-bold mb-0">Product Details</h6><small class="text-muted">Select the fuel product, tank, and dispenser pump.</small></div>
                        </div>

                    <!--==================================================
                    FUEL PRODUCT
                    ==================================================-->

                    <div class="mb-3">

                        <label for="fuel_product"
                               class="form-label fw-semibold">

                            <i class="bi bi-fuel-pump me-2 text-success"></i>
                            Fuel Product

                        </label>


                        <input type="text"
                               class="form-control rounded-3"
                               list="fuelProductList"
                               name="fuel_product"
                               id="fuel_product"
                               required
                               disabled
                               autocomplete="off"
                               placeholder="Loading fuel products...">


                        <!-- Fuel Product List -->
                        <datalist id="fuelProductList">
                        </datalist>


                        <div class="invalid-feedback"
                             id="fuel_productError">

                            Please select a fuel product.

                        </div>

                    </div>


                    <!--==================================================
                    TANK AND PUMP
                    ==================================================-->

                    <div class="row g-3 mb-3">


                        <!--==================================================
                        TANK
                        ==================================================-->

                        <div class="col-md-6">

                            <label for="fuel_tank"
                                   class="form-label fw-semibold">

                                <i class="bi bi-database-fill me-2 text-success"></i>
                                Tank

                            </label>


                            <input type="text"
                                   class="form-control rounded-3"
                                   list="fuelTankList"
                                   name="fuel_tank"
                                   id="fuel_tank"
                                   required
                                   autocomplete="off"
                                   placeholder="Select tank">


                            <!-- Tank List -->
                            <datalist id="fuelTankList">
                                <!--
                                    Tank options are loaded dynamically
                                    by LoadProductTank()
                                -->
                            </datalist>


                            <div class="invalid-feedback"
                                 id="fuel_tankError">

                                Please select a tank.

                            </div>

                        </div>


                        <!--==================================================
                        PUMP
                        ==================================================-->

                        <div class="col-md-6">

                            <label for="fuel_pump"
                                   class="form-label fw-semibold">

                                <i class="bi bi-fuel-pump me-2 text-success"></i>
                                Pump

                            </label>


                            <input type="text"
                                   class="form-control rounded-3"
                                   list="fuelPumpList"
                                   name="fuel_pump"
                                   id="fuel_pump"
                                   required
                                   autocomplete="off"
                                   placeholder="Select pump">


                            <!-- Pump List -->
                            <datalist id="fuelPumpList">
                                <!--
                                    Pump options are loaded dynamically
                                    by LoadProductPump()
                                -->
                            </datalist>


                            <div class="invalid-feedback"
                                 id="fuel_pumpError">

                                Please select a pump.

                            </div>

                        </div>

                    </div>

                    </section>

                    <!--==================================================
                    METER READINGS
                    ==================================================-->

                    <section class="rounded-4 border bg-white p-3 mb-4">

                        <div class="card-body p-0">


                            <!-- Section Header -->
                            <div class="d-flex align-items-center mb-3">

                                <div class="bg-success bg-opacity-10
                                            rounded-3
                                            d-flex
                                            align-items-center
                                            justify-content-center
                                            me-2"
                                     style="width:38px;height:38px;">

                                    <i class="bi bi-speedometer2 text-success"></i>

                                </div>


                                <div>

                                    <div class="fw-semibold">
                                        Readings
                                    </div>

                                    <small class="text-muted">
                                        Enter the fuel dispenser readings
                                    </small>

                                </div>

                            </div>


                            <div class="row g-3">


                                <!--==================================================
                                BEGINNING READING
                                ==================================================-->

                                <div class="col-md-4">

                                    <label for="fuel_beginning_reading"
                                           class="form-label fw-semibold">

                                        Beginning

                                    </label>


                                    <input type="number"
                                           class="form-control rounded-3"
                                           name="fuel_beginning_reading"
                                           id="fuel_beginning_reading"
                                           min="0"
                                           step="0.01"
                                           required
                                           placeholder="0.00">


                                    <div class="invalid-feedback"
                                         id="fuel_beginning_readingError">

                                        Please enter beginning reading.

                                    </div>

                                </div>


                                <!--==================================================
                                CLOSING READING
                                ==================================================-->

                                <div class="col-md-4">

                                    <label for="fuel_closing_reading"
                                           class="form-label fw-semibold">

                                        Closing

                                    </label>


                                    <input type="number"
                                           class="form-control rounded-3"
                                           name="fuel_closing_reading"
                                           id="fuel_closing_reading"
                                           min="0"
                                           step="0.01"
                                           required
                                           placeholder="0.00">


                                    <div class="invalid-feedback"
                                         id="fuel_closing_readingError">

                                        Please enter closing reading.

                                    </div>

                                </div>


                                <!--==================================================
                                CALIBRATION
                                ==================================================-->

                                <div class="col-md-4">

                                    <label for="fuel_calibration"
                                           class="form-label fw-semibold">

                                        Calibration

                                    </label>


                                    <input type="number"
                                           class="form-control rounded-3"
                                           name="fuel_calibration"
                                           id="fuel_calibration"
                                           min="0"
                                           step="0.01"
                                           placeholder="0.00">


                                    <div class="invalid-feedback"
                                         id="fuel_calibrationError">

                                        Please enter calibration.

                                    </div>

                                </div>

                            </div>

                        </div>

                    </section>

                    <section class="rounded-4 border bg-white p-3 mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary bg-opacity-10 text-primary me-2" style="width:38px;height:38px"><i class="bi bi-tag-fill"></i></span>
                            <div><h6 class="fw-bold mb-0">Pump Price</h6><small class="text-muted">Enter the current price per liter.</small></div>
                        </div>
                        <label for="fuel_pump_price" class="form-label fw-semibold">Price per Liter</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" class="form-control rounded-end-3" name="fuel_pump_price" id="fuel_pump_price" min="0" step="0.01" placeholder="0.00">
                        </div>
                        <div class="invalid-feedback" id="fuel_pump_priceError">Please enter the pump price.</div>
                    </section>

                    <section class="rounded-4 border bg-white p-3">
                        <div class="d-flex align-items-center mb-3">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-success bg-opacity-10 text-success me-2" style="width:38px;height:38px"><i class="bi bi-receipt"></i></span>
                            <div><h6 class="fw-bold mb-0">Sales Summary</h6><small class="text-muted">Calculated from quantity and pump price.</small></div>
                        </div>
                        <div class="card border-0 bg-success bg-opacity-10 rounded-4">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted small fw-semibold">TOTAL FUEL SALES</div>
                                        <div class="text-muted small">Quantity × Pump Price</div>
                                    </div>
                                    <div class="text-end text-success fw-bold fs-4">₱ <span id="FuelSalesTotalAmount">0.00</span></div>
                                </div>
                            </div>
                        </div>
                    </section>

                </form>

            </div>


            <!--==================================================
            MODAL FOOTER
            ==================================================-->

            <div class="modal-footer border-0 bg-light px-3 py-2">


                <!-- Reset -->
                <button type="reset"
                        class="btn btn-light border rounded-3 px-4"
                        id="clear-fuel-sales">

                    <i class="bi bi-arrow-counterclockwise me-2"></i>
                    Reset

                </button>

				<input type="hidden"
				   id="fuel_sales_id"
				   name="fuel_sales_id"
				   value="0">
	   
                <!-- Save / Update -->
                <button type="submit"
                        form="CashierReportFuelSalesForm"
                        class="btn btn-success rounded-3 px-4 shadow-sm"
                        id="save-fuel-sales">

                    <i class="bi bi-save-fill me-2"></i>
                    Save Fuel Sales

                </button>


            </div>

        </div>

    </div>

</div>

<!-- ========================================== -->
<!-- DELETE FUEL SALES MODAL -->
<!-- ========================================== -->

<div class="modal fade"
     id="CashierReportFuelSalesDeleteModal"
     tabindex="-1"
     aria-labelledby="CashierReportFuelSalesDeleteModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">


            <!-- ====================================== -->
            <!-- HEADER -->
            <!-- ====================================== -->

            <div class="modal-header border-0 px-4 py-3"
                 style="background: linear-gradient(135deg, #dc3545, #b02a37);">

                <div class="d-flex align-items-center text-white">

                    <div class="bg-white bg-opacity-25
                                rounded-3
                                d-flex
                                align-items-center
                                justify-content-center
                                me-3"
                         style="width:44px;height:44px;">

                        <i class="bi bi-trash3-fill fs-5"></i>

                    </div>

                    <div>

                        <h6 class="modal-title fw-bold mb-0"
                            id="CashierReportFuelSalesDeleteModalLabel">

                            Delete Fuel Sales

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


            <!-- ====================================== -->
            <!-- BODY -->
            <!-- ====================================== -->

            <div class="modal-body px-4 py-3">


                <div class="alert alert-warning
                            border-0
                            rounded-3
                            d-flex
                            align-items-start
                            mb-3">

                    <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>

                    <div class="small">

                        Are you sure you want to delete this
                        fuel sales transaction?

                        <strong>
                            This action cannot be undone.
                        </strong>

                    </div>

                </div>


                <!-- ================================== -->
                <!-- DETAILS -->
                <!-- ================================== -->

                <div class="card border-0 bg-light rounded-3">

                    <div class="card-body py-2 px-3">


                        <!-- ORDER DATE

                        <div class="d-flex justify-content-between
                                    border-bottom py-2">

                            <span class="text-muted small">
                                Order Date
                            </span>

                            <span class="fw-semibold small"
                                  id="fuel_sales_delete_order_date">
                                -
                            </span>

                        </div>
						-->

                        <!-- PRODUCT -->

                        <div class="d-flex justify-content-between
                                    border-bottom py-2">

                            <span class="text-muted small">
                                Product
                            </span>

                            <span class="fw-semibold small text-end"
                                  id="fuel_sales_delete_product">
                                -
                            </span>

                        </div>


                        <!-- BEGINNING READING -->

                        <div class="d-flex justify-content-between
                                    border-bottom py-2">

                            <span class="text-muted small">
                                Beginning Reading
                            </span>

                            <span class="fw-semibold small"
                                  id="fuel_sales_delete_beginning_reading">
                                0.00
                            </span>

                        </div>


                        <!-- CLOSING READING -->

                        <div class="d-flex justify-content-between
                                    border-bottom py-2">

                            <span class="text-muted small">
                                Closing Reading
                            </span>

                            <span class="fw-semibold small"
                                  id="fuel_sales_delete_closing_reading">
                                0.00
                            </span>

                        </div>


                        <!-- CALIBRATION -->

                        <div class="d-flex justify-content-between
                                    border-bottom py-2">

                            <span class="text-muted small">
                                Calibration
                            </span>

                            <span class="fw-semibold small"
                                  id="fuel_sales_delete_calibration">
                                0.00
                            </span>

                        </div>


                        <!-- QUANTITY -->

                        <div class="d-flex justify-content-between
                                    border-bottom py-2">

                            <span class="text-muted small">
                                Quantity
                            </span>

                            <span class="fw-semibold small"
                                  id="fuel_sales_delete_quantity">
                                0.00
                            </span>

                        </div>


                        <!-- PUMP PRICE -->

                        <div class="d-flex justify-content-between
                                    border-bottom py-2">

                            <span class="text-muted small">
                                Pump Price
                            </span>

                            <span class="fw-semibold small">
                                ₱
                                <span id="fuel_sales_delete_pump_price">
                                    0.00
                                </span>
                            </span>

                        </div>


                        <!-- TOTAL AMOUNT -->

                        <div class="d-flex justify-content-between
                                    pt-2">

                            <span class="text-muted small fw-semibold">
                                Total Amount
                            </span>

                            <span class="text-danger fw-bold">

                                ₱
                                <span id="fuel_sales_delete_total_amount">
                                    0.00
                                </span>

                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ====================================== -->
            <!-- FOOTER -->
            <!-- ====================================== -->

            <div class="modal-footer border-0 bg-light px-4 py-2">

                <button type="button"
                        class="btn btn-light border btn-sm rounded-3 px-3"
                        data-bs-dismiss="modal">

                    <i class="bi bi-x-circle me-1"></i>

                    Cancel

                </button>


                <button type="button"
                        class="btn btn-danger btn-sm rounded-3 px-3"
                        id="deleteFuelSalesConfirmed">

                    <i class="bi bi-trash3-fill me-2"></i>

                    Delete Fuel Sales

                </button>

            </div>

        </div>

    </div>

</div>
