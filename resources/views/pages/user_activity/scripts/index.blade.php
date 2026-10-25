@php
    $activityConfiguration = ['list' => route('user.activity.list'), 'detail' => url('/user/activity'), 'timezone' => config('app.timezone')];
@endphp
<script id="user-activity-config" type="application/json">@json($activityConfiguration)</script>
<script src="{{ asset('js/user-activity.js') }}?v={{ filemtime(public_path('js/user-activity.js')) }}"></script>
