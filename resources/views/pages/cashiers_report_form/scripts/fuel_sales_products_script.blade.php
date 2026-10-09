<script type="text/javascript">	
/*==================================================
CASHIER'S REPORT - PH1
FUEL SALES
==================================================*/

$(document).ready(function ()
{
    /* Initialize Fuel Sales DataTable */
    initializeCashiersReportFuelSalesTable();

    /* Load only Fuel-category products for the Fuel Sales form */
    loadFuelSalesProductList();
});


/*==================================================
GLOBAL DATATABLE VARIABLE
==================================================*/

let CashiersReportFuelSalesTable;
let FuelSalesProductListLoaded = false;
let FuelSalesProductListRequest = null;

function normalizeFuelSalesDecimal(value)
{
    const rounded = Math.round(Number(value) * 100) / 100;
    return Math.abs(rounded) < 0.005 ? 0 : rounded;
}

function fuelSalesFixedNumberRenderer(prefix = '')
{
    return function (data, type) {
        if (type !== 'display' && type !== 'filter') {
            return data;
        }

        const value = Number(data);
        if (!Number.isFinite(value)) {
            return data ?? '';
        }

        const normalized = Math.abs(value) < 0.005 ? 0 : value;
        return prefix + normalized.toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    };
}


/*==================================================
LOAD FUEL SALES PRODUCT OPTIONS
==================================================*/

function loadFuelSalesProductList()
{
    if (FuelSalesProductListLoaded)
    {
        return $.Deferred().resolve().promise();
    }

    if (FuelSalesProductListRequest)
    {
        return FuelSalesProductListRequest;
    }

    const $productInput = $('#fuel_product');
    $productInput.prop('disabled', true);

    FuelSalesProductListRequest = $.ajax({
        url: "{{ route('GetFuelSalesProducts') }}",
        type: 'POST',
        dataType: 'json',
        data: {
            _token: "{{ csrf_token() }}"
        }
    }).done(function (products)
    {
        const $productList = $('#fuelProductList').empty();
        const hasProducts = Array.isArray(products) && products.length > 0;

        if (Array.isArray(products))
        {
            products.forEach(function (product)
            {
                const price = Number(product.product_price || 0);
                $('<option>')
                    .val(product.product_name)
                    .attr('label', '₱ ' + price.toLocaleString('en-PH', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }) + ' | ' + product.product_name)
                    .attr('data-id', product.product_id)
                    .attr('data-price', product.product_price)
                    .appendTo($productList);
            });
        }

        FuelSalesProductListLoaded = true;
        $productInput
            .prop('disabled', false)
            .attr('placeholder', hasProducts
                ? 'Select fuel product'
                : 'No Fuel products available');
    }).fail(function (xhr)
    {
        FuelSalesProductListRequest = null;
        $productInput
            .prop('disabled', true)
            .attr('placeholder', 'Unable to load Fuel products');

        const message = xhr.responseJSON?.message ||
            'Unable to load Fuel products.';

        if (typeof showFuelSalesValidationModal === 'function')
        {
            showFuelSalesValidationModal(message);
        }
        else if (typeof showValidationErrorModal === 'function')
        {
            showValidationErrorModal(message);
        }
    });

    return FuelSalesProductListRequest;
}


/*==================================================
INITIALIZE FUEL SALES DATATABLE
==================================================*/

