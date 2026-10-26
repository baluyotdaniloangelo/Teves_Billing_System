(function (window, document, $) {
    'use strict';

    if (window.SalesOrderEditor) return;

    var state = { sequence: 0, load: null, requests: new Set(), child: null, lockTimer: null };
    var modalId = 'SalesOrderEditWorkspaceModal';

    function root() {
        return document.querySelector('.so-order-editor-content[data-sales-order-id]');
    }

    function value(name) {
        var element = root();
        return Number(element ? element.dataset[name] || 0 : 0);
    }

    function refreshLists() {
        ['#getSalesOrderList', '#getSalesOrderList_quotation', '#getSalesOrderList_delivered'].forEach(function (selector) {
            if ($.fn.DataTable && $.fn.DataTable.isDataTable(selector)) {
                $(selector).DataTable().ajax.reload(null, false);
            }
        });
    }

    function cleanup() {
        state.sequence++;
        if (state.load) state.load.abort();
        state.load = null;
        state.requests.forEach(function (request) { request.abort(); });
        state.requests.clear();
        window.clearInterval(state.lockTimer);
        state.lockTimer = null;
        state.child = null;
        var host = document.getElementById('sales-order-editor-dialogs');
        if (host) {
            host.querySelectorAll('.modal').forEach(function (dialog) {
                var instance = window.bootstrap.Modal.getInstance(dialog);
                if (instance) instance.dispose();
            });
            host.remove();
        }
        $('#sales-order-edit-content').empty();
    }

    function error(message) {
        var loading = document.getElementById('sales-order-edit-loading');
        if (!loading) return;
        loading.replaceChildren();
        var notice = document.createElement('span');
        notice.setAttribute('role', 'alert');
        notice.textContent = message;
        loading.appendChild(notice);
        $(loading).show();
    }

    function initialize() {
        if (!root()) return;
        ['LoadProduct_under_sales_order', 'LoadProductListDelivery', 'LoadSellingPriceList', 'LoadPayment', 'LoadReceivables'].forEach(function (name) {
            if (typeof window[name] === 'function') window[name]();
        });
    }

    function mount(markup, orderId) {
        if (/<(?:!doctype|html|head|body)\b/i.test(markup)) {
            throw new Error('A full page was returned instead of the Sales Order editor.');
        }
        var parsed = new window.DOMParser().parseFromString(markup, 'text/html');
        if (parsed.querySelector('script, iframe')) {
            throw new Error('Unexpected scripts in the Sales Order editor response.');
        }
        var roots = parsed.querySelectorAll('.so-order-editor-content[data-sales-order-id]');
        if (roots.length !== 1 || Number(roots[0].dataset.salesOrderId) !== Number(orderId)) {
            throw new Error('The Sales Order editor response did not match this order.');
        }
        var ids = new Set();
        parsed.querySelectorAll('[id]').forEach(function (element) {
            if (ids.has(element.id) || document.getElementById(element.id)) {
                throw new Error('Duplicate editor ID: ' + element.id);
            }
            ids.add(element.id);
        });
        var editor = document.importNode(roots[0], true);
        document.getElementById('sales-order-edit-content').replaceChildren(editor);
        // Child forms are siblings of the editor modal, with a separate Bootstrap lifecycle.
        var host = document.createElement('div');
        host.id = 'sales-order-editor-dialogs';
        host.className = 'so-order-editor-content so-editor-dialog-host';
        editor.querySelectorAll('.modal').forEach(function (dialog) {
            dialog.classList.add('so-modal', 'so-editor-child-modal');
            host.appendChild(dialog);
        });
        document.body.appendChild(host);
        $('#sales-order-edit-loading').hide();
        initialize();
    }

    function open(orderId, number) {
        var modal = document.getElementById(modalId);
        if (!modal || !orderId) return;
        cleanup();
        var sequence = state.sequence;
        $('#sales-order-edit-number').text(number || '#' + orderId);
        var loading = document.getElementById('sales-order-edit-loading');
        loading.replaceChildren();
        var spinner = document.createElement('span');
        spinner.className = 'spinner-border spinner-border-sm';
        spinner.setAttribute('aria-hidden', 'true');
        loading.append(spinner, document.createTextNode(' Loading Sales Order…'));
        $(loading).show();
        $(modal).modal('show');
        state.load = $.ajax({
            url: modal.dataset.editorUrl,
            dataType: 'html',
            data: { sales_order_id: orderId, tab: 'product', modal: 1 }
        }).done(function (markup) {
            if (sequence !== state.sequence) return;
            try { mount(markup, orderId); }
            catch (failure) {
                window.console.error('Sales Order editor:', failure);
                error('Unable to load the editor. Please refresh this page and try again.');
            }
        }).fail(function (xhr, status) {
            if (status !== 'abort' && sequence === state.sequence) {
                error('Unable to load this Sales Order. Please close and try again.');
            }
        });
    }

    // Requests from an old/closed order must never update the next editor.
    function request(options) {
        var current = root();
        var guarded = Object.assign({}, options);
        ['success', 'error', 'complete'].forEach(function (name) {
            var callback = options[name];
            guarded[name] = function () {
                if (!current || root() !== current || (name === 'error' && arguments[1] === 'abort')) return;
                if (callback) return callback.apply(this, arguments);
            };
        });
        var pending = $.ajax(guarded);
        state.requests.add(pending);
        pending.always(function () { state.requests.delete(pending); });
        return pending;
    }

    function afterSave() {
        refreshLists();
        var parent = document.getElementById(modalId);
        if (parent && (parent.classList.contains('show') || state.child)) {
            if (state.child) {
                var child = state.child;
                state.child = null;
                $(child).one('hidden.bs.modal', cleanup).modal('hide');
            } else {
                $(parent).modal('hide');
            }
        } else {
            window.location.reload();
        }
    }

    window.SalesOrderEditor = {
        open: open,
        cleanup: cleanup,
        scheduleLock: function (expiration, callback) {
            window.clearInterval(state.lockTimer);
            var current = root();
            state.lockTimer = window.setInterval(function () {
                if (root() !== current) {
                    window.clearInterval(state.lockTimer);
                } else if (Date.now() >= new Date(expiration).getTime()) {
                    window.clearInterval(state.lockTimer);
                    callback();
                }
            }, 1000);
        }
    };
    window.salesOrderEditorRoot = root;
    window.salesOrderEditorId = function () { return value('salesOrderId'); };
    window.salesOrderEditorReceivableId = function () { return value('receivableId'); };
    window.salesOrderEditorCategoryId = function () { return value('categoryId'); };
    window.salesOrderEditorClientId = function () { return value('clientId'); };
    window.salesOrderEditorRequest = request;
    window.salesOrderEditorAfterSave = afterSave;

    $(document).on('click.soEditor', '#EditSalesOrder', function (event) {
        event.preventDefault();
        open($(this).data('id'), $(this).data('control-number'));
    });
    $(document).on('hidden.bs.modal.soEditor', '#' + modalId, function (event) {
        if (event.target !== this || state.child) return;
        cleanup();
        refreshLists();
    });
    $(document).on('show.bs.modal.soEditor', '.so-editor-child-modal', function (event) {
        if (event.target !== this) return;
        var parent = document.getElementById(modalId);
        if (parent && parent.classList.contains('show')) {
            state.child = this;
            $(parent).modal('hide');
        }
    });
    $(document).on('hidden.bs.modal.soEditor', '.so-editor-child-modal', function (event) {
        if (event.target !== this || state.child !== this) return;
        state.child = null;
        if (root()) $('#' + modalId).modal('show');
    });
    // Prevent Bootstrap's data API from closing the parent editor and discarding its tabs.
    window.addEventListener('click', function (event) {
        var trigger = event.target.closest('[data-bs-toggle="modal"]');
        var selector = trigger && trigger.getAttribute('data-bs-target');
        if (!selector || !selector.startsWith('#so-editor-')) return;
        var child = document.querySelector(selector);
        if (!child || !child.classList.contains('so-editor-child-modal')) return;
        event.preventDefault();
        event.stopPropagation();
        window.bootstrap.Modal.getOrCreateInstance(child).show(trigger);
    }, true);
    $(function () {
        if (!document.getElementById(modalId) && root()) initialize();
    });
})(window, document, window.jQuery);
