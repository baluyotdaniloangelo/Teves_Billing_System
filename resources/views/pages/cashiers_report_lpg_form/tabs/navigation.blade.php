<ul class="nav nav-pills nav-fill gap-2 p-3 bg-light rounded-4 shadow-sm mb-3"
    id="PurchaseOrderTab"
    role="tablist">

    <!-- PURCHASE ORDER INFORMATION -->
    <li class="nav-item" role="presentation">

        <button class="nav-link active rounded-3 py-3 fw-semibold shadow-sm"
                id="purchase_order_info-tab"
                data-bs-toggle="tab"
                data-bs-target="#purchase_order_info"
                type="button"
                role="tab"
                aria-controls="purchase_order_info"
                aria-selected="true">

            <i class="bi bi-file-earmark-text me-2"></i>
            Purchase Order Information

        </button>

    </li>

    <!-- PRODUCT -->
    <li class="nav-item" role="presentation">

        <button class="nav-link rounded-3 py-3 fw-semibold shadow-sm"
                id="purchase_order_product_list-tab"
                data-bs-toggle="tab"
                data-bs-target="#purchase_order_product_list"
                type="button"
                role="tab"
                aria-controls="purchase_order_product_list"
                aria-selected="false"
                onclick="LoadProduct()">

            <i class="bi bi-box-seam me-2"></i>
            Products

        </button>

    </li>

    <!-- WITHDRAWAL -->
    <li class="nav-item" role="presentation">

        <button class="nav-link rounded-3 py-3 fw-semibold shadow-sm"
                id="withdrawal-tab"
                data-bs-toggle="tab"
                data-bs-target="#withdrawal"
                type="button"
                role="tab"
                aria-controls="withdrawal"
                aria-selected="false"
                title="Product Withdrawal List, Create, Update and Delete Withdrawal">

            <i class="bi bi-truck me-2"></i>
            Withdrawal

        </button>

    </li>

    <!-- PAYMENT -->
    <li class="nav-item" role="presentation">

        <button class="nav-link rounded-3 py-3 fw-semibold shadow-sm"
                id="payment-tab"
                data-bs-toggle="tab"
                data-bs-target="#payment"
                type="button"
                role="tab"
                aria-controls="payment"
                aria-selected="false">

            <i class="bi bi-credit-card-2-front me-2"></i>
            Payment

        </button>

    </li>

</ul>