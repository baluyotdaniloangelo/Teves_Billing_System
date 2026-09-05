<!-- PRODUCT TAB -->
<div class="tab-pane fade show active"
     id="cashiers_report_fuel"
     role="tabpanel"
     aria-labelledby="cashiers_report_fuel">

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <!-- HEADER -->
<div class="card-header bg-white border-0 py-3 px-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap">

        <!-- LEFT -->
        <div>
			<!--
            <small class="text-muted text-uppercase fw-semibold">
                Purchase Order
            </small>
			
            <h5 class="fw-bold mb-0">
                <i class="bi bi-receipt-cutoff text-success me-2"></i>
                Control Number
            </h5>
			-->
        </div>

        <!-- RIGHT -->
        <div class="d-flex align-items-center gap-2">
			<!--
			<h5 class="fw-bold mb-0">
                <i class="bi bi-receipt-cutoff text-success me-2"></i>
                Control Number
            </h5>
			
            <span class="badge rounded-pill bg-success fs-6 px-4 py-2 shadow-sm"
                  id="control_no">
                
            </span>
			-->
            <button type="button"
                    class="btn btn-success rounded-3 shadow-sm "
                    data-bs-toggle="modal"
                    data-bs-target="#AddProductModal" 
					id="AddPurchaseOrderProductBTN">

                <i class="bi bi-plus-circle me-1"></i>
                Add
            </button>

        </div>

    </div>

</div>

        <!-- BODY -->

<!-- =========================================================
FUEL CASHIER REPORT TABS
========================================================= -->

<div class="card-body">

    <!-- Bordered Tabs -->

    <ul class="nav nav-tabs nav-tabs-bordered"
        id="borderedTab"
        role="tablist">


        <!-- =================================================
        FUEL SALES
        ================================================== -->

        <li class="nav-item"
            role="presentation">

            <button class="nav-link active"
                    id="ph1-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#bordered-ph1"
                    type="button"
                    role="tab"
                    aria-controls="bordered-ph1"
                    aria-selected="true">

                <i class="bi bi-fuel-pump me-1"></i>

                Fuel Sales

            </button>

        </li>


        <!-- =================================================
        OTHER SALES
        ================================================== -->

        <li class="nav-item"
            role="presentation">

            <button class="nav-link"
                    id="ph2-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#bordered-ph2"
                    type="button"
                    role="tab"
                    aria-controls="bordered-ph2"
                    aria-selected="false">

                <i class="bi bi-cart-check me-1"></i>

                Other Sales

            </button>

        </li>


        <!-- =================================================
        MISCELLANEOUS ITEMS
        ================================================== -->

        <li class="nav-item"
            role="presentation">

            <button class="nav-link"
                    id="ph3-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#bordered-ph3"
                    type="button"
                    role="tab"
                    aria-controls="bordered-ph3"
                    aria-selected="false">

                <i class="bi bi-box-seam me-1"></i>

                Miscellaneous Items

            </button>

        </li>


        <!-- =================================================
        DIPSTICK INVENTORY
        ================================================== -->

        <li class="nav-item"
            role="presentation">

            <button class="nav-link"
                    id="ph7-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#bordered-ph7"
                    type="button"
                    role="tab"
                    aria-controls="bordered-ph7"
                    aria-selected="false">

                <i class="bi bi-bar-chart-line me-1"></i>

                Dipstick Inventory

            </button>

        </li>

    </ul>


    <!-- =====================================================
    TAB CONTENT
    ====================================================== -->

    <div class="tab-content pt-2"
         id="borderedTabContent">


        <!-- =================================================
        FUEL SALES
        ================================================== -->

        <div class="tab-pane fade show active"
             id="bordered-ph1"
             role="tabpanel"
             aria-labelledby="ph1-tab">

            @include('pages.cashiers_report_form_p1')

        </div>


        <!-- =================================================
        OTHER SALES
        ================================================== -->

        <div class="tab-pane fade"
             id="bordered-ph2"
             role="tabpanel"
             aria-labelledby="ph2-tab">

            @include('pages.cashiers_report_form_p2')

        </div>


        <!-- =================================================
        MISCELLANEOUS ITEMS
        ================================================== -->

        <div class="tab-pane fade"
             id="bordered-ph3"
             role="tabpanel"
             aria-labelledby="ph3-tab">

            @include('pages.cashiers_report_form_p3')

        </div>


        <!-- =================================================
        DIPSTICK INVENTORY
        ================================================== -->

        <div class="tab-pane fade"
             id="bordered-ph7"
             role="tabpanel"
             aria-labelledby="ph7-tab">

            @include('pages.cashiers_report_form_p7')

        </div>


    </div>

    <!-- End Bordered Tabs -->

</div>89=


    </div> <!-- card -->

</div> <!-- tab-pane -->