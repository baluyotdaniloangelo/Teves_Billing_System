   <script type="text/javascript">
	<!--Load Table-->
	$(function () {

		var BillingListTable = $('#getBillingTransactionList').DataTable({
			"language": {
						 "decimal": ".",
            "thousands": ",",
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
			ajax: "{{ route('getBillingTransactionList') }}",
			columns: [
					{data: 'DT_RowIndex', name: 'DT_RowIndex' , orderable: false, searchable: false},
					{data: 'order_date'},
					{data: 'order_time'},  					
					{data: 'drivers_name'},     
					{data: 'order_po_number'},     
					{data: 'plate_no'},     
					{data: 'product_name'}, 
					{data: 'product_price', render: $.fn.dataTable.render.number( ',', '.', 2, '' ) }, 					
					{data: 'quantity_measurement', name: 'quantity_measurement', orderable: true, searchable: true},
					//{data: 'order_total_amount'},  
					{ data: "order_total_amount", render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },

					{data: 'action', name: 'action', orderable: false, searchable: false},
			],
			columnDefs: [
					{ className: 'text-center', targets: [0, 1, 5, 6, 7, 8, 9, 10] },
					 { type: 'numeric-comma', targets: [8,9] }
			]
		});
				$('<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">'+
				'<button type="button" class="btn btn-success new_item bi bi-plus-circle" data-bs-toggle="modal" data-bs-target="#CreateBillingModal"></button>'+
				'</div>').appendTo('#billing_option');
	});
	
	<!--Save New Billing-->
	$("#save-billing-transaction").click(function(event){
		
			event.preventDefault();
			
					/*Reset Warnings*/
					$('#order_dateError').text('');
					$('#order_timeError').text('');
					$('#order_po_numberError').text('');				  
					$('#client_idxError').text('');
					$('#plate_noError').text('');
					$('#drivers_nameError').text('');
					$('#product_idxError').text('');
					$('#product_manual_priceError').text('');
					$('#order_quantityError').text('');

			document.getElementById('BillingformNew').className = "g-3 needs-validation was-validated";

			let order_date 				= $("input[name=order_date]").val();
			let order_time 				= $("input[name=order_time]").val();
			let order_po_number 		= $("input[name=order_po_number]").val();
			let client_idx 				= $("#client_idx").val();
			let plate_no 				= $("input[name=plate_no]").val();
			let drivers_name 			= $("input[name=drivers_name]").val();
			let product_idx 			= $("#product_idx").val();
			let product_manual_price 	= $("#product_manual_price").val();
			let order_quantity 			= $("input[name=order_quantity]").val();
			
			  $.ajax({
				url: "/create_bill_post",
				type:"POST",
				data:{
				  order_date:order_date,
				  order_time:order_time,
				  order_po_number:order_po_number,
				  client_idx:client_idx,
				  plate_no:plate_no,
				  drivers_name:drivers_name,
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
					
					var table = $("#getBillingTransactionList").DataTable();
				    table.ajax.reload(null, false);
					
				  }
				},
				error: function(error) {
					
				 console.log(error);	
				 
				  $('#order_dateError').text(error.responseJSON.errors.order_date);
				  document.getElementById('order_dateError').className = "invalid-feedback";
				  			  
				  $('#order_timeError').text(error.responseJSON.errors.order_time);
				  document.getElementById('order_timeError').className = "invalid-feedback";		

				  $('#order_po_numberError').text(error.responseJSON.errors.order_po_number);
				  document.getElementById('order_po_numberError').className = "invalid-feedback";		
				
				  $('#client_idxError').text(error.responseJSON.errors.client_idx);
				  document.getElementById('client_idxError').className = "invalid-feedback";				
				  
				  $('#plate_noError').text(error.responseJSON.errors.plate_no);
				  document.getElementById('plate_noError').className = "invalid-feedback";				
				 
				  $('#drivers_nameError').text(error.responseJSON.errors.drivers_name);
				  document.getElementById('drivers_nameError').className = "invalid-feedback";				
				  
				  $('#product_idxError').text(error.responseJSON.errors.product_idx);
				  document.getElementById('product_idxError').className = "invalid-feedback";	  
				 
 				 // $('#TestError').text('g');
				 // document.getElementById('order_quantityError').className = "invalid-feedback";
					
				  $('#order_quantityError').text(error.responseJSON.errors.order_quantity);
				  document.getElementById('order_quantityError').className = "invalid-feedback";					
				//alert('m');				
								
				$('#switch_notice_off').show();
				$('#sw_off').html("Invalid Input");
				setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);			  	  
				  
				}
			   });	
	  });

	<!--Select Bill For Update-->
	$('body').on('click','#editBill',function(){
			
			event.preventDefault();
			let billID = $(this).data('id');
			
			  $.ajax({
				url: "/bill_info",
				type:"POST",
				data:{
				  billID:billID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("update-billing-transaction").value = billID;
					
					/*Set Details*/
					document.getElementById("update_order_date").value = response[0].order_date;
					document.getElementById("update_order_time").value = response[0].order_time;
					document.getElementById("update_order_po_number").value = response[0].order_po_number;
					document.getElementById("update_client_idx").value = response[0].client_idx;
					
					document.getElementById("update_plate_no").value = response[0].plate_no;
					document.getElementById("update_product_idx").value = response[0].product_idx;
					document.getElementById("update_product_manual_price").value = response[0].product_price;
					document.getElementById("update_drivers_name").value = response[0].drivers_name;
					document.getElementById("update_order_quantity").value = response[0].order_quantity;
										
					$('#UpdateBillingModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });

	$("#update-billing-transaction").click(function(event){			
			event.preventDefault();
			
					/*Reset Warnings*/
					$('#order_dateError').text('');
					$('#order_timeError').text('');
					$('#order_po_numberError').text('');				  
					$('#client_idxError').text('');
					$('#plate_noError').text('');
					$('#drivers_nameError').text('');
					$('#product_idxError').text('');
					$('#update_product_manual_priceError').text('');
					$('#order_quantityError').text('');

			document.getElementById('BillingformEdit').className = "g-3 needs-validation was-validated";
			
			let billID 							= document.getElementById("update-billing-transaction").value;
			let order_date 						= $("input[name=update_order_date]").val();
			let order_time 						= $("input[name=update_order_time]").val();
			let order_po_number 				= $("input[name=update_order_po_number]").val();
			let client_idx 						= $("#update_client_idx").val();
			let plate_no 						= $("input[name=update_plate_no]").val();
			let drivers_name 					= $("input[name=update_drivers_name]").val();
			let product_idx 					= $("#update_product_idx").val();
			let update_product_manual_price 	= $("#update_product_manual_price").val();
			let order_quantity 					= $("input[name=update_order_quantity]").val();
			
			  $.ajax({
				url: "/update_bill_post",
				type:"POST",
				data:{
				  billID:billID,
				  order_date:order_date,
				  order_time:order_time,
				  order_po_number:order_po_number,
				  client_idx:client_idx,
				  plate_no:plate_no,
				  drivers_name:drivers_name,
				  product_idx:product_idx,
				  product_manual_price:update_product_manual_price,
				  order_quantity:order_quantity,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
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
					
					var table = $("#getBillingTransactionList").DataTable();
				    table.ajax.reload(null, false);
					
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				  $('#update_order_dateError').text(error.responseJSON.errors.order_date);
				  document.getElementById('update_order_dateError').className = "invalid-feedback";
				  			  
				  $('#update_order_timeError').text(error.responseJSON.errors.order_time);
				  document.getElementById('update_order_timeError').className = "invalid-feedback";		

				  $('#update_order_po_numberError').text(error.responseJSON.errors.order_po_number);
				  document.getElementById('update_order_po_numberError').className = "invalid-feedback";		
				
				  $('#update_client_idxError').text(error.responseJSON.errors.client_idx);
				  document.getElementById('update_client_idxError').className = "invalid-feedback";				
				  
				  $('#update_plate_noError').text(error.responseJSON.errors.plate_no);
				  document.getElementById('update_plate_noError').className = "invalid-feedback";				
				 
				  $('#update_drivers_nameError').text(error.responseJSON.errors.drivers_name);
				  document.getElementById('update_drivers_nameError').className = "invalid-feedback";				
				  
				  $('#update_product_idxError').text(error.responseJSON.errors.product_idx);
				  document.getElementById('update_product_idxError').className = "invalid-feedback";				  
				 
 				  $('#update_order_quantityError').text(error.responseJSON.errors.order_quantity);
				  document.getElementById('update_order_quantityError').className = "invalid-feedback";
				
				
				$('#switch_notice_off').show();
				$('#sw_off').html("Invalid Input");
				setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);			  	  
				  
				}
			   });	
	  });
	  
	<!--Bill Deletion Confirmation-->
	$('body').on('click','#deleteBill',function(){
			
			event.preventDefault();
			let billID = $(this).data('id');
			
			  $.ajax({
				url: "/bill_info",
				type:"POST",
				data:{
				  billID:billID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("deleteBillConfirmed").value = billID;
					
					/*Set Details*/
					$('#bill_delete_order_date').text(response[0].order_date);
					$('#bill_delete_order_time').text(response[0].order_time);
					$('#bill_delete_order_po_number').text(response[0].order_po_number);
					$('#bill_delete_client_name').text(response[0].client_name);
					$('#bill_delete_plate_no').text(response[0].plate_no);
					$('#bill_delete_product_name').text(response[0].product_name);
					$('#bill_delete_drivers_name').text(response[0].drivers_name);
					$('#bill_delete_order_quantity').text(response[0].order_quantity);					
					$('#bill_delete_order_total_amount').text(response[0].order_total_amount);

					/*Set Details Confirmed
					$('#bill_delete_confirmed_order_date').text(response[0].order_date);
					$('#bill_delete_confirmed_order_time').text(response[0].order_time);
					$('#bill_delete_confirmed_order_po_number').text(response[0].order_po_number);
					$('#bill_delete_confirmed_client_name').text(response[0].client_name);
					$('#bill_delete_confirmed_plate_no').text(response[0].plate_no);
					$('#bill_delete_confirmed_product_name').text(response[0].product_name);
					$('#bill_delete_confirmed_drivers_name').text(response[0].drivers_name);
					$('#bill_delete_confirmed_order_quantity').text(response[0].order_quantity);					
					$('#bill_delete_confirmed_order_total_amount').text(response[0].order_total_amount);
					*/
					$('#BillDeleteModal').modal('toggle');									  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });		
	  });

	  <!--Site Confirmed For Deletion-->
	  $('body').on('click','#deleteBillConfirmed',function(){
			
			event.preventDefault();

			let billID = document.getElementById("deleteBillConfirmed").value;
			
			  $.ajax({
				url: "/delete_bill_confirmed",
				type:"POST",
				data:{
				  billID:billID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					$('#switch_notice_off').show();
					$('#sw_off').html("Bill Deleted");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);	
					
					/*
					If you are using server side datatable, then you can use ajax.reload() 
					function to reload the datatable and pass the true or false as a parameter for refresh paging.
					*/
					
					var table = $("#getBillingTransactionList").DataTable();
				    table.ajax.reload(null, false);
					
				  }
				},
				error: function(error) {
				 console.log(error);
					//alert(error);
				}
			   });		
	  });
  </script>
	