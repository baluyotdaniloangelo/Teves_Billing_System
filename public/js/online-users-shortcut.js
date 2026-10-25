(function (root) {
    'use strict';

    const menu = document.querySelector('[data-online-users]');
    if (!menu || !root.fetch) return;
    const button = menu.querySelector('#online-users-shortcut');
    const badge = menu.querySelector('[data-online-count]');
    const headingCount = menu.querySelector('[data-online-heading-count]');
    const list = menu.querySelector('[data-online-list]');
    const status = menu.querySelector('[data-online-status]');
    const updated = menu.querySelector('[data-online-updated]');
    const refresh = menu.querySelector('[data-online-refresh]');
    const roles = { SUAdmin: 'Super admin', Admin: 'Admin', Supervisor: 'Supervisor', Accounting_Staff: 'Accounting staff', Encoder: 'Encoder' };
    let loading = false;
    let stopped = false;
    let lastAttempt = 0;

    function display(result) {
        const total = result.available ? Number(result.total || 0) : null;
        badge.textContent = total === null ? '—' : String(total);
        headingCount.textContent = total === null ? '' : '(' + total + ')';
        button.setAttribute('aria-label', total === null ? 'Online users: unavailable' : 'Online users: ' + total + ' online');
        status.classList.remove('is-error');
        status.textContent = total === null ? 'Online tracking is not available yet.' : total === 0 ? 'No users online right now.' : 'Connected within the last ' + Math.ceil(result.window_seconds / 60) + ' min' + (total > result.users.length ? ' · Showing ' + result.users.length + ' of ' + total : '');
        list.replaceChildren();
        result.users.forEach(user => {
            const item = document.createElement('li'); item.className = 'ou-user';
            const avatar = document.createElement('span'); avatar.className = 'ou-avatar'; avatar.setAttribute('aria-hidden', 'true');
            avatar.textContent = String(user.name || '?').trim().split(/\s+/).slice(0, 2).map(word => Array.from(word)[0]).join('').toUpperCase();
            const person = document.createElement('div'); person.className = 'ou-person';
            const name = document.createElement('strong'); name.textContent = user.name;
            const role = document.createElement('small'); role.textContent = roles[user.role] || user.role;
            person.append(name, role);
            const dot = document.createElement('span'); dot.className = 'ou-online-dot'; dot.setAttribute('aria-hidden', 'true');
            item.append(avatar, person, dot); list.appendChild(item);
        });
        updated.textContent = 'Updated ' + new Date().toLocaleTimeString('en-PH', {hour:'numeric',minute:'2-digit'});
    }

    async function load(force) {
        if (loading || stopped || document.visibilityState === 'hidden' || (!force && Date.now() - lastAttempt < 15000)) return;
        loading = true; lastAttempt = Date.now(); refresh.disabled = true;
        menu.setAttribute('aria-busy', 'true');
        try {
            const response = await root.fetch(menu.dataset.onlineUrl, {
                credentials: 'same-origin', cache: 'no-store', headers: {Accept:'application/json', 'X-Requested-With':'XMLHttpRequest'}
            });
            if (response.status === 403 || response.status === 401) {
                stopped = true; list.replaceChildren(); menu.hidden = true;
                menu.style.display = 'none';
                return;
            }
            if (response.redirected) { stopped = true; throw new Error('Your session has ended. Refresh the page to sign in again.'); }
            if (!response.ok) throw new Error('Unable to refresh online users. Try again.');
            display(await response.json());
        } catch (error) {
            status.textContent = error.message || 'Unable to refresh online users. Try again.';
            status.classList.add('is-error'); badge.textContent = '—';
            button.setAttribute('aria-label', 'Online users: refresh unavailable');
            updated.textContent = 'List may be out of date';
        } finally {
            loading = false; refresh.disabled = false; menu.removeAttribute('aria-busy');
        }
    }

    refresh.addEventListener('click', () => load(true));
    button.addEventListener('show.bs.dropdown', () => load(false));
    document.addEventListener('visibilitychange', () => load(false));
    root.addEventListener('online', () => load(true));
    root.setInterval(() => load(false), 30000);
    load(true);
}(window));
