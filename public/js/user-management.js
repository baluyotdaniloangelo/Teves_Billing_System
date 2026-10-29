(function (root, $) {
    'use strict';

    const configNode = document.getElementById('user-management-config');
    if (!configNode || !$ || !$.fn.DataTable) return;
    const urls = JSON.parse(configNode.textContent);
    const canMonitorPresence = urls.canMonitorPresence === true;
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const roles = { SUAdmin: 'Super admin', Admin: 'Admin', Supervisor: 'Supervisor', Accounting_Staff: 'Accounting staff', Encoder: 'Encoder' };
    const createForm = document.getElementById('CreateUserform');
    const editForm = document.getElementById('UpdateUserform');
    let editSnapshot = '';
    let readingUser = false;
    let searchTimer;
    let loadingUsers = false;
    const monitorState = document.getElementById('user-monitor-state');
    const monitorUpdated = document.getElementById('user-monitor-updated');

    function escape(value) {
        const span = document.createElement('span');
        span.textContent = value == null ? '' : String(value);
        return span.innerHTML;
    }

    function feedback(message, kind) {
        const node = document.getElementById('user-feedback');
        node.textContent = message;
        node.className = 'um-feedback' + (kind ? ' is-' + kind : '');
        node.hidden = false;
    }

    function alertIn(container, message) {
        const node = container.querySelector('.um-form-alert');
        node.textContent = message;
        node.hidden = !message;
    }

    function clearErrors(form) {
        form.classList.remove('was-validated');
        form.querySelectorAll('.is-invalid').forEach(node => {
            node.classList.remove('is-invalid');
            node.removeAttribute('aria-invalid');
        });
        form.querySelectorAll('[data-error-for]').forEach(node => { node.textContent = ''; node.style.display = ''; });
        alertIn(form, '');
    }

    function showErrors(container, error) {
        const errors = error.errors || {};
        Object.keys(errors).forEach(name => {
            const field = container.elements && container.elements.namedItem(name);
            const label = Array.from(container.querySelectorAll('[data-error-for]')).find(node => node.dataset.errorFor === name);
            if (field && field.classList) { field.classList.add('is-invalid'); field.setAttribute('aria-invalid', 'true'); }
            if (label) { label.textContent = [].concat(errors[name]).join(' '); label.style.display = 'block'; }
        });
        alertIn(container, error.message || 'Unable to complete the request. Please try again.');
        const invalid = container.querySelector('.is-invalid');
        if (invalid) invalid.focus();
    }

    async function request(url, data, method) {
        const options = {
            method: method || 'POST', credentials: 'same-origin',
            headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' }
        };
        if (options.method === 'GET') {
            const target = new URL(url, root.location.href);
            Object.entries(data || {}).forEach(([name, value]) => target.searchParams.set(name, value));
            url = target.href;
        } else {
            options.headers['Content-Type'] = 'application/json';
            options.body = JSON.stringify(data || {});
        }
        const response = await root.fetch(url, options);
        if (response.redirected || !response.headers.get('content-type')?.includes('application/json')) {
            throw { message: 'Your session may have expired. Refresh this page and sign in again.' };
        }
        const result = await response.json();
        if (!response.ok) throw result;
        return result;
    }

    async function busy(container, operation) {
        if (container.dataset.busy === 'true') return;
        container.dataset.busy = 'true';
        container.setAttribute('aria-busy', 'true');
        // Freeze the form while saving so an unsaved edit cannot be silently lost.
        const controls = Array.from(container.querySelectorAll('button, input, select'));
        const disabled = controls.map(node => node.disabled);
        controls.forEach(node => { node.disabled = true; });
        try { await operation(); }
        catch (error) { showErrors(container, error); }
        finally {
            controls.forEach((node, index) => { node.disabled = disabled[index]; });
            delete container.dataset.busy;
            container.removeAttribute('aria-busy');
            container.querySelector('.is-invalid')?.focus();
        }
    }

    function modal(id) { return root.bootstrap.Modal.getOrCreateInstance(document.getElementById(id)); }

    function hideThen(id, next) {
        const node = document.getElementById(id);
        node.addEventListener('hidden.bs.modal', () => { if (next) next(); }, { once: true });
        // Let the save operation release its busy state before dismissing the dialog.
        root.setTimeout(() => modal(id).hide(), 0);
    }

    function populateDetails(container, user) {
        container.querySelectorAll('[data-user-detail]').forEach(node => {
            node.textContent = node.dataset.userDetail === 'user_type' ? (roles[user.user_type] || user.user_type) : (user[node.dataset.userDetail] || '—');
        });
    }

    function dateCell(value, type) {
        if (type !== 'display') return value || '';
        if (!value || String(value).startsWith('0000-00-00')) return '—';
        const date = new Date(String(value).replace(' ', 'T'));
        return Number.isNaN(date.getTime()) ? escape(value) : escape(date.toLocaleDateString('en-PH', { month: 'short', day: 'numeric', year: 'numeric' }));
    }

    function presenceCell(value, type) {
        if (type !== 'display') return value === null ? '' : (value ? 'Online' : 'Offline');
        if (value === null) return '<span class="um-presence um-presence-unknown">Unavailable</span>';
        return '<span class="um-presence um-presence-' + (value ? 'online' : 'offline') + '"><i aria-hidden="true"></i>' + (value ? 'Online' : 'Offline') + '</span>';
    }

    function lastSeenCell(value, type) {
        if (type !== 'display') return value || '';
        if (!value) return '<span class="um-last-seen">Not recorded yet</span>';
        const date = new Date(value);
        if (Number.isNaN(date.getTime())) return '—';
        const minutes = Math.max(0, Math.floor((Date.now() - date.getTime()) / 60000));
        const label = minutes < 1 ? 'Just now' : minutes < 60 ? minutes + ' min ago' : date.toLocaleString('en-PH', { timeZone: urls.timezone, month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit' });
        const exact = date.toLocaleString('en-PH', { timeZone: urls.timezone, year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit', second: '2-digit' });
        return '<time class="um-last-seen" datetime="' + escape(date.toISOString()) + '" title="' + escape(exact) + '">' + escape(label) + '</time>';
    }

    const table = $('#userList').DataTable({
        serverSide: true, processing: true, responsive: true, searchDelay: 300,
        pageLength: 10, lengthMenu: [10, 20, 50, 100], order: [[1, 'asc']],
        layout: { topStart: null, topEnd: null, bottomStart: ['pageLength', 'info'], bottomEnd: 'paging' },
        ajax: {
            url: urls.list, type: 'POST', headers: { 'X-CSRF-TOKEN': csrf, Accept: 'application/json' },
            data: data => {
                data.role = document.getElementById('user-role-filter').value;
                data.status = document.getElementById('user-status-filter').value;
                if (canMonitorPresence) data.presence = document.getElementById('user-presence-filter').value;
            },
            beforeSend: () => { loadingUsers = true; },
            complete: () => { loadingUsers = false; },
            dataSrc: json => {
                if (json.summary) document.querySelectorAll('[data-user-count]').forEach(node => { node.textContent = json.summary[node.dataset.userCount] ?? '—'; });
                if (json.presence) document.querySelectorAll('[data-online-window]').forEach(node => { node.textContent = Math.ceil(json.presence.window_seconds / 60); });
                if (monitorState) monitorState.textContent = json.presence && !json.presence.available ? 'Online tracking is not available yet. Please contact your administrator.' : 'Presence refreshes every 30 seconds.';
                if (monitorUpdated) monitorUpdated.textContent = 'Updated ' + new Date().toLocaleTimeString('en-PH', { hour: 'numeric', minute: '2-digit', second: '2-digit' });
                return json.data;
            },
            error: () => {
                if (monitorState) monitorState.textContent = 'Connection lost. Presence may be out of date; retrying on the next refresh.';
                table.processing(false);
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false, responsivePriority: 11 },
            { data: 'user_real_name', responsivePriority: 1, render: (value, type) => {
                if (type !== 'display') return value;
                const initials = String(value || '?').trim().split(/\s+/).slice(0, 2).map(word => Array.from(word)[0]).join('').toUpperCase();
                return '<div class="um-person"><span class="um-avatar" aria-hidden="true">' + escape(initials) + '</span><strong>' + escape(value) + '</strong></div>';
            } },
            { data: 'user_job_title', render: value => escape(value || '—'), responsivePriority: 10 },
            { data: 'user_name', render: value => escape(value), responsivePriority: 6 },
            { data: 'user_type', render: (value, type) => type === 'display' ? '<span class="um-pill">' + escape(roles[value] || value) + '</span>' : value, responsivePriority: 4 },
            { data: 'user_status', render: (value, type) => type === 'display' ? '<span class="um-pill um-status-' + (value === 'Active' ? 'active' : 'inactive') + '">' + escape(value) + '</span>' : value, responsivePriority: 7 },
            { data: 'is_online', render: presenceCell, orderable: false, searchable: false, className: 'all', responsivePriority: 3 },
            { data: 'last_seen_at', render: lastSeenCell, orderable: false, searchable: false, responsivePriority: 5 },
            { data: 'user_email_address', render: value => escape(value || '—'), responsivePriority: 9 },
            { data: 'created_at_dt_format', name: 'created_at', render: dateCell, searchable: false, className: 'none' },
            { data: 'updated_at_dt_format', name: 'updated_at', render: dateCell, searchable: false, className: 'none' },
            { data: 'action', orderable: false, searchable: false, responsivePriority: 2 }
        ].filter(column => canMonitorPresence || !['is_online', 'last_seen_at'].includes(column.data)),
        language: { emptyTable: 'No user accounts found.', zeroRecords: 'No users match your search or filters.', info: 'Showing _START_–_END_ of _TOTAL_ users', infoEmpty: '0 users', lengthMenu: '_MENU_ per page', processing: 'Loading users…' }
    });

    document.getElementById('user-search').addEventListener('input', event => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => table.search(event.target.value).draw(), 300);
    });
    ['user-role-filter', 'user-status-filter', 'user-presence-filter'].forEach(id => document.getElementById(id)?.addEventListener('change', () => table.ajax.reload()));
    document.getElementById('user-reset-filters').addEventListener('click', () => {
        clearTimeout(searchTimer);
        ['user-search', 'user-role-filter', 'user-status-filter', 'user-presence-filter'].forEach(id => { const input = document.getElementById(id); if (input) input.value = ''; });
        table.search('').ajax.reload();
    });
    function refreshPresence() {
        if (!canMonitorPresence || document.visibilityState === 'hidden' || loadingUsers || readingUser || document.querySelector('.um-modal.show')) return;
        table.ajax.reload(null, false);
    }
    root.setInterval(refreshPresence, 30000);
    document.addEventListener('visibilitychange', refreshPresence);
    root.addEventListener('online', refreshPresence);
    document.getElementById('CreateUserModal').addEventListener('show.bs.modal', () => { createForm.reset(); clearErrors(createForm); });
    [createForm, editForm].forEach(form => {
        form.elements.user_type.addEventListener('change', () => {
            form.elements.user_access.value = ['Admin', 'SUAdmin'].includes(form.elements.user_type.value) ? 'ALL' : 'BYBRANCH';
        });
        form.addEventListener('input', () => { if (form === editForm) form.querySelector('[type="submit"]').disabled = snapshot(form) === editSnapshot; });
        form.addEventListener('change', () => { if (form === editForm) form.querySelector('[type="submit"]').disabled = snapshot(form) === editSnapshot; });
        form.addEventListener('submit', event => {
            event.preventDefault();
            clearErrors(form);
            form.classList.add('was-validated');
            if (!form.checkValidity()) { form.querySelector(':invalid')?.focus(); return; }
            const payload = Object.fromEntries(new FormData(form));
            const editing = form === editForm;
            const id = editing ? 'UpdateUserModal' : 'CreateUserModal';
            busy(form, async () => {
                const result = await request(editing ? urls.update : urls.create, payload);
                feedback(result.success + (result.warning ? ' ' + result.warning : ''), result.warning ? 'warning' : '');
                table.ajax.reload(null, false);
                hideThen(id, payload.user_access === 'BYBRANCH' ? () => openAccess('branch', editing ? payload.userID : result.user_id) : null);
            });
        });
    });

    function snapshot(form) { return JSON.stringify(Object.fromEntries(new FormData(form))); }

    document.addEventListener('click', async event => {
        const toggle = event.target.closest('[data-toggle-password]');
        if (toggle) {
            const field = document.getElementById(toggle.dataset.togglePassword);
            field.type = field.type === 'password' ? 'text' : 'password';
            toggle.setAttribute('aria-pressed', String(field.type === 'text'));
            toggle.setAttribute('aria-label', field.type === 'text' ? 'Hide password' : 'Show password');
            toggle.querySelector('i').className = field.type === 'text' ? 'bi bi-eye-slash' : 'bi bi-eye';
            return;
        }
        const button = event.target.closest('[data-user-action]');
        if (!button || button.disabled || readingUser) return;
        const id = button.dataset.id;
        const action = button.dataset.userAction;
        if (action === 'branch' || action === 'category') { openAccess(action, id); return; }
        readingUser = true;
        button.disabled = true;
        try {
            const user = await request(urls.info, { UserID: id });
            if (action === 'edit') {
                editForm.reset(); clearErrors(editForm);
                ['user_real_name', 'user_job_title', 'user_name', 'user_email_address', 'user_type', 'user_status'].forEach(name => { editForm.elements[name].value = user[name] || ''; });
                editForm.elements.user_access.value = user.user_branch_access_type;
                editForm.elements.userID.value = id;
                editSnapshot = snapshot(editForm);
                editForm.querySelector('[type="submit"]').disabled = true;
                modal('UpdateUserModal').show();
            } else if (action === 'delete') {
                const node = document.getElementById('UserDeleteModal');
                alertIn(node, ''); populateDetails(node, user);
                document.getElementById('deleteUserConfirmed').value = id;
                modal('UserDeleteModal').show();
            }
        } catch (error) { feedback(error.message || 'Unable to load this user.', 'error'); }
        finally { button.disabled = false; readingUser = false; }
    });

    document.getElementById('deleteUserConfirmed').addEventListener('click', event => {
        const node = document.getElementById('UserDeleteModal');
        const id = event.currentTarget.value;
        busy(node, async () => {
            alertIn(node, '');
            const result = await request(urls.delete, { userID: id });
            hideThen('UserDeleteModal');
            feedback(result.success); table.ajax.reload(null, false);
        });
    });

    async function openAccess(type, id) {
        if (readingUser) return;
        readingUser = true;
        try {
            const result = await request(urls[type + 'List'], { UserID: id }, 'GET');
            const node = document.getElementById(type + '-access-modal');
            node.dataset.userId = id;
            alertIn(node, ''); populateDetails(node, result.user);
            node.querySelector('[data-access-search]').value = '';
            const list = node.querySelector('[data-access-list]');
            list.replaceChildren();
            result.data.forEach(row => {
                const item = document.createElement('label');
                item.className = 'um-access-option';
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox'; checkbox.value = row[type === 'branch' ? 'branch_id' : 'category_id']; checkbox.checked = !!row.selected;
                const text = document.createElement('span');
                const title = document.createElement('strong');
                title.textContent = row[type === 'branch' ? 'branch_name' : 'category_name'];
                text.appendChild(title);
                if (type === 'branch') { const code = document.createElement('small'); code.textContent = row.branch_code; text.appendChild(code); }
                item.dataset.search = text.textContent.toLocaleLowerCase();
                item.append(checkbox, text); list.appendChild(item);
            });
            updateSelection(node);
            modal(type + '-access-modal').show();
        } catch (error) { feedback(error.message || 'Unable to load user access.', 'error'); }
        finally { readingUser = false; }
    }

    function updateSelection(node) {
        node.querySelector('[data-selected-count]').textContent = node.querySelectorAll('[data-access-list] input:checked').length + ' selected';
        node.querySelector('[data-access-empty]').hidden = !!Array.from(node.querySelectorAll('.um-access-option')).find(item => !item.hidden);
    }

    document.querySelectorAll('[data-access-type]').forEach(node => {
        node.querySelector('[data-access-search]').addEventListener('input', event => {
            const term = event.target.value.trim().toLocaleLowerCase();
            node.querySelectorAll('.um-access-option').forEach(item => { item.hidden = !item.dataset.search.includes(term); });
            updateSelection(node);
        });
        node.querySelector('[data-access-list]').addEventListener('change', () => updateSelection(node));
        node.querySelectorAll('[data-select-access]').forEach(button => button.addEventListener('click', () => {
            node.querySelectorAll('.um-access-option').forEach(item => { if (!item.hidden) item.querySelector('input').checked = button.dataset.selectAccess === 'all'; });
            updateSelection(node);
        }));
        node.querySelector('[data-save-access]').addEventListener('click', () => {
            const type = node.dataset.accessType;
            const selected = Array.from(node.querySelectorAll('[data-access-list] input:checked')).map(input => input.value);
            const payload = { userID: node.dataset.userId, [type === 'branch' ? 'site_items' : 'category_items']: selected };
            busy(node, async () => {
                alertIn(node, '');
                const result = await request(urls[type + 'Save'], payload);
                hideThen(type + '-access-modal'); feedback(result.success);
            });
        });
    });

    document.querySelectorAll('.um-modal').forEach(node => {
        node.addEventListener('hide.bs.modal', event => { if (node.dataset.busy === 'true' || node.querySelector('[data-busy="true"]')) event.preventDefault(); });
        node.addEventListener('hidden.bs.modal', () => {
            node.querySelectorAll('input[name="user_password"]').forEach(input => { input.value = ''; input.type = 'password'; });
            node.querySelectorAll('[data-toggle-password]').forEach(button => { button.setAttribute('aria-pressed', 'false'); button.setAttribute('aria-label', 'Show password'); button.querySelector('i').className = 'bi bi-eye'; });
        });
    });
}(window, window.jQuery));
