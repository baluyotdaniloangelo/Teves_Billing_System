   <script type="text/javascript">		 
	<!--Update Sales Order-->
	//ClientInfo();
	LoadProduct();
	LoadPayment();
	LoadReceivables();
	
	function ResetPaymentForm(){
		/*Reset Form*/
		document.getElementById("AddPayment").reset();
		/*Hide Image Reference Div*/
		$("#image_payment_div").hide();
		/*Reset Payment Id*/
		document.getElementById("receivable_payment_id").value = 0;
	}
	
	$("#update-sales-order").click(function(event){

			event.preventDefault();
			
			/*Reset Warnings*/
			$('#client_idxError').text('');

			document.getElementById('UpdateSalesOrderformUpdate').className = "g-3 needs-validation was-validated";

			let sales_order_id 				= {{ $SalesOrderID }};
			let company_header 				= $("#company_header").val();
			let sales_order_payment_type 	= $("#sales_order_payment_type").val();
			let client_idx 					= ($("#client_name option[value='" + $('#client_id').val() + "']").attr('data-id'));	
			let sales_order_date 			= $("input[name=sales_order_date]").val();
			let delivered_to 				= $("input[name=delivered_to]").val();
			let delivered_to_address 		= $("input[name=delivered_to_address]").val();
			let dr_number 					= $("input[name=dr_number]").val();
			let or_number 					= $("input[name=or_number]").val();
			let payment_term 				= $("input[name=payment_term]").val();
			let delivery_method 			= $("input[name=delivery_method]").val();
			let hauler 						= $("input[name=hauler]").val();
			let required_date 				= $("input[name=required_date]").val();
			let instructions 				= $("#instructions").val();
			let note 						= $("#note").val();
			let sales_order_net_percentage 	= $("input[name=sales_order_net_percentage]").val();
			let sales_order_less_percentage = $("input[name=sales_order_less_percentage]").val();
			
			  $.ajax({
				url: "/update_sales_order_post",
				type:"POST",
				data:{
				  sales_order_id:sales_order_id,
				  company_header:company_header,
				  sales_order_payment_type:sales_order_payment_type,
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
				  sales_order_net_percentage:sales_order_net_percentage,
				  sales_order_less_percentage:sales_order_less_percentage,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					LoadProduct(sales_order_id);
					LoadProductList(company_header);
					document.getElementById("AddSalesOrderProductBTN").disabled = false;
					
					$('#switch_notice_on').show();
					$('#sw_on').html(response.success);
					setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
					
					$('#update_order_dateError').text('');					
					$('#update_order_timeError').text('');
					$('#update_order_po_numberError').text('');
					$('#update_client_idxError').text('');
					
					$('#update_plate_noError').text('');
					$('#update_drivers_nameError').text('');
					
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
				  
				  $('#product_idxError').html(error.responseJSON.errors.product_idx);
								
				$('#switch_notice_off').show();
				$('#sw_off').html("Invalid Input");
				setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);			  	  
				  
				}
			   });	
	  });	  

	function UpdateBranch(){ 
	
		$('#switch_notice_off').show();
		$('#sw_off').html("You selected a new branch, to confirm changes click the update button");
		setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },2000);
		
		/*Disable the Add Product Button Until Changes not Save*/
		document.getElementById("AddSalesOrderProductBTN").disabled = true;
		
	}

	<!--Select Receivable For Update-->	
	function LoadReceivables(){ 
			
			  $.ajax({
				url: "/receivable_info",
				type:"POST",
				data:{
				  receivable_id: {{ @$receivables_details['receivable_id'] }},
				  
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					/*Set Details*/
					document.getElementById("receivable_control_number_info").innerHTML = response[0].control_number;	
					document.getElementById("receivable_amount_info").innerHTML = response[0].receivable_amount;
					
					document.getElementById("receivable_billing_date_SO").value = response[0].billing_date;
					document.getElementById("receivable_payment_term_SO").value = response[0].payment_term;
					document.getElementById("receivable_description_SO").textContent = response[0].receivable_description;					
							
					
					$('#UpdateReceivablesModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	}

	function LoadProductList(branch_id) {		
	
		$("#product_list span").remove();
		$('<span style="display: none;"></span>').appendTo('#product_list');

			  $.ajax({
				url: "{{ route('ProductListPricingPerBranch') }}",
				type:"POST",
				data:{
				  branch_idx:branch_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){						
				  console.log(response);
				  if(response!='') {			  
						var len = response.length;
						for(var i=0; i<len; i++){
						
							var product_id = response[i].product_id;						
							var product_price = response[i].product_price.toLocaleString("en-PH", {maximumFractionDigits: 2});
							var product_name = response[i].product_name;
	
							$('#product_list span:last').after("<span style='font-family: DejaVu Sans; sans-serif;'>"+
							"<option label='&#8369; "+product_price+" | "+product_name+"' data-id='"+product_id+"' value='"+product_name+"' data-price='"+product_price+"' >" +
							"</span>");	
							
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
	
	$("#save-product").click(function(event){
		
			event.preventDefault();
			
					/*Reset Warnings*/
					
					$('#product_idxError').text('');
					$('#product_manual_priceError').text('');
					$('#order_quantityError').text('');

			document.getElementById('AddProduct').className = "g-3 needs-validation was-validated";
			
			let company_header 			= $("#company_header").val();
		
			let product_idx 			= $("#product_list option[value='" + $('#product_idx').val() + "']").attr('data-id');

			let product_manual_price 	= $("#product_manual_price").val();
			let order_quantity 			= $("input[name=order_quantity]").val();

			/*Product Name*/
			let product_name 			= $("input[name=product_name]").val();
			
			let sales_order_id 			= {{ $SalesOrderID }};
			let receivable_id 			= {{ @$receivables_details['receivable_id'] }};
			
			let sales_order_net_percentage 	= $("input[name=sales_order_net_percentage]").val();
			let sales_order_less_percentage = $("input[name=sales_order_less_percentage]").val();
			
			  $.ajax({
				url: "{{ route('SalesOrderComponentCompose') }}",
				type:"POST",
				data:{
				  sales_order_component_id:0,
				  branch_idx:company_header,
				  sales_order_id:sales_order_id,
				  receivable_id:receivable_id,
				  product_idx:product_idx, 
				  item_description:product_name,
				  product_manual_price:product_manual_price,
				  order_quantity:order_quantity,
				  sales_order_net_percentage:sales_order_net_percentage,
				  sales_order_less_percentage:sales_order_less_percentage,
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
						
						document.getElementById("save-product").value = 0;
						
						/*Reload Table*/
						LoadProduct();
						
				  
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
				      
					if(error.responseJSON.errors.product_idx=='Item Description or Product is Required'){
							
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

	function LoadProduct() {
		
		$("#table_sales_order_product_body_data tr").remove();
		$('<tr style="display: none;"><td>HIDDEN</td></tr>').appendTo('#table_sales_order_product_body_data');
		
		let receivable_id = {{ @$receivables_details['receivable_id'] }};

			  $.ajax({
				url: "/get_sales_order_product_list",
				type:"POST",
				data:{
				  sales_order_id:{{ $SalesOrderID }},
				  receivable_idx:receivable_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){						
				  
				  console.log(response['productlist']);
				  var len = response['productlist'].length;
				  
					  if(response['productlist']!='') {			  
							
							if(response['paymentcount']!=0){
							
								$(".action_column_class").hide();
								document.getElementById("AddSalesOrderProductBTN").disabled = true;
								
								for(var i=0; i<len; i++){
							
								var id = response['productlist'][i].sales_order_component_id;
								var product_name = response['productlist'][i].product_name;
								var product_unit_measurement = response['productlist'][i].product_unit_measurement;
								var product_price = response['productlist'][i].product_price;
								var order_quantity = response['productlist'][i].order_quantity;
								
								var order_total_amount = response['productlist'][i].order_total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2});
								
								$('#table_sales_order_product_body_data tr:last').after("<tr>"+
								"<td align='center'>" + (i+1) + "</td>" +
								"<td class='product_td' align='left'>"+product_name+"</td>"+
								"<td class='manual_price_td' align='center'>"+product_price+"</td>"+
								"<td class='calibration_td' align='center'>"+order_quantity+"</td>"+
								"<td class='calibration_td' align='center'>"+product_unit_measurement+"</td>"+
								"<td class='manual_price_td' align='right'>"+order_total_amount+"</td>"+
								"</tr>");
								
								}
								
							}else{
								
								$(".action_column_class").show();
								document.getElementById("AddSalesOrderProductBTN").disabled = false;
								
								for(var i=0; i<len; i++){
							
								var id = response['productlist'][i].sales_order_component_id;
								var product_name = response['productlist'][i].product_name;
								var product_unit_measurement = response['productlist'][i].product_unit_measurement;
								var product_price = response['productlist'][i].product_price;
								var order_quantity = response['productlist'][i].order_quantity;
								
								var order_total_amount = response['productlist'][i].order_total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2});
								
								$('#table_sales_order_product_body_data tr:last').after("<tr>"+
								"<td class='action_column_class'><div align='center' class='action_table_menu_Product' ><a href='#' class='btn-danger btn-circle btn-sm bi-pencil-fill btn_icon_table btn_icon_table_edit' id='SalesOrderProduct_Edit' data-id='"+id+"'></a> <a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deleteSalesOrderComponentProduct'  data-id='"+id+"'></a></div></td>"+
								"<td align='center'>" + (i+1) + "</td>" +
								"<td class='product_td' align='left'>"+product_name+"</td>"+
								"<td class='manual_price_td' align='center'>"+product_price+"</td>"+
								"<td class='calibration_td' align='center'>"+order_quantity+"</td>"+
								"<td class='calibration_td' align='center'>"+product_unit_measurement+"</td>"+
								"<td class='manual_price_td' align='right'>"+order_total_amount+"</td>"+
								"</tr>");
								}
								
							}
					  }
				  else{
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
			
			let company_header 			= $("#company_header").val();
			
			let sales_order_id 			= {{ $SalesOrderID }};
			let receivable_id 			= {{ @$receivables_details['receivable_id'] }};
			
			let sales_order_component_id 	= document.getElementById("update-product").value;
			let product_idx 				= $("#product_list option[value='" + $('#edit_product_idx').val() + "']").attr('data-id');
			let product_manual_price 		= $("#edit_product_manual_price").val();
			let order_quantity 				= $("input[name=edit_order_quantity]").val();

			/*Product Name*/
			let product_name 				= $("input[name=edit_product_name]").val();
			
			let sales_order_net_percentage 	= $("input[name=sales_order_net_percentage]").val();
			let sales_order_less_percentage = $("input[name=sales_order_less_percentage]").val();

			  $.ajax({
				url: "{{ route('SalesOrderComponentCompose') }}",
				type:"POST",
				data:{
				  branch_idx:company_header,	
				  sales_order_id:sales_order_id,
				  receivable_id:receivable_id,
				  sales_order_component_id:sales_order_component_id,
				  product_idx:product_idx,
				  item_description:product_name,
				  product_manual_price:product_manual_price,
				  order_quantity:order_quantity,
				  sales_order_net_percentage:sales_order_net_percentage,
				  sales_order_less_percentage:sales_order_less_percentage,
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
						
						}
						
						document.getElementById("update-product").value = 0;
						
						/*Reload Table*/
						LoadProduct();
						LoadReceivables();
									  
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

	/*Re-print Sales Order*/
	$('body').on('click','#PrintSalesOrder',function(){	  
	  
			let salesOrderID = {{ $SalesOrderID }};
			var query = {
						sales_order_id:salesOrderID,
						_token: "{{ csrf_token() }}"
					}

			var url = "{{URL::to('generate_sales_order_pdf')}}?" + $.param(query)
			window.open(url);
	  
	});
	 
	/*Print Statement of Account from Receivable*/
	$('body').on('click','#PrintSOA',function(){	  
	  
			let ReceivableID = {{ @$receivables_details['receivable_id'] }};
			var query_receivable = {
								receivable_id:ReceivableID,
								_token: "{{ csrf_token() }}"
							}

					var url_receivable = "{{URL::to('generate_receivable_soa_pdf')}}?" + $.param(query_receivable)
					window.open(url_receivable);
					
	});	 

	/*Print Receivable*/
	$('body').on('click','#PrintReceivable',function(){	  
	  
			let ReceivableID = {{ @$receivables_details['receivable_id'] }};	
			var query_receivable = {
								receivable_id:ReceivableID,
								_token: "{{ csrf_token() }}"
							}

					var url_receivable = "{{URL::to('generate_receivable_pdf')}}?" + $.param(query_receivable)
					window.open(url_receivable);
	});	 
	
	function ClientInfo() {
			
			let clientID = $("#client_name option[value='" + $('#client_id').val() + "']").attr('data-id');
			
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

	<!--Bill Deletion Confirmation-->
	$('body').on('click','#deleteSalesOrderComponentProduct',function(){
			
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
					
					document.getElementById("deleteSalesOrderComponentConfirmed").value = sales_order_component_id;
					
					/*Set Details*/
					$('#bill_delete_product_name').text(response[0].product_name);
					$('#bill_delete_order_quantity').text(response[0].order_quantity);					
					$('#bill_delete_order_total_amount').text(response[0].order_total_amount);

					$('#SalesOrderComponentDeleteModal').modal('toggle');				
					
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });		
	  });

	<!-- Confirmed For Deletion-->
	$('body').on('click','#deleteSalesOrderComponentConfirmed',function(){
			
			event.preventDefault();
			let sales_order_id 				= {{ $SalesOrderID }};
			let receivable_id 			= {{ @$receivables_details['receivable_id'] }};
			
			let sales_order_component_id 	= document.getElementById("deleteSalesOrderComponentConfirmed").value;
			let sales_order_net_percentage 	= $("input[name=sales_order_net_percentage]").val();
			let sales_order_less_percentage = $("input[name=sales_order_less_percentage]").val();
			
			  $.ajax({
				url: "{{ route('SalesOrderDeleteComponent') }}",
				type:"POST",
				data:{
					sales_order_id:sales_order_id,
					receivable_id:receivable_id,
					sales_order_component_id:sales_order_component_id,
					sales_order_net_percentage:sales_order_net_percentage,
					sales_order_less_percentage:sales_order_less_percentage,
					_token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					$('#switch_notice_off').show();
					$('#sw_off').html("Sales Order Product Deleted");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);	

					/*Reload Table*/
					LoadProduct();
					
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

	function UpdateTotalAmount(){
		let product_price 			= $('#product_list option[value="' + $('#edit_product_idx').val() + '"]').attr('data-price');
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

	/*Add Payment and Edit With Upload Function*/
    $('#AddPayment').on('submit', function(e){

                e.preventDefault();
	 		
				$('#receivable_mode_of_paymentError').text('');
				$('#receivable_date_of_payment').text('');
				$('#receivable_referenceError').text('');
				$('#receivable_payment_amountError').text('');
			
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
					
						$('#switch_notice_on').show();
						$('#sw_on').html(data.success);
						setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);

						$('#receivable_mode_of_paymentError').text('');
						$('#receivable_date_of_paymentError').text('');
						$('#receivable_referenceError').text('');
						$('#receivable_payment_amountError').text('');
						
						let receivable_payment_id = document.getElementById("receivable_payment_id").value;
						
						/*Close Payment Modal if the item is from Receivable*/
						if(receivable_payment_id!=0){
							
							$('#AddPaymentModal').modal('toggle');	
						
						}
						
						/*Reset Form*/
						ResetPaymentForm();
						/*Reload Table*/
						LoadPayment();
						LoadProduct();
					
                    },error: function(error) {
					
						console.log(error);	
						
						let receivable_reference 	= $("#receivable_reference").val();
						if(error.responseJSON.errors.receivable_reference=="The receivable reference has already been taken."){
							  
						receivable_reference_error = "<b>"+ receivable_reference +"</b> has already been taken.";
						$('#receivable_referenceError').html(receivable_reference_error);
						document.getElementById('receivable_referenceError').className = "invalid-feedback";
					
						$('#receivable_reference').val("");
					
						$('#switch_notice_off').show();
						$('#sw_off').html("Invalid Input" + ' ' + receivable_reference_error);
						setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);		
					  
					}else {		
					
						$('#receivable_mode_of_paymentError').text(error.responseJSON.errors.receivable_mode_of_payment);
						document.getElementById('receivable_mode_of_paymentError').className = "invalid-feedback";
						
						$('#receivable_date_of_payment').text(error.responseJSON.errors.purchase_order_date_of_payment);
						document.getElementById('receivable_date_of_payment').className = "invalid-feedback";					
						
						$('#receivable_referenceError').text(error.responseJSON.errors.receivable_reference);
						document.getElementById('receivable_referenceError').className = "invalid-feedback";					
						
						$('#receivable_payment_amountError').text(error.responseJSON.errors.receivable_payment_amount);
						document.getElementById('receivable_payment_amountError').className = "invalid-feedback";					
						
						$('#switch_notice_off').show();
						$('#sw_off').html("Invalid Input" + "");
						setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);			
					
					}
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
	
	function LoadPayment() {
				
			  let receivable_id = {{ @$receivables_details['receivable_id'] }};

			  $.ajax({
				url: "/get_sales_order_payment_list",
				type:"POST",
				data:{
				  receivable_idx:receivable_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
							
				  console.log(response);
				  if(response!='') {
					 
					$("#update_table_payment_body_data tr").remove();
					$('<tr style="display: none;"><td>HIDDEN</td></tr>').appendTo('#update_table_payment_body_data');
					
						var len = response.length;
						for(var i=0; i<len; i++){
							
							var id = response[i].receivable_payment_id;
							
							var receivable_mode_of_payment 		= response[i].receivable_mode_of_payment;
							var receivable_date_of_payment 		= response[i].receivable_date_of_payment;
							var receivable_reference			= response[i].receivable_reference;
							var receivable_payment_amount 		= response[i].receivable_payment_amount;
							var image_reference 				= response[i].image_reference;
							
							if(image_reference==null){
								
								$('#update_table_payment_body_data tr:last').after("<tr>"+
								"<td align='center'>" + (i+1) + "</td>" +
								"<td><div align='center' class='action_table_menu_Product' ><a href='#' class='btn-danger btn-circle btn-sm bi-pencil-fill btn_icon_table btn_icon_table_edit' id='SalesOrderPayment_Edit' data-id='"+id+"'></a> <a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deleteSalesOrderPayment'  data-id='"+id+"'></a></div></td>"+	
								"<td class='bank_td' align='center'>"+receivable_mode_of_payment+"</td>"+
								"<td class='update_date_of_payment_td' align='center'>"+receivable_date_of_payment+"</td>"+
								"<td class='update_purchase_order_reference_no_td' align='center'>"+receivable_reference+"</td>"+
								"<td class='update_purchase_order_payment_amount_td' align='center'>"+receivable_payment_amount+"</td>");
								
							}else{
								
								$('#update_table_payment_body_data tr:last').after("<tr>"+
								"<td align='center'>" + (i+1) + "</td>" +
								"<td><div align='center' class='action_table_menu_Product' ><a href='#' class='btn-danger btn-circle btn-sm bi-pencil-fill btn_icon_table btn_icon_table_edit' id='SalesOrderPayment_Edit' data-id='"+id+"'></a> <a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deleteSalesOrderPayment'  data-id='"+id+"'></a> <a href='#' class='btn-danger btn-circle btn-sm bi bi-eye-fill btn_icon_table btn_icon_table_view' id='ViewSalesOrderPayment'  data-id='"+id+"'></a></div></td>"+	
								"<td class='bank_td' align='center'>"+receivable_mode_of_payment+"</td>"+
								"<td class='update_date_of_payment_td' align='center'>"+receivable_date_of_payment+"</td>"+
								"<td class='update_purchase_order_reference_no_td' align='center'>"+receivable_reference+"</td>"+
								"<td class='update_purchase_order_payment_amount_td' align='center'>"+receivable_payment_amount+"</td>");
							
							}	

						}			
				  }else{
							/*No Result Found or Error*/
							$("#update_table_payment_body_data tr").remove();
							$('<tr style="display: none;"><td>HIDDEN</td></tr>').appendTo('#update_table_payment_body_data');
				  }
				},
				error: function(error) {
				 console.log(error);	 
				}
			   });
	  }  	  	
	
	<!--Select For Update-->
	$('body').on('click','#SalesOrderPayment_Edit',function(){
			
			event.preventDefault();
			let sales_order_payment_details_id = $(this).data('id');
			
			  $.ajax({
				url: "{{ route('SalesPaymentInfo') }}",
				type:"POST",
				data:{
				  receivable_payment_id:sales_order_payment_details_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("receivable_idx_payment").value 	= response[0].receivable_idx;
					document.getElementById("receivable_payment_id").value 		= response[0].receivable_payment_id;
					
					/*Set Details*/
					document.getElementById("receivable_mode_of_payment").value = response[0].receivable_mode_of_payment;
					document.getElementById("receivable_date_of_payment").value = response[0].receivable_date_of_payment;
					document.getElementById("receivable_reference").value 		= response[0].receivable_reference;
					document.getElementById("receivable_payment_amount").value 	= response[0].receivable_payment_amount;
					
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

	<!--Select Bill For Update-->
	$('body').on('click','#ViewSalesOrderPayment',function(){
			
			event.preventDefault();
			let sales_order_payment_details_id = $(this).data('id');
			
			  $.ajax({
				url: "{{ route('SalesPaymentInfo') }}",
				type:"POST",
				data:{
				  receivable_payment_id:sales_order_payment_details_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					/*Set Details*/
					$('#view_receivable_mode_of_payment').text(response[0].receivable_mode_of_payment);
					$('#view_receivable_date_of_payment').text(response[0].receivable_date_of_payment);
					$('#view_receivable_reference').text(response[0].receivable_reference);
					$('#view_receivable_payment_amount').text(response[0].receivable_payment_amount);
					
					if(response[0].image_reference != null){
					
						/*Display Image*/
						
						var img_holder = $('.view_img-holder');
						img_holder.empty();
						image_src = "data:image/jpg;image/png;base64,"+response[0].image_reference;
						
						$('<img/>',{'src':image_src,'class':'img-fluid','style':'max-width:600px;margin-top:5px;margin-bottom:5px;'}).appendTo(img_holder);
						
					}else{
					
					}
					
					$('#ViewOrderViewPaymentReferenceModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });	

	<!--Select Bill For Update-->
	$('body').on('click','#deleteSalesOrderPayment',function(){
			
			event.preventDefault();
			let sales_order_payment_details_id = $(this).data('id');
			
			  $.ajax({
				url: "{{ route('SalesPaymentInfo') }}",
				type:"POST",
				data:{
				  receivable_payment_id:sales_order_payment_details_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("deleteSalesOrderPaymentConfirmed").value = response[0].receivable_payment_id;
					
					/*Set Details*/
				
					$('#delete_receivable_mode_of_payment').text(response[0].receivable_mode_of_payment);
					$('#delete_receivable_date_of_payment').text(response[0].receivable_date_of_payment);
					$('#delete_receivable_reference').text(response[0].receivable_reference);
					$('#delete_receivable_payment_amount').text(response[0].receivable_payment_amount);
				
					if(response[0].image_reference != null){
					
						/*Display Image*/
						var img_holder = $('.delete_img-holder');
						img_holder.empty();
						image_src = "data:image/jpg;image/png;base64,"+response[0].image_reference;
						
						$('<img/>',{'src':image_src,'class':'img-fluid','style':'max-width:400px;margin-top:5px;margin-bottom:5px;'}).appendTo(img_holder);
					
					}else{
					
					}
					
					$('#SalesOrderPaymentDeleteModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });	

	  <!-- Confirmed For Deletion-->
	  $('body').on('click','#deleteSalesOrderPaymentConfirmed',function(){
			
			event.preventDefault();
			
			let receivable_id 		= {{ @$receivables_details['receivable_id'] }};
			let paymentitemID 		= document.getElementById("deleteSalesOrderPaymentConfirmed").value;
			
			  $.ajax({
				url: "{{ route('SalesOrderDeletePayment') }}",
				type:"POST",
				data:{
					receivable_idx:receivable_id,
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
					LoadPayment();
					
				  }
				},
				error: function(error) {
				 console.log(error);
				}
			   });		
	  });	  

	<!--Save New receivables->
	$("#SO-update-receivables").click(function(event){
			
			event.preventDefault();
			$('#receivable_description_SO_Error').text('');

			document.getElementById('ReceivableformEditFromSalesOrder').className = "g-3 needs-validation was-validated";
			
			let ReceivableID 			= {{ @$receivables_details['receivable_id'] }};

			let billing_date			= $("input[name=receivable_billing_date_SO]").val();	
			let payment_term 			= $("input[name=receivable_payment_term_SO]").val();
			let receivable_description 	= $("#receivable_description_SO").val();
			
			$.ajax({
				url: "/update_receivables_from_sale_order_post",
				type:"POST",
				data:{
				  ReceivableID:ReceivableID,
				  billing_date:billing_date,
				  payment_term:payment_term,
				  receivable_description:receivable_description,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					/*Reset Warnings*/
					$('#receivable_payment_termError').text('');
					$('#receivable_descriptionError').text('');
					
					/*Clear Form*/
					$('#receivable_payment_term').val("");
					$('#receivable_description').val("");
					
					
					$('#switch_notice_on').show();
					$('#sw_on').html(response.success);
					setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
					
					
					
				  }
				},
				beforeSend:function()
				{
					/*Disable Submit Button*/
					document.getElementById("SO-update-receivables").disabled = true;
					/*Show Status*/
					$('#update_loading_data_receivable').show();
				},
				complete: function(){
					
					/*Enable Submit Button*/
					document.getElementById("SO-update-receivables").disabled = false;
					/*Hide Status*/
					$('#update_loading_data_receivable').hide();
					
				},
				error: function(error) {
				 console.log(error);	
				
				$('#receivable_descriptionError').text(error.responseJSON.errors.receivable_description);
				document.getElementById('receivable_descriptionError').className = "invalid-feedback";					
				
				$('#switch_notice_off').show();
				$('#sw_off').html("Invalid Input");
				setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);			  	  
				  
				}
			   });		
	  });	  
 </script>