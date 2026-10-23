@php
    $canEditSODate = in_array(Session::get('UserType'), ['Admin', 'SUAdmin'], true);
@endphp
<script>
    (() => {
        'use strict';

        const urls = {
            list: @json(route('getSOBillingTransactionList')),
            create: @json(route('CreateSOPost')),
            info: @json(route('so_info')),
            products: @json(route('GetSoProduct')),
            createProduct: @json(route('so_product_create_from_list')),
            deleteProduct: @json(route('so_product_delete_from_list')),
            updateSoInformation: @json(route('so_information_update_from_list')),
            updateProduct: @json(route('so_product_update_from_list')),
            remove: @json(route('delete_so_confirmed')),
        };
        const canEditSODate = @json($canEditSODate);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const form = document.getElementById('SOBillingformNew');
        const clientInput = document.getElementById('so_client_idx');
        const clientOptions = Array.from(document.querySelectorAll('#so_client_name option'));
        const deleteModalElement = document.getElementById('DeleteSOModal');
        const deleteProductModalElement = document.getElementById('DeleteSOProductModal');
        const deleteButton = document.getElementById('deleteSOConfirmed');
        const addSOModalElement = document.getElementById('AddSOModal');
        const addProductModalElement = document.getElementById('AddSOProductModal');
        const manageModalElement = document.getElementById('ManageSOProductsModal');
        const editProductModalElement = document.getElementById('EditSOProductModal');
        const manageProductsBody = document.getElementById('manage_so_products_body');
        const manageFeedback = document.getElementById('manage-so-feedback');
        const editProductFeedback = document.getElementById('edit-so-product-feedback');
        const soInformationForm = document.getElementById('UpdateSOInformationForm');
        const addProductForm = document.getElementById('AddSOProductForm');
        const editProductForm = document.getElementById('EditSOProductForm');
        const bootstrapModal = (element) => window.bootstrap?.Modal?.getOrCreateInstance(element);
        const listFeedback = document.getElementById('so-list-feedback');
        const deleteFeedback = document.getElementById('so-delete-feedback');
        const createFeedback = document.getElementById('so-create-feedback');
        const addProductFeedback = document.getElementById('add-so-product-feedback');
        const deleteProductFeedback = document.getElementById('delete-so-product-feedback');
        const deleteProductButton = document.getElementById('confirm-delete-so-product');
        let pendingNewOrderForProducts = null;
        let activeNewOrderForProducts = null;

        const showFeedback = (element, message, state = 'error') => {
            element.textContent = message;
            element.dataset.state = state;
            element.hidden = false;
        };

        const clearFeedback = (element) => {
            element.textContent = '';
            element.hidden = true;
            delete element.dataset.state;
        };

        const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (character) => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
        })[character]);

        const post = (url, data) => $.ajax({
            url,
            method: 'POST',
            data: { ...data, _token: csrfToken }
        });

        const displayError = (xhr, fallback) => {
            const errors = xhr.responseJSON?.errors;
            if (errors) {
                const labels = {
                    branch_id: 'Branch', order_date: 'Date', order_time: 'Time',
                    so_number: 'S.O. number', client_idx: 'Account name',
                    plate_no: 'Description', drivers_name: "Driver's name"
                };
                const fieldIds = {
                    order_date: 'so_order_date', order_time: 'so_order_time',
                    client_idx: 'so_client_idx', plate_no: 'so_plate_no', drivers_name: 'so_drivers_name'
                };
                Object.entries(errors).forEach(([name, messages]) => {
                    const fieldId = fieldIds[name] || name;
                    const field = document.getElementById(fieldId);
                    const feedback = document.getElementById(`${fieldId}Error`);
                    if (!field || !feedback) return;
                    const message = Array.isArray(messages) ? messages.join(' ') : String(messages);
                    field.classList.add('is-invalid');
                    field.setAttribute('aria-invalid', 'true');
                    feedback.textContent = message;
                });
                const firstMessage = Object.values(errors).flat()[0];
                return firstMessage || fallback;
            }
            if (xhr.status === 419) return 'Your session expired. Refresh the page and try again.';
            if (xhr.status === 403) return 'You do not have permission to complete this action.';
            return fallback;
        };

        if (!form || !window.jQuery || !$.fn.DataTable) return;

        const table = $('#getSOBillingTransactionList').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            pageLength: 10,
            lengthMenu: [10, 20, 30, 40, 50, 100],
            layout: {
                topStart: null,
                topEnd: null,
                bottomStart: 'info',
                bottomEnd: 'paging'
            },
            responsive: { details: { type: 'inline', target: 0 } },
            ajax: {
                url: urls.list,
                type: 'GET',
                data: (request) => {
                    request.branch_filter = document.getElementById('so_branch_filter')?.value || '';
                    request.client_filter = document.getElementById('so_client_filter')?.value || '';
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'order_date', name: 'teves_billing_so_table.order_date' },
                { data: 'order_time', name: 'teves_billing_so_table.order_time', orderable: false },
                {
                    data: 'so_number', name: 'teves_billing_so_table.so_number', orderable: false,
                    render: (value, type, row) => {
                        if (type !== 'display') return value;
                        return `<button type="button" class="so-number-button js-open-so-products" data-id="${escapeHtml(row.so_id)}" data-can-edit="${row.can_edit ? '1' : '0'}" data-can-edit-information="${row.can_edit_information ? '1' : '0'}" aria-label="Show products for sales order ${escapeHtml(value)}">${escapeHtml(value)}</button>`;
                    }
                },
                { data: 'branch_code', name: 'teves_branch_table.branch_code', orderable: false },
                { data: 'client_name', name: 'teves_client_table.client_name', orderable: false },
                { data: 'drivers_name', name: 'teves_billing_so_table.drivers_name', orderable: false },
                { data: 'plate_no', name: 'teves_billing_so_table.plate_no', orderable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            order: [[1, 'desc']],
            columnDefs: [{ targets: 0, className: 'dtr-control text-center' }],
            language: {
                processing: 'Loading sales orders…',
                emptyTable: 'No sales orders found',
                zeroRecords: 'No matching sales orders found',
                info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                infoEmpty: 'Showing 0 entries',
                paginate: { previous: '‹', next: '›', first: '«', last: '»' }
            }
        });

        const searchInput = document.getElementById('so_list_search');
        const lengthSelect = document.getElementById('so_list_length');
        const branchFilter = document.getElementById('so_branch_filter');
        const clientFilter = document.getElementById('so_client_filter');
        const resetFiltersButton = document.getElementById('resetSOListFilters');
        let searchTimeout;

        if (searchInput) {
            searchInput.value = table.search();
            searchInput.addEventListener('input', () => {
                window.clearTimeout(searchTimeout);
                searchTimeout = window.setTimeout(() => table.search(searchInput.value).draw(), 250);
            });
        }

        if (lengthSelect) {
            lengthSelect.value = String(table.page.len());
            lengthSelect.addEventListener('change', () => {
                table.page.len(Number(lengthSelect.value)).page(0).draw();
            });
        }

        [branchFilter, clientFilter].forEach((filter) => {
            filter?.addEventListener('change', () => table.ajax.reload(null, true));
        });

        resetFiltersButton?.addEventListener('click', () => {
            window.clearTimeout(searchTimeout);
            if (searchInput) searchInput.value = '';
            if (lengthSelect) lengthSelect.value = '10';
            if (branchFilter) branchFilter.value = '';
            if (clientFilter) clientFilter.value = '';
            table.search('').page.len(10).page(0).draw();
        });

        $('#getSOBillingTransactionList').on('error.dt', (_event, _settings, _techNote, message) => {
            showFeedback(listFeedback, `The sales order list could not be loaded. ${message || 'Please refresh and try again.'}`);
        });

        const getSelectedClientId = () => {
            const name = clientInput.value.trim().toLocaleLowerCase();
            const option = clientOptions.find((item) => item.value.trim().toLocaleLowerCase() === name);
            return option?.dataset.id || '';
        };

        const resetFieldErrors = () => {
            form.querySelectorAll('input, select, textarea').forEach((field) => {
                if (typeof field.setCustomValidity === 'function') field.setCustomValidity('');
                field.removeAttribute('aria-invalid');
            });
            form.querySelectorAll('.is-invalid').forEach((field) => {
                field.classList.remove('is-invalid');
            });
            form.querySelectorAll('.invalid-feedback').forEach((feedback) => { feedback.textContent = ''; });
        };

        clientInput.addEventListener('input', () => {
            clientInput.setCustomValidity('');
            clientInput.classList.remove('is-invalid');
            clientInput.removeAttribute('aria-invalid');
            document.getElementById('so_client_idxError').textContent = '';
        });

        form.addEventListener('submit', (event) => {
            event.preventDefault();
            resetFieldErrors();
            clearFeedback(createFeedback);

            const clientId = getSelectedClientId();
            if (!clientId) {
                clientInput.setCustomValidity('Choose an account from the suggested list.');
                document.getElementById('so_client_idxError').textContent = clientInput.validationMessage;
            }

            form.classList.add('was-validated');
            if (!form.checkValidity()) {
                form.querySelector(':invalid')?.focus();
                return;
            }

            const saveButton = document.getElementById('save-so-billing-transaction');
            const spinner = document.getElementById('so-save-spinner');
            saveButton.disabled = true;
            spinner.hidden = false;

            post(urls.create, {
                branch_id: document.getElementById('branch_id').value,
                order_date: document.getElementById('so_order_date').value,
                order_time: document.getElementById('so_order_time').value,
                so_number: document.getElementById('so_number').value.trim(),
                client_idx: clientId,
                plate_no: document.getElementById('so_plate_no').value.trim(),
                drivers_name: document.getElementById('so_drivers_name').value.trim()
            }).done((response) => {
                if (!response?.so_id) {
                    showFeedback(createFeedback, response?.success || 'The order was saved, but the add-product form could not be opened.');
                    return;
                }
                pendingNewOrderForProducts = {
                    so_id: response.so_id,
                    so_number: document.getElementById('so_number').value.trim(),
                    canEditProducts: true,
                    canEditInformation: true
                };
                table.ajax.reload(null, false);
                bootstrapModal(addSOModalElement)?.hide();
            }).fail((xhr) => {
                showFeedback(createFeedback, displayError(xhr, 'The sales order could not be saved. Please review the fields and try again.'));
            }).always(() => {
                saveButton.disabled = false;
                spinner.hidden = true;
            });
        });

        const formatNumber = (value) => Number(value || 0).toLocaleString('en-PH', { maximumFractionDigits: 2 });
        const setText = (id, value) => { document.getElementById(id).textContent = value || '—'; };

        const addProductPrice = document.getElementById('add_so_product_price');
        const addProductQuantity = document.getElementById('add_so_product_quantity');
        const updateAddedProductAmount = () => {
            const price = Number(addProductPrice.value || 0);
            const amount = price * Number(addProductQuantity.value || 0);
            document.getElementById('add_so_product_amount').textContent = price > 0
                ? `₱${formatNumber(amount)}`
                : 'Standard price will be applied on save';
        };

        addProductPrice.addEventListener('input', updateAddedProductAmount);
        addProductQuantity.addEventListener('input', updateAddedProductAmount);

        addProductForm.addEventListener('submit', (event) => {
            event.preventDefault();
            clearFeedback(addProductFeedback);
            addProductForm.classList.add('was-validated');
            if (!addProductForm.checkValidity()) {
                addProductForm.querySelector(':invalid')?.focus();
                return;
            }

            const saveButton = document.getElementById('save-so-product-add');
            const spinner = document.getElementById('so-product-add-spinner');
            saveButton.disabled = true;
            spinner.hidden = false;
            post(urls.createProduct, {
                so_id: document.getElementById('add_so_product_so_id').value,
                product_idx: document.getElementById('add_so_product_idx').value,
                product_manual_price: addProductPrice.value,
                order_quantity: addProductQuantity.value
            }).done(() => {
                const soId = activeNewOrderForProducts?.so_id;
                addProductForm.reset();
                addProductForm.classList.remove('was-validated');
                document.getElementById('add_so_product_so_id').value = soId || '';
                document.getElementById('add_so_product_amount').textContent = 'Standard price will be applied on save';
                showFeedback(addProductFeedback, 'Product added. You can add another product or finish and view the order.', 'success');
                table.ajax.reload(null, false);
            }).fail((xhr) => {
                const message = xhr.responseJSON?.errors
                    ? Object.values(xhr.responseJSON.errors).flat().join(' ')
                    : (xhr.responseJSON?.message || 'The product could not be added. Please review the fields and try again.');
                showFeedback(addProductFeedback, message);
            }).always(() => {
                saveButton.disabled = false;
                spinner.hidden = true;
            });
        });

        document.getElementById('finish-so-product-add').addEventListener('click', () => {
            bootstrapModal(addProductModalElement)?.hide();
        });

        addProductModalElement.addEventListener('hidden.bs.modal', () => {
            addProductForm.reset();
            addProductForm.classList.remove('was-validated');
            clearFeedback(addProductFeedback);
            document.getElementById('add_so_product_amount').textContent = 'Standard price will be applied on save';
            if (!activeNewOrderForProducts) return;
            const order = activeNewOrderForProducts;
            activeNewOrderForProducts = null;
            openManageProducts(order.so_id, order.canEditProducts !== false, order.canEditInformation !== false);
        });

        const loadProducts = (soId) => post(urls.products, { so_id: soId }).done((products) => {
            const tbody = document.getElementById('so_product_list_data_delete');
            const rows = Array.isArray(products) ? products : [];
            tbody.replaceChildren();
            let total = 0;

            if (!rows.length) {
                const row = tbody.insertRow();
                const cell = row.insertCell();
                cell.colSpan = 5;
                cell.className = 'text-center text-muted';
                cell.textContent = 'No products on this order.';
                setText('delete_so_total', '0.00');
                return;
            }

            rows.forEach((product, index) => {
                const row = tbody.insertRow();
                const amount = Number(product.order_total_amount || 0);
                total += amount;
                [String(index + 1), product.product_name, formatNumber(product.order_quantity), formatNumber(product.product_price), formatNumber(amount)].forEach((value, cellIndex) => {
                    const cell = row.insertCell();
                    cell.textContent = value || '—';
                    if (cellIndex >= 2) cell.className = 'text-end';
                });
            });
            setText('delete_so_total', formatNumber(total));
        }).fail((xhr) => {
            showFeedback(deleteFeedback, displayError(xhr, 'Could not load the products for this order.'));
        });

        let activeSoId = '';
        let activeCanEditProducts = false;
        let activeCanEditInformation = false;
        let activeProducts = [];
        let activeEditProduct = null;
        let activeDeleteBillingId = null;
        let switchingToProductEditor = false;
        let switchingToProductAdder = false;
        let returnToManageAfterEdit = false;
        let pendingProductSaveMessage = '';

        const renderManageProducts = (products) => {
            activeProducts = Array.isArray(products) ? products : [];
            manageProductsBody.replaceChildren();
            let total = 0;

            if (!activeProducts.length) {
                const row = manageProductsBody.insertRow();
                const cell = row.insertCell();
                cell.colSpan = 6;
                cell.className = 'text-center text-muted py-4';
                cell.textContent = 'No products have been added to this sales order.';
                document.getElementById('manage_so_total').textContent = formatNumber(0);
                return;
            }

            activeProducts.forEach((product, index) => {
                const row = manageProductsBody.insertRow();
                const amount = Number(product.order_total_amount || 0);
                total += amount;
                [String(index + 1), product.product_name || '—', formatNumber(product.product_price), formatNumber(product.order_quantity), formatNumber(amount)].forEach((value, cellIndex) => {
                    const cell = row.insertCell();
                    cell.textContent = value;
                    if (cellIndex >= 2) cell.className = 'text-end';
                });

                const actionCell = row.insertCell();
                actionCell.className = 'text-center text-nowrap';
                const hasReceivable = Number(product.receivable_idx || 0) !== 0;
                const locked = hasReceivable || (product.lock_billing_item && !['0', 'false', 'null', ''].includes(String(product.lock_billing_item).toLowerCase()));
                const linkedToCashiersReport = Number(product.cashiers_report_idx || 0) !== 0
                    || Number(product.cashiers_report_linked || 0) > 0;
                if (activeCanEditProducts && !locked) {
                    const editButton = document.createElement('button');
                    editButton.type = 'button';
                    editButton.className = 'btn btn-outline-primary btn-sm rounded-3 js-edit-so-product me-1';
                    editButton.dataset.index = String(index);
                    editButton.setAttribute('aria-label', `Edit ${product.product_name || 'product'}`);
                    editButton.innerHTML = '<i class="bi bi-pencil-square me-1" aria-hidden="true"></i>Edit';
                    actionCell.append(editButton);
                    if (linkedToCashiersReport) {
                        const reportBadge = document.createElement('span');
                        reportBadge.className = 'badge text-bg-light border ms-1';
                        reportBadge.textContent = 'Cashiers Report linked';
                        actionCell.append(reportBadge);
                    } else {
                        const removeButton = document.createElement('button');
                        removeButton.type = 'button';
                        removeButton.className = 'btn btn-outline-danger btn-sm rounded-3 js-delete-so-product';
                        removeButton.dataset.index = String(index);
                        removeButton.setAttribute('aria-label', `Delete ${product.product_name || 'product'}`);
                        removeButton.innerHTML = '<i class="bi bi-trash3" aria-hidden="true"></i><span class="visually-hidden"> Delete</span>';
                        actionCell.append(removeButton);
                    }
                } else {
                    actionCell.textContent = linkedToCashiersReport ? 'Cashiers Report' : (hasReceivable ? 'Receivable' : (locked ? 'Locked' : '—'));
                    actionCell.classList.add('text-muted');
                }
            });

            document.getElementById('manage_so_total').textContent = `₱${formatNumber(total)}`;
        };

        const loadManageProducts = (soId) => {
            const loadingRow = manageProductsBody.insertRow();
            const loadingCell = loadingRow.insertCell();
            loadingCell.colSpan = 6;
            loadingCell.className = 'text-center text-muted py-4';
            loadingCell.textContent = 'Loading products…';

            post(urls.products, { so_id: soId }).done(renderManageProducts).fail((xhr) => {
                manageProductsBody.replaceChildren();
                showFeedback(manageFeedback, displayError(xhr, 'Could not load products for this sales order.'));
            });
        };

        const openManageProducts = (soId, canEditProducts, canEditInformation) => {
            activeSoId = soId;
            activeCanEditProducts = canEditProducts;
            activeCanEditInformation = canEditInformation;
            activeEditProduct = null;
            soInformationForm.reset();
            soInformationForm.classList.remove('was-validated');
            soInformationForm.querySelectorAll('input:not([type="hidden"]), select').forEach((field) => { field.disabled = !canEditInformation; });
            document.getElementById('save-so-information').disabled = !canEditInformation;
            editProductForm.reset();
            clearFeedback(editProductFeedback);
            clearFeedback(manageFeedback);
            manageProductsBody.replaceChildren();
            document.getElementById('manage_so_number').textContent = '…';
            document.getElementById('open-so-add-product').hidden = true;
            bootstrapModal(manageModalElement)?.show();

            post(urls.info, { so_id: soId }).done((response) => {
                const order = Array.isArray(response) ? response[0] : response;
                if (!order) {
                    showFeedback(manageFeedback, 'Sales order details were not returned.');
                    return;
                }
                document.getElementById('manage_so_number').textContent = order.so_number || '—';
                document.getElementById('manage_so_id').value = order.so_id || soId;
                document.getElementById('manage_so_branch').value = order.branch_idx || '';
                document.getElementById('manage_so_client').value = order.client_idx || '';
                document.getElementById('manage_so_number_input').value = order.so_number || '';
                document.getElementById('manage_so_date').value = order.order_date || '';
                document.getElementById('manage_so_time').value = String(order.order_time || '').slice(0, 5);
                document.getElementById('manage_so_description').value = order.plate_no || '';
                document.getElementById('manage_so_driver').value = order.drivers_name || '';

                const isCashiersReportSO = Number(order.cashiers_report_idx || 0) !== 0;
                document.getElementById('open-so-add-product').hidden = isCashiersReportSO || !canEditProducts;
                document.getElementById('manage_so_branch').disabled = !canEditInformation || isCashiersReportSO;
                document.getElementById('manage_so_client').disabled = !canEditInformation || isCashiersReportSO;
                document.getElementById('manage_so_date').disabled = !canEditInformation || !canEditSODate;
            }).fail((xhr) => {
                showFeedback(manageFeedback, displayError(xhr, 'Could not load the selected sales order.'));
            });

            loadManageProducts(soId);
        };

        document.getElementById('open-so-add-product').addEventListener('click', () => {
            if (!activeSoId || !activeCanEditProducts) return;
            activeNewOrderForProducts = {
                so_id: activeSoId,
                so_number: document.getElementById('manage_so_number').textContent,
                canEditProducts: activeCanEditProducts,
                canEditInformation: activeCanEditInformation
            };
            document.getElementById('add_so_product_so_id').value = activeSoId;
            document.getElementById('add_so_context_number').textContent = activeNewOrderForProducts.so_number || '—';
            addProductForm.reset();
            addProductForm.classList.remove('was-validated');
            document.getElementById('add_so_product_amount').textContent = 'Standard price will be applied on save';
            clearFeedback(addProductFeedback);
            switchingToProductAdder = true;
            bootstrapModal(manageModalElement)?.hide();
        });

        soInformationForm.addEventListener('submit', (event) => {
            event.preventDefault();
            if (!activeCanEditInformation) return;
            if (!soInformationForm.checkValidity()) {
                soInformationForm.classList.add('was-validated');
                soInformationForm.querySelector(':invalid')?.focus();
                return;
            }

            const saveButton = document.getElementById('save-so-information');
            const spinner = document.getElementById('so-information-spinner');
            saveButton.disabled = true;
            spinner.hidden = false;
            post(urls.updateSoInformation, {
                so_id: document.getElementById('manage_so_id').value,
                branch_id: document.getElementById('manage_so_branch').value,
                client_idx: document.getElementById('manage_so_client').value,
                so_number: document.getElementById('manage_so_number_input').value.trim(),
                order_date: document.getElementById('manage_so_date').value,
                order_time: document.getElementById('manage_so_time').value,
                plate_no: document.getElementById('manage_so_description').value.trim(),
                drivers_name: document.getElementById('manage_so_driver').value.trim()
            }).done(() => {
                document.getElementById('manage_so_number').textContent = document.getElementById('manage_so_number_input').value.trim();
                showFeedback(manageFeedback, 'SO information updated successfully.', 'success');
                table.ajax.reload(null, false);
            }).fail((xhr) => {
                const message = xhr.responseJSON?.errors
                    ? Object.values(xhr.responseJSON.errors).flat().join(' ')
                    : displayError(xhr, 'The SO information could not be updated. Please review the fields and try again.');
                showFeedback(manageFeedback, message);
            }).always(() => {
                saveButton.disabled = !activeCanEditInformation;
                spinner.hidden = true;
            });
        });

        manageProductsBody.addEventListener('click', (event) => {
            const removeButton = event.target.closest('.js-delete-so-product');
            if (removeButton && activeCanEditProducts) {
                const product = activeProducts[Number(removeButton.dataset.index)];
                if (!product) return;
                activeDeleteBillingId = product.billing_id;
                document.getElementById('delete_so_product_name').textContent = product.product_name || 'this product';
                document.getElementById('delete_so_product_order').textContent = document.getElementById('manage_so_number').textContent;
                clearFeedback(deleteProductFeedback);
                bootstrapModal(deleteProductModalElement)?.show();
                return;
            }

            const button = event.target.closest('.js-edit-so-product');
            if (!button || !activeCanEditProducts) return;

            const product = activeProducts[Number(button.dataset.index)];
            if (!product) return;
            activeEditProduct = product;
            editProductForm.reset();
            editProductForm.classList.remove('was-validated');
            document.getElementById('edit-so-cashiers-sync-wrap').hidden = Number(product.cashiers_report_linked || 0) <= 0;
            document.getElementById('edit_so_update_cashiers_report').checked = false;
            document.getElementById('edit_so_id').value = activeSoId;
            document.getElementById('edit_billing_id').value = product.billing_id;
            document.getElementById('edit_branch_idx').value = document.getElementById('manage_so_branch').value;
            document.getElementById('edit_client_idx').value = document.getElementById('manage_so_client').value;
            document.getElementById('edit_so_context_number').textContent = document.getElementById('manage_so_number').textContent;

            const productSelect = document.getElementById('edit_so_product_idx');
            const productId = String(product.product_idx || '');
            if (productId && !Array.from(productSelect.options).some((option) => option.value === productId)) {
                const option = document.createElement('option');
                option.value = productId;
                option.textContent = product.product_name || 'Current product';
                productSelect.add(option);
            }
            productSelect.value = productId;
            productSelect.dataset.originalProductId = productId;
            document.getElementById('edit_so_product_price').value = Number(product.product_price || 0).toFixed(2);
            document.getElementById('edit_so_product_quantity').value = product.order_quantity || '';
            clearFeedback(editProductFeedback);
            switchingToProductEditor = true;
            bootstrapModal(manageModalElement)?.hide();
            updateProductAmountPreview();
        });

        deleteProductButton.addEventListener('click', () => {
            if (!activeSoId || !activeDeleteBillingId || deleteProductButton.disabled) return;
            const spinner = document.getElementById('delete-so-product-spinner');
            deleteProductButton.disabled = true;
            spinner.hidden = false;
            clearFeedback(deleteProductFeedback);
            post(urls.deleteProduct, { so_id: activeSoId, billing_id: activeDeleteBillingId }).done(() => {
                activeDeleteBillingId = null;
                bootstrapModal(deleteProductModalElement)?.hide();
                showFeedback(manageFeedback, 'Product deleted successfully.', 'success');
                loadManageProducts(activeSoId);
                table.ajax.reload(null, false);
            }).fail((xhr) => {
                showFeedback(deleteProductFeedback, displayError(xhr, 'The product could not be deleted.'));
            }).always(() => {
                deleteProductButton.disabled = false;
                spinner.hidden = true;
            });
        });

        const productSelectForEdit = document.getElementById('edit_so_product_idx');
        const productPriceForEdit = document.getElementById('edit_so_product_price');
        const productQuantityForEdit = document.getElementById('edit_so_product_quantity');
        const updateProductAmountPreview = () => {
            const price = productPriceForEdit.value === '' ? 0 : Number(productPriceForEdit.value);
            const amount = price * Number(productQuantityForEdit.value || 0);
            document.getElementById('edit_so_product_amount').textContent = price ? `₱${formatNumber(amount)}` : 'Standard price is recalculated on save';
        };

        productSelectForEdit.addEventListener('change', () => {
            if (productSelectForEdit.value !== productSelectForEdit.dataset.originalProductId) productPriceForEdit.value = '';
            updateProductAmountPreview();
        });
        productPriceForEdit.addEventListener('input', updateProductAmountPreview);
        productQuantityForEdit.addEventListener('input', updateProductAmountPreview);
        document.getElementById('cancel-so-product-edit').addEventListener('click', () => {
            bootstrapModal(editProductModalElement)?.hide();
        });

        editProductForm.addEventListener('submit', (event) => {
            event.preventDefault();
            if (!editProductForm.checkValidity()) {
                editProductForm.classList.add('was-validated');
                editProductForm.querySelector(':invalid')?.focus();
                return;
            }

            const saveButton = document.getElementById('save-so-product-edit');
            const spinner = document.getElementById('so-product-edit-spinner');
            saveButton.disabled = true;
            spinner.hidden = false;
            clearFeedback(editProductFeedback);
            post(urls.updateProduct, {
                so_id: document.getElementById('edit_so_id').value,
                billing_id: document.getElementById('edit_billing_id').value,
                branch_idx: document.getElementById('edit_branch_idx').value,
                client_idx: document.getElementById('edit_client_idx').value,
                product_idx: productSelectForEdit.value,
                product_manual_price: productPriceForEdit.value || 0,
                order_quantity: productQuantityForEdit.value,
                update_cashiers_report: document.getElementById('edit_so_update_cashiers_report').checked ? 1 : 0
            }).done(() => {
                pendingProductSaveMessage = document.getElementById('edit_so_update_cashiers_report').checked
                    ? 'Product and Cashiers Report item updated successfully.'
                    : 'Product updated successfully.';
                returnToManageAfterEdit = true;
                bootstrapModal(editProductModalElement)?.hide();
                table.ajax.reload(null, false);
            }).fail((xhr) => {
                const message = xhr.responseJSON?.errors
                    ? Object.values(xhr.responseJSON.errors).flat().join(' ')
                    : displayError(xhr, 'The product could not be updated. Please review the fields and try again.');
                showFeedback(editProductFeedback, message);
            }).always(() => {
                saveButton.disabled = false;
                spinner.hidden = true;
            });
        });

        manageModalElement.addEventListener('hidden.bs.modal', () => {
            if (switchingToProductEditor) {
                switchingToProductEditor = false;
                returnToManageAfterEdit = true;
                bootstrapModal(editProductModalElement)?.show();
                return;
            }
            if (switchingToProductAdder) {
                switchingToProductAdder = false;
                bootstrapModal(addProductModalElement)?.show();
                return;
            }
            activeSoId = '';
            activeCanEditProducts = false;
            activeCanEditInformation = false;
            activeProducts = [];
            activeEditProduct = null;
            editProductForm.reset();
            manageProductsBody.replaceChildren();
        });

        editProductModalElement.addEventListener('hidden.bs.modal', () => {
            editProductForm.reset();
            editProductForm.classList.remove('was-validated');
            clearFeedback(editProductFeedback);
            if (!returnToManageAfterEdit) return;
            returnToManageAfterEdit = false;
            bootstrapModal(manageModalElement)?.show();
            if (activeSoId) loadManageProducts(activeSoId);
            if (pendingProductSaveMessage) {
                showFeedback(manageFeedback, pendingProductSaveMessage, 'success');
                pendingProductSaveMessage = '';
            }
        });

        $('#getSOBillingTransactionList').on('click', '.js-open-so-products', function (event) {
            event.preventDefault();
            if (this.dataset.id) {
                openManageProducts(this.dataset.id, this.dataset.canEdit === '1', this.dataset.canEditInformation === '1');
            }
        });

        $('#getSOBillingTransactionList').on('click', '.js-delete-so, #deleteSO', function (event) {
            event.preventDefault();
            const soId = this.dataset.id;
            if (!soId) return;

            clearFeedback(deleteFeedback);
            deleteButton.value = soId;
            deleteButton.disabled = true;
            document.getElementById('so-delete-spinner').hidden = false;
            const productBody = document.getElementById('so_product_list_data_delete');
            productBody.replaceChildren();
            const loadingRow = productBody.insertRow();
            const loadingCell = loadingRow.insertCell();
            loadingCell.colSpan = 5;
            loadingCell.className = 'text-center text-muted';
            loadingCell.textContent = 'Loading products…';

            post(urls.info, { so_id: soId }).done((response) => {
                const order = Array.isArray(response) ? response[0] : response;
                if (!order) {
                    showFeedback(listFeedback, 'Sales order details were not returned.');
                    return;
                }
                setText('delete_so_order_date', order.order_date);
                setText('delete_so_order_time', order.order_time);
                setText('delete_so_so_number', order.so_number);
                setText('delete_so_client_name', order.client_name);
                setText('delete_so_drivers_name', order.drivers_name);
                setText('delete_so_plate_no', order.plate_no);
                bootstrapModal(deleteModalElement)?.show();
                loadProducts(soId);
            }).fail((xhr) => {
                showFeedback(listFeedback, displayError(xhr, 'Could not load the selected sales order.'));
            }).always(() => {
                deleteButton.disabled = false;
                document.getElementById('so-delete-spinner').hidden = true;
            });
        });

        deleteButton.addEventListener('click', () => {
            const soId = deleteButton.value;
            if (!soId || deleteButton.disabled) return;

            deleteButton.disabled = true;
            document.getElementById('so-delete-spinner').hidden = false;
            post(urls.remove, { so_id: soId }).done(() => {
                bootstrapModal(deleteModalElement)?.hide();
                showFeedback(listFeedback, 'Sales order deleted.', 'success');
                table.ajax.reload(null, false);
            }).fail((xhr) => {
                showFeedback(deleteFeedback, displayError(xhr, 'The sales order could not be deleted. Please try again.'));
            }).always(() => {
                deleteButton.disabled = false;
                document.getElementById('so-delete-spinner').hidden = true;
            });
        });

        addSOModalElement.addEventListener('hidden.bs.modal', () => {
            form.reset();
            form.classList.remove('was-validated');
            resetFieldErrors();
            clearFeedback(createFeedback);
            if (!pendingNewOrderForProducts) return;
            activeNewOrderForProducts = pendingNewOrderForProducts;
            pendingNewOrderForProducts = null;
            document.getElementById('add_so_product_so_id').value = activeNewOrderForProducts.so_id;
            document.getElementById('add_so_context_number').textContent = activeNewOrderForProducts.so_number || '—';
            bootstrapModal(addProductModalElement)?.show();
        });
    })();
</script>
