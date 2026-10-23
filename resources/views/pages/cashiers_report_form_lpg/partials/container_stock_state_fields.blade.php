@foreach($stockStateFields as $stockPrefix => $stockLabel)
    <div class="col-md-6 lpg-container-stock-state-field" data-stock-prefix="{{ $stockPrefix }}">
        <label class="form-label fw-semibold" for="{{ $stockPrefix }}_stock_state">{{ $stockLabel }}</label>
        <select class="form-select form-select-sm" id="{{ $stockPrefix }}_stock_state" name="{{ $stockPrefix }}_stock_state">
            <option value="">Select Empty or Filled</option>
            <option value="EMPTY">Empty</option>
            <option value="FILLED">Filled</option>
        </select>
        <div class="invalid-feedback">Select whether this item is empty or filled.</div>
        <div class="form-text">Empty items update Empty Cylinders / Canisters automatically.</div>
    </div>
@endforeach
