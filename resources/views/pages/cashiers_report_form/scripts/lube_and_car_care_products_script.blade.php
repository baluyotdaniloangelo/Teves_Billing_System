<script type="text/javascript">	
/*==================================================
CASHIER'S REPORT - PH2
LUBRICANTS & CAR CARE PRODUCTS
==================================================*/

$(document).ready(function ()
{
    initializeCashiersReportLubeCarCareTable();
});

let CashiersReportLubeCarCareTable;

function initializeCashiersReportLubeCarCareTable()
{
    CashiersReportLubeCarCareTable =
        $('#lube_and_car_care_products_table').DataTable({
            processing: true,
            responsive: true,
            stateSave: false,
            autoWidth: false,

            ajax:
            {
                url: "{{ route('GetLubeAndCarCareProducts') }}",
                type: "POST",

                data: function (d)
                {
                    d.CashiersReportId = {{ $CashiersReportId }};
                    d._token = "{{ csrf_token() }}";
                },

                dataSrc: function (response)
                {
                    return response || [];
                }
            },

            columns:
            [
				/* ROW NUMBER */
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    width: '60px',

                    render: function (data, type, row, meta)
                    {
                        return meta.row + 1;
                    }
                }, 
                {
                    data: 'product_name',
                    className: 'text-start'
                },
                {
                    data: 'order_quantity',
                    className: 'text-center'
                },
                {
                    data: 'product_price',
                    className: 'text-end',
                    render: function (data)
                    {
                        return formatLubeCarCareAmount(data);
                    }
                },
                {
                    data: 'order_total_amount',
                    className: 'text-end',
                    render: function (data)
                    {
                        return formatLubeCarCareAmount(data);
                    }
                },
                {
                    data: 'cashiers_report_p2_id',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',

                    render: function (data)
                    {
                        return `
                            <div>

                                <a href="#"
                                   class="btn btn-sm btn-warning btn-circle bi-pencil-fill"
                                   id="editCashiersReportLubeCarCare"
                                   data-id="${data}"
                                   title="Edit">
                                </a>

                                <a href="#"
                                   class="btn btn-sm btn-danger btn-circle bi-trash3-fill"
                                   id="deleteLubeCarCare"
                                   data-id="${data}"
                                   title="Delete">
                                </a>

                            </div>
                        `;
                    }
                }
            ],

            order: [[0, 'asc']],

            language:
            {
                emptyTable: "No lubricants or car care products found."
            }
        });

    autoAdjustColumns(CashiersReportLubeCarCareTable);
}

/*==================================================
RELOAD LUBRICANTS & CAR CARE DATATABLE
==================================================*/

function reloadLubeCarCareTable()
{
    if (
        typeof CashiersReportLubeCarCareTable !== 'undefined' &&
        CashiersReportLubeCarCareTable
    )
    {
        CashiersReportLubeCarCareTable
            .ajax
            .reload(null, false);
    }
}

/*==================================================
FORMAT AMOUNT
==================================================*/

