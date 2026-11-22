@if($canManage || $canDelete || $userType === 'SUAdmin')
<div class="action_table_menu_Product">
    @if($userType === 'SUAdmin')
        <a href="#" class="btn-circle bi bi-lock btn_icon_table btn_icon_table_lock" title="Lock settings" aria-label="Lock settings" onclick="return LockReceivable({{ (int) $row->receivable_id }})"></a>
    @endif
    @if($canManage)
        <a href="{{ route('receivable_from_billing_form', ['receivable_id' => $row->receivable_id, 'tab' => 'payment']) }}" class="btn-circle bi bi-cash-stack btn_icon_table btn_icon_table_view" title="Add payment" aria-label="Add payment"></a>
        <a href="{{ route('receivable_from_billing_form', ['receivable_id' => $row->receivable_id, 'tab' => 'product']) }}" class="btn-circle bi bi-pencil-fill btn_icon_table btn_icon_table_edit" title="Update" aria-label="Update receivable"></a>
    @endif
    @if($canDelete)
        <a href="#" data-id="{{ $row->receivable_id }}" class="btn-circle bi bi-trash3-fill btn_icon_table btn_icon_table_delete js-delete-receivable" title="Delete" aria-label="Delete receivable"></a>
    @endif
</div>
@endif
