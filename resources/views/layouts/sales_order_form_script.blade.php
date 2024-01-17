   <script type="text/javascript">		 
	<!--Update Sales Order-->
	//ClientInfo();
	LoadProductRowForUpdate();
	$("#update-sales-order").click(function(event){

			event.preventDefault();
			
			/*Reset Warnings*/
			$('#client_idxError').text('');

			document.getElementById('UpdateSalesOrderformUpdate').className = "g-3 needs-validation was-validated";

			let sales_order_id 			= {{ $SalesOrderID }};
			let company_header 			= $("#company_header").val();
			let client_idx 				= ($("#client_name option[value='" + $('#client_id').val() + "']").attr('data-id'));	
			let sales_order_date 		= $("input[name=sales_order_date]").val();
			let delivered_to 			= $("input[name=delivered_to]").val();
			let delivered_to_address 	= $("input[name=delivered_to_address]").val();
			let dr_number 				= $("input[name=dr_number]").val();
			let or_number 				= $("input[name=or_number]").val();
			let payment_term 			= $("input[name=payment_term]").val();
			let delivery_method 		= $("input[name=delivery_method]").val();
			let hauler 					= $("input[name=hauler]").val();
			let required_date 			= $("input[name=required_date]").val();
			let instructions 			= $("#instructions").val();
			let note 					= $("#note").val();
			let sales_order_net_percentage 	= $("input[name=sales_order_net_percentage]").val();
			let sales_order_less_percentage = $("input[name=sales_order_less_percentage]").val();
			
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
				  sales_order_net_percentage:sales_order_net_percentage,
				  sales_order_less_percentage:sales_order_less_percentage,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					LoadProductRowForUpdate(sales_order_id);
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
					
					
					/*Open PDF for Printing
					var query = {
								sales_order_id:sales_order_id,
								_token: "{{ csrf_token() }}"
							}

					var url = "{{URL::to('generate_sales_order_pdf')}}?" + $.param(query)
					window.open(url);*/
					
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
			//let item_description  		= $("#product_idx").val();
			let product_manual_price 	= $("#product_manual_price").val();
			let order_quantity 			= $("input[name=order_quantity]").val();

			/*Product Name*/
			let product_name 					= $("input[name=product_name]").val();
			
			let sales_order_id 			= {{ $SalesOrderID }};
			
			let sales_order_net_percentage 	= $("input[name=sales_order_net_percentage]").val();
			let sales_order_less_percentage = $("input[name=sales_order_less_percentage]").val();
			
			  $.ajax({
				url: "{{ route('SalesOrderComponentCompose') }}",
				type:"POST",
				data:{
				  sales_order_component_id:0,
				  branch_idx:company_header,
				  sales_order_id:sales_order_id,
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


	function LoadProductRowForUpdate() {	
		
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
							"<td><div align='center' class='action_table_menu_Product' ><a href='#' class='btn-danger btn-circle btn-sm bi-pencil-fill btn_icon_table btn_icon_table_edit' id='SalesOrderProduct_Edit' data-id='"+id+"'></a> <a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deleteSalesOrderComponentProduct'  data-id='"+id+"'></a></div></td>"+
							"<td align='center'>" + (i+1) + "</td>" +
							"<td class='product_td' align='left'>"+product_name+"</td>"+
							"<td class='manual_price_td' align='center'>"+product_price+"</td>"+
							"<td class='calibration_td' align='center'>"+order_quantity+"</td>"+
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
			
			let company_header 			= $("#company_header").val();
			
			let sales_order_id 			= {{ $SalesOrderID }};
			
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
	  
			let salesOrderID = {{ $SalesOrderID }};
			var query = {
						sales_order_id:salesOrderID,
						_token: "{{ csrf_token() }}"
					}

			var url = "{{URL::to('generate_sales_order_pdf')}}?" + $.param(query)
			window.open(url);
	  
	});
	 
	function ClientInfo() {
			
			let clientID = $('#client_name option[value="' + console.log($("#client_id").val()) + '"]').attr('data-id');
			
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
			let sales_order_id 			= {{ $SalesOrderID }};
			let sales_order_component_id = document.getElementById("deleteSalesOrderComponentConfirmed").value;
			let sales_order_net_percentage 	= $("input[name=sales_order_net_percentage]").val();
			let sales_order_less_percentage = $("input[name=sales_order_less_percentage]").val();
			
			
			  $.ajax({
				url: "{{ route('SalesOrderDeleteComponent') }}",
				type:"POST",
				data:{
					sales_order_id:sales_order_id,
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
					LoadProductRowForUpdate();
					
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
 </script>