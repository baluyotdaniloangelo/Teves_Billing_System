@php
    $userManagementUrls = [
        'canMonitorPresence' => $data->user_type === 'SUAdmin' && config('ui_visibility.online_status', true),
        'list' => route('UserList'), 'info' => route('user_info'),
        'timezone' => config('app.timezone'),
        'create' => route('create_user_post'), 'update' => route('update_user_post'),
        'delete' => route('delete_user_confirmed'),
        'branchList' => route('getUserBranchAccess'), 'branchSave' => route('add_user_access_post'),
        'categoryList' => route('getUserProductAccess'), 'categorySave' => route('add_user_product_access_post'),
    ];
@endphp
<script id="user-management-config" type="application/json">@json($userManagementUrls)</script>
<script src="{{ asset('Datatables/2.0.8/js/dataTables.js') }}"></script>
<script src="{{ asset('Datatables/responsive/3.0.2/js/dataTables.responsive.js') }}"></script>
<script src="{{ asset('js/user-management.js') }}?v={{ filemtime(public_path('js/user-management.js')) }}"></script>
