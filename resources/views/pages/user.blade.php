@extends('layouts.layout')
@section('body_class', 'fuel-dashboard-shell user-management-shell')

@push('page_styles')
<link rel="stylesheet" href="{{ asset('style/fuel-dashboard.css') }}?v={{ filemtime(public_path('style/fuel-dashboard.css')) }}">
<link rel="stylesheet" href="{{ asset('style/user-management.css') }}?v={{ filemtime(public_path('style/user-management.css')) }}">
@endpush

@section('content')
<main id="main" class="main fuel-page um-page">
    @include('pages.user.partials.header')
    <div id="user-feedback" class="um-feedback" role="status" aria-live="polite" hidden></div>
    @include('pages.user.partials.user_table')
</main>
@include('pages.user.modals.create')
@include('pages.user.modals.edit')
@include('pages.user.modals.delete')
@include('pages.user.modals.access', ['type' => 'branch', 'label' => 'Branch', 'icon' => 'building'])
@include('pages.user.modals.access', ['type' => 'category', 'label' => 'Product category', 'icon' => 'box-seam'])
@endsection

@push('page_scripts')
    @include('pages.user.scripts.index')
@endpush
