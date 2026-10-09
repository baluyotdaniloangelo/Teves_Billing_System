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
			
                <button type="button"
                    class="btn btn-success rounded-3 shadow-sm fuel-report-action-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#AddProductModal" 
					id="AddPurchaseOrderProductBTN">

                <i class="bi bi-plus-circle me-1"></i>
                Add
            </button>
			-->
        </div>

    </div>

</div>

        <!-- BODY -->

<!-- =========================================================
FUEL CASHIER REPORT TABS
========================================================= -->

<div class="card-body">

    <style>
        .fuel-report-subtabs {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
            padding: .5rem;
            margin-bottom: 1rem;
            background: #f7f9f8;
            border: 1px solid #e4ebe7;
            border-radius: 1rem;
        }

        .fuel-report-subtabs .nav-item {
            flex: 1 1 12rem;
        }

        .fuel-report-subtabs .nav-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .65rem;
            width: 100%;
            min-height: 3.25rem;
            padding: .5rem .85rem;
            color: #495057;
            font-weight: 600;
            background: #fff;
            border: 1px solid #e2e8e5;
            border-radius: .75rem;
            transition: background-color .18s ease, border-color .18s ease, color .18s ease, box-shadow .18s ease;
        }

        .fuel-report-subtabs .nav-link:hover:not(.active) {
            color: #146c43;
            background: #f1f8f4;
            border-color: #b7d9c6;
        }

        .fuel-report-subtabs .nav-link.active {
            color: #fff;
            background: #198754;
            border-color: #198754;
            box-shadow: 0 .25rem .75rem rgba(25, 135, 84, .2);
        }

        .fuel-report-subtabs .subtab-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 2rem;
            width: 2rem;
            height: 2rem;
            color: #198754;
            background: rgba(25, 135, 84, .1);
            border-radius: .6rem;
        }

        .fuel-report-subtabs .nav-link.active .subtab-icon {
            color: #fff;
            background: rgba(255, 255, 255, .18);
        }

        @media (max-width: 575.98px) {
            .fuel-report-subtabs .nav-item {
                flex-basis: 100%;
            }
        }

        .misc-sales-subtabs {
            display: flex;
            flex-wrap: wrap;
            gap: .25rem .5rem;
            padding: 0 0 .35rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid #dfe7e2;
        }

        .misc-sales-subtabs .nav-item {
            flex: 1 1 14rem;
        }

        .misc-sales-subtabs .nav-link {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: .6rem;
            width: 100%;
            height: 100%;
            min-height: 3.25rem;
            padding: .55rem .75rem;
            color: #58645e;
            font-size: .9rem;
            font-weight: 600;
            line-height: 1.25;
            text-align: left;
            background: transparent;
            border: 0;
            border-bottom: 3px solid transparent;
            border-radius: .65rem .65rem 0 0;
            transition: background-color .18s ease, border-color .18s ease, color .18s ease;
        }

        .misc-sales-subtabs .nav-link:hover:not(.active) {
            color: #146c43;
            background: #f6faf7;
        }

        .misc-sales-subtabs .nav-link.active {
            color: #146c43;
            background: #eef7f1;
            border-bottom-color: #198754;
        }

        .misc-sales-subtabs .subtab-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 1.9rem;
            width: 1.9rem;
            height: 1.9rem;
            color: #198754;
            background: rgba(25, 135, 84, .1);
            border-radius: .55rem;
        }

        .misc-sales-subtabs .nav-link.active .subtab-icon {
            color: #fff;
            background: #198754;
        }
    </style>

    <ul class="nav fuel-report-subtabs"
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

                <span class="subtab-icon"><i class="bi bi-fuel-pump"></i></span>
                <span>Fuel Sales</span>

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

                <span class="subtab-icon"><i class="bi bi-box-seam"></i></span>
                <span>Miscellaneous Items</span>

            </button>

        </li>


        <!-- =================================================
        DIPSTICK INVENTORY
        ================================================== -->

        <li class="nav-item"
            role="presentation">

            <button class="nav-link"
                    id="dipstick-inventory-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#bordered-dipstick-inventory"
                    type="button"
                    role="tab"
                    aria-controls="bordered-dipstick-inventory"
                    aria-selected="false">

                <span class="subtab-icon"><i class="bi bi-bar-chart-line"></i></span>
                <span>Dipstick Inventory</span>

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


			
			<div align="right">
				<button type="button"
                    class="btn btn-success rounded-3 shadow-sm fuel-report-action-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#CashierReportFuelSalesModal" 
					id="CashierReportFuelSalesModalBTN">

                <i class="bi bi-plus-circle me-1"></i>
                Add
				</button>
			</div>
			<br>

				<table  class="table table-hover align-middle dataTable display nowrap cell-border" id="FuelSales">
					<thead>
							<tr class='report'>
								<th><i class="bi bi-hash" aria-hidden="true"></i>Item</th>
								<th><i class="bi bi-box-seam" aria-hidden="true"></i>Product</th>
								<th><i class="bi bi-water" aria-hidden="true"></i>Tank</th>
								<th><i class="bi bi-fuel-pump" aria-hidden="true"></i>Pump</th>
								<th><i class="bi bi-box-arrow-in-down" aria-hidden="true"></i>Beginning</th>
								<th><i class="bi bi-box-arrow-up" aria-hidden="true"></i>Closing</th>
								<th><i class="bi bi-sliders" aria-hidden="true"></i>Calibration</th>
								<th><i class="bi bi-droplet" aria-hidden="true"></i>Sales in Liters</th>
								<th><i class="bi bi-tag" aria-hidden="true"></i>Pump Price</th>
								<th><i class="bi bi-currency-exchange" aria-hidden="true"></i>Peso Sales</th>
								<th><i class="bi bi-three-dots" aria-hidden="true"></i>Action</th>
								<th class="none">Created At:</th>
								<th class="none">Updated At:</th>
							</tr>
					</thead>
					<tbody id="table_product_body_data">
							<tr style="display: none;">
								<td>HIDDEN</td>
							</tr>
					</tbody>
				</table>

        </div>


        <!-- =================================================
        MISCELLANEOUS ITEMS
        ================================================== -->

        <div class="tab-pane fade"
             id="bordered-ph3"
             role="tabpanel"
             aria-labelledby="ph3-tab">