function initializeCashiersReportFuelSalesTable()
{
    CashiersReportFuelSalesTable =
        $('#FuelSales').DataTable({

            /* Enable DataTables processing indicator */
            processing: true,

            /* Enable responsive table behavior */
            responsive: true,

            /* Enable pagination */
            paging: true,

            /* Disable DataTables built-in search */
            searching: false,

            /* Hide table information
               Example: Showing 1 to 10 of 10 entries */
            info: false,

            /* Do not save previous table state */
            stateSave: false,

            /* Allow columns to use their defined widths */
            autoWidth: false,


            /*==================================================
            AJAX CONFIGURATION
            ==================================================*/

            ajax:
            {
                /* Laravel route for Fuel Sales */
                url: "{{ route('GetCashiersReportFuelSales') }}",

                type: "POST",

                /* Data sent to Laravel */
                data: function (d)
                {
                    /* Current Cashier's Report ID */
                    d.CashiersReportId =
                        {{ $CashiersReportId }};

                    /* Laravel CSRF token */
                    d._token =
                        "{{ csrf_token() }}";
                },


                /*==================================================
                PROCESS AJAX RESPONSE
                ==================================================*/

                dataSrc: function (response)
                {
                    /*
                     * Yajra/DataTables response is expected
                     * to contain the records inside response.data.
                     *
                     * If no data is returned, use an empty array.
                     */
                    return response.data || [];
                }
            },


            /*==================================================
            DATATABLE COLUMNS
            ==================================================*/

            columns:
            [

                /*==================================================
                ROW NUMBER
                ==================================================*/

                {
                    data: null,

                    /* Row number should not be sortable */
                    orderable: false,

                    /* Row number should not be searchable */
                    searchable: false,

                    className: 'text-center',

                    render: function (
                        data,
                        type,
                        row,
                        meta
                    )
                    {
                        /*
                         * Display sequential row number.
                         *
                         * meta.row starts at 0,
                         * therefore +1 makes it start at 1.
                         */
                        return meta.row + 1;
                    }
                },


                /*==================================================
                PRODUCT
                ==================================================*/

                {
                    data: 'product_name',
                    className: 'text-start'
                },


                /*==================================================
                TANK
                ==================================================*/

                {
                    data: 'tank_name',
                    className: 'text-start'
                },


                /*==================================================
                PUMP
                ==================================================*/

                {
                    data: 'pump_name',
                    className: 'text-start'
                },


                /*==================================================
                BEGINNING READING
                ==================================================*/

                {
                    data: 'beginning_reading',

                    /* Reading is not intended for sorting */
                    orderable: false,

                    className: 'text-end',

                    /* Format number with 2 decimal places */
                    render:
                        $.fn.dataTable.render.number(
                            ',',
                            '.',
                            2,
                            ''
                        )
                },


                /*==================================================
                CLOSING READING
                ==================================================*/

                {
                    data: 'closing_reading',

                    orderable: false,

                    className: 'text-end',

                    /* Format number with 2 decimal places */
                    render:
                        $.fn.dataTable.render.number(
                            ',',
                            '.',
                            2,
                            ''
                        )
                },


                /*==================================================
                CALIBRATION
                ==================================================*/

                {
                    data: 'calibration',

                    orderable: false,

                    className: 'text-end',

                    /* Format number with 2 decimal places */
                    render:
                        $.fn.dataTable.render.number(
                            ',',
                            '.',
                            2,
                            ''
                        )
                },


                /*==================================================
                ORDER QUANTITY
                ==================================================*/

                {
                    data: 'order_quantity',

                    orderable: false,

                    className: 'text-end',

                    /* Normalize floating-point residue and always show 2 decimals */
                    render: fuelSalesFixedNumberRenderer()
                },


                /*==================================================
                PRODUCT PRICE
                ==================================================*/

                {
                    data: 'product_price',

                    orderable: false,

                    className: 'text-end',

                    /* Format price as Philippine Peso */
                    render:
                        $.fn.dataTable.render.number(
                            ',',
                            '.',
                            2,
                            '₱ '
                        )
                },


                /*==================================================
                TOTAL AMOUNT
                ==================================================*/

                {
                    data: 'order_total_amount',

                    orderable: false,

                    className: 'text-end fw-semibold',

                    /* Normalize floating-point residue and always show 2 decimals */
                    render: fuelSalesFixedNumberRenderer('₱ ')
                },


                /*==================================================
                ACTION
                ==================================================*/

                {
                    data: 'action',

                    /* Action buttons should not be sortable */
                    orderable: false,

                    /* Action buttons should not be searchable */
                    searchable: false,

                    className: 'text-center'
                },


                /*==================================================
                DATE CREATED
                ==================================================*/

                {
                    data: 'created_at',

                    render: function (data)
                    {
                        /*
                         * If there is no date,
                         * return an empty value.
                         */
                        if (!data)
                        {
                            return '';
                        }

                        const date =
                            new Date(data);

                        /*
                         * Format created date/time.
                         *
                         * Example:
                         * Sep 06, 2026, 10:30 PM
                         */
                        return date.toLocaleDateString(
                            'en-US',
                            {
                                year: 'numeric',
                                month: 'short',
                                day: '2-digit',
                                hour: '2-digit',
                                minute: '2-digit'
                            }
                        );
                    }
                },


                /*==================================================
                DATE UPDATED
                ==================================================*/

                {
                    data: 'updated_at',

                    render: function (data)
                    {
                        /*
                         * If there is no date,
                         * return an empty value.
                         */
                        if (!data)
                        {
                            return '';
                        }

                        const date =
                            new Date(data);

                        /*
                         * Format updated date/time.
                         */
                        return date.toLocaleDateString(
                            'en-US',
                            {
                                year: 'numeric',
                                month: 'short',
                                day: '2-digit',
                                hour: '2-digit',
                                minute: '2-digit'
                            }
                        );
                    }
                }
            ],


            /*==================================================
            DEFAULT SORTING
            ==================================================*/

            /*
             * Column 1 = Product
             *
             * Column 0 is the row number,
             * therefore sorting starts at column 1.
             */
            order:
            [
                [1, 'asc']
            ],


            /*==================================================
            UPDATE ROW NUMBERS AFTER SORTING AND PAGINATION
            ==================================================*/

            drawCallback: function ()
            {
                const api = this.api();
                const pageInfo = api.page.info();

                api.column(0, { page: 'current' })
                    .nodes()
                    .each(function (cell, index)
                    {
                        cell.textContent = pageInfo.start + index + 1;
                    });
            },


            /*==================================================
            DATATABLE MESSAGES
            ==================================================*/

            language:
            {
                /* Message displayed when there are no records */
                emptyTable:
                    'No fuel sales found.'
            }
        });


    /*==================================================
    AUTO ADJUST COLUMNS
    ==================================================*/

    autoAdjustColumns(
        CashiersReportFuelSalesTable
    );
}


