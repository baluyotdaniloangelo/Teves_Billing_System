<li class="nav-item dropdown ou-menu" data-online-users data-online-url="{{ route('user.presence.online') }}">
    <button type="button" id="online-users-shortcut" class="nav-link nav-icon ou-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" aria-controls="online-users-menu" aria-label="Online users" title="Online users">
        <i class="bi bi-people" aria-hidden="true"></i><span class="ou-toggle-label">Online</span><span class="ou-count" data-online-count aria-hidden="true">—</span>
    </button>
    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow ou-dropdown" id="online-users-menu" aria-labelledby="online-users-shortcut">
        <div class="ou-head"><div><span class="ou-eyebrow">TEAM ACTIVITY</span><h2>Online users <span class="ou-heading-count" data-online-heading-count></span></h2></div><button type="button" class="ou-refresh" data-online-refresh aria-label="Refresh online users" title="Refresh online users"><i class="bi bi-arrow-clockwise" aria-hidden="true"></i></button></div>
        <p class="ou-status" data-online-status role="status" aria-live="polite">Checking who is online…</p>
        <ul class="ou-list" data-online-list aria-label="Currently online users"></ul>
        <div class="ou-foot"><small data-online-updated>Updates every 30 seconds</small><a href="{{ route('user', ['presence' => 'online']) }}">View all online <i class="bi bi-arrow-right" aria-hidden="true"></i></a></div>
    </div>
</li>
