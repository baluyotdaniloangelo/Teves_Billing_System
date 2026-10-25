<div class="modal fade um-modal" id="UserDeleteModal" tabindex="-1" aria-labelledby="delete-user-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
        <div class="modal-header"><div class="um-modal-heading"><span class="um-modal-icon um-danger-icon"><i class="bi bi-person-x" aria-hidden="true"></i></span><div><p>ACCOUNT REMOVAL</p><h2 class="modal-title" id="delete-user-title">Delete this user?</h2></div></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <div class="modal-body"><div class="um-form-alert" role="alert" hidden></div><p class="um-delete-note">This account will no longer be available for signing in. Review the account before continuing.</p><dl class="um-user-summary"><div><dt>Full name</dt><dd data-user-detail="user_real_name"></dd></div><div><dt>Username</dt><dd data-user-detail="user_name"></dd></div><div><dt>Role</dt><dd data-user-detail="user_type"></dd></div></dl></div>
        <div class="modal-footer"><button type="button" class="fuel-button" data-bs-dismiss="modal">Keep user</button><button type="button" id="deleteUserConfirmed" class="fuel-button um-button-danger"><i class="bi bi-trash3" aria-hidden="true"></i> Delete user</button></div>
    </div></div>
</div>
