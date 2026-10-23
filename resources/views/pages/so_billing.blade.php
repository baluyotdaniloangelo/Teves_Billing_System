@extends('layouts.layout')

@section('content')
<main id="main" class="main so-list-page">
    <section class="section" aria-labelledby="so-list-title">
        @include('pages.so_billing.partials.so_list')
        @include('pages.so_billing.modals.create_modal')
        @include('pages.so_billing.modals.delete_modal')
    </section>
</main>

@include('pages.so_billing.scripts.customized_script')
@endsection

@push('page_scripts')
    @include('pages.so_billing.scripts.plugins_script')
    @include('pages.so_billing.scripts.so_billing_script')
@endpush
