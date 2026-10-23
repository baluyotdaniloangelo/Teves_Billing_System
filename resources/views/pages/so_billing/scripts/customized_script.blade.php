<style>
    .so-list-page {
        --so-burgundy: #441827;
        --so-teal: #1B4242;
        --so-sage: #5C8374;
        --so-cream: #F5F2B8;
        --so-cream-soft: #FBF9E8;
        --so-border: rgba(92, 131, 116, .28);
        --so-text: #263B38;
        --bs-primary: var(--so-teal);
        --bs-primary-rgb: 27, 66, 66;
        --bs-success: var(--so-sage);
        --bs-success-rgb: 92, 131, 116;
        --bs-danger: var(--so-burgundy);
        --bs-danger-rgb: 68, 24, 39;
        color: var(--so-text);
    }

    .so-list-page .card { border-color: var(--so-border); }
    .so-list-page .card-header { box-shadow: inset 0 3px 0 var(--so-sage); }
    .so-list-page h1, .so-list-page h2, .so-list-page h3,
    .so-list-page h4, .so-list-page h5, .so-list-page .form-label { color: var(--so-burgundy); }
    .so-list-page .btn-success, .so-list-page .btn-primary {
        --bs-btn-color: #fff;
        --bs-btn-bg: var(--so-teal);
        --bs-btn-border-color: var(--so-teal);
        --bs-btn-hover-color: #fff;
        --bs-btn-hover-bg: #143333;
        --bs-btn-hover-border-color: #143333;
        --bs-btn-active-color: #fff;
        --bs-btn-active-bg: var(--so-burgundy);
        --bs-btn-active-border-color: var(--so-burgundy);
    }
    .so-list-page .btn-danger {
        --bs-btn-color: #fff;
        --bs-btn-bg: var(--so-burgundy);
        --bs-btn-border-color: var(--so-burgundy);
        --bs-btn-hover-color: #fff;
        --bs-btn-hover-bg: #35121f;
        --bs-btn-hover-border-color: #35121f;
    }
    .so-list-page .btn-outline-primary {
        --bs-btn-color: var(--so-teal);
        --bs-btn-border-color: var(--so-teal);
        --bs-btn-hover-color: #fff;
        --bs-btn-hover-bg: var(--so-teal);
        --bs-btn-hover-border-color: var(--so-teal);
    }
    .so-list-page .text-success, .so-list-page .text-primary { color: var(--so-sage) !important; }
    .so-list-page .bg-success.bg-opacity-10, .so-list-page .bg-primary.bg-opacity-10 { background-color: rgba(92, 131, 116, .12) !important; }
    .so-list-card { border-color: var(--so-border) !important; }
    .so-list-icon { width: 55px; height: 55px; flex: 0 0 55px; }
    .so-list-header .small, .so-list-header small { display: inline-block; margin-top: .2rem; }
    .so-list-body { color: var(--so-text); }
    .so-filter-card { background: #fff; }
    .so-filter-card .form-label { color: var(--so-burgundy); }
    .so-list-page .form-control:focus, .so-list-page .form-select:focus {
        border-color: var(--so-sage);
        box-shadow: 0 0 0 .2rem rgba(92, 131, 116, .2);
    }
    .so-list-page .table {
        --bs-table-color: var(--so-text);
        --bs-table-border-color: var(--so-border);
        --bs-table-striped-bg: rgba(92, 131, 116, .045);
        --bs-table-striped-color: var(--so-text);
    }
    .so-list-page .so-list-table thead th {
        color: var(--so-cream) !important;
        background-color: var(--so-teal) !important;
        border-bottom: 2px solid var(--so-sage) !important;
        font-weight: 600;
        vertical-align: middle;
        white-space: nowrap;
    }
    .so-list-page .so-list-table thead th .bi { color: var(--so-cream) !important; font-size: .9em; margin-right: .35rem; }
    .so-list-page .table-hover > tbody > tr:hover > * { --bs-table-bg: var(--so-cream-soft); color: var(--so-text); }
    .so-list-page .so-number-button { padding: 0; border: 0; background: transparent; color: var(--so-teal); font-weight: 600; text-decoration: underline; text-decoration-color: rgba(27, 66, 66, .3); text-underline-offset: .15em; }
    .so-list-page .so-number-button:hover, .so-list-page .so-number-button:focus { color: var(--so-burgundy); }
    .so-list-page .action_table_menu_site { display: flex; justify-content: center; gap: .4rem; }
    .so-list-page .btn_icon_table {
        width: 2.1rem;
        height: 2.1rem;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--so-border) !important;
        border-radius: .65rem;
        background: var(--so-cream-soft);
        text-decoration: none;
        transition: background-color .15s ease, border-color .15s ease, color .15s ease, transform .15s ease;
    }
    .so-list-page .btn_icon_table_edit, .so-list-page .btn_icon_table_delete { color: var(--so-teal); }
    .so-list-page .btn_icon_table_delete { color: var(--so-burgundy); }
    .so-list-page .btn_icon_table_edit:hover, .so-list-page .btn_icon_table_delete:hover {
        color: #fff;
        background: var(--so-teal);
        border-color: var(--so-teal) !important;
        transform: translateY(-1px);
    }
    .so-list-page .btn_icon_table_delete:hover { background: var(--so-burgundy); border-color: var(--so-burgundy) !important; }
    .so-list-page .dataTables_wrapper > .row:last-child,
    .so-list-page .dataTables_wrapper > .dt-layout-row:last-child {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: .75rem;
    }
    .so-list-page .dataTables_wrapper .dataTables_info,
    .so-list-page .dataTables_wrapper .dataTables_paginate { margin: 0; }
    .so-list-page .dataTables_wrapper .paginate_button.current,
    .so-list-page .dataTables_wrapper .dt-paging-button.current {
        color: var(--so-cream) !important;
        background: var(--so-teal) !important;
        border-color: var(--so-teal) !important;
        border-radius: .5rem;
    }
    .so-list-page .dataTables_wrapper .paginate_button,
    .so-list-page .dataTables_wrapper .dt-paging-button { color: var(--so-teal) !important; }
    .so-feedback { margin-bottom: 1rem; padding: .75rem 1rem; border-radius: .75rem; }
    .so-feedback[data-state="error"] { color: var(--so-burgundy); border: 1px solid rgba(68, 24, 39, .25); background: rgba(68, 24, 39, .06); }
    .so-feedback[data-state="success"] { color: var(--so-teal); border: 1px solid var(--so-border); background: rgba(92, 131, 116, .12); }

    /* Bootstrap places open modals under <body>, so the palette lives on each modal root. */
    .so-modal-theme {
        --so-burgundy: #441827;
        --so-teal: #1B4242;
        --so-sage: #5C8374;
        --so-cream: #F5F2B8;
        --so-cream-soft: #FBF9E8;
        --so-border: rgba(92, 131, 116, .28);
        --so-text: #263B38;
        --bs-primary: var(--so-teal);
        --bs-primary-rgb: 27, 66, 66;
        --bs-success: var(--so-sage);
        --bs-success-rgb: 92, 131, 116;
        --bs-danger: var(--so-burgundy);
        --bs-danger-rgb: 68, 24, 39;
        color: var(--so-text);
    }
    .so-modal-theme .modal-content { border: 0; border-radius: 1rem; overflow: hidden; box-shadow: 0 1rem 3rem rgba(0, 0, 0, .2); }
    .so-modal-theme .modal-header {
        color: #fff;
        border: 0;
        padding: .85rem 1.25rem;
        background: linear-gradient(115deg, var(--so-burgundy), var(--so-teal) 72%, var(--so-sage));
    }
    .so-modal-theme .modal-header h1, .so-modal-theme .modal-header h2,
    .so-modal-theme .modal-header h3, .so-modal-theme .modal-header h4,
    .so-modal-theme .modal-header h5, .so-modal-theme .modal-header h6 { color: #fff; }
    .so-modal-theme .modal-title { font-size: 1.05rem; font-weight: 700; }
    .so-modal-theme .so-modal-eyebrow { margin: 0 0 .15rem; color: var(--so-cream); font-size: .7rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
    .so-modal-icon { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: .75rem; background: rgba(255, 255, 255, .2); color: #fff; }
    .so-modal-theme .modal-body { padding: 1rem; }
    .so-modal-theme .so-form-note { margin-bottom: 1rem; color: #718096; font-size: .9rem; }
    .so-modal-theme .form-label { margin-bottom: .25rem; color: var(--so-burgundy); font-size: .85rem; font-weight: 600; }
    .so-modal-theme .form-control, .so-modal-theme .form-select { border-color: var(--so-border); border-radius: .6rem; }
    .so-modal-theme .form-control:focus, .so-modal-theme .form-select:focus { border-color: var(--so-sage); box-shadow: 0 0 0 .2rem rgba(92, 131, 116, .2); }
    .so-modal-theme .modal-footer { border: 0; background: #f8f9fa; padding: .8rem 1rem; }
    .so-modal-theme .btn-success, .so-modal-theme .btn-primary {
        --bs-btn-color: #fff; --bs-btn-bg: var(--so-teal); --bs-btn-border-color: var(--so-teal);
        --bs-btn-hover-color: #fff; --bs-btn-hover-bg: #143333; --bs-btn-hover-border-color: #143333;
    }
    .so-modal-theme .btn-danger {
        --bs-btn-color: #fff; --bs-btn-bg: var(--so-burgundy); --bs-btn-border-color: var(--so-burgundy);
        --bs-btn-hover-color: #fff; --bs-btn-hover-bg: #35121f; --bs-btn-hover-border-color: #35121f;
    }
    .so-modal-theme .table { --bs-table-color: var(--so-text); --bs-table-border-color: var(--so-border); --bs-table-striped-bg: rgba(92, 131, 116, .045); }
    .so-modal-theme .table thead th { color: var(--so-cream) !important; background: var(--so-teal) !important; border-bottom: 2px solid var(--so-sage); }
    .so-required-note { margin-right: auto; color: #718096; font-size: .8rem; }
    .so-detail-list { margin: 0; border: 1px solid var(--so-border); border-radius: .75rem; overflow: hidden; }
    .so-detail-list > div { display: grid; grid-template-columns: minmax(100px, .8fr) 1.2fr; gap: 1rem; padding: .7rem .85rem; border-bottom: 1px solid rgba(92, 131, 116, .16); }
    .so-detail-list > div:last-child { border: 0; }
    .so-detail-list dt { color: #718096; font-size: .82rem; font-weight: 600; }
    .so-detail-list dd { margin: 0; color: var(--so-text); overflow-wrap: anywhere; }
    .so-order-meta { height: 100%; padding: .65rem .75rem; border: 1px solid var(--so-border); border-radius: .65rem; background: var(--so-cream-soft); }
    .so-order-meta span { display: block; color: #718096; font-size: .75rem; }
    .so-order-meta strong { display: block; color: var(--so-text); font-size: .85rem; overflow-wrap: anywhere; }
    .so-information-form { padding: 1rem; border: 1px solid var(--so-border); border-radius: .85rem; background: #fff; }
    .so-info-panel { position: sticky; top: 0; }
    .so-info-fields { display: grid; gap: .8rem; }
    .so-info-fields .form-label { display: block; }
    .so-products-panel { padding: .25rem; }
    .so-edit-context { padding: .7rem .85rem; border: 1px solid var(--so-border); border-radius: .65rem; background: var(--so-cream-soft); color: var(--so-teal); }
    .so-edit-amount { color: #718096; }
    .so-edit-amount strong { color: var(--so-burgundy); }
    .so-products-title { margin: .25rem 0 .85rem; color: var(--so-burgundy); font-size: .95rem; font-weight: 700; }
    .so-delete-warning { color: var(--so-burgundy); font-size: .82rem; }
    @media (max-width: 768px) {
        .so-list-page .dataTables_wrapper > .row:last-child,
        .so-list-page .dataTables_wrapper > .dt-layout-row:last-child { flex-direction: column; align-items: center; }
        .so-list-page .dataTables_wrapper .dataTables_info { text-align: center; }
    }
    @media (max-width: 575.98px) {
        .so-list-header { padding: .85rem 1rem !important; }
        .so-list-body { padding: 1rem !important; }
        .so-list-header .btn { width: 100%; justify-content: center; }
        .so-modal-theme .modal-header { padding: .75rem; }
        .so-modal-theme .modal-body { padding: .75rem; }
        .so-modal-theme .modal-footer { padding: .75rem; }
        .so-info-panel { position: static; }
        .so-delete-warning { flex-basis: 100%; margin: 0 !important; }
    }
</style>
