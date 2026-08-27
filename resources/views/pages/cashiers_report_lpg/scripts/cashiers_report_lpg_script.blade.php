<script type="text/javascript">
/*==================================================
CASHIER REPORT MODULE
==================================================*/

$(document).ready(function ()
{
    initializeCashierReportTable();
    bindCashierReportEvents();
});

let cashierReportSearchTimer;

$('#cashier_report_search')
    .on('keyup', function ()
    {
        clearTimeout(cashierReportSearchTimer);

        cashierReportSearchTimer =
            setTimeout(function ()
            {
                CashierReportTable.ajax.reload();
            }, 400);
    });
	
/*==================================================
DATATABLE
==================================================*/

let CashierReportTable;

function initializeCashierReportTable()
{
    CashierReportTable =
        $('#getCashierReport').DataTable({

            processing: true,
            serverSide: true,
            responsive: true,
            scrollY: '500px',
            scrollCollapse: true,
            stateSave: true,
            autoWidth: false,
			searching: false,
            /*==================================================
            HIDE DEFAULT DATATABLE CONTROLS
            ==================================================*/

            dom:
            'rt' +
            '<"d-flex justify-content-between align-items-center mt-3"ip>',

            /*==================================================
            AJAX
            ==================================================*/

            ajax:
            {
                url: "{{ route('getCashierReportLPG') }}",

                data: function (d)
                {

                    /* SEARCH */

                    d.cashier_report_search =
                        $('#cashier_report_search').val();


                    /* BRANCH */

                    d.filter_branch =
                        $('#filter_branch').val();


                    /* DATE FROM */

                    d.filter_date_from =
                        $('#filter_date_from').val();


                    /* DATE TO */

                    d.filter_date_to =
                        $('#filter_date_to').val();


                    /* SHIFT */

                    d.filter_shift =
                        $('#filter_shift').val();

                }
            },


            /*==================================================
            COLUMNS
            ==================================================*/

            columns:
            [

                /* VISIBLE CARD */

                {
                    data: null,
                    name: 'cashier_report',
                    orderable: false,
                    searchable: false,
                    className: 'cashier-report-column',

                    render: function (data, type, row)
                    {
                        return renderCashierReportCard(row);
                    }
                },


                /* HIDDEN SEARCHABLE DATA */

                {
                    data: 'report_date',
                    name: 'report_date',
                    visible: false,
                    searchable: true
                },

                {
                    data: 'branch_code',
                    name: 'branch_code',
                    visible: false,
                    searchable: true
                },

                {
                    data: 'user_real_name',
                    name: 'user_real_name',
                    visible: false,
                    searchable: true
                },

                {
                    data: 'cashiers_name',
                    name: 'cashiers_name',
                    visible: false,
                    searchable: true
                },

                {
                    data: 'forecourt_attendant',
                    name: 'forecourt_attendant',
                    visible: false,
                    searchable: true
                },

                {
                    data: 'shift',
                    name: 'shift',
                    visible: false,
                    searchable: true
                },

                {
                    data: 'created_at',
                    name: 'created_at',
                    visible: false,
                    searchable: true
                },

                {
                    data: 'updated_at',
                    name: 'updated_at',
                    visible: false,
                    searchable: true
                }

            ],


            /*==================================================
            DEFAULT ORDER
            ==================================================*/

            order:
            [
                [1, 'desc']
            ]

        });


    autoAdjustColumns(CashierReportTable);
	initializeCashierReportLength();
}

function bindCashierReportEvents()
{
    //initializeCashierReportButton();


    /*==================================================
    FILTER CHANGEhttp://localhost:8000/receivables
    ==================================================*/

    $('#filter_branch, #filter_shift')
        .on('change', function ()
        {
            CashierReportTable.ajax.reload();
        });


    /*==================================================
    DATE FILTER
    ==================================================*/

    $('#filter_date_from, #filter_date_to')
        .on('change', function ()
        {
            CashierReportTable.ajax.reload();
        });


    /*==================================================
    RESET FILTERS
    ==================================================*/

    $('#resetCashierReportFilters')
        .on('click', function ()
        {
            $('#filter_branch').val('');
            $('#filter_date_from').val('');
            $('#filter_date_to').val('');
            $('#filter_shift').val('');
            $('#cashier_report_search').val('');

            CashierReportTable.ajax.reload();
        });
}


