@if(session()->has('loginID'))
<script id="user-presence-client" src="{{ asset('js/user-presence.js') }}?v={{ filemtime(public_path('js/user-presence.js')) }}" data-heartbeat-url="{{ route('user.presence.heartbeat') }}" defer></script>
@if(($data->user_type ?? null) === 'SUAdmin')
<link rel="stylesheet" href="{{ asset('style/online-users-shortcut.css') }}?v={{ filemtime(public_path('style/online-users-shortcut.css')) }}">
<script src="{{ asset('js/online-users-shortcut.js') }}?v={{ filemtime(public_path('js/online-users-shortcut.js')) }}" defer></script>
@endif
@endif
