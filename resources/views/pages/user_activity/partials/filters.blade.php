<section class="ua-filter-panel" aria-labelledby="activity-filters-title">
    <div class="ua-filter-heading"><h2 id="activity-filters-title"><i class="bi bi-funnel" aria-hidden="true"></i> Filters</h2><a href="{{ route('user.activity') }}" class="ua-reset">Reset filters</a></div>
    <form class="ua-filters" id="activity-filters" method="get" action="{{ route('user.activity') }}">
        <label for="activity-user">User<select id="activity-user" name="user_id"><option value="">All users</option>@foreach($users as $user)<option value="{{ $user->user_id }}" @if((string)($filters['user_id'] ?? '') === (string)$user->user_id) selected @endif>{{ $user->user_real_name }}{{ $user->deleted_at ? ' (deleted account)' : '' }}</option>@endforeach
        @if(!empty($filters['user_id']) && !$users->contains('user_id', (int)$filters['user_id']))<option value="{{ $filters['user_id'] }}" selected>User #{{ $filters['user_id'] }}</option>@endif</select></label>
        <label for="activity-start">From<input id="activity-start" name="start_date" type="date" value="{{ $filters['start_date'] }}" required></label>
        <label for="activity-end">To<input id="activity-end" name="end_date" type="date" value="{{ $filters['end_date'] }}" required></label>
        <label for="activity-module">Module<select id="activity-module" name="module"><option value="">All modules</option>@foreach($modules as $module)<option value="{{ $module }}" @if(($filters['module'] ?? '') === $module) selected @endif>{{ $module }}</option>@endforeach</select></label>
        <label for="activity-action">Action<select id="activity-action" name="action"><option value="">All actions</option>@foreach($actions as $key => $label)<option value="{{ $key }}" @if(($filters['action'] ?? '') === $key) selected @endif>{{ $label }}</option>@endforeach</select></label>
        <button type="submit" class="fuel-button fuel-button-primary"><i class="bi bi-funnel" aria-hidden="true"></i> Apply filters</button>
    </form>
</section>