/*==================================================
RELOAD FUEL SALES DATATABLE
==================================================*/

function reloadFuelSalesTable()
{
    /*
     * Check if the DataTable has already been initialized
     * before attempting to reload it.
     */
    if (
        typeof CashiersReportFuelSalesTable !== 'undefined' &&
        CashiersReportFuelSalesTable
    )
    {
        /*
         * Reload data from the server.
         *
         * false = keep the current DataTable page.
         */
        CashiersReportFuelSalesTable
            .ajax
            .reload(null, false);
    }
}

/*==================================================
CASHIER'S REPORT - PH1
FUEL SALES
CALCULATE TOTAL AMOUNT
==================================================*/

function calculateFuelSalesTotal()
{
    /*==================================================
    GET FUEL PRODUCT
    ==================================================*/

    const fuelProduct =
        $('#fuel_product').val();


    /*==================================================
    GET DEFAULT PRODUCT PRICE
    --------------------------------------------------
    The price is retrieved from the selected
    datalist option.
    ==================================================*/

    const productOption =
        $('#fuelProductList option[value="' + fuelProduct + '"]');

    const productPrice =
        parseFloat(productOption.attr('data-price')) || 0;


    /*==================================================
    GET PUMP PRICE / MANUAL PRICE
    ==================================================*/

    const manualPrice =
        parseFloat($('#fuel_pump_price').val()) || 0;


    /*==================================================
    GET METER READINGS
    ==================================================*/

    const beginningReading =
        parseFloat($('#fuel_beginning_reading').val()) || 0;

    const closingReading =
        parseFloat($('#fuel_closing_reading').val()) || 0;

    const calibration =
        parseFloat($('#fuel_calibration').val()) || 0;


    /*==================================================
    CALCULATE FUEL QUANTITY
    --------------------------------------------------
    Quantity =
        Closing Reading
        - Beginning Reading
        - Calibration
    ==================================================*/

    const orderQuantity = normalizeFuelSalesDecimal(
        (closingReading - beginningReading) - calibration
    );


    /*==================================================
    DETERMINE UNIT PRICE
    --------------------------------------------------
    If a manual/pump price is entered, use it.
    Otherwise use the product's default price.
    ==================================================*/

    const unitPrice =
        manualPrice > 0
            ? manualPrice
            : productPrice;


    /*==================================================
    CALCULATE TOTAL
    ==================================================*/

    const totalAmount = normalizeFuelSalesDecimal(
        orderQuantity > 0 ? unitPrice * orderQuantity : 0
    );


    /*==================================================
    DISPLAY TOTAL
    ==================================================*/

    $('#FuelSalesTotalAmount').text(
        totalAmount.toLocaleString(
            'en-PH',
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        )
    );
}

/*==================================================
CASHIER'S REPORT - PH1
FUEL SALES
METER READING CHANGE
==================================================*/

$(document).on(
    'change',
    '#fuel_beginning_reading, #fuel_closing_reading, #fuel_calibration, #fuel_pump_price',
    function ()
{
    /*==================================================
    RECALCULATE TOTAL
    ==================================================*/

    calculateFuelSalesTotal();
});

/*==================================================
CASHIER'S REPORT - PH1
FUEL SALES
SAVE FUEL SALES
==================================================*/

