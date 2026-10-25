<div class="modal fade um-modal" id="{{ $type }}-access-modal" tabindex="-1" aria-labelledby="{{ $type }}-access-title" aria-hidden="true" data-access-type="{{ $type }}">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><div class="um-modal-heading"><span class="um-modal-icon"><i class="bi bi-{{ $icon }}" aria-hidden="true"></i></span><div><p>PERMISSION SETTINGS</p><h2 class="modal-title" id="{{ $type }}-access-title">{{ $label }} access</h2></div></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <div class="modal-body"><div class="um-form-alert" role="alert" hidden></div>
            <dl class="um-user-summary"><div><dt>Full name</dt><dd data-user-detail="user_real_name"></dd></div><div><dt>Username</dt><dd data-user-detail="user_name"></dd></div><div><dt>Role</dt><dd data-user-detail="user_type"></dd></div></dl>
            <p class="um-access-note">@if($type === 'branch')Select the branches this user can access. For an account with all-branch access, these assignments apply only after switching to “Assign by branch”.@else Select the product categories assigned to this user. Existing role rules continue to determine where these assignments apply.@endif</p>
            <div class="um-access-toolbar"><label class="um-search" for="{{ $type }}-access-search"><i class="bi bi-search" aria-hidden="true"></i><input type="search" id="{{ $type }}-access-search" data-access-search placeholder="Search {{ strtolower($label) }}" autocomplete="off"></label><span class="um-selected-count" data-selected-count>0 selected</span></div>
            <div class="um-selection-actions"><button type="button" data-select-access="all">Select visible</button><button type="button" data-select-access="none">Clear visible</button></div>
            <div class="um-access-list" data-access-list aria-label="{{ $label }} selection"></div>
            <p class="um-empty" data-access-empty hidden>No matching {{ $type === 'branch' ? 'branches' : 'categories' }}.</p>
        </div>
        <div class="modal-footer"><span class="um-required-note">Selections stay checked while searching.</span><button type="button" class="fuel-button" data-bs-dismiss="modal">Cancel</button><button type="button" class="fuel-button fuel-button-primary" data-save-access><i class="bi bi-check2" aria-hidden="true"></i> Save access</button></div>
    </div></div>
</div>
