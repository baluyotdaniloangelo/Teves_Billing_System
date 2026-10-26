<section class="fuel-panel um-directory" aria-labelledby="user-directory-title">
    <div class="fuel-panel-head"><div><h2 id="user-directory-title">Team directory</h2><p>Account information and permissions in one place</p></div><span class="um-section-icon"><i class="bi bi-person-lines-fill" aria-hidden="true"></i></span></div>
    <div class="um-toolbar">
        <label class="um-search" for="user-search"><i class="bi bi-search" aria-hidden="true"></i><input type="search" id="user-search" placeholder="Search name, username, or email" autocomplete="off"></label>
        <label for="user-role-filter"><span>Role</span><select id="user-role-filter"><option value="">All roles</option>@if($data->user_type === 'SUAdmin')<option value="SUAdmin">Super admin</option>@endif<option value="Admin">Admin</option><option value="Supervisor">Supervisor</option><option value="Accounting_Staff">Accounting staff</option><option value="Encoder">Encoder</option></select></label>
        <label for="user-status-filter"><span>Account</span><select id="user-status-filter"><option value="">All statuses</option><option value="Active">Active</option><option value="Inactive">Inactive</option></select></label>
        @if($data->user_type === 'SUAdmin' && config('ui_visibility.online_status', false))
        <label for="user-presence-filter"><span>Presence</span><select id="user-presence-filter"><option value="">Everyone</option><option value="online" @if(request('presence') === 'online') selected @endif>Online now</option><option value="offline" @if(request('presence') === 'offline') selected @endif>Offline</option></select></label>
        @endif
        <button type="button" id="user-reset-filters" class="fuel-button fuel-button-small"><i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i> Reset</button>
    </div>
    <div class="um-table-wrap">
        <table class="table display nowrap" id="userList" aria-label="User accounts">
            <thead><tr><th>#</th><th>Name</th><th>Job title</th><th>Username</th><th>Role</th><th>Account status</th>@if($data->user_type === 'SUAdmin' && config('ui_visibility.online_status', false))<th>Presence</th><th>Last seen</th>@endif<th>Email address</th><th>Created</th><th>Updated</th><th>Actions</th></tr></thead>
            <tbody></tbody>
        </table>
    </div>
    @if($data->user_type === 'SUAdmin' && config('ui_visibility.online_status', false))
    <div class="um-presence-monitor"><span><i class="bi bi-broadcast" aria-hidden="true"></i> <span id="user-monitor-state" role="status" aria-live="polite">Presence refreshes every 30 seconds.</span></span><span id="user-monitor-updated"></span></div>
    <p class="um-directory-note"><i class="bi bi-info-circle" aria-hidden="true"></i> Online means a recent connection to the system. A user can remain signed in after going offline.</p>
    @else
    <p class="um-directory-note"><i class="bi bi-info-circle" aria-hidden="true"></i> Use the branch and product icons to manage each user's access.</p>
    @endif
</section>
