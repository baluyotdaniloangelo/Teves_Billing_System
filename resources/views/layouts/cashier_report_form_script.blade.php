   <script type="text/javascript">

	
	<!--Save New Client->
	$("#update-cashiers-report").click(function(event){
			
			event.preventDefault();
			
			/*Reset Warnings*/
				$('#teves_branchError').text('');
				$('#forecourt_attendantError').text('');
				$('#report_dateError').text('');

			document.getElementById('CashierReportformNew').className = "g-3 needs-validation was-validated";
			
			let CashiersReportId 			= {{ $CashiersReportId }};
			
			let teves_branch 				= $("#teves_branch").val();
			let forecourt_attendant 		= $("input[name=forecourt_attendant]").val();
			let report_date 				= $("input[name=report_date]").val();
			let shift 						= $("input[name=shift]").val();
			
			/*Call Function for Product Cahier's Report*/
			
			

			
			  $.ajax({
				url: "/update_cashier_report_post",
				type:"POST",
				data:{
				  CashiersReportId:CashiersReportId,
				  teves_branch:teves_branch,
				  forecourt_attendant:forecourt_attendant,
				  report_date:report_date,
				  shift:shift,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					$('#switch_notice_on').show();
					$('#sw_on').html(response.success);
					setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
					
					$('#teves_branchError').text('');
					$('#forecourt_attendantError').text('');
					$('#report_dateError').text('');
					
					document.getElementById("CashierReportformNew").reset();
					
					document.getElementById('CashierReportformNew').className = "g-3 needs-validation";
					
					/*Refresh Table*/
					//var table = $("#getCashierReport").DataTable();
				    //table.ajax.reload(null, false);
				  
					//cashier_report_id = response.cashiers_report_id;
					//var query = {
					//	cashier_report_id:cashier_report_id,
					//	_token: "{{ csrf_token() }}"
					//}
					
					/*Open Cashier's Report*/
					//var url = "{{URL::to('cashiers_report_form')}}";
					//window.location.href = url+'/'+cashier_report_id;
				  
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				 if(error.responseJSON.errors.teves_branch=="The client name has already been taken."){
							  
				  $('#teves_branchError').html("<b>"+ teves_branch +"</b> has already been taken.");
				  document.getElementById('teves_branchError').className = "invalid-feedback";
				  document.getElementById('teves_branch').className = "form-control is-invalid";
				  $('#teves_branch').val("");
				  
				}else{
				  $('#teves_branchError').text(error.responseJSON.errors.teves_branch);
				  document.getElementById('teves_branchError').className = "invalid-feedback";
				}
				
				  $('#forecourt_attendantError').text(error.responseJSON.errors.forecourt_attendant);
				  document.getElementById('forecourt_attendantError').className = "invalid-feedback";	
				  
				  $('#report_dateError').text(error.responseJSON.errors.report_date);
				  document.getElementById('report_dateError').className = "invalid-feedback";			

				$('#switch_notice_off').show();
				$('#sw_off').html("Invalid Input");
				setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);
				  
				}
			   });		
	  });

	function AddProductRow() {
		
		var x = document.getElementById("table_product_body_data").rows.length;
		/*Limit to 5 rows*/
		
		if(x > 50){
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
			"<td class='beginning_reading_td' align='center'><input type='number' class='form-control beginning_reading' id='beginning_reading' name='beginning_reading' ></td>"+
			"<td class='closing_reading_td' align='center'><input type='number' class='form-control closing_reading' id='closing_reading' name='closing_reading' ></td>"+
			"<td class='calibration_td' align='center'><input type='number' class='form-control calibration' id='calibration' name='calibration' ></td>"+
			"<td class='order_quantity_td' align='center'><input type='text' class='form-control order_quantity' placeholder='0.00' aria-label='' name='order_quantity' id='order_quantity' value=''></td>"+
			"<td class='manual_price_td' align='center'><input type='text' class='form-control product_manual_price' placeholder='0.00' aria-label='' name='product_manual_price' id='product_manual_price' value=''></td>"+
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
				//url: "/delete_sales_order_item",
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


	function AddProductOtherSalesRow() {
		
		var x = document.getElementById("table_product_data_other_sales").rows.length;
		/*Limit to 5 rows*/
		
		if(x > 5){
		   return;
		}else{
		
			$('#table_product_data_other_sales tr:last').after("<tr>"+
			"<td class='product_data_other_sales_td' align='center'>"+
			"<input class='form-control' list='product_data_other_sales' name='product_idx_other_sales' id='product_idx_other_sales' required autocomplete='off'>"+
			"<datalist id='product_data_other_sales'>"+
			<?php foreach ($product_data as $product_data_cols){ ?>
			"<span style='font-family: DejaVu Sans; sans-serif;'><option label='<?php echo $product_data_cols->product_price; ?> | <?php echo $product_data_cols->product_name; ?>' data-id='<?php echo $product_data_cols->product_id; ?>' data-price='<?php echo $product_data_cols->product_price; ?>' value='<?php echo $product_data_cols->product_name; ?>'></option></span>"+
			<?php } ?>
			"</datalist>"+
			"</td>"+
			"<td class='quantity_td' align='center'><input type='number' class='form-control order_quantity' id='order_quantity' name='order_quantity' ></td>"+
			"<td class='manual_price_td' align='center'><input type='text' class='form-control product_manual_price' placeholder='0.00' aria-label='' name='product_manual_price' id='product_manual_price' value=''></td>"+
			"<td><div onclick='deleteRowOtherSales(this)' data-id='0' id='product_item'><div align='center' class='action_table_menu_Product' style='margin-top: 6px;'><a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deleteReceivables'></a></div></div></td></tr>");
		
		}	
	  }
	  
	 	function deleteRowOtherSales(btn) {
			
		var productitemID= $(btn).data("id");			
		
		var row = btn.parentNode.parentNode;
		row.parentNode.removeChild(row);
		
		if(productitemID!=0){
			/*Delete the Selected Item*/
			
			  $.ajax({
				//url: "/delete_sales_order_item",
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
	  
	<!--client Confirmed For Deletion-->
	$('body').on('click','#deleteClientConfirmed',function(){
			
			event.preventDefault();

			let clientID = document.getElementById("deleteClientConfirmed").value;
			
			  $.ajax({
				url: "/delete_client_confirmed",
				type:"POST",
				data:{
				  clientID:clientID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					$('#switch_notice_off').show();
					$('#sw_off').html("Client Deleted");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);	
					/*
					If you are using server side datatable, then you can use ajax.reload() 
					function to reload the datatable and pass the true or false as a parameter for refresh paging.
					*/
					
					var table = $("#getclientList").DataTable();
				    table.ajax.reload(null, false);
					
				  }
				},
				error: function(error) {
				 console.log(error);
				}
			   });		
	});

</script>