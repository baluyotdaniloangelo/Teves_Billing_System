<script>
window.selectedProductId = @json($productID ?? null);
window.initialProductTab = @json($tab ?? 'product-info');
window.productInformationModalTemporarilyHidden = false;
window.restoreProductInformationAfterChildModal = false;

function getSelectedProductId() {
    return window.selectedProductId || null;
}

function setProductPageState(productId) {
    const url = new URL(window.location.href);
    if (productId) {
        url.searchParams.set('productID', productId);
    } else {
        url.searchParams.delete('productID');
    }
    history.replaceState({}, '', url);
}

function showProductInformationChildModal(modalSelector) {
    const targetModal = document.querySelector(modalSelector);
    const productInformationModal = document.getElementById('ProductInformationModal');
    if (!targetModal || !productInformationModal) return;
    if (window.productInformationModalTemporarilyHidden && !productInformationModal.classList.contains('show')) return;

    const showTargetWhenReady = function () {
        if (targetModal.classList.contains('show')) return;

        const activeChildModal = Array.from(document.querySelectorAll('.product-information-modal-theme.show'))
            .find(function (modal) {
                return modal !== productInformationModal && modal !== targetModal;
            });

        if (activeChildModal) {
            if (activeChildModal._productInformationRestoreHandler) {
                activeChildModal.removeEventListener('hidden.bs.modal', activeChildModal._productInformationRestoreHandler);
                activeChildModal._productInformationRestoreHandler = null;
            }
            activeChildModal.addEventListener('hidden.bs.modal', showTargetWhenReady, { once: true });
            $(activeChildModal).modal('hide');
            return;
        }

        if (window.restoreProductInformationAfterChildModal && !targetModal._productInformationRestoreHandler) {
            const restoreProductInformation = function () {
                targetModal._productInformationRestoreHandler = null;
                if (!window.restoreProductInformationAfterChildModal) return;
                window.restoreProductInformationAfterChildModal = false;
                $(productInformationModal).modal('show');
            };
            targetModal._productInformationRestoreHandler = restoreProductInformation;
            targetModal.addEventListener('hidden.bs.modal', restoreProductInformation, { once: true });
        }

        $(targetModal).modal('show');
    };

    if (productInformationModal.classList.contains('show')) {
        window.restoreProductInformationAfterChildModal = true;
        window.productInformationModalTemporarilyHidden = true;
        productInformationModal.addEventListener('hidden.bs.modal', showTargetWhenReady, { once: true });
        $(productInformationModal).modal('hide');
        return;
    }

    showTargetWhenReady();
}

function updateFuelProductTabs() {
    const selectedCategory = $('#update_category_idx option:selected').text().trim().toUpperCase();
    const isFuelProduct = selectedCategory === 'FUEL';

    $('#tank-tab-item, #pump-tab-item').prop('hidden', !isFuelProduct);

    const activeTab = $('#myTab .nav-link.active').data('bs-target');
    if (!isFuelProduct && (activeTab === '#tank' || activeTab === '#pump')) {
        $('#product-info-tab').tab('show');
    }

    return isFuelProduct;
}

window.ProductFormValidation = {
    initialize: function (form) {
        if (!form || form.dataset.validationInitialized === 'true') return;

        form.querySelectorAll('.invalid-feedback').forEach(function (feedback) {
            if (feedback.dataset.defaultMessage === undefined) {
                feedback.dataset.defaultMessage = feedback.textContent.trim();
            }
        });

        form.addEventListener('input', function (event) {
            if (event.target.matches('input, select, textarea')) {
                window.ProductFormValidation.clearField(event.target);
            }
        });
        form.addEventListener('change', function (event) {
            if (event.target.matches('input, select, textarea')) {
                window.ProductFormValidation.clearField(event.target);
            }
        });
        form.dataset.validationInitialized = 'true';
    },

    clearField: function (field) {
        if (!field) return;
        field.classList.remove('is-invalid');

        const feedback = field.id ? document.getElementById(field.id + 'Error') : null;
        if (feedback) {
            feedback.classList.remove('d-block');
            feedback.textContent = feedback.dataset.defaultMessage || '';
        }
    },

    clearErrors: function (form) {
        if (!form) return;
        form.querySelectorAll('.is-invalid').forEach(function (field) {
            field.classList.remove('is-invalid');
        });
        form.querySelectorAll('.invalid-feedback').forEach(function (feedback) {
            feedback.classList.remove('d-block');
            feedback.textContent = feedback.dataset.defaultMessage || '';
        });
    },

    reset: function (form) {
        if (!form) return;
        window.ProductFormValidation.clearErrors(form);
        form.classList.remove('was-validated');
    },

    showErrors: function (form, errors, fieldMap) {
        if (!form || !errors) return 0;
        const aliases = fieldMap || {};
        let shown = 0;

        Object.keys(errors).forEach(function (errorKey) {
            const fieldName = aliases[errorKey] || errorKey;
            const field = form.elements.namedItem(fieldName);
            if (!field || !field.classList) return;

            const feedback = field.id ? document.getElementById(field.id + 'Error') : null;
            const rawMessage = errors[errorKey];
            const message = Array.isArray(rawMessage) ? rawMessage[0] : rawMessage;
            field.classList.add('is-invalid');
            if (feedback && message) {
                feedback.textContent = message;
                feedback.classList.add('d-block');
            }
            shown += 1;
        });

        return shown;
    }
};

