<!-- LPG CASHIER REPORT COLLECTION REPORT TAB -->
<div class="tab-pane fade"
     id="collection_report"
     role="tabpanel"
     aria-labelledby="collection_report-tab">

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3 px-4">
            <small class="text-muted text-uppercase fw-semibold">LPG Cashier Report</small>
            <h5 class="fw-bold mb-0">
                <i class="bi bi-wallet2 text-success me-2"></i>
                Collection Report
            </h5>
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs mb-3" id="lpgCollectionReportSubTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active"
                            id="lpg-collection-non-cash-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#lpg-collection-non-cash"
                            type="button"
                            role="tab"
                            aria-controls="lpg-collection-non-cash"
                            aria-selected="true">
                        Non-Cash
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link"
                            id="lpg-collection-cash-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#lpg-collection-cash"
                            type="button"
                            role="tab"
                            aria-controls="lpg-collection-cash"
                            aria-selected="false">
                        Cash
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="lpgCollectionReportSubTabsContent">
                <div class="tab-pane fade show active"
                     id="lpg-collection-non-cash"
                     role="tabpanel"
                     aria-labelledby="lpg-collection-non-cash-tab">
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button"
                                class="btn btn-success shadow-sm lpg-report-action-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#CashierReportLpgNonCashPaymentModal">
                            <i class="bi bi-plus-circle me-1"></i>Add Non-Cash Payment
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle dataTable display nowrap cell-border"
                               id="LpgNonCashPaymentsTable"
                               style="width:100%;">
                            <thead>
                                <tr class="report">
                                    <th><i class="bi bi-hash" aria-hidden="true"></i>#</th>
                                    <th><i class="bi bi-credit-card" aria-hidden="true"></i>Mode of Payment</th>
                                    <th><i class="bi bi-person" aria-hidden="true"></i>Payer Name</th>
                                    <th><i class="bi bi-123" aria-hidden="true"></i>Account Number</th>
                                    <th><i class="bi bi-file-earmark-text" aria-hidden="true"></i>Ref. No.</th>
                                    <th><i class="bi bi-calendar3" aria-hidden="true"></i>Check Expiry Date</th>
                                    <th><i class="bi bi-cash-stack" aria-hidden="true"></i>Amount</th>
                                    <th><i class="bi bi-three-dots" aria-hidden="true"></i>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade"
                     id="lpg-collection-cash"
                     role="tabpanel"
                     aria-labelledby="lpg-collection-cash-tab">
                    <form id="CashierReportLpgCashForm" class="needs-validation" novalidate>
                        @csrf
                        <input type="hidden" id="cashiers_report_lpg_cash_id"
                               name="cashiers_report_lpg_cash_id" value="0">
                        <input type="hidden" id="cashiers_report_lpg_cash_report_id"
                               name="cashiers_report_id"
                               value="{{ $CashiersReportId ?? ($cashiers_report_id ?? 0) }}">

                        <div class="mx-auto mb-3" style="max-width: 760px;">
                            <div class="card border rounded-3 overflow-hidden mb-0 lpg-cash-count-list">
                                <div class="card-header bg-light border-0 px-3 py-2">
                                    <h6 class="fw-semibold mb-0">
                                        <i class="bi bi-cash-stack text-success me-2" aria-hidden="true"></i>Cash On Hand
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="row g-0 align-items-center bg-light border-bottom px-3 py-2 small fw-semibold text-muted">
                                        <div class="col-12 col-sm-4"><i class="bi bi-cash me-1" aria-hidden="true"></i>Denomination</div>
                                        <div class="col-6 col-sm-4 text-center"><i class="bi bi-123 me-1" aria-hidden="true"></i>Quantity</div>
                                        <div class="col-6 col-sm-4 text-end"><i class="bi bi-currency-exchange me-1" aria-hidden="true"></i>Amount</div>
                                    </div>
                                    @php
                                        $lpgCashDenominations = [
                                            ['label' => '1,000.00', 'value' => 1000, 'field' => 'one_thousand_deno'],
                                            ['label' => '500.00', 'value' => 500, 'field' => 'five_hundred_deno'],
                                            ['label' => '200.00', 'value' => 200, 'field' => 'two_hundred_deno'],
                                            ['label' => '100.00', 'value' => 100, 'field' => 'one_hundred_deno'],
                                            ['label' => '50.00', 'value' => 50, 'field' => 'fifty_deno'],
                                            ['label' => '20.00', 'value' => 20, 'field' => 'twenty_deno'],
                                            ['label' => '10.00', 'value' => 10, 'field' => 'ten_deno'],
                                            ['label' => '5.00', 'value' => 5, 'field' => 'five_deno'],
                                            ['label' => '1.00', 'value' => 1, 'field' => 'one_deno'],
                                            ['label' => '0.25', 'value' => 0.25, 'field' => 'twenty_five_cent_deno'],
                                        ];
                                    @endphp
                                    @foreach ($lpgCashDenominations as $denomination)
                                        <div class="row g-0 align-items-center border-bottom px-3 py-2 lpg-cash-denomination-row">
                                            <div class="col-12 col-sm-4 mb-2 mb-sm-0">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-success bg-opacity-10 text-success flex-shrink-0" style="width:34px;height:34px;">
                                                        <i class="bi bi-cash" aria-hidden="true"></i>
                                                    </span>
                                                    <span class="fw-semibold text-nowrap">₱ {{ $denomination['label'] }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6 col-sm-4 d-flex justify-content-center">
                                                <label for="lpg_cash_{{ $denomination['field'] }}" class="visually-hidden">Quantity of ₱ {{ $denomination['label'] }} denomination</label>
                                                <input type="number"
                                                       class="form-control form-control-sm lpg-cash-quantity"
                                                       id="lpg_cash_{{ $denomination['field'] }}"
                                                       name="{{ $denomination['field'] }}"
                                                       data-denomination="{{ $denomination['value'] }}"
                                                       min="0" step="1" value="0"
                                                       style="width: 150px; max-width: 100%;"
                                                       aria-label="Quantity of {{ $denomination['label'] }} denomination">
                                            </div>
                                            <div class="col-6 col-sm-4 text-end fw-semibold">
                                                ₱ <span class="lpg-cash-line-amount" id="lpg_cash_amount_{{ $denomination['field'] }}">0.00</span>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="row g-0 align-items-center px-3 py-3 lpg-cash-total-row">
                                        <div class="col-8">
                                            <div class="d-flex align-items-center justify-content-end gap-2 fw-semibold">
                                                <i class="bi bi-cash-stack text-success" aria-hidden="true"></i>Total Cash on Hand:
                                            </div>
                                        </div>
                                        <div class="col-4 text-end fw-bold text-success">
                                            ₱ <span id="lpg_cash_total_on_hand">0.00</span>
                                        </div>
                                    </div>

                                    <div class="row g-0 align-items-center border-top px-3 py-3">
										<div class="col-12 col-sm-4 mb-2 mb-sm-0">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-success bg-opacity-10 text-success flex-shrink-0" style="width:34px;height:34px;">
                                                        <i class="bi bi-cash" aria-hidden="true"></i>
                                                    </span>
                                                    <span class="fw-semibold text-nowrap">Cash Drop:</span>
                                                </div>
                                        </div>
											
										<div class="col-6 col-sm-4 d-flex justify-content-center">
                                                <label for="lpg_cash_drop" class="visually-hidden">Cash Drop:</label>
                                                <input type="number"
                                                       class="form-control form-control-sm lpg-cash-quantity"
                                                       id="lpg_cash_drop"
                                                       name="cash_drop"
                                                       min="0" step="1" value="0"
                                                       style="width: 150px; max-width: 100%;"
                                                       aria-label="Total Cash Drop">
                                        </div>
											
										<div class="col-6 col-sm-4 text-end fw-semibold">
                                                ₱ <span class="lpg-cash-drop-amount" id="lpg-cash-drop-amount">0.00</span>
                                        </div>
                                    </div>

                                    <div class="row g-0 align-items-center px-3 py-3 lpg-cash-actual-row">
                                        <div class="col-8">
                                            <div class="d-flex align-items-center justify-content-end gap-2 fw-semibold">
                                                <i class="bi bi-wallet2" aria-hidden="true"></i>Total Actual Cash:
                                            </div>
                                        </div>
                                        <div class="col-4 text-end fw-bold">
                                            ₱ <span id="lpg_cash_total_actual">0.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mx-auto" style="max-width: 760px;">
                            <button type="submit" class="btn btn-success shadow-sm lpg-report-action-btn"
                                    id="save-lpg-cash">
                                <i class="bi bi-save-fill me-2"></i>Save Cash Count
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
