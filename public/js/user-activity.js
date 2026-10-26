(function (root) {
    'use strict';

    const configNode = document.getElementById('user-activity-config');
    if (!configNode) return;
    const config = JSON.parse(configNode.textContent);
    const form = document.getElementById('activity-filters');
    const rows = document.getElementById('activity-history-rows');
    const previous = document.getElementById('activity-previous');
    const next = document.getElementById('activity-next');
    const pageNumbers = document.getElementById('activity-page-numbers');
    const notice = document.getElementById('activity-notice');
    let page = 1;
    let lastPage = 1;
    let applied = new URLSearchParams(new FormData(form));
    let activeRequest;
    let detailRequest;
    let searchTimer;

    function element(tag, text, className) {
        const node = document.createElement(tag);
        if (text != null) node.textContent = String(text);
        if (className) node.className = className;
        return node;
    }

    function timestamp(value) {
        const date = new Date(value);
        return !value || Number.isNaN(date.getTime()) ? 'Date unavailable' : date.toLocaleString('en-PH', {timeZone:config.timezone,year:'numeric',month:'short',day:'numeric',hour:'numeric',minute:'2-digit',second:'2-digit'});
    }

    function displayValue(value) {
        if (value === null || value === undefined || value === '') return '—';
        if (typeof value === 'boolean') return value ? 'Yes' : 'No';
        return typeof value === 'object' ? JSON.stringify(value, null, 2) : String(value);
    }

    function eventTime(value) {
        const cell = element('td',null,'ua-event-time');
        const date = new Date(value);
        if (!value || Number.isNaN(date.getTime())) { cell.textContent = 'Date unavailable'; return cell; }
        const time = element('time'); time.dateTime = date.toISOString();
        time.append(element('span',date.toLocaleDateString('en-PH',{timeZone:config.timezone,year:'numeric',month:'short',day:'numeric'})),element('small',date.toLocaleTimeString('en-PH',{timeZone:config.timezone,hour:'numeric',minute:'2-digit'})));
        time.title = timestamp(value) + ' · ' + config.timezone;
        cell.append(time); return cell;
    }

    function eventSummary(entry) {
        const module = entry.module || 'Application';
        const summaries = {
            created: 'Created a record in ' + module + '.',
            updated: 'Updated a record in ' + module + '.',
            deleted: 'Deleted a record in ' + module + '.',
            restored: 'Restored a record in ' + module + '.',
            login: 'User signed in successfully.',
            logout: 'User signed out.',
            viewed: 'Viewed ' + module + '.',
            submitted: 'Submitted a request in ' + module + '.'
        };
        return summaries[entry.action] || 'Recorded an event in ' + module + '.';
    }

    function renderPages() {
        pageNumbers.replaceChildren();
        const pages = [...new Set([1,page-1,page,page+1,lastPage].filter(number => number > 0 && number <= lastPage))].sort((a,b) => a-b);
        pages.forEach((number,index) => {
            if (index && number-pages[index-1]>1) pageNumbers.append(element('span','…','ua-page-gap'));
            const button = element('button',number,'ua-page-button');
            button.type = 'button'; button.dataset.activityPage = number;
            button.setAttribute('aria-label','Page ' + number);
            if (number === page) button.setAttribute('aria-current','page');
            pageNumbers.append(button);
        });
    }

    // Reuse the SUAdmin header's live count so this card does not add another poll.
    function mirrorOnlineCount() {
        const menu = document.querySelector('[data-online-users]');
        const badge = menu?.querySelector('[data-online-count]');
        const target = document.getElementById('activity-online-count');
        const state = document.getElementById('activity-online-state');
        if (!target || !state) return;
        const sync = () => {
            const text = badge?.textContent.trim() || '';
            const available = !!menu && !menu.hidden && menu.style.display !== 'none' && /^\d+$/.test(text);
            target.textContent = available ? Number(text).toLocaleString() + ' online now' : 'Online status unavailable';
            state.classList.toggle('is-online',available && Number(text)>0);
        };
        sync();
        if (menu) new MutationObserver(sync).observe(menu,{childList:true,subtree:true,characterData:true,attributes:true,attributeFilter:['hidden','style']});
    }

    async function getJson(url, signal) {
        const response = await root.fetch(url, {credentials:'same-origin',cache:'no-store',headers:{Accept:'application/json','X-Requested-With':'XMLHttpRequest'},signal});
        if (response.redirected) throw new Error('Your session has ended. Refresh this page to sign in again.');
        if ([401,403].includes(response.status)) throw new Error('SUAdmin access is required to view activity history.');
        if (!response.headers.get('content-type')?.includes('application/json')) throw new Error('Activity history could not be loaded. Please try again.');
        const result = await response.json();
        if (!response.ok) throw new Error(Object.values(result.errors || {}).flat().join(' ') || result.message || 'Activity history could not be loaded.');
        return result;
    }

    function render(data) {
        rows.replaceChildren();
        if (!data.length) {
            const row = element('tr'); const cell = element('td','No activity matches these filters.','ua-empty'); cell.colSpan = 6;
            row.appendChild(cell); rows.appendChild(row); return;
        }
        data.forEach(entry => {
            const row = element('tr');
            row.appendChild(eventTime(entry.occurred_at));
            const person = element('td',null,'ua-person');
            const layout = element('div',null,'ua-person-layout');
            const initials = String(entry.user_name || '?').trim().split(/\s+/).slice(0,2).map(part => part.charAt(0).toUpperCase()).join('') || '?';
            const identity = element('div');
            const avatar = element('span',initials,'ua-avatar'); avatar.setAttribute('aria-hidden','true'); layout.append(avatar);
            identity.append(element('strong',entry.user_name),element('small',(entry.user_role || 'Role unavailable').replace(/_/g,' ')));
            layout.append(identity); person.append(layout);
            row.append(person,element('td',entry.module));
            const action = element('td'); action.appendChild(element('span',entry.action_label,'ua-action ua-action-' + entry.action));
            const record = element('td'); record.append(element('span',entry.record,entry.record && entry.record !== '—' ? 'ua-record-reference' : ''));
            row.append(action,record);
            const details = element('td'); const button = element('button',eventSummary(entry),'ua-details-button');
            button.type = 'button'; button.dataset.activityId = entry.id;
            button.setAttribute('aria-label',eventSummary(entry) + ' View details for event ' + entry.id);
            button.title = 'View event details';
            details.appendChild(button); row.appendChild(details); rows.appendChild(row);
        });
    }

    async function load() {
        if (activeRequest) activeRequest.abort();
        const controller = new AbortController(); activeRequest = controller;
        const parameters = new URLSearchParams(applied); parameters.set('page',page);
        previous.disabled = true; next.disabled = true; rows.closest('section').setAttribute('aria-busy','true');
        pageNumbers.querySelectorAll('button').forEach(button => {button.disabled = true;});
        try {
            const result = await getJson(config.list + '?' + parameters, controller.signal);
            if (activeRequest !== controller) return;
            render(result.data); lastPage = result.last_page; page = result.page;
            document.querySelectorAll('[data-activity-count]').forEach(node => {node.textContent = result.summary[node.dataset.activityCount] ?? 0;});
            const first = result.total && result.data.length ? (page-1)*Number(applied.get('per_page') || 20)+1 : 0;
            const final = first ? first+result.data.length-1 : 0;
            document.getElementById('activity-page-info').textContent = 'Showing ' + first.toLocaleString() + ' to ' + final.toLocaleString() + ' of ' + result.total.toLocaleString() + ' entries';
            renderPages();
            document.getElementById('activity-updated').textContent = 'Updated ' + new Date().toLocaleTimeString('en-PH',{hour:'numeric',minute:'2-digit'});
            notice.textContent = !result.available ? 'Activity history is currently unavailable.' : !result.recording_enabled ? 'History is available. New activity recording is currently disabled.' : '';
            notice.hidden = !notice.textContent;
        } catch (error) {
            if (error.name !== 'AbortError') {notice.textContent = error.message;notice.hidden = false;}
        } finally {
            if (activeRequest === controller) {
                activeRequest = null; rows.closest('section').removeAttribute('aria-busy');
                previous.disabled = page <= 1; next.disabled = page >= lastPage;
                pageNumbers.querySelectorAll('button').forEach(button => {button.disabled = false;});
            }
        }
    }

    function apply() {
        if (!form.reportValidity()) return;
        applied = new URLSearchParams(new FormData(form)); page = 1;
        const query = new URLSearchParams(applied);
        Array.from(query.entries()).forEach(([key,value])=>{if(!value)query.delete(key);});
        root.history.replaceState({},'',root.location.pathname + '?' + query);
        load();
    }

    form.addEventListener('submit',event=>{event.preventDefault();clearTimeout(searchTimer);apply();});
    document.getElementById('activity-search').addEventListener('input',()=>{clearTimeout(searchTimer);searchTimer=root.setTimeout(apply,350);});
    document.getElementById('activity-refresh').addEventListener('click',()=>load());
    previous.addEventListener('click',()=>{if(page>1){page--;load();}});
    next.addEventListener('click',()=>{if(page<lastPage){page++;load();}});
    pageNumbers.addEventListener('click',event=>{
        const button = event.target.closest('[data-activity-page]');
        if (!button || button.disabled) return;
        const selected = Number(button.dataset.activityPage);
        if (selected !== page && selected >= 1 && selected <= lastPage) {page = selected;load();}
    });

    rows.addEventListener('click',async event=>{
        const button = event.target.closest('[data-activity-id]'); if(!button)return;
        if(detailRequest)detailRequest.abort();
        const controller = new AbortController();detailRequest = controller;
        const modalNode = document.getElementById('ActivityDetailsModal');
        const status = document.getElementById('activity-detail-status');
        const info = document.getElementById('activity-detail-info');
        const changes = document.getElementById('activity-changes');
        const changeTable = document.getElementById('activity-changes-table');
        status.textContent = 'Loading event details…';info.replaceChildren();changes.replaceChildren();changeTable.hidden = true;
        root.bootstrap.Modal.getOrCreateInstance(modalNode).show();
        try {
            const entry = await getJson(config.detail + '/' + encodeURIComponent(button.dataset.activityId),controller.signal);
            if(detailRequest !== controller)return;
            [['When',timestamp(entry.occurred_at)],['User',entry.user_name],['Module',entry.module],['Action',entry.action_label],['Record',entry.record],...(entry.operation?[['Operation',entry.operation]]:[])].forEach(([label,value])=>{const item=element('div');item.append(element('dt',label),element('dd',value));info.appendChild(item);});
            entry.changes.forEach(change=>{const row=element('tr');row.append(element('td',change.field),element('td',displayValue(change.before)),element('td',displayValue(change.after)));changes.appendChild(row);});
            changeTable.hidden = !entry.changes.length;
            status.textContent = entry.changes.length ? '' : 'No field-level changes were recorded for this event.';
        } catch(error) {if(error.name!=='AbortError')status.textContent = error.message;}
    });
    document.getElementById('ActivityDetailsModal').addEventListener('hidden.bs.modal',()=>{if(detailRequest)detailRequest.abort();});
    mirrorOnlineCount();
    load();
}(window));