function formatLubeCarCareAmount(value)
{
    return (Number(value) || 0).toLocaleString('en-PH',
    {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}


/*==================================================
AUTO ADJUST DATATABLE COLUMNS
==================================================*/

function autoAdjustColumns(table)
{
    const container =
        table.table().container();

    const resizeObserver =
        new ResizeObserver(function ()
        {
            table.columns.adjust();
        });

    resizeObserver.observe(container);
}

/*==================================================
CALCULATE LUBRICANTS & CAR CARE TOTAL
==================================================*/

$(document).on(
    'input change',
    '#lube_car_care_product, #lube_car_care_quantity, #lube_car_care_unit_price',
    function ()
    {
        calculateLubeCarCareTotal();
    }
);

function calculateLubeCarCareTotal()
{
    const productName =
        $('#lube_car_care_product').val();

    const productPrice =
        $('#lubeCarCareProductList option[value="' + productName + '"]')
            .attr('data-price');

    const manualPrice =
        $('#lube_car_care_unit_price').val();

    const quantity =
        $('#lube_car_care_quantity').val();


    const qty = parseFloat(quantity) || 0;

    const defaultPrice =
        parseFloat(productPrice) || 0;

    const overridePrice =
        parseFloat(manualPrice) || 0;


    /*
     * Use manual price if entered.
     * Otherwise use the product's default price.
     */
    const unitPrice =
        overridePrice > 0
            ? overridePrice
            : defaultPrice;


    const totalAmount =
        unitPrice * qty;


    $('#LubeCarCareTotalAmount').text(
        totalAmount.toLocaleString('en-PH',
        {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        })
    );
}


/*==================================================
SAVE LUBRICANTS & CAR CARE PRODUCT
==================================================*/

$('#CashierReportLubeCarCareForm').on('submit', function (event)
{
    event.preventDefault();

    const form = this;

    /* Clear previous errors */
    $('#lube_car_care_productError').text('');
    $('#lube_car_care_quantityError').text('');
    $('#lube_car_care_unit_priceError').text('');

    form.classList.add('was-validated');


    /*==================================================
    GET FORM VALUES
    ==================================================*/

    const CashiersReportId =
        {{ $CashiersReportId }};
		
    const teves_branch =
        {{ $CashiersReportData[0]['teves_branch'] }};		

    const productName =
        $('#lube_car_care_product').val();

    const productOption =
        $('#lubeCarCareProductList option[value="' + productName + '"]');

    const product_idx =
        productOption.attr('data-id');

    const order_quantity =
        $('#lube_car_care_quantity').val();

    const product_manual_price =
        $('#lube_car_care_unit_price').val();
	
	const lube_car_care_id =
        $('#lube_car_care_id').val();

    /*==================================================
    AJAX SAVE
    ==================================================*/

    $.ajax({
        url: "{{ route('SaveLubeAndCarCareProducts') }}",

        type: "POST",

        data:
        {
            lube_car_care_id: lube_car_care_id,
            CashiersReportId: CashiersReportId,
            product_idx: product_idx,
            branch_idx: teves_branch,
            order_quantity: order_quantity,
            product_manual_price: product_manual_price,
            _token: "{{ csrf_token() }}"
        },

        beforeSend: function ()
        {
            setButtonLoading(
                '#save-lube-car-care',
                true
            );
        },

        success: function (response)
        {
            console.log(response);

            if (response)
            {
                /* Close modal */
                $('#CashierReportLubeCarCareModal').modal('hide');


                /* Reset form */
                resetLubeCarCareForm();
				reloadLubeCarCareTable();

                /* Reload product DataTable */
                if ($.fn.DataTable.isDataTable(
                    '#table_product_data_lube_car_care'
                ))
                {
                    CashiersReportLubeCarCareTable
                        .ajax
                        .reload(null, false);
                }


                /* Update summary */
                if (typeof UpdateCashiersReportSummary === 'function')
                {
                    UpdateCashiersReportSummary();
                }

                if (typeof LoadCashiersReportSummary === 'function')
                {
                    LoadCashiersReportSummary();
                }


                /* Success message */
                if (typeof showSuccessModal === 'function')
                {
                    showSuccessModal(response.success);
                }
            }
        },

        error: function (xhr)
        {
            console.log(xhr);

            handleLubeCarCareValidation(xhr);
        },

        complete: function ()
        {
            setButtonLoading(
                '#save-lube-car-care',
                false
            );
        }
    });
});

/*==================================================
HANDLE LUBRICANTS & CAR CARE VALIDATION
==================================================*/

function handleLubeCarCareValidation(xhr)
{
    const errors = xhr.responseJSON?.errors || {};
    const fieldMap = {
        product_idx: '#lube_car_care_productError',
        order_quantity: '#lube_car_care_quantityError',
        product_manual_price: '#lube_car_care_unit_priceError'
    };
    let firstError = '';

    Object.keys(errors).forEach(function (field)
    {
        const message = Array.isArray(errors[field])
            ? errors[field][0]
            : errors[field];
        const selector = fieldMap[field] || `#${field}Error`;
        const $error = $(selector);

        if ($error.length)
        {
            $error.text(message).addClass('d-block');
        }

        if (!firstError)
        {
            firstError = message;
        }
    });

    if (!firstError)
    {
        firstError = xhr.responseJSON?.message ||
            'An unexpected error occurred while saving the product.';
    }

    if (typeof showValidationErrorModal === 'function')
    {
        showValidationErrorModal(firstError);
    }
    else if ($('#ValidationErrorModal').length)
    {
        $('#validation_error_message').text(firstError);
        $('#ValidationErrorModal').modal('show');
    }
    else
    {
        window.alert(firstError);
    }
}

/*==================================================
BUTTON LOADING STATE
==================================================*/

function setButtonLoading(button, loading, loadingText = 'Saving...')
{
    const $button = $(button);

    if (loading)
    {
        /* Store original button HTML */
        if (!$button.data('original-html'))
        {
            $button.data(
                'original-html',
                $button.html()
            );
        }

        $button
            .prop('disabled', true)
            .html(`
                <span class="spinner-border spinner-border-sm me-2"
                      role="status"
                      aria-hidden="true"></span>
                ${loadingText}
            `);
    }
    else
    {
        const originalHTML =
            $button.data('original-html');

        $button
            .prop('disabled', false)
            .html(
                originalHTML ||
                '<i class="bi bi-save-fill me-2"></i>Save Product'
            );

        $button.removeData('original-html');
    }
}

/*==================================================
EDIT LUBRICANTS & CAR CARE PRODUCT
==================================================*/

$('body').on('click', '#editCashiersReportLubeCarCare', function (event)
{
    event.preventDefault();

    const lube_car_care_id =
        $(this).data('id');


    $.ajax({
        url: "{{ route('cashiers_report_lube_car_care_info') }}",

        type: "POST",

        data:
        {
            lube_car_care_id: lube_car_care_id,
            _token: "{{ csrf_token() }}"
        },

        success: function (response)
        {
            console.log(response);

            if (response && response.length > 0)
            {
                const product = response[0];


                /*==================================================
                SET EDIT ID
                ==================================================*/

                $('#lube_car_care_id')
                    .val(lube_car_care_id);


                /*==================================================
                SET PRODUCT
                ==================================================*/

                $('#lube_car_care_product')
                    .val(product.product_name);


                /*==================================================
                SET QUANTITY
                ==================================================*/

                $('#lube_car_care_quantity')
                    .val(product.order_quantity);


                /*==================================================
                SET UNIT PRICE
                ==================================================*/

                $('#lube_car_care_unit_price')
                    .val(product.product_price);


                /*==================================================
                CALCULATE TOTAL
                ==================================================*/

                calculateLubeCarCareTotal();


                /*==================================================
                CHANGE MODAL TITLE
                ==================================================*/

                $('#CashierReportLubeCarCareModalLabel')
                    .text('Edit Lubricants & Car Care');


                /*==================================================
                CHANGE SAVE BUTTON
                ==================================================*/

                $('#save-lube-car-care').html(
                    '<i class="bi bi-check-circle-fill me-2"></i>Update Product'
                );

				/* Hide Reset button during editing */
                $('#clear-lube-car-care').hide();
				
				
                /*==================================================
                SHOW MODAL
                ==================================================*/

                $('#CashierReportLubeCarCareModal')
                    .modal('show');
            }
        },

        error: function (xhr)
        {
            console.log(xhr);

            if (typeof showValidationErrorModal === 'function')
            {
                showValidationErrorModal(
                    'Unable to load the selected product.'
                );
            }
        }
    });
});

/*==================================================
DELETE LUBRICANTS & CAR CARE PRODUCT
==================================================*/

$('body').on('click', '#deleteLubeCarCare', function (event)
{
    event.preventDefault();

    const lube_car_care_id =
        $(this).data('id');


    $.ajax({
        url: "{{ route('cashiers_report_lube_car_care_info') }}",

        type: "POST",

        dataType: "json",

        data:
        {
            lube_car_care_id: lube_car_care_id,
            _token: "{{ csrf_token() }}"
        },

        success: function (response)
        {
            console.log(response);

            if (!Array.isArray(response) ||
                response.length === 0)
            {
                showValidationErrorModal(
                    'Unable to load the selected product.'
                );

                return;
            }


            const product = response[0];


            /*==================================================
            STORE DELETE ID
            ==================================================*/

            $('#deleteLubeCarCareConfirmed')
                .val(lube_car_care_id);


            /*==================================================
            DISPLAY PRODUCT DETAILS
            ==================================================*/

            $('#delete_lube_car_care_product')
                .text(product.product_name || '-');

            $('#delete_lube_car_care_quantity')
                .text(product.order_quantity || '0');

            $('#delete_lube_car_care_unit_price')
                .text(
                    formatLubeCarCareAmount(
                        product.product_price
                    )
                );

            $('#deleteLubeCarCareTotalAmount')
                .text(
                    formatLubeCarCareAmount(
                        product.order_total_amount
                    )
                );

			
			reloadLubeCarCareTable();
				
            /*==================================================
            SHOW DELETE MODAL
            ==================================================*/

            $('#CashierReportLubeCarCareDeleteModal')
                .modal('show');
        },

        error: function (xhr)
        {
            console.log(xhr);

            showValidationErrorModal(
                'Unable to load the selected product.'
            );
        }
    });
});


/*==================================================
CONFIRM DELETE
==================================================*/

$('body').on(
    'click',
    '#deleteLubeCarCareConfirmed',
    function (event)
{
    event.preventDefault();

    const lube_car_care_id =
        $(this).val();

    if (!lube_car_care_id)
    {
        showValidationErrorModal(
            'Invalid product selected.'
        );

        return;
    }


    $.ajax({
        url: "{{ route('delete_cashiers_report_lube_car_care') }}",

        type: "POST",

        data:
        {
            lube_car_care_id: lube_car_care_id,
            _token: "{{ csrf_token() }}"
        },

        beforeSend: function ()
        {
            $('#deleteLubeCarCareConfirmed')
                .prop('disabled', true)
                .html(`
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    Deleting...
                `);
        },

        success: function (response)
        {
            /* Hide modal */
            $('#CashierReportLubeCarCareDeleteModal')
                .modal('hide');


            /* Reload DataTable */
            CashiersReportLubeCarCareTable
                .ajax
                .reload(null, false);


            /* Update summary */
            if (typeof UpdateCashiersReportSummary === 'function')
            {
                UpdateCashiersReportSummary();
            }

            if (typeof LoadCashiersReportSummary === 'function')
            {
                LoadCashiersReportSummary();
            }


            /* Success message */
            if (typeof showSuccessModal === 'function')
            {
                showSuccessModal(
                    response.success ||
                    'Product deleted successfully.'
                );
            }
        },

        error: function (xhr)
        {
            console.log(xhr);

            showValidationErrorModal(
                xhr.responseJSON?.message ||
                'Unable to delete the product.'
            );
        },

        complete: function ()
        {
            $('#deleteLubeCarCareConfirmed')
                .prop('disabled', false)
                .html(
                    '<i class="bi bi-trash3-fill me-2"></i>Delete Product'
                );
        }
    });
});

/*==================================================
RESET LUBRICANTS & CAR CARE FORM
==================================================*/

function resetLubeCarCareForm()
{
    const form =
        $('#CashierReportLubeCarCareForm')[0];

    if (form)
    {
        form.reset();
    }


    /*==================================================
    RESET RECORD ID
    0 = CREATE MODE
    ==================================================*/

    $('#lube_car_care_id').val(0);


    /*==================================================
    CLEAR VALIDATION ERRORS
    ==================================================*/

    $('#lube_car_care_productError').text('');
    $('#lube_car_care_quantityError').text('');
    $('#lube_car_care_unit_priceError').text('');


    /* Remove validation state */
    $('#CashierReportLubeCarCareForm')
        .removeClass('was-validated');


    /*==================================================
    RESET TOTAL
    ==================================================*/

    $('#LubeCarCareTotalAmount')
        .text('0.00');


    /*==================================================
    RESET MODAL TITLE
    ==================================================*/

    $('#CashierReportLubeCarCareModalLabel')
        .text('Add Lubricants & Car Care');


    /*==================================================
    RESET SAVE BUTTON
    ==================================================*/

    $('#save-lube-car-care')
        .prop('disabled', false)
        .html(
            '<i class="bi bi-save-fill me-2"></i>Save Product'
        );


    /*==================================================
    SHOW RESET BUTTON
    ==================================================*/

    $('#clear-lube-car-care').show();
}
</script>
