<section aria-label="Campaign {{ $selected->campaign_id }} recipient details">
    <div class="sms-body sms-history-summary">
        <h3>Campaign #{{ $selected->campaign_id }} · Recipient details</h3>
        <p class="sms-help">{{ $selected->campaign_title }}<br>{{ $selected->campaign_status }} · {{ $selected->provider }} · {{ $selected->customer_type }}</p>
        <div class="sms-history-totals"><span><strong>{{ number_format($selected->total_recipients) }}</strong> Recipients</span><span><strong>{{ number_format($selected->total_sent) }}</strong> Provider accepted</span><span><strong>{{ number_format($selected->total_failed) }}</strong> Failed</span></div>
        <p class="sms-full-message">{{ $selected->sms_message }}</p>
        <p class="sms-help">Sent = accepted by provider. Delivered appears only when a delivery confirmation is recorded. Pending records may require investigation if a broadcast was interrupted.</p>
    </div>
    <div class="sms-table-wrap"><table class="sms-table"><thead><tr><th>Customer</th><th>Mobile number</th><th>Personalized message</th><th>Status</th><th>Accepted at</th><th>Delivered at</th></tr></thead><tbody>
    @forelse($logs as $log)<tr><td>{{ $log->client_name }}</td><td>{{ $log->mobile_number }}</td><td><span class="sms-recipient-message">{{ $log->sms_message }}</span></td><td><span class="sms-status {{ $log->sms_status === 'Failed' ? 'sms-status-error' : '' }}">{{ $log->sms_status }}</span></td><td>{{ $log->sent_at ?: '—' }}</td><td>{{ $log->delivered_at ?: '—' }}</td></tr>@empty<tr><td colspan="6" class="sms-empty">No recipient records.</td></tr>@endforelse
    </tbody></table></div>
    <div class="sms-pagination">{{ $logs->withPath(route('sms_campaign_details', ['campaignId' => $selected->campaign_id]))->links('pagination::bootstrap-4') }}</div>
</section>
