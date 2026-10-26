(function (window, document, $) {
    'use strict';
    if (window.SalesOrderEditorForms) return;

    var snapshots = new WeakMap();
    var selector = '[data-so-editor-form]';
    var money = new Intl.NumberFormat('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    var maxReceiptBytes = 10048 * 1024;

    function field(form, name) { return form.elements.namedItem(name); }
    function current(id) { return document.getElementById('so-editor-' + id); }
    function currentValue(id) { var element = current(id); return element ? element.value : ''; }
    function selectedOption(input) {
        return input && input.list ? Array.from(input.list.options).find(function (option) { return option.value === input.value; }) : null;
    }
    function clearErrors(form) {
        form.classList.remove('was-validated');
        form.querySelectorAll('.is-invalid').forEach(function (input) { input.classList.remove('is-invalid'); input.removeAttribute('aria-invalid'); });
        form.querySelectorAll('.has-error').forEach(function (group) { group.classList.remove('has-error'); });
        form.querySelectorAll('.invalid-feedback').forEach(function (element) { element.textContent = ''; });
        form.querySelector('[data-form-alert]').hidden = true;
    }
    function showAlert(form, message) {
        var element = form.querySelector('[data-form-alert]');
        element.textContent = message;
        element.hidden = false;
    }
    function invalid(input, message) {
        if (!input) return;
        input.classList.add('is-invalid');
        input.setAttribute('aria-invalid', 'true');
        input.closest('.so-field')?.classList.add('has-error');
        var errorId = input.getAttribute('aria-describedby');
        var error = errorId && document.getElementById(errorId);
        if (error) error.textContent = message;
    }
    function busy(form, saving) {
        form.dataset.saving = saving ? '1' : '0';
        form.setAttribute('aria-busy', String(saving));
        form.querySelectorAll('[type="submit"], [type="reset"], [data-bs-dismiss="modal"]').forEach(function (button) { button.disabled = saving; });
        form.querySelector('[data-submit-status]').hidden = !saving;
    }
    function updateProduct(form) {
        var input = form.querySelector('[data-product-input]');
        if (!input) return;
        var option = selectedOption(input);
        var standard = Number(option ? option.getAttribute('data-price') : 0) || 0;
        var custom = Number(form.querySelector('[data-product-price-input]').value) || 0;
        var quantity = Number(form.querySelector('[data-product-quantity]').value) || 0;
        var total = (custom > 0 ? custom : standard) * quantity;
        form.querySelector('[data-product-total]').textContent = money.format(Number.isFinite(total) ? total : 0);
        form.querySelector('[data-product-price]').textContent = option
            ? 'Client unit price: ₱ ' + money.format(standard)
            : 'For a custom item description, enter a custom unit price.';
    }
    function preview(form, source) {
        var holder = form.querySelector('.so-payment-preview');
        if (!holder) return;
        holder.replaceChildren();
        holder.hidden = !source;
        if (source) {
            var image = document.createElement('img');
            image.alt = 'Payment receipt preview';
            image.src = source;
            holder.appendChild(image);
        }
    }
    function capture(form) {
        var holder = form.querySelector('.so-payment-preview');
        snapshots.set(form, {
            fields: Array.from(form.querySelectorAll('input:not([type="file"]), select, textarea, [data-record-id]')).map(function (input) { return [input, input.value]; }),
            image: holder?.querySelector('img')?.getAttribute('src') || ''
        });
    }
    function restore(form) {
        var snapshot = snapshots.get(form);
        if (!snapshot) return;
        snapshot.fields.forEach(function (entry) { entry[0].value = entry[1]; });
        var file = form.querySelector('[data-payment-image]');
        if (file) file.value = '';
        preview(form, snapshot.image);
        clearErrors(form);
        updateProduct(form);
    }
    function beginNew(type) {
        var ids = { payment: 'AddPayment', delivery: 'AddProductDelivery', product: 'AddProduct' };
        var form = current(ids[type]);
        if (!form) return;
        form.soNativeReset = true;
        form.reset();
        form.soNativeReset = false;
        form.querySelector('[data-record-id]').value = '0';
        if (type === 'payment') {
            field(form, 'receivable_idx_payment').value = window.salesOrderEditorReceivableId();
            preview(form, '');
        }
        clearErrors(form);
        updateProduct(form);
    }
    function receiptValid(form) {
        var input = form.querySelector('[data-payment-image]');
        var file = input?.files[0];
        if (!file) return true;
        if (!/\.(jpe?g|png|svg)$/i.test(file.name) || file.size > maxReceiptBytes) {
            invalid(input, 'Choose a JPG, PNG, or SVG image up to 10 MB.');
            return false;
        }
        return true;
    }
    function validate(form) {
        var valid = true;
        form.querySelectorAll('input, select, textarea').forEach(function (input) {
            if (!input.checkValidity()) {
                invalid(input, input.validationMessage);
                valid = false;
            }
        });
        if (form.dataset.soEditorForm === 'product') {
            var product = form.querySelector('[data-product-input]');
            if (!selectedOption(product) && !(Number(form.querySelector('[data-product-price-input]').value) > 0)) {
                invalid(form.querySelector('[data-product-price-input]'), 'Enter a unit price for a custom item.');
                valid = false;
            }
        }
        if (form.dataset.soEditorForm === 'delivery' && !selectedOption(form.querySelector('[data-delivery-product]'))) {
            invalid(form.querySelector('[data-delivery-product]'), 'Select a product already added to this Sales Order.');
            valid = false;
        }
        if (form.dataset.soEditorForm === 'payment' && !receiptValid(form)) valid = false;
        if (!valid) form.querySelector('.is-invalid')?.focus();
        return valid;
    }
    function payload(form) {
        var type = form.dataset.soEditorForm;
        if (type === 'payment') {
            var data = new window.FormData(form);
            data.set('client_idx', window.salesOrderEditorClientId());
            return data;
        }
        var data = {
            _token: field(form, '_token').value,
            sales_order_id: window.salesOrderEditorId(),
            receivable_id: window.salesOrderEditorReceivableId()
        };
        if (type === 'product') {
            var input = form.querySelector('[data-product-input]');
            var option = selectedOption(input);
            Object.assign(data, {
                sales_order_component_id: form.querySelector('[data-record-id]').value || 0,
                client_idx: window.salesOrderEditorClientId(),
                branch_idx: currentValue('company_header'),
                product_idx: option ? option.getAttribute('data-id') : 0,
                item_description: input.value.trim(),
                product_manual_price: form.querySelector('[data-product-price-input]').value || 0,
                order_quantity: form.querySelector('[data-product-quantity]').value,
                sales_order_net_percentage: currentValue('sales_order_net_percentage'),
                sales_order_withholding_tax: currentValue('sales_order_withholding_tax')
            });
        } else {
            var option = selectedOption(form.querySelector('[data-delivery-product]'));
            Object.assign(data, {
                sales_order_delivery_details_id: form.querySelector('[data-record-id]').value || 0,
                sales_order_component_idx: option.getAttribute('data-id'),
                sales_order_component_product_idx: option.getAttribute('product-id')
            });
            ['sales_order_delivery_date', 'sales_order_delivery_quantity', 'sales_order_delivery_withdrawal_reference', 'sales_order_delivery_hauler_details', 'sales_order_delivery_remarks'].forEach(function (name) {
                data[name] = field(form, name).value;
            });
        }
        return data;
    }
    function showServerErrors(form, xhr) {
        var errors = xhr.responseJSON?.errors || {};
        Object.entries(errors).forEach(function (entry) {
            var input = Array.from(form.querySelectorAll('[data-errors]')).find(function (element) {
                return element.dataset.errors.split(' ').includes(entry[0]);
            });
            invalid(input, Array.isArray(entry[1]) ? entry[1][0] : entry[1]);
        });
        showAlert(form, Object.keys(errors).length
            ? 'Please check the highlighted fields.'
            : xhr.status === 419 ? 'Your session expired. Refresh this page before saving again.'
            : 'Unable to save. Your entries are still here; please try again.');
        form.querySelector('.is-invalid')?.focus();
    }
    function refresh(type) {
        var names = type === 'delivery' ? ['LoadProductListDelivery']
            : type === 'payment' ? ['LoadPayment', 'LoadReceivables', 'LoadProduct_under_sales_order']
            : ['LoadProduct_under_sales_order', 'LoadReceivables'];
        names.forEach(function (name) { if (typeof window[name] === 'function') window[name](); });
    }
    $(document).on('submit.soEditorForms', selector, function (event) {
        event.preventDefault();
        var form = this;
        if (form.dataset.saving === '1') return;
        clearErrors(form);
        if (!validate(form)) return;
        var data = payload(form);
        var multipart = form.dataset.soEditorForm === 'payment';
        busy(form, true);
        window.salesOrderEditorRequest({
            url: form.action, type: 'POST', data: data, dataType: 'json', timeout: 30000,
            processData: !multipart, contentType: multipart ? false : 'application/x-www-form-urlencoded; charset=UTF-8',
            success: function (response) {
                busy(form, false);
                $('#sw_on').text(response.success || 'Saved successfully.');
                $('#switch_notice_on').show();
                window.setTimeout(function () { $('#switch_notice_on').fadeOut('fast'); }, 2000);
                refresh(form.dataset.soEditorForm);
                $(form.closest('.modal')).modal('hide');
            },
            error: function (xhr) { showServerErrors(form, xhr); },
            complete: function () { busy(form, false); }
        });
    });
    $(document).on('input.soEditorForms change.soEditorForms', selector + ' input', function () {
        var form = this.closest(selector);
        if (form.dataset.soEditorForm === 'product') updateProduct(form);
    });
    $(document).on('show.bs.modal.soEditorForms', '.so-editor-entry', function (event) {
        var form = this.querySelector(selector);
        if (event.target !== this || !form) return;
        if (event.relatedTarget || (form.dataset.soEditorForm === 'product' && form.dataset.editing === '0')) beginNew(form.dataset.soEditorForm);
        clearErrors(form);
        busy(form, false);
        var title = form.querySelector('[data-record-title]');
        if (title) title.textContent = Number(form.querySelector('[data-record-id]').value) > 0 ? title.dataset.editTitle : title.dataset.createTitle;
        updateProduct(form);
        capture(form);
    });
    $(document).on('hide.bs.modal.soEditorForms', '.so-editor-entry', function (event) {
        if (event.target === this && this.querySelector(selector)?.dataset.saving === '1') event.preventDefault();
    });
    $(document).on('reset.soEditorForms', selector, function (event) {
        if (this.soNativeReset) return;
        event.preventDefault();
        restore(this);
    });
    $(document).on('change.soEditorForms', selector + ' [data-payment-image]', function () {
        var form = this.closest(selector);
        clearErrors(form);
        if (!receiptValid(form)) { preview(form, ''); return; }
        var file = this.files[0];
        if (!file) { preview(form, ''); return; }
        var input = this;
        var reader = new window.FileReader();
        reader.onload = function () {
            if (form.isConnected && input.files[0] === file) preview(form, reader.result);
        };
        reader.readAsDataURL(file);
    });

    window.SalesOrderEditorForms = {
        beginNew: beginNew,
        setPaymentImage: function (base64) {
            var form = current('AddPayment');
            if (!form) return;
            var mime = base64 && base64.startsWith('iVBOR') ? 'image/png' : 'image/jpeg';
            preview(form, base64 ? 'data:' + mime + ';base64,' + base64 : '');
        }
    };
    window.ResetPaymentForm = function () { beginNew('payment'); };
    window.ResetDeliveryForm = function () { beginNew('delivery'); };
})(window, document, window.jQuery);
