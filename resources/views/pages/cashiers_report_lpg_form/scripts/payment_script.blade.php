<script type="text/javascript">	
	 
	function ResetPaymentForm(){
		/*Reset Form*/
		document.getElementById("AddPayment").reset();
		/*Hide Image Reference Div*/
		$("#image_payment_div").hide();
		/*Reset Payment Id*/
		document.getElementById("purchase_order_payment_details_id").value = 0;
	}
	
	initializePurchaseOrderPaymentTable();
	function initializePurchaseOrderPaymentTable(){

		PaymentTable = $('#PurchaseOrderPaymentTable').DataTable({

			processing: true,
			serverSide: true,
			responsive: true,
			destroy: true,

			ajax:{
				url:"{{ route('get_purchase_order_payment_list') }}",
				type:"POST",
				data:function(d){

					d.purchase_order_id = {{ $PurchaseOrderID }};
					d._token = "{{ csrf_token() }}";

				}
			},

			columns:[

				{
					data:'DT_RowIndex',
					searchable:false,
					orderable:false,
					className:'text-center'
				},

				{
					data:'purchase_order_bank',
					title:'Bank'
				},

				{
					data:'purchase_order_date_of_payment',
					title:'Date'
				},

				{
					data:'purchase_order_reference_no',
					title:'Reference No.'
				},

				{
					data:'purchase_order_payment_amount',
					className:'text-end',
					title:'Amount'
				},

				

				{
					data:'action',
					searchable:false,
					orderable:false,
					className:'text-center',
					title:'Action'
				}

			],

			order:[[2,'asc']]

		});

	}	
 

/*==================================================
SAVE / UPDATE PAYMENT
==================================================*/

$('#AddPayment').on('submit', savePayment);

function savePayment(event)
{
    event.preventDefault();

    const form = $('#AddPayment');

    resetPaymentValidation();

    form.addClass('was-validated');

    $.ajax({

        url: form.attr('action'),
        type: form.attr('method'),
        data: new FormData(form[0]),
        processData: false,
        contentType: false,
        dataType: 'json',

        beforeSend: function ()
        {
            setButtonLoading('#save-payment', true);
        },

        success: function (response)
        {
            console.log(response);

            $('#switch_notice_on').show();
            $('#sw_on').html(response.success);

            setTimeout(function ()
            {
                $('#switch_notice_on').fadeOut('fast');
            }, 1500);

            if ($('#purchase_order_payment_details_id').val() != 0)
            {
                $('#AddPaymentModal').modal('hide');
            }

            resetPaymentForm();

            //LoadPayment();

            LoadProduct();
        },

        complete: function ()
        {
            setButtonLoading('#save-payment', false);
        },

        error: function (xhr)
        {
            console.log(xhr);

            handlePaymentValidation(xhr);
        }

    });

}


/*==================================================
VALIDATION
==================================================*/

function handlePaymentValidation(xhr)
{
    if (!xhr.responseJSON || !xhr.responseJSON.errors)
    {
        $('#validation_error_message')
            .text('An unexpected error occurred.');

        $('#ValidationErrorModal').modal('show');

        return;
    }
	
	

    const errors = xhr.responseJSON.errors;

    let firstError = '';

    /*
    LOOP THROUGH LARAVEL ERRORS
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

    $('#validation_error_message')
        .text(firstError);

    $('#ValidationErrorModal')
        .modal('show');
}


/*==================================================
RESET VALIDATION
==================================================*/

function resetPaymentValidation()
{
    $('[id$="Error"]')
        .html('')
        .removeClass('d-block');
}


/*==================================================
RESET FORM
==================================================*/

function resetPaymentForm()
{
    $('#AddPayment')[0].reset();

    $('#purchase_order_payment_details_id').val(0);

    $('#payment_preview')
        .attr('src', '')
        .hide();

    $('#image_payment_div').empty();

    resetPaymentValidation();

    $('#AddPayment')
        .removeClass('was-validated');
}


/*==================================================
IMAGE PREVIEW
==================================================*/

$('#payment_image_reference').on('change', function ()
{
    const file = this.files[0];

    if (!file)
    {
        $('#payment_preview')
            .hide()
            .attr('src', '');

        $('#image_payment_div').empty();

        return;
    }

    const extension =
        file.name.split('.').pop().toLowerCase();

    if (!['jpg', 'jpeg', 'png'].includes(extension))
    {
        $('#payment_preview')
            .hide()
            .attr('src', '');

        $('#image_payment_div').empty();

        return;
    }

    const reader = new FileReader();

    reader.onload = function (e)
    {
        $('#payment_preview')
            .attr('src', e.target.result)
            .show();

        $('#image_payment_div').empty();
    };

    reader.readAsDataURL(file);
});


/*==================================================
BUTTON LOADING
==================================================*/

function setButtonLoading(button, loading)
{
    const $button = $(button);

    if (loading)
    {
        $button
            .prop('disabled', true)
            .data('original-html', $button.html())
            .html(`
                <span class="spinner-border spinner-border-sm me-2"></span>
                Saving...
            `);
    }
    else
    {
        $button
            .prop('disabled', false)
            .html($button.data('original-html'));
    }
}

/*==================================================
VALIDATION ERROR MODAL
==================================================*/

function showValidationErrorModal(message)
{
    $('#validation_error_message').text(message);

    const modal = new bootstrap.Modal(
        document.getElementById('ValidationErrorModal')
    );

    modal.show();
}

	
	<!--Select For Update-->
	$('body').on('click','#PurchaseOrderPayment_Edit',function(){
			
			event.preventDefault();
			let purchase_order_payment_details_id = $(this).data('id');
			  $.ajax({
				url: "{{ route('PaymentInfo') }}",
				type:"POST",
				data:{
				  purchase_order_payment_details_id:purchase_order_payment_details_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("purchase_order_id_payment").value = response[0].purchase_order_idx;
					document.getElementById("purchase_order_payment_details_id").value = response[0].purchase_order_payment_details_id;
					
					/*Set Details*/
					document.getElementById("purchase_order_bank").value = response[0].purchase_order_bank;
					document.getElementById("purchase_order_date_of_payment").value = response[0].purchase_order_date_of_payment;
					document.getElementById("purchase_order_reference_no").value = response[0].purchase_order_reference_no;
					document.getElementById("purchase_order_payment_amount").value = response[0].purchase_order_payment_amount;
					
					/*Display Image*/
					if(response[0].image_reference != null){
						
						var img_holder = $('.img-holder');
						img_holder.empty();
						image_src = "data:image/jpg;image/png;base64,"+response[0].image_reference;
						
						$('<img/>',{'src':image_src,'class':'img-fluid','style':'max-width:400px;margin-bottom:5px;'}).appendTo(img_holder);
						$("#image_payment_div").show();
					}else{
					}
					
					$('#AddPaymentModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	});	  	
 </script>