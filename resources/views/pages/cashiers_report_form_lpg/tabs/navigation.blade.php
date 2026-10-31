@php
    $lpgMainTabs = [
        ['id' => 'lpg-sell-out-tab', 'target' => 'lpg-sell-out', 'label' => 'LPG Sell-out', 'icon' => 'bi-table'],
        ['id' => 'lpg-kitchen-tab', 'target' => 'lpg-kitchen', 'label' => 'Kitchen Products & Accessories', 'icon' => 'bi-shop'],
        ['id' => 'lpg-empty-inventory-tab', 'target' => 'lpg-empty-inventory', 'label' => 'Empty Cylinders / Canisters', 'icon' => 'bi-box-seam'],
        ['id' => 'lpg_accounts_recievable-tab', 'target' => 'lpg_accounts_recievable', 'label' => 'Accounts Receivables Collection', 'icon' => 'bi-person-lines-fill'],
        ['id' => 'lpg_expenses-tab', 'target' => 'lpg_expenses', 'label' => 'Expenses', 'icon' => 'bi-cash-stack'],
        ['id' => 'collection_report-tab', 'target' => 'collection_report', 'label' => 'Cash Collection', 'icon' => 'bi-cash-coin'],
        ['id' => 'inventory-tab', 'target' => 'inventory_tab', 'label' => 'Inventory & Transactions', 'icon' => 'bi-boxes'],
    ];
@endphp
<ul class="nav nav-pills gap-2 p-3 bg-light rounded-4 shadow-sm mb-3" id="LPGTab" role="tablist">
    @foreach($lpgMainTabs as $tab)
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $loop->first ? 'active' : '' }} rounded-3 py-3 fw-semibold shadow-sm"
                    id="{{ $tab['id'] }}"
                    data-bs-toggle="tab"
                    data-bs-target="#{{ $tab['target'] }}"
                    type="button"
                    role="tab"
                    aria-controls="{{ $tab['target'] }}"
                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                <i class="bi {{ $tab['icon'] }} me-2" aria-hidden="true"></i>{{ $tab['label'] }}
            </button>
        </li>
    @endforeach
</ul>
