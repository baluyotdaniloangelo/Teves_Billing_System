@extends('layouts.layout')
@section('body_class', 'fuel-products-shell')

@push('page_styles')
<link href="{{ asset('style/fuel-products.css') }}?v={{ filemtime(public_path('style/fuel-products.css')) }}" rel="stylesheet">
@endpush

@section('content')
<style>
    .product-information-theme {
        --product-info-burgundy: #441827;
        --product-info-teal: #1B4242;
        --product-info-sage: #5C8374;
        --product-info-cream: #F5F2B8;
        --product-info-cream-soft: #FBF9E8;
        --product-info-border: rgba(92, 131, 116, .28);
        --product-info-text: #263B38;
        --bs-primary: var(--product-info-teal);
        --bs-primary-rgb: 27, 66, 66;
        --bs-success: var(--product-info-sage);
        --bs-success-rgb: 92, 131, 116;
        --bs-danger: var(--product-info-burgundy);
        --bs-danger-rgb: 68, 24, 39;
        color: var(--product-info-text);
    }

    .product-information-theme .card {
        border-color: var(--product-info-border);
    }

    .product-information-theme .card-header {
        box-shadow: inset 0 3px 0 var(--product-info-sage);
    }

    .product-information-theme h1,
    .product-information-theme h2,
    .product-information-theme h3,
    .product-information-theme h4,
    .product-information-theme h5,
    .product-information-theme .form-label {
        color: var(--product-info-burgundy);
    }

    .product-information-theme .form-control:focus,
    .product-information-theme .form-select:focus {
        border-color: var(--product-info-sage);
        box-shadow: 0 0 0 .2rem rgba(92, 131, 116, .2);
    }

    .product-information-theme .btn-success,
    .product-information-theme .btn-primary {
        --bs-btn-color: #fff;
        --bs-btn-bg: var(--product-info-teal);
        --bs-btn-border-color: var(--product-info-teal);
        --bs-btn-hover-color: #fff;
        --bs-btn-hover-bg: #143333;
        --bs-btn-hover-border-color: #143333;
        --bs-btn-active-color: #fff;
        --bs-btn-active-bg: var(--product-info-burgundy);
        --bs-btn-active-border-color: var(--product-info-burgundy);
        --bs-btn-disabled-color: #fff;
        --bs-btn-disabled-bg: var(--product-info-sage);
        --bs-btn-disabled-border-color: var(--product-info-sage);
    }

    .product-information-theme .btn-danger {
        --bs-btn-color: #fff;
        --bs-btn-bg: var(--product-info-burgundy);
        --bs-btn-border-color: var(--product-info-burgundy);
        --bs-btn-hover-color: #fff;
        --bs-btn-hover-bg: #35121f;
        --bs-btn-hover-border-color: #35121f;
    }

    .product-information-theme .text-success,
    .product-information-theme .text-primary {
        color: var(--product-info-sage) !important;
    }

    .product-information-theme .bg-success.bg-opacity-10,
    .product-information-theme .bg-primary.bg-opacity-10 {
        background-color: rgba(92, 131, 116, .12) !important;
    }

    .product-information-theme .table {
        --bs-table-color: var(--product-info-text);
        --bs-table-border-color: var(--product-info-border);
        --bs-table-striped-bg: rgba(92, 131, 116, .045);
        --bs-table-striped-color: var(--product-info-text);
    }

    .product-information-theme .table thead th {
        color: var(--product-info-cream) !important;
        background-color: var(--product-info-teal) !important;
        border-bottom: 2px solid var(--product-info-sage);
        font-weight: 600;
        vertical-align: middle;
        white-space: nowrap;
    }

    .product-information-theme .table thead th .bi {
        color: var(--product-info-cream) !important;
        font-size: .9em;
        margin-right: .35rem;
        vertical-align: -.03em;
    }

    .product-information-theme .table-light {
        --bs-table-bg: var(--product-info-teal);
        --bs-table-color: var(--product-info-cream);
        --bs-table-border-color: var(--product-info-sage);
    }

    .product-information-theme .table-hover > tbody > tr:hover > * {
        --bs-table-bg: var(--product-info-cream-soft);
        color: var(--product-info-text);
    }

    .product-information-theme .modal .modal-header {
        color: #fff;
        background: linear-gradient(115deg, var(--product-info-burgundy), var(--product-info-teal) 72%, var(--product-info-sage));
    }

    .product-information-theme .modal-header h1,
    .product-information-theme .modal-header h2,
    .product-information-theme .modal-header h3,
    .product-information-theme .modal-header h4,
    .product-information-theme .modal-header h5,
    .product-information-theme .modal-header h6 {
        color: #fff;
    }

    .product-information-theme #ProductInformationModal .product-information-tabs {
        background: rgba(92, 131, 116, .1);
        border-color: var(--product-info-border);
    }

    .product-information-theme #ProductInformationModal .product-information-tabs .nav-link {
        color: var(--product-info-teal);
        border-color: var(--product-info-border);
    }

    .product-information-theme #ProductInformationModal .product-information-tabs .nav-link:hover:not(.active) {
        color: var(--product-info-burgundy);
        background: var(--product-info-cream-soft);
        border-color: var(--product-info-sage);
    }

    .product-information-theme #ProductInformationModal .product-information-tabs .nav-link.active {
        color: var(--product-info-cream);
        background: var(--product-info-teal);
        border-color: var(--product-info-teal);
        box-shadow: 0 .25rem .75rem rgba(27, 66, 66, .2);
    }

    .product-information-theme #CreateProductModal .create-product-modal-header,
    .product-information-theme #ProductInformationModal .product-information-modal-header {
        background: linear-gradient(115deg, var(--product-info-burgundy), var(--product-info-teal) 72%, var(--product-info-sage));
    }

    .product-information-theme #ProductValidationErrorModal .text-danger,
    .product-information-theme #ProductValidationErrorModal .btn-danger {
        color: var(--product-info-burgundy) !important;
    }

    .product-information-theme #ProductValidationErrorModal .btn-danger {
        color: #fff !important;
        background-color: var(--product-info-burgundy);
        border-color: var(--product-info-burgundy);
    }

    .product-information-theme #ProductValidationErrorModal .bg-danger.bg-opacity-10 {
        background-color: rgba(68, 24, 39, .1) !important;
    }

    .product-information-theme .invalid-feedback {
        color: var(--product-info-burgundy);
    }

    .product-information-theme .product-update-success-notice,
    .product-information-modal-theme .product-update-success-notice {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
        padding: .65rem .85rem;
        color: var(--product-info-teal);
        background: rgba(92, 131, 116, .12);
        border: 1px solid rgba(92, 131, 116, .35);
        border-left: 4px solid var(--product-info-sage);
        border-radius: .75rem;
        font-size: .9rem;
    }

    .product-information-theme .is-invalid,
    .product-information-theme .was-validated .form-control:invalid,
    .product-information-theme .was-validated .form-select:invalid {
        border-color: var(--product-info-burgundy);
    }

    .product-information-theme .is-invalid:focus,
    .product-information-theme .was-validated .form-control:invalid:focus,
    .product-information-theme .was-validated .form-select:invalid:focus {
        box-shadow: 0 0 0 .2rem rgba(68, 24, 39, .15);
    }

    .product-information-theme .pagination .page-link {
        color: var(--product-info-teal);
    }

    .product-information-theme .pagination .active > .page-link,
    .product-information-theme .pagination .page-link.active {
        color: var(--product-info-cream);
        background-color: var(--product-info-teal);
        border-color: var(--product-info-teal);
    }

    .product-information-theme .pagination .page-link:focus {
        box-shadow: 0 0 0 .2rem rgba(92, 131, 116, .2);
    }

    /* Bootstrap moves shown modals to <body>, so give each Product modal its own palette scope. */
    .product-information-modal-theme {
        --product-info-burgundy: #441827;
        --product-info-teal: #1B4242;
        --product-info-sage: #5C8374;
        --product-info-cream: #F5F2B8;
        --product-info-cream-soft: #FBF9E8;
        --product-info-border: rgba(92, 131, 116, .28);
        --product-info-text: #263B38;
        --bs-primary: var(--product-info-teal);
        --bs-primary-rgb: 27, 66, 66;
        --bs-success: var(--product-info-sage);
        --bs-success-rgb: 92, 131, 116;
        --bs-danger: var(--product-info-burgundy);
        --bs-danger-rgb: 68, 24, 39;
        color: var(--product-info-text);
    }

    .product-information-modal-theme .btn-success,
    .product-information-modal-theme .btn-primary {
        --bs-btn-color: #fff;
        --bs-btn-bg: var(--product-info-teal);
        --bs-btn-border-color: var(--product-info-teal);
        --bs-btn-hover-color: #fff;
        --bs-btn-hover-bg: #143333;
        --bs-btn-hover-border-color: #143333;
        --bs-btn-active-color: #fff;
        --bs-btn-active-bg: var(--product-info-burgundy);
        --bs-btn-active-border-color: var(--product-info-burgundy);
    }

    .product-information-modal-theme .btn-danger {
        --bs-btn-color: #fff;
        --bs-btn-bg: var(--product-info-burgundy);
        --bs-btn-border-color: var(--product-info-burgundy);
        --bs-btn-hover-color: #fff;
        --bs-btn-hover-bg: #35121f;
        --bs-btn-hover-border-color: #35121f;
    }

    .product-information-modal-theme .text-success,
    .product-information-modal-theme .text-primary {
        color: var(--product-info-sage) !important;
    }

    .product-information-modal-theme .bg-success.bg-opacity-10,
    .product-information-modal-theme .bg-primary.bg-opacity-10 {
        background-color: rgba(92, 131, 116, .12) !important;
    }

    .product-information-modal-theme .modal-header {
        color: #fff;
        background: linear-gradient(115deg, var(--product-info-burgundy), var(--product-info-teal) 72%, var(--product-info-sage)) !important;
    }

    .product-information-modal-theme .form-label,
    .product-information-modal-theme h1,
    .product-information-modal-theme h2,
    .product-information-modal-theme h3,
    .product-information-modal-theme h4,
    .product-information-modal-theme h5 {
        color: var(--product-info-burgundy);
    }

    .product-information-modal-theme .modal-header h1,
    .product-information-modal-theme .modal-header h2,
    .product-information-modal-theme .modal-header h3,
    .product-information-modal-theme .modal-header h4,
    .product-information-modal-theme .modal-header h5,
    .product-information-modal-theme .modal-header h6 {
        color: #fff;
    }

    .product-information-modal-theme .form-control:focus,
    .product-information-modal-theme .form-select:focus {
        border-color: var(--product-info-sage);
        box-shadow: 0 0 0 .2rem rgba(92, 131, 116, .2);
    }

    .product-information-modal-theme .table {
        --bs-table-color: var(--product-info-text);
        --bs-table-border-color: var(--product-info-border);
        --bs-table-striped-bg: rgba(92, 131, 116, .045);
        --bs-table-striped-color: var(--product-info-text);
    }

    .product-information-modal-theme .table thead th {
        color: var(--product-info-cream) !important;
        background-color: var(--product-info-teal) !important;
        border-bottom: 2px solid var(--product-info-sage);
    }

    .product-information-modal-theme .table thead th .bi {
        color: var(--product-info-cream) !important;
    }

    .product-information-modal-theme .table-hover > tbody > tr:hover > * {
        --bs-table-bg: var(--product-info-cream-soft);
        color: var(--product-info-text);
    }

    .product-information-modal-theme .invalid-feedback {
        color: var(--product-info-burgundy);
    }

    .product-information-modal-theme .product-settings-modal-content {
        border: 0;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, .2);
    }

    .product-information-modal-theme .product-settings-modal-body {
        padding: 1rem;
        background: #fff;
    }

    .product-information-modal-theme .product-settings-form-card {
        border: 1px solid var(--product-info-border);
        border-radius: 1rem;
        padding: 1rem;
        background: #fff;
    }

    .product-information-modal-theme .product-settings-modal-footer {
        border-top: 1px solid rgba(92, 131, 116, .16);
        background: var(--product-info-cream-soft);
        padding: .8rem 1rem;
    }

    .product-information-modal-theme #productValidationErrorModalMessage {
        white-space: pre-line;
    }

    @media (max-width: 575.98px) {
        .product-information-theme #CreateProductModal .create-product-modal-header,
        .product-information-theme #ProductInformationModal .product-information-modal-header {
            padding: .75rem;
        }
    }
