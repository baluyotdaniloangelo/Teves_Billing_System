   <script type="text/javascript">

	<!--Load Table-->
	$(function () {

		var ReceivableListTable = $('#getSalesOrderList').DataTable({
			"language": {
						"lengthMenu":'<select class="form-select form-control form-control-sm">'+
			             '<option value="10">10</option>'+
			             '<option value="20">20</option>'+
			             '<option value="30">30</option>'+
			             '<option value="40">40</option>'+
			             '<option value="50">50</option>'+
			             '<option value="-1">All</option>'+
			             '</select> '
		    },
			/*processing: true,*/
			serverSide: true,
			stateSave: true,/*Remember Searches*/
			ajax: "{{ route('getSalesOrderList') }}",
			columns: [
					{data: 'DT_RowIndex', name: 'DT_RowIndex' , orderable: false, searchable: false},
					{data: 'sales_order_date'},
					{data: 'sales_order_control_number'},
					{data: 'client_name'},   
					//{data: 'sales_order_dr_number'},
					//{data: 'sales_order_or_number'},
					{data: 'sales_order_payment_term'},
					{data: 'sales_order_total_due', render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },
					{data: 'status', name: 'status', orderable: true, searchable: true},
					{data: 'action', name: 'action', orderable: false, searchable: false},
			],
			order: [[ 1, "asc" ]],
			columnDefs: [
					{ className: 'text-center', targets: [0, 1, 2, 4, 7] },
			]
		});
				/**/
				$('<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">'+
				'<button type="button" class="btn btn-success new_item bi bi-plus-circle" data-bs-toggle="modal" data-bs-target="#CreateSalesOrderModal"></button>'+
				'</div>').appendTo('#sales_order_option');
				
	});
	
		function AddPaymentRow() {
		
		var x = document.getElementById("table_payment_body_data").rows.length;
		/*Limit to 5 rows*/
		
		if(x > 3){
		   return;
		}else{
		
			$('#table_payment_body_data tr:last').after("<tr>"+
			"<td class='bank_td' align='center'>"+
			"<input type='text' class='form-control mode_of_payment' id='mode_of_payment' name='mode_of_payment' list='mode_of_payment_list' autocomplete='off'>"+
							"<datalist id='mode_of_payment_list'>"+
								<?php foreach ($sales_order_mode_of_payment_suggestion as $mode_of_payment_cols) {?>
									"<option value='<?=$mode_of_payment_cols->sales_order_mode_of_payment;?>'>"+
								<?php } ?>
							"</datalist>"+
			"</td>"+
			"<td class='date_of_payment_td' align='center'><input type='date' class='form-control date_of_payment' id='date_of_payment' name='date_of_payment' value='<?=date('Y-m-d');?>'></td>"+
			"<td class='reference_no_td' align='center'><input type='text' class='form-control reference_no' id='reference_no' name='reference_no'></td>"+
			"<td class='payment_amount_td' align='center'><input type='number' class='form-control payment_amount' id='payment_amount' name='payment_amount'></td>"+
			"<td><div onclick='deletePaymentRow(this)' data-id='0' id='payment_item'><div align='center' class='action_table_menu_Product' style='margin-top: 6px;'><a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deletePayment'></a></div></div></td></tr>");
		
		}	
	}		
	
	function UpdatePaymentRow() {
		
		var x = document.getElementById("update_table_payment_body_data").rows.length;
		/*Limit to 5 rows*/
		if(x > 3){
		   return;
		}else{
		
					
						$('#update_table_payment_body_data tr:last').after("<tr>"+
							"<td class='bank_td' align='center'>"+
							"<input type='text' class='form-control update_mode_of_payment' id='update_mode_of_payment' name='update_mode_of_payment' list='mode_of_payment_list' autocomplete='off' value=''>"+
											"<datalist id='mode_of_payment_list'>"+
												<?php foreach ($sales_order_mode_of_payment_suggestion as $mode_of_payment_cols) {?>
													"<option value='<?=$mode_of_payment_cols->sales_order_mode_of_payment;?>'>"+
												<?php } ?>
											"</datalist>"+
							"</td>"+
							"<td class='update_date_of_payment_td' align='center'><input type='date' class='form-control update_date_of_payment' id='update_date_of_payment' name='update_date_of_payment' value=''></td>"+
							"<td class='update_reference_no_td' align='center'><input type='text' class='form-control update_reference_no' id='update_reference_no' name='update_reference_no' value=''></td>"+
							"<td class='update_payment_amount_td' align='center'><input type='number' class='form-control update_payment_amount' id='update_payment_amount' name='update_payment_amount' value=''></td>"+
							"<td><div onclick='deletePaymentRow(this)' data-id='' id='payment_item'><div align='center' class='action_table_menu_Product' style='margin-top: 6px;'><a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deletePayment'></a></div></div></td></tr>");

		}	
	}
	
	function deletePaymentRow(btn) {
			
		var paymentitemID= $(btn).data("id");			
		
		var row = btn.parentNode.parentNode;
		row.parentNode.removeChild(row);
		
		if(paymentitemID!=0){
			/*Delete the Selected Item*/
			
			  $.ajax({
				url: "/delete_sales_order_payment_item",
				type:"POST",
				data:{
				  paymentitemID:paymentitemID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					//$('#switch_notice_off').show();
					//$('#sw_off').html("Item Deleted");
					//setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);	
					
				  }
				},
				error: function(error) {
				 console.log(error);
					//alert(error);
				}
			   });		
		}
		
	}

	
	function AddProductRow() {
		
		var x = document.getElementById("table_product_body_data").rows.length;
		/*Limit to 5 rows*/
		
		if(x > 5){
		   return;
		}else{
		
			$('#table_product_body_data tr:last').after("<tr>"+
			"<td class='product_td' align='center'>"+
			"<select class='form-control form-select product_idx' name='product_idx' id='product_idx' required>"+
				"<option selected='' disabled='' value=''>Choose...</option>"+
					<?php foreach ($product_data as $product_data_cols){ ?>
						"<option value='<?=$product_data_cols->product_id;?>'>"+
						"<?=$product_data_cols->product_name;?> | <span style='font-family: DejaVu Sans; sans-serif;'>&#8369;</span>&nbsp;<?=$product_data_cols->product_price;?></option>"+
					<?php } ?>
			"</select></td>"+
			"<td class='quantity_td' align='center'><input type='number' class='form-control order_quantity' id='order_quantity' name='order_quantity' ></td>"+
			"<td class='manual_price_td' align='center'><input type='text' class='form-control product_manual_price' placeholder='0.00' aria-label='' name='product_manual_price' id='product_manual_price' value=''></td>"+
			"<td><div onclick='deleteRow(this)' data-id='0' id='product_item'><div align='center' class='action_table_menu_Product' style='margin-top: 6px;'><a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deleteReceivables'></a></div></div></td></tr>");
		
		}	
	  }
	  
	function UpdateProductRow() {
		
		var x = document.getElementById("update_table_product_body_data").rows.length;
		/*Limit to 5 rows*/
		if(x > 5){
		   return;
		}else{
		
			$('#update_table_product_body_data tr:last').after("<tr>"+
			"<td class='product_td' align='center'>"+
			"<select class='form-control form-select update_product_idx' name='update_product_idx' id='update_product_idx' required>"+
				"<option selected='' disabled='' value=''>Choose...</option>"+
					<?php foreach ($product_data as $product_data_cols){ ?>
						"<option value='<?=$product_data_cols->product_id;?>'>"+
						"<?=$product_data_cols->product_name;?> | <span style='font-family: DejaVu Sans; sans-serif;'>&#8369;</span>&nbsp;<?=$product_data_cols->product_price;?></option>"+
					<?php } ?>
			"</select></td>"+
			"<td class='quantity_td' align='center'><input type='number' class='form-control update_order_quantity' id='update_order_quantity' name='update_order_quantity' ></td>"+
			"<td class='manual_price_td' align='center'><input type='text' class='form-control update_product_manual_price' placeholder='0.00' aria-label='' name='update_product_manual_price' id='update_product_manual_price' value=''></td>"+
			"<td><div onclick='deleteRow(this)' data-id='0' id='product_item'><div align='center' class='action_table_menu_Product' style='margin-top: 6px;'><a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deleteReceivables'></a></div></div></td></tr>");
		
		}	
	}
	  
	function deleteRow(btn) {
			
		var productitemID= $(btn).data("id");			
		
		var row = btn.parentNode.parentNode;
		row.parentNode.removeChild(row);
		
		if(productitemID!=0){
			/*Delete the Selected Item*/
			
			  $.ajax({
				url: "/delete_sales_order_item",
				type:"POST",
				data:{
				  productitemID:productitemID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					//$('#switch_notice_off').show();
					//$('#sw_off').html("Item Deleted");
					//setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);	
					
				  }
				},
				error: function(error) {
				 console.log(error);
					//alert(error);
				}
			   });		
		}
		
	}
	
	<!--Save New Sales Order-->
	$("#save-sales-order").click(function(event){

			event.preventDefault();
			
					/*Reset Warnings*/
					$('#client_idxError').text('');

			document.getElementById('SalesOrderformNew').className = "g-3 needs-validation was-validated";

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
			
			//let mode_of_payment 		= $("input[name=mode_of_payment]").val();
			//let date_of_payment 		= $("input[name=date_of_payment]").val();
			//let reference_no 			= $("input[name=reference_no]").val();
			//let payment_amount 			= $("input[name=payment_amount]").val();
			
			let sales_order_net_percentage 	= $("input[name=sales_order_net_percentage]").val();
			let sales_order_less_percentage = $("input[name=sales_order_less_percentage]").val();
			
				var product_idx = [];
				var order_quantity = [];
				var product_manual_price = [];
				  
				  $('.product_idx').each(function(){
					if($(this).val() == ''){
						alert('Please Select a Product');
						exit();
					}else{  				  
				   		product_idx.push($(this).val());
					}				  
				  });
				  
				  $('.order_quantity').each(function(){
					if($(this).val() == ''){
						alert('Quantity is Empty');
						exit(); 
					}else{  				  
				   		order_quantity.push($(this).val());
					}				  
				  });
				  
				  $('.product_manual_price').each(function(){ 				  
				   		product_manual_price.push($(this).val());			  
				  });		
				
				/*Payment Options*/
				var mode_of_payment = [];
				var date_of_payment = [];
				var reference_no = [];
				var payment_amount = [];
				  
				  $('.mode_of_payment').each(function(){
					if($(this).val() == ''){
						alert('Please Select a Bank');
						exit();
					}else{  				  
				   		mode_of_payment.push($(this).val());
					}				  
				  });
				  
				  $('.date_of_payment').each(function(){
					if($(this).val() == ''){
						alert('Date of Payment is Empty');
						exit(); 
					}else{  				  
				   		date_of_payment.push($(this).val());
					}				  
				  });
				  
				  $('.reference_no').each(function(){
					if($(this).val() == ''){
						alert('Reference is Empty');
						exit(); 
					}else{  				  
				   		reference_no.push($(this).val());
					}				  
				  });	
				  
				  $('.payment_amount').each(function(){
					if($(this).val() == ''){
						alert('Payment Amount is Empty');
						exit(); 
					}else{  				  
				   		payment_amount.push($(this).val());
					}				  
				  });		

				
			  $.ajax({
				url: "/create_sales_order_post",
				type:"POST",
				data:{
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
				  product_idx:product_idx,
				  order_quantity:order_quantity,
				  product_manual_price:product_manual_price,
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
					
					$('#order_dateError').text('');					
					$('#order_timeError').text('');
					$('#order_po_numberError').text('');
					$('#client_idxError').text('');
					
					$('#plate_noError').text('');
					$('#drivers_nameError').text('');
					$('#product_idxError').text('');
					$('#product_manual_priceError').text('');
					$('#order_quantityError').text('');
		
					
				    /*
					If you are using server side datatable, then you can use ajax.reload() 
					function to reload the datatable and pass the true or false as a parameter for refresh paging.
					*/
					
					var table = $("#getSalesOrderList").DataTable();
					table.ajax.reload(null, false);
					
					/*Close Modal*/
					$('#CreateSalesOrderModal').modal('toggle');
					/*Open PDF for Printing*/
					var query = {
						sales_order_id:response.sales_order_id,
						_token: "{{ csrf_token() }}"
					}

					var url = "{{URL::to('generate_sales_order_pdf')}}?" + $.param(query)
					window.open(url);
					
				  }
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
	
	<!--Product Deletion Confirmation-->
	$('body').on('click','#deleteSalesOrder',function(){
			
			event.preventDefault();
			let sales_order_id = $(this).data('id');
			
			  $.ajax({
				url: "/sales_order_info",
				type:"POST",
				data:{
				  sales_order_id:sales_order_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("deleteSalesOrderConfirmed").value = sales_order_id;
											
					/*Set Details*/
					$('#confirm_delete_sales_order_date').text(response[0].sales_order_date);
					$('#confirm_delete_sales_control_number').text(response[0].sales_order_control_number);
					$('#confirm_delete_client_name').text(response[0].client_name);
					$('#confirm_delete_dr_number').text(response[0].sales_order_dr_number);					
					$('#confirm_delete_or_number').text(response[0].sales_order_or_number);
					$('#confirm_delete_total_due').text(response[0].sales_order_total_due);
					
					$('#SalesOrderDeleteModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });		
	  });	
	  
	  
	  <!--Product Confirmed For Deletion-->
	  $('body').on('click','#deleteSalesOrderConfirmed',function(){
			
			event.preventDefault();

			let sales_order_id = document.getElementById("deleteSalesOrderConfirmed").value;
			
			  $.ajax({
				url: "/delete_sales_order_confirmed",
				type:"POST",
				data:{
				  sales_order_id:sales_order_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					$('#switch_notice_off').show();
					$('#sw_off').html("Sales Order Deleted");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);	
					
					/*
					If you are using server side datatable, then you can use ajax.reload() 
					function to reload the datatable and pass the true or false as a parameter for refresh paging.
					*/
					
					var table = $("#getSalesOrderList").DataTable();
				    table.ajax.reload(null, false);
					
				  }
				},
				error: function(error) {
				 console.log(error);
				}
			   });		
	  });
	  
	  
	<!--Select Product For Update-->
	$('body').on('click','#EditSalesOrder',function(){
			
			event.preventDefault();
			let sales_order_id = $(this).data('id');
			
			/*Call Product List for Sales Order*/
			LoadProductRowForUpdate(sales_order_id);
			
			/*Call Product List for Sales Order*/
			LoadPaymentRowForUpdate(sales_order_id);
			
			  $.ajax({
				url: "/sales_order_info",
				type:"POST",
				data:{
				  sales_order_id:sales_order_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("update_sales_order_date").value = response[0].sales_order_date;
					document.getElementById("update_dr_number").value = response[0].sales_order_dr_number;
					document.getElementById("update_or_number").value = response[0].sales_order_or_number;
					document.getElementById("update_payment_term").value = response[0].sales_order_payment_term;
					
					document.getElementById("update_client_idx").value = response[0].client_name;
					document.getElementById("update_delivered_to").value = response[0].sales_order_delivered_to_address;
					document.getElementById("update_delivered_to_address").value = response[0].sales_order_delivered_to_address;
					
					document.getElementById("update_delivery_method").value = response[0].sales_order_delivery_method;
					document.getElementById("update_hauler").value = response[0].sales_order_hauler;
					document.getElementById("update_required_date").value = response[0].sales_order_required_date;
					
					document.getElementById("update_instructions").value = response[0].sales_order_instructions;
					document.getElementById("update_note").value = response[0].sales_order_note;
					
					//document.getElementById("update_mode_of_payment").value = response[0].sales_order_mode_of_payment;
					//document.getElementById("update_date_of_payment").value = response[0].sales_order_date_of_payment;
					//document.getElementById("update_reference_no").value = response[0].sales_order_reference_no;
					//document.getElementById("update_payment_amount").value = response[0].sales_order_payment_amount;
					
					document.getElementById("update-sales-order").value = response[0].sales_order_id;
					
					document.getElementById("update_sales_order_net_percentage").value = response[0].sales_order_net_percentage;
					document.getElementById("update_sales_order_less_percentage").value = response[0].sales_order_less_percentage;
					
				var update_product_idx = [];
				var update_order_quantity = [];
				var update_product_manual_price = [];
				  
					 $('.update_product_idx').each(function(){
						if($(this).val() == ''){
							alert('Please Select a Product');
							exit();
						}else{  				  
							update_product_idx.push($(this).val());
						}				  
					});
					  
					$('.update_order_quantity').each(function(){
						if($(this).val() == ''){
							alert('Quantity is Empty');
							exit(); 
						}else{  				  
							update_order_quantity.push($(this).val());
						}				  
					});
					  
					$('.update_product_manual_price').each(function(){ 				  
							update_product_manual_price.push($(this).val());			  
					});		

					$('#UpdateSalesOrderModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });	

	function LoadProductRowForUpdate(sales_order_id) {
		
		event.preventDefault();
		$("#update_table_product_body_data tr").remove();
		$('<tr style="display: none;"><td>HIDDEN</td></tr>').appendTo('#update_table_product_body_data');
		
			  $.ajax({
				url: "/get_sales_order_product_list",
				type:"POST",
				data:{
				  sales_order_id:sales_order_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
							
				  console.log(response);
				  if(response!='') {
					  
						var len = response.length;
						for(var i=0; i<len; i++){
							
							var id = response[i].sales_order_component_id;
							
							var product_idx = response[i].product_idx;
							var product_price = response[i].product_price;
							var order_quantity = response[i].order_quantity;
							
							$('#update_table_product_body_data tr:last').after("<tr>"+
								"<td class='product_td' align='center'>"+
								"<select class='form-control form-select update_product_idx' name='update_product_idx' id='update_product_idx' required>"+
									"<option selected='' disabled='' value=''>Choose...</option>"+
										<?php foreach ($product_data as $product_data_cols){ ?>
											"<option value='<?=$product_data_cols->product_id;?>'"+
											((product_idx == <?=$product_data_cols->product_id;?>) ? 'selected' : '') +
											">"+
											"<?=$product_data_cols->product_name;?> | <span style='font-family: DejaVu Sans; sans-serif;'>&#8369;</span>&nbsp;<?=$product_data_cols->product_price;?></option>"+
										<?php } ?>
								"</select></td>"+
								"<td class='quantity_td' align='center'>"+
								"<input type='number' class='form-control update_order_quantity' id='update_order_quantity' name='update_order_quantity' value='"+order_quantity+"'>"+
								"</td>"+
								"<td class='manual_price_td' align='center'>"+
								"<input type='text' class='form-control update_product_manual_price' placeholder='0.00' aria-label='' name='update_product_manual_price' id='update_product_manual_price' value='"+product_price+"'>"+
								"</td>"+
								"<td><div onclick='deleteRow(this)' data-id="+id+" id='product_item'><div align='center' class='action_table_menu_Product' style='margin-top: 6px;'>"+
								"<a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deleteReceivables'></a>"+"</div></div>"+"</td></tr>");
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
	

	function LoadPaymentRowForUpdate(sales_order_id) {
		
		event.preventDefault();
		$("#update_table_payment_body_data tr").remove();
		$('<tr style="display: none;"><td>HIDDEN</td></tr>').appendTo('#update_table_payment_body_data');
		
			  $.ajax({
				url: "/get_sales_order_payment_list",
				type:"POST",
				data:{
				  sales_order_id:sales_order_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
							
				  console.log(response);
				  if(response!='') {
					  
						var len = response.length;
						for(var i=0; i<len; i++){
							
							var id = response[i].sales_order_payment_details_id;
							
							var mode_of_payment 			= response[i].sales_order_mode_of_payment;
							var sales_order_date_of_payment 	= response[i].sales_order_date_of_payment;
							var sales_order_reference_no		= response[i].sales_order_reference_no;
							var sales_order_payment_amount 	= response[i].sales_order_payment_amount;
							
							$('#update_table_payment_body_data tr:last').after("<tr>"+
							"<td class='bank_td' align='center'>"+
							"<input type='text' class='form-control update_mode_of_payment' id='update_mode_of_payment' name='update_mode_of_payment' list='mode_of_payment_list' autocomplete='off' value='"+mode_of_payment+"'>"+
											"<datalist id='mode_of_payment_list'>"+
												<?php foreach ($sales_order_mode_of_payment_suggestion as $mode_of_payment_cols) {?>
													"<option value='<?=$mode_of_payment_cols->sales_order_mode_of_payment;?>'>"+
												<?php } ?>
											"</datalist>"+
							"</td>"+
							"<td class='update_date_of_payment_td' align='center'><input type='date' class='form-control update_date_of_payment' id='update_date_of_payment' name='update_date_of_payment' value='"+sales_order_date_of_payment+"'></td>"+
							"<td class='update_reference_no_td' align='center'><input type='text' class='form-control update_reference_no' id='update_reference_no' name='update_reference_no' value='"+sales_order_reference_no+"'></td>"+
							"<td class='update_payment_amount_td' align='center'><input type='number' class='form-control update_payment_amount' id='update_payment_amount' name='update_payment_amount' value='"+sales_order_payment_amount+"'></td>"+
							"<td><div onclick='deletePaymentRow(this)' data-id='"+id+"' id='payment_item'><div align='center' class='action_table_menu_Product' style='margin-top: 6px;'><a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deletePayment'></a></div></div></td></tr>");

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

	
	<!--Save New Sales Order-->
	$("#update-sales-order").click(function(event){

			event.preventDefault();
			
					/*Reset Warnings*/
					$('#client_idxError').text('');

			document.getElementById('UpdateSalesOrderformUpdate').className = "g-3 needs-validation was-validated";

			let sales_order_id			= document.getElementById("update-sales-order").value;
			
			//let client_idx 				= $("#update_client_idx").val();
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
			//let mode_of_payment 		= $("input[name=update_mode_of_payment]").val();
			//let date_of_payment 		= $("input[name=update_date_of_payment]").val();
			//let reference_no 			= $("input[name=update_reference_no]").val();
			//let payment_amount 			= $("input[name=update_payment_amount]").val();
			
			let sales_order_net_percentage 	= $("input[name=update_sales_order_net_percentage]").val();
			let sales_order_less_percentage = $("input[name=update_sales_order_less_percentage]").val();
			
				var product_idx = [];
				var order_quantity = [];
				var product_manual_price = [];
				var sales_order_product_item_id = [];  
				
				  $('.update_product_idx').each(function(){
					if($(this).val() == ''){
						alert('Please Select a Product');
						exit();
					}else{  				  
				   		product_idx.push($(this).val());
					}				  
				  });
				  
				  $('.update_order_quantity').each(function(){
					if($(this).val() == ''){
						alert('Quantity is Empty');
						exit(); 
					}else{  				  
				   		order_quantity.push($(this).val());
					}				  
				  });
				  
				  $('.update_product_manual_price').each(function(){ 				  
				   		product_manual_price.push($(this).val());			  
				  });	

				  $.each($("[id='product_item']"), function(){
					sales_order_product_item_id.push($(this).attr("data-id"));
				  });
				  
				  
				  /*Payment Options*/
				var mode_of_payment = [];
				var date_of_payment = [];
				var reference_no = [];
				var payment_amount = [];
				var payment_item_id = [];
				
				  $('.update_mode_of_payment').each(function(){
					if($(this).val() == ''){
						alert('Please Select a Bank');
						exit();
					}else{  				  
				   		mode_of_payment.push($(this).val());
					}				  
				  });
				  
				  $('.update_date_of_payment').each(function(){
					if($(this).val() == ''){
						alert('Date of Payment is Empty');
						exit(); 
					}else{  				  
				   		date_of_payment.push($(this).val());
					}				  
				  });
				  
				  $('.update_reference_no').each(function(){
					if($(this).val() == ''){
						alert('Reference is Empty');
						exit(); 
					}else{  				  
				   		reference_no.push($(this).val());
					}				  
				  });	
				  
				  $('.update_payment_amount').each(function(){
					if($(this).val() == ''){
						alert('Payment Amount is Empty');
						exit(); 
					}else{  				  
				   		payment_amount.push($(this).val());
					}				  
				  });		
				  
				  $.each($("[id='payment_item']"), function(){
					payment_item_id.push($(this).attr("data-id"));
				  });	
				  
			  $.ajax({
				url: "/update_sales_order_post",
				type:"POST",
				data:{
				  sales_order_id:sales_order_id,
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
					//let salesOrderID = $(this).data('id');
					var query = {
								sales_order_id:sales_order_id,
								_token: "{{ csrf_token() }}"
							}

					var url = "{{URL::to('generate_sales_order_pdf')}}?" + $.param(query)
					window.open(url);
					
				  }
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
	  
	  function sales_update_status(id){
		  
			event.preventDefault();
			var sales_status = document.getElementById("sales_order_status_"+id).value;
		
			  $.ajax({
				url: "/update_sales_status",
				type:"POST",
				data:{
				  sales_order_id:id,
				  sales_status:sales_status,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
							
				  console.log(response);
				  if(response!='') {

				  }else{
							/*No Result Found or Error*/	
				  }
				},
				error: function(error) {
				 console.log(error);	 
				}
			   });
		  
	  }	    
 </script>
	