function initializeCashierReportLength()
{
    const lengthControl = `
        <div class="cashier-report-length d-flex align-items-center gap-2">

            <label for="cashier_report_length"
                   class="small fw-semibold mb-0">

                <i class="bi bi-list-ol text-secondary me-1"></i>

                Rows:

            </label>

            <select id="cashier_report_length"
                    class="form-select form-select-sm"
                    style="width:75px;">

                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="40">40</option>
                <option value="50">50</option>
                <option value="100">100</option>

            </select>

        </div>
    `;

    $('#getCashierReport_wrapper .cashier-report-footer-left')
        .append(lengthControl);


    $('#cashier_report_length').on('change', function ()
    {
        CashierReportTable
            .page
            .len(parseInt($(this).val()))
            .draw();
    });
}

/*==================================================
CASHIER REPORT CARD
==================================================*/

function renderCashierReportCard(data)
{
    return `

        <div class="cashier-report-card">

            <!-- ================================= -->
            <!-- HEADER -->
            <!-- ================================= -->

            <div class="d-flex justify-content-between align-items-start">


                <!-- LEFT SIDE -->
                <div class="d-flex align-items-start">


                    <!-- ICON -->
                    <div class="cashier-report-icon me-3">

                        <i class="bi bi-receipt"></i>

                    </div>


                    <!-- REPORT INFORMATION -->
                    <div>


                        <!-- REPORT DATE -->
                        <div class="cashier-report-title">

                            <i class="bi bi-calendar3 text-primary me-1"></i>
							<strong>Report Date:</strong>
                            ${data.report_date ?? '-'}

                            

                        </div>

                        <!-- SHIFT -->
                        <div class="cashier-report-title">

                            <i class="bi bi-clock text-primary me-1"></i>
							<strong>Shift :</strong>
                            ${data.shift ?? '-'}

                            

                        </div>
						
                        <!-- BRANCH -->
                        <div class="cashier-report-sub">

                            <i class="bi bi-building text-primary me-1"></i>

                            <strong>Branch:</strong>

                            ${data.branch_code ?? '-'}

                        </div>


                        <!-- CASHIER -->
                        <div class="cashier-report-sub">

                            <i class="bi bi-person-badge text-primary me-1"></i>

                            <strong>Cashier:</strong>

                            ${data.cashiers_name ?? '-'}

                        </div>


                        <!-- FORECOURT ATTENDANT -->
                        <div class="cashier-report-sub">

                            <i class="bi bi-person-workspace text-primary me-1"></i>

                            <strong>Forecourt:</strong>

                            ${data.forecourt_attendant ?? '-'}

                        </div>


                        <?php if (
                            $data->user_type == "SUAdmin" ||
                            $data->user_type == "Admin" ||
                            $data->user_type == "Supervisor" ||
                            $data->user_type == "Accounting_Staff"
                        ) { ?>

                        <!-- CREATED BY -->
                        <div class="cashier-report-sub">

                            <i class="bi bi-person-check text-primary me-1"></i>

                            <strong>Created By:</strong>

                            ${data.user_real_name ?? '-'}

                        </div>

                        <?php } ?>


                    </div>

                </div>


                <!-- ACTION -->
                <div class="cashier-report-actions">

                    ${data.action ?? ''}

                </div>


            </div>


            <hr>


            <!-- ================================= -->
            <!-- FOOTER -->
            <!-- ================================= -->

            <div class="d-flex justify-content-between align-items-center">


                <div class="cashier-report-meta">

                    <i class="bi bi-plus-circle me-1"></i>

                    Created:

                    ${data.created_at_dt_format ?? '-'}

                </div>


                <div class="cashier-report-meta">

                    <i class="bi bi-pencil-square me-1"></i>

                    Updated:

                    ${data.updated_at_dt_format ?? '-'}

                </div>


            </div>

        </div>

    `;
}


/*==================================================
AUTO ADJUST TABLE
==================================================*/

function autoAdjustColumns(table)
{
    const container =
        table.table().container();

    const resizeObserver =
        new ResizeObserver(function ()
        {
            table.columns.adjust();

            if (table.responsive)
            {
                table.responsive.recalc();
            }
        });

    resizeObserver.observe(container);
}

/*==================================================
CREATE CASHIER REPORT MODAL
==================================================*/

function openCreateCashierReportModal()
{
    resetCashierReportForm();

    $('#clear-cashiers-report').show();

    $('#save-cashiers-report').html(
        '<i class="bi bi-save-fill me-2"></i>Save Cashier\'s Report'
    );

    $('#CashierReportLPGModal').modal('show');
}


/*==================================================
RESET MODAL ON CLOSE
==================================================*/

$('#CashierReportLPGModal').on('hidden.bs.modal', function ()
{
    resetCashierReportForm();

    $('#clear-cashiers-report').show();

    $('#save-cashiers-report').html(
        '<i class="bi bi-save-fill me-2"></i>Save Cashier\'s Report'
    );
});


