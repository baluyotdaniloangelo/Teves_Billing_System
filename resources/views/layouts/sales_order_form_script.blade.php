   <script type="text/javascript">		 
	<!--Update Sales Order-->
	//ClientInfo();
	LoadProductRowForUpdate();
	$("#update-sales-order").click(function(event){

			event.preventDefault();
			
			/*Reset Warnings*/
			$('#client_idxError').text('');

			document.getElementById('UpdateSalesOrderformUpdate').className = "g-3 needs-validation was-validated";

			//let sales_order_id			= document.getElementById("update-sales-order").value;
			let sales_order_id 			= {{ $SalesOrderID }};
			/*Added May 6, 2023*/
			let company_header 			= $("#update_company_header").val();
			let client_idx 				= ($("#update_client_name option[value='" + $('#update_client_idx').val() + "']").attr('data-id'));	
			let sales_order_date 		= $("input[name=update_sales_order_date]").val();
			let delivered_to 			= $("input[name=update_delivered_to]").val();
			let delivered_to_address 	= $("input[name=update_delivered_to_address]").val();
			let dr_number 				= $("input[name=update_dr_number]").val();
			let or_number 				= $("input[name=update_or_number]").val();
			let payment_term 			= $("input[name=update_payment_term]").val();
			let delivery_method 		= $("input[name=update_delivery_method]").val();
			let hauler 					= $("input[name=update_hauler]").val();
			let required_date 			= $("input[name=update_required_date]").val();
			let instructions 			= $("#update_instructions").val();
			let note 					= $("#update_note").val();
			let sales_order_net_percentage 	= $("input[name=update_sales_order_net_percentage]").val();
			let sales_order_less_percentage = $("input[name=update_sales_order_less_percentage]").val();
			
			  $.ajax({
				url: "/update_sales_order_post",
				type:"POST",
				data:{
				  sales_order_id:sales_order_id,
				  company_header:company_header,
				  client_idx:client_idx,
				  sales_order_date:sales_order_date,
				  delivered_to:delivered_to,
				  delivered_to_address:delivered_to_address,
				  dr_number:dr_number,
				  or_number:or_number,
				  payment_term:payment_term,
				  delivery_method:delivery_method,
				  hauler:hauler,
				  required_date:required_date,
				  instructions:instructions,
				  note:note,
				  
				  mode_of_payment:mode_of_payment,
				  date_of_payment:date_of_payment,
				  reference_no:reference_no,
				  payment_amount:payment_amount,
				  payment_item_id:payment_item_id,
				  
				  product_idx:product_idx,
				  order_quantity:order_quantity,
				  product_manual_price:product_manual_price, 
				  sales_order_product_item_ids:sales_order_product_item_id,
				  sales_order_net_percentage:sales_order_net_percentage,
				  sales_order_less_percentage:sales_order_less_percentage,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					LoadProductRowForUpdate(sales_order_id);
					
					$('#switch_notice_on').show();
					$('#sw_on').html(response.success);
					setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
					
					$('#update_order_dateError').text('');					
					$('#update_order_timeError').text('');
					$('#update_order_po_numberError').text('');
					$('#update_client_idxError').text('');
					
					$('#update_plate_noError').text('');
					$('#update_drivers_nameError').text('');
					$('#update_product_idxError').text('');
					$('#update_product_manual_priceError').text('');
					$('#update_order_quantityError').text('');
					
				    /*
					If you are using server side datatable, then you can use ajax.reload() 
					function to reload the datatable and pass the true or false as a parameter for refresh paging.
					*/
					
					var table = $("#getSalesOrderList").DataTable();
					table.ajax.reload(null, false);
					
					/*Close Modal*/
					$('#UpdateSalesOrderModal').modal('toggle');
					/*Open PDF for Printing*/
					var query = {
								sales_order_id:sales_order_id,
								_token: "{{ csrf_token() }}"
							}

					var url = "{{URL::to('generate_sales_order_pdf')}}?" + $.param(query)
					window.open(url);
					
					/*Reload Page
					location.reload();*/
					
				  }
				},
				beforeSend:function()
				{
					$('#upload_loading_data').show();
				},
				complete: function(){
					
					$('#upload_loading_data').hide();
					
					/*Close Form*/
					$('#UpdateSalesOrderModal').modal('toggle');
					
				},
				error: function(error) {
					
				 console.log(error);	
				 
				  $('#client_idxError').text(error.responseJSON.errors.client_idx);
				  document.getElementById('client_idxError').className = "invalid-feedback";
				  
				  //document.getElementById('product_idxError').className = "invalid-feedback";
				  $('#product_idxError').html(error.responseJSON.errors.product_idx);
								
				$('#switch_notice_off').show();
				$('#sw_off').html("Invalid Input");
				setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);			  	  
				  
				}
			   });	
	  });	  

	$("#save-product").click(function(event){
		
			event.preventDefault();
			
					/*Reset Warnings*/
					
					$('#product_idxError').text('');
					$('#product_manual_priceError').text('');
					$('#order_quantityError').text('');

			document.getElementById('AddProduct').className = "g-3 needs-validation was-validated";
		
			let product_idx 			= $("#product_name option[value='" + $('#product_idx').val() + "']").attr('data-id');
			let product_manual_price 	= $("#product_manual_price").val();
			let order_quantity 			= $("input[name=order_quantity]").val();

			/*Client and Product Name*/
			let product_name 					= $("input[name=product_name]").val();
			
			let sales_order_id 			= {{ $SalesOrderID }};
			
			  $.ajax({
				url: "{{ route('SalesOrderComponentCompose') }}",
				type:"POST",
				data:{
				  sales_order_component_id:0,
				  sales_order_id:sales_order_id,
				  product_idx:product_idx,
				  product_manual_price:product_manual_price,
				  order_quantity:order_quantity,
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
						
						$('#TotalAmount').html('0.00');
						}
						
						/*Reload Table*/
						LoadProductRowForUpdate();
						
				  
				},
				beforeSend:function()
				{
					/*Disable Submit Button*/
					document.getElementById("save-product").disabled = true;
					/*Show Status*/
					$('#loading_data_add_product').show();
			
				},
				complete: function(){
					
					/*Disable Submit Button*/
					document.getElementById("save-product").disabled = false;
					/*Hide Status*/
					$('#loading_data_add_product').hide();
				
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


	function LoadProductRowForUpdate() {		
		//event.preventDefault();
		
		$("#table_sales_order_product_body_data tr").remove();
		$('<tr style="display: none;"><td>HIDDEN</td></tr>').appendTo('#table_sales_order_product_body_data');


			  $.ajax({
				url: "/get_sales_order_product_list",
				type:"POST",
				data:{
				  sales_order_id:{{ $SalesOrderID }},
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){						
				  console.log(response);
				  if(response!='') {			  
						var len = response.length;
						for(var i=0; i<len; i++){
						
							var id = response[i].sales_order_component_id;
							var product_name = response[i].product_name;
							var product_price = response[i].product_price;
							var order_quantity = response[i].order_quantity;
							
							var order_total_amount = response[i].order_total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2});
							
							$('#table_sales_order_product_body_data tr:last').after("<tr>"+
							"<td><div align='center' class='action_table_menu_Product' ><a href='#' class='btn-danger btn-circle btn-sm bi-pencil-fill btn_icon_table btn_icon_table_edit' id='SalesOrderProduct_Edit' data-id='"+id+"'></a> <a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deleteSOProduct'  data-id='"+id+"'></a></div></td>"+
							"<td align='center'>" + (i+1) + "</td>" +
							"<td class='product_td' align='left'>"+product_name+"</td>"+
							"<td class='calibration_td' align='center'>"+order_quantity+"</td>"+
							"<td class='manual_price_td' align='center'>"+product_price+"</td>"+
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


	  
	<!--Select Bill For Update-->
	$('body').on('click','#SalesOrderProduct_Edit',function(){
			
			event.preventDefault();
			let sales_order_component_id = $(this).data('id');
			
			  $.ajax({
				url: "/sales_order_component_info",
				type:"POST",
				data:{
				  sales_order_component_id:sales_order_component_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("update-product").value = sales_order_component_id;
					
					/*Set Details*/
					document.getElementById("edit_product_idx").value = response[0].product_name;
					document.getElementById("edit_product_manual_price").value = response[0].product_price;
					document.getElementById("edit_order_quantity").value = response[0].order_quantity;
					
					var total_amount = response[0].order_total_amount;
					$('#UpdateTotalAmount').html(total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}));
					
					$('#EditProductModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });	  

	<!--Update So Product-->
	$("#update-product").click(function(event){
		
			event.preventDefault();
			
			/*Reset Warnings*/
					
			$('#product_idxError').text('');
			$('#product_manual_priceError').text('');
			$('#order_quantityError').text('');

			document.getElementById('EditSalesOrderComponentProduct').className = "g-3 needs-validation was-validated";
			
			let sales_order_component_id 	= document.getElementById("update-product").value;
			let product_idx 				= $("#edit_product_name option[value='" + $('#edit_product_idx').val() + "']").attr('data-id');
			let product_manual_price 		= $("#edit_product_manual_price").val();
			let order_quantity 				= $("input[name=edit_order_quantity]").val();

			/*Client and Product Name*/
			let product_name 				= $("input[name=product_name]").val();
			
			  $.ajax({
				url: "{{ route('SalesOrderComponentCompose') }}",
				type:"POST",
				data:{
				  sales_order_component_id:sales_order_component_id,
				  product_idx:product_idx,
				  product_manual_price:product_manual_price,
				  order_quantity:order_quantity,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
						$('#switch_notice_on').show();
						$('#sw_on').html(response.success);
						setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);

						$('#edit_product_idxError').text('');
						$('#edit_product_manual_priceError').text('');
						$('#edit_order_quantityError').text('');
						
						/*Clear Form*/
						$('#edit_product_idx').val("");
						$('#edit_product_manual_price').val("");
						$('#edit_order_quantity').val("");
						$('#EditProductModal').modal('toggle');	
						//$('#TotalAmount').html('0.00');
						}
						
						/*Reload Table*/
						LoadProductRowForUpdate();
									  
				},
				beforeSend:function()
				{
					/*Disable Submit Button*/
					document.getElementById("update-product").disabled = true;
					/*Show Status*/
					$('#loading_data_update_product').show();
			
				},
				complete: function(){
					
					/*Disable Submit Button*/
					document.getElementById("update-product").disabled = false;
					/*Hide Status*/
					$('#loading_data_update_product').hide();
				
				},
				error: function(error) {
					
				 console.log(error);	
				      
					if(error.responseJSON.errors.product_idx=='Product is Required'){
							
							if(product_name==''){
								$('#edit_product_idxError').html(error.responseJSON.errors.product_idx);
							}else{
								$('#edit_product_idxError').html("Incorrect Product Name <b>" + product_name + "</b>");
							}
							
							document.getElementById("edit_product_idx").value = "";
							document.getElementById('edit_product_idxError').className = "invalid-feedback";
					
					}			
					
				  $('#edit_order_quantityError').text(error.responseJSON.errors.order_quantity);
				  document.getElementById('edit_order_quantityError').className = "invalid-feedback";					
								
				$('#switch_notice_off').show();
				$('#sw_off').html("Invalid Input" + "");
				setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);			  	  
				  
				}
			   });	
	  });		

	  
	/*Re-print*/
	$('body').on('click','#PrintSalesOrder',function(){	  
	  
			let salesOrderID = $(this).data('id');
			var query = {
						sales_order_id:salesOrderID,
						_token: "{{ csrf_token() }}"
					}

			var url = "{{URL::to('generate_sales_order_pdf')}}?" + $.param(query)
			window.open(url);
	  
	});
	 
	function ClientInfo() {
			
			let clientID 				= ($("#client_name option[value='" + $('#client_id').val() + "']").attr('data-id'));
			
			$.ajax({
				url: "/client_info",
				type:"POST",
				data:{
				  clientID:clientID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					/*Set Details*/
					
					document.getElementById("sales_order_less_percentage").value = response.default_less_percentage;
					document.getElementById("sales_order_net_percentage").value = response.default_net_percentage;		
					document.getElementById("payment_term").value = response.default_payment_terms;		
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
			   
	}	 
	
	function TotalAmount(){
		
		let product_price 			= $("#product_name option[value='" + $('#product_idx').val() + "']").attr('data-price');
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

	function UpdateTotalAmount(){
		
		let product_price 			= $("#edit_product_name option[value='" + $('#edit_product_idx').val() + "']").attr('data-price');
		let product_manual_price 	= $("#edit_product_manual_price").val();
		let order_quantity 			= $("input[name=edit_order_quantity]").val();
		
		if(order_quantity!=0 || order_quantity!=''){
			if(product_manual_price!='' && product_manual_price!=0){
				var total_amount = product_manual_price * order_quantity;
				$('#UpdateTotalAmount').html(total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}));
			}else{
				var total_amount = product_price * order_quantity;
				$('#UpdateTotalAmount').html(total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}));
			}
		}
		
	}		
 </script>