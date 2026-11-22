@extends('layouts.layout')
@section('body_class', 'fuel-dashboard-shell')

@push('page_styles')
<link href="{{ asset('style/fuel-dashboard.css') }}?v={{ filemtime(public_path('style/fuel-dashboard.css')) }}" rel="stylesheet">
<link href="{{ asset('style/sales-order.css') }}?v={{ filemtime(public_path('style/sales-order.css')) }}" rel="stylesheet">
<link href="{{ asset('Datatables/2.0.8/css/dataTables.dataTables.css') }}" rel="stylesheet">
<link href="{{ asset('Datatables/2.0.8/css/responsive.dataTables.css') }}" rel="stylesheet">
@endpush

@section('content')
<main id="main" class="main fuel-page so-form-page">
    @include('pages.sales_order_v2.editor.content')
</main>
@endsection

@push('page_scripts')
    <script src="{{ asset('Datatables/2.0.8/js/dataTables.js') }}"></script>
    <script src="{{ asset('Datatables/responsive/3.0.2/js/dataTables.responsive.js') }}"></script>
    <script src="{{ asset('Datatables/responsive/3.0.2/js/responsive.dataTables.js') }}"></script>
    <script src="{{ asset('js/sales-order-form-summary.js') }}?v={{ filemtime(public_path('js/sales-order-form-summary.js')) }}"></script>
    <script src="{{ asset('js/sales-order-form-tables.js') }}?v={{ filemtime(public_path('js/sales-order-form-tables.js')) }}"></script>
    @include('pages.sales_order_v2.scripts.edit_modal')
    @include('layouts.sales_order_form_delivery_script')
    @include('layouts.sales_order_form_script')
@endpush
