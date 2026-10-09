<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-3">
    <div class="card-header bg-white border-0 px-3 px-lg-4 py-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h5 class="fw-bold mb-1">
                    <i class="bi bi-receipt-cutoff text-success me-2"></i>Cashier's Report
                </h5>
                <small class="text-muted">Report information and shift summary</small>
            </div>
            <div class="d-flex align-items-center gap-2 lpg-report-header-actions">
                <button type="button"
                        class="btn btn-outline-secondary collapsed lpg-report-action-btn"
                        id="toggleCashierReportOverview"
                        data-bs-toggle="collapse"
                        data-bs-target="#cashierReportOverview"
                        aria-expanded="false"
                        aria-controls="cashierReportOverview">
                    <i class="bi bi-chevron-down me-1" id="cashierReportOverviewIcon"></i>
                    <span id="cashierReportOverviewLabel">Show Report Details</span>
                </button>
                <button type="button"
                        class="btn btn-success shadow-sm lpg-report-action-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#UpdateCashiersReportModal">
                    <i class="bi bi-pencil-fill me-1"></i>Edit
                </button>
                <button type="button"
                        class="btn btn-dark shadow-sm lpg-report-action-btn"
                        id="PrintCashiersReport"
                        onclick="printCashierReportPDF()">
                    <i class="bi bi-printer-fill me-1"></i>Print
                </button>
            </div>
        </div>
    </div>

    <div class="collapse" id="cashierReportOverview">
    <div class="card-body p-3 p-lg-4">
        <div class="row g-3 align-items-stretch">
            <section class="col-12 col-lg-4" aria-labelledby="cashier-report-information-title">
                <div class="card border rounded-3 h-100 mb-0">
                    <div class="card-header bg-light border-0 px-3 py-2">
                        <h6 class="fw-bold mb-0" id="cashier-report-information-title">
                            <i class="bi bi-card-text text-success me-2"></i>Report Information
                        </h6>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-3 py-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary bg-opacity-10 text-primary flex-shrink-0" style="width:34px;height:34px;">
                                    <i class="bi bi-calendar-event"></i>
                                </span>
                                <div class="min-w-0">
                                    <small class="text-muted d-block">Report Date</small>
                                    <div class="fw-semibold text-break" id="cashier_info_date">-</div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-3 py-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-success bg-opacity-10 text-success flex-shrink-0" style="width:34px;height:34px;">
                                    <i class="bi bi-building"></i>
                                </span>
                                <div class="min-w-0">
                                    <small class="text-muted d-block">Branch</small>
                                    <div class="fw-semibold text-break" id="cashier_info_branch_name">-</div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-3 py-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-info bg-opacity-10 text-info flex-shrink-0" style="width:34px;height:34px;">
                                    <i class="bi bi-person-badge"></i>
                                </span>
                                <div class="min-w-0">
                                    <small class="text-muted d-block">Cashier's on Duty</small>
                                    <div class="fw-semibold text-break" id="cashier_info_cashiers_name">-</div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-3 py-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-info bg-opacity-10 text-info flex-shrink-0" style="width:34px;height:34px;">
                                    <i class="bi bi-person-workspace"></i>
                                </span>
                                <div class="min-w-0">
                                    <small class="text-muted d-block">Employee's On-Duty</small>
                                    <div class="fw-semibold text-break" id="cashier_info_forecourt_attendant">-</div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-3 py-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-warning bg-opacity-10 text-warning flex-shrink-0" style="width:34px;height:34px;">
                                    <i class="bi bi-clock"></i>
                                </span>
                                <div class="min-w-0">
                                    <small class="text-muted d-block">Shift</small>
                                    <div class="fw-semibold text-break" id="cashier_info_shift">-</div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-3 py-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-secondary bg-opacity-10 text-secondary flex-shrink-0" style="width:34px;height:34px;">
                                    <i class="bi bi-person-check"></i>
                                </span>
                                <div class="min-w-0">
                                    <small class="text-muted d-block">Encoded By</small>
                                    <div class="fw-semibold text-break" id="cashier_info_encoder_name">-</div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-3 py-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-secondary bg-opacity-10 text-secondary flex-shrink-0" style="width:34px;height:34px;">
                                    <i class="bi bi-chat-left-text"></i>
                                </span>
                                <div class="min-w-0">
                                    <small class="text-muted d-block">Remarks</small>
                                    <div class="fw-semibold text-break" id="cashier_info_remarks">-</div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </section>

            <section class="col-12 col-lg-8" aria-labelledby="cashier-report-summary-title">
                <div class="card border rounded-3 h-100 mb-0">
                    <div class="card-header bg-light border-0 px-3 py-2">
                        <h6 class="fw-bold mb-0" id="cashier-report-summary-title">
                            <i class="bi bi-bar-chart-line text-primary me-2"></i>Sales &amp; Payment Summary
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-0 border rounded-3 overflow-hidden">
                            <div class="col-12 border-bottom">
                                <div class="d-flex align-items-center gap-2 px-3 py-2 h-100">
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary bg-opacity-10 text-primary flex-shrink-0" style="width:34px;height:34px;"><i class="bi bi-fuel-pump-fill"></i></span>
                                    <div class="min-w-0"><small class="text-muted d-block">Fuel Sales</small><div class="fw-bold text-body" id="fuel_sales_total">0.00</div></div>
                                </div>
                            </div>
                            <div class="col-12 border-bottom">
                                <div class="d-flex align-items-center gap-2 px-3 py-2 h-100">
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-info bg-opacity-10 text-info flex-shrink-0" style="width:34px;height:34px;"><i class="bi bi-cart-check"></i></span>
                                    <div class="min-w-0"><small class="text-muted d-block">Other Sales</small><div class="fw-bold text-body" id="other_sales_total">0.00</div></div>
                                </div>
                            </div>
                            <div class="col-12 border-bottom">
                                <div class="d-flex align-items-center gap-2 px-3 py-2 h-100">
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-danger bg-opacity-10 text-danger flex-shrink-0" style="width:34px;height:34px;"><i class="bi bi-dash-circle"></i></span>
                                    <div class="min-w-0"><small class="text-muted d-block">Miscellaneous</small><div class="fw-bold text-danger" id="miscellaneous_total">0.00</div></div>
                                </div>
                            </div>
                            <div class="col-12 border-bottom">
                                <div class="d-flex align-items-center gap-2 px-3 py-2 h-100">
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-secondary bg-opacity-10 text-secondary flex-shrink-0" style="width:34px;height:34px;"><i class="bi bi-calculator"></i></span>
                                    <div class="min-w-0"><small class="text-muted d-block">Theoretical Sales</small><div class="fw-bold text-body" id="theoretical_sales">0.00</div></div>
                                </div>
                            </div>
                            <div class="col-12 border-bottom">
                                <div class="d-flex align-items-center gap-2 px-3 py-2 h-100">
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-success bg-opacity-10 text-success flex-shrink-0" style="width:34px;height:34px;"><i class="bi bi-cash-stack"></i></span>
                                    <div class="min-w-0"><small class="text-muted d-block">Cash on Hand</small><div class="fw-bold text-success" id="cash_on_hand">0.00</div></div>
                                </div>
                            </div>
                            <div class="col-12 border-bottom">
                                <div class="d-flex align-items-center gap-2 px-3 py-2 h-100">
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary bg-opacity-10 text-primary flex-shrink-0" style="width:34px;height:34px;"><i class="bi bi-credit-card"></i></span>
                                    <div class="min-w-0"><small class="text-muted d-block">Non-Cash Payment</small><div class="fw-bold text-body" id="total_non_cash_payment">0.00</div></div>
                                </div>
                            </div>
                            <div class="col-12 border-bottom">
                                <div class="d-flex align-items-center gap-2 px-3 py-2 h-100">
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-success bg-opacity-10 text-success flex-shrink-0" style="width:34px;height:34px;"><i class="bi bi-wallet2"></i></span>
                                    <div class="min-w-0"><small class="text-muted d-block">Total Cash Payment</small><div class="fw-bold text-success" id="total_cash_payment">0.00</div></div>
                                </div>
                            </div>
                        
						
                            <div class="col-12 border-bottom">
                                <div class="d-flex align-items-center gap-2 bg-primary bg-opacity-10 px-3 py-2 h-100">
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-white text-primary flex-shrink-0" style="width:34px;height:34px;"><i class="bi bi-graph-up-arrow"></i></span>
                                    <div class="min-w-0"><small class="text-primary d-block">Total Sales</small><div class="fw-bold fs-5 text-primary" id="total_sales">0.00</div></div>
                                </div>
                            </div>
                            <div class="col-12 border-bottom">
                                <div class="d-flex align-items-center gap-2 bg-light px-3 py-2 h-100">
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-warning bg-opacity-10 text-warning flex-shrink-0" style="width:34px;height:34px;"><i class="bi bi-clipboard-data"></i></span>
                                    <div class="min-w-0"><small class="text-muted d-block">Cash Short / Over</small><div class="fw-bold fs-5" id="cash_short_or_over">0.00</div></div>
                                </div>
                            </div>
                        </div>
						
                    </div>
					
                </div>
            </section>
        </div>
    </div>
    </div>
</div>
