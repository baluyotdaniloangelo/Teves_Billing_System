
<script type="text/javascript">
$(document).ready(function () {

    /*==========================
      CHARACTER COUNTER
    ==========================*/

    $("#sms_content").on("input", function () {

        let length = $(this).val().length;

        $("#sms_count").text(length);

        let smsParts = Math.ceil(length / 160);

        if (smsParts < 1) smsParts = 1;

        $("#sms_parts").text(smsParts);

    });

    /*==========================
      SEND BUTTON
    ==========================*/

    $("#send_sms_btn").on("click", function (e) {

        e.preventDefault();

        resetSmsValidation();

        $("#SmsBlastForm").addClass("was-validated");

        if (!$("#SmsBlastForm")[0].checkValidity()) {
            return;
        }

        Swal.fire({

            title: "Send SMS Broadcast?",

            text: "This will send the SMS to all customers under the selected customer type.",

            icon: "question",

            showCancelButton: true,

            confirmButtonColor: "#198754",

            confirmButtonText: "Send SMS",

            cancelButtonText: "Cancel"

        }).then((result) => {

            if (result.isConfirmed) {

                sendSms();

            }

        });

    });

});


/*=====================================
SEND SMS
=====================================*/

function sendSms() {

    $.ajax({

        url: "/send_sms_blast",

        type: "POST",
    headers: {

        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

    },

        data: {

            customer_type: $("#customer_type").val(),

            sms_content: $("#sms_content").val()


        },

        beforeSend: function () {

            $("#send_sms_btn")

                .prop("disabled", true)

                .html('<span class="spinner-border spinner-border-sm me-2"></span>Sending...');

        },

        complete: function () {

            $("#send_sms_btn")

                .prop("disabled", false)

                .html('<i class="bi bi-send-fill me-2"></i> Send SMS Broadcast');

        },

        success: function (response) {

            Swal.fire({

                icon: "success",

                title: "Success",

                text: response.message

            });

            $("#SmsBlastForm")[0].reset();

            $("#SmsBlastForm").removeClass("was-validated");

            $("#sms_count").text("0");

            $("#sms_parts").text("1");

        },

        error: function (xhr) {

            if (xhr.status === 422) {

                let errors = xhr.responseJSON.errors;

                if (errors.customer_type) {

                    $("#customer_type_error").text(errors.customer_type[0]);

                }

                if (errors.sms_content) {

                    $("#sms_content_error").text(errors.sms_content[0]);

                }

                return;

            }

            Swal.fire({

                icon: "error",

                title: "Error",

                text: "Unable to send SMS."

            });

        }

    });

}


/*=====================================
RESET VALIDATION
=====================================*/

function resetSmsValidation() {

    $("#customer_type_error").text("");

    $("#sms_content_error").text("");

}
</script>
