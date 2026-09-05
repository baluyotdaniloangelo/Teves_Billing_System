
<script type="text/javascript">
/*==================================================
VALIDATION
==================================================*/

function handleValidation(xhr)
{
    if (!xhr.responseJSON || !xhr.responseJSON.errors)
    {
        const message = 'An unexpected error occurred.';

        $('#validation_error_message').text(message);

        showValidationErrorModal(message);

        return;
    }

    const errors = xhr.responseJSON.errors;

    let firstError = '';

    /*
     * LOOP THROUGH LARAVEL ERRORS
     */
    Object.keys(errors).forEach(function(field)
    {
        const message = errors[field][0];

        const errorElement = $('#' + field + 'Error');

        if (errorElement.length)
        {
            errorElement
                .html(message)
                .addClass('invalid-feedback d-block');
        }

        if (!firstError)
        {
            firstError = message;
        }
    });

    $('#validation_error_message').text(firstError);

    showValidationErrorModal(firstError);
}


function showDangerMessage(message)
{
	
    $('#validation_error_message').text(message);
    $('#ValidationErrorModal').modal('show');

}

function showSuccessModal(message)
{
    $('#success_message').text(message);

    $('#SuccessModal').css('z-index', 1070);

    $('#SuccessModal').modal('show');

    setTimeout(function()
    {
        $('.modal-backdrop').last().css('z-index', 1065);
    }, 400);
}

function showValidationErrorModal(message)
{
    $('#validation_error_message').text(message);

    $('#ValidationErrorModal').css('z-index', 1070);

    $('#ValidationErrorModal').modal('show');

    setTimeout(function()
    {
        $('.modal-backdrop').last().css('z-index', 1065);
    }, 100);
	
}

</script>
