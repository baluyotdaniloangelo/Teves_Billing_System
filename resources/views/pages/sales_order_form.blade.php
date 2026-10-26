@extends('layouts.layout')

@push('page_styles')
<link href="{{ asset('style/sales-order.css') }}?v={{ filemtime(public_path('style/sales-order.css')) }}" rel="stylesheet">
@endpush

@section('content')
<main id="main" class="main">
    @include('pages.sales_order_v2.editor.content')
</main>
@endsection

@push('page_scripts')
    @include('pages.sales_order_v2.scripts.edit_modal')
    @include('layouts.sales_order_form_delivery_script')
    @include('layouts.sales_order_form_script')
@endpush
