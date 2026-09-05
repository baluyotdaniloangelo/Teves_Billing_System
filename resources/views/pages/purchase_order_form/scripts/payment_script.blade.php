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

			beforeSend: function()
			{
				/* Disable Submit Button */
				document.getElementById("save-payment").disabled = true;

				/* Show Loading */
				$('#update_loading_data').show();

				setButtonLoading('#save-payment', true);
			},

			success: function(response)
			{
				console.log(response);

				if (response)
				{
					showSuccessModal(response.success);

					if ($('#purchase_order_payment_details_id').val() != 0)
					{
						$('#AddPaymentModal').modal('hide');
					}

					resetPaymentForm();
					initializePurchaseOrderPaymentTable();
					LoadProduct();
				}
			},

			complete: function()
			{
				/* Enable Submit Button */
				document.getElementById("save-payment").disabled = false;

				/* Hide Loading */
				$('#update_loading_data').hide();

				setButtonLoading('#save-payment', false);
			},

			error: function(error)
			{
				console.log(error);

				handleValidation(error);

				$('#action_error_message').text('Validation Error');
			}

		});
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



/* ============================================================
 * OPEN PAYMENT DELETE CONFIRMATION MODAL
 * ============================================================
 *
 * Gets the selected payment information from the server and
 * displays it in the Delete Payment modal.
 * ============================================================ */

$('body').on('click', '#deletePurchaseOrderPayment', function (event)
{
    event.preventDefault();

    /* Get Payment ID from the clicked Delete button */
    const purchase_order_payment_details_id = $(this).data('id');

    /* Make sure a valid ID was supplied */
    if (!purchase_order_payment_details_id)
    {
        console.error('Payment Details ID is missing.');
        return;
    }


    /* ========================================================
     * GET PAYMENT INFORMATION
     * ======================================================== */

    $.ajax({

        url: "{{ route('PaymentInfo') }}",

        type: "POST",

        data: {
            purchase_order_payment_details_id: purchase_order_payment_details_id,
            _token: "{{ csrf_token() }}"
        },

        dataType: 'json',


        /* ====================================================
         * SUCCESS
         * ==================================================== */

        success: function (response)
        {
            console.log('Payment Information:', response);


            /* ------------------------------------------------
             * Validate server response
             * ------------------------------------------------ */

            if (!response || !response.length)
            {
                console.error('No payment information was returned.');
                return;
            }


            /* Get the first payment record */
            const payment = response[0];


            /* ------------------------------------------------
             * Set Payment ID
             *
             * This ID will be used by the actual Delete button.
             * ------------------------------------------------ */

            $('#deletePurchaseOrderPaymentConfirmed')
                .val(payment.purchase_order_payment_details_id);


            /* ------------------------------------------------
             * Display Payment Details
             * ------------------------------------------------ */

            $('#delete_purchase_order_bank')
                .text(payment.purchase_order_bank || '-');

            $('#delete_purchase_order_date_of_payment')
                .text(payment.purchase_order_date_of_payment || '-');

            $('#delete_purchase_order_reference_no')
                .text(payment.purchase_order_reference_no || '-');

            $('#delete_purchase_order_payment_amount')
                .text(payment.purchase_order_payment_amount || '-');


            /* =================================================
             * DISPLAY PAYMENT PROOF IMAGE
             * ================================================= */

            const imgHolder = $('.delete_img-holder');

            /* Always clear the previous image first.
             *
             * This is important because the modal can be opened
             * multiple times. Otherwise, an old image may remain
             * when the next payment has no image.
             */
            imgHolder.empty();


            if (payment.image_reference)
            {
                /*
                 * Convert Base64 image data into an image source.
                 *
                 * If your database always stores JPG images,
                 * image/jpeg can be used. If PNG is possible,
                 * the server should ideally also return the
                 * correct MIME type.
                 */

                const imageSrc =
                    'data:image/jpeg;base64,' + payment.image_reference;


                /* Create and display the image */
                $('<img>', {
                    src: imageSrc,
                    class: 'img-fluid rounded-3 shadow-sm',
                    alt: 'Payment Proof',
                    style: 'max-width:400px; max-height:300px; object-fit:contain;'
                }).appendTo(imgHolder);
            }
            else
            {
                /* Display message when no payment proof exists */
                $('<div>', {
                    class: 'text-muted small py-4',
                    html: '<i class="bi bi-image me-1"></i> No payment proof available.'
                }).appendTo(imgHolder);
            }


            /* =================================================
             * SHOW DELETE CONFIRMATION MODAL
             * =================================================
             *
             * Use "show" instead of "toggle".
             *
             * "toggle" can accidentally hide the modal if the
             * modal is already open.
             */

            $('#PurchaseOrderPaymentDeleteModal').modal('show');

        },


        /* ====================================================
         * ERROR
         * ==================================================== */

        error: function (xhr)
        {
            console.error('PaymentInfo Error:', xhr);

            let errorMessage = 'Unable to retrieve payment information.';


            /* Laravel validation/server error */
            if (xhr.responseJSON)
            {
                if (xhr.responseJSON.message)
                {
                    errorMessage = xhr.responseJSON.message;
                }
                else if (xhr.responseJSON.errors)
                {
                    errorMessage = 'Validation error occurred.';
                }
            }


            /*
             * Use your standard error modal instead of
             * JavaScript alert().
             */

            $('#validation_error_message')
                .text(errorMessage);

            showValidationErrorModal(errorMessage);
        }

    });

});