function showProductSuccessModal(message) {
    $('#productSuccessModalMessage').text(message);
    $('#ProductSuccessModal').modal('show');
    window.setTimeout(function () {
        $('#ProductSuccessModal').modal('hide');
    }, 1500);
}

function showProductValidationErrorModal(message) {
    const validationModal = document.getElementById('ProductValidationErrorModal');
    if (!validationModal) return;

    const messages = Array.isArray(message) ? message : [message];
    $('#productValidationErrorModalMessage').text(messages.filter(Boolean).join('\n'));

    if (validationModal.classList.contains('show')) return;

    const activeModal = Array.from(document.querySelectorAll('.product-information-modal-theme.show'))
        .find(function (modal) { return modal !== validationModal; });

    if (!activeModal) {
        $(validationModal).modal('show');
        return;
    }

    const restoreHandler = activeModal._productInformationRestoreHandler || null;
    if (restoreHandler) {
        activeModal.removeEventListener('hidden.bs.modal', restoreHandler);
        activeModal._productInformationRestoreHandler = null;
    }

    if (activeModal.id === 'ProductInformationModal') {
        window.productInformationModalTemporarilyHidden = true;
    }

    activeModal.addEventListener('hidden.bs.modal', function () {
        validationModal.addEventListener('hidden.bs.modal', function () {
            if (restoreHandler && window.restoreProductInformationAfterChildModal) {
                activeModal._productInformationRestoreHandler = restoreHandler;
                activeModal.addEventListener('hidden.bs.modal', restoreHandler, { once: true });
            } else if (activeModal.id !== 'ProductInformationModal' && window.restoreProductInformationAfterChildModal) {
                const productInformationModal = document.getElementById('ProductInformationModal');
                const restoreProductInformation = function () {
                    activeModal._productInformationRestoreHandler = null;
                    if (!window.restoreProductInformationAfterChildModal) return;
                    window.restoreProductInformationAfterChildModal = false;
                    $(productInformationModal).modal('show');
                };
                activeModal._productInformationRestoreHandler = restoreProductInformation;
                activeModal.addEventListener('hidden.bs.modal', restoreProductInformation, { once: true });
            }

            $(activeModal).modal('show');
        }, { once: true });

        $(validationModal).modal('show');
    }, { once: true });

    $(activeModal).modal('hide');
}

window.validateProductSettingsForm = function (form) {
    if (!form) return false;

    const invalidFields = Array.from(form.querySelectorAll('input, select, textarea'))
        .filter(function (field) { return field.willValidate && !field.checkValidity(); });

    if (!invalidFields.length) return true;

    const messages = invalidFields.map(function (field) {
        const label = form.querySelector('label[for="' + field.id + '"]');
        const fieldName = label ? label.textContent.trim() : (field.name || 'Field');
        return fieldName + ': ' + (field.validationMessage || 'Please check this field.');
    });

    showProductValidationErrorModal(messages);
    return false;
};

window.showProductRequestError = function (xhr, fallbackMessage, form) {
    const response = (xhr && xhr.responseJSON) || {};
    const errors = response.errors || {};
    const messages = [];
    const fieldLabels = { product_idx: 'Product', supplier_idx: 'Supplier', client_idx: 'Client' };

    Object.keys(errors).forEach(function (key) {
        const rawMessages = Array.isArray(errors[key]) ? errors[key] : [errors[key]];
        const field = form && form.elements.namedItem(key);
        const label = field && field.id ? form.querySelector('label[for="' + field.id + '"]') : null;
        const fieldName = label ? label.textContent.trim() : (fieldLabels[key] || key.replace(/_/g, ' '));
        rawMessages.filter(Boolean).forEach(function (item) {
            messages.push(fieldName + ': ' + item);
        });
    });

    if (!messages.length && response.message) messages.push(response.message);
    showProductValidationErrorModal(messages.length ? messages : fallbackMessage);
}