/*==================================================
SAVE CASHIER REPORT
==================================================*/

function saveCashierReport(event)
{
    event.preventDefault();


    /*==================================================
    FORM
    ==================================================*/

    const form =
        $('#CashierReportformNew');


    form.addClass('was-validated');


    /*==================================================
    GET VALUES
    ==================================================*/

    const payload =
    {
        /*
        ==============================================
        BRANCH
        ==============================================
        */

        teves_branch:
            $('#teves_branch').val(),


        /*
        ==============================================
        CASHIER
        ==============================================
        */

        cashiers_name:
            $('#cashiers_name').val(),


        /*
        ==============================================
        EMPLOYEE ON DUTY
        ==============================================
        */

        forecourt_attendant:
            $('#forecourt_attendant').val(),


        /*
        ==============================================
        REPORT DATE
        ==============================================
        */

        report_date:
            $('#report_date').val(),


        /*
        ==============================================
        SHIFT
        ==============================================
        */

        shift:
            $('#shift').val(),


        /*
        ==============================================
        REMARKS
        ==============================================
        */

        cashier_report_remarks:
            $('#cashier_report_remarks').val(),


        /*
        ==============================================
        CSRF
        ==============================================
        */

        _token:
            "{{ csrf_token() }}"
    };


    /*==================================================
    AJAX
    ==================================================*/

    $.ajax({

        url:
            '/create_cashier_report_lpg_post',

        type:
            'POST',

        data:
            payload,


        /*================================================
        BEFORE SEND
        =================================================*/

        beforeSend: function()
        {
            setButtonLoading(
                '#save-cashiers-report',
                true
            );
        },


        /*================================================
        SUCCESS
        =================================================*/

        success: function(response)
        {
            console.log(response);


            /*
            ==============================================
            CLOSE MODAL
            ==============================================
            */

            $('#CashierReportLPGModal')
                .modal('hide');


            /*
            ==============================================
            RESET FORM
            ==============================================
            */

            resetCashierReportForm();


            /*
            ==============================================
            SUCCESS MESSAGE
            ==============================================
            */

            showSuccessModal(
                response.success
            );


            /*
            ==============================================
            GET REPORT ID
            ==============================================
            */

            const cashier_report_id =
                response.cashiers_report_id;


            /*
            ==============================================
            REDIRECT
            ==============================================
            */

            if(cashier_report_id)
            {
                setTimeout(function()
                {
                    const url =
                        "{{ URL::to('cashiers_report_form') }}";

                    window.location.href =
                        url + '/' + cashier_report_id;

                }, 500);
            }

        },


        /*================================================
        COMPLETE
        =================================================*/

        complete: function()
        {
            setButtonLoading(
                '#save-cashiers-report',
                false
            );
        },


        /*================================================
        ERROR
        =================================================*/

        error: function(xhr)
        {
            console.log(xhr);

            handleCashierReportValidation(xhr);
        }

    });
}


/*==================================================
FORM SUBMIT
==================================================*/

$('#CashierReportformNew').on(
    'submit',
    function(event)
    {
        saveCashierReport(event);
    }
);


/*==================================================
RESET CASHIER REPORT FORM
==================================================*/

function resetCashierReportForm()
{
    const form =
        $('#CashierReportformNew');


    if(form.length)
    {
        form[0].reset();
    }


    /*
    ==============================================
    CLEAR VALIDATION MESSAGES
    ==============================================
    */

    $('#teves_branchError').html('');

    $('#cashiers_nameError').html('');

    $('#forecourt_attendantError').html('');

    $('#report_dateError').html('');

    $('#shiftError').html('');

    $('#cashier_report_remarksError').html('');


    /*
    ==============================================
    REMOVE INVALID STATES
    ==============================================
    */

    $('#teves_branch')
        .removeClass('is-invalid');

    $('#cashiers_name')
        .removeClass('is-invalid');

    $('#forecourt_attendant')
        .removeClass('is-invalid');

    $('#report_date')
        .removeClass('is-invalid');

    $('#shift')
        .removeClass('is-invalid');

    $('#cashier_report_remarks')
        .removeClass('is-invalid');


    /*
    ==============================================
    REMOVE VALIDATION STATE
    ==============================================
    */

    form.removeClass(
        'was-validated'
    );


    /*
    ==============================================
    RESET REPORT DATE
    ==============================================
    */

    $('#report_date').val(
        new Date().toISOString().split('T')[0]
    );


    /*
    ==============================================
    RESET SHIFT
    ==============================================
    */

    $('#shift').val('1st Shift');
}


