(function () {
    'use strict';
    const form = document.getElementById('SmsBlastForm');
    if (!form) return;
    const type = form.elements.customer_type;
    const message = form.elements.sms_content;
    const button = document.getElementById('send_sms_btn');
    const feedback = document.getElementById('sms-feedback');
    const countLabel = document.getElementById('recipient-count');
    const dialog = document.getElementById('sms-confirm');
    const templateSelect = document.getElementById('template_key');
    const audience = document.getElementById('audience');
    const individualFields = document.getElementById('sms-individual-fields');
    const searchInput = document.getElementById('sms-client-search');
    const searchResults = document.getElementById('sms-client-results');
    const searchStatus = document.getElementById('sms-client-search-status');
    const clientId = document.getElementById('sms-client-id');
    const selectedLabel = document.getElementById('sms-selected-client');
    const library = JSON.parse(document.getElementById('sms-template-data').textContent);
    const gsm = new Set(Array.from('@£$¥èéùìòÇ\nØø\rÅåΔ_ΦΓΛΩΠΨΣΘΞÆæßÉ !"#¤%&\'()*+,-./0123456789:;<=>?¡ABCDEFGHIJKLMNOPQRSTUVWXYZÄÖÑÜ§¿abcdefghijklmnopqrstuvwxyzäöñüà'));
    const extension = new Set(Array.from('\f^{}\\[~]|€'));
    let recipients = null, sequence = 0, sending = false, uncertain = false;
    let selectedRecipient = null, searchSequence = 0, searchTimer;
    let requestId = newId();
    function newId() {
        const bytes = crypto.getRandomValues(new Uint8Array(16));
        bytes[6] = (bytes[6] & 15) | 64; bytes[8] = (bytes[8] & 63) | 128;
        const hex = Array.from(bytes, b => b.toString(16).padStart(2, '0')).join('');
        return `${hex.slice(0,8)}-${hex.slice(8,12)}-${hex.slice(12,16)}-${hex.slice(16,20)}-${hex.slice(20)}`;
    }
    function measure(value) {
        const unicode = Array.from(value).some(c => !gsm.has(c) && !extension.has(c));
        const units = unicode ? value.length : Array.from(value).reduce((n,c) => n + (extension.has(c) ? 2 : 1), 0);
        const single = unicode ? 70 : 160, multi = unicode ? 67 : 153;
        return { parts: units === 0 ? 0 : units <= single ? 1 : Math.ceil(units / multi), encoding: unicode ? 'Unicode' : 'GSM-7' };
    }
    function update() {
        const preview = sample(message.value);
        const estimate = measure(preview);
        document.getElementById('sms_count').textContent = Array.from(message.value).length;
        document.getElementById('sms_parts').textContent = estimate.parts;
        document.getElementById('sms_encoding').textContent = estimate.encoding;
        document.getElementById('sms-preview').textContent = preview || 'Your message will appear here.';
        button.disabled = sending || uncertain || !recipients || !message.value.trim();
    }
    message.addEventListener('input', update);
    function sample(value) {
        const individual = audience.value === 'individual';
        const person = individual ? selectedRecipient : null;
        const gender = (person?.client_gender || '').trim().toLowerCase();
        const fields = {
            client_name: person ? person.client_name.trim() || 'valued customer' : (individual ? '[select client]' : 'Juan Dela Cruz'),
            client_first_name: person ? (person.client_first_name || '').trim() || 'valued customer' : (individual ? '[select client]' : 'Juan'),
            salutation: person ? (['male','m'].includes(gender) ? 'Mr.' : ['female','f'].includes(gender) ? 'Ms.' : '') : (individual ? '' : 'Mr.'),
            business_name: library.business_name, business_signature: library.business_signature, contact_number: library.contact_number,
        };
        return value.replace(/\{([^{}]+)\}/g, (whole, field) => Object.prototype.hasOwnProperty.call(fields, field) ? fields[field] : whole);
    }
    function clearClient() {
        ++sequence; clientId.value = ''; selectedRecipient = null; recipients = null;
        selectedLabel.textContent = 'No client selected.';
        if (audience.value === 'individual') countLabel.textContent = 'Search and select one client to continue.';
        update();
    }
    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimer); const current = ++searchSequence;
        clearClient(); searchResults.replaceChildren(); searchResults.disabled = true;
        const query = searchInput.value.trim();
        if (query.length < 2) { searchStatus.textContent = 'Type at least 2 letters of the client name.'; return; }
        searchStatus.textContent = 'Searching clients…';
        searchTimer = setTimeout(async function () {
            try {
                const url = new URL(form.dataset.clientsUrl, location.href); url.searchParams.set('q', query);
                const response = await fetch(url, { headers: { Accept: 'application/json' } });
                if (!response.ok) throw new Error('Unable to search clients. Try searching again.');
                const result = await response.json();
                if (current !== searchSequence || audience.value !== 'individual') return;
                for (const client of result.clients) {
                    const option = document.createElement('option'); option.value = client.client_id;
                    option.textContent = `${client.client_name} · ${client.mobile_number || 'No valid mobile number'} · ${client.customer_type}`;
                    option.disabled = !client.mobile_number; searchResults.appendChild(option);
                }
                searchResults.selectedIndex = -1; searchResults.disabled = result.clients.length === 0;
                searchStatus.textContent = result.clients.length ? `${result.clients.length} results. Select one client. Refine the name if needed; at most 20 results are shown.` : 'No matching clients found.';
            } catch (error) { if (current === searchSequence) searchStatus.textContent = error.message; }
        }, 300);
    });
    searchResults.addEventListener('change', function () { clientId.value = searchResults.value; refreshCount(); });
    async function refreshCount() {
        const current = ++sequence;
        recipients = null; selectedRecipient = null; update();
        const birthday = audience.value === 'birthday';
        const individual = audience.value === 'individual';
        individualFields.hidden = !individual;
        type.disabled = birthday || individual; type.required = !birthday && !individual;
        if (individual && !clientId.value) { countLabel.textContent = 'Search and select one client to continue.'; return; }
        if (!birthday && !individual && !type.value) { countLabel.textContent = 'Select a customer type to count valid recipients.'; return; }
        countLabel.textContent = 'Counting valid recipients…';
        try {
            const url = new URL(form.dataset.countUrl, location.href);
            url.searchParams.set('audience', audience.value);
            if (individual) url.searchParams.set('client_id', clientId.value);
            else if (!birthday) url.searchParams.set('customer_type', type.value);
            const response = await fetch(url, { headers: { Accept: 'application/json' } });
            const result = await response.json();
            if (!response.ok) throw new Error(Object.values(result.errors || {}).flat().join(' ') || result.message || 'Unable to check recipients. Select the recipient again.');
            if (current !== sequence) return;
            recipients = result.count;
            if (individual) {
                selectedRecipient = result.recipient;
                selectedLabel.textContent = `${selectedRecipient.client_name} · ${selectedRecipient.mobile_number} · ${selectedRecipient.customer_type}`;
                countLabel.textContent = '1 selected client. Only this client will receive the SMS.';
            } else countLabel.textContent = `${recipients.toLocaleString()} unique valid mobile numbers${birthday ? ' with birthdays today. Already reserved/sent greetings are skipped when sending.' : ''}`;
        } catch (error) { if (current === sequence) countLabel.textContent = error.message; }
        update();
    }
    type.addEventListener('change', refreshCount);
    templateSelect.addEventListener('change', function () {
        const template = library.templates[templateSelect.value];
        if (template) message.value = template.message.replaceAll('{business_signature}', library.business_signature);
        if (audience.value !== 'individual') audience.value = template && template.scope === 'birthday' ? 'birthday' : 'customer_type';
        update(); refreshCount();
    });
    audience.addEventListener('change', function () {
        ++searchSequence; clearTimeout(searchTimer);
        clearClient(); searchResults.replaceChildren(); searchResults.disabled = true; searchInput.value = '';
        if (audience.value === 'birthday') {
            const selected = library.templates[templateSelect.value];
            const key = selected && selected.scope === 'birthday' ? templateSelect.value : (library.templates.birthday ? 'birthday' : Object.keys(library.templates).find(key => library.templates[key].scope === 'birthday'));
            if (key) { templateSelect.value = key; message.value = library.templates[key].message.replaceAll('{business_signature}', library.business_signature); }
        } else if (audience.value !== 'individual' && library.templates[templateSelect.value]?.scope === 'birthday') {
            templateSelect.value = ''; message.value = '';
        }
        update(); refreshCount();
    });
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        if (sending || uncertain || !recipients || !message.value.trim() || !form.reportValidity()) return;
        const individual = audience.value === 'individual';
        if (individual && !selectedRecipient) return;
        document.getElementById('sms-confirm-summary').textContent = individual
            ? `${selectedRecipient.client_name} · ${selectedRecipient.mobile_number} · 1 client only · estimated ${measure(sample(message.value.trim())).parts} SMS parts.`
            : `${audience.value === 'birthday' ? 'Birthday today only' : type.value} · up to ${recipients.toLocaleString()} recipients · sample estimate ${measure(sample(message.value.trim())).parts} SMS parts per recipient. Actual personalized message length can vary.`;
        document.getElementById('sms-confirm-message').textContent = sample(message.value.trim());
        dialog.returnValue = '';
        dialog.showModal();
    });
    dialog.addEventListener('close', async function () {
        if (dialog.returnValue !== 'send' || sending) return;
        if (audience.value === 'individual' && !selectedRecipient) return;
        sending = true; type.disabled = true; audience.disabled = true; templateSelect.disabled = true; searchInput.disabled = true; searchResults.disabled = true; message.readOnly = true; update();
        button.textContent = 'Sending SMS…'; feedback.textContent = 'Sending is in progress. Keep this page open.';
        const payload = new FormData();
        payload.set('_token', form.elements._token.value);
        if (audience.value === 'customer_type') payload.set('customer_type', type.value);
        if (audience.value === 'individual') { payload.set('client_id', clientId.value); payload.set('confirmed_mobile', selectedRecipient.mobile_number); }
        payload.set('sms_content', message.value.trim()); payload.set('request_id', requestId);
        payload.set('audience', audience.value); payload.set('template_key', templateSelect.value);
        uncertain = true;
        try {
            const response = await fetch(form.action, { method: 'POST', body: payload, headers: { Accept: 'application/json' } });
            const result = await response.json();
            if (!response.ok) {
                uncertain = response.status !== 422;
                const errors = Object.values(result.errors || {}).flat().join(' ');
                throw new Error(errors || result.message || 'Unable to complete sending. Check Message History.');
            }
            uncertain = false;
            feedback.textContent = result.message;
            const link = document.createElement('a'); link.href = result.history_url; link.textContent = 'View campaign results'; feedback.appendChild(link);
            if (result.total_failed === 0) { message.value = ''; requestId = newId(); }
            else uncertain = true;
        } catch (error) {
            // Network failures may happen after the server has started sending.
            if (!(error instanceof Error) || error.name === 'TypeError' || error.name === 'SyntaxError') uncertain = true;
            feedback.textContent = error.message || 'Connection interrupted. Check Message History before sending again.';
        } finally {
            sending = false; type.disabled = audience.value !== 'customer_type'; audience.disabled = false; templateSelect.disabled = false; searchInput.disabled = false; searchResults.disabled = searchResults.options.length === 0; message.readOnly = false;
            button.textContent = uncertain ? 'Check Message History before resending' : 'Review SMS'; update();
        }
    });
    update();
    if (templateSelect.value) templateSelect.dispatchEvent(new Event('change'));
}());
