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
					{data: 'control_number'},
					{data: 'client_name'},   
					{data: 'dr_number'},
					{data: 'or_number'},
					{data: 'payment_term'},
					{data: 'total_due'},
					{data: 'action', name: 'action', orderable: false, searchable: false},
			],
			columnDefs: [
					{ className: 'text-center', targets: [0, 1, 2, 3, 4] },
			]
		});
				/**/
				$('<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">'+
				'<button type="button" class="btn btn-success new_item bi bi-plus-circle" data-bs-toggle="modal" data-bs-target="#CreateSalesOrderModal"></button>'+
				'</div>').appendTo('#sales_order_option');
				
	});
	
	
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
			"<td><div onclick='deleteRow(this)'>Delete Row</div></td></tr>");
		
		}	
	  }

	function deleteRow(btn) {
	  var row = btn.parentNode.parentNode;
	  row.parentNode.removeChild(row);
	}
	
	<!--Save New Billing-->
	$("#save-sales-order").click(function(event){

			event.preventDefault();
			
					/*Reset Warnings*/
					$('#client_idxError').text('');

			document.getElementById('SalesOrderformNew').className = "g-3 needs-validation was-validated";

			let client_idx 				= $("#client_idx").val();
			let sales_order_date 		= $("input[name=sales_order_date]").val();
			let delivered_to 			= $("input[name=delivered_to]").val();
			let delivered_to_address 	= $("input[name=delivered_to_address]").val();
			let dr_number 				= $("input[name=dr_number]").val();
			let or_number 				= $("input[name=or_number]").val();
			let payment_term 			= $("input[name=payment_term]").val();
			let delivery_method 		= $("input[name=delivery_method]").val();
			let hauler 					= $("input[name=hauler]").val();
			let required_date 			= $("input[name=required_date]").val();
			let instructions 			= $("input[name=instructions]").val();
			let note 					= $("input[name=note]").val();
			let mode_of_payment 		= $("input[name=mode_of_payment]").val();
			let date_of_payment 		= $("input[name=date_of_payment]").val();
			let reference_no 			= $("input[name=reference_no]").val();
			let payment_amount 			= $("input[name=payment_amount]").val();
			
				var product_idx = [];
				var order_quantity = [];
				var product_manual_price = [];
				  
				  $('.product_idx').each(function(){
					if($(this).val() == ''){
						alert('Please Select a client');
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
				 
			  $.ajax({
				url: "/create_sales_order_post",
				type:"POST",
				data:{
				  client_idx:client_idx,
				  sales_order_date:sales_order_date,
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
					
					//var table = $("#getBillingTransactionList").DataTable();
					//table.ajax.reload(null, false);
					
				  }
				},
				error: function(error) {
					
				 console.log(error);	
				 
				  $('#client_idxError').text(error.responseJSON.errors.client_idx);
				  document.getElementById('client_idxError').className = "invalid-feedback";
				  			  		
								
				$('#switch_notice_off').show();
				$('#sw_off').html("Invalid Input");
				setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);			  	  
				  
				}
			   });	
	  });
	
	<!--Select Receivable For Update-->	
	$('body').on('click','#editReceivables',function(){
			
			event.preventDefault();
			let ReceivableID = $(this).data('id');
			
			  $.ajax({
				url: "/receivable_info",
				type:"POST",
				data:{
				  receivable_id:ReceivableID,
				  
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("update-receivables").value = ReceivableID;
					
					/*Set Details*/
							
					document.getElementById("client_name_receivables").innerHTML = response[0].client_name;
					document.getElementById("client_address_receivables").innerHTML = response[0].client_address;
					document.getElementById("control_no_receivables").innerHTML = response[0].control_number;	
					document.getElementById("billing_receivables").innerHTML = response[0].billing_date;					
					document.getElementById("client_tin_receivables").innerHTML = response[0].client_tin;
					document.getElementById("amount_receivables").innerHTML = response[0].receivable_amount;
					
					document.getElementById("billing_date").value = response[0].billing_date;
					document.getElementById("or_number").value = response[0].or_number;
					document.getElementById("payment_term").value = response[0].payment_term;
					document.getElementById("receivable_description").textContent = response[0].receivable_description;
					
					$('#UpdateReceivablesModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });


	$("#update-receivables").click(function(event){			
			event.preventDefault();
			
					/*Reset Warnings*/
					$('#update_Receivable_nameError').text('');
					$('#update_Receivable_priceError').text('');

			document.getElementById('ReceivableformEdit').className = "g-3 needs-validation was-validated";
			
			let ReceivableID 			= document.getElementById("update-receivables").value;
			let billing_date 			= $("input[name=billing_date]").val();	
			let or_number 				= $("input[name=or_number]").val();				
			let payment_term 			= $("input[name=payment_term]").val();
			let receivable_description 	= $("#receivable_description").val();
			let receivable_status 		= $("#receivable_status").val();
			
			  $.ajax({
				url: "/update_receivables_post",
				type:"POST",
				data:{
				  ReceivableID:ReceivableID,
				  billing_date:billing_date,
				  or_number:or_number,
				  payment_term:payment_term,
				  receivable_description:receivable_description,
				  receivable_status:receivable_status,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					$('#update_Receivable_nameError').text('');	
					$('#update_Receivable_priceError').text('');
					
					$('#switch_notice_on').show();
					$('#sw_on').html(response.success);
					setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
					
					/*Close form*/
					$('#UpdateReceivablesModal').modal('toggle');
					
					/*Refresh Table*/
					var table = $("#getReceivablesList").DataTable();
				    table.ajax.reload(null, false);
				  
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				$('#or_numberError').text(error.responseJSON.errors.product_price);
				document.getElementById('or_numberError').className = "invalid-feedback";	
				
				$('#payment_termError').text(error.responseJSON.errors.product_price);
				document.getElementById('payment_termError').className = "invalid-feedback";	
				
				$('#receivable_descriptionError').text(error.responseJSON.errors.product_price);
				document.getElementById('receivable_descriptionError').className = "invalid-feedback";					
				
				$('#switch_notice_off').show();
				$('#sw_off').html("Invalid Input");
				setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);				  	  
				  
				}
			   });
		
	  });
	  
	<!--Receivable Deletion Confirmation-->
	$('body').on('click','#deleteReceivables',function(){
			
			event.preventDefault();
			let ReceivableID = $(this).data('id');
			
			  $.ajax({
				url: "/receivable_info",
				type:"POST",
				data:{
				  receivable_id:ReceivableID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("deleteReceivableConfirmed").value = ReceivableID;
					
					/*Set Details*/
					document.getElementById("confirm_delete_billing_date").value = response[0].billing_date;
					document.getElementById("confirm_delete_control_number").innerHTML = response[0].control_number;
					document.getElementById("confirm_delete_or_no").value = response[0].or_number;	
					document.getElementById("confirm_delete_client_info").innerHTML = response[0].client_name;
					document.getElementById("confirm_delete_description").textContent = response[0].receivable_description;
					document.getElementById("confirm_delete_amount").innerHTML = response[0].receivable_amount;
					
					$('#confirm_delete_Receivable_name').text(response.Receivable_name);
					$('#confirm_delete_Receivable_price').text(response.Receivable_price);
					
					$('#ReceivableDeleteModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });		
	  });

	  <!--Receivable Confirmed For Deletion-->
	  $('body').on('click','#deleteReceivableConfirmed',function(){
			
			event.preventDefault();

			let ReceivableID = document.getElementById("deleteReceivableConfirmed").value;
			
			  $.ajax({
				url: "/delete_receivable_confirmed",
				type:"POST",
				data:{
				  receivable_id:ReceivableID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					$('#switch_notice_off').show();
					$('#sw_off').html("Receivable Deleted");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);	
					
					/*
					If you are using server side datatable, then you can use ajax.reload() 
					function to reload the datatable and pass the true or false as a parameter for refresh paging.
					*/
					
					var table = $("#getReceivablesList").DataTable();
				    table.ajax.reload(null, false);
					
				  }
				},
				error: function(error) {
				 console.log(error);
				}
			   });		
	  });
	  
	  /*Re-print*/
	  $('body').on('click','#PrintReceivables',function(){	  
	  
			let ReceivableID = $(this).data('id');
			var query = {
						receivable_id:ReceivableID,
						_token: "{{ csrf_token() }}"
					}

			var url = "{{URL::to('generate_receivable_pdf')}}?" + $.param(query)
			window.open(url);
	  
	  });
  </script>
	