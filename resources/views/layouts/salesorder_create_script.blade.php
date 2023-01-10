   <script type="text/javascript">
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
			"<td><div onclick='deleteRow(this)'><div align='center' class='action_table_menu_Product' style='margin-top: 6px;'><a href='#' data-id='3' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deleteReceivables'></a></div></div></td></tr>");
		
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
	
	
  </script>
	