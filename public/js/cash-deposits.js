(function (document, window) {
    'use strict';
    var page = document.querySelector('.cash-deposits-page');
    if (!page) return;
    var token = document.querySelector('meta[name="csrf-token"]').content;
    var form = document.getElementById('cd-form');
    var formModal = window.bootstrap.Modal.getOrCreateInstance(document.getElementById('cd-form-modal'));
    var viewModal = window.bootstrap.Modal.getOrCreateInstance(document.getElementById('cd-view-modal'));
    var deleteModal = window.bootstrap.Modal.getOrCreateInstance(document.getElementById('cd-delete-modal'));
    var photo = document.getElementById('cd-photo');
    var preview = document.getElementById('cd-photo-preview');
    var viewPhoto = document.getElementById('cd-view-photo');
    var viewPhotoLink = document.getElementById('cd-photo-link');
    var noPhoto = document.getElementById('cd-no-photo');
    var currentId = null;
    var deleteId = null;
    var money = new Intl.NumberFormat('en-PH', {style:'currency', currency:'PHP'});

    function rowData(button) {
        return button.closest('tr').querySelector('.cd-view').dataset;
    }
    function clearErrors() {
        document.getElementById('cd-form-alert').hidden = true;
        form.querySelectorAll('.is-invalid').forEach(function (input) { input.classList.remove('is-invalid'); });
        form.querySelectorAll('[data-error-for]').forEach(function (slot) { slot.textContent = ''; });
    }
    function showPreview(src) {
        preview.hidden = !src;
        if (src) preview.src = src; else preview.removeAttribute('src');
    }
    function url(template, id) { return template.replace('__ID__', encodeURIComponent(id)); }
    function setText(key, value) {
        document.querySelector('[data-cd-view="' + key + '"]').textContent = value || '—';
    }
    viewPhoto.addEventListener('error', function () {
        viewPhoto.hidden = true;
        viewPhotoLink.hidden = true;
        noPhoto.hidden = false;
    });
    document.getElementById('cd-new').addEventListener('click', function () {
        currentId = null;
        form.reset();
        clearErrors();
        showPreview('');
        document.getElementById('cd-form-title').textContent = 'New cash deposit';
        document.getElementById('cd-photo-required').hidden = false;
        formModal.show();
    });
    document.querySelectorAll('.cd-edit').forEach(function (button) {
        button.addEventListener('click', function () {
            var data = rowData(button);
            currentId = data.id;
            form.reset();
            clearErrors();
            form.elements.branch_idx.value = data.branchId;
            form.elements.bank_idx.value = data.bankId;
            form.elements.cash_deposit_date.value = data.date;
            form.elements.cash_deposit_amount.value = data.amount;
            form.elements.cash_deposit_reference.value = data.reference;
            form.elements.cash_deposit_remarks.value = data.remarks || '';
            showPreview(data.photo);
            document.getElementById('cd-form-title').textContent = 'Edit cash deposit';
            document.getElementById('cd-photo-required').hidden = true;
            formModal.show();
        });
    });
    document.querySelectorAll('.cd-view').forEach(function (button) {
        button.addEventListener('click', function () {
            var data = button.dataset;
            setText('branch', data.branch);
            setText('bank', data.bank);
            setText('date', data.date);
            setText('reference', data.reference);
            setText('amount', money.format(Number(data.amount) || 0));
            setText('encoder', data.encoder);
            setText('remarks', data.remarks);
            viewPhoto.hidden = !data.photo;
            viewPhotoLink.hidden = !data.photo;
            noPhoto.hidden = !!data.photo;
            viewPhotoLink.href = data.photo || '#';
            if (data.photo) viewPhoto.src = data.photo; else viewPhoto.removeAttribute('src');
            viewModal.show();
        });
    });
    document.querySelectorAll('.cd-delete').forEach(function (button) {
        button.addEventListener('click', function () {
            deleteId = button.dataset.id;
            document.getElementById('cd-delete-reference').textContent = rowData(button).reference;
            document.getElementById('cd-delete-alert').hidden = true;
            deleteModal.show();
        });
    });
    photo.addEventListener('change', function () {
        if (photo.files[0]) showPreview(URL.createObjectURL(photo.files[0]));
        else showPreview('');
    });
    form.addEventListener('submit', async function (event) {
        event.preventDefault();
        clearErrors();
        var button = document.getElementById('cd-save');
        button.disabled = true;
        var payload = new FormData(form);
        if (currentId) payload.append('_method', 'PUT');
        try {
            var response = await fetch(currentId ? url(page.dataset.updateUrl, currentId) : page.dataset.storeUrl, {
                method: 'POST', headers: {'X-CSRF-TOKEN': token, 'Accept':'application/json', 'X-Requested-With':'XMLHttpRequest'}, body: payload
            });
            var result = await response.json();
            if (!response.ok) {
                if (result.errors) Object.keys(result.errors).forEach(function (field) {
                    var input = form.elements[field];
                    var slot = form.querySelector('[data-error-for="' + field + '"]');
                    if (input) input.classList.add('is-invalid');
                    if (slot) slot.textContent = result.errors[field][0];
                });
                throw new Error(result.message || 'Unable to save deposit.');
            }
            window.location.reload();
        } catch (error) {
            var alert = document.getElementById('cd-form-alert');
            alert.textContent = error.message;
            alert.hidden = false;
        } finally { button.disabled = false; }
    });
    document.getElementById('cd-confirm-delete').addEventListener('click', async function () {
        var button = this;
        button.disabled = true;
        try {
            var response = await fetch(url(page.dataset.deleteUrl, deleteId), {
                method:'DELETE', headers:{'X-CSRF-TOKEN':token, 'Accept':'application/json', 'X-Requested-With':'XMLHttpRequest'}
            });
            if (!response.ok) {
                var result = await response.json();
                throw new Error(result.message || 'Unable to delete deposit.');
            }
            window.location.reload();
        } catch (error) {
            var alert = document.getElementById('cd-delete-alert');
            alert.textContent = error.message;
            alert.hidden = false;
        } finally { button.disabled = false; }
    });
})(document, window);
