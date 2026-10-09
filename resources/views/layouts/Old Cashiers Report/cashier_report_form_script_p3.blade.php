   <script type="text/javascript">
	/*Part 3*/
    function input_settings_create_PH3(){

    var miscellaneous_items_type 	= $("#miscellaneous_items_type_PH3").val();

        if(miscellaneous_items_type == 'DISCOUNTS'){

            document.getElementById("reference_no_PH3").disabled = false;
            document.getElementById("product_manual_price_PH3").disabled = false;
			document.getElementById("product_idx_PH3").disabled = false;
			document.getElementById("order_quantity_PH3").disabled = false;
			
            document.getElementById("quantity_label").innerHTML = "LITERS";
            document.getElementById("manual_price_label").innerHTML = "UNIT PRICE";
           

        }else if(miscellaneous_items_type == 'SALES_CREDIT'){

            document.getElementById("reference_no_PH3").disabled = false;
            document.getElementById("product_manual_price_PH3").disabled = false;
			document.getElementById("product_idx_PH3").disabled = false;
			document.getElementById("order_quantity_PH3").disabled = false;
			
            document.getElementById("quantity_label").innerHTML = "QUANTITY";
            document.getElementById("manual_price_label").innerHTML = "AMOUNT";

        }else{

            document.getElementById("reference_no_PH3").disabled = false;
            document.getElementById("product_manual_price_PH3").disabled = false;
			document.getElementById("product_idx_PH3").disabled = false;
			document.getElementById("order_quantity_PH3").disabled = false;
			
            document.getElementById("quantity_label").innerHTML = "LITERS/PCS";
            document.getElementById("manual_price_label").innerHTML = "AMOUNT";
			
			/*Clear*/
			document.getElementById("product_idx_PH3").value = '';

        }
        

    }

    function update_input_settings_create_PH3(){

    var miscellaneous_items_type 	= $("#update_miscellaneous_items_type_PH3").val();

        if(miscellaneous_items_type == 'DISCOUNTS'){

            document.getElementById("update_reference_no_PH3").disabled = false;
            document.getElementById("update_product_manual_price_PH3").disabled = false;
			document.getElementById("update_product_idx_PH3").disabled = false;
			document.getElementById("update_order_quantity_PH3").disabled = false;
			
            document.getElementById("update_quantity_label").innerHTML = "LITERS";
            document.getElementById("update_manual_price_label").innerHTML = "UNIT PRICE";
           

        }else if(miscellaneous_items_type == 'SALES_CREDIT'){
			
            document.getElementById("update_reference_no_PH3").disabled = false;
            document.getElementById("update_product_manual_price_PH3").disabled = false;
			document.getElementById("update_product_idx_PH3").disabled = false;
			document.getElementById("update_order_quantity_PH3").disabled = false;
			
            document.getElementById("update_quantity_label").innerHTML = "QUANTITY";
            document.getElementById("update_manual_price_label").innerHTML = "AMOUNT";

		}else{

            document.getElementById("update_reference_no_PH3").disabled = false;
            document.getElementById("update_product_manual_price_PH3").disabled = false;
			document.getElementById("update_product_idx_PH3").disabled = false;
			document.getElementById("update_order_quantity_PH3").disabled = false;
			
            document.getElementById("update_quantity_label").innerHTML = "LITERS/PCS";
            document.getElementById("update_manual_price_label").innerHTML = "AMOUNT";
			
			/*Clear*/
			//document.getElementById("update_product_idx_PH3").value = '';

        }
    }


	function load_so_reference_no(submit_mode) {		
	
		
		if(submit_mode == 0){
			
			var client_idx = $('#sold_to_client_name_list option[value="' + $('#sold_to_client_id').val() + '"]').attr('data-id');
			
		}else{
			
			var client_idx = $('#update_sold_to_client_name_list option[value="' + $('#update_sold_to_client_id').val() + '"]').attr('data-id');
			
		}
		
	LoadSellingPriceList(client_idx);
		
		let teves_branch 				= $("#teves_branch").val();
		
		$("#so_list_reference option").remove();
		$('<option style="display: none;"></option>').appendTo('#so_list_reference');
		
			  $.ajax({
				url: "{{ route('so_reference_list') }}",
				type:"POST",
				data:{
				  client_idx:client_idx,
				  teves_branch:teves_branch,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){						
				  console.log(response);
				  if(response!='') {	

						var len = response.length;
						for(var i=0; i<len; i++){
						
							var so_id = response[i].so_id;		
							var so_number = response[i].so_number;
	
							$('#so_list_reference option:last').after("<option label='"+so_number+"' data-id='"+so_id+"' value='"+so_number+"' data-price='"+so_number+"' >");	
						
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


	$("#save-CRPH3").click(function(event){
		
			event.preventDefault();
			
			$('#product_idx_PH3Error').text('');
			$('#order_quantity_PH3Error').text('');
			$('#product_manual_price_PH3Error').text('');
			
			let CashiersReportId 			= {{ $CashiersReportId }};			

            var miscellaneous_items_type 	= $("#miscellaneous_items_type_PH3").val();
			
            var reference_no 			    = $("input[name=reference_no_PH3]").val();
			var reference_no_id 			= $('#so_list_reference option[value="' + $('#reference_no_PH3').val() + '"]').attr('data-id');/*IF Available*/
			var client_idx 			   		= $('#sold_to_client_name_list option[value="' + $('#sold_to_client_id').val() + '"]').attr('data-id');
			
			var order_time 			    	= $("input[name=order_time_PH3]").val();
			
			/*Product ID*/
			var product_idx 			    = $('#product_list_PH3 option[value="' + $('#product_idx_PH3').val() + '"]').attr('data-id');
			/*Product Name*/
			let product_name 				= $("input[name=product_name_PH3]").val();
			
			var order_quantity 			    = $("input[name=order_quantity_PH3]").val();
			var product_manual_price 	    = $("input[name=product_manual_price_PH3]").val();
			
			let teves_branch 				= $("#teves_branch").val();
			var report_date 			    = $("input[name=report_date]").val();

			document.getElementById('CRPH3_form').className = "g-3 needs-validation was-validated";
			
				/*Delete the Selected Item*/			
				  $.ajax({
					url: "{{ route('SAVE_CHR_PH3') }}",
					type:"POST",
					data:{
					  CHPH3_ID:0,
					  CashiersReportId:CashiersReportId,
					  miscellaneous_items_type:miscellaneous_items_type,
                      reference_no:reference_no,
                      reference_no_id:reference_no_id,
					  client_idx:client_idx,
                      product_idx:product_idx,
					  order_time:order_time,
					  branch_idx:teves_branch,
					  item_description:product_name,
					  order_quantity:order_quantity, 
					  product_manual_price:product_manual_price,
					  report_date:report_date,
					  _token: "{{ csrf_token() }}"
					},
					success:function(response){
					  console.log(response);
					  if(response) {
						  
						$('#switch_notice_on').show();
						$('#sw_on').html(response.success);
						setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
						
						load_so_reference_no(0);
						
						if(miscellaneous_items_type=='SALES_CREDIT'){
							LoadCashiersReportPH3_SALES_CREDIT();
						}
						else if(miscellaneous_items_type=='DISCOUNTS'){
							LoadCashiersReportPH3_DISCOUNT();
						}
						else{
							LoadCashiersReportPH3_OTHERS();	
						}
						
						$('#product_idx_PH3Error').text('');					
						$('#order_quantity_PH3Error').text('');		
						$('#product_manual_price_PH3Error').text('');
						
						/*Clear Form*/
						document.getElementById("reference_no_PH3").value = '';
						document.getElementById("product_idx_PH3").value = '';
						document.getElementById("order_quantity_PH3").value = '';
						document.getElementById("product_manual_price_PH3").value = '';
						UpdateCashiersReportSummary();
						LoadCashiersReportSummary();
						$('#pump_price_txt').html('0');
						$('#discounted_price_txt').html('0');
						$('#TotalAmount_PH3').html('0');
						
					  }
					},
					error: function(error) {
					 console.log(error);
						
							if(error.responseJSON.errors.product_idx=='Item Description or Product is Required'){
								if(product_name==''){
									$('#product_idx_PH3Error').html(error.responseJSON.errors.product_idx);
								}else{
									$('#product_idx_PH3Error').html("Incorrect Product Name <b>" + product_name + "</b>");
								}
								
								document.getElementById("product_idx_PH3").value = "";
								document.getElementById('product_idx_PH3Error').className = "invalid-feedback";
							}
						
							$('#order_quantity_PH3Error').text(error.responseJSON.errors.order_quantity);
							document.getElementById('order_quantity_PH3Error').className = "invalid-feedback";
							
							$('#product_manual_price_PH3Error').text(error.responseJSON.errors.closing_reading);
							document.getElementById('product_manual_price_PH3Error').className = "invalid-feedback";
					}
				   });		
		 });

	function LoadCashiersReportPH3_DISCOUNT() {		
		$("#table_product_data_msc_DISCOUNT tr").remove();
		$('<tr style="display: none;"><td>HIDDEN</td></tr>').appendTo('#table_product_data_msc_DISCOUNT');
		let CashiersReportId 			= {{ $CashiersReportId }};	
			  $.ajax({
				url: "{{ route('GetCashiersProductP3_DISCOUNTS') }}",
				type:"POST",
				data:{
				  CashiersReportId:CashiersReportId,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){						
				  console.log(response);
				  if(response!='') {			  
						var len = response.length;
						for(var i=0; i<len; i++){
							var reference_no = response[i].reference_no;			
							var cashiers_report_p3_id = response[i].cashiers_report_p3_id;						
							var product_idx = response[i].product_idx;						
							var pump_price = response[i].pump_price.toLocaleString("en-PH", {minimumFractionDigits: 2});
							var unit_price = response[i].unit_price.toLocaleString("en-PH", {minimumFractionDigits: 2});
							var discounted_price = response[i].discounted_price.toLocaleString("en-PH", {minimumFractionDigits: 2});
							var product_name = response[i].product_name;
							var order_quantity = response[i].order_quantity;
							var order_total_amount = response[i].order_total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2});
							
							$('#table_product_data_msc_DISCOUNT tr:last').after("<tr>"+
							"<td align='center'>" + (i+1) + "</td>" +
							"<td class='calibration_td' align='center'>"+reference_no+"</td>"+
							"<td class='product_td' align='center'>"+product_name+"</td>"+
							"<td class='calibration_td' align='center'>"+order_quantity+"</td>"+
							"<td class='manual_price_td' align='center'>"+pump_price+"</td>"+
							"<td class='manual_price_td' align='center'>"+unit_price+"</td>"+
							"<td class='manual_price_td' align='center'>"+discounted_price+"</td>"+
							"<td class='manual_price_td' align='center'>"+order_total_amount+"</td>"+
							"<td><div align='center' class='action_table_menu_Product' style='margin-top: 6px;'><a href='#' class='btn-danger btn-circle btn-sm bi-pencil-fill btn_icon_table btn_icon_table_edit' id='CHPH3_Edit_DISCOUNT' data-id='"+cashiers_report_p3_id+"'></a> <a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deleteCashiersProductP3_DISCOUNT'  data-id='"+cashiers_report_p3_id+"'></a></div></td>"+
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
	
	function LoadCashiersReportPH3_SALES_CREDIT() {		
		$("#table_product_data_msc_SALES_CREDIT tr").remove();
		$('<tr style="display: none;"><td>HIDDEN</td></tr>').appendTo('#table_product_data_msc_SALES_CREDIT');
		let CashiersReportId 			= {{ $CashiersReportId }};	
			  $.ajax({
				url: "{{ route('GetCashiersProductP3_SALES_CREDIT') }}",
				type:"POST",
				data:{
				  CashiersReportId:CashiersReportId,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){	
				
				  console.log(response);
				  if(response!='') {			  
						var len = response.length;
						for(var i=0; i<len; i++){
						
							var cashiers_report_p3_id = response[i].cashiers_report_p3_id;						
							var product_idx = response[i].product_idx;						
							var pump_price = response[i].pump_price.toLocaleString("en-PH", {maximumFractionDigits: 2});
							var product_name = response[i].product_name;
							var client_name = response[i].client_name;
							var reference_no = response[i].reference_no;
							var order_quantity = response[i].order_quantity.toLocaleString("en-PH", {maximumFractionDigits: 2});
							
							var order_total_amount = response[i].order_total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2});
							
							$('#table_product_data_msc_SALES_CREDIT tr:last').after("<tr>"+
							"<td align='center'>" + (i+1) + "</td>" +
							"<td align='left'>"+client_name+"</td>"+
							"<td align='left'>"+reference_no+"</td>"+
							"<td align='left'>"+product_name+"</td>"+
							"<td class='calibration_td' align='center'>"+order_quantity+"</td>"+
							"<td class='manual_price_td' align='center'>"+pump_price+"</td>"+
							"<td class='manual_price_td' align='center'>"+order_total_amount+"</td>"+
							"<td><div align='center' class='action_table_menu_Product' style='margin-top: 6px;'><a href='#' class='btn-danger btn-circle btn-sm bi-pencil-fill btn_icon_table btn_icon_table_edit' id='CHPH3_Edit_SALES_CREDIT' data-id='"+cashiers_report_p3_id+"'></a> <a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deleteCashiersProductP3_SALES_CREDIT'  data-id='"+cashiers_report_p3_id+"'></a></div></td>"+
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
	
	function LoadCashiersReportPH3_OTHERS() {		
		$("#table_product_data_msc_OTHERS tr").remove();
		$('<tr style="display: none;"><td>HIDDEN</td></tr>').appendTo('#table_product_data_msc_OTHERS');
		let CashiersReportId 			= {{ $CashiersReportId }};	
			  $.ajax({
				url: "{{ route('GetCashiersProductP3_OTHERS') }}",
				type:"POST",
				data:{
				  CashiersReportId:CashiersReportId,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){						
				  console.log(response);
				  if(response!='') {			  
						var len = response.length;
						for(var i=0; i<len; i++){
							
							var reference_no = response[i].reference_no;		
							var cashiers_report_p3_id = response[i].cashiers_report_p3_id;						
							var product_idx = response[i].product_idx;						
							var unit_price = response[i].unit_price.toLocaleString("en-PH", {maximumFractionDigits: 2});
							var item_description = response[i].item_description;
							var order_quantity = response[i].order_quantity;
							var order_total_amount = response[i].order_total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2});
							
							$('#table_product_data_msc_OTHERS tr:last').after("<tr>"+
							"<td align='center'>" + (i+1) + "</td>" +
							"<td class='calibration_td' align='center'>"+reference_no+"</td>"+
							"<td class='calibration_td' align='center'>"+item_description+"</td>"+
							"<td class='calibration_td' align='center'>"+order_quantity+"</td>"+
							"<td class='manual_price_td' align='center'>"+order_total_amount+"</td>"+
							"<td><div align='center' class='action_table_menu_Product' style='margin-top: 6px;'><a href='#' class='btn-danger btn-circle btn-sm bi-pencil-fill btn_icon_table btn_icon_table_edit' id='CHPH3_Edit_OTHERS' data-id='"+cashiers_report_p3_id+"'></a> <a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deleteCashiersProductP3_OTHERS'  data-id='"+cashiers_report_p3_id+"'></a></div></td>"+
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
    
	$('body').on('click','#CHPH3_Edit_SALES_CREDIT',function(){			
			event.preventDefault();
			let CHPH3_ID = $(this).data('id');			
			
			/*Reset Form*/
			document.getElementById("CRPH3_form_edit").reset();	
			
			$.ajax({
				url: "{{ route('CRP3_info_SALES_CREDIT') }}",
				type:"POST",
				data:{
				  CHPH3_ID:CHPH3_ID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {				
					document.getElementById("update-CRPH3").value = CHPH3_ID;				
					/*Set Details*/					
					
					document.getElementById("update_miscellaneous_items_type_PH3").value 	= response[0].miscellaneous_items_type;
					document.getElementById("update_product_idx_PH3").value 				= response[0].product_name;
					document.getElementById("update_sold_to_client_id").value 				= response[0].client_name;
					document.getElementById("update_order_quantity_PH3").value 				= response[0].order_quantity;
					document.getElementById("update_product_manual_price_PH3").value 		= response[0].unit_price;

					document.getElementById("update_reference_no_PH3").value 				= response[0].reference_no;
					
					let client_idx = response[0].client_idx;
					load_so_reference_no(client_idx);
					
					update_input_settings_create_PH3();
					
					var pump_price = response[0].pump_price;
					$('#pump_price_txt_update').html(pump_price.toLocaleString("en-PH", {maximumFractionDigits: 2}));		
					
					var discounted_price = response[0].discounted_price;
					$('#discounted_price_txt').html(discounted_price.toLocaleString("en-PH", {maximumFractionDigits: 2}));		
					
					var total_amount = response[0].order_total_amount;
					$('#UpdateTotalAmount_PH3').html(total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));		

					//UpdateTotalAmount_PH3();
					
					$('#Update_CRPH3_Modal').modal('toggle');							  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });
	  
	$('body').on('click','#CHPH3_Edit_DISCOUNT',function(){			
			event.preventDefault();
			let CHPH3_ID = $(this).data('id');
			
			/*Reset Form*/
			document.getElementById("CRPH3_form_edit").reset();		
			
			$.ajax({
				url: "{{ route('CRP3_info_DISCOUNT') }}",
				type:"POST",
				data:{
				  CHPH3_ID:CHPH3_ID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {				
					document.getElementById("update-CRPH3").value = CHPH3_ID;				
					/*Set Details*/					
					
					document.getElementById("update_miscellaneous_items_type_PH3").value 	= response[0].miscellaneous_items_type;
					document.getElementById("update_reference_no_PH3").value 				= response[0].reference_no;
					document.getElementById("update_product_idx_PH3").value 				= response[0].product_name;
					document.getElementById("update_order_quantity_PH3").value 				= response[0].order_quantity;
					document.getElementById("update_product_manual_price_PH3").value 		= response[0].unit_price;
					
					
					update_input_settings_create_PH3();
					var total_amount = response[0].order_total_amount;
					$('#UpdateTotalAmount_PH3').html(total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));		

					UpdateTotalAmount_PH3();

					
					$('#Update_CRPH3_Modal').modal('toggle');							  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });
	  	  
	$('body').on('click','#CHPH3_Edit_OTHERS',function(){			
			event.preventDefault();
			let CHPH3_ID = $(this).data('id');

			/*Reset Form*/
			document.getElementById("CRPH3_form_edit").reset();
			
			$.ajax({
				url: "{{ route('CRP3_info_OTHERS') }}",
				type:"POST",
				data:{
				  CHPH3_ID:CHPH3_ID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {				
					document.getElementById("update-CRPH3").value = CHPH3_ID;				
					/*Set Details*/					
					
					document.getElementById("update_miscellaneous_items_type_PH3").value 	= response[0].miscellaneous_items_type;
					document.getElementById("update_reference_no_PH3").value 				= response[0].reference_no;
					
					document.getElementById("update_product_idx_PH3").value = response[0].item_description;
					
					document.getElementById("update_order_quantity_PH3").value 				= response[0].order_quantity;
					document.getElementById("update_product_manual_price_PH3").value 		= response[0].unit_price;
					
					update_input_settings_create_PH3();
					$('#UpdateTotalAmount_PH3').html('0');		

					UpdateTotalAmount_PH3();
					$('#Update_CRPH3_Modal').modal('toggle');		
					
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });

	$("#update-CRPH3").click(function(event){
		
			event.preventDefault();
			
			$('#update_product_idx_PH3Error').text('');
			$('#update_order_quantity_PH3Error').text('');
			$('#update_product_manual_price_PH3Error').text('');
			
			let CashiersReportId 			= {{ $CashiersReportId }};			
			let CHPH3_ID 					= document.getElementById("update-CRPH3").value;
			var miscellaneous_items_type 	= $("#update_miscellaneous_items_type_PH3").val();
			
			var reference_no 			    = $("input[name=update_reference_no_PH3]").val();
			var reference_no_id 			= $('#so_list_reference option[value="' + $('#update_reference_no_PH3').val() + '"]').attr('data-id');/*IF Available*/
			
			var client_idx 			    	= $('#update_sold_to_client_name_list option[value="' + $('#update_sold_to_client_id').val() + '"]').attr('data-id');
			var product_idx 				= $('#product_list_PH3 option[value="' + $('#update_product_idx_PH3').val() + '"]').attr('data-id');
			
			var order_time 			    	= $("input[name=update_order_time_PH3]").val();
			
			/*Product Name*/
			let product_name 				= $("input[name=update_product_name_PH3]").val();
			
			var order_quantity 				= $("input[name=update_order_quantity_PH3]").val();
			var product_manual_price 		= $("input[name=update_product_manual_price_PH3]").val();
			var report_date 			    = $("input[name=report_date]").val();
			
			document.getElementById('CRPH3_form_edit').className = "g-3 needs-validation was-validated";
			
			var _billing_update 			= $('.billing_update:checked').val() || 'off';
			var billing_update 				= (_billing_update ==="on") ? "YES":"NO";
			
			let teves_branch 				= $("#teves_branch").val();
			
				/*Delete the Selected Item*/			
				  $.ajax({
					url: "{{ route('SAVE_CHR_PH3') }}",
					type:"POST",
					data:{
					  CHPH3_ID:CHPH3_ID,
					  CashiersReportId:CashiersReportId,
					  miscellaneous_items_type:miscellaneous_items_type, 
					  reference_no:reference_no, 
					  reference_no_id:reference_no_id,
					  client_idx:client_idx,
					  branch_idx:teves_branch,
					  product_idx:product_idx,
					  order_time:order_time,
					  item_description:product_name,
					  order_quantity:order_quantity, 
					  product_manual_price:product_manual_price, 
					  report_date:report_date,
					  billing_update:billing_update,
					  _token: "{{ csrf_token() }}"
					},
					success:function(response){
					  console.log(response);
					  if(response) {
						  
						$('#switch_notice_on').show();
						$('#sw_on').html(response.success);
						setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
						
						load_so_reference_no(1);
							
						if(miscellaneous_items_type=='SALES_CREDIT'){
							LoadCashiersReportPH3_SALES_CREDIT();
						}
						else if(miscellaneous_items_type=='DISCOUNTS'){
							LoadCashiersReportPH3_DISCOUNT();
						}
						else{
							LoadCashiersReportPH3_OTHERS();	
						}	
						
						UpdateCashiersReportSummary();
						
						$('#update_product_idx_PH3Error').text('');					
						$('#update_order_quantity_PH3Error').text('');		
						$('#update_product_manual_price_PH3Error').text('');
						
						$('#Update_CRPH3_Modal').modal('toggle');
						
					  }
					},
					error: function(error) {
					 console.log(error);
						/*alert(error);*/
							$('#update_product_idx_PH3Error').text(error.responseJSON.errors.product_idx);
							document.getElementById('update_product_idx_PH3Error').className = "invalid-feedback";
							
							$('#update_order_quantity_PH3Error').text(error.responseJSON.errors.order_quantity);
							document.getElementById('update_order_quantity_PH3Error').className = "invalid-feedback";
							
							$('#update_product_manual_price_PH3Error').text(error.responseJSON.errors.closing_reading);
							document.getElementById('update_product_manual_price_PH3Error').className = "invalid-feedback";
					}
				   });		
		 });

	<!--CRPH1 Deletion Confirmation-->	
	$('body').on('click','#deleteCashiersProductP3_SALES_CREDIT',function(){
			event.preventDefault();
			let CHPH3_ID = $(this).data('id');			
			$.ajax({
				url: "{{ route('CRP3_info_SALES_CREDIT') }}",
				type:"POST",
				data:{
				  CHPH3_ID:CHPH3_ID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {				
					document.getElementById("deleteCRPH3Confirmed").value = CHPH3_ID;				
					/*Set Details*/					
					$('#delete_product_idx_PH3').text(response[0].product_name);
					$('#delete_order_quantity_PH3').text(response[0].order_quantity);
					$('#delete_product_manual_price_PH3').text(response[0].product_price);
					
					var total_amount = response[0].order_total_amount;
					$('#delete_TotalAmount_PH3').html(total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));				
					$('#CRPH3DeleteModal').modal('toggle');							  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });
	  
	$('body').on('click','#deleteCashiersProductP3_DISCOUNT',function(){
			event.preventDefault();
			let CHPH3_ID = $(this).data('id');			
			$.ajax({
				url: "{{ route('CRP3_info_DISCOUNT') }}",
				type:"POST",
				data:{
				  CHPH3_ID:CHPH3_ID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {				
					document.getElementById("deleteCRPH3Confirmed_DISCOUNT").value = CHPH3_ID;				
					/*Set Details*/					
					$('#delete_reference_PH3_DISCOUNT').text(response[0].reference_no);
					$('#delete_product_idx_PH3_DISCOUNT').text(response[0].product_name);
					$('#delete_order_quantity_PH3_DISCOUNT').text(response[0].order_quantity);
					$('#delete_product_manual_price_PH3_DISCOUNT').text(response[0].product_price);
					
					var total_amount = response[0].order_total_amount;
					$('#delete_TotalAmount_PH3_DISCOUNT').html(total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));				
					$('#CRPH3DeleteModal_DISCOUNT').modal('toggle');							  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });
	  
	<!--CRPH1 Deletion Confirmation-->	
	$('body').on('click','#deleteCashiersProductP3_OTHERS',function(){
			event.preventDefault();
			let CHPH3_ID = $(this).data('id');			
			$.ajax({
				url: "{{ route('CRP3_info_OTHERS') }}",
				type:"POST",
				data:{
				  CHPH3_ID:CHPH3_ID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {				
					document.getElementById("deleteCRPH3Confirmed_OTHERS").value = CHPH3_ID;				
					/*Set Details*/
					
					$('#delete_reference_PH3_others').text(response[0].reference_no);
					$('#delete_liters_pcs_PH3_others').text(response[0].order_quantity);
					$('#delete_amount_PH3_others').text(response[0].unit_price);			
					
					$('#CRPH3DeleteModal_OTHERS').modal('toggle');							  
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });

	$('body').on('click','#deleteCRPH3Confirmed',function(){
			
		let CHPH3_ID = document.getElementById("deleteCRPH3Confirmed").value;
		let CashiersReportId 			= {{ $CashiersReportId }};
		
		if(CHPH3_ID!=0){
			/*Delete the Selected Item*/	
			  $.ajax({
				url: "{{ route('DeleteCashiersProductP3') }}",
				type:"POST",
				data:{
				  CHPH3_ID:CHPH3_ID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					$('#switch_notice_off').show();
					$('#sw_off').html("Deleted");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);	
					    LoadCashiersReportPH3_SALES_CREDIT();
						LoadCashiersReportPH3_DISCOUNT();
						LoadCashiersReportPH3_OTHERS();
						UpdateCashiersReportSummary();
				  }
				},
				error: function(error) {
				 console.log(error);
				}
			   });		
		}	
	});
	$('body').on('click','#deleteCRPH3Confirmed_OTHERS',function(){
			
		let CHPH3_ID = document.getElementById("deleteCRPH3Confirmed_OTHERS").value;
		
		if(CHPH3_ID!=0){
			/*Delete the Selected Item*/	
			  $.ajax({
				url: "{{ route('DeleteCashiersProductP3') }}",
				type:"POST",
				data:{
				  CHPH3_ID:CHPH3_ID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					$('#switch_notice_off').show();
					$('#sw_off').html("Deleted");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);	
						LoadCashiersReportPH3_OTHERS();
						LoadCashiersReportSummary();
						UpdateCashiersReportSummary();
				  }
				},
				error: function(error) {
				 console.log(error);
				}
			   });		
		}	
	});
	
	$('body').on('click','#deleteCRPH3Confirmed_DISCOUNT',function(){
			
		let CHPH3_ID = document.getElementById("deleteCRPH3Confirmed_DISCOUNT").value;
		
		if(CHPH3_ID!=0){
			/*Delete the Selected Item*/	
			  $.ajax({
				url: "{{ route('DeleteCashiersProductP3') }}",
				type:"POST",
				data:{
				  CHPH3_ID:CHPH3_ID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					$('#switch_notice_off').show();
					$('#sw_off').html("Deleted");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);	
						LoadCashiersReportPH3_DISCOUNT();
						LoadCashiersReportSummary();
						UpdateCashiersReportSummary();
				  }
				},
				error: function(error) {
				 console.log(error);
				}
			   });		
		}	
	});
	
	function TotalAmount_PH3(){
		
		let CashiersReportId 		= {{ $CashiersReportId }};

        let miscellaneous_items_type 	= $("#miscellaneous_items_type_PH3").val();

        
		    let product_id 				= $('#product_list_PH3 option[value="' + $('#product_idx_PH3').val() + '"]').attr('data-id');		
		    let product_price 			= $('#product_list_PH3 option[value="' + $('#product_idx_PH3').val() + '"]').attr('data-price');
	
		    let product_manual_price 	= $("#product_manual_price_PH3").val();		
		    let order_quantity 			= $("input[name=order_quantity_PH3]").val();
		
		    /*GET PUMP PRICE*/
		    event.preventDefault();
			
			    let CHPH1_ID = $(this).data('id');		
				
			    $.ajax({
				    url: "{{ route('CRP1_info') }}",
				    type:"POST",
				    data:{
				      CashiersReportId:CashiersReportId,
				      CHPH1_ID:CHPH1_ID,
				      product_id:product_id,
				      _token: "{{ csrf_token() }}"
				    },
				    success:function(response){
				      console.log(response);
				      if(response) {				
					
					    if(response==''){
						   
						   $('#pump_price_txt').html('0');
						   $('#discounted_price_txt').html('0');
						
						    if(order_quantity!=0 || order_quantity!=''){
							
							    if(product_manual_price!='' && product_manual_price!=0){
								
								    var total_amount = (product_manual_price) * order_quantity;
								    $('#TotalAmount_PH3').html(total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							
							    }else{
								
								    var total_amount = product_price * order_quantity;
								    $('#TotalAmount_PH3').html(total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							
							    }
							
						    }		
						
					    }else{
						
						    var pump_price	= response[0].product_price;
						    
							if(miscellaneous_items_type=='DISCOUNTS'){
								var discounted_price	= response[0].product_price - product_manual_price;
							}else{
								var discounted_price	= 0;
							}
							
						    $('#pump_price_txt').html(pump_price.toLocaleString("en-PH", {maximumFractionDigits: 2}));
						
						    $('#discounted_price_txt').html(discounted_price.toLocaleString("en-PH", {maximumFractionDigits: 2}));
						
						    if(order_quantity!=0 || order_quantity!=''){
							
							    if(product_manual_price!='' && product_manual_price!=0){
								
								    var total_amount = (pump_price - product_manual_price) * order_quantity;
								    $('#TotalAmount_PH3').html(total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							
							    }else{
								
								    var total_amount = pump_price * order_quantity;
								    $('#TotalAmount_PH3').html(total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							
							    }
							
						    }		
				      }
						
				      }
				    },
				    error: function(error) {
				     console.log(error);
					    alert(error);
				    }
			       });	
	}

	function UpdateTotalAmount_PH3(){
	
	event.preventDefault();
	
    let selectedValue = $('#update_product_idx_PH3').val().trim();
    console.log("🧩 Selected input value:", selectedValue);

    // Find matching option from datalist (case-insensitive match just in case)
    let matchedOption = $('#product_list_PH3 option').filter(function () {
        return $(this).val().toLowerCase() === selectedValue.toLowerCase();
    });

    // Log what we found
    console.log("🧩 Matched option:", matchedOption[0]);

    let product_price = matchedOption.attr('data-price');
    let product_id = matchedOption.attr('data-id');

	let product_id2 			= $('#product_list_PH3 option[value="' + $('#update_product_idx_PH3').val() + '"]').attr('data-id');		
	let product_price2 			= $('#product_list_PH3 option[value="' + $('#update_product_idx_PH3').val() + '"]').attr('data-price');

    console.log("✅ product_id:", product_id);
    console.log("✅ product_price:", product_price);

	let miscellaneous_items_type = $("#update_miscellaneous_items_type_PH3").val();
	 
    // If still undefined, show a helpful alert
	if(miscellaneous_items_type=='OTHERS' || miscellaneous_items_type==''){
	}
	else{
		if (!product_id || !product_price) {
			alert("⚠ Please select a valid product from the list."+product_id2);
			return;
		}
	}
    // Continue your logic safely now that product_id and price are defined
    let product_manual_price = $("#update_product_manual_price_PH3").val();
    let order_quantity = $("input[name=update_order_quantity_PH3]").val();
   
    let CashiersReportId = {{ $CashiersReportId }};
			$.ajax({
				url: "{{ route('CRP1_info') }}",
				type:"POST",
				data:{
				  CashiersReportId:CashiersReportId,
				 // CHPH1_ID:CHPH1_ID,
				  miscellaneous_items_type:miscellaneous_items_type,
				  product_id:product_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  
				  if(response) {				
					
					if(response==''){
						
					$('#pump_price_txt_update').html('0');
					
					$('#discounted_price_txt_update').html('0');
					
						$('#pump_price_txt').html('0');
						$('#discounted_price_txt').html('0');
						
						if(order_quantity!=0 || order_quantity!=''){
							
							if(product_manual_price!='' && product_manual_price!=0){
								
								var total_amount = (product_manual_price) * order_quantity;
								$('#UpdateTotalAmount_PH3').html(total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							
							}else{
								
								var total_amount = product_price * order_quantity;
								$('#UpdateTotalAmount_PH3').html(total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							
							}
							
						}		
						
					}else{
					
					var pump_price	= response[0].product_price;
					
							if(miscellaneous_items_type=='DISCOUNTS'){
								var discounted_price	= response[0].product_price - product_manual_price;
							}else{
								var discounted_price	= 0;
							}
							
					$('#pump_price_txt_update').html(pump_price.toLocaleString("en-PH", {maximumFractionDigits: 2}));
					
					$('#discounted_price_txt_update').html(discounted_price.toLocaleString("en-PH", {maximumFractionDigits: 2}));
					
					
						if(order_quantity!=0 || order_quantity!=''){
							
							if(product_manual_price!='' && product_manual_price!=0){
								
								var total_amount = (pump_price - product_manual_price) * order_quantity;
								$('#UpdateTotalAmount_PH3').html(total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							
							}else{
								
								var total_amount = pump_price * order_quantity;
								$('#UpdateTotalAmount_PH3').html(total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							
							}
							
						}	
					
					}					
					
					
					
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });			
		
	}	

	
	</script>
