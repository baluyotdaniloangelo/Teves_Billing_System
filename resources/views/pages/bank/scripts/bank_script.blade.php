<script type="text/javascript">

/*==================================================
BANK DETAILS MODULE
==================================================*/

$(document).ready(function ()
{
    initializeBankDetailsTable();

    bindBankDetailsEvents();
});


/*==================================================
DATATABLE
==================================================*/

let BankDetailsTable;

function initializeBankDetailsTable()
{
    BankDetailsTable =
        $('#getBankDetailsList').DataTable({

            processing: true,
            serverSide: true,
            responsive: true,
            scrollY: '500px',
            stateSave: true,

            ajax:
                "{{ route('getBankDetailsList') }}",

            columns: [

                {
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },

                { data: 'bank_name' },

                { data: 'bank_branch' },

                { data: 'bank_account_number' },

                {
                    data: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }

            ],

            order: [[1, 'asc']]

        });

    autoAdjustColumns(BankDetailsTable);
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
        });

    resizeObserver.observe(container);
}


/*==================================================
EVENT BINDINGS
==================================================*/

function bindBankDetailsEvents()
{
    /*
    CREATE
    */
    $('body').on(
        'click',
        '#createBankDetails',
        openCreateBankModal
    );

    /*
    EDIT
    */
    $('body').on(
        'click',
        '#editBankDetails',
        openEditBankModal
    );

    /*
    SAVE / UPDATE
    */
    $('#save-bank').on(
        'click',
        saveBankDetails
    );

    /*
    DELETE MODAL
    */
    $('body').on(
        'click',
        '#deleteBankDetails',
        showDeleteBankModal
    );

    /*
    DELETE CONFIRM
    */
    $('body').on(
        'click',
        '#deleteBankDetailsConfirmed',
        deleteBankDetailsConfirmed
    );
}


/*==================================================
CREATE MODAL
==================================================*/

function openCreateBankModal()
{
    resetBankForm();

    $('#bank_id').val('');

    $('#bank_modal_title')
        .text('Create Bank Details');
	
	$('#reset-bank-form').show();
	
    $('#save-bank')
        .html(`
            <i class="bi bi-save-fill me-2"></i>
            Save Bank Details
        `);

    $('#BankDetailsModal')
        .modal('show');
}

/*==================================================
RESET MODAL ON CLOSE
==================================================*/

$('#BankDetailsModal').on('hidden.bs.modal', function ()
{
    /*
    RESET FORM
    */
    resetBankForm();

    /*
    RESET TITLE
    */
    $('#bank_modal_title')
        .text('Create Bank Details');

    /*
    RESET BUTTON
    */
    $('#save-bank')
        .html(`
            <i class="bi bi-save-fill me-2"></i>
            Save Bank Details
        `);

    /*
    SHOW RESET BUTTON
    */
    $('#reset-bank-form')
        .show();
});

/*==================================================
EDIT MODAL
==================================================*/

function openEditBankModal()
{
    const bank_id =
        $(this).data('id');

    resetBankForm();
	
	$('#reset-bank-form').hide();
	
    $('#loading_data')
        .show();

    $('#BankDetailsModal')
        .modal('show');

    $.ajax({

        url: '/bank_info',

        type: 'POST',

        data: {

            bank_id: bank_id,

            _token:
                "{{ csrf_token() }}"
        },

        success: function(response)
        {
            console.log(response);

            const data =
                response.data ?? response;

            if(!data)
            {
                showDangerMessage(
                    'Bank details not found.'
                );

                return;
            }

            /*
            LOAD VALUES
            */
            $('#bank_id')
                .val(data.bank_id);

            $('#bank_name')
                .val(data.bank_name);

            $('#bank_branch')
                .val(data.bank_branch);

            $('#bank_account_number')
                .val(data.bank_account_number);

            /*
            UPDATE MODAL
            */
            $('#bank_modal_title')
                .text('Update Bank Details');

            $('#save-bank')
                .html(`
                    <i class="bi bi-check-circle-fill me-2"></i>
                    Update Bank Details
                `);
			
			
			
        },

        complete: function()
        {
            $('#loading_data')
                .hide();
        },

        error: function(xhr)
        {
            console.log(xhr);

            showDangerMessage(
                'Unable to load bank details.'
            );
        }

    });
}


/*==================================================
SAVE / UPDATE
==================================================*/

function saveBankDetails(event)
{
    event.preventDefault();

    const form =
        $('#BankDetailsForm');

    form.addClass('was-validated');

    const bank_id =
        $('#bank_id').val();

    const payload = {

        bank_id:
            bank_id,

        bank_name:
            $('#bank_name').val(),

        bank_branch:
            $('#bank_branch').val(),

        bank_account_number:
            $('#bank_account_number').val(),

        _token:
            "{{ csrf_token() }}"
    };

    $.ajax({

        url:
            bank_id
            ? '/update_bank_post'
            : '/create_bank_post',

        type: 'POST',

        data: payload,

        beforeSend: function()
        {
            setButtonLoading(
                '#save-bank',
                true
            );
        },

        success: function(response)
        {
            console.log(response);

            $('#BankDetailsModal')
                .modal('hide');

            reloadBankDetailsTable();

			showSuccessModal(response.success);
			
            resetBankForm();
			
			$('#bank_modal_title')
				.text('Create Bank Details');

			$('#save-bank')
				.html(`
					<i class="bi bi-save-fill me-2"></i>
					Save Bank Details
				`);
	
        },

        complete: function()
        {
            setButtonLoading(
                '#save-bank',
                false
            );
        },

        error: function(xhr)
        {
            console.log(xhr);

            handleBankValidation(xhr);
        }

    });
}