/* ============================================================
 * CONFIRM PAYMENT DELETION
 * ============================================================
 *
 * Executes the actual deletion after the user confirms
 * deletion from the Purchase Order Payment Delete Modal.
 * ============================================================ */

$('body').on('click', '#deletePurchaseOrderPaymentConfirmed', function (event)
{
    event.preventDefault();


    /* ========================================================
     * GET REQUIRED VALUES
     * ======================================================== */

    const purchase_order_id = {{ $PurchaseOrderID }};

    const paymentitemID = $('#deletePurchaseOrderPaymentConfirmed').val();


    /* ========================================================
     * VALIDATE PAYMENT ID
     * ======================================================== */

    if (!paymentitemID)
    {
        console.error('Payment Item ID is missing.');

        showValidationErrorModal(
            'Unable to delete the payment. Payment information is missing.'
        );

        return;
    }


    /* ========================================================
     * DELETE PAYMENT
     * ======================================================== */

    $.ajax({

        url: "{{ route('DeletePayment') }}",

        type: "POST",

        data: {
            purchase_order_idx: purchase_order_id,
            paymentitemID: paymentitemID,
            _token: "{{ csrf_token() }}"
        },

        dataType: 'json',


        /* ====================================================
         * BEFORE SEND
         * ==================================================== */

        beforeSend: function ()
        {
            /*
             * Disable Delete button to prevent double-clicks
             * and duplicate deletion requests.
             */

            $('#deletePurchaseOrderPaymentConfirmed')
                .prop('disabled', true);


            /*
             * Optional loading state.
             *
             * If you already have setButtonLoading(), use it
             * here so it follows the rest of your application.
             */

            setButtonLoading(
                '#deletePurchaseOrderPaymentConfirmed',
                true
            );
        },


        /* ====================================================
         * SUCCESS
         * ==================================================== */

        success: function (response)
        {
            console.log('Delete Payment Response:', response);


            if (response)
            {
                /*
                 * Close the confirmation modal first.
                 *
                 * This happens only after the server confirms
                 * that the payment was successfully deleted.
                 */

                $('#PurchaseOrderPaymentDeleteModal')
                    .modal('hide');


                /*
                 * Display standard success modal.
                 */

                showSuccessModal(
                    response.success || 'Purchase Order Payment Deleted'
                );


                /*
                 * Reload the payment table/list so the deleted
                 * payment is immediately removed from the UI.
                 */

                initializePurchaseOrderPaymentTable();
            }
        },


        /* ====================================================
         * COMPLETE
         * ==================================================== */

        complete: function ()
        {
            /*
             * Re-enable Delete button after the AJAX request
             * finishes, whether successful or unsuccessful.
             */

            $('#deletePurchaseOrderPaymentConfirmed')
                .prop('disabled', false);


            /*
             * Remove loading state.
             */

            setButtonLoading(
                '#deletePurchaseOrderPaymentConfirmed',
                false
            );
        },


        /* ====================================================
         * ERROR
         * ==================================================== */

        error: function (xhr)
        {
            console.error('Delete Payment Error:', xhr);


            /*
             * Send Laravel validation errors through the
             * standard payment validation handler.
             */

            handleValidation(xhr);


            /*
             * Optional generic action error message.
             */

            $('#action_error_message')
                .text('Unable to delete payment.');
        }

    });

});

   
	
 </script>