function showProductSuccessAfterModalClose(parentModal, message) {
    if (parentModal && parentModal.classList.contains('show')) {
        parentModal.addEventListener('hidden.bs.modal', function () {
            showProductSuccessModal(message);
        }, { once: true });
        $(parentModal).modal('hide');
        return;
    }

    showProductSuccessModal(message);
}

$(function () {
    window.ProductFormValidation.initialize(document.getElementById('ProductformNew'));
    window.ProductFormValidation.initialize(document.getElementById('ProductformEdit'));

    window.ProductListTable = $('#getProductList').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        stateSave: true,
        scrollCollapse: true,
        scrollY: '500px',
        searching: true,
        lengthChange: false,
        dom: 'rt<"d-flex justify-content-between align-items-center mt-3"ip>',
        ajax: "{{ route('getProductList') }}",
        info: true,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center text-muted' },
            { data: 'product_name', render: $.fn.dataTable.render.text(), className: 'fw-semibold' },
            { data: 'category_name', name: 'teves_product_category.category_name', defaultContent: 'Uncategorized', render: $.fn.dataTable.render.text(), className: 'text-center' },
            { data: 'product_price', orderable: false, render: $.fn.dataTable.render.number(',', '.', 2, ''), className: 'text-end' },
            { data: 'product_unit_measurement', className: 'text-center' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ]
    });

    $('#product_list_search').val(window.ProductListTable.search());
    $('#product_list_category').val(window.ProductListTable.column(2).search());
    $('#product_list_length').val(String(window.ProductListTable.page.len()));

    let productSearchTimer;
    $('#product_list_search').on('input', function () {
        const searchValue = this.value;
        window.clearTimeout(productSearchTimer);
        productSearchTimer = window.setTimeout(function () {
            window.ProductListTable.search(searchValue).draw();
        }, 350);
    });

    $('#product_list_length').on('change', function () {
        window.ProductListTable.page.len(Number(this.value)).draw();
    });

    $('#product_list_category').on('change', function () {
        window.ProductListTable.column(2).search(this.value).draw();
    });

    $('#resetProductListFilters').on('click', function () {
        $('#product_list_search').val('');
        $('#product_list_category').val('');
        $('#product_list_length').val('10');
        window.ProductListTable.search('');
        window.ProductListTable.column(2).search('');
        window.ProductListTable.page.len(10).draw();
    });

    $('#getProductList').on('click', '.select-product', function (event) {
        event.preventDefault();
        selectProduct($(this).data('id'));
    });

    $(document).on('click', '[data-product-modal-target]', function (event) {
        event.preventDefault();
        showProductInformationChildModal(this.getAttribute('data-product-modal-target'));
    });

    $('#update_category_idx').on('change', function () {
        if (updateFuelProductTabs()) {
            if (typeof LoadProductTank === 'function') LoadProductTank();
            if (typeof LoadProductPump === 'function') LoadProductPump();
        }
    });

    if (window.selectedProductId) {
        window.selectedProductId = null;
        setProductPageState(null);
    }
});

function selectProduct(productId, options) {
    if (!productId) return;

    window.selectedProductId = productId;
    window.ProductFormValidation.reset(document.getElementById('ProductformEdit'));
    $('#product-info-tab').tab('show');
    $('#ProductInformationModal').modal('show');
    setProductPageState(productId);

    if (typeof ProductInfo === 'function') ProductInfo(productId, options || {});
    if (typeof loadPricingListPerBranch === 'function') loadPricingListPerBranch();
    if (typeof loadPricingListPerSeller === 'function') loadPricingListPerSeller();
    if (typeof loadProductSellingPrice === 'function') loadProductSellingPrice();

}

$('#ProductInformationModal').on('shown.bs.modal', function () {
    window.setTimeout(function () {
        if ($.fn.dataTable && $.fn.dataTable.tables) {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        }
    }, 50);
});

$('#ProductInformationModal').on('hidden.bs.modal', function () {
    if (window.productInformationModalTemporarilyHidden) {
        window.productInformationModalTemporarilyHidden = false;
        return;
    }

    window.selectedProductId = null;
    setProductPageState(null);
    window.ProductFormValidation.reset(document.getElementById('ProductformEdit'));
    $('#productInformationModalTitle').text('Product Information');
    $('#productInformationHeading').text('Select a product to manage its details.');
});

