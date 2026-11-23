@extends('layouts.layout')
@section('body_class', 'cash-deposits-shell')

@push('page_styles')
<link href="{{ asset('style/cash-deposits.css') }}?v={{ filemtime(public_path('style/cash-deposits.css')) }}" rel="stylesheet">
@endpush

@section('content')
<main id="main" class="main cash-deposits-page" data-store-url="{{ route('cash_deposits.store') }}" data-update-url="{{ route('cash_deposits.update', '__ID__') }}" data-delete-url="{{ route('cash_deposits.destroy', '__ID__') }}">
    <header class="cd-heading">
        <div>
            <p class="cd-eyebrow">FINANCE / BANKING</p>
            <h1>Cash <span>Deposits</span></h1>
            <p>Record and review bank deposits in one place.</p>
        </div>
        <div class="cd-heading-actions">
            <button type="button" class="btn cd-btn-primary" id="cd-new"><i class="bi bi-plus-lg" aria-hidden="true"></i> New deposit</button>
        </div>
    </header>

    <div class="cd-summary">
        <article><span>Total deposited</span><strong>₱{{ number_format((float) $summary->total_amount, 2) }}</strong><small>{{ $data->user_type === 'Encoder' ? 'Your recorded deposits' : 'Across all cash deposits' }}</small></article>
        <article><span>Deposit entries</span><strong>{{ number_format((int) $summary->deposit_count) }}</strong><small>Recorded deposits</small></article>
        <article><span>My editable entries</span><strong>{{ number_format($editableCount) }}</strong><small>Within 48 hours of encoding</small></article>
    </div>

    <section class="cd-panel" aria-labelledby="cd-history-title">
        <div class="cd-panel-heading">
            <div><h2 id="cd-history-title">Deposit history</h2><p>Only the encoder can change an entry during its first 48 hours.</p></div>
            <form method="get" class="cd-search" role="search">
                <label class="visually-hidden" for="cd-search">Search deposits</label>
                <input id="cd-search" type="search" name="q" class="form-control" placeholder="Search bank or reference" value="{{ $search }}">
                <button class="btn cd-btn-outline" type="submit">Search</button>
            </form>
        </div>
        <div class="cd-table-scroll">
            <table class="table cd-table">
                <thead><tr><th>Deposit date</th><th>Bank</th><th>Reference</th><th class="text-end">Amount</th><th>Deposited by</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($deposits as $deposit)
                        @php
                            $canChange = $access->canChange($deposit, $data);
                            $photoUrl = $deposit->cash_deposit_photo ? route('cash_deposits.photo', $deposit->cash_deposit_id, false) : '';
                        @endphp
                        <tr>
                            <td>{{ $deposit->cash_deposit_date ? \Carbon\Carbon::parse($deposit->cash_deposit_date)->format('M d, Y') : '—' }}</td>
                            <td><strong>{{ $deposit->bank_name ?? $deposit->cash_deposit_bank ?? 'Bank unavailable' }}</strong><small>{{ $deposit->bank_account_number }}</small></td>
                            <td>{{ $deposit->cash_deposit_reference }}</td>
                            <td class="text-end cd-amount">₱{{ number_format((float) $deposit->cash_deposit_amount, 2) }}</td>
                            <td>{{ $deposit->encoder_name ?? 'Unknown user' }}</td>
                            <td><span class="cd-status {{ $canChange ? 'cd-status-open' : 'cd-status-locked' }}">{{ $canChange ? 'Editable' : 'Read only' }}</span></td>
                            <td>
                                <div class="cd-row-actions">
                                    <button type="button" class="btn cd-icon cd-view" title="View" aria-label="View deposit"
                                        data-id="{{ $deposit->cash_deposit_id }}" data-bank-id="{{ $deposit->bank_idx }}"
                                        data-bank="{{ $deposit->bank_name ?? $deposit->cash_deposit_bank }}" data-date="{{ $deposit->cash_deposit_date ? \Carbon\Carbon::parse($deposit->cash_deposit_date)->format('Y-m-d') : '' }}"
                                        data-reference="{{ $deposit->cash_deposit_reference }}" data-amount="{{ $deposit->cash_deposit_amount }}"
                                        data-remarks="{{ $deposit->cash_deposit_remarks }}" data-photo="{{ $photoUrl }}" data-encoder="{{ $deposit->encoder_name }}">
                                        <i class="bi bi-eye" aria-hidden="true"></i>
                                    </button>
                                    @if($canChange)
                                        <button type="button" class="btn cd-icon cd-edit" title="Edit" aria-label="Edit deposit" data-id="{{ $deposit->cash_deposit_id }}"><i class="bi bi-pencil" aria-hidden="true"></i></button>
                                        <button type="button" class="btn cd-icon cd-delete" title="Delete" aria-label="Delete deposit" data-id="{{ $deposit->cash_deposit_id }}"><i class="bi bi-trash3" aria-hidden="true"></i></button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="cd-empty">No cash deposits found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($deposits->hasPages())<div class="cd-pagination">{{ $deposits->links() }}</div>@endif
    </section>
    @include('pages.cash_deposits.modals.form')
    @include('pages.cash_deposits.modals.view')
    @include('pages.cash_deposits.modals.delete')
</main>
@endsection

@push('page_scripts')
<script src="{{ asset('js/cash-deposits.js') }}?v={{ filemtime(public_path('js/cash-deposits.js')) }}"></script>
@endpush