$('#CashierReportFuelSalesForm').on('submit', function (event)
{
    event.preventDefault();

    const form = this;

    /*==================================================
    CLEAR PREVIOUS VALIDATION MESSAGES
    ==================================================*/

    clearFuelSalesValidation();

    /*==================================================
    ENABLE BOOTSTRAP VALIDATION STATE
    ==================================================*/

    form.classList.add('was-validated');

    /*==================================================
    CASHIER'S REPORT INFORMATION
    ==================================================*/

    const CashiersReportId = {{ $CashiersReportId }};

    const teves_branch  =
       {{ $CashiersReportData[0]['teves_branch'] }};	

	const fuel_sales_id = 
	    $('#fuel_sales_id').val();
		
    /*==================================================
    GET FUEL PRODUCT ID
    --------------------------------------------------
    Frontend:
        #fuel_product

    Backend:
        product_idx
    ==================================================*/

    const fuelProduct =
        $('#fuel_product').val();

    const productOption =
        $('#fuelProductList option[value="' + fuelProduct + '"]');

    const product_idx =
        productOption.attr('data-id');


    /*==================================================
    GET TANK ID
    --------------------------------------------------
    Frontend:
        #fuel_tank

    Backend:
        tank_idx
    ==================================================*/

    const fuelTank =
        $('#fuel_tank').val();

    const tankOption =
        $('#fuelTankList option[value="' + fuelTank + '"]');

    const tank_idx =
        tankOption.attr('data-id');


    /*==================================================
    GET PUMP ID
    --------------------------------------------------
    Frontend:
        #fuel_pump

    Backend:
        pump_idx
    ==================================================*/

    const fuelPump =
        $('#fuel_pump').val();

    const pumpOption =
        $('#fuelPumpList option[value="' + fuelPump + '"]');

    const pump_idx =
        pumpOption.attr('data-id');


    /*==================================================
    GET METER READINGS
    ==================================================*/

    const beginning_reading =
        $('#fuel_beginning_reading').val();

    const closing_reading =
        $('#fuel_closing_reading').val();

    const calibration =
        $('#fuel_calibration').val();


    /*==================================================
    GET PUMP PRICE
    ==================================================*/

    const product_manual_price =
        $('#fuel_pump_price').val();


    /*==================================================
    FRONTEND VALIDATION
    ==================================================*/

    let hasValidationError = false;


    /*--------------------------------------------------
    PRODUCT
    --------------------------------------------------*/

    if (!product_idx)
    {
        $('#fuel_productError')
            .text('Please select a valid fuel product.')
            .addClass('d-block');

        hasValidationError = true;
    }


    /*--------------------------------------------------
    TANK
    --------------------------------------------------*/

    if (!tank_idx)
    {
        $('#fuel_tankError')
            .text('Please select a valid tank.')
            .addClass('d-block');

        hasValidationError = true;
    }


    /*--------------------------------------------------
    PUMP
    --------------------------------------------------*/

    if (!pump_idx)
    {
        $('#fuel_pumpError')
            .text('Please select a valid pump.')
            .addClass('d-block');

        hasValidationError = true;
    }


    /*--------------------------------------------------
    BEGINNING READING
    --------------------------------------------------*/

    if (beginning_reading === '')
    {
        $('#fuel_beginning_readingError')
            .text('Please enter beginning reading.')
            .addClass('d-block');

        hasValidationError = true;
    }


    /*--------------------------------------------------
    CLOSING READING
    --------------------------------------------------*/

    if (closing_reading === '')
    {
        $('#fuel_closing_readingError')
            .text('Please enter closing reading.')
            .addClass('d-block');

        hasValidationError = true;
    }


    /*--------------------------------------------------
    STOP SUBMISSION IF FRONTEND VALIDATION FAILED
    --------------------------------------------------*/

    if (hasValidationError)
    {
        const firstValidationError = $(
            '#fuel_productError, #fuel_tankError, #fuel_pumpError, ' +
            '#fuel_beginning_readingError, #fuel_closing_readingError'
        ).filter(function ()
        {
            return $(this).text().trim() !== '';
        }).first().text().trim();

        showFuelSalesValidationModal(
            firstValidationError || 'Please check the required Fuel Sales fields.'
        );

        return;
    }


    /*==================================================
    SAVE FUEL SALES
    ==================================================*/

    $.ajax({
        url: "{{ route('SaveFuelSales') }}",

        type: "POST",

        data:
        {
            /*
            IMPORTANT:
            Backend field names remain unchanged.
            */

            fuel_sales_id: fuel_sales_id,

            CashiersReportId:
                CashiersReportId,

            branch_idx:
                teves_branch,

            product_idx:
                product_idx,

            tank_idx:
                tank_idx,

            pump_idx:
                pump_idx,

            beginning_reading:
                beginning_reading,

            closing_reading:
                closing_reading,

            calibration:
                calibration,

            product_manual_price:
                product_manual_price,

            _token:
                "{{ csrf_token() }}"
        },


        /*==================================================
        BEFORE AJAX REQUEST
        ==================================================*/

        beforeSend: function ()
        {
            setButtonLoading(
                '#save-fuel-sales',
                true,
                'Saving...'
            );
        },


        /*==================================================
        SUCCESS
        ==================================================*/

        success: function (response)
        {
            console.log(response);


            /*----------------------------------------------
            CLOSE MODAL
            ----------------------------------------------*/

            $('#CashierReportFuelSalesModal')
                .modal('hide');


            /*----------------------------------------------
            RESET FORM
            ----------------------------------------------*/

            resetFuelSalesForm();


            /*----------------------------------------------
            RELOAD FUEL SALES DATATABLE
            ----------------------------------------------*/

            if (
                typeof reloadFuelSalesTable === 'function'
            )
            {
                reloadFuelSalesTable();
            }
            else if (
                typeof LoadCashiersReportPH1 === 'function'
            )
            {
                /*
                Backward compatibility with old function.
                */

                LoadCashiersReportPH1();
            }


            /*----------------------------------------------
            UPDATE CASHIER'S REPORT SUMMARY
            ----------------------------------------------*/

            if (
                typeof UpdateCashiersReportSummary === 'function'
            )
            {
                UpdateCashiersReportSummary();
            }


            if (
                typeof LoadCashiersReportSummary === 'function'
            )
            {
                LoadCashiersReportSummary();
            }


            /*----------------------------------------------
            SUCCESS MESSAGE
            ----------------------------------------------*/

            if (
                typeof showSuccessModal === 'function'
            )
            {
                showSuccessModal(
                    response.success ||
                    'Fuel sales added successfully.'
                );
            }
        },


        /*==================================================
        VALIDATION / AJAX ERROR
        ==================================================*/

        error: function (xhr)
        {
            console.log(xhr);

            handleFuelSalesValidation(xhr);
        },


        /*==================================================
        AJAX COMPLETE
        ==================================================*/

        complete: function ()
        {
            setButtonLoading(
                '#save-fuel-sales',
                false
            );
        }
    });
});


