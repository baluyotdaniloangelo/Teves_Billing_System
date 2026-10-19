<script>
(function ($) {
    'use strict';

    let activeMainProductId = null;
    let activeMainProductName = '';
    let unlinkReturnAfterSuccess = false;

    function refreshProductTables() {
        if (window.ProductListTable) window.ProductListTable.ajax.reload(null, false);
        if (window.UnassignedProductTable) window.UnassignedProductTable.ajax.reload(null, false);
        if (window.MainProductVariantsTable && activeMainProductId) window.MainProductVariantsTable.ajax.reload(null, false);
    }

    function loadMainOptions($select, selectedId, categoryId) {
        $select.prop('disabled', true).empty().append(new Option('Loading main products…', ''));
        return $.get("{{ route('getMainProductOptions') }}")
            .done(function (options) {
                $select.empty();
                $select.append(new Option(categoryId ? 'No main products in this category' : 'Select a main product', ''));
                (options || []).forEach(function (option) {
                    if (categoryId && String(option.category_idx) !== String(categoryId)) return;
                    const label = option.product_name + (option.category_name ? ' — ' + option.category_name : '');
                    const element = new Option(label, option.product_main_id);
                    element.dataset.categoryIdx = option.category_idx;
                    $select.append(element);
                });
                if (selectedId) $select.val(String(selectedId)).trigger('change');
                $select.prop('disabled', false);
            })
            .fail(function (xhr) {
                $select.empty().append(new Option('Unable to load main products', '')).prop('disabled', false);
                window.showProductRequestError(xhr, 'Unable to load the main product list. Please try again.');
            });
    }

    function syncVariantCategory() {
        const $main = $('#product_main_idx');
        const $category = $('#category_idx');
        const categoryId = $main.find(':selected').data('category-idx');

        if ($main.val() && categoryId) {
            $category.val(String(categoryId)).prop('disabled', true);
        } else {
            $category.prop('disabled', false);
        }
    }

    function initializeVariantsTable() {
        if (window.MainProductVariantsTable) {
            window.MainProductVariantsTable.ajax.reload(null, false);
            return;
        }

        window.MainProductVariantsTable = $('#MainProductVariantsTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: false,
            searching: true,
            lengthChange: true,
            pageLength: 10,
            ajax: {
                url: "{{ route('getMainProductVariants') }}",
                data: function (request) {
                    request.product_main_idx = activeMainProductId;
                }
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center text-muted' },
                { data: 'product_name', render: $.fn.dataTable.render.text(), className: 'fw-semibold' },
                { data: 'category_name', defaultContent: 'Uncategorized', render: $.fn.dataTable.render.text(), className: 'text-center' },
                { data: 'product_price', orderable: false, render: $.fn.dataTable.render.number(',', '.', 2, ''), className: 'text-end' },
                { data: 'product_unit_measurement', className: 'text-center' },
                { data: 'action', orderable: false, searchable: false, className: 'text-center' }
            ]
        });
    }

    function openManageMainProduct(mainProduct) {
        activeMainProductId = mainProduct.product_main_id;
        activeMainProductName = mainProduct.product_name || '';
        $('#ManageMainProductModalLabel').text(activeMainProductName + ' — Product Variants');
        $('#manageMainProductCategory').text(mainProduct.category_name || 'Manage variants linked to this main product.');
        $('#ManageMainProductModal').modal('show');
    }

    function getProductRowData(button, table) {
        const $row = $(button).closest('tr');
        const row = $row.hasClass('child') ? $row.prev() : $row;
        return table.row(row).data() || {};
    }

    $(function () {
        $('#MainProductModal').on('show.bs.modal', function (event) {
            if (event.relatedTarget && !$(event.relatedTarget).hasClass('edit-main-product')) {
                $('#MainProductForm')[0].reset();
                $('#product_main_id').val('');
                $('#MainProductModalLabel').text('Add Main Product');
            }
        });

        $('#MainProductForm').on('submit', function (event) {
            event.preventDefault();
            const form = this;
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const id = $('#product_main_id').val();
            const $button = $('#save-main-product');
            const originalHtml = $button.html();
            $button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Saving…');

            $.ajax({
                url: id ? "{{ route('update_product_main_post') }}" : "{{ route('create_product_main_post') }}",
                type: 'POST',
                data: $(form).serialize() + '&_token={{ csrf_token() }}'
            }).done(function (response) {
                const message = response.success || 'Main product saved successfully.';
                form.reset();
                $('#product_main_id').val('');
                refreshProductTables();
                loadMainOptions($('#product_main_idx'), null, null);
                showProductSuccessAfterModalClose(document.getElementById('MainProductModal'), message);
            }).fail(function (xhr) {
                window.showProductRequestError(xhr, 'Unable to save the main product. Please review the details and try again.', form);
            }).always(function () {
                $button.prop('disabled', false).html(originalHtml);
            });
        });

        $('#MainProductModal').on('hidden.bs.modal', function () {
            $('#MainProductForm')[0].reset();
            $('#product_main_id').val('');
            $('#MainProductModalLabel').text('Add Main Product');
        });

        $('#getProductList').on('click', '.manage-main-product', function () {
            const row = getProductRowData(this, window.ProductListTable);
            openManageMainProduct(row);
        });

        $('#getProductList').on('click', '.edit-main-product', function () {
            const row = getProductRowData(this, window.ProductListTable);
            $('#product_main_id').val(row.product_main_id || '');
            $('#main_product_name').val(row.product_name || '');
            $('#main_category_idx').val(row.category_idx || '');
            $('#MainProductModalLabel').text('Edit Main Product');
            $('#MainProductModal').modal('show');
        });

        $('#getProductList').on('click', '.delete-main-product', function () {
            const row = getProductRowData(this, window.ProductListTable);
            $('#delete_product_main_id').val(row.product_main_id || '');
            $('#deleteMainProductName').text(row.product_name || 'this main product');
            $('#DeleteMainProductModal').modal('show');
        });

        $('#confirmDeleteMainProduct').on('click', function () {
            const button = this;
            const originalHtml = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Deleting…';

            $.post("{{ route('delete_product_main_confirmed') }}", {
                product_main_id: $('#delete_product_main_id').val(),
                _token: "{{ csrf_token() }}"
            }).done(function (response) {
                refreshProductTables();
                showProductSuccessAfterModalClose(document.getElementById('DeleteMainProductModal'), response.success || 'Main product deleted successfully.');
            }).fail(function (xhr) {
                window.showProductRequestError(xhr, 'Unable to delete this main product. Remove or assign its variants first.');
            }).always(function () {
                button.disabled = false;
                button.innerHTML = originalHtml;
            });
        });

        $('#ManageMainProductModal').on('shown.bs.modal', function () {
            initializeVariantsTable();
            window.setTimeout(function () {
                if (window.MainProductVariantsTable) window.MainProductVariantsTable.columns.adjust();
            }, 50);
        });

        $('#ManageMainProductModal').on('click', '.select-product', function () {
            const row = getProductRowData(this, window.MainProductVariantsTable);
            if (!row.product_id) return;
            window.returnToMainVariantsId = activeMainProductId;
            $('#ProductInformationModal').one('shown.bs.modal', function () {
                selectProduct(row.product_id);
            });
            showProductInformationChildModal('#ProductInformationModal');
        });

        $('#addVariantToMainProduct').on('click', function () {
            window.returnToMainVariantsId = activeMainProductId;
            window.pendingProductMainID = activeMainProductId;
            showProductInformationChildModal('#CreateProductModal');
        });

        $('#CreateProductModal').on('shown.bs.modal', function () {
            loadMainOptions($('#product_main_idx'), window.pendingProductMainID || null, null);
        });

        $('#CreateProductModal').on('hidden.bs.modal', function () {
            $('#category_idx').prop('disabled', false);
            if (window.returnToMainVariantsId && !window.openingCreatedProductInformation) {
                window.returnToMainVariantsId = null;
                window.pendingProductMainID = null;
                const reopen = function () {
                    if (window.MainProductVariantsTable) window.MainProductVariantsTable.ajax.reload(null, false);
                    $('#ManageMainProductModal').modal('show');
                };
                window.setTimeout(reopen, 0);
            }
        });

        $('#product_main_idx').on('change', syncVariantCategory);

        $('#ProductInformationModal').on('hidden.bs.modal', function () {
            if (!window.returnToMainVariantsId || window.restoreProductInformationAfterChildModal) return;
            window.returnToMainVariantsId = null;
            window.pendingProductMainID = null;
            const reopen = function () {
                if (window.MainProductVariantsTable) window.MainProductVariantsTable.ajax.reload(null, false);
                $('#ManageMainProductModal').modal('show');
            };
            window.setTimeout(reopen, 0);
        });

        $('#getUnassignedProductList').on('click', '.select-product', function () {
            const row = getProductRowData(this, window.UnassignedProductTable);
            if (row.product_id) selectProduct(row.product_id);
        });

        $('#getUnassignedProductList').on('click', '.assign-product-variant', function () {
            const row = getProductRowData(this, window.UnassignedProductTable);
            $('#assign_product_id').val(row.product_id || '');
            $('#assign_product_category_idx').val(row.category_idx || '');
            $('#assignVariantName').text(row.product_name ? 'Assign “' + row.product_name + '” to a main product.' : 'Choose a main product for this variant.');
            $('#assign_product_main_idx').empty().append(new Option('Loading main products…', '')).prop('disabled', true);
            $('#AssignProductVariantModal').modal('show');
            loadMainOptions($('#assign_product_main_idx'), null, row.category_idx || null);
        });

        $('#AssignProductVariantForm').on('submit', function (event) {
            event.preventDefault();
            const form = this;
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const $button = $('#confirmAssignVariant');
            const originalHtml = $button.html();
            $button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Assigning…');
            $.post("{{ route('assign_product_variant') }}", {
                product_id: $('#assign_product_id').val(),
                product_main_idx: $('#assign_product_main_idx').val(),
                _token: "{{ csrf_token() }}"
            }).done(function (response) {
                refreshProductTables();
                showProductSuccessAfterModalClose(document.getElementById('AssignProductVariantModal'), response.success || 'Product variant assigned successfully.');
            }).fail(function (xhr) {
                window.showProductRequestError(xhr, 'Unable to assign this variant. Confirm the categories match.', form);
            }).always(function () {
                $button.prop('disabled', false).html(originalHtml);
            });
        });

        $('#ManageMainProductModal, #UnlinkProductVariantModal').on('click', '#MainProductVariantsTable .unlink-product-variant', function (event) {
            event.preventDefault();
            const row = getProductRowData(this, window.MainProductVariantsTable);
            window.returnToMainVariantsId = activeMainProductId;
            $('#unlink_product_id').val(row.product_id || '');
            $('#unlinkProductName').text(row.product_name || 'this variant');
            showProductInformationChildModal('#UnlinkProductVariantModal');
        });

        $('#UnlinkProductVariantModal').on('hidden.bs.modal', function () {
            if (unlinkReturnAfterSuccess) {
                unlinkReturnAfterSuccess = false;
                return;
            }
            if (!window.returnToMainVariantsId) return;
            window.returnToMainVariantsId = null;
            window.setTimeout(function () {
                $('#ManageMainProductModal').modal('show');
            }, 0);
        });

        $('#confirmUnlinkProductVariant').on('click', function () {
            const button = this;
            const originalHtml = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Removing…';
            $.post("{{ route('unlink_product_variant') }}", {
                product_id: $('#unlink_product_id').val(),
                product_main_idx: activeMainProductId,
                _token: "{{ csrf_token() }}"
            }).done(function (response) {
                if (window.ProductListTable) window.ProductListTable.ajax.reload(null, false);
                if (window.UnassignedProductTable) window.UnassignedProductTable.ajax.reload(null, false);
                unlinkReturnAfterSuccess = true;
                $('#UnlinkProductVariantModal').one('hidden.bs.modal', function () {
                    const successModal = document.getElementById('ProductSuccessModal');
                    if (successModal) {
                        successModal.addEventListener('hidden.bs.modal', function () {
                            window.returnToMainVariantsId = null;
                            $('#ManageMainProductModal').modal('show');
                            if (window.MainProductVariantsTable) window.MainProductVariantsTable.ajax.reload(null, false);
                        }, { once: true });
                    }
                    showProductSuccessModal(response.success || 'Product variant removed from the main product.');
                });
                $('#UnlinkProductVariantModal').modal('hide');
            }).fail(function (xhr) {
                window.showProductRequestError(xhr, 'Unable to remove this variant link. Please try again.');
            }).always(function () {
                button.disabled = false;
                button.innerHTML = originalHtml;
            });
        });

        $('#ManageMainProductModal').on('hidden.bs.modal', function () {
            if (!window.returnToMainVariantsId) activeMainProductId = null;
        });
    });
})(jQuery);
</script>
