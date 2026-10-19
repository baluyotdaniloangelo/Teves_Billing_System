@php
    $capacityPrefix = $capacityPrefix ?? '';
    $capacityColumnClass = $capacityColumnClass ?? 'col-6';
@endphp
<div class="row g-2 mb-2 justify-content-center">
    <div class="{{ $capacityColumnClass }}">
        <label for="{{ $capacityPrefix }}product_capacity" class="form-label small fw-semibold mb-1">
            <i class="bi bi-rulers me-1 text-success" aria-hidden="true"></i>Capacity (Optional)
        </label>
        <input type="number" class="form-control form-control-sm rounded-3" name="{{ $capacityPrefix }}product_capacity" id="{{ $capacityPrefix }}product_capacity" min="0.001" max="9999999.999" step="0.001" placeholder="e.g. 1, 2.7, 11">
        <div class="invalid-feedback" id="{{ $capacityPrefix }}product_capacityError">Enter a capacity greater than zero, or leave both capacity fields blank.</div>
    </div>
</div>
<div class="row g-2 mb-2 justify-content-center">
    <div class="{{ $capacityColumnClass }}">
        <label for="{{ $capacityPrefix }}product_capacity_unit" class="form-label small fw-semibold mb-1">Capacity Unit</label>
        <select class="form-select form-select-sm rounded-3" name="{{ $capacityPrefix }}product_capacity_unit" id="{{ $capacityPrefix }}product_capacity_unit">
            <option value="">No capacity unit</option>
            <option value="kg">Kilogram | kg</option>
            <option value="g">Gram | g</option>
        </select>
        <div class="form-text">Package or cylinder size, such as 11 kg or 170 g. Quantity is the number of items.</div>
        <div class="invalid-feedback" id="{{ $capacityPrefix }}product_capacity_unitError">Select a unit when entering a capacity.</div>
    </div>
</div>