/*==================================================
RELOAD CASHIER REPORT TABLE
==================================================*/

function reloadCashierReportTable()
{
    if(
        typeof CashierReportTable !==
        'undefined'
    )
    {
        CashierReportTable
            .ajax
            .reload(null, false);
    }
}


/*==================================================
BUTTON LOADING
==================================================*/

function setButtonLoading(
    button,
    loading
)
{
    $(button)
        .prop(
            'disabled',
            loading
        );
}


/*==================================================
SUCCESS MODAL
==================================================*/

function showSuccessModal(message)
{
    $('#success_modal_message')
        .text(message);


    $('#SuccessModal')
        .modal('show');


    setTimeout(function()
    {
        $('#SuccessModal')
            .modal('hide');

    }, 1500);
}


/*==================================================
VALIDATION
==================================================*/

function handleCashierReportValidation(xhr)
{
    /*
    ==============================================
    GET ERRORS
    ==============================================
    */

    const errors =
        xhr.responseJSON &&
        xhr.responseJSON.errors
            ? xhr.responseJSON.errors
            : {};


    /*
    ==============================================
    DEFAULT MESSAGE
    ==============================================
    */

    let firstError =
        'Invalid input detected.';


    /*================================================
    BRANCH
    =================================================*/

    if(errors.teves_branch)
    {
        $('#teves_branchError')
            .html(
                errors.teves_branch[0]
            )
            .show();


        $('#teves_branch')
            .addClass('is-invalid');


        firstError =
            errors.teves_branch[0];
    }


    /*================================================
    CASHIER
    =================================================*/

    if(errors.cashiers_name)
    {
        $('#cashiers_nameError')
            .html(
                errors.cashiers_name[0]
            )
            .show();


        $('#cashiers_name')
            .addClass('is-invalid');


        if(
            firstError ===
            'Invalid input detected.'
        )
        {
            firstError =
                errors.cashiers_name[0];
        }
    }


    /*================================================
    EMPLOYEE ON DUTY
    =================================================*/

    if(errors.forecourt_attendant)
    {
        $('#forecourt_attendantError')
            .html(
                errors.forecourt_attendant[0]
            )
            .show();


        $('#forecourt_attendant')
            .addClass('is-invalid');


        if(
            firstError ===
            'Invalid input detected.'
        )
        {
            firstError =
                errors.forecourt_attendant[0];
        }
    }


    /*================================================
    REPORT DATE
    =================================================*/

    if(errors.report_date)
    {
        let message =
            errors.report_date[0];


        if(
            message ===
            'The report date has already been taken.'
        )
        {
            message =
                'Report has already been created for the selected date.';
        }


        $('#report_dateError')
            .html(message)
            .show();


        $('#report_date')
            .addClass('is-invalid');


        if(
            firstError ===
            'Invalid input detected.'
        )
        {
            firstError =
                message;
        }
    }


    /*================================================
    SHIFT
    =================================================*/

    if(errors.shift)
    {
        let message =
            errors.shift[0];


        if(
            message ===
            'The shift has already been taken.'
        )
        {
            message =
                'Report has already been created for the selected shift.';
        }


        $('#shiftError')
            .html(message)
            .show();


        $('#shift')
            .addClass('is-invalid');


        if(
            firstError ===
            'Invalid input detected.'
        )
        {
            firstError =
                message;
        }
    }


    /*================================================
    REMARKS
    =================================================*/

    if(errors.cashier_report_remarks)
    {
        $('#cashier_report_remarksError')
            .html(
                errors.cashier_report_remarks[0]
            )
            .show();


        $('#cashier_report_remarks')
            .addClass('is-invalid');


        if(
            firstError ===
            'Invalid input detected.'
        )
        {
            firstError =
                errors.cashier_report_remarks[0];
        }
    }


    /*================================================
    SHOW VALIDATION ERROR MODAL
    =================================================*/

    showValidationErrorModal(
        firstError
    );
}


/*==================================================
VALIDATION ERROR MODAL
==================================================*/

function showValidationErrorModal(message)
{
    $('#validation_error_message')
        .text(message);


    $('#ValidationErrorModal')
        .modal('show');
}


/*==================================================
RESET BUTTON
==================================================*/

$('#clear-cashiers-report').on(
    'click',
    function()
    {
        resetCashierReportForm();
    }
);


/*==================================================
CLEAN MODAL BACKDROP
==================================================*/

$(document).on(
    'hidden.bs.modal',
    '.modal',
    function()
    {
        $('.modal-backdrop').remove();

        $('body')
            .removeClass('modal-open');
    }
);

</script>