<script>
(function () {
    let pickerSequence = 0;
    const activePickers = new Set();

    function dispatchInputEvent(element, name) {
        element.dispatchEvent(new Event(name, { bubbles: true }));
    }

    function optionDetails(option, label) {
        const code = option.getAttribute('data-product-code') || '';
        const capacity = option.getAttribute('data-capacity') || '';
        const details = [];
        if (code) details.push('Code: ' + code);
        if (capacity && !label.toLowerCase().includes(capacity.toLowerCase())) details.push(capacity);
        const price = option.getAttribute('data-price');
        if (price !== null && price !== '') {
            details.push('₱ ' + Number(price || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        }
        return details.join(' · ');
    }

    function makeMenu() {
        const menu = document.createElement('div');
        menu.className = 'lpg-product-suggestion-menu';
        menu.setAttribute('role', 'listbox');
        menu.hidden = true;
        document.body.appendChild(menu);
        return menu;
    }

    function positionMenu(state) {
        if (state.menu.hidden || !state.input.isConnected) return;
        const rect = state.input.getBoundingClientRect();
        const gap = 6;
        const below = window.innerHeight - rect.bottom - gap - 12;
        const above = rect.top - gap - 12;
        const openAbove = below < 170 && above > below;
        const maxHeight = Math.max(120, Math.min(280, openAbove ? above : below));
        state.menu.style.left = Math.max(8, rect.left) + 'px';
        state.menu.style.width = Math.min(rect.width, window.innerWidth - 16) + 'px';
        state.menu.style.maxHeight = maxHeight + 'px';
        state.menu.style.top = (openAbove ? Math.max(8, rect.top - maxHeight - gap) : rect.bottom + gap) + 'px';
    }

    function closeMenu(state) {
        state.menu.hidden = true;
        state.input.setAttribute('aria-expanded', 'false');
        state.input.removeAttribute('aria-activedescendant');
        state.activeIndex = -1;
    }

    function getItems(state) {
        if (state.select) {
            return Array.from(state.select.options)
                .filter(option => option.value !== '')
                .map(option => ({
                    value: option.value,
                    label: option.textContent.trim(),
                    details: optionDetails(option, option.textContent.trim()),
                    option: option
                }));
        }

        return Array.from(state.datalist?.options || []).map(option => ({
            value: option.value,
            label: option.value,
            details: optionDetails(option, option.value),
            option: option
        }));
    }

    function visibleItems(state) {
        const query = (state.query ?? state.input.value).trim().toLocaleLowerCase();
        const items = getItems(state);
        return (query
            ? items.filter(item => (item.label + ' ' + item.details).toLocaleLowerCase().includes(query))
            : items
        ).slice(0, 40);
    }

    function renderMenu(state) {
        const items = visibleItems(state);
        state.menu.replaceChildren();
        state.activeIndex = -1;

        if (!items.length) {
            const empty = document.createElement('div');
            empty.className = 'lpg-product-suggestion-empty';
            empty.textContent = state.select ? 'No matching products found.' : 'No matching products. You can still enter a description.';
            state.menu.appendChild(empty);
        } else {
            items.forEach((item, index) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'lpg-product-suggestion-option';
                button.id = state.id + '-option-' + index;
                button.setAttribute('role', 'option');
                button.setAttribute('aria-selected', 'false');
                button.dataset.index = String(index);

                const label = document.createElement('span');
                label.className = 'lpg-product-suggestion-label';
                label.textContent = item.label;
                button.appendChild(label);

                if (item.details) {
                    const details = document.createElement('small');
                    details.className = 'lpg-product-suggestion-details';
                    details.textContent = item.details;
                    button.appendChild(details);
                }

                button.addEventListener('mousedown', event => event.preventDefault());
                button.addEventListener('click', () => chooseItem(state, item));
                state.menu.appendChild(button);
            });
        }

        state.menu.hidden = false;
        state.input.setAttribute('aria-expanded', 'true');
        positionMenu(state);
    }

    function updateValidation(state) {
        if (!state.select) return;

        const hasValue = state.select.value !== '';
        const hasTypedText = state.input.value.trim() !== '';
        const required = state.input.required;
        const validSelection = hasValue && Array.from(state.select.options).some(option => option.value === state.select.value);
        state.input.setCustomValidity((required && !validSelection) || (hasTypedText && !validSelection)
            ? 'Choose a product from the suggestions.'
            : '');

        const sourceInvalid = state.select.classList.contains('is-invalid');
        const showInvalid = sourceInvalid || (state.form?.classList.contains('was-validated') && !state.input.validity.valid);
        state.input.classList.toggle('is-invalid', showInvalid);
        state.input.classList.toggle('is-valid', state.form?.classList.contains('was-validated') && state.input.validity.valid);
        if (state.feedback) {
            state.feedback.classList.toggle('d-block', showInvalid);
            if (!showInvalid && !state.select.classList.contains('is-invalid')) {
                state.feedback.classList.remove('d-block');
            }
        }
    }

    function syncFromSelect(state) {
        if (!state.select || state.suppressSync) return;
        const selected = state.select.selectedOptions[0];
        state.input.value = selected?.value ? selected.textContent.trim() : '';
        syncControlState(state);
    }

    function syncControlState(state) {
        if (!state.select) return;
        state.input.disabled = state.select.disabled;
        state.input.required = state.required;
        state.input.classList.toggle('is-invalid', state.select.classList.contains('is-invalid'));
        updateValidation(state);
    }

    function chooseItem(state, item) {
        if (state.select) {
            state.select.classList.remove('is-invalid');
            state.suppressSync = true;
            state.select.value = item.value;
            dispatchInputEvent(state.select, 'change');
            state.suppressSync = false;
            state.input.value = item.label;
            updateValidation(state);
        } else {
            state.input.value = item.value;
            dispatchInputEvent(state.input, 'input');
            dispatchInputEvent(state.input, 'change');
        }
        state.query = item.label;
        closeMenu(state);
    }

    function attachPicker(select) {
        if (select.dataset.lpgAutocompleteReady === '1') return;
        select.dataset.lpgAutocompleteReady = '1';

        const originalId = select.id || ('lpg-product-' + (++pickerSequence));
        const required = select.required;
        const wrapper = document.createElement('div');
        wrapper.className = 'lpg-product-picker-shell';
        select.parentNode.insertBefore(wrapper, select);
        wrapper.appendChild(select);

        const input = document.createElement('input');
        input.type = 'text';
        input.id = originalId + '_search';
        input.className = 'form-control lpg-product-picker-input';
        if (select.classList.contains('form-select-lg')) input.classList.add('form-control-lg');
        if (select.classList.contains('form-select-sm')) input.classList.add('form-control-sm');
        if (select.classList.contains('rounded-3')) input.classList.add('rounded-3');
        input.placeholder = select.options[0]?.textContent.trim() || 'Search products';
        input.autocomplete = 'off';
        input.setAttribute('role', 'combobox');
        input.setAttribute('aria-autocomplete', 'list');
        input.setAttribute('aria-haspopup', 'listbox');
        input.setAttribute('aria-expanded', 'false');

        const icon = document.createElement('span');
        icon.className = 'lpg-product-picker-icon bi bi-search';
        icon.setAttribute('aria-hidden', 'true');
        wrapper.append(input, icon);

        document.querySelectorAll('label[for="' + CSS.escape(originalId) + '"]').forEach(label => label.htmlFor = input.id);
        select.classList.add('lpg-product-source-select');
        select.required = false;
        select.tabIndex = -1;
        select.setAttribute('aria-hidden', 'true');

        const state = {
            id: 'lpg-product-picker-' + (++pickerSequence),
            select: select,
            shell: wrapper,
            input: input,
            form: select.form,
            feedback: select.parentElement.parentElement.querySelector('.invalid-feedback'),
            required: required,
            menu: makeMenu(),
            query: '',
            activeIndex: -1,
            suppressSync: false
        };
        activePickers.add(state);
        input.required = state.required;
        state.menu.id = state.id + '-list';
        input.setAttribute('aria-controls', state.menu.id);

        $(select).on('change.lpgProductAutocomplete', () => syncFromSelect(state));
        input.addEventListener('focus', () => {
            state.query = '';
            if (select.value) input.select();
            renderMenu(state);
        });
        input.addEventListener('input', () => {
            select.classList.remove('is-invalid');
            if (state.feedback) {
                state.feedback.textContent = '';
                state.feedback.classList.remove('d-block');
            }
            if (select.value) {
                state.suppressSync = true;
                select.value = '';
                dispatchInputEvent(select, 'change');
                state.suppressSync = false;
            }
            state.query = input.value;
            updateValidation(state);
            renderMenu(state);
        });
        input.addEventListener('invalid', () => updateValidation(state));
        input.addEventListener('keydown', event => handleKeyboard(state, event));

        if (state.form) {
            state.form.addEventListener('reset', () => window.setTimeout(() => syncFromSelect(state), 0));
        }
        select.closest('.modal')?.addEventListener('hidden.bs.modal', () => closeMenu(state));

        const observer = new MutationObserver(() => syncControlState(state));
        observer.observe(select, { attributes: true, attributeFilter: ['class', 'disabled', 'required'] });
        state.observer = observer;

        syncFromSelect(state);
    }

    function attachSearchInput(input) {
        if (input.dataset.lpgAutocompleteReady === '1') return;
        const listId = input.getAttribute('list');
        const datalist = listId ? document.getElementById(listId) : null;
        if (!datalist) return;
        input.dataset.lpgAutocompleteReady = '1';
        input.removeAttribute('list');
        const wrapper = document.createElement('div');
        wrapper.className = 'lpg-product-picker-shell';
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);
        input.classList.add('lpg-product-picker-input');
        const icon = document.createElement('span');
        icon.className = 'lpg-product-picker-icon bi bi-search';
        icon.setAttribute('aria-hidden', 'true');
        wrapper.appendChild(icon);
        input.setAttribute('role', 'combobox');
        input.setAttribute('aria-autocomplete', 'list');
        input.setAttribute('aria-haspopup', 'listbox');
        input.setAttribute('aria-expanded', 'false');

        const state = {
            id: 'lpg-product-picker-' + (++pickerSequence),
            input: input,
            shell: wrapper,
            form: input.form,
            datalist: datalist,
            menu: makeMenu(),
            query: '',
            activeIndex: -1
        };
        activePickers.add(state);
        state.menu.id = state.id + '-list';
        input.setAttribute('aria-controls', state.menu.id);
        input.closest('.modal')?.addEventListener('hidden.bs.modal', () => closeMenu(state));
        input.addEventListener('focus', () => {
            state.query = '';
            renderMenu(state);
        });
        input.addEventListener('input', () => {
            state.query = input.value;
            renderMenu(state);
        });
        input.addEventListener('keydown', event => handleKeyboard(state, event));
    }

    function handleKeyboard(state, event) {
        const options = Array.from(state.menu.querySelectorAll('.lpg-product-suggestion-option'));
        if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
            if (state.menu.hidden) renderMenu(state);
            const direction = event.key === 'ArrowDown' ? 1 : -1;
            state.activeIndex = Math.max(0, Math.min(options.length - 1, state.activeIndex + direction));
            options.forEach((option, index) => {
                option.classList.toggle('is-active', index === state.activeIndex);
                option.setAttribute('aria-selected', String(index === state.activeIndex));
            });
            const active = options[state.activeIndex];
            if (active) {
                state.input.setAttribute('aria-activedescendant', active.id);
                active.scrollIntoView({ block: 'nearest' });
            }
            event.preventDefault();
        } else if (event.key === 'Enter' && !state.menu.hidden && state.activeIndex >= 0 && options[state.activeIndex]) {
            event.preventDefault();
            options[state.activeIndex].click();
        } else if (event.key === 'Escape') {
            closeMenu(state);
        }
    }

    document.addEventListener('click', event => {
        activePickers.forEach(state => {
            const shell = state.shell;
            if (!state.menu.contains(event.target) && !state.input.contains(event.target) && !shell?.contains(event.target)) {
                closeMenu(state);
            }
        });
    });
    window.addEventListener('resize', () => activePickers.forEach(positionMenu));
    document.addEventListener('scroll', () => activePickers.forEach(positionMenu), true);

    window.LpgProductAutocomplete = {
        sync: function (select) {
            const state = Array.from(activePickers).find(item => item.select === select);
            if (state) syncFromSelect(state);
        }
    };

    $(function () {
        document.querySelectorAll('select.lpg-product-picker').forEach(attachPicker);
        document.querySelectorAll('input.lpg-product-search[list]').forEach(attachSearchInput);
    });
})();
</script>
