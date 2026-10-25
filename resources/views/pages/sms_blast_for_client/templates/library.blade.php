<div class="sms-library-heading"><div><h2>SMS template library</h2><p>Create a template, edit its message, or archive one you no longer use.</p></div><a href="{{ route('sms_blast', ['tab' => 'templates', 'new_template' => 1]) }}#template-editor" class="fuel-button fuel-button-primary"><i class="bi bi-plus-lg"></i> Add template</a></div>
@if(session('sms_template_status'))<div class="sms-save-notice" role="status">{{ session('sms_template_status') }}</div>@endif
@if(!$templatesReady)
<p class="sms-save-notice">Template management is not ready yet. Contact your system administrator to finish setup.</p>
@else
@if($editingTemplate || request('new_template'))
@php
    $isEditing = (bool) $editingTemplate;
    $initialMessage = $isEditing ? strtr($editingTemplate->message, ['{business_signature}' => config('sms.business_signature')]) : '';
@endphp
<section class="fuel-panel sms-template-editor" id="template-editor">
    <div class="fuel-panel-head"><div><h2>{{ $isEditing ? 'Edit template' : 'Add template' }}</h2><p>Save the message here to reuse it later. Saving a template does not send an SMS.</p></div></div>
    <form class="sms-body" method="post" action="{{ $isEditing ? route('sms_templates.update', ['key' => $editingTemplate->template_key]) : route('sms_templates.store') }}" id="SmsTemplateEditor">
        @csrf
        @if($isEditing) @method('PUT')<input type="hidden" name="version" value="{{ old('version', $editingTemplate->version) }}"> @endif
        <div class="sms-editor-grid"><div>
            <label for="template-name">Template name</label><input id="template-name" name="name" required maxlength="100" value="{{ old('name', $editingTemplate->name ?? '') }}" placeholder="Example: LPG delivery update">
            <label for="template-scope">Category</label>
            <select name="scope" id="template-scope" required>
                @foreach(['broadcast' => 'General broadcast', 'birthday' => 'Birthday greeting', 'order' => 'LPG order / follow-up'] as $scope => $label)
                @if(!$isEditing || $editingTemplate->template_key !== 'birthday' || $scope === 'birthday')
                <option value="{{ $scope }}" {{ old('scope', $editingTemplate->scope ?? 'broadcast') === $scope ? 'selected' : '' }}>{{ $label }}</option>
                @endif
                @endforeach
            </select>
            <p class="sms-help" id="template-category-help">General broadcasts use your selected customer type. Birthday messages use today's birthdays. LPG order sending is pending its final schedule.</p>
            @if($isEditing && $editingTemplate->template_key === 'birthday')<p class="sms-save-notice">This is the daily birthday template. Changes also apply to future automatic greetings when birthday sending is enabled.</p>@endif
            <label for="template-situation">When to use it</label><input id="template-situation" name="situation" maxlength="255" value="{{ old('situation', $editingTemplate->situation ?? '') }}" placeholder="Example: Customer service announcement">
            <label for="template-message">Message</label>
            <div class="sms-token-buttons" aria-label="Insert a personal field">
                @foreach(['client_first_name' => 'First name', 'client_name' => 'Client name', 'salutation' => 'Mr. / Ms.', 'business_name' => 'Business name', 'business_signature' => 'Business signature', 'contact_number' => 'Contact number', 'order_number' => 'Order number'] as $token => $label)
                <button class="fuel-button fuel-button-small" type="button" data-sms-token="{{ $token }}">+ {{ $label }}</button>
                @endforeach
            </div>
            <textarea id="template-message" name="message" rows="11" maxlength="1000" required placeholder="Write your reusable SMS message…">{{ old('message', $initialMessage) }}</textarea>
            <p class="sms-help"><span id="template-character-count">{{ mb_strlen(old('message', $initialMessage)) }}</span> / 1,000 characters. Fields in braces are filled from each client's record when sending. Replace the address directly if this template needs a different signature.</p>
        </div><aside><h3>Sample preview</h3><div class="sms-phone"><small>SAMPLE CLIENT · JUAN</small><p id="template-sample-preview">Your message will appear here.</p><span>This is a sample; no SMS will be sent.</span></div></aside></div>
        <div class="sms-dialog-actions"><a class="fuel-button" href="{{ route('sms_blast', ['tab' => 'templates']) }}">Cancel</a><button type="submit" class="fuel-button fuel-button-primary"><i class="bi bi-check2"></i> {{ $isEditing ? 'Save changes' : 'Save template' }}</button></div>
    </form>
</section>
@endif
<section class="sms-template-grid" aria-label="SMS template library">
    @forelse($templateLibrary as $record)
    <article class="fuel-panel {{ !$record->is_active ? 'sms-template-archived' : '' }}"><div class="fuel-panel-head"><div><h2>{{ $record->name }}</h2><p>{{ $record->situation }}</p></div><span class="sms-status">{{ $record->is_active ? ucfirst($record->scope) : 'Archived' }}</span></div>
        <div class="sms-body"><p class="sms-full-message">{{ strtr($record->message, ['{business_signature}' => config('sms.business_signature')]) }}</p>
            @if($record->scope === 'order')<p class="sms-help">LPG template saved. Automatic sending is not active; follow-up timing and the order/delivery reference date are pending.</p>@endif
            @if($record->template_key === 'birthday')<p class="sms-help">Daily birthday template · editable · used by automatic birthday greetings when enabled.</p>@endif
            <div class="sms-library-actions">
                @if($record->is_active && $record->scope !== 'order')<a class="fuel-button fuel-button-primary" href="{{ route('sms_blast', ['template' => $record->template_key]) }}">Use template</a>@endif
                <a class="fuel-button" href="{{ route('sms_blast', ['tab' => 'templates', 'edit_template' => $record->template_key]) }}#template-editor"><i class="bi bi-pencil"></i> Edit</a>
                @if($record->template_key !== 'birthday')
                <form method="post" action="{{ route('sms_templates.status', ['key' => $record->template_key]) }}">@csrf @method('PATCH')<input type="hidden" name="version" value="{{ $record->version }}"><input type="hidden" name="is_active" value="{{ $record->is_active ? 0 : 1 }}"><button class="fuel-button" type="submit">{{ $record->is_active ? 'Archive' : 'Restore' }}</button></form>
                @endif
            </div>
        </div>
    </article>
    @empty<p class="sms-save-notice">No saved templates yet. Use Add template to create your first one.</p>@endforelse
</section>
@endif
