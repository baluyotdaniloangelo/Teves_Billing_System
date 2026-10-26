@extends('layouts.layout')
@section('body_class', 'fuel-dashboard-shell ua-dashboard-shell')

@push('page_styles')
<link rel="stylesheet" href="{{ asset('style/fuel-dashboard.css') }}?v={{ filemtime(public_path('style/fuel-dashboard.css')) }}">
<link rel="stylesheet" href="{{ asset('style/user-activity.css') }}?v={{ filemtime(public_path('style/user-activity.css')) }}">
@endpush

@section('content')
<main id="main" class="main fuel-page ua-page">
    <div class="fuel-title-row ua-title-row">
        <div>
            <h1><span>Activity</span> Center</h1>
            <p class="fuel-subtitle">Monitor account access and changes across your billing system.</p>
        </div>
    </div>

    <section class="fuel-stats ua-stats" aria-label="Filtered activity totals">
        <article class="fuel-stat ua-stat-events">
            <span class="ua-stat-icon"><i class="bi bi-file-earmark-text-fill" aria-hidden="true"></i></span>
            <div class="ua-stat-copy"><div class="fuel-stat-label">Recorded events</div><div class="fuel-stat-value" data-activity-count="events">—</div></div>
            <i class="bi bi-bar-chart-fill ua-stat-mark" aria-hidden="true"></i>
        </article>
        <article class="fuel-stat ua-stat-users">
            <span class="ua-stat-icon"><i class="bi bi-people-fill" aria-hidden="true"></i></span>
            <div class="ua-stat-copy"><div class="fuel-stat-label" title="Users with recorded activity within the selected filters">Active users</div><div class="ua-stat-user-value"><div class="fuel-stat-value" data-activity-count="users">—</div><a class="ua-online-state" href="{{ route('user', ['presence' => 'online']) }}" id="activity-online-state"><span class="ua-online-dot" aria-hidden="true"></span><span id="activity-online-count">Checking online status</span></a></div></div>
        </article>
        <article class="fuel-stat ua-stat-changes">
            <span class="ua-stat-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 8c3 3 13 3 16 0v4c0 1.7-3.6 3-8 3s-8-1.3-8-3V8Zm0 7c3 3 13 3 16 0v4c0 1.7-3.6 3-8 3s-8-1.3-8-3v-4Z"/></svg></span>
            <div class="ua-stat-copy"><div class="fuel-stat-label">Record changes</div><div class="fuel-stat-value" data-activity-count="changes">—</div></div>
            <i class="bi bi-bar-chart-fill ua-stat-mark" aria-hidden="true"></i>
        </article>
        <article class="fuel-stat ua-stat-signins">
            <span class="ua-stat-icon"><i class="bi bi-shield-fill-check" aria-hidden="true"></i></span>
            <div class="ua-stat-copy"><div class="fuel-stat-label">Successful sign-ins</div><div class="fuel-stat-value" data-activity-count="signins">—</div></div>
            <i class="bi bi-bar-chart-fill ua-stat-mark" aria-hidden="true"></i>
        </article>
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
