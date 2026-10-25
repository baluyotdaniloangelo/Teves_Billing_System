<div class="modal fade um-modal" id="UpdateUserModal" tabindex="-1" aria-labelledby="edit-user-title" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><div class="um-modal-heading"><span class="um-modal-icon"><i class="bi bi-pencil-square" aria-hidden="true"></i></span><div><p>TEAM MANAGEMENT</p><h2 class="modal-title" id="edit-user-title">Edit user account</h2></div></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="UpdateUserform" class="um-account-form" data-mode="edit" method="post" action="{{ route('update_user_post') }}" novalidate>
            @csrf
            <input type="hidden" name="userID">
            <div class="modal-body"><div class="um-form-alert" role="alert" hidden></div>@include('pages.user.forms.fields', ['mode' => 'edit'])</div>
            <div class="modal-footer"><span class="um-required-note">* Required fields</span><button type="button" class="fuel-button" data-bs-dismiss="modal">Cancel</button><button type="submit" class="fuel-button fuel-button-primary" disabled><i class="bi bi-check2" aria-hidden="true"></i> Save changes</button></div>
        </form>
    </div></div>
</div>
