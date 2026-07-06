<script type="text/javascript">

/*==================================================
SalesAgent MODULE
==================================================*/

$(document).ready(function ()
{
    initializeSalesAgentTable();
    bindSalesAgentEvents();
});


/*==================================================
DATATABLE
==================================================*/

let SalesAgentTable;

function initializeSalesAgentTable()
{
    SalesAgentTable =
        $('#getsalesagentList').DataTable({

            processing: true,
            serverSide: true,
            responsive: true,
            scrollY: '500px',
            stateSave: true,
            ajax:
                "{{ route('getSalesAgentList') }}",
            columns: [
                {
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                { data: 'sales_agent_name' },
				{ data: 'sales_agent_address' },
                { data: 'sales_agent_contact_number' },
                { 	
					data: 'sales_agent_email_address',
                    className: 'text-left' 
				},
                { 	data: 'sales_agent_status',
                    className: 'text-center'  
					},
                {
                    data: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ],

            order: [[1, 'asc']]

        });

    autoAdjustColumns(SalesAgentTable);
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

function bindSalesAgentEvents()
{
    /*
    CREATE
    */
    $('body').on(
        'click',
        '#createSalesAgent',
        openCreateSalesAgentModal
    );

    /*
    EDIT
    */
    $('body').on(
        'click',
        '#editSalesAgentDetails',
        openEditSalesAgentModal
    );

    /*
    SAVE / UPDATE
    */
    $('#save-SalesAgent').on(
        'click',
        saveSalesAgent
    );

    /*
    DELETE MODAL
    */
    $('body').on(
        'click',
        '#deleteSalesAgentDetails',
        showDeleteSalesAgentModal
    );

    /*
    DELETE CONFIRM
    */
    $('body').on(
        'click',
        '#deleteSalesAgentConfirmed',
        deleteSalesAgentConfirmed
    );
}


/*==================================================
CREATE MODAL
==================================================*/

function openCreateSalesAgentModal()
{
    resetSalesAgentForm();
	$('#clear-SalesAgent').show();
    $('#sales_agent_id').val('');
    $('#SalesAgent_modal_title').text('Account Creation');
    $('#save-SalesAgent').html(`<i class="bi bi-save-fill me-2"></i>Save`);
    $('#CreateSalesAgentModal').modal('show');
}


/*==================================================
RESET MODAL ON CLOSE
==================================================*/

$('#CreateSalesAgentModal').on('hidden.bs.modal', function ()
{
    resetSalesAgentForm();
	$('#clear-SalesAgent').show();
    $('#SalesAgent_modal_title').text('Account Creation');
    $('#save-SalesAgent').html(`<i class="bi bi-save-fill me-2"></i>Save`);
	
});


/*==================================================
EDIT MODAL
==================================================*/

function openEditSalesAgentModal()
{
    const SalesAgentID = $(this).data('id');

    resetSalesAgentForm();
	$('#clear-SalesAgent').hide();
	
    $('#CreateSalesAgentModal').modal('show');

    $.ajax({
        url: '/sales_agent_info',
        type: 'POST',
        data: {
            SalesAgentID: SalesAgentID,
            _token:
                "{{ csrf_token() }}"
        },
        success: function(response)
        {
            console.log(response);
            const data = response.data ?? response;

            if(!data)
            {
                showDangerMessage('SalesAgent not found.');
				return;
            }

            /*
            LOAD VALUES
            */
            $('#sales_agent_id').val(SalesAgentID);
            $('#sales_agent_name').val(data.sales_agent_name);
            $('#sales_agent_contact_number').val(data.sales_agent_contact_number);
            $('#sales_agent_address').val(data.sales_agent_address);
            $('#sales_agent_email_address').val(data.sales_agent_email_address);

            /*
            UPDATE MODAL
            */
            $('#sales_agent_modal_title').text('Sales Agent Update');
            $('#save-SalesAgent').html(`<i class="bi bi-check-circle-fill me-2"></i>Update`);

        },

        error: function(xhr)
        {
            console.log(xhr);
            showDangerMessage('Unable to load SalesAgent details.');
        }

    });
}


/*==================================================
SAVE / UPDATE
==================================================*/

function saveSalesAgent(event)
{
    event.preventDefault();

    const form = $('#SalesAgentformNew');

    form.addClass('was-validated');

    const sales_agent_id = $('#sales_agent_id').val();

    const payload = {
        SalesAgentID: 				sales_agent_id,
        sales_agent_name: 			$('#sales_agent_name').val(),
        sales_agent_contact_number: $('#sales_agent_contact_number').val(),
        sales_agent_address: 		$('#sales_agent_address').val(),
        sales_agent_email_address:  $('#sales_agent_email_address').val(),
		sales_agent_status: 		$("#sales_agent_status").val(),
        _token: 					"{{ csrf_token() }}"
    };

    $.ajax({
        url:
            sales_agent_id
            ? '/update_sales_agent_post'
            : '/create_sales_agent_post',
        type: 'POST',
		data: payload,
        beforeSend: function()
        {
            setButtonLoading('#save-SalesAgent',true);
        },
        success: function(response)
        {
            console.log(response);
            $('#CreateSalesAgentModal').modal('hide');
            reloadSalesAgentTable();
            showSuccessModal(response.success);
            resetSalesAgentForm();
        },
        complete: function()
        {
            setButtonLoading('#save-SalesAgent',false);
        },
        error: function(xhr)
        {
            console.log(xhr);
            handleSalesAgentValidation(xhr);
			$('#action_error_message').text('Validation Error');
        }

    });
}


/*==================================================
DELETE MODAL
==================================================*/

function showDeleteSalesAgentModal(event)
{
    event.preventDefault();

    /*
    GET SalesAgent ID
    */
    const SalesAgentID = $(this).data('id');
    console.log('SalesAgent ID:', SalesAgentID);

    /*
    SET DELETE BUTTON VALUE
    */
    $('#deleteSalesAgentConfirmed').val(SalesAgentID);

    /*
    AJAX LOAD SalesAgent INFO
    */
    $.ajax({
        url: "/sales_agent_info",
        type: "POST",
        data: {
            SalesAgentID: SalesAgentID,
            _token: "{{ csrf_token() }}"
        },

        success: function(response)
        {
            console.log(response);

            /*
            RESPONSE DATA
            */
            const data =
                response.data ?? response;

            /*
            LOAD SalesAgent DETAILS
            */
            $('#confirm_delete_sales_agent_name')
                .text(data.sales_agent_name ?? '-');

            $('#confirm_delete_sales_agent_contact_number')
                .text(data.sales_agent_contact_number ?? '-');

            $('#confirm_delete_address')
                .text(data.sales_agent_address ?? '-');

            $('#confirm_delete_sales_agent_contact_number')
                .text(data.sales_agent_contact_number ?? '-');

            /*
            CREATE MODAL INSTANCE
            */
            const deleteModal =
                new bootstrap.Modal(
                    document.getElementById('SalesAgentDeleteModal')
                );

            /*
            SHOW MODAL
            */
            deleteModal.show();
        },

        error: function(xhr)
        {
            console.log(xhr);

            alert('Unable to load SalesAgent information.');
        }

    });
}



/*==================================================
DELETE CONFIRMED
==================================================*/

function deleteSalesAgentConfirmed()
{
    const SalesAgentID = $('#deleteSalesAgentConfirmed').val();

    $.ajax({

        url: '/delete_sales_agent_confirmed',
        type: 'POST',
        data: {
            SalesAgentID: SalesAgentID,
            _token: "{{ csrf_token() }}"
        },

        success: function(response)
        {
            console.log(response);

            $('#SalesAgentDeleteModal').modal('hide');

            reloadSalesAgentTable();

			$('#action_error_message').text('');
		
            showDangerMessage(
                'SalesAgent Deleted'
            );
        },

        error: function(xhr)
        {
            console.log(xhr);

            showDangerMessage(
                'Unable to delete SalesAgent.'
            );
        }

    });
}


/*==================================================
HELPERS
==================================================*/

function reloadSalesAgentTable()
{
    SalesAgentTable
        .ajax
        .reload(null, false);
}


function resetSalesAgentForm()
{
    $('#SalesAgentformNew')[0].reset();
    $('#sales_agent_id').val('');
    $('.invalid-feedback').html('');
    $('#SalesAgentformNew').removeClass('was-validated');
}


function setButtonLoading(button, loading)
{
    $(button).prop('disabled', loading);
}


function showDangerMessage(message)
{
    $('#validation_error_message').text(message);
    $('#ValidationErrorModal').modal('show');
}


function showSuccessModal(message)
{
    $('#success_modal_message').text(message);	
    $('#SuccessModal').modal('show');

    setTimeout(function ()
    {
        $('#SuccessModal').modal('hide');
    }, 1500);
}


/*==================================================
VALIDATION
==================================================*/

function handleSalesAgentValidation(xhr)
{
    /*
    GET ERRORS
    */
    const errors = xhr.responseJSON.errors;

    /*
    DEFAULT MESSAGE
    */
    let firstError = 'Invalid input detected.';

    /*
    SalesAgent NAME
    */
    if(errors.sales_agent_name)
    {
        $('#sales_agent_name_error') .html(errors.sales_agent_name[0]) .show();
        firstError = errors.sales_agent_name[0];
    }

    /*
    ADDRESS
    */
    if(errors.sales_agent_contact_number)
    {
        $('#sales_agent_contact_number_error').html(errors.sales_agent_contact_number[0]).show();
        firstError = errors.sales_agent_contact_number[0];
    }

    /*
    TIN
    */
    if(errors.sales_agent_address)
    {
        $('#address_error').html(errors.sales_agent_address[0]).show();
        firstError = errors.sales_agent_address[0];
    }

    /*
    SHOW MODAL
    */
    showValidationErrorModal(firstError);
}

function showValidationErrorModal(message)
{
    $('#validation_error_message').text(message);
    $('#ValidationErrorModal').modal('show');
}


$(document).on('hidden.bs.modal', '.modal', function ()
{
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open');
});
</script>