/*==================================================
CLEAR FUEL SALES VALIDATION
==================================================*/

function clearFuelSalesValidation()
{
    $('#fuel_productError')
        .text('')
        .removeClass('d-block');

    $('#fuel_tankError')
        .text('')
        .removeClass('d-block');

    $('#fuel_pumpError')
        .text('')
        .removeClass('d-block');

    $('#fuel_beginning_readingError')
        .text('')
        .removeClass('d-block');

    $('#fuel_closing_readingError')
        .text('')
        .removeClass('d-block');

    $('#fuel_calibrationError')
        .text('')
        .removeClass('d-block');

    $('#fuel_pump_priceError')
        .text('')
        .removeClass('d-block');
}


/*==================================================
HANDLE FUEL SALES VALIDATION
==================================================*/

function showFuelSalesValidationModal(message)
{
    const modalElement =
        document.getElementById('ValidationErrorModal');

    $('#validation_error_message').text(message);

    if (!modalElement)
    {
        window.alert(message);
        return;
    }

    if (window.bootstrap?.Modal)
    {
        const modal =
            window.bootstrap.Modal.getOrCreateInstance(modalElement);

        modal.show();

        window.setTimeout(function ()
        {
            modalElement.style.zIndex = '1070';

            const backdrops =
                document.querySelectorAll('.modal-backdrop');
            const backdrop = backdrops[backdrops.length - 1];

            if (backdrop)
            {
                backdrop.style.zIndex = '1065';
            }
        }, 100);

        return;
    }

    if (typeof showValidationErrorModal === 'function')
    {
        showValidationErrorModal(message);
        return;
    }

    window.alert(message);
}

