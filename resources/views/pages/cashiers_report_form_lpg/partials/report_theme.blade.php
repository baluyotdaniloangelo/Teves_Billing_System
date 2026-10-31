<style>
    /* Keep the LPG report aligned with the current Product page palette. */
    :is(.lpg-report-theme, .lpg-report-modal-theme) {
        --lpg-primary: #1B4242;
        --lpg-dark: #441827;
        --lpg-accent: #5C8374;
        --lpg-surface: #FBF9E8;
        --lpg-highlight: #F5F2B8;
        --lpg-accent-soft: rgba(92, 131, 116, .10);
        --lpg-accent-border: rgba(92, 131, 116, .35);
        --lpg-border: rgba(92, 131, 116, .28);
        --lpg-text: #263B38;
        --lpg-muted: #60756D;
        --bs-primary: var(--lpg-primary);
        --bs-primary-rgb: 27, 66, 66;
        --bs-success: var(--lpg-accent);
        --bs-success-rgb: 92, 131, 116;
        --bs-danger: var(--lpg-dark);
        --bs-danger-rgb: 68, 24, 39;
        --bs-border-color: var(--lpg-border);
        color: var(--lpg-text);
    }

    .lpg-report-theme { background-color: var(--lpg-surface); }

    :is(.lpg-report-theme, .lpg-report-modal-theme) .card {
        --bs-card-bg: #fff;
        --bs-card-border-color: var(--lpg-border);
        --bs-card-color: var(--lpg-text);
    }

    :is(.lpg-report-theme, .lpg-report-modal-theme) .card-header {
        box-shadow: inset 0 3px 0 var(--lpg-accent);
    }

    :is(.lpg-report-theme, .lpg-report-modal-theme) :is(h1, h2, h3, h4, h5, h6, .form-label) {
        color: var(--lpg-dark);
    }

    :is(.lpg-report-theme, .lpg-report-modal-theme) .text-body { color: var(--lpg-text) !important; }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .text-muted { color: var(--lpg-muted) !important; }
    :is(.lpg-report-theme, .lpg-report-modal-theme) :is(.text-primary, .text-success, .text-info) { color: var(--lpg-accent) !important; }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .text-danger { color: var(--lpg-dark) !important; }

    :is(.lpg-report-theme, .lpg-report-modal-theme) :is(.bg-primary.bg-opacity-10, .bg-primary-subtle, .bg-success.bg-opacity-10, .bg-info.bg-opacity-10) {
        background-color: var(--lpg-accent-soft) !important;
    }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .bg-danger.bg-opacity-10 { background-color: rgba(68, 24, 39, .10) !important; }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .bg-success:not(.bg-opacity-10) {
        color: #fff !important;
        background-color: var(--lpg-accent) !important;
    }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .bg-light { background-color: var(--lpg-surface) !important; }

    :is(.lpg-report-theme, .lpg-report-modal-theme) :is(.btn-success, .btn-primary) {
        --bs-btn-color: #fff;
        --bs-btn-bg: var(--lpg-primary);
        --bs-btn-border-color: var(--lpg-primary);
        --bs-btn-hover-color: #fff;
        --bs-btn-hover-bg: #143333;
        --bs-btn-hover-border-color: #143333;
        --bs-btn-active-color: #fff;
        --bs-btn-active-bg: var(--lpg-dark);
        --bs-btn-active-border-color: var(--lpg-dark);
        --bs-btn-disabled-color: #fff;
        --bs-btn-disabled-bg: var(--lpg-accent);
        --bs-btn-disabled-border-color: var(--lpg-accent);
        --bs-btn-focus-shadow-rgb: 92, 131, 116;
    }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .btn-danger {
        --bs-btn-color: #fff;
        --bs-btn-bg: var(--lpg-dark);
        --bs-btn-border-color: var(--lpg-dark);
        --bs-btn-hover-color: #fff;
        --bs-btn-hover-bg: #35121f;
        --bs-btn-hover-border-color: #35121f;
        --bs-btn-active-color: #fff;
        --bs-btn-active-bg: #35121f;
        --bs-btn-active-border-color: #35121f;
        --bs-btn-focus-shadow-rgb: 68, 24, 39;
    }
    :is(.lpg-report-theme, .lpg-report-modal-theme) :is(.btn-outline-success, .btn-outline-primary, .btn-outline-secondary) {
        --bs-btn-color: var(--lpg-primary);
        --bs-btn-border-color: var(--lpg-border);
        --bs-btn-hover-color: #fff;
        --bs-btn-hover-bg: var(--lpg-primary);
        --bs-btn-hover-border-color: var(--lpg-primary);
        --bs-btn-active-color: #fff;
        --bs-btn-active-bg: var(--lpg-dark);
        --bs-btn-active-border-color: var(--lpg-dark);
        --bs-btn-focus-shadow-rgb: 92, 131, 116;
    }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .btn-outline-danger {
        --bs-btn-color: var(--lpg-dark);
        --bs-btn-border-color: var(--lpg-dark);
        --bs-btn-hover-color: #fff;
        --bs-btn-hover-bg: var(--lpg-dark);
        --bs-btn-hover-border-color: var(--lpg-dark);
        --bs-btn-active-color: #fff;
        --bs-btn-active-bg: #35121f;
        --bs-btn-active-border-color: #35121f;
    }
    :is(.lpg-report-theme, .lpg-report-modal-theme) :is(.btn-light, .btn-secondary) {
        --bs-btn-color: var(--lpg-primary);
        --bs-btn-bg: var(--lpg-surface);
        --bs-btn-border-color: var(--lpg-border);
        --bs-btn-hover-color: var(--lpg-dark);
        --bs-btn-hover-bg: var(--lpg-highlight);
        --bs-btn-hover-border-color: var(--lpg-accent);
        --bs-btn-active-color: var(--lpg-primary);
        --bs-btn-active-bg: var(--lpg-accent-soft);
        --bs-btn-active-border-color: var(--lpg-accent);
    }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .btn-dark {
        --bs-btn-bg: var(--lpg-dark);
        --bs-btn-border-color: var(--lpg-dark);
        --bs-btn-hover-bg: #35121f;
        --bs-btn-hover-border-color: #35121f;
    }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .btn-link { --bs-btn-color: var(--lpg-primary); --bs-btn-hover-color: var(--lpg-dark); }

    :is(.lpg-report-theme, .lpg-report-modal-theme) .modal-content {
        color: var(--lpg-text);
        background-color: #fff;
        border: 1px solid var(--lpg-border);
    }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .modal-header {
        color: #fff;
        background: linear-gradient(115deg, var(--lpg-dark), var(--lpg-primary) 72%, var(--lpg-accent)) !important;
    }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .modal-header :is(h1, h2, h3, h4, h5, h6, .text-white, .text-primary, .text-success) { color: #fff !important; }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .modal-header :is(.text-white-50, .text-muted) { color: rgba(255, 255, 255, .78) !important; }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .modal-header .btn-danger {
        --bs-btn-bg: rgba(255, 255, 255, .12);
        --bs-btn-border-color: rgba(255, 255, 255, .30);
        --bs-btn-hover-bg: rgba(255, 255, 255, .24);
        --bs-btn-hover-border-color: rgba(255, 255, 255, .50);
    }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .modal-footer { background-color: var(--lpg-surface); border-color: var(--lpg-border); }

    :is(.lpg-report-theme, .lpg-report-modal-theme) :is(.form-control, .form-select, .input-group-text, .dt-input) {
        color: var(--lpg-text);
        background-color: #fff;
        border-color: var(--lpg-border);
    }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .input-group-text { background-color: var(--lpg-surface); }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .form-control::placeholder { color: var(--lpg-muted); opacity: 1; }
    :is(.lpg-report-theme, .lpg-report-modal-theme) :is(.form-control:disabled, .form-control[readonly], .form-select:disabled) { background-color: var(--lpg-accent-soft); }
    :is(.lpg-report-theme, .lpg-report-modal-theme) :is(.form-control:focus, .form-select:focus, .form-check-input:focus, .dt-input:focus) {
        border-color: var(--lpg-accent);
        box-shadow: 0 0 0 .2rem rgba(92, 131, 116, .20);
    }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .form-check-input:checked { background-color: var(--lpg-primary); border-color: var(--lpg-primary); }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .invalid-feedback { color: var(--lpg-dark); }
    :is(.lpg-report-theme, .lpg-report-modal-theme) :is(.is-invalid, .was-validated .form-control:invalid, .was-validated .form-select:invalid) { border-color: var(--lpg-dark); }
    :is(.lpg-report-theme, .lpg-report-modal-theme) :is(.is-invalid:focus, .was-validated .form-control:invalid:focus, .was-validated .form-select:invalid:focus) { box-shadow: 0 0 0 .2rem rgba(68, 24, 39, .15); }

    .lpg-report-theme #LPGTab {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        border: 1px solid var(--lpg-border);
        background-color: var(--lpg-accent-soft) !important;
        padding: .5rem !important;
        box-shadow: none !important;
    }
    .lpg-report-theme #LPGTab .nav-link {
        width: 100%;
        height: 100%;
        padding: .8rem 1rem !important;
        font-size: 18px;
        color: var(--lpg-primary);
        background-color: transparent;
        border: 1px solid transparent;
        box-shadow: none !important;
    }
    .lpg-report-theme #LPGTab .nav-link:hover:not(.active) { color: var(--lpg-dark); background-color: var(--lpg-surface); border-color: var(--lpg-border); }
    .lpg-report-theme #LPGTab .nav-link.active { color: var(--lpg-highlight); background-color: var(--lpg-primary); border-color: var(--lpg-primary); box-shadow: 0 .2rem .6rem rgba(27, 66, 66, .18) !important; }

    .lpg-report-theme :is(#lpgCollectionReportSubTabs, #lpgInventorySubTabs) { gap: .25rem; padding: .35rem; border: 1px solid var(--lpg-border); border-radius: .9rem; background-color: var(--lpg-accent-soft); }
    .lpg-report-theme :is(#lpgCollectionReportSubTabs, #lpgInventorySubTabs, #lpgKitchenSubTabs) .nav-link { color: var(--lpg-primary); border: 1px solid transparent; border-radius: .7rem; font-weight: 600; }
    .lpg-report-theme :is(#lpgCollectionReportSubTabs, #lpgInventorySubTabs, #lpgKitchenSubTabs) .nav-link:hover:not(.active) { color: var(--lpg-dark); background-color: var(--lpg-surface); }
    .lpg-report-theme :is(#lpgCollectionReportSubTabs, #lpgInventorySubTabs, #lpgKitchenSubTabs) .nav-link.active { color: var(--lpg-highlight); background-color: var(--lpg-primary); border-color: var(--lpg-primary); }

    :is(.lpg-report-theme, .lpg-report-modal-theme) .table {
        --bs-table-bg: #fff;
        --bs-table-color: var(--lpg-text);
        --bs-table-border-color: var(--lpg-border);
        --bs-table-striped-bg: rgba(92, 131, 116, .045);
        --bs-table-striped-color: var(--lpg-text);
        --bs-table-hover-bg: var(--lpg-surface);
        --bs-table-hover-color: var(--lpg-text);
    }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .table thead th {
        --bs-table-bg: var(--lpg-primary);
        --bs-table-color: var(--lpg-highlight);
        --bs-table-accent-bg: transparent;
        color: var(--lpg-highlight) !important;
        background-color: var(--lpg-primary) !important;
        border-color: var(--lpg-accent-border);
        border-bottom: 2px solid var(--lpg-accent);
        font-weight: 600;
        vertical-align: middle;
        white-space: nowrap;
    }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .table thead th :is(.bi, .text-muted, .text-primary, .text-success) { color: var(--lpg-highlight) !important; }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .table thead th .bi { font-size: .9em; margin-right: .35rem; vertical-align: -.03em; }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .table thead .btn .bi { margin: 0; }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .table thead .btn:not(:hover) .bi { color: var(--lpg-primary) !important; }
    .lpg-report-theme .table thead .lpg-summary-actions th { padding: .5rem; vertical-align: middle; }
    .lpg-report-theme .lpg-summary-source-buttons .btn { flex: 0 0 auto; white-space: nowrap; }
    .lpg-report-theme :is(#LpgSellOutTable, #LpgKitchenTable, #LpgKitchenSetsTable, #LpgKitchenIndividualTable, #LpgEmptyInventoryTable, #LpgRegulatorInventoryTable) {
        --lpg-grid-border: #93A99F;
        --bs-table-border-color: var(--lpg-grid-border);
        border-collapse: collapse !important;
        border-spacing: 0;
        border: 1px solid var(--lpg-grid-border) !important;
        font-size: 18px;
    }
    .lpg-report-theme :is(#LpgSellOutTable, #LpgKitchenTable, #LpgKitchenSetsTable, #LpgKitchenIndividualTable, #LpgEmptyInventoryTable, #LpgRegulatorInventoryTable) :is(th, td) {
        border: 1px solid var(--lpg-grid-border) !important;
    }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .table .table-light { --bs-table-bg: var(--lpg-surface); --bs-table-color: var(--lpg-primary); }

    :is(.lpg-report-theme, .lpg-report-modal-theme) .table :is(.btn-primary, .btn-danger) {
        --bs-btn-bg: var(--lpg-surface);
        --bs-btn-border-color: var(--lpg-border);
        --bs-btn-color: var(--lpg-primary);
        --bs-btn-hover-color: #fff;
        --bs-btn-hover-bg: var(--lpg-primary);
        --bs-btn-hover-border-color: var(--lpg-primary);
        border-radius: .65rem;
        transition: background-color .15s ease, border-color .15s ease, color .15s ease;
    }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .table .btn-danger { --bs-btn-color: var(--lpg-dark); --bs-btn-hover-bg: var(--lpg-dark); --bs-btn-hover-border-color: var(--lpg-dark); }

    :is(.lpg-report-theme, .lpg-report-modal-theme) :is(.dt-paging-button, .paginate_button):not(.page-item) {
        color: var(--lpg-primary) !important;
        background: #fff !important;
        border: 1px solid var(--lpg-border) !important;
        border-radius: .5rem;
    }
    :is(.lpg-report-theme, .lpg-report-modal-theme) :is(.dt-paging-button, .paginate_button):not(.page-item):hover { background: var(--lpg-surface) !important; }
    :is(.lpg-report-theme, .lpg-report-modal-theme) :is(.dt-paging-button, .paginate_button).current:not(.page-item) { color: var(--lpg-highlight) !important; background: var(--lpg-primary) !important; border-color: var(--lpg-primary) !important; }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .pagination .page-link { color: var(--lpg-primary); background-color: #fff; border-color: var(--lpg-border); }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .pagination .page-link:hover { background-color: var(--lpg-surface); }
    :is(.lpg-report-theme, .lpg-report-modal-theme) .pagination :is(.active > .page-link, .page-link.active) { color: var(--lpg-highlight); background-color: var(--lpg-primary); border-color: var(--lpg-primary); }
    :is(.lpg-report-theme, .lpg-report-modal-theme) :is(.dt-paging-button.disabled, .paginate_button.disabled, .pagination .disabled > .page-link) { color: var(--lpg-muted) !important; background: var(--lpg-surface) !important; }

    .lpg-report-theme .lpg-cash-denomination-row:hover { background-color: var(--lpg-surface); }
    .lpg-report-theme :is(.lpg-cash-total-row, .lpg-cash-actual-row) { color: var(--lpg-primary); background-color: var(--lpg-surface); }
    .lpg-report-theme .lpg-cash-actual-row { border-top: 2px solid var(--lpg-accent) !important; }
    .lpg-report-theme .dropdown-menu { --bs-dropdown-link-hover-bg: var(--lpg-surface); --bs-dropdown-link-hover-color: var(--lpg-dark); --bs-dropdown-link-active-bg: var(--lpg-primary); border-color: var(--lpg-border); }

    .lpg-report-action-btn { display: inline-flex; align-items: center; justify-content: center; gap: .5rem; width: 12rem; max-width: 100%; height: 2.5rem; padding: 0 .75rem; border-radius: .6rem; font-size: .9rem; font-weight: 600; line-height: 1; white-space: nowrap; }
    .lpg-report-action-btn > .bi { margin: 0 !important; line-height: 1; }
    .lpg-report-header-actions { flex-wrap: wrap; }
    .lpg-report-info-card { position: relative; z-index: 2; overflow: visible; }
    .lpg-report-info-card .dropdown-menu { z-index: 1080; }

    .lpg-product-picker-shell { position: relative; width: 100%; }
    .lpg-product-source-select { display: none !important; }
    .lpg-product-picker-input { padding-right: 2.5rem; background-color: #fff; }
    .lpg-product-picker-icon { position: absolute; top: 50%; right: .9rem; z-index: 2; color: var(--lpg-muted, #60756D); transform: translateY(-50%); pointer-events: none; }
    .lpg-product-suggestion-menu { position: fixed; z-index: 2000; overflow-y: auto; padding: .35rem; border: 1px solid rgba(92, 131, 116, .28); border-radius: .8rem; background: #fff; box-shadow: 0 .75rem 1.8rem rgba(27, 66, 66, .20); }
    .lpg-product-suggestion-menu[hidden] { display: none !important; }
    .lpg-product-suggestion-option { display: flex; width: 100%; flex-direction: column; gap: .15rem; padding: .65rem .75rem; border: 0; border-radius: .55rem; background: transparent; color: #263B38; text-align: left; }
    .lpg-product-suggestion-option:hover, .lpg-product-suggestion-option.is-active { background: #FBF9E8; }
    .lpg-product-suggestion-label { font-weight: 600; }
    .lpg-product-suggestion-details, .lpg-product-suggestion-empty { color: #60756D; font-size: .82rem; }
    .lpg-product-suggestion-empty { padding: .8rem; }

    @media (max-width: 1199.98px) { .lpg-report-theme #LPGTab { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 575.98px) { .lpg-report-theme #LPGTab { grid-template-columns: minmax(0, 1fr); } }
</style>
