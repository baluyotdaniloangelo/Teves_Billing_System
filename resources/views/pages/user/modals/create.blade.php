<div class="modal fade um-modal" id="CreateUserModal" tabindex="-1" aria-labelledby="create-user-title" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><div class="um-modal-heading"><span class="um-modal-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></span><div><p>TEAM MANAGEMENT</p><h2 class="modal-title" id="create-user-title">Add a new user</h2></div></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="CreateUserform" class="um-account-form" data-mode="create" method="post" action="{{ route('create_user_post') }}" novalidate>
            @csrf
            <div class="modal-body"><div class="um-form-alert" role="alert" hidden></div>@include('pages.user.forms.fields', ['mode' => 'create'])</div>
            <div class="modal-footer"><span class="um-required-note">* Required fields</span><button type="button" class="fuel-button" data-bs-dismiss="modal">Cancel</button><button type="submit" class="fuel-button fuel-button-primary"><i class="bi bi-check2" aria-hidden="true"></i> Create user</button></div>
        </form>
    </div></div>
</div>