<!-- ========================================== -->
<!-- PART 3 - MISCELLANEOUS SALES -->
<!-- ========================================== -->

<div class="d-flex justify-content-end mb-2">

    <button type="button"
            class="btn btn-success new_item rounded-3 shadow-sm px-3 py-2 fuel-report-action-btn"
            data-bs-toggle="modal"
            data-bs-target="#miscellaneous_sales_Modal"
            title="Add Miscellaneous Sales">
        <i class="bi bi-plus-circle me-2"></i>Add
    </button>

</div>


<!-- ========================================== -->
<!-- MISCELLANEOUS SALES TABS -->
<!-- ========================================== -->

<ul class="nav misc-sales-subtabs"
    id="miscellaneous-sales-tab"
    role="tablist">


    <!-- SALES ORDER - CREDIT SALES -->
    <li class="nav-item"
        role="presentation">

        <button class="nav-link active"
                id="miscellaneous-sales-credit-tab"
                data-bs-toggle="tab"
                data-bs-target="#miscellaneous-sales-credit"
                type="button"
                role="tab"
                aria-controls="miscellaneous-sales-credit"
                aria-selected="true">

            <span class="subtab-icon"><i class="bi bi-receipt-cutoff"></i></span>
            <span>Sales Order - Credit Sales</span>

        </button>

    </li>


    <!-- DISCOUNTS -->
    <li class="nav-item"
        role="presentation">

        <button class="nav-link"
                id="miscellaneous-sales-discount-tab"
                data-bs-toggle="tab"
                data-bs-target="#miscellaneous-sales-discount"
                type="button"
                role="tab"
                aria-controls="miscellaneous-sales-discount"
                aria-selected="false">

            <span class="subtab-icon"><i class="bi bi-percent"></i></span>
            <span>Discounts (Wholesale - Fuel)</span>

        </button>

    </li>


    <!-- OTHERS -->
    <li class="nav-item"
        role="presentation">

        <button class="nav-link"
                id="miscellaneous-sales-others-tab"
                data-bs-toggle="tab"
                data-bs-target="#miscellaneous-sales-others"
                type="button"
                role="tab"
                aria-controls="miscellaneous-sales-others"
                aria-selected="false">

            <span class="subtab-icon"><i class="bi bi-cash-coin"></i></span>
            <span>Others (Lubricants Discounts / Money Cash Out / Misload)</span>

        </button>

    </li>

</ul>


<!-- ========================================== -->
<!-- TAB CONTENT -->
<!-- ========================================== -->