function showSuccessModal(message)
{
    /*
    SET MESSAGE
    */
    $('#success_modal_message')
        .text(message);

    /*
    SHOW MODAL
    */
    $('#SuccessModal')
        .modal('show');

    /*
    AUTO CLOSE
    */
    setTimeout(function ()
    {
        $('#SuccessModal')
            .modal('hide');

    }, 1500);
}

/*==================================================
SHOW DELETE MODAL
==================================================*/

function showDeleteBankModal(event)
{
    event.preventDefault();

    const bank_id =
        $(this).data('id');

    resetDeleteBankModal();

    $('#BankDetailsDeleteModal')
        .modal('show');

    $('#deleteBankDetailsConfirmed')
        .prop('disabled', true);

    $.ajax({

        url: '/bank_info',

        type: 'POST',

        data: {

            bank_id: bank_id,

            _token:
                "{{ csrf_token() }}"
        },

        success: function(response)
        {
            console.log(response);

            const data =
                response.data ?? response;

            if(!data)
            {
                showDangerMessage(
                    'Bank details not found.'
                );

                $('#BankDetailsDeleteModal')
                    .modal('hide');

                return;
            }

            /*
            SET BUTTON VALUE
            */
            $('#deleteBankDetailsConfirmed')
                .val(data.bank_id);

            /*
            LOAD DETAILS
            */
            $('#confirm_delete_bank_name')
                .text(data.bank_name || '-');

            $('#confirm_delete_bank_branch')
                .text(data.bank_branch || '-');

            $('#confirm_delete_bank_account_number')
                .text(data.bank_account_number || '-');

            /*
            ENABLE BUTTON
            */
            $('#deleteBankDetailsConfirmed')
                .prop('disabled', false);
        },

        error: function(xhr)
        {
            console.log(xhr);

            showDangerMessage(
                'Unable to load bank details.'
            );

            $('#BankDetailsDeleteModal')
                .modal('hide');
        }

    });
}


/*==================================================
DELETE CONFIRMED
==================================================*/

function deleteBankDetailsConfirmed()
{
    const bank_id =
        $('#deleteBankDetailsConfirmed')
            .val();

    $('#deleteBankDetailsConfirmed')
        .prop('disabled', true);

    $.ajax({

        url: '/delete_bank_confirmed',

        type: 'POST',

        data: {

            bank_id: bank_id,

            _token:
                "{{ csrf_token() }}"
        },

        success: function(response)
        {
            console.log(response);

            $('#BankDetailsDeleteModal')
                .modal('hide');

            reloadBankDetailsTable();

            showDangerMessage(
                'Bank Details Deleted'
            );
        },

        complete: function()
        {
            $('#deleteBankDetailsConfirmed')
                .prop('disabled', false);
        },

        error: function(xhr)
        {
            console.log(xhr);

            showDangerMessage(
                'Unable to delete bank details.'
            );
        }

    });
}


/*==================================================
HELPERS
==================================================*/

function reloadBankDetailsTable()
{
    BankDetailsTable
        .ajax
        .reload(null, false);
}


function resetBankForm()
{
    $('#BankDetailsForm')[0]
        .reset();

    $('#bank_id')
        .val('');

    $('.invalid-feedback')
        .html('');

    $('#BankDetailsForm')
        .removeClass('was-validated');
}


function resetDeleteBankModal()
{
    $('#confirm_delete_bank_name')
        .text('-');

    $('#confirm_delete_bank_branch')
        .text('-');

    $('#confirm_delete_bank_account_number')
        .text('-');

    $('#deleteBankDetailsConfirmed')
        .val('');
}


function setButtonLoading(button, loading)
{
    $(button).prop('disabled', loading);

    if (loading)
    {
        $('#loading_data')
            .show();
    }
    else
    {
        $('#loading_data')
            .hide();
    }
}

function showDangerMessage(message)
{
    $('#switch_notice_off').show();

    $('#sw_off').html(message);

    setTimeout(function ()
    {
        $('#switch_notice_off')
            .fadeOut('slow');

    }, 1000);
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

function handleBankValidation(xhr)
{
    const errors =
        xhr.responseJSON.errors;

    /*
    BANK NAME
    */
    if(errors.bank_name)
    {
        $('#bank_name_error')
            .html(errors.bank_name[0])
            .show();
    }

    /*
    BANK BRANCH
    */
    if(errors.bank_branch)
    {
        $('#bank_branch_error')
            .html(errors.bank_branch[0])
            .show();
    }

    /*
    ACCOUNT NUMBER
    */
    if(errors.bank_account_number)
    {
        $('#bank_account_number_error')
            .html(errors.bank_account_number[0])
            .show();

        /*
        SHOW MODAL ALERT
        */
        showValidationErrorModal(
            errors.bank_account_number[0]
        );
    }
}



</script>