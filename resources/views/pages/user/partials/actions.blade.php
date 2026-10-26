<div class="um-row-actions">
    @if(($canViewActivity ?? false) && config('ui_visibility.activity_center', true))
    <a href="{{ route('user.activity', ['user_id' => $user->user_id]) }}" class="um-history-link" title="Activity history" aria-label="Activity history for {{ $user->user_real_name }}"><i class="bi bi-clock-history" aria-hidden="true"></i></a>
    @endif
    <button type="button" data-user-action="edit" data-id="{{ $user->user_id }}" title="Edit user" aria-label="Edit {{ $user->user_real_name }}"><i class="bi bi-pencil-square" aria-hidden="true"></i></button>
    @if($user->user_branch_access_type !== 'ALL')
    <button type="button" data-user-action="branch" data-id="{{ $user->user_id }}" title="Branch access" aria-label="Branch access for {{ $user->user_real_name }}"><i class="bi bi-building" aria-hidden="true"></i></button>
    @endif
    <button type="button" data-user-action="category" data-id="{{ $user->user_id }}" title="Product category access" aria-label="Product access for {{ $user->user_real_name }}"><i class="bi bi-box-seam" aria-hidden="true"></i></button>
    <button type="button" data-user-action="delete" data-id="{{ $user->user_id }}" class="um-action-danger" title="{{ (int)$user->user_id === (int)session('loginID') ? 'You cannot delete your own account' : 'Delete user' }}" aria-label="Delete {{ $user->user_real_name }}" @if((int)$user->user_id === (int)session('loginID')) disabled @endif><i class="bi bi-trash3" aria-hidden="true"></i></button>
</div>