<div class="tab-content pt-2"
     id="miscellaneous-sales-tabContent">


    <!-- ========================================== -->
    <!-- SALES ORDER - CREDIT SALES -->
    <!-- ========================================== -->

    <div class="tab-pane fade show active"
         id="miscellaneous-sales-credit"
         role="tabpanel"
         aria-labelledby="miscellaneous-sales-credit-tab">


		<table id="CashiersReportPH3SalesCreditTable"
			   class="table table-hover align-middle w-100">

			<thead>
				<tr>
					<th><i class="bi bi-hash" aria-hidden="true"></i>#</th>
					<th><i class="bi bi-person" aria-hidden="true"></i>Account Name</th>
					<th><i class="bi bi-file-earmark-text" aria-hidden="true"></i>Reference No.</th>
					<th><i class="bi bi-box-seam" aria-hidden="true"></i>Product / Description</th>
					<th><i class="bi bi-cart3" aria-hidden="true"></i>Quantity</th>
					<th><i class="bi bi-tag" aria-hidden="true"></i>Pump Price</th>
					<th><i class="bi bi-cash-stack" aria-hidden="true"></i>Total Amount</th>
					<th><i class="bi bi-three-dots" aria-hidden="true"></i>Action</th>
				</tr>
			</thead>

			<tbody id="table_product_data_msc_SALES_CREDIT">
			</tbody>

		</table>

    </div>


    <!-- ========================================== -->
    <!-- DISCOUNTS -->
    <!-- ========================================== -->

    <div class="tab-pane fade"
         id="miscellaneous-sales-discount"
         role="tabpanel"
         aria-labelledby="miscellaneous-sales-discount-tab">


		<table id="CashiersReportPH3DiscountTable"
			   class="table table-hover align-middle w-100">

			<thead>
				<tr>
					<th><i class="bi bi-hash" aria-hidden="true"></i>#</th>
					<th><i class="bi bi-file-earmark-text" aria-hidden="true"></i>Reference No.</th>
					<th><i class="bi bi-box-seam" aria-hidden="true"></i>Product / Description</th>
					<th><i class="bi bi-cart3" aria-hidden="true"></i>Quantity</th>
					<th><i class="bi bi-tag" aria-hidden="true"></i>Pump Price</th>
					<th><i class="bi bi-tag-fill" aria-hidden="true"></i>Unit Price</th>
					<th><i class="bi bi-percent" aria-hidden="true"></i>Discounted Price</th>
					<th><i class="bi bi-cash-stack" aria-hidden="true"></i>Total Amount</th>
					<th><i class="bi bi-three-dots" aria-hidden="true"></i>Action</th>
				</tr>
			</thead>

			<tbody id="table_product_data_msc_DISCOUNT">
			</tbody>

		</table>

    </div>


    <!-- ========================================== -->
    <!-- OTHERS -->
    <!-- ========================================== -->

    <div class="tab-pane fade"
         id="miscellaneous-sales-others"
         role="tabpanel"
         aria-labelledby="miscellaneous-sales-others-tab">


		<table id="CashiersReportPH3OthersTable"
			   class="table table-hover align-middle w-100">

			<thead>
				<tr>
					<th><i class="bi bi-hash" aria-hidden="true"></i>#</th>
					<th><i class="bi bi-file-earmark-text" aria-hidden="true"></i>Reference No.</th>
					<th><i class="bi bi-box-seam" aria-hidden="true"></i>Item Description</th>
					<th><i class="bi bi-cart3" aria-hidden="true"></i>Quantity</th>
					<th><i class="bi bi-cash-stack" aria-hidden="true"></i>Total Amount</th>
					<th><i class="bi bi-three-dots" aria-hidden="true"></i>Action</th>
				</tr>
			</thead>

			<tbody id="table_product_data_msc_OTHERS">
			</tbody>

		</table>

    </div>

</div>

        </div>


        <!-- =================================================
        DIPSTICK INVENTORY
        ================================================== -->

        <div class="tab-pane fade"
             id="bordered-dipstick-inventory"
             role="tabpanel"
             aria-labelledby="dipstick-inventory-tab">
			<div align="right">
					<button type="button" class="btn btn-success new_item fuel-report-action-btn" id="add_dipstick_inventory">
						<i class="bi bi-plus-circle me-1"></i> Add Dipstick Reading
					</button>
			</div>
			<br>
            <table id="table_product_dipstick_inventory"
				   class="table table-bordered table-hover w-100">

				<thead>
					<tr>
						<th class="all"><i class="bi bi-box-seam" aria-hidden="true"></i>Product</th>
						<th class="all"><i class="bi bi-water" aria-hidden="true"></i>Tank</th>
						<th class="none"><i class="bi bi-speedometer2" aria-hidden="true"></i>Tank Capacity</th>
						<th class="all"><i class="bi bi-box-arrow-in-down" aria-hidden="true"></i>Beginning Inventory</th>
						<th class="all"><i class="bi bi-droplet" aria-hidden="true"></i>Sales in Liters</th>
						<th class="all"><i class="bi bi-arrow-down-up" aria-hidden="true"></i>UGT Pumping</th>
						<th class="all"><i class="bi bi-truck" aria-hidden="true"></i>Delivery</th>
						<th class="all"><i class="bi bi-box-arrow-up" aria-hidden="true"></i>Ending Inventory</th>
						<th class="all"><i class="bi bi-calculator" aria-hidden="true"></i>Book Stock</th>
						<th class="all"><i class="bi bi-activity" aria-hidden="true"></i>Variance</th>
						<th class="all"><i class="bi bi-three-dots" aria-hidden="true"></i>Action</th>
					</tr>
				</thead>

				<tbody>
				</tbody>

			</table>
        </div>


    </div>

    <!-- End Bordered Tabs -->

</div>


    </div> <!-- card -->

</div> <!-- tab-pane -->
