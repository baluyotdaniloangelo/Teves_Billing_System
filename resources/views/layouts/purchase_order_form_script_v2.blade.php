   <script type="text/javascript">
	LoadPurchaseOrderInfo();  
	LoadProductRowForUpdate();
	LoadPaymentRowForUpdate();
	<!--Select Product For Update-->
	function LoadPurchaseOrderInfo() {
			
			let purchase_order_id = {{ $PurchaseOrderID }};
			
			  $.ajax({
				url: "/purchase_order_info",
				type:"POST",
				data:{
				  purchase_order_id:purchase_order_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {					
			
			$('#control_no').text(response[0].purchase_order_control_number);
			
			document.getElementById("update_purchase_order_date").value = response[0].purchase_order_date;
			document.getElementById("update_supplier_idx").value = response[0].supplier_name;
				
			document.getElementById("update_purchase_order_sales_order_number").value = response[0].purchase_order_sales_order_number;
			document.getElementById("update_purchase_order_collection_receipt_no").value = response[0].purchase_order_collection_receipt_no;
			document.getElementById("update_purchase_order_official_receipt_no").value = response[0].purchase_order_official_receipt_no;
			document.getElementById("update_purchase_order_delivery_receipt_no").value = response[0].purchase_order_delivery_receipt_no;
		
			document.getElementById("update_purchase_order_delivery_method").value = response[0].purchase_order_delivery_method;
			document.getElementById("update_purchase_loading_terminal").value = response[0].purchase_loading_terminal;
			
			document.getElementById("update_purchase_order_net_percentage").value = response[0].purchase_order_net_percentage;
			document.getElementById("update_purchase_order_less_percentage").value = response[0].purchase_order_less_percentage;
				
			document.getElementById("update_hauler_operator").value = response[0].hauler_operator;
			document.getElementById("update_lorry_driver").value = response[0].lorry_driver;
			document.getElementById("update_plate_number").value = response[0].plate_number;
			document.getElementById("update_contact_number").value = response[0].contact_number;		
			
			document.getElementById("update_purchase_destination").value = response[0].purchase_destination;
			document.getElementById("update_purchase_destination_address").value = response[0].purchase_destination_address;
			document.getElementById("update_purchase_date_of_departure").value = response[0].purchase_date_of_departure;
			document.getElementById("update_purchase_date_of_arrival").value = response[0].purchase_date_of_arrival;
			
			document.getElementById("update_purchase_order_instructions").value = response[0].purchase_order_instructions;
			document.getElementById("update_purchase_order_note").value = response[0].purchase_order_note;		
					
			document.getElementById("update-purchase-order").value = purchase_order_id;		
			document.getElementById("update_company_header").value = response[0].company_header;	
			
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	}	

	function LoadProductRowForUpdate() {	
		
		$("#table_purchase_order_product_body_data tr").remove();
		$('<tr style="display: none;"><td>HIDDEN</td></tr>').appendTo('#table_purchase_order_product_body_data');


			  $.ajax({
				url: "/get_purchase_order_product_list",
				type:"POST",
				data:{
					purchase_order_id:{{ $PurchaseOrderID }},
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){						
				  console.log(response);
				  if(response!='') {			  
						var len = response.length;
						for(var i=0; i<len; i++){
						
							var id = response[i].purchase_order_component_id;
							var product_name = response[i].product_name;
							var product_unit_measurement = response[i].product_unit_measurement;
							var product_price = response[i].product_price;
							var order_quantity = response[i].order_quantity;
							
							var order_total_amount = response[i].order_total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2});
							
							$('#table_purchase_order_product_body_data tr:last').after("<tr>"+
							"<td align='center'>" + (i+1) + "</td>" +
							"<td><div align='center' class='action_table_menu_Product' ><a href='#' class='btn-danger btn-circle btn-sm bi-pencil-fill btn_icon_table btn_icon_table_edit' id='PurchaseOrderProduct_Edit' data-id='"+id+"'></a> <a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deletePurchaseOrderComponentProduct'  data-id='"+id+"'></a></div></td>"+
							
							"<td class='product_td' align='left'>"+product_name+"</td>"+
							"<td class='manual_price_td' align='center'>"+product_price+"</td>"+
							"<td class='calibration_td' align='center'>"+order_quantity+" "+product_unit_measurement+"</td>"+
							"<td class='manual_price_td' align='right'>"+order_total_amount+"</td>"+
							"</tr>");
							
					}			
				  }else{
							/*No Result Found or Error*/	
				  }
				},
				error: function(error) {
				 console.log(error);	 
				}
			   });
	  } 
	  
  
	function LoadPaymentRowForUpdate(purchase_order_id) {
	
			  $.ajax({
				url: "/get_purchase_order_payment_list",
				type:"POST",
				data:{
				  purchase_order_id:{{ $PurchaseOrderID }},
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
							
				  console.log(response);
				  if(response!='') {
					 
					$("#update_table_payment_body_data tr").remove();
					$('<tr style="display: none;"><td>HIDDEN</td></tr>').appendTo('#update_table_payment_body_data');

						var len = response.length;
						for(var i=0; i<len; i++){
							
							var id = response[i].purchase_order_payment_details_id;
							
							var purchase_order_bank 			= response[i].purchase_order_bank;
							var purchase_order_date_of_payment 	= response[i].purchase_order_date_of_payment;
							var purchase_order_reference_no		= response[i].purchase_order_reference_no;
							var purchase_order_payment_amount 	= response[i].purchase_order_payment_amount;
							//var image_reference 	= response[i].image_reference;
							
							/*Display Image*/
							//var image = new Image();
							//image_src = "data:image/jpg;image/png;base64,"+image_reference;
				
							$('#update_table_payment_body_data tr:last').after("<tr>"+
							"<td align='center'>" + (i+1) + "</td>" +
							//"<td align='center'><img src='"+image_src+"' width='30px'/></td>"+
							"<td><div align='left' class='action_table_menu_Product' ><a href='#' class='btn-danger btn-circle btn-sm bi-pencil-fill btn_icon_table btn_icon_table_edit' id='PurchaseOrderPayment_Edit' data-id='"+id+"'></a> <a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deletePurchaseOrderPayment'  data-id='"+id+"'></a></div></td>"+	
							"<td class='bank_td' align='center'>"+purchase_order_bank+"</td>"+
							"<td class='update_date_of_payment_td' align='center'>"+purchase_order_date_of_payment+"</td>"+
							"<td class='update_purchase_order_reference_no_td' align='center'>"+purchase_order_reference_no+"</td>"+
							"<td class='update_purchase_order_payment_amount_td' align='center'>"+purchase_order_payment_amount+"</td>");

						}			
				  }else{
							/*No Result Found or Error*/
				  }
				},
				error: function(error) {
				 console.log(error);	 
				}
			   });
	  }  	  
	
	  /*Re-print*/
	  $('body').on('click','#PrintPurchaseOrder',function(){	  
	  
			let purchaseOrderID = $(this).data('id');
			var query = {
						purchase_order_id:purchaseOrderID,
						_token: "{{ csrf_token() }}"
					}

			var url = "{{URL::to('generate_purchase_order_pdf')}}?" + $.param(query)
			window.open(url);
	  
	  });
	  
	<!--Product-->
	$("#save-product").click(function(event){
		
			event.preventDefault();
			
			/*Reset Warnings*/
					
			$('#product_idxError').text('');
			$('#product_manual_priceError').text('');
			$('#order_quantityError').text('');

			document.getElementById('AddProduct').className = "g-3 needs-validation was-validated";
			
			let company_header 					= $("#update_company_header").val();
			
			let purchase_order_id 				= {{ $PurchaseOrderID }};
			
			let purchase_order_component_id 	= document.getElementById("save-product").value;
			let product_idx 					= $("#product_list option[value='" + $('#product_idx').val() + "']").attr('data-id');
			let product_manual_price 			= $("#product_manual_price").val();
			let order_quantity 					= $("input[name=order_quantity]").val();

			/*Product Name*/
			let product_name 					= $("input[name=product_name]").val();
			
			let purchase_order_net_percentage 	= $("input[name=purchase_order_net_percentage]").val();
			let purchase_order_less_percentage 	= $("input[name=purchase_order_less_percentage]").val();
			
			  $.ajax({
				url: "{{ route('PurchaseOrderProduct') }}",
				type:"POST",
				data:{
				  branch_idx:company_header,	
				  purchase_order_id:purchase_order_id,
				  purchase_order_component_id:purchase_order_component_id,
				  product_idx:product_idx,
				  item_description:product_name,
				  product_manual_price:product_manual_price,
				  order_quantity:order_quantity,
				  purchase_order_net_percentage:purchase_order_net_percentage,
				  purchase_order_less_percentage:purchase_order_less_percentage,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
						$('#switch_notice_on').show();
						$('#sw_on').html(response.success);
						setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);

						$('#product_idxError').text('');
						$('#product_manual_priceError').text('');
						$('#order_quantityError').text('');
						
						/*Clear Form*/
						$('#product_idx').val("");
						$('#product_manual_price').val("");
						$('#order_quantity').val("");
						$('#AddProductModal').modal('toggle');	
						
						}
						
						/*Reload Table*/
						LoadProductRowForUpdate();
									  
				},
				beforeSend:function()
				{
					/*Disable Submit Button*/
					document.getElementById("save-product").disabled = true;
					/*Show Status*/
					$('#loading_data_update_product').show();
			
				},
				complete: function(){
					
					/*Disable Submit Button*/
					document.getElementById("save-product").disabled = false;
					/*Hide Status*/
					$('#loading_data_update_product').hide();
				
				},
				error: function(error) {
					
				 console.log(error);	
				      
					if(error.responseJSON.errors.product_idx=='Product is Required'){
							
							if(product_name==''){
								$('#product_idxError').html(error.responseJSON.errors.product_idx);
							}else{
								$('#product_idxError').html("Incorrect Product Name <b>" + product_name + "</b>");
							}
							
							document.getElementById("product_idx").value = "";
							document.getElementById('product_idxError').className = "invalid-feedback";
					
					}			
					
				  $('#order_quantityError').text(error.responseJSON.errors.order_quantity);
				  document.getElementById('order_quantityError').className = "invalid-feedback";					
								
				$('#switch_notice_off').show();
				$('#sw_off').html("Invalid Input" + "");
				setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);			  	  
				  
				}
			   });	
	  });		
	  
	<!--Select Bill For Update-->
	$('body').on('click','#PurchaseOrderProduct_Edit',function(){
			
			event.preventDefault();
			let purchase_order_component_id = $(this).data('id');
			
			  $.ajax({
				url: "/purchase_order_product_info",
				type:"POST",
				data:{
				  purchase_order_component_id:purchase_order_component_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("save-product").value = purchase_order_component_id;
					
					/*Set Details*/
					document.getElementById("product_idx").value = response[0].product_name;
					document.getElementById("product_manual_price").value = response[0].product_price;
					document.getElementById("order_quantity").value = response[0].order_quantity;
					
					var total_amount = response[0].order_total_amount;
					$('#TotalAmount').html(total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}));
					
					$('#AddProductModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });	  	  
	
	<!--Bill Deletion Confirmation-->
	$('body').on('click','#deletePurchaseOrderComponentProduct',function(){
			
			event.preventDefault();
			let purchase_order_component_id = $(this).data('id');
			
			  $.ajax({
				url: "/purchase_order_component_info",
				type:"POST",
				data:{
				  purchase_order_component_id:purchase_order_component_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("deletePurchaseOrderComponentConfirmed").value = purchase_order_component_id;
					
					/*Set Details*/
					$('#bill_delete_product_name').text(response[0].product_name);
					$('#bill_delete_order_quantity').text(response[0].order_quantity);					
					$('#bill_delete_order_total_amount').text(response[0].order_total_amount);

					$('#PurchaseOrderProductDeleteModal').modal('toggle');									  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });		
	  });

	  <!-- Confirmed For Deletion-->
	  $('body').on('click','#deletePurchaseOrderComponentConfirmed',function(){
			
			event.preventDefault();
			let purchase_order_id 				= {{ $PurchaseOrderID }};
			let purchase_order_component_id 	= document.getElementById("deletePurchaseOrderComponentConfirmed").value;
			
			  $.ajax({
				url: "{{ route('PurchaseOrderDeleteProduct') }}",
				type:"POST",
				data:{
					purchase_order_id:purchase_order_id,
					purchase_order_component_id:purchase_order_component_id,
					_token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					$('#switch_notice_off').show();
					$('#sw_off').html("Purchase Order Product Deleted");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);	

					/*Reload Table*/
					LoadProductRowForUpdate();
					
				  }
				},
				error: function(error) {
				 console.log(error);
				}
			   });		
	  });	  
	
	<!--Select For Update-->
	$('body').on('click','#PurchaseOrderPayment_Edit',function(){
			
			event.preventDefault();
			let purchase_order_payment_details_id = $(this).data('id');
			//alert(purchase_order_payment_details_id);
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
					//var image = new Image();
					if(response[0].image_reference != null){
						var img_holder = $('.img-holder');
						img_holder.empty();
						image_src = "data:image/jpg;image/png;base64,"+response[0].image_reference;
						
						$('<img/>',{'src':image_src,'class':'img-fluid','style':'max-width:400px;margin-bottom:5px;'}).appendTo(img_holder);
					
						//"<img src='"+image_src+"' width='30px'/>"+
						
						//var total_amount = response[0].order_total_amount;
						//$('#TotalAmount').html(total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}));
					}else{
					}
					//LoadPaymentRowForUpdate();
					$('#AddPaymentModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });	  	  
		
	<!--Select Bill For Update-->
	$('body').on('click','#deletePurchaseOrderPayment',function(){
			
			event.preventDefault();
			let purchase_order_payment_details_id = $(this).data('id');
			//alert(purchase_order_payment_details_id);
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
					
					document.getElementById("deletePurchaseOrderPaymentConfirmed").value = response[0].purchase_order_payment_details_id;
					
					/*Set Details*/
					//$('#control_no').text(
					$('#delete_purchase_order_bank').text(response[0].purchase_order_bank);
					$('#delete_purchase_order_date_of_payment').text(response[0].purchase_order_date_of_payment);
					$('#delete_purchase_order_reference_no').text(response[0].purchase_order_reference_no);
					$('#delete_purchase_order_payment_amount').text(response[0].purchase_order_payment_amount);
				
					
					if(response[0].image_reference != null){
					
						/*Display Image*/
						
						var img_holder = $('.delete_img-holder');
						img_holder.empty();
						image_src = "data:image/jpg;image/png;base64,"+response[0].image_reference;
						
						$('<img/>',{'src':image_src,'class':'img-fluid','style':'max-width:400px;margin-top:5px;margin-bottom:5px;'}).appendTo(img_holder);
					
					}else{
					
					}
					
					$('#PurchaseOrderPaymentDeleteModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });	  	  
		
	  <!-- Confirmed For Deletion-->
	  $('body').on('click','#deletePurchaseOrderPaymentConfirmed',function(){
			
			event.preventDefault();
			let purchase_order_id 				= {{ $PurchaseOrderID }};
			let paymentitemID 					= document.getElementById("deletePurchaseOrderPaymentConfirmed").value;
			
			  $.ajax({
				url: "{{ route('DeletePayment') }}",
				type:"POST",
				data:{
					purchase_order_id:purchase_order_id,
					paymentitemID:paymentitemID,
					_token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					$('#switch_notice_off').show();
					$('#sw_off').html("Purchase Order Payment Deleted");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);	

					/*Reload Table*/
					LoadPaymentRowForUpdate();
					
				  }
				},
				error: function(error) {
				 console.log(error);
				}
			   });		
	  });	  


	  function TotalAmount(){
		
		let product_price 			= $('#product_list option[value="' + $('#product_idx').val() + '"]').attr('data-price');
		let product_manual_price 	= $("#product_manual_price").val();
		let order_quantity 			= $("input[name=order_quantity]").val();
		
		if(order_quantity!=0 || order_quantity!=''){
			if(product_manual_price!='' && product_manual_price!=0){
				var total_amount = product_manual_price * order_quantity;
				$('#TotalAmount').html(total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}));
			}else{
				var total_amount = product_price * order_quantity;
				$('#TotalAmount').html(total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}));
			}
		}
		
	}	  

   $(function(){
	   
			/*Add Payment and Edit With Upload Function*/
            $('#AddPayment').on('submit', function(e){
                e.preventDefault();
	 		
				$('#purchase_order_bankError').text('');
				$('#purchase_order_date_of_paymentError').text('');
				$('#purchase_order_reference_noError').text('');
				$('#purchase_order_payment_amountError').text('');
			
				document.getElementById('AddPayment').className = "g-3 needs-validation was-validated";
                
				var form = this;
				
				$.ajax({
                    url:$(form).attr('action'),
                    method:$(form).attr('method'),
                    data:new FormData(form),
                    processData:false,
                    dataType:'json',
                    contentType:false,
                    beforeSend:function(){
                        $(form).find('span.error-text').text('');
                    },
                    success:function(data){
						console.log(data);
					
					if(data) {
					
						$('#switch_notice_on').show();
						$('#sw_on').html(data.success);
						setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);

						$('#purchase_order_bankError').text('');
						$('#purchase_order_date_of_paymentError').text('');
						$('#purchase_order_reference_noError').text('');
						$('#purchase_order_payment_amountError').text('');
						
						/*Clear Form*/
						$('#purchase_order_bank').val("");
						$('#purchase_order_reference_no').val("");
						$('#purchase_order_payment_amount').val("");
						$('#AddPaymentModal').modal('toggle');	
						
						}
						
						/*Reload Table*/
						LoadPaymentRowForUpdate();
						$(form)[0].reset();
					
                    },error: function(error) {
					
						console.log(error);	
								
					$('#purchase_order_bankError').text(error.responseJSON.errors.purchase_order_bank);
					document.getElementById('purchase_order_bankError').className = "invalid-feedback";
					
					$('#purchase_order_date_of_paymentError').text(error.responseJSON.errors.purchase_order_date_of_payment);
					document.getElementById('purchase_order_date_of_paymentError').className = "invalid-feedback";					
					
					$('#purchase_order_reference_noError').text(error.responseJSON.errors.purchase_order_reference_no);
					document.getElementById('purchase_order_reference_noError').className = "invalid-feedback";					
					
					$('#purchase_order_payment_amountError').text(error.responseJSON.errors.purchase_order_payment_amount);
					document.getElementById('purchase_order_payment_amountError').className = "invalid-feedback";					
					
					$('#switch_notice_off').show();
					$('#sw_off').html("Invalid Input" + "");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);			  	  
				  
				}
                });
            });

            //Reset input file
            $('input[type="file"][name="payment_image_reference"]').val('');
            //Image preview
            $('input[type="file"][name="payment_image_reference"]').on('change', function(){
                var img_path = $(this)[0].value;
                var img_holder = $('.img-holder');
                var extension = img_path.substring(img_path.lastIndexOf('.')+1).toLowerCase();

                if(extension == 'jpeg' || extension == 'jpg' || extension == 'png'){
                     if(typeof(FileReader) != 'undefined'){
                          img_holder.empty();
                          var reader = new FileReader();
                          reader.onload = function(e){
                              $('<img/>',{'src':e.target.result,'class':'img-fluid','style':'max-width:400px;margin-bottom:5px;'}).appendTo(img_holder);
                          }
                          img_holder.show();
                          reader.readAsDataURL($(this)[0].files[0]);
                     }else{
                         $(img_holder).html('This browser does not support FileReader');
                     }
                }else{
                    $(img_holder).empty();
                }
            });


    
        })
    </script>
