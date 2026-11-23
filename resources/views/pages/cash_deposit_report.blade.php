<section class="cdr" aria-labelledby="cdr-title">
    <div class="cdr-heading">
        <div>
            <p class="cdr-eyebrow">CASH REPORT / BANK DEPOSITS</p>
            <h2 id="cdr-title">Cash Deposit Report</h2>
            <p>Compare the Fuel and LPG cashier cash counts with deposits recorded for the same period.</p>
        </div>
        @if($cashDepositReport)
            <button type="button" class="btn cdr-print" onclick="window.print()"><i class="bi bi-printer" aria-hidden="true"></i> Print report</button>
        @endif
    </div>

    <form class="cdr-filters" method="get" action="{{ route('cash_report_page') }}">
        <input type="hidden" name="tab" value="cash-deposits">
        <div><label for="cdr-start">From</label><input class="form-control" type="date" id="cdr-start" name="start_date" value="{{ $cashDepositPeriod['start_date'] }}" required></div>
        <div><label for="cdr-end">To</label><input class="form-control" type="date" id="cdr-end" name="end_date" min="{{ $cashDepositPeriod['start_date'] }}" value="{{ $cashDepositPeriod['end_date'] }}" required></div>
        <div><label for="cdr-branch">Site branch</label><select class="form-select" id="cdr-branch" name="branch"><option value="All">All assigned branches</option>@foreach($teves_branch as $branch)<option value="{{ $branch->branch_id }}" @if((string) $cashDepositPeriod['branch'] === (string) $branch->branch_id) selected @endif>{{ $branch->branch_code }} · {{ $branch->branch_name }}</option>@endforeach</select></div>
        <button type="submit" class="btn cdr-generate"><i class="bi bi-funnel" aria-hidden="true"></i> Generate report</button>
    </form>

    @if($cashDepositReport)
        @php
            $cdrPeriod = \Carbon\Carbon::parse($cashDepositPeriod['start_date'])->format('M d, Y') . ' – ' . \Carbon\Carbon::parse($cashDepositPeriod['end_date'])->format('M d, Y');
        @endphp
        <div class="cdr-period">Cashier report and deposit dates: <strong>{{ $cdrPeriod }}</strong></div>
        <div class="cdr-stats">
            <article><span>Total Cash On-Hand</span><strong>₱{{ number_format($cashDepositReport['totals']['on_hand'], 2) }}</strong><small>Fuel ₱{{ number_format($cashDepositReport['totals']['fuel'], 2) }} · LPG ₱{{ number_format($cashDepositReport['totals']['lpg'], 2) }}</small></article>
            <article><span>Total Cash Deposited</span><strong>₱{{ number_format($cashDepositReport['totals']['deposited'], 2) }}</strong><small>Across recorded bank accounts</small></article>
            <article><span>Variance</span><strong class="{{ $cashDepositReport['totals']['variance'] < 0 ? 'cdr-negative' : '' }}">₱{{ number_format($cashDepositReport['totals']['variance'], 2) }}</strong><small>Deposited minus Cash On-Hand</small></article>
        </div>

        @if($cashDepositReport['unassigned'] && $cashDepositReport['unassigned']->entry_count > 0)
            <div class="cdr-notice" role="status"><i class="bi bi-exclamation-circle" aria-hidden="true"></i> {{ number_format($cashDepositReport['unassigned']->entry_count) }} older deposit(s), totaling ₱{{ number_format((float) $cashDepositReport['unassigned']->amount, 2) }}, have no site branch and are excluded from branch totals.</div>
        @endif

        <div class="cdr-table-wrap">
            <table class="table cdr-table">
                <thead><tr><th>Site Branch</th><th>Cashiers Report Period</th><th class="text-end">Total Cash On-Hand</th><th class="text-end">Total Cash Deposited</th><th class="text-end">Variance</th></tr></thead>
                <tbody>
                    @forelse($cashDepositReport['rows'] as $row)
                        <tr>
                            <td><strong>{{ $row['branch_code'] }}</strong><small>{{ $row['branch_name'] }}</small></td>
                            <td>{{ $cdrPeriod }}</td>
                            <td class="text-end"><strong>₱{{ number_format($row['on_hand'], 2) }}</strong><small>Fuel ₱{{ number_format($row['fuel'], 2) }} · LPG ₱{{ number_format($row['lpg'], 2) }}</small></td>
                            <td class="text-end">₱{{ number_format($row['deposited'], 2) }}</td>
                            <td class="text-end {{ $row['variance'] < 0 ? 'cdr-negative' : '' }}">₱{{ number_format($row['variance'], 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="cdr-empty">No cashier cash counts or assigned deposits found for this period.</td></tr>
                    @endforelse
                </tbody>
                @if($cashDepositReport['rows']->isNotEmpty())
                    <tfoot><tr><th>Total</th><td>{{ $cdrPeriod }}</td><td class="text-end">₱{{ number_format($cashDepositReport['totals']['on_hand'], 2) }}</td><td class="text-end">₱{{ number_format($cashDepositReport['totals']['deposited'], 2) }}</td><td class="text-end">₱{{ number_format($cashDepositReport['totals']['variance'], 2) }}</td></tr></tfoot>
                @endif
            </table>
        </div>
        <p class="cdr-footnote">Cash On-Hand uses the denomination counts in Fuel and LPG cashier reports. Cash Drop is excluded. Deposits use the deposit date entered on the Cash Deposits page.</p>
    @else
        <div class="cdr-empty-state"><i class="bi bi-bank" aria-hidden="true"></i><h3>Ready to compare deposits</h3><p>Select a period and generate the report to see branch totals.</p></div>
    @endif
</section>