function handleFuelSalesValidation(xhr)
{
    const errors =
        xhr.responseJSON?.errors || {};


    /*==================================================
    PRODUCT
    ==================================================*/

    if (errors.product_idx)
    {
        $('#fuel_productError')
            .text(
                Array.isArray(errors.product_idx)
                    ? errors.product_idx[0]
                    : errors.product_idx
            )
            .addClass('d-block');
    }


    /*==================================================
    TANK
    ==================================================*/

    if (errors.tank_idx)
    {
        $('#fuel_tankError')
            .text(
                Array.isArray(errors.tank_idx)
                    ? errors.tank_idx[0]
                    : errors.tank_idx
            )
            .addClass('d-block');
    }


    /*==================================================
    PUMP
    ==================================================*/

    if (errors.pump_idx)
    {
        $('#fuel_pumpError')
            .text(
                Array.isArray(errors.pump_idx)
                    ? errors.pump_idx[0]
                    : errors.pump_idx
            )
            .addClass('d-block');
    }


    /*==================================================
    BEGINNING READING
    ==================================================*/

    if (errors.beginning_reading)
    {
        let message =
            Array.isArray(errors.beginning_reading)
                ? errors.beginning_reading[0]
                : errors.beginning_reading;


        if (
            message ===
            'The beginning reading has already been taken.'
        )
        {
            message =
                'The Beginning Reading already exists for the selected product.';

            $('#fuel_beginning_reading').val('');
        }


        $('#fuel_beginning_readingError')
            .text(message)
            .addClass('d-block');
    }


    /*==================================================
    CLOSING READING
    ==================================================*/

    if (errors.closing_reading)
    {
        let message =
            Array.isArray(errors.closing_reading)
                ? errors.closing_reading[0]
                : errors.closing_reading;


        if (
            message ===
            'The closing reading has already been taken.'
        )
        {
            message =
                'The Closing Reading already exists for the selected product.';

            $('#fuel_closing_reading').val('');
        }


        $('#fuel_closing_readingError')
            .text(message)
            .addClass('d-block');
    }


    /*==================================================
    CALIBRATION
    ==================================================*/

    if (errors.calibration)
    {
        $('#fuel_calibrationError')
            .text(
                Array.isArray(errors.calibration)
                    ? errors.calibration[0]
                    : errors.calibration
            )
            .addClass('d-block');
    }


    /*==================================================
    PUMP PRICE
    ==================================================*/

    if (errors.product_manual_price)
    {
        $('#fuel_pump_priceError')
            .text(
                Array.isArray(errors.product_manual_price)
                    ? errors.product_manual_price[0]
                    : errors.product_manual_price
            )
            .addClass('d-block');
    }


    /*==================================================
    GENERAL ERROR
    ==================================================*/

    let firstError = '';

    Object.keys(errors).some(function (field)
    {
        const value = errors[field];
        firstError = Array.isArray(value) ? value[0] : value;
        return Boolean(firstError);
    });

    firstError = firstError || xhr.responseJSON?.message ||
        'Unable to save fuel sales.';

    showFuelSalesValidationModal(firstError);
}


/*==================================================
RESET FUEL SALES FORM
==================================================*/

function resetFuelSalesForm()
{
    const form =
        $('#CashierReportFuelSalesForm')[0];


    /*----------------------------------------------
    RESET FORM
    ----------------------------------------------*/

    if (form)
    {
        form.reset();
    }


    /*----------------------------------------------
    CLEAR VALIDATION
    ----------------------------------------------*/

    clearFuelSalesValidation();

    $('#CashierReportFuelSalesForm')
        .removeClass('was-validated');


    /*----------------------------------------------
    RESET TOTAL
    ----------------------------------------------*/

    $('#FuelSalesTotalAmount')
        .text('0.00');


    /*----------------------------------------------
    RESET MODAL TITLE
    ----------------------------------------------*/

    $('#CashierReportFuelSalesModalLabel')
        .text('Add Fuel Sales');


    /*----------------------------------------------
    RESET SAVE BUTTON
    ----------------------------------------------*/

    $('#save-fuel-sales')
        .prop('disabled', false)
        .html(
            '<i class="bi bi-save-fill me-2"></i>' +
            'Save Fuel Sales'
        );


    /*----------------------------------------------
    SHOW RESET BUTTON
    ----------------------------------------------*/

    $('#clear-fuel-sales')
        .show();
}

/*==================================================
CASHIER'S REPORT - PH1
FUEL SALES
EDIT FUEL SALES
==================================================*/

