<ul class="nav nav-pills nav-fill gap-2 p-3 bg-light rounded-4 shadow-sm mb-3"
    id="LPGTab"
    role="tablist">

    <!-- Accounts Receivable -->
    <li class="nav-item" role="presentation">
        <button class="nav-link active rounded-3 py-3 fw-semibold shadow-sm"
                id="lpg_accounts_recievable-tab"
                data-bs-toggle="tab"
                data-bs-target="#lpg_accounts_recievable"
                type="button"
                role="tab"
                aria-controls="lpg_accounts_recievable"
                aria-selected="true">
            <i class="bi bi-person-lines-fill me-2"></i>
            Accounts Receivable
        </button>
    </li>

    <!-- Sales Order / AR / Returned -->
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-3 py-3 fw-semibold shadow-sm"
                id="lpg_ar_so-tab"
                data-bs-toggle="tab"
                data-bs-target="#lpg_ar_so"
                type="button"
                role="tab"
                aria-controls="lpg_ar_so"
                aria-selected="false">
            <i class="bi bi-receipt-cutoff me-2"></i>
            Sales Order - Account Receivable / Returned / Cash Sales
        </button>
    </li>

    <!-- Expenses -->
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-3 py-3 fw-semibold shadow-sm"
                id="lpg_expenses-tab"
                data-bs-toggle="tab"
                data-bs-target="#lpg_expenses"
                type="button"
                role="tab"
                aria-controls="lpg_expenses"
                aria-selected="false">
            <i class="bi bi-cash-stack me-2"></i>
            Expenses
        </button>
    </li>

    <!-- Cash Collection -->
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-3 py-3 fw-semibold shadow-sm"
                id="collection_report-tab"
                data-bs-toggle="tab"
                data-bs-target="#collection_report"
                type="button"
                role="tab"
                aria-controls="collection_report"
                aria-selected="false">
            <i class="bi bi-cash-coin me-2"></i>
            Cash Collection
        </button>
    </li>

</ul>