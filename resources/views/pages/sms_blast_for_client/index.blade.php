@extends('layouts.layout')
@section('body_class', 'fuel-dashboard-shell')
@section('skip_default_footer', '1')
@push('page_styles')
<link href="{{ asset('style/fuel-dashboard.css') }}" rel="stylesheet">
<link href="{{ asset('style/sms-blast.css') }}?v={{ filemtime(public_path('style/sms-blast.css')) }}" rel="stylesheet">
@endpush
@section('content')
@php
    $history = request('tab') === 'history' || $selected;
    $library = request('tab') === 'templates';
    $smsTemplateData = ['templates' => $templates, 'business_name' => config('sms.business_name'), 'business_signature' => config('sms.business_signature'), 'contact_number' => config('sms.contact_number')];
@endphp
<main id="main" class="main fuel-page fuel-dashboard sms-page">
    <div class="fuel-title-row">
        <div><p class="fuel-eyebrow">CUSTOMER COMMUNICATIONS</p><h1><span>SMS Broadcast</span></h1><p class="fuel-subtitle">Send updates to your customers and follow every campaign in one place.</p></div>
        <a class="fuel-button" href="{{ route('sms_blast', ['tab' => $library ? 'templates' : ($history ? 'history' : 'compose')]) }}"><i class="bi bi-arrow-clockwise"></i> Refresh</a>
    </div>
    <nav class="fuel-dashboard-tabs" aria-label="SMS pages">
        <a class="fuel-dashboard-tab {{ !$history && !$library ? 'is-active' : '' }}" @if(!$history && !$library) aria-current="page" @endif href="{{ route('sms_blast') }}"><i class="bi bi-pencil-square"></i> Compose a message</a>
        <a class="fuel-dashboard-tab {{ $library ? 'is-active' : '' }}" @if($library) aria-current="page" @endif href="{{ route('sms_blast', ['tab' => 'templates']) }}"><i class="bi bi-journal-text"></i> SMS templates</a>
        <a class="fuel-dashboard-tab {{ $history ? 'is-active' : '' }}" @if($history) aria-current="page" @endif href="{{ route('sms_blast', ['tab' => 'history']) }}"><i class="bi bi-clock-history"></i> Message history</a>
    </nav>
    @if($errors->any())<div class="fuel-alert" role="alert"><strong>Please review the information below.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <section class="fuel-stats" aria-label="{{ $history ? 'Filtered campaign totals' : 'Campaign totals' }}">
        @foreach([['Campaigns', $summary->campaigns, 'bi-collection'], ['Recipients', $summary->recipients, 'bi-people'], ['Provider accepted', $summary->sent, 'bi-send-check'], ['Failed', $summary->failed, 'bi-exclamation-circle']] as $stat)
        <article class="fuel-stat"><div class="fuel-stat-label">{{ $stat[0] }} <i class="bi {{ $stat[2] }}"></i></div><div class="fuel-stat-value">{{ number_format($stat[1]) }}</div><div class="fuel-stat-foot">{{ $history ? 'Across filtered campaigns' : 'Across all campaigns' }}</div></article>
        @endforeach
    </section>
    @if($library)
    @include('pages.sms_blast_for_client.templates.library')
    @elseif(!$history)
    <div class="sms-compose-grid">
        <section class="fuel-panel"><div class="fuel-panel-head"><div><h2>Compose a message</h2><p>Choose a customer type, then review your broadcast.</p></div><i class="bi bi-chat-left-text"></i></div>
            <form id="SmsBlastForm" class="sms-body" action="{{ route('send_sms_blast') }}" data-count-url="{{ route('sms_recipient_count') }}" method="post">
                @csrf
                <label for="template_key">SMS template</label>
                <select id="template_key" name="template_key"><option value="">Write a custom message</option>@foreach($templates as $key => $template)@if($template['scope'] !== 'order')<option value="{{ $key }}" {{ request('template') === $key ? 'selected' : '' }}>{{ $template['name'] }}</option>@endif @endforeach</select>
                <p class="sms-help">Choose a ready message and edit it before sending. See SMS templates for LPG follow-up drafts.</p>
                <label for="audience">Send to</label>
                <select id="audience" name="audience"><option value="customer_type">Selected customer type</option><option value="birthday">Birthday today only</option></select>
                <p class="sms-help" id="birthday-info">Birthday greetings use the saved birthday and first name. Automatic birthday sending: {{ $birthdayEnabled ? 'enabled' : 'not active' }}. {{ !$birthdayReady ? 'Birthday tracking migration is required before sending.' : '' }}</p>
                <label for="customer_type">Customer type</label>
                <select id="customer_type" name="customer_type" required><option value="">Select customer type</option>@foreach($types as $type)<option value="{{ $type }}">{{ $type }}</option>@endforeach</select>
                <p class="sms-help" id="recipient-count" aria-live="polite">Select a customer type to count valid recipients.</p>
                <label for="sms_content">Message</label>
                <textarea id="sms_content" name="sms_content" rows="8" maxlength="1000" required placeholder="Write your customer update here…"></textarea>
                <div class="sms-counter"><span><b id="sms_count">0</b> / 1,000 characters</span><span><b id="sms_parts">0</b> SMS parts · <span id="sms_encoding">GSM-7</span></span></div>
                <p class="sms-help">Unicode characters can use more SMS parts. Preview uses a sample first name; actual parts can vary per client. Supported fields: {client_name}, {client_first_name}, {salutation}, {business_name}, {business_signature}, {contact_number}.</p>
                <div id="sms-feedback" role="status" aria-live="polite"></div>
                <button class="fuel-button fuel-button-primary" id="send_sms_btn" type="submit" disabled><i class="bi bi-send"></i> Review broadcast</button>
            </form>
        </section>
        <aside class="fuel-panel"><div class="fuel-panel-head"><div><h2>Message preview</h2><p>A quick look before you send.</p></div><i class="bi bi-phone"></i></div><div class="sms-body"><div class="sms-phone"><small>CUSTOMER MESSAGE</small><p id="sms-preview">Your message will appear here.</p><span>SMS · ITEXMO</span></div><div class="sms-note"><i class="bi bi-info-circle"></i><p>One message per unique, valid Philippine mobile number. “Provider accepted” does not confirm delivery to the customer's phone.</p></div></div></aside>
    </div>
    <dialog id="sms-confirm"><form method="dialog"><h2>Review broadcast</h2><p id="sms-confirm-summary"></p><p class="sms-confirm-message" id="sms-confirm-message"></p><p>Sending will use your SMS credits.</p><div class="sms-dialog-actions"><button class="fuel-button" value="cancel">Back to editing</button><button class="fuel-button fuel-button-primary" value="send">Send broadcast</button></div></form></dialog>
    @else
    <form class="fuel-filterbar sms-filters" method="get" action="{{ route('sms_blast') }}">
        <input type="hidden" name="tab" value="history">
        <label>From<input type="date" name="from" value="{{ request('from') }}"></label>
        <label>To<input type="date" name="to" value="{{ request('to') }}"></label>
        <label>Customer type<select name="customer_type"><option value="">All types</option>@foreach(array_merge($types, ['Birthday']) as $type)<option value="{{ $type }}" {{ request('customer_type') === $type ? 'selected' : '' }}>{{ $type }}</option>@endforeach</select></label>
        <label>Status<select name="status"><option value="">All statuses</option>@foreach($statuses as $status)<option {{ request('status') === $status ? 'selected' : '' }}>{{ $status }}</option>@endforeach</select></label>
        <label>Search<input name="search" maxlength="100" value="{{ request('search') }}" placeholder="Campaign or message"></label>
        <button class="fuel-button fuel-button-primary" type="submit">Apply filters</button><a class="fuel-reset" href="{{ route('sms_blast', ['tab' => 'history']) }}">Reset</a>
    </form>
    <section class="fuel-panel"><div class="fuel-panel-head"><div><h2>Campaign history</h2><p>{{ number_format($campaigns->total()) }} campaigns found. Open a campaign to view individual recipient results.</p></div></div>
        <div class="sms-table-wrap"><table class="sms-table"><thead><tr><th>Campaign / Message</th><th>Customer type</th><th>Created</th><th>Recipients</th><th>Accepted</th><th>Failed</th><th>Status</th><th></th></tr></thead><tbody>
        @forelse($campaigns as $campaign)<tr><td><strong>#{{ $campaign->campaign_id }} · {{ $campaign->campaign_title }}</strong><span class="sms-excerpt">{{ \Illuminate\Support\Str::limit($campaign->sms_message, 95) }}</span></td><td>{{ $campaign->customer_type }}</td><td>{{ $campaign->created_at->format('M d, Y H:i') }}</td><td>{{ number_format($campaign->total_recipients) }}</td><td>{{ number_format($campaign->total_sent) }}</td><td>{{ number_format($campaign->total_failed) }}</td><td><span class="sms-status {{ $campaign->total_failed ? 'sms-status-error' : '' }}">{{ $campaign->campaign_status }}</span></td><td><a class="fuel-button fuel-button-small" href="{{ route('sms_blast', array_merge(request()->except('recipients_page'), ['tab' => 'history', 'campaign_id' => $campaign->campaign_id])) }}#campaign-details">View<span class="visually-hidden"> campaign {{ $campaign->campaign_id }}</span></a></td></tr>
        @empty<tr><td colspan="8" class="sms-empty"><i class="bi bi-inbox"></i><strong>No campaigns found</strong><span>Compose a broadcast or adjust your filters.</span></td></tr>@endforelse
        </tbody></table></div>
        <div class="sms-pagination">{{ $campaigns->links('pagination::bootstrap-4') }}</div>
    </section>
    @if($selected)
    <section class="fuel-panel sms-details" id="campaign-details"><div class="fuel-panel-head"><div><h2>Campaign #{{ $selected->campaign_id }} · Recipient details</h2><p>{{ $selected->campaign_status }} · {{ $selected->provider }} · {{ $selected->customer_type }}</p></div><a class="fuel-button fuel-button-small" href="{{ route('sms_blast', request()->except('campaign_id', 'recipients_page')) }}">Close details</a></div>
        <div class="sms-body"><p class="sms-full-message">{{ $selected->sms_message }}</p><p class="sms-help">Sent = accepted by provider. Delivered appears only when a delivery confirmation is recorded. Pending records may require investigation if a broadcast was interrupted.</p></div>
        <div class="sms-table-wrap"><table class="sms-table"><thead><tr><th>Customer</th><th>Mobile number</th><th>Personalized message</th><th>Status</th><th>Accepted at</th><th>Delivered at</th></tr></thead><tbody>
        @forelse($logs as $log)<tr><td>{{ $log->client_name }}</td><td>{{ $log->mobile_number }}</td><td><span class="sms-recipient-message">{{ $log->sms_message }}</span></td><td><span class="sms-status {{ $log->sms_status === 'Failed' ? 'sms-status-error' : '' }}">{{ $log->sms_status }}</span></td><td>{{ $log->sent_at ?: '—' }}</td><td>{{ $log->delivered_at ?: '—' }}</td></tr>@empty<tr><td colspan="6" class="sms-empty">No recipient records.</td></tr>@endforelse
        </tbody></table></div><div class="sms-pagination">{{ $logs->fragment('campaign-details')->links('pagination::bootstrap-4') }}</div>
    </section>
    @endif
    @endif
    @include('pages.user_account_settings.modals.logout_modal')
</main>
@endsection
@push('page_scripts')
<script type="application/json" id="sms-template-data">@json($smsTemplateData)</script>
<script src="{{ asset('NiceAdmin-pro/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('NiceAdmin-pro/assets/js/main.js') }}"></script>
<script src="{{ asset('js/sms-blast.js') }}?v={{ filemtime(public_path('js/sms-blast.js')) }}" defer></script>
<script src="{{ asset('js/sms-template-editor.js') }}?v={{ filemtime(public_path('js/sms-template-editor.js')) }}" defer></script>
@endpush