$('body').on('click', '#EditFuelSales', function (event)
{
    event.preventDefault();


    /*==================================================
    GET SELECTED FUEL SALES ID
    ==================================================*/

    const CHPH1_ID =
        $(this).data('id');


    /*==================================================
    VALIDATE ID
    ==================================================*/

    if (!CHPH1_ID)
    {
        if (typeof showValidationErrorModal === 'function')
        {
            showValidationErrorModal(
                'Invalid fuel sales record selected.'
            );
        }

        return;
    }


    /*==================================================
    LOAD FUEL SALES INFORMATION
    ==================================================*/

    $.ajax({
        url: "{{ route('FuelSalesInformation') }}",

        type: "POST",

        data:
        {
            CHPH1_ID:
                CHPH1_ID,

            _token:
                "{{ csrf_token() }}"
        },


        /*==================================================
        BEFORE REQUEST
        ==================================================*/

        beforeSend: function ()
        {
            /*
            Optional:
            You can show a loading indicator here
            if desired.
            */
        },


        /*==================================================
        SUCCESS
        ==================================================*/

        success: function (response)
        {
            console.log(
                'Fuel Sales Info:',
                response
            );


            /*==================================================
            VALIDATE RESPONSE
            ==================================================*/

            if (
                !Array.isArray(response) ||
                response.length === 0
            )
            {
                if (
                    typeof showValidationErrorModal ===
                    'function'
                )
                {
                    showValidationErrorModal(
                        'Unable to load the selected fuel sales record.'
                    );
                }

                return;
            }


            const fuelSales =
                response[0];


            /*==================================================
            SET HIDDEN RECORD ID
            ==================================================*/

            $('#fuel_sales_id')
                .val(CHPH1_ID);


            /*==================================================
            SET FUEL PRODUCT
            ==================================================*/

            $('#fuel_product')
                .val(
                    fuelSales.product_name || ''
                );


            /*==================================================
            SET TANK
            ==================================================*/

            $('#fuel_tank')
                .val(
                    fuelSales.tank_name || ''
                );


            /*==================================================
            SET PUMP
            ==================================================*/

            $('#fuel_pump')
                .val(
                    fuelSales.pump_name || ''
                );


            /*==================================================
            SET METER READINGS
            ==================================================*/

            $('#fuel_beginning_reading')
                .val(
                    fuelSales.beginning_reading ?? ''
                );


            $('#fuel_closing_reading')
                .val(
                    fuelSales.closing_reading ?? ''
                );


            $('#fuel_calibration')
                .val(
                    fuelSales.calibration ?? ''
                );


            /*==================================================
            SET PUMP PRICE
            ==================================================*/

            $('#fuel_pump_price')
                .val(
                    fuelSales.product_price ?? ''
                );


            /*==================================================
            RECALCULATE TOTAL
            ==================================================*/

            calculateFuelSalesTotal();


            /*==================================================
            CHANGE MODAL TO EDIT MODE
            ==================================================*/

            $('#CashierReportFuelSalesModalLabel')
                .text('Edit Fuel Sales');


            /*==================================================
            CHANGE SAVE BUTTON
            ==================================================*/

            $('#save-fuel-sales')
                .html(
                    '<i class="bi bi-check-circle-fill me-2"></i>' +
                    'Update Fuel Sales'
                );


            /*==================================================
            HIDE RESET BUTTON
            --------------------------------------------------
            Same behavior as the PH2 edit modal.
            ==================================================*/

            $('#clear-fuel-sales')
                .hide();


            /*==================================================
            SHOW MODAL
            ==================================================*/

            $('#CashierReportFuelSalesModal')
                .modal('show');


            /*==================================================
            LOAD AVAILABLE TANKS
            ==================================================*/

            LoadProductTank(
                'fuelsales_edit'
            );


            /*==================================================
            LOAD AVAILABLE PUMPS
            ==================================================*/

            LoadProductPump(
                'fuelsales_edit'
            );
        },


        /*==================================================
        ERROR
        ==================================================*/

        error: function (xhr)
        {
            console.log(
                'Unable to load fuel sales:',
                xhr
            );


            if (
                typeof showValidationErrorModal ===
                'function'
            )
            {
                showValidationErrorModal(
                    xhr.responseJSON?.message ||
                    'Unable to load the selected fuel sales record.'
                );
            }
        }
    });
});


/*==================================================
FUEL SALES DELETE - LOAD DETAILS
==================================================*/

