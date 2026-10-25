(function () {
    'use strict';
    const form = document.getElementById('SmsTemplateEditor');
    if (!form) return;
    const library = JSON.parse(document.getElementById('sms-template-data').textContent);
    const message = form.elements.message;
    const scope = form.elements.scope;
    const preview = document.getElementById('template-sample-preview');
    const tokens = form.querySelectorAll('[data-sms-token]');
    function update() {
        const fields = { client_name: 'Juan Dela Cruz', client_first_name: 'Juan', salutation: 'Mr.', business_name: library.business_name, business_signature: library.business_signature, contact_number: library.contact_number, order_number: 'LPG-000123' };
        preview.textContent = message.value.replace(/\{([^{}]+)\}/g, (whole, key) => Object.prototype.hasOwnProperty.call(fields, key) ? fields[key] : whole) || 'Your message will appear here.';
        document.getElementById('template-character-count').textContent = Array.from(message.value).length;
        tokens.forEach(button => { button.hidden = button.dataset.smsToken === 'order_number' && scope.value !== 'order'; });
    }
    tokens.forEach(button => button.addEventListener('click', function () {
        const token = '{' + button.dataset.smsToken + '}';
        const start = message.selectionStart, end = message.selectionEnd;
        if (message.value.length - (end - start) + token.length > message.maxLength) return;
        message.setRangeText(token, start, end, 'end'); message.focus(); update();
    }));
    message.addEventListener('input', update); scope.addEventListener('change', update); update();
}());
