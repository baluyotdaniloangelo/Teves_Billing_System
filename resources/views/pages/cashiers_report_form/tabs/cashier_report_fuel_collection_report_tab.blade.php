<div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3 px-4">
            <small class="text-muted text-uppercase fw-semibold">Fuel Cashier Report</small>
            <h5 class="fw-bold mb-0"><i class="bi bi-wallet2 text-success me-2"></i>Collection Report</h5>
        </div>
        <div class="card-body p-4">
            <ul class="nav nav-tabs mb-3" id="fuelCashReportSubTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="fuel-non-cash-payment-tab" data-bs-toggle="tab" data-bs-target="#fuel-non-cash-payment" type="button" role="tab" aria-controls="fuel-non-cash-payment" aria-selected="true">
                        <i class="bi bi-credit-card me-1"></i>Non-Cash Payment
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="fuel-cash-on-hand-tab" data-bs-toggle="tab" data-bs-target="#fuel-cash-on-hand" type="button" role="tab" aria-controls="fuel-cash-on-hand" aria-selected="false">
                        <i class="bi bi-cash-stack me-1"></i>Cash on Hand
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="fuelCashReportSubTabsContent">
                <div class="tab-pane fade show active" id="fuel-non-cash-payment" role="tabpanel" aria-labelledby="fuel-non-cash-payment-tab">
                    <section class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white border-0 px-4 pt-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-3"><span class="rounded-3 bg-primary-subtle text-primary p-3"><i class="bi bi-credit-card fs-4"></i></span><div><h5 class="fw-bold mb-1">Non-Cash Payments</h5><p class="text-muted small mb-0">Manage checks, card, GCash, and Limitless payments.</p></div></div>
                            <button type="button" class="btn btn-success rounded-3 fuel-report-action-btn" id="add_non_cash_payment" data-bs-toggle="modal" data-bs-target="#nonCashPaymentModal"><i class="bi bi-plus-circle me-2"></i>Add Payment</button>
                        </div>
                        <div class="card-body p-4"><div class="table-responsive"><table class="table table-hover align-middle w-100" id="non_cash_payments_table"><thead class="table-light"><tr><th><i class="bi bi-credit-card" aria-hidden="true"></i>Payment Type</th><th class="text-end"><i class="bi bi-cash-stack" aria-hidden="true"></i>Amount</th><th><i class="bi bi-person" aria-hidden="true"></i>Name</th><th><i class="bi bi-telephone" aria-hidden="true"></i>Number</th><th><i class="bi bi-file-earmark-text" aria-hidden="true"></i>Reference No.</th><th class="text-center"><i class="bi bi-three-dots" aria-hidden="true"></i>Action</th></tr></thead><tbody></tbody></table></div></div>
                    </section>
                </div>
                <div class="tab-pane fade" id="fuel-cash-on-hand" role="tabpanel" aria-labelledby="fuel-cash-on-hand-tab">
                    <section class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white border-0 px-4 pt-4"><div class="d-flex align-items-center gap-3"><span class="rounded-3 bg-success-subtle text-success p-3"><i class="bi bi-cash-stack fs-4"></i></span><div><h5 class="fw-bold mb-1">Cash on Hand</h5><p class="text-muted small mb-0">Count bills and coins, then record the cash drop.</p></div></div></div>
                        <div class="card-body p-4">
                            <form id="cash_on_hand_form" class="needs-validation" novalidate>
                                <input type="hidden" id="cash_on_hand_id" value="0">
                                <div class="mx-auto mb-3" style="max-width:760px;">
                                    <div class="card border rounded-3 overflow-hidden mb-0 fuel-cash-count-list">
                                        <div class="card-header bg-light border-0 px-3 py-2">
                                            <h6 class="fw-semibold mb-0"><i class="bi bi-cash-stack text-success me-2" aria-hidden="true"></i>Cash On Hand</h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="row g-0 align-items-center bg-light border-bottom px-3 py-2 small fw-semibold text-muted">
                                                <div class="col-12 col-sm-4"><i class="bi bi-cash me-1" aria-hidden="true"></i>Denomination</div>
                                                <div class="col-6 col-sm-4 text-center"><i class="bi bi-123 me-1" aria-hidden="true"></i>Quantity</div>
                                                <div class="col-6 col-sm-4 text-end"><i class="bi bi-currency-exchange me-1" aria-hidden="true"></i>Amount</div>
                                            </div>
                                            @php
                                                $fuelCashDenominations = [
                                                    ['label' => '1,000.00', 'input' => 'cash_on_hand_one_thousand', 'output' => 'cash_on_hand_one_thousand_total'],
                                                    ['label' => '500.00', 'input' => 'cash_on_hand_five_hundred', 'output' => 'cash_on_hand_five_hundred_total'],
                                                    ['label' => '200.00', 'input' => 'cash_on_hand_two_hundred', 'output' => 'cash_on_hand_two_hundred_total'],
                                                    ['label' => '100.00', 'input' => 'cash_on_hand_one_hundred', 'output' => 'cash_on_hand_one_hundred_total'],
                                                    ['label' => '50.00', 'input' => 'cash_on_hand_fifty', 'output' => 'cash_on_hand_fifty_total'],
                                                    ['label' => '20.00', 'input' => 'cash_on_hand_twenty', 'output' => 'cash_on_hand_twenty_total'],
                                                    ['label' => '10.00', 'input' => 'cash_on_hand_ten', 'output' => 'cash_on_hand_ten_total'],
                                                    ['label' => '5.00', 'input' => 'cash_on_hand_five', 'output' => 'cash_on_hand_five_total'],
                                                    ['label' => '1.00', 'input' => 'cash_on_hand_one', 'output' => 'cash_on_hand_one_total'],
                                                    ['label' => '0.25', 'input' => 'cash_on_hand_twenty_five_cent', 'output' => 'cash_on_hand_twenty_five_cent_total'],
                                                ];
                                            @endphp
                                            @foreach ($fuelCashDenominations as $denomination)
                                                <div class="row g-0 align-items-center border-bottom px-3 py-2 fuel-cash-denomination-row">
                                                    <div class="col-12 col-sm-4 mb-2 mb-sm-0">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-success bg-opacity-10 text-success flex-shrink-0" style="width:34px;height:34px;">
                                                                <i class="bi bi-cash" aria-hidden="true"></i>
                                                            </span>
                                                            <span class="fw-semibold text-nowrap">₱ {{ $denomination['label'] }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-sm-4 d-flex justify-content-center">
                                                        <label for="{{ $denomination['input'] }}" class="visually-hidden">Quantity of ₱ {{ $denomination['label'] }} denomination</label>
                                                        <input type="number" class="form-control form-control-sm cash-on-hand-count" id="{{ $denomination['input'] }}" min="0" step="1" value="0" required style="width:150px;max-width:100%;" aria-label="Quantity of {{ $denomination['label'] }} denomination">
                                                    </div>
                                                    <div class="col-6 col-sm-4 text-end fw-semibold">₱ <span id="{{ $denomination['output'] }}">0.00</span></div>
                                                </div>
                                            @endforeach

                                            <div class="row g-0 align-items-center px-3 py-3 fuel-cash-total-row">
                                                <div class="col-8">
                                                    <div class="d-flex align-items-center justify-content-end gap-2 fw-semibold"><i class="bi bi-cash-stack text-success" aria-hidden="true"></i>Total Cash on Hand:</div>
                                                </div>
                                                <div class="col-4 text-end fw-bold text-success">₱ <span id="cash_on_hand_total_amount">0.00</span></div>
                                            </div>

                                            <div class="row g-0 align-items-center border-top px-3 py-3">
                                                <div class="col-12 col-sm-4 mb-2 mb-sm-0">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-success bg-opacity-10 text-success flex-shrink-0" style="width:34px;height:34px;"><i class="bi bi-cash" aria-hidden="true"></i></span>
                                                        <label for="cash_on_hand_drop" class="fw-semibold text-nowrap mb-0">Cash Drop:</label>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-sm-4 d-flex justify-content-center">
                                                    <input type="number" class="form-control form-control-sm text-end" id="cash_on_hand_drop" name="cash_drop" min="0" step="0.01" value="0" required style="width:150px;max-width:100%;" aria-label="Cash Drop amount">
                                                </div>
                                                <div class="col-6 col-sm-4 text-end fw-semibold">₱ <span id="cash_on_hand_drop_amount">0.00</span></div>
                                            </div>

                                            <div class="row g-0 align-items-center px-3 py-3 fuel-cash-actual-row">
                                                <div class="col-8">
                                                    <div class="d-flex align-items-center justify-content-end gap-2 fw-semibold"><i class="bi bi-wallet2" aria-hidden="true"></i>Total Actual Cash:</div>
                                                </div>
                                                <div class="col-4 text-end fw-bold">₱ <span id="cash_on_hand_total_actual">0.00</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mx-auto mt-2" style="max-width:760px">
                                    <button type="button" class="btn btn-success fuel-report-action-btn" id="save_cash_on_hand"><i class="bi bi-save me-2"></i>Save Cash Count</button>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