$('body').on(
    'click',
    '#DeleteFuelSales',
    function (event)
{
    event.preventDefault();

    const CHPH1_ID =
        $(this).data('id');


    if (!CHPH1_ID)
    {
        showValidationErrorModal(
            'Invalid fuel sales selected.'
        );

        return;
    }


    $.ajax({

        url: "{{ route('FuelSalesInformation') }}",

        type: "POST",

        data:
        {
            CHPH1_ID:
                CHPH1_ID,

            _token:
                "{{ csrf_token() }}"
        },


        /*----------------------------------------------
        BEFORE REQUEST
        ----------------------------------------------*/

        beforeSend: function ()
        {
            // Optional loading state
        },


        /*----------------------------------------------
        SUCCESS
        ----------------------------------------------*/

        success: function (response)
        {
            console.log(response);


            if (
                !response ||
                !response.length
            )
            {
                showValidationErrorModal(
                    'Fuel sales record not found.'
                );

                return;
            }


            const data =
                response[0];


            /*------------------------------------------
            STORE ID
            ------------------------------------------*/

            $('#deleteFuelSalesConfirmed')
                .val(CHPH1_ID);


            /*------------------------------------------
            SET DELETE DETAILS
            ------------------------------------------*/

            $('#fuel_sales_delete_order_date')
                .text(
                    data.order_date || '-'
                );


            $('#fuel_sales_delete_product')
                .text(
                    data.product_name || '-'
                );


            $('#fuel_sales_delete_beginning_reading')
                .text(
                    data.beginning_reading || '0.00'
                );


            $('#fuel_sales_delete_closing_reading')
                .text(
                    data.closing_reading || '0.00'
                );


            $('#fuel_sales_delete_calibration')
                .text(
                    data.calibration || '0.00'
                );


            $('#fuel_sales_delete_quantity')
                .text(
                    data.order_quantity || '0.00'
                );


            $('#fuel_sales_delete_pump_price')
                .text(
                    parseFloat(data.product_price || 0)
                        .toLocaleString(
                            'en-PH',
                            {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }
                        )
                );


            $('#fuel_sales_delete_total_amount')
                .text(
                    parseFloat(data.order_total_amount || 0)
                        .toLocaleString(
                            'en-PH',
                            {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }
                        )
                );


            /*------------------------------------------
            SHOW DELETE MODAL
            ------------------------------------------*/

            $('#CashierReportFuelSalesDeleteModal')
                .modal('show');
        },


        /*----------------------------------------------
        ERROR
        ----------------------------------------------*/

        error: function (xhr)
        {
            console.log(xhr);

            showValidationErrorModal(
                xhr.responseJSON?.message ||
                'Unable to load fuel sales details.'
            );
        }
    });
});


/*==================================================
CONFIRM DELETE FUEL SALES
==================================================*/

$('body').on(
    'click',
    '#deleteFuelSalesConfirmed',
    function (event)
{
    event.preventDefault();

    const CHPH1_ID =
        $(this).val();


    if (!CHPH1_ID)
    {
        showValidationErrorModal(
            'Invalid fuel sales selected.'
        );

        return;
    }


    $.ajax({

        url: "{{ route('DeleteCashiersReportFuelSales') }}",

        type: "POST",

        data:
        {
            CHPH1_ID:
                CHPH1_ID,

            _token:
                "{{ csrf_token() }}"
        },


        /*----------------------------------------------
        BEFORE REQUEST
        ----------------------------------------------*/

        beforeSend: function ()
        {
            $('#deleteFuelSalesConfirmed')
                .prop('disabled', true)
                .html(`
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    Deleting...
                `);
        },


        /*----------------------------------------------
        SUCCESS
        ----------------------------------------------*/

        success: function (response)
        {
            /* Hide modal */

            $('#CashierReportFuelSalesDeleteModal')
                .modal('hide');


            /*------------------------------------------
            RELOAD FUEL SALES DATATABLE
            ------------------------------------------*/
			
            
			if (
                typeof reloadFuelSalesTable === 'function'
            )
            {
                reloadFuelSalesTable();
            }
            else if (
                typeof LoadCashiersReportPH1 === 'function'
            )
            {
                LoadCashiersReportPH1();
            }


            /*------------------------------------------
            UPDATE SUMMARY
            ------------------------------------------

            if (
                typeof UpdateCashiersReportSummary === 'function'
            )
            {
                UpdateCashiersReportSummary();
            }


            if (
                typeof LoadCashiersReportSummary === 'function'
            )
            {
                LoadCashiersReportSummary();
            }
*/

            /*------------------------------------------
            SUCCESS MESSAGE
            ------------------------------------------

            if (
                typeof showSuccessModal === 'function'
            )
            {
                showSuccessModal(
                    response.success ||
                    'Fuel sales deleted successfully.'
                );
            }*/
        },


        /*----------------------------------------------
        ERROR
        ----------------------------------------------*/

        error: function (xhr)
        {
            console.log(xhr);

            showValidationErrorModal(
                xhr.responseJSON?.message ||
                'Unable to delete the fuel sales.'
            );
        },


        /*----------------------------------------------
        COMPLETE
        ----------------------------------------------*/

        complete: function ()
        {
            $('#deleteFuelSalesConfirmed')
                .prop('disabled', false)
                .html(
                    '<i class="bi bi-trash3-fill me-2"></i>' +
                    'Delete Fuel Sales'
                );
        }

    });
});
</script>
