@extends('layouts.layout')
@section('body_class', 'fuel-dashboard-shell')

@push('page_styles')
<link rel="stylesheet" href="{{ asset('style/fuel-dashboard.css') }}?v={{ filemtime(public_path('style/fuel-dashboard.css')) }}">
<link rel="stylesheet" href="{{ asset('style/user-activity.css') }}?v={{ filemtime(public_path('style/user-activity.css')) }}">
@endpush

@section('content')
<main id="main" class="main fuel-page ua-page">
    <div class="fuel-title-row"><div><p class="fuel-eyebrow">ADMINISTRATION / AUDIT HISTORY</p><h1>User <span>Activity</span></h1><p class="fuel-subtitle">Review recorded actions, account access, and changes across the system.</p></div><a class="fuel-button" href="{{ route('user') }}"><i class="bi bi-arrow-left" aria-hidden="true"></i> User management</a></div>
    <section class="fuel-stats ua-stats" aria-label="Filtered activity totals">
        <article class="fuel-stat"><div class="fuel-stat-label">Recorded events <i class="bi bi-clock-history" aria-hidden="true"></i></div><div class="fuel-stat-value" data-activity-count="events">—</div><p class="fuel-stat-foot">Within the selected filters</p></article>
        <article class="fuel-stat"><div class="fuel-stat-label">Users with activity <i class="bi bi-people" aria-hidden="true"></i></div><div class="fuel-stat-value" data-activity-count="users">—</div><p class="fuel-stat-foot">Distinct user accounts</p></article>
        <article class="fuel-stat"><div class="fuel-stat-label">Record changes <i class="bi bi-pencil-square" aria-hidden="true"></i></div><div class="fuel-stat-value" data-activity-count="changes">—</div><p class="fuel-stat-foot">Created, updated, deleted, restored</p></article>
        <article class="fuel-stat"><div class="fuel-stat-label">Successful sign-ins <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i></div><div class="fuel-stat-value" data-activity-count="signins">—</div><p class="fuel-stat-foot">Verified account logins</p></article>
    </section>
    @include('pages.user_activity.partials.filters')
    <div id="activity-notice" class="ua-notice" role="status" aria-live="polite" hidden></div>
    @include('pages.user_activity.partials.history')
</main>
@include('pages.user_activity.modals.details')
@endsection

@push('page_scripts')
    @include('pages.user_activity.scripts.index')
@endpush
