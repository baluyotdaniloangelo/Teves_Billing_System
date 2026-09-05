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
    ClientTable = $('#getclientList').DataTable({

        processing: true,
        serverSide: true,
        responsive: true,
        stateSave: true,
        scrollY: "550px",
        pageLength: 10,

        ajax: "{{ route('getClientList') }}",

        columns: [

            {
                data: 'DT_RowIndex',
                searchable: false,
                orderable: false,
                className: 'text-center align-top'
            },

            {

                data: null,
                name: 'client_name',
render: function(data) {

    /*
    ==========================================
    OWNER FULL NAME
    ==========================================
    */

    const ownerName = [
        data.client_title,
        data.client_first_name,
        data.client_middle_name,
        data.client_last_name,
        data.client_name_extension
    ]
    .filter(value => value && value.trim() !== '')
    .join(' ');


    /*
    ==========================================
    BIRTHDAY
    ==========================================
    */

    let birthday = '-';

    if (data.client_birthday) {

        const date = new Date(data.client_birthday);

        birthday = date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }


    return `

        <div class="client-card">

            <!-- ================================= -->
            <!-- HEADER -->
            <!-- ================================= -->

            <div class="d-flex justify-content-between align-items-center">

                <div class="d-flex justify-content-between align-items-center">

                    <!-- AVATAR -->
                    <div class="me-4 flex-shrink-0">

                        <img src="/images/default-avatar.png"
                             class="client-avatar"
                             alt="Client Avatar">

                    </div>


                    <!-- CLIENT INFORMATION -->
                    <div class="flex-grow-1">

                        <!-- COMPANY NAME -->
                        <div class="client-name">

                            ${data.client_name}

                            <span class="badge bg-primary ms-2">

                                ${data.customer_type ?? 'N/A'}

                            </span>

                        </div>


                        <!-- OWNER -->
                        <div class="client-sub">

                            <i class="bi bi-person-badge text-primary me-1"></i>

                            <strong>Owner:</strong>

                            ${ownerName || '-'}

                        </div>


                        <!-- ACCOUNT NUMBER -->
                        <div class="client-sub">

                            <i class="bi bi-credit-card text-primary me-1"></i>

                            <strong>Account Number:</strong>

                            ${data.client_account_number}

                        </div>


                        <!-- TIN -->
                        <div class="client-sub">

                            <i class="bi bi-receipt text-primary me-1"></i>

                            <strong>TIN:</strong>

                            ${data.client_tin ?? '-'}

                        </div>


                        <!-- ADDRESS -->
                        <div class="client-sub">

                            <i class="bi bi-geo-alt text-primary me-1"></i>

                            <strong>Address:</strong>

                            ${data.client_address ?? '-'}

                        </div>

                    </div>


                    <!-- ACTION BUTTONS -->
                    <div class="client-actions">

                        ${data.action}

                    </div>

                </div>

            </div>


            <hr>


            <!-- ================================= -->
            <!-- CONTACT INFORMATION -->
            <!-- ================================= -->

            <div class="row">

                <!-- CONTACT NUMBER -->
                <div class="col-lg-6">

                    <div class="client-sub">

                        <small>

                            <i class="bi bi-telephone-fill text-primary me-1"></i>

                            <strong>Contact #:</strong>

                            ${data.client_contact_number || '-'}

                        </small>

                    </div>

                </div>


                <!-- EMAIL -->
                <div class="col-lg-6">

                    <div class="client-sub">

                        <small>

                            <i class="bi bi-envelope-fill text-primary me-1"></i>

                            <strong>Email:</strong>

                            ${data.client_email_address || '-'}

                        </small>

                    </div>

                </div>

            </div>


            <hr>


            <!-- ================================= -->
            <!-- OWNER / BIRTHDAY -->
            <!-- ================================= -->

            <div class="row">

                <div class="col-lg-6">

                    <div class="client-sub">

                        <i class="bi bi-calendar-heart text-info me-1"></i>

                        <strong>Birthday:</strong>

                        ${birthday}

                    </div>

                </div>

            </div>


            <!-- ================================= -->
            <!-- TAX SETTINGS -->
            <!-- ================================= -->

            <div class="mt-3">

                <span class="badge bg-danger">

                    Less: ${data.default_less_percentage ?? 0}%

                </span>

                <span class="badge bg-success">

                    Net: ${data.default_net_percentage ?? 0}

                </span>

                <span class="badge bg-info text-dark">

                    VAT: ${data.default_vat_percentage ?? 0}%

                </span>

                <span class="badge bg-warning text-dark">

                    WHT: ${data.default_withholding_tax_percentage ?? 0}%

                </span>

            </div>


            <!-- ================================= -->
            <!-- PAYMENT TERMS -->
            <!-- ================================= -->

            <div class="mt-3">

                <i class="bi bi-calendar-check me-1 text-secondary"></i>

                <strong>Payment Terms:</strong>

                ${data.default_payment_terms ?? 'Not Set'}

            </div>


            <hr>


            <!-- ================================= -->
            <!-- REFERRED BY -->
            <!-- ================================= -->

            <div class="mt-2">

                <i class="bi bi-person-check-fill me-1 text-secondary"></i>

                <strong>Referred By:</strong>

                ${data.referred_by_name ?? 'None'}

            </div>

        </div>

    `;

}

            }

        ],

        order:[[1,'asc']]

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
            _token: "{{ csrf_token() }}"
        },


        success: function(response)
        {
            console.log(response);

            const data = response.data ?? response;


            if (!data)
            {
                showDangerMessage('Client not found.');
                return;
            }


            /*
            ==========================================
            LOAD CLIENT ID
            ==========================================
            */

            $('#client_id').val(clientID);


            /*
            ==========================================
            ACCOUNT INFORMATION
            ==========================================
            */

            $('#customer_type').val(data.customer_type);

            $('#client_name').val(data.client_name);

            $('#client_contact_number')
                .val(data.client_contact_number);

            $('#client_email_address')
                .val(data.client_email_address);


            /*
            ==========================================
            ADDRESS
            ==========================================
            */

            $('#client_house_number')
                .val(data.client_house_number);

            $('#client_street')
                .val(data.client_street);

            $('#client_subdivision')
                .val(data.client_subdivision);

            $('#client_barangay')
                .val(data.client_barangay);

            $('#client_city')
                .val(data.client_city);

            $('#client_province')
                .val(data.client_province);

            $('#client_country')
                .val(data.client_country || 'Philippines');


            /*
            Generate Complete Address
            from the individual fields
            */

            generateCompleteAddress();


            /*
            ==========================================
            OWNER INFORMATION
            ==========================================
            */

            $('#client_title')
                .val(data.client_title);

            $('#client_gender')
                .val(data.client_gender);

            $('#client_first_name')
                .val(data.client_first_name);

            $('#client_middle_name')
                .val(data.client_middle_name);

            $('#client_last_name')
                .val(data.client_last_name);

            $('#client_name_extension')
                .val(data.client_name_extension);

            $('#client_birthday')
                .val(data.client_birthday);


            /*
            ==========================================
            TAX & PAYMENT SETTINGS
            ==========================================
            */

            $('#client_tin')
                .val(data.client_tin);

            $('#default_less_percentage')
                .val(data.default_less_percentage);

            $('#default_net_percentage')
                .val(data.default_net_percentage);

            $('#default_vat_percentage')
                .val(data.default_vat_percentage);

            $('#default_withholding_tax_percentage')
                .val(data.default_withholding_tax_percentage);

            $('#default_payment_terms')
                .val(data.default_payment_terms);


            /*
            ==========================================
            REFERRAL
            ==========================================
            */

            if (data.sales_agent_idx)
            {
                $('#sales_agent_id')
                    .val(data.sales_agent_name);
            }
            else
            {
                $('#sales_agent_id').val('');
            }


            /*
            ==========================================
            UPDATE MODAL
            ==========================================
            */

            $('#client_modal_title')
                .text('Account Update');

            $('#save-client').html(
                `<i class="bi bi-check-circle-fill me-2"></i>Update`
            );

        },


        error: function(xhr)
        {
            console.log(xhr);

            showDangerMessage(
                'Unable to load client details.'
            );
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

        /*
        ==========================================
        CLIENT
        ==========================================
        */

        clientID: client_id,


        /*
        ==========================================
        ACCOUNT INFORMATION
        ==========================================
        */

        client_name: $('#client_name').val(),
        customer_type: $('#customer_type').val(),

        client_contact_number:
            $('#client_contact_number').val(),

        client_email_address:
            $('#client_email_address').val(),


        /*
        ==========================================
        ADDRESS
        ==========================================
        */

        client_house_number:
            $('#client_house_number').val(),

        client_street:
            $('#client_street').val(),

        client_subdivision:
            $('#client_subdivision').val(),

        client_barangay:
            $('#client_barangay').val(),

        client_city:
            $('#client_city').val(),

        client_province:
            $('#client_province').val(),

        client_country:
            $('#client_country').val(),

        // Generated complete address
        client_address:
            $('#client_address').val(),


        /*
        ==========================================
        OWNER INFORMATION
        ==========================================
        */

        client_gender:
            $('#client_gender').val(),

        client_title:
            $('#client_title').val(),

        client_first_name:
            $('#client_first_name').val(),

        client_middle_name:
            $('#client_middle_name').val(),

        client_last_name:
            $('#client_last_name').val(),

        client_name_extension:
            $('#client_name_extension').val(),

        client_birthday:
            $('#client_birthday').val(),


        /*
        ==========================================
        REFERRAL
        ==========================================
        */

        sales_agent_idx: sales_agent_idx,


        /*
        ==========================================
        TAX & PAYMENT SETTINGS
        ==========================================
        */

        client_tin:
            $('#client_tin').val(),

        default_less_percentage:
            $('#default_less_percentage').val(),

        default_net_percentage:
            $('#default_net_percentage').val(),

        default_vat_percentage:
            $('#default_vat_percentage').val(),

        default_withholding_tax_percentage:
            $('#default_withholding_tax_percentage').val(),

        default_payment_terms:
            $('#default_payment_terms').val(),


        /*
        ==========================================
        CSRF
        ==========================================
        */

        _token: "{{ csrf_token() }}"
    };


    $.ajax({
        url: client_id
            ? '/update_client_post'
            : '/create_client_post',

        type: 'POST',

        data: payload,

        beforeSend: function()
        {
            setButtonLoading('#save-client', true);
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
            setButtonLoading('#save-client', false);
        },

        error: function(xhr)
        {
            console.log(xhr);

            handleClientValidation(xhr);

            $('#action_error_message').text(
                'Validation Error'
            );
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
    const errors = xhr.responseJSON?.errors || {};


    /*
    DEFAULT MESSAGE
    */
    let firstError = 'Invalid input detected.';


    /*
    ==========================================
    HELPER
    ==========================================
    */

    function showFieldError(field, error)
    {
        if (errors[field])
        {
            $('#' + field + '_error')
                .html(errors[field][0])
                .show();

            if (firstError === 'Invalid input detected.')
            {
                firstError = errors[field][0];
            }
        }
    }


    /*
    ==========================================
    ACCOUNT INFORMATION
    ==========================================
    */

    // Customer Type
    showFieldError(
        'customer_type',
        errors.customer_type
    );


    // Client Name
    showFieldError(
        'client_name',
        errors.client_name
    );


    // Contact Number
    showFieldError(
        'client_contact_number',
        errors.client_contact_number
    );


    // Email Address
    showFieldError(
        'client_email_address',
        errors.client_email_address
    );


    /*
    ==========================================
    ADDRESS
    ==========================================
    */

    // Complete Address
    showFieldError(
        'client_address',
        errors.client_address
    );


    // House Number - Optional
    showFieldError(
        'client_house_number',
        errors.client_house_number
    );


    // Street - Optional
    showFieldError(
        'client_street',
        errors.client_street
    );


    // Subdivision - Optional
    showFieldError(
        'client_subdivision',
        errors.client_subdivision
    );


    // Barangay - Required
    showFieldError(
        'client_barangay',
        errors.client_barangay
    );


    // City / Municipality - Required
    showFieldError(
        'client_city',
        errors.client_city
    );


    // Province - Required
    showFieldError(
        'client_province',
        errors.client_province
    );


    // Country - Required
    showFieldError(
        'client_country',
        errors.client_country
    );


    /*
    ==========================================
    OWNER INFORMATION
    ==========================================
    */

    // Title
    showFieldError(
        'client_title',
        errors.client_title
    );


    // Gender
    showFieldError(
        'client_gender',
        errors.client_gender
    );


    // First Name
    showFieldError(
        'client_first_name',
        errors.client_first_name
    );


    // Middle Name - Optional
    showFieldError(
        'client_middle_name',
        errors.client_middle_name
    );


    // Last Name
    showFieldError(
        'client_last_name',
        errors.client_last_name
    );


    // Name Extension - Optional
    showFieldError(
        'client_name_extension',
        errors.client_name_extension
    );


    // Birthday
    showFieldError(
        'client_birthday',
        errors.client_birthday
    );


    /*
    ==========================================
    TAX & PAYMENT SETTINGS
    ==========================================
    */

    showFieldError(
        'client_tin',
        errors.client_tin
    );

    showFieldError(
        'default_less_percentage',
        errors.default_less_percentage
    );

    showFieldError(
        'default_net_percentage',
        errors.default_net_percentage
    );

    showFieldError(
        'default_vat_percentage',
        errors.default_vat_percentage
    );

    showFieldError(
        'default_withholding_tax_percentage',
        errors.default_withholding_tax_percentage
    );

    showFieldError(
        'default_payment_terms',
        errors.default_payment_terms
    );


    /*
    ==========================================
    REFERRAL
    ==========================================
    */

    showFieldError(
        'sales_agent_name',
        errors.sales_agent_idx
    );


    /*
    ==========================================
    SHOW VALIDATION MODAL
    ==========================================
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



// ============================================================
// GENERATE COMPLETE ADDRESS
// ============================================================
function generateCompleteAddress() {

    const houseNumber = $('#client_house_number').val().trim();
    const street      = $('#client_street').val().trim();
    const subdivision = $('#client_subdivision').val().trim();
    const barangay    = $('#client_barangay').val().trim();
    const city        = $('#client_city').val().trim();
    const province    = $('#client_province').val().trim();
    const country     = $('#client_country').val().trim();

    let addressParts = [];

    if (houseNumber) {
        addressParts.push(houseNumber);
    }

    if (street) {
        addressParts.push(street);
    }

    if (subdivision) {
        addressParts.push(subdivision);
    }

    if (barangay) {
        addressParts.push('' + barangay);
    }

    if (city) {
        addressParts.push(city);
    }

    if (province) {
        addressParts.push(province);
    }

    if (country) {
        addressParts.push(country);
    }

    $('#client_address').val(addressParts.join(', '));
}


// ============================================================
// UPDATE ADDRESS WHILE TYPING
// ============================================================
$(document).on(
    'input change',
    '#client_house_number, #client_street, #client_subdivision, #client_barangay, #client_city, #client_province, #client_country',
    function () {

        generateCompleteAddress();

    }
);
</script>