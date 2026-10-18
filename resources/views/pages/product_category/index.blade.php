@extends('layouts.layout')

@section('content')
<main id="main" class="main">
    <section class="section product-information-theme">
        @include('pages.product_category.tabs.product_category_management')
        @include('pages.product_category.modals.product_category_create_edit_modal')
        @include('pages.product_category.modals.product_category_delete_modal')
    </section>
</main>

@include('pages.product_category.scripts.plugins_script')
@include('pages.product_category.scripts.product_category_script')
@endsection

@section('skip_default_footer', '1')
