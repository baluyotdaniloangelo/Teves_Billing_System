<div class="fuel-title-row">
    <div>
        <p class="fuel-eyebrow">ADMINISTRATION / PEOPLE & ACCESS</p>
        <h1>User <span>Management</span></h1>
        <p class="fuel-subtitle">Manage your team, account details, and access across the business.</p>
    </div>
    <div class="fuel-actions">
        @if($data->user_type === 'SUAdmin' && config('ui_visibility.activity_center', true))<a class="fuel-button" href="{{ route('user.activity') }}"><i class="bi bi-clock-history" aria-hidden="true"></i> Activity history</a>@endif
        <button type="button" class="fuel-button fuel-button-primary" data-bs-toggle="modal" data-bs-target="#CreateUserModal"><i class="bi bi-plus-lg" aria-hidden="true"></i> Add user</button>
    </div>
</div>
<section class="fuel-stats um-stats" aria-label="User account overview">
    <article class="fuel-stat"><div class="fuel-stat-label">Total users <i class="bi bi-people" aria-hidden="true"></i></div><div class="fuel-stat-value" data-user-count="total">{{ $summary['total'] }}</div><p class="fuel-stat-foot">Accounts you can manage</p></article>
    @if($data->user_type === 'SUAdmin' && config('ui_visibility.online_status', true))
    <article class="fuel-stat"><div class="fuel-stat-label">Online now <i class="bi bi-broadcast" aria-hidden="true"></i></div><div class="fuel-stat-value" data-user-count="online">{{ $summary['online'] ?? '—' }}</div><p class="fuel-stat-foot"><i class="fuel-dot fuel-green"></i> Seen in the last <span data-online-window>{{ $summary['online_window_minutes'] ?? 5 }}</span> min</p></article>
    @else
    <article class="fuel-stat"><div class="fuel-stat-label">Active accounts <i class="bi bi-person-check" aria-hidden="true"></i></div><div class="fuel-stat-value" data-user-count="active">{{ $summary['active'] }}</div><p class="fuel-stat-foot">Ready to sign in</p></article>
    @endif
    <article class="fuel-stat"><div class="fuel-stat-label">Administrators <i class="bi bi-shield-check" aria-hidden="true"></i></div><div class="fuel-stat-value" data-user-count="administrators">{{ $summary['administrators'] }}</div><p class="fuel-stat-foot">Admin & super admin roles</p></article>
    <article class="fuel-stat"><div class="fuel-stat-label">Assigned by branch <i class="bi bi-building" aria-hidden="true"></i></div><div class="fuel-stat-value" data-user-count="by_branch">{{ $summary['by_branch'] }}</div><p class="fuel-stat-foot">Access to selected branches</p></article>
</section>
