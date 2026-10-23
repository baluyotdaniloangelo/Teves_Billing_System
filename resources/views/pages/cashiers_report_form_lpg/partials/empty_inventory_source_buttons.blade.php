@if(($withColumnActions ?? false) && ($withActions ?? false))
    <div class="d-flex flex-nowrap justify-content-center align-items-center gap-1 lpg-summary-source-buttons">
        <button type="button" class="btn btn-sm btn-light lpg-empty-column-add" data-quantity-field="{{ $quantityField }}" aria-label="Add or edit {{ \App\Models\CashiersReportModel_LPG_EmptyInventory::QUANTITY_FIELDS[$quantityField] }}"><i class="bi bi-plus-circle me-1" aria-hidden="true"></i>Add</button>
        <button type="button" class="btn btn-sm btn-light lpg-empty-column-view" data-quantity-field="{{ $quantityField }}" aria-label="View {{ \App\Models\CashiersReportModel_LPG_EmptyInventory::QUANTITY_FIELDS[$quantityField] }}"><i class="bi bi-eye me-1" aria-hidden="true"></i>View</button>
    </div>
@endif
