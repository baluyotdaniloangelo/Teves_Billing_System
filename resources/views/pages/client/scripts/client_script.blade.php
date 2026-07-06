<script type="text/javascript">

/*==================================================
CLIENT MODULE
==================================================*/

$(document).ready(function ()
{
    initializeClientTable();
    bindClientEvents();
});


/*==================================================
DATATABLE
==================================================*/

let ClientTable;

function initializeClientTable()
{
    ClientTable =
        $('#getclientList').DataTable({

            processing: true,
            serverSide: true,
            responsive: true,
            scrollY: '500px',
            stateSave: true,
            ajax:
                "{{ route('getClientList') }}",
            columns: [
                {
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                { data: 'client_name' },
                { data: 'client_account_number' },
                { data: 'client_address' },
                { data: 'client_tin' },
                { data: 'referred_by_name' },
                { data: 'client_contact_number' },
                { data: 'client_age' },
                { data: 'default_less_percentage' },
                { data: 'default_net_percentage' },
                { data: 'default_vat_percentage' },
                { data: 'default_withholding_tax_percentage' },
                { data: 'default_payment_terms' },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ],

            order: [[1, 'asc']]

        });

    autoAdjustColumns(ClientTable);
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

function bindClientEvents()
{
    /*
    CREATE
    */
    $('body').on(
        'click',
        '#createClient',
        openCreateClientModal
    );

    /*
    EDIT
    */
    $('body').on(
        'click',
        '#editClientDetails',
        openEditClientModal
    );

    /*
    SAVE / UPDATE
    */
    $('#save-client').on(
        'click',
        saveClient
    );

    /*
    DELETE MODAL
    */
    $('body').on(
        'click',
        '#deleteClientDetails',
        showDeleteClientModal
    );

    /*
    DELETE CONFIRM
    */
    $('body').on(
        'click',
        '#deleteClientConfirmed',
        deleteClientConfirmed
    );
}


/*==================================================
CREATE MODAL
==================================================*/

function openCreateClientModal()
{
    resetClientForm();
	$('#clear-client').show();
    $('#client_id').val('');
    $('#client_modal_title').text('Account Creation');
    $('#save-client').html(`<i class="bi bi-save-fill me-2"></i>Save`);
    $('#CreateClientModal').modal('show');
}


/*==================================================
RESET MODAL ON CLOSE
==================================================*/

$('#CreateClientModal').on('hidden.bs.modal', function ()
{
    resetClientForm();
	$('#clear-client').show();
    $('#client_modal_title').text('Account Creation');
    $('#save-client').html(`<i class="bi bi-save-fill me-2"></i>Save`);
	
});


/*==================================================
EDIT MODAL
==================================================*/

function openEditClientModal()
{
    const clientID = $(this).data('id');

    resetClientForm();
	$('#clear-client').hide();
	
    $('#CreateClientModal').modal('show');

    $.ajax({
        url: '/client_info',
        type: 'POST',
        data: {
            clientID: clientID,
            _token:
                "{{ csrf_token() }}"
        },
        success: function(response)
        {
            console.log(response);
            const data = response.data ?? response;

            if(!data)
            {
                showDangerMessage('Client not found.');
				return;
            }

            /*
            LOAD VALUES
            */
            $('#client_id').val(clientID);
            $('#client_name').val(data.client_name);
            $('#client_address').val(data.client_address);
            $('#client_tin').val(data.client_tin);
            $('#default_less_percentage').val(data.default_less_percentage);
            $('#default_net_percentage').val(data.default_net_percentage);
            $('#default_vat_percentage').val(data.default_vat_percentage);
            $('#default_withholding_tax_percentage').val(data.default_withholding_tax_percentage);
            $('#default_payment_terms').val(data.default_payment_terms);
			
			/*For SMS Notification*/
			$('#client_contact_number').val(data.client_contact_number);
			$('#client_age').val(data.client_age);
	
            /*
            REFERRAL
            */
            if(data.sales_agent_idx)
            {
                $('#sales_agent_id').val(data.sales_agent_name);
            }

            /*
            UPDATE MODAL
            */
            $('#client_modal_title').text('Account Update');
            $('#save-client').html(`<i class="bi bi-check-circle-fill me-2"></i>Update`);

        },

        error: function(xhr)
        {
            console.log(xhr);
            showDangerMessage('Unable to load client details.');
        }

    });
}


/*==================================================
SAVE / UPDATE
==================================================*/

function saveClient(event)
{
    event.preventDefault();

    const form = $('#ClientformNew');

    form.addClass('was-validated');

    const client_id = $('#client_id').val();

    const sales_agent_idx =
        $('#sales_agent_name option[value="' +
        $('#sales_agent_id').val() +
        '"]').attr('data-id');

    const payload = {
        clientID: client_id,
        client_name: $('#client_name').val(),
        client_address: $('#client_address').val(),
        client_tin: $('#client_tin').val(),
        default_less_percentage: $('#default_less_percentage').val(),
        default_net_percentage: $('#default_net_percentage').val(),
        default_vat_percentage: $('#default_vat_percentage').val(),
        default_withholding_tax_percentage: $('#default_withholding_tax_percentage').val(),
        default_payment_terms: $('#default_payment_terms').val(),
        sales_agent_idx: sales_agent_idx,
		client_contact_number: $('#client_contact_number').val(),
		client_age: $('#client_age').val(),
        _token: "{{ csrf_token() }}"
    };

    $.ajax({
        url:
            client_id
            ? '/update_client_post'
            : '/create_client_post',
        type: 'POST',
		data: payload,
        beforeSend: function()
        {
            setButtonLoading('#save-client',true);
        },
        success: function(response)
        {
            console.log(response);
            $('#CreateClientModal').modal('hide');
            reloadClientTable();
            showSuccessModal(response.success);
            resetClientForm();
        },

        complete: function()
        {
            setButtonLoading('#save-client',false);
        },

        error: function(xhr)
        {
            console.log(xhr);
            handleClientValidation(xhr);
			$('#action_error_message').text('Validation Error');
        }

    });
}


/*==================================================
DELETE MODAL
==================================================*/

function showDeleteClientModal(event)
{
    event.preventDefault();

    /*
    GET CLIENT ID
    */
    const clientID = $(this).data('id');
    console.log('CLIENT ID:', clientID);

    /*
    SET DELETE BUTTON VALUE
    */
    $('#deleteClientConfirmed').val(clientID);

    /*
    AJAX LOAD CLIENT INFO
    */
    $.ajax({
        url: "/client_info",
        type: "POST",
        data: {
            clientID: clientID,
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
            LOAD CLIENT DETAILS
            */
            $('#confirm_delete_client_name')
                .text(data.client_name ?? '-');

            $('#confirm_delete_client_account_number')
                .text(data.client_account_number ?? '-');

            $('#confirm_delete_client_tin')
                .text(data.client_tin ?? '-');

            $('#confirm_delete_client_address')
                .text(data.client_address ?? '-');

            /*
            CREATE MODAL INSTANCE
            */
            const deleteModal =
                new bootstrap.Modal(
                    document.getElementById('ClientDeleteModal')
                );

            /*
            SHOW MODAL
            */
            deleteModal.show();
        },

        error: function(xhr)
        {
            console.log(xhr);

            alert('Unable to load client information.');
        }

    });
}



/*==================================================
DELETE CONFIRMED
==================================================*/

function deleteClientConfirmed()
{
    const clientID = $('#deleteClientConfirmed').val();

    $.ajax({

        url: '/delete_client_confirmed',
        type: 'POST',
        data: {
            clientID: clientID,
            _token: "{{ csrf_token() }}"
        },

        success: function(response)
        {
            console.log(response);

            $('#ClientDeleteModal').modal('hide');

            reloadClientTable();

			$('#action_error_message').text('');
		
            showDangerMessage(
                'Client Deleted'
            );
        },

        error: function(xhr)
        {
            console.log(xhr);

            showDangerMessage(
                'Unable to delete client.'
            );
        }

    });
}


/*==================================================
HELPERS
==================================================*/

function reloadClientTable()
{
    ClientTable
        .ajax
        .reload(null, false);
}


function resetClientForm()
{
    $('#ClientformNew')[0].reset();
    $('#client_id').val('');
    $('.invalid-feedback').html('');
    $('#ClientformNew').removeClass('was-validated');
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

function handleClientValidation(xhr)
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
    CLIENT NAME
    */
    if(errors.client_name)
    {
        $('#client_name_error') .html(errors.client_name[0]) .show();
        firstError = errors.client_name[0];
    }

    /*
    ADDRESS
    */
    if(errors.client_address)
    {
        $('#client_address_error').html(errors.client_address[0]).show();
        firstError = errors.client_address[0];
    }

    /*
    TIN
    */
    if(errors.client_tin)
    {
        $('#client_tin_error').html(errors.client_tin[0]).show();
        firstError = errors.client_tin[0];
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