</style>
<main id="main" class="main fuel-page fuel-products">
    <div class="fuel-products-heading">
        <div><p class="fuel-products-eyebrow"></p><h1><span>Product Management</span></h1><p class="fuel-products-subtitle">Manage your main products, variants, and categories in one place.</p></div>
        <span class="fuel-catalog-label"><i class="bi bi-grid" aria-hidden="true"></i> Products &amp; services</span>
    </div>
    <section class="section product-information-theme">
        <div class="row g-3">
            <div class="col-12">
                @include('pages.product_information.tabs.product_list')
            </div>
        </div>

        @include('pages.product_information.modals.product_information_modal')
        @include('pages.product_information.modals.create_product_modal')
        @include('pages.product_information.modals.main_product_modals')
        @include('pages.product_information.modals.delete_product_modal')
        @include('pages.product_information.modals.product_qr_modal')
        @include('pages.product_information.modals.tank_modals')
        @include('pages.product_information.modals.pricing_modals')
        @include('pages.product_information.modals.seller_price_modals')
        @include('pages.product_information.modals.selling_price_modals')
        @include('pages.product_information.modals.pump_modals')
        @include('pages.product_information.modals.product_notice_modals')
        @if(in_array($data->user_type ?? null, ['SUAdmin', 'Admin']))
            @include('pages.product_category.modals.product_category_create_edit_modal')
            @include('pages.product_category.modals.product_category_delete_modal')
        @endif
    </section>
    <div class="fuel-products-footer"><span>Products &amp; services</span></div>
</main>
@endsection

@push('page_scripts')
    @include('pages.product_information.scripts.plugins_script')
    @include('pages.product_information.scripts.product_script')
    @include('pages.product_information.scripts.product_qr_script')
    @include('pages.product_information.scripts.main_product_script')
    @include('pages.product_information.scripts.product_info_script')
    @include('pages.product_information.scripts.tank_script')
    @include('pages.product_information.scripts.seller_price_script')
    @include('pages.product_information.scripts.selling_price_script')
    @include('pages.product_information.scripts.pump_script')
    @if(in_array($data->user_type ?? null, ['SUAdmin', 'Admin']))
        @include('pages.product_category.scripts.product_category_script')
    @endif
@endpush