$(document).on('shown.bs.tab', '#myTab button[data-bs-toggle="tab"]', function () {
    const url = new URL(window.location.href);
    url.searchParams.set('tab', $(this).data('bs-target').replace('#', ''));
    history.replaceState({}, '', url);
});

$('#ProductformNew').on('submit', function (event) {
    event.preventDefault();
    const productForm = this;
    const saveButton = document.getElementById('save-product');

    productForm.classList.add('was-validated');
    if (!productForm.checkValidity()) {
        productForm.reportValidity();
        return;
    }

    window.ProductFormValidation.clearErrors(productForm);
    const originalButtonHtml = saveButton.innerHTML;
    saveButton.disabled = true;
    saveButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Saving...';

    $.ajax({
        url: "{{ route('create_product_post') }}",
        type: 'POST',
        data: {
            product_name: $('#product_name').val(),
            category_idx: $('#category_idx').val(),
            product_price: $('#product_price').val(),
            product_unit_measurement: $('#product_unit_measurement').val(),
            _token: "{{ csrf_token() }}"
        },
        success: function (response) {
            productForm.reset();
            window.ProductFormValidation.reset(productForm);
            if (window.ProductListTable) window.ProductListTable.ajax.reload(null, false);

            if (response.productID) {
                const createModal = document.getElementById('CreateProductModal');
                const openNewProduct = function () {
                    selectProduct(response.productID, { newlyCreated: true });
                };

                if (createModal && createModal.classList.contains('show')) {
                    createModal.addEventListener('hidden.bs.modal', openNewProduct, { once: true });
                    $(createModal).modal('hide');
                } else {
                    openNewProduct();
                }
            } else {
                showProductSuccessAfterModalClose(
                    document.getElementById('CreateProductModal'),
                    response.success || 'Product created successfully.'
                );
            }
        },
        error: function (xhr) {
            const response = xhr.responseJSON || {};
            const errors = response.errors || {};
            const shownErrors = window.ProductFormValidation.showErrors(productForm, errors);
            if (!shownErrors) {
                showProductValidationErrorModal(response.message || 'Unable to create the product. Please review the form and try again.');
            }
        },
        complete: function () {
            saveButton.disabled = false;
            saveButton.innerHTML = originalButtonHtml;
        }
    });
});

$('#ProductformNew').on('reset', function () {
    const form = this;
    window.setTimeout(function () {
        window.ProductFormValidation.reset(form);
    }, 0);
});

$('#CreateProductModal').on('hidden.bs.modal', function () {
    const form = document.getElementById('ProductformNew');
    if (form) {
        form.reset();
        window.ProductFormValidation.reset(form);
    }
});

$(document).on('click', '.delete-product', function (event) {
    event.preventDefault();
    const productId = $(this).data('id');

    $.ajax({
        url: "{{ route('product_info') }}",
        type: 'POST',
        data: { productID: productId, _token: "{{ csrf_token() }}" }
    }).done(function (product) {
        if (!product) return;
        $('#deleteProductConfirmed').val(productId);
        $('#confirm_delete_product_name').text(product.product_name || '');
        $('#confirm_delete_product_price').text(product.product_price || '');
        showProductInformationChildModal('#ProductDeleteModal');
    }).fail(function (xhr) {
        window.showProductRequestError(xhr, 'Unable to load this product. Please try again.');
    });
});

$(document).on('click', '#deleteProductConfirmed', function (event) {
    event.preventDefault();
    const button = this;
    const productId = button.value;
    const originalButtonHtml = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Deleting...';

    $.ajax({
        url: "{{ route('delete_product_confirmed') }}",
        type: 'POST',
        data: { productID: productId, _token: "{{ csrf_token() }}" }
    }).done(function () {
        $('#ProductDeleteModal').modal('hide');
        window.ProductListTable.ajax.reload(null, false);
        if (String(getSelectedProductId()) === String(productId)) {
            window.selectedProductId = null;
            $('#ProductInformationModal').modal('hide');
            setProductPageState(null);
        }
        showProductSuccessAfterModalClose(document.getElementById('ProductDeleteModal'), 'Product deleted successfully.');
    }).fail(function (xhr) {
        window.showProductRequestError(xhr, 'Unable to delete this product. Please try again.');
    }).always(function () {
        button.disabled = false;
        button.innerHTML = originalButtonHtml;
    });
});</script>
