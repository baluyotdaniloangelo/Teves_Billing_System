   <!-- Page level plugins -->
   <script src="{{asset('Datatables/2.0.8/js/dataTables.js')}}"></script>
   <script src="{{asset('Datatables/responsive/3.0.2/js/dataTables.responsive.js')}}"></script>
   <script src="{{asset('Datatables/responsive/3.0.2/js/responsive.dataTables.js')}}"></script>

   <script type="text/javascript">
	/*Load Cashiers Report Phase 1*/
	//LoadCashiersReportPH1();
	LoadCashiersReportPH2();

    LoadCashiersReportPH3_SALES_CREDIT();
	LoadCashiersReportPH3_DISCOUNT();
    LoadCashiersReportPH3_OTHERS();

	//LoadCashiersReportPH4();
	LoadCashiersReportPH5();
	LoadCashiersReportSummary();
	<!--Save New Client->
	$("#update-cashiers-report").click(function(event){
			
			event.preventDefault();
			
			/*Reset Warnings*/
				$('#teves_branchError').text('');
				$('#cashiers_nameError').text('');
				$('#forecourt_attendantError').text('');
				$('#report_dateError').text('');

			document.getElementById('CashierReportformNew').className = "g-3 needs-validation was-validated";
			
			let CashiersReportId 			= {{ $CashiersReportId }};
			
			let teves_branch 				= $("#teves_branch").val();
			let forecourt_attendant 		= $("input[name=forecourt_attendant]").val();
			let cashiers_name 				= $("input[name=cashiers_name]").val();
			let report_date 				= $("input[name=report_date]").val();
			let shift 						= $("#shift").val();
			let cashier_report_remarks 		= $("input[name=cashier_report_remarks]").val();
			
			  $.ajax({
				url: "/update_cashier_report_post",
				type:"POST",
				data:{
				  CashiersReportId:CashiersReportId,
				  teves_branch:teves_branch, 
				  cashiers_name:cashiers_name,
				  forecourt_attendant:forecourt_attendant,
				  report_date:report_date,
				  cashier_report_remarks:cashier_report_remarks,
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
					$('#cashiers_nameError').text('');
					$('#forecourt_attendantError').text('');
					$('#report_dateError').text('');

					LoadCashiersReportPH1();
					LoadProductList(teves_branch);
					document.getElementById("CRPH1_Modal_add").disabled = false;
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				if(error.responseJSON.errors.teves_branch=="The teves branch has already been taken."){
							  
				  $('#teves_branchError').html("Report has already been created for the selected branch<!--<b>"+ teves_branch +"</b>-->");
				  document.getElementById('teves_branchError').className = "invalid-feedback";
				  document.getElementById('teves_branch').className = "form-control is-invalid";
				  
				  
				}else{
				  $('#teves_branchError').text(error.responseJSON.errors.teves_branch);
				  document.getElementById('teves_branchError').className = "invalid-feedback";
				}
				
				if(error.responseJSON.errors.report_date=="The report date has already been taken."){
							  
				  $('#report_dateError').html("Report has already been created for the selected date");
				  document.getElementById('report_dateError').className = "invalid-feedback";
				  document.getElementById('report_date').className = "form-control is-invalid";
				 
				  
				}else{
				  $('#report_dateError').text(error.responseJSON.errors.report_date);
				  document.getElementById('report_dateError').className = "invalid-feedback";
				}
				
				if(error.responseJSON.errors.shift=="The shift has already been taken."){
							  
				  $('#shiftError').html("Report has already been created for the selected shift");
				  document.getElementById('shiftError').className = "invalid-feedback";
				  document.getElementById('shift').className = "form-control is-invalid";
				 
				  
				}else{
				  $('#shiftError').text(error.responseJSON.errors.shift);
				  document.getElementById('shiftError').className = "invalid-feedback";
				}
				
				  $('#cashiers_nameError').text(error.responseJSON.errors.cashiers_name);
				  document.getElementById('cashiers_nameError').className = "invalid-feedback";
				  
				  $('#forecourt_attendantError').text(error.responseJSON.errors.forecourt_attendant);
				  document.getElementById('forecourt_attendantError').className = "invalid-feedback";		

				$('#switch_notice_off').show();
				$('#sw_off').html("Invalid Input");
				setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);
				
				document.getElementById("CRPH1_Modal_add").disabled = false;
				  
				}
			   });		
	  });


	function LoadProductList(branch_id) {		
	
		$("#product_name span").remove();
		$('<span style="display: none;"></span>').appendTo('#product_name');
		
		$("#product_name_PH2 span").remove();
		$('<span style="display: none;"></span>').appendTo('#product_name_PH2');

		$("#product_list_inventory span").remove();
		$('<span style="display: none;"></span>').appendTo('#product_list_inventory');
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
	
							$('#product_name span:last').after("<span style='font-family: DejaVu Sans; sans-serif;'>"+
							"<option label='&#8369; "+product_price+" | "+product_name+"' data-id='"+product_id+"' value='"+product_name+"' data-price='"+product_price+"' >" +
							"</span>");	
							
							$('#product_name_PH2 span:last').after("<span style='font-family: DejaVu Sans; sans-serif;'>"+
							"<option label='&#8369; "+product_price+" | "+product_name+"' data-id='"+product_id+"' value='"+product_name+"' data-price='"+product_price+"' >" +
							"</span>");	
							
							$('#product_list_inventory span:last').after("<span style='font-family: DejaVu Sans; sans-serif;'>"+
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
	
	/*for MSC Items*/
	function LoadSellingPriceListlll(client_idx){	
		
		$("#product_list_PH3 span").remove();
		$('<span style="display: none;"></span>').appendTo('#product_list_PH3');

		let branch_idx 			= $("#teves_branch").val();
		//let client_idx 				= $('#so_client_name option[value="' + $('#so_client_idx').val() + '"]').attr('data-id');
			
			$.ajax({
				url: "/get_product_list_selling_price",
				type:"POST",
				data:{
					client_idx:client_idx,
					branch_idx:branch_idx,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){						
				  console.log(response['clients_price_list']);
				  if(response['clients_price_list']!='') {			  
				  
						var len = response['clients_price_list'].length;

						
							for(var i=0; i<len; i++){
								
								var product_idx = response['clients_price_list'][i].product_idx;
								var product_name = response['clients_price_list'][i].product_name;
								var product_unit_measurement = response['clients_price_list'][i].product_unit_measurement;
								var product_price = response['clients_price_list'][i].product_price;
								
								$('#product_list_PH3 span:last').after("<span style='font-family: DejaVu Sans; sans-serif;'>"+
								"<option label='&#8369; "+product_price +" | "+product_name +"' data-price='"+product_price+"' data-id='"+product_idx+"' value='"+product_name+"'>" +
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
	
function LoadSellingPriceList(client_idx) {
    let branch_idx = $("#teves_branch").val();
    $("#product_list_PH3").empty(); // clear before loading

    $.ajax({
        url: "/get_product_list_selling_price",
        type: "POST",
        data: {
            client_idx: client_idx,
            branch_idx: branch_idx,
            _token: "{{ csrf_token() }}"
        },
        success: function (response) {
            const list = response.clients_price_list || [];

            if (list.length === 0) {
                console.warn("⚠ No products returned");
                return;
            }

            list.forEach((item) => {
                $("#product_list_PH3").append(`
                    <option 
                        value="${item.product_name}"
                        data-id="${item.product_idx}"
                        data-price="${item.product_price}"
                        label="₱ ${item.product_price} | ${item.product_name}">
                    </option>
                `);
            });
        },
        error: function (err) {
            console.error("Error loading product list:", err);
        }
    });
}


	function UpdateBranch(){ 
	
		$('#switch_notice_off').show();
		$('#sw_off').html("You selected a new branch, to confirm changes click the update button");
		setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },2000);
		
		/*Disable the Add Product Button Until Changes not Save*/
		document.getElementById("CRPH1_Modal_add").disabled = false;
		
	}
	
	function LoadProductPump(inventory_mode) {		
	
		let branch_idx 			= $("#teves_branch").val();
		
		if(inventory_mode=='fuelsales_edit'){
			var product_id 			= $('#product_name option[value="' + $('#update_product_idx').val() + '"]').attr('data-id');
		}else if(inventory_mode=='fuelsales'){
			var product_id 			= $('#product_name option[value="' + $('#product_idx').val() + '"]').attr('data-id');
		}
		
		$("#product_pump_list span").remove();
		$('<span style="display: none;"></span>').appendTo('#product_pump_list');

			  $.ajax({
				url: "{{ route('ProductPumpPerBranch') }}",
				type:"POST",
				data:{
				  branchID:branch_idx,
				  productID:product_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){						
				  console.log(response);
				  if(response!='') {			
				  
						var len = response.length;
						for(var i=0; i<len; i++){
						
							var pump_id = response[i].pump_id;						
							var pump_name = response[i].pump_name;
	
							$('#product_pump_list span:last').after("<span style='font-family: DejaVu Sans; sans-serif;'>"+
							"<option label='"+pump_name+"' data-id='"+pump_id+"' value='"+pump_name+"'>" +
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

	/*Part 2*/
	$("#save-CRPH2").click(function(event){
		
			event.preventDefault();
			
			$('#product_idx_PH2Error').text('');
			$('#order_quantity_PH2Error').text('');
			$('#product_manual_price_PH2Error').text('');
			
			let CashiersReportId 		= {{ $CashiersReportId }};			
		
			var product_idx 			= $('#product_name_PH2 option[value="' + $('#product_idx_PH2').val() + '"]').attr('data-id');
			var order_quantity 			= $("input[name=order_quantity_PH2]").val();
			var product_manual_price 	= $("input[name=product_manual_price_PH2]").val();
			let teves_branch 			= $("#teves_branch").val();

			document.getElementById('CRPH2_form').className = "g-3 needs-validation was-validated";
			
				/*Delete the Selected Item*/			
				  $.ajax({
					url: "{{ route('SAVE_CHR_PH2') }}",
					type:"POST",
					data:{
					  CHPH2_ID:0,
					  CashiersReportId:CashiersReportId,
					  product_idx:product_idx,
					  branch_idx:teves_branch,
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
						LoadCashiersReportPH2();
						$('#product_idx_PH2Error').text('');					
						$('#order_quantity_PH2Error').text('');		
						$('#product_manual_price_PH2Error').text('');
						
						/*Clear Form*/
						document.getElementById("product_idx_PH2").value = '';
						document.getElementById("order_quantity_PH2").value = '';
						document.getElementById("product_manual_price_PH2").value = '';
						LoadCashiersReportSummary();
					  }
					},
					error: function(error) {
					 console.log(error);
						/*alert(error);*/
							$('#product_idx_PH2Error').text(error.responseJSON.errors.product_idx);
							document.getElementById('product_idx_PH2Error').className = "invalid-feedback";
							
							$('#order_quantity_PH2Error').text(error.responseJSON.errors.order_quantity);
							document.getElementById('order_quantity_PH2Error').className = "invalid-feedback";
							
							$('#product_manual_price_PH2Error').text(error.responseJSON.errors.closing_reading);
							document.getElementById('product_manual_price_PH2Error').className = "invalid-feedback";
					}
				   });		
		 });

	function LoadCashiersReportPH2() {		
		$("#table_product_data_other_sales tr").remove();
		$('<tr style="display: none;"><td>HIDDEN</td></tr>').appendTo('#table_product_data_other_sales');
		let CashiersReportId 			= {{ $CashiersReportId }};	
			  $.ajax({
				url: "{{ route('GetCashiersProductP2') }}",
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
						
							var cashiers_report_p2_id = response[i].cashiers_report_p2_id;						
							var product_idx = response[i].product_idx;						
							var product_price = response[i].product_price.toLocaleString("en-PH", {minimumFractionDigits: 2});
							var product_name = response[i].product_name;
							var order_quantity = response[i].order_quantity;
							var order_total_amount = response[i].order_total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2});
							
							$('#table_product_data_other_sales tr:last').after("<tr>"+
							"<td class='product_td' align='center'>"+product_name+"</td>"+
							"<td class='calibration_td' align='center'>"+order_quantity+"</td>"+
							"<td class='manual_price_td' align='center'>"+product_price+"</td>"+
							"<td class='manual_price_td' align='center'>"+order_total_amount+"</td>"+
							"<td><div align='center' class='action_table_menu_Product' style='margin-top: 6px;'><a href='#' class='btn-danger btn-circle btn-sm bi-pencil-fill btn_icon_table btn_icon_table_edit' id='CHPH2_Edit' data-id='"+cashiers_report_p2_id+"'></a> <a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deleteCashiersProductP2'  data-id='"+cashiers_report_p2_id+"'></a></div></td>"+
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

	$('body').on('click','#CHPH2_Edit',function(){			
			event.preventDefault();
			let CHPH2_ID = $(this).data('id');			
			$.ajax({
				url: "{{ route('CRP2_info') }}",
				type:"POST",
				data:{
				  CHPH2_ID:CHPH2_ID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {				
					document.getElementById("update-CRPH2").value = CHPH2_ID;				
					/*Set Details*/					
					document.getElementById("update_product_idx_PH2").value 			= response[0].product_name;
					document.getElementById("update_order_quantity_PH2").value 			= response[0].order_quantity;
					document.getElementById("update_product_manual_price_PH2").value 	= response[0].product_price;
					
					var total_amount = response[0].order_total_amount;
					$('#UpdateTotalAmount_PH2').html(total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}));				
					$('#Update_CRPH2_Modal').modal('toggle');							  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });

	$("#update-CRPH2").click(function(event){
		
			event.preventDefault();
			
			$('#update_product_idx_PH2Error').text('');
			$('#update_order_quantity_PH2Error').text('');
			$('#update_product_manual_price_PH2Error').text('');
			
			let CashiersReportId 		= {{ $CashiersReportId }};			
			let CHPH2_ID 				= document.getElementById("update-CRPH2").value;
			var product_idx 			= $('#update_product_name_PH2 option[value="' + $('#update_product_idx_PH2').val() + '"]').attr('data-id');
			var order_quantity 			= $("input[name=update_order_quantity_PH2]").val();
			var product_manual_price 	= $("input[name=update_product_manual_price_PH2]").val();
			let teves_branch 			= $("#teves_branch").val();

			document.getElementById('CRPH2_form_edit').className = "g-3 needs-validation was-validated";
			
				/*Delete the Selected Item*/			
				  $.ajax({
					url: "{{ route('SAVE_CHR_PH2') }}",
					type:"POST",
					data:{
					  CHPH2_ID:CHPH2_ID,
					  CashiersReportId:CashiersReportId,
					  product_idx:product_idx,
					  branch_idx:teves_branch,
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
						LoadCashiersReportPH2();
						LoadCashiersReportSummary();
						$('#update_product_idx_PH2Error').text('');					
						$('#update_order_quantity_PH2Error').text('');		
						$('#update_product_manual_price_PH2Error').text('');
						/*Close Form*/
						$('#Update_CRPH2_Modal').modal('toggle');		
					  }
					},
					error: function(error) {
					 console.log(error);
						/*alert(error);*/
							$('#update_product_idx_PH2Error').text(error.responseJSON.errors.product_idx);
							document.getElementById('update_product_idx_PH2Error').className = "invalid-feedback";
							
							$('#update_order_quantity_PH2Error').text(error.responseJSON.errors.order_quantity);
							document.getElementById('update_order_quantity_PH2Error').className = "invalid-feedback";
							
							$('#update_product_manual_price_PH2Error').text(error.responseJSON.errors.closing_reading);
							document.getElementById('update_product_manual_price_PH2Error').className = "invalid-feedback";
					}
				   });		
		 });

	<!--CRPH1 Deletion Confirmation-->	
	$('body').on('click','#deleteCashiersProductP2',function(){
			event.preventDefault();
			let CHPH2_ID = $(this).data('id');			
			$.ajax({
				url: "{{ route('CRP2_info') }}",
				type:"POST",
				data:{
				  CHPH2_ID:CHPH2_ID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {				
					document.getElementById("deleteCRPH2Confirmed").value = CHPH2_ID;				
					/*Set Details*/					
					//$('#CRPH1_delete_product_idx').text(response[0].product_name);
					$('#delete_product_idx_PH2').text(response[0].product_name);
					$('#delete_order_quantity_PH2').text(response[0].order_quantity);
					$('#delete_product_manual_price_PH2').text(response[0].product_price);
					
					var total_amount = response[0].order_total_amount;
					$('#delete_TotalAmount_PH2').html(total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}));				
					$('#CRPH2DeleteModal').modal('toggle');			
										
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });

	$('body').on('click','#deleteCRPH2Confirmed',function(){
			
		let CHPH2_ID = document.getElementById("deleteCRPH2Confirmed").value;
		
		if(CHPH2_ID!=0){
			/*Delete the Selected Item*/	
			  $.ajax({
				url: "{{ route('DeleteCashiersProductP2') }}",
				type:"POST",
				data:{
				  CHPH2_ID:CHPH2_ID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					$('#switch_notice_off').show();
					$('#sw_off').html("Deleted");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);	
					LoadCashiersReportPH2();
					LoadCashiersReportSummary();
				  }
				},
				error: function(error) {
				 console.log(error);
				}
			   });		
		}	
	});

	function TotalAmount_PH2(){
		
		let product_price 			= $('#product_name_PH2 option[value="' + $('#product_idx_PH2').val() + '"]').attr('data-price');
		let product_manual_price 	= $("#product_manual_price_PH2").val();		
		let order_quantity 			= $("input[name=order_quantity_PH2]").val();
		
		if(order_quantity!=0 || order_quantity!=''){
			if(product_manual_price!='' && product_manual_price!=0){
				var total_amount = product_manual_price * order_quantity;
				$('#TotalAmount_PH2').html(total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}));
			}else{
				var total_amount = product_price * order_quantity;
				$('#TotalAmount_PH2').html(total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}));
			}
		}		
	}

	function UpdateTotalAmount_PH2(){
		
		let product_price 			= $('#update_product_name_PH2 option[value="' + $('#update_product_idx_PH2').val() + '"]').attr('data-price');
		let product_manual_price 	= $("#update_product_manual_price_PH2").val();		
		let order_quantity 			= $("input[name=update_order_quantity_PH2]").val();
		
		if(order_quantity!=0 || order_quantity!=''){
			if(product_manual_price!='' && product_manual_price!=0){
				var total_amount = product_manual_price * order_quantity;
				$('#UpdateTotalAmount_PH2').html(total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}));
			}else{
				var total_amount = product_price * order_quantity;
				$('#UpdateTotalAmount_PH2').html(total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}));
			}
		}		
	}	

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
					//alert(response[0].item_description);
					document.getElementById("update_product_idx_PH3").value = response[0].item_description;
					
					document.getElementById("update_order_quantity_PH3").value 				= response[0].order_quantity;
					document.getElementById("update_product_manual_price_PH3").value 		= response[0].unit_price;
					
					
					update_input_settings_create_PH3();
					//var total_amount = response[0].order_total_amount;
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
			alert(product_idx);
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
					//$('#CRPH1_delete_product_idx').text(response[0].product_name);
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
					
					//var total_amount = response[0].order_total_amount;
					//$('#delete_TotalAmount_PH3_OTHERS').html(total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));				
					
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

	let product_id2 				= $('#product_list_PH3 option[value="' + $('#update_product_idx_PH3').val() + '"]').attr('data-id');		
	let product_price2 			= $('#product_list_PH3 option[value="' + $('#update_product_idx_PH3').val() + '"]').attr('data-price');

    console.log("✅ product_id:", product_id);
    console.log("✅ product_price:", product_price);

    // If still undefined, show a helpful alert
    if (!product_id || !product_price) {
        alert("⚠ Please select a valid product from the list."+product_id2);
        return;
    }

    // Continue your logic safely now that product_id and price are defined
    let product_manual_price = $("#update_product_manual_price_PH3").val();
    let order_quantity = $("input[name=update_order_quantity_PH3]").val();
    let miscellaneous_items_type = $("#update_miscellaneous_items_type_PH3").val();
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
		
		/*
		if(order_quantity!=0 || order_quantity!=''){
			if(product_manual_price!='' && product_manual_price!=0){
				var total_amount = product_manual_price * order_quantity;
				$('#UpdateTotalAmount_PH3').html(total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));
			}else{
				var total_amount = product_price * order_quantity;
				$('#UpdateTotalAmount_PH3').html(total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));
			}
		}		
		*/
	}	

	
	/*Part 4*/
	$("#save-CRPH4").click(function(event){
		
			event.preventDefault();
			
			$('#description_p4Error').text('');
			$('#amount_p4Error').text('');
			
			let CashiersReportId 		= {{ $CashiersReportId }};			
			
			var description_p4 		= $("input[name=description_p4]").val();
			var amount_p4 			= $("input[name=amount_p4]").val();
			
			document.getElementById('CRPH4_form').className = "g-3 needs-validation was-validated";
			
				/*Delete the Selected Item*/			
				  $.ajax({
					url: "{{ route('SAVE_CHR_PH4') }}",
					type:"POST",
					data:{
					  CHPH4_ID:0,
					  CashiersReportId:CashiersReportId,
					  description_p4:description_p4,
					  amount_p4:amount_p4, 
					  _token: "{{ csrf_token() }}"
					},
					success:function(response){
					  console.log(response);
					  if(response) {
						  
						$('#switch_notice_on').show();
						$('#sw_on').html(response.success);
						setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
						LoadCashiersReportPH4();
						LoadCashiersReportSummary();
						$('#description_p4Error').text('');					
						$('#amount_p4Error').text('');		
					  }
					},
					error: function(error) {
					 console.log(error);
						/*alert(error);*/
							$('#description_p4Error').text(error.responseJSON.errors.description_p4);
							document.getElementById('description_p4Error').className = "invalid-feedback";
							
							$('#amount_p4Error').text(error.responseJSON.errors.amount_p4);
							document.getElementById('amount_p4Error').className = "invalid-feedback";
							
					}
				   });		
		 });

	function LoadCashiersReportPH4() {		
		$("#table_product_data_PH4 tr").remove();
		$('<tr style="display: none;"><td>HIDDEN</td></tr>').appendTo('#table_product_data_PH4');
		let CashiersReportId 			= {{ $CashiersReportId }};	
			  $.ajax({
				url: "{{ route('GetCashiersP4') }}",
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
						
							var cashiers_report_p4_id = response[i].cashiers_report_p4_id;					
							var description_p4 = response[i].description_p4;
							var amount_p4 = response[i].amount_p4.toLocaleString("en-PH", {maximumFractionDigits: 2});
							
							$('#table_product_data_PH4 tr:last').after("<tr>"+
							"<td class='product_td' align='center'>"+description_p4+"</td>"+
							"<td class='calibration_td' align='center'>"+amount_p4+"</td>"+
							"<td><div align='center' class='action_table_menu_Product' style='margin-top: 6px;'><a href='#' class='btn-danger btn-circle btn-sm bi-pencil-fill btn_icon_table btn_icon_table_edit' id='CHPH4_Edit' data-id='"+cashiers_report_p4_id+"'></a> <a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deleteCashiersP4'  data-id='"+cashiers_report_p4_id+"'></a></div></td>"+
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

	$('body').on('click','#CHPH4_Edit',function(){			
			event.preventDefault();
			let CHPH4_ID = $(this).data('id');			
			$.ajax({
				url: "{{ route('CRP4_info') }}",
				type:"POST",
				data:{
				  CHPH4_ID:CHPH4_ID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {				
					document.getElementById("update-CRPH4").value = CHPH4_ID;				
					/*Set Details*/					
					document.getElementById("update_description_p4").value 	= response[0].description_p4;
					document.getElementById("update_amount_p4").value 		= response[0].amount_p4;
					$('#Update_CRPH4_Modal').modal('toggle');	
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });

	$("#update-CRPH4").click(function(event){
		
			event.preventDefault();
			
			$('#update_description_p4Error').text('');
			$('#update_amount_p4Error').text('');
			
			let CashiersReportId 		= {{ $CashiersReportId }};			
			let CHPH4_ID 				= document.getElementById("update-CRPH4").value;
			
			var description_p4 		= $("input[name=update_description_p4]").val();
			var amount_p4 			= $("input[name=update_amount_p4]").val();
			
			document.getElementById('CRPH3_form_edit').className = "g-3 needs-validation was-validated";
			
				/*Delete the Selected Item*/			
				  $.ajax({
					url: "{{ route('SAVE_CHR_PH4') }}",
					type:"POST",
					data:{
						CHPH4_ID:CHPH4_ID,
						CashiersReportId:CashiersReportId,
						description_p4:description_p4,
						amount_p4:amount_p4, 
					  _token: "{{ csrf_token() }}"
					},
					success:function(response){
					  console.log(response);
					  if(response) {
						  
						$('#switch_notice_on').show();
						$('#sw_on').html(response.success);
						setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
						LoadCashiersReportPH4();
						LoadCashiersReportSummary();
						$('#update_description_p4Error').text('');					
						$('#update_amount_p4Error').text('');		
					  }
					},
					error: function(error) {
					 console.log(error);
						/*alert(error);*/
							$('#update_description_p4Error').text(error.responseJSON.errors.description_p4);
							document.getElementById('update_description_p4Error').className = "invalid-feedback";
							
							$('#update_amount_p4Error').text(error.responseJSON.errors.amount_p4);
							document.getElementById('update_amount_p4Error').className = "invalid-feedback";
					}
				   });		
		 });

	<!--CRPH1 Deletion Confirmation-->	
	$('body').on('click','#deleteCashiersP4',function(){
			event.preventDefault();
			let CHPH4_ID = $(this).data('id');			
			$.ajax({
				url: "{{ route('CRP4_info') }}",
				type:"POST",
				data:{
				  CHPH4_ID:CHPH4_ID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {				
					document.getElementById("deleteCRPH4Confirmed").value = CHPH4_ID;				
					/*Set Details*/					
					$('#delete_description_p4').text(response[0].description_p4);
					$('#delete_amount_p4').text(response[0].amount_p4);
					
					$('#CRPH4DeleteModal').modal('toggle');							  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });

	$('body').on('click','#deleteCRPH4Confirmed',function(){
			
		let CHPH4_ID = document.getElementById("deleteCRPH4Confirmed").value;
		
		if(CHPH4_ID!=0){
			/*Delete the Selected Item*/	
			  $.ajax({
				url: "{{ route('DeleteCashiersP4') }}",
				type:"POST",
				data:{
				  CHPH4_ID:CHPH4_ID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					$('#switch_notice_off').show();
					$('#sw_off').html("Deleted");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);	
					LoadCashiersReportPH4();
					LoadCashiersReportSummary();
				  }
				},
				error: function(error) {
				 console.log(error);
				}
			   });		
		}	
	});
	
	$('body').on('click','#cashonhand',function(){
		
		var one_thousand_deno = $("input[name=one_thousand_deno]").val();
		var five_hundred_deno = $("input[name=five_hundred_deno]").val();
		var two_hundred_deno = $("input[name=two_hundred_deno]").val();
		var one_hundred_deno = $("input[name=one_hundred_deno]").val();
		var fifty_deno = $("input[name=fifty_deno]").val();
		var twenty_deno = $("input[name=twenty_deno]").val();
		var ten_deno = $("input[name=ten_deno]").val();
		var five_deno = $("input[name=five_deno]").val();
		var one_deno = $("input[name=one_deno]").val();
		var twenty_five_cent_deno = $("input[name=twenty_five_cent_deno]").val();
			
		one_thousand_deno_amt = one_thousand_deno * 1000;	
		five_hundred_deno_amt = five_hundred_deno * 500;	
		two_hundred_deno_amt = two_hundred_deno * 200;
		one_hundred_deno_amt = one_hundred_deno * 100;
		fifty_deno_amt = fifty_deno * 50;
		twenty_deno_amt = twenty_deno * 20;
		ten_deno_amt = ten_deno * 10;
		five_deno_amt = five_deno * 5;
		one_deno_amt = one_deno * 1;
		twenty_five_deno_amt = twenty_five_cent_deno * 0.25;
	
		total_cash_on_hand = one_thousand_deno_amt
		+ five_hundred_deno_amt
		+ two_hundred_deno_amt
		+ one_hundred_deno_amt
		+ fifty_deno_amt
		+ twenty_deno_amt
		+ ten_deno_amt
		+ five_deno_amt
		+ one_deno_amt
		+ twenty_five_deno_amt;
		
		$('#total_cash_on_hand_amt').html(total_cash_on_hand.toLocaleString("en-PH", {maximumFractionDigits: 2}));

	});
	
	function one_thousand_deno_total(){
		
		var one_thousand_deno = $("input[name=one_thousand_deno]").val();
		var five_hundred_deno = $("input[name=five_hundred_deno]").val();
		var two_hundred_deno = $("input[name=two_hundred_deno]").val();
		var one_hundred_deno = $("input[name=one_hundred_deno]").val();
		var fifty_deno = $("input[name=fifty_deno]").val();
		var twenty_deno = $("input[name=twenty_deno]").val();
		var ten_deno = $("input[name=ten_deno]").val();
		var five_deno = $("input[name=five_deno]").val();
		var one_deno = $("input[name=one_deno]").val();
		var twenty_five_cent_deno = $("input[name=twenty_five_cent_deno]").val();
		
		if(one_thousand_deno!=0 || one_thousand_deno!=''){
			
				deno_amount = one_thousand_deno * 1000;
				$('#one_thousand_deno_total_amt').html(deno_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));

		}			
			
		one_thousand_deno_amt = one_thousand_deno * 1000;	
		five_hundred_deno_amt = five_hundred_deno * 500;	
		two_hundred_deno_amt = two_hundred_deno * 200;
		one_hundred_deno_amt = one_hundred_deno * 100;
		fifty_deno_amt = fifty_deno * 50;
		twenty_deno_amt = twenty_deno * 20;
		ten_deno_amt = ten_deno * 10;
		five_deno_amt = five_deno * 5;
		one_deno_amt = one_deno * 1;
		twenty_five_deno_amt = twenty_five_cent_deno * 0.25;
	
		total_cash_on_hand = one_thousand_deno_amt
		+ five_hundred_deno_amt
		+ two_hundred_deno_amt
		+ one_hundred_deno_amt
		+ fifty_deno_amt
		+ twenty_deno_amt
		+ ten_deno_amt
		+ five_deno_amt
		+ one_deno_amt
		+ twenty_five_deno_amt;
		
		$('#total_cash_on_hand_amt').html(total_cash_on_hand.toLocaleString("en-PH", {maximumFractionDigits: 2}));
		
	}

	function five_hundred_deno_total(){
		
		var one_thousand_deno = $("input[name=one_thousand_deno]").val();
		var five_hundred_deno = $("input[name=five_hundred_deno]").val();
		var two_hundred_deno = $("input[name=two_hundred_deno]").val();
		var one_hundred_deno = $("input[name=one_hundred_deno]").val();
		var fifty_deno = $("input[name=fifty_deno]").val();
		var twenty_deno = $("input[name=twenty_deno]").val();
		var ten_deno = $("input[name=ten_deno]").val();
		var five_deno = $("input[name=five_deno]").val();
		var one_deno = $("input[name=one_deno]").val();
		var twenty_five_cent_deno = $("input[name=twenty_five_cent_deno]").val();
		
		if(five_hundred_deno!=0 || five_hundred_deno!=''){
			
				deno_amount = five_hundred_deno * 500;
				$('#five_hundred_deno_total_amt').html(deno_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));

		}		
		
		one_thousand_deno_amt = one_thousand_deno * 1000;	
		five_hundred_deno_amt = five_hundred_deno * 500;	
		two_hundred_deno_amt = two_hundred_deno * 200;
		one_hundred_deno_amt = one_hundred_deno * 100;
		fifty_deno_amt = fifty_deno * 50;
		twenty_deno_amt = twenty_deno * 20;
		ten_deno_amt = ten_deno * 10;
		five_deno_amt = five_deno * 5;
		one_deno_amt = one_deno * 1;
		twenty_five_deno_amt = twenty_five_cent_deno * 0.25;
	
		total_cash_on_hand = one_thousand_deno_amt
		+ five_hundred_deno_amt
		+ two_hundred_deno_amt
		+ one_hundred_deno_amt
		+ fifty_deno_amt
		+ twenty_deno_amt
		+ ten_deno_amt
		+ five_deno_amt
		+ one_deno_amt
		+ twenty_five_deno_amt;
		
		$('#total_cash_on_hand_amt').html(total_cash_on_hand.toLocaleString("en-PH", {maximumFractionDigits: 2}));		
		
	}	
	
	function two_hundred_deno_total(){
		
		var one_thousand_deno = $("input[name=one_thousand_deno]").val();
		var five_hundred_deno = $("input[name=five_hundred_deno]").val();
		var two_hundred_deno = $("input[name=two_hundred_deno]").val();
		var one_hundred_deno = $("input[name=one_hundred_deno]").val();
		var fifty_deno = $("input[name=fifty_deno]").val();
		var twenty_deno = $("input[name=twenty_deno]").val();
		var ten_deno = $("input[name=ten_deno]").val();
		var five_deno = $("input[name=five_deno]").val();
		var one_deno = $("input[name=one_deno]").val();
		var twenty_five_cent_deno = $("input[name=twenty_five_cent_deno]").val();
		
		if(two_hundred_deno!=0 || two_hundred_deno!=''){
			
				deno_amount = two_hundred_deno * 200;
				$('#two_hundred_deno_total_amt').html(deno_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));

		}		
		
		one_thousand_deno_amt = one_thousand_deno * 1000;	
		five_hundred_deno_amt = five_hundred_deno * 500;	
		two_hundred_deno_amt = two_hundred_deno * 200;
		one_hundred_deno_amt = one_hundred_deno * 100;
		fifty_deno_amt = fifty_deno * 50;
		twenty_deno_amt = twenty_deno * 20;
		ten_deno_amt = ten_deno * 10;
		five_deno_amt = five_deno * 5;
		one_deno_amt = one_deno * 1;
		twenty_five_deno_amt = twenty_five_cent_deno * 0.25;
	
		total_cash_on_hand = one_thousand_deno_amt
		+ five_hundred_deno_amt
		+ two_hundred_deno_amt
		+ one_hundred_deno_amt
		+ fifty_deno_amt
		+ twenty_deno_amt
		+ ten_deno_amt
		+ five_deno_amt
		+ one_deno_amt
		+ twenty_five_deno_amt;
		
		$('#total_cash_on_hand_amt').html(total_cash_on_hand.toLocaleString("en-PH", {maximumFractionDigits: 2}));		
		
	}	

	function one_hundred_deno_total(){
		
		var one_thousand_deno = $("input[name=one_thousand_deno]").val();
		var five_hundred_deno = $("input[name=five_hundred_deno]").val();
		var two_hundred_deno = $("input[name=two_hundred_deno]").val();
		var one_hundred_deno = $("input[name=one_hundred_deno]").val();
		var fifty_deno = $("input[name=fifty_deno]").val();
		var twenty_deno = $("input[name=twenty_deno]").val();
		var ten_deno = $("input[name=ten_deno]").val();
		var five_deno = $("input[name=five_deno]").val();
		var one_deno = $("input[name=one_deno]").val();
		var twenty_five_cent_deno = $("input[name=twenty_five_cent_deno]").val();
		
		if(one_hundred_deno!=0 || one_hundred_deno!=''){
			
				deno_amount = one_hundred_deno * 100;
				$('#one_hundred_deno_total_amt').html(deno_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));

		}		
		
		one_thousand_deno_amt = one_thousand_deno * 1000;	
		five_hundred_deno_amt = five_hundred_deno * 500;	
		two_hundred_deno_amt = two_hundred_deno * 200;
		one_hundred_deno_amt = one_hundred_deno * 100;
		fifty_deno_amt = fifty_deno * 50;
		twenty_deno_amt = twenty_deno * 20;
		ten_deno_amt = ten_deno * 10;
		five_deno_amt = five_deno * 5;
		one_deno_amt = one_deno * 1;
		twenty_five_deno_amt = twenty_five_cent_deno * 0.25;
	
		total_cash_on_hand = one_thousand_deno_amt
		+ five_hundred_deno_amt
		+ two_hundred_deno_amt
		+ one_hundred_deno_amt
		+ fifty_deno_amt
		+ twenty_deno_amt
		+ ten_deno_amt
		+ five_deno_amt
		+ one_deno_amt
		+ twenty_five_deno_amt;
		
		$('#total_cash_on_hand_amt').html(total_cash_on_hand.toLocaleString("en-PH", {maximumFractionDigits: 2}));
				
	}

	function fifty_deno_total(){
		
		var one_thousand_deno = $("input[name=one_thousand_deno]").val();
		var five_hundred_deno = $("input[name=five_hundred_deno]").val();
		var two_hundred_deno = $("input[name=two_hundred_deno]").val();
		var one_hundred_deno = $("input[name=one_hundred_deno]").val();
		var fifty_deno = $("input[name=fifty_deno]").val();
		var twenty_deno = $("input[name=twenty_deno]").val();
		var ten_deno = $("input[name=ten_deno]").val();
		var five_deno = $("input[name=five_deno]").val();
		var one_deno = $("input[name=one_deno]").val();
		var twenty_five_cent_deno = $("input[name=twenty_five_cent_deno]").val();
			
		if(fifty_deno!=0 || fifty_deno!=''){
			
				deno_amount = fifty_deno * 50;
				$('#fifty_deno_total_amt').html(deno_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));

		}		
		
		one_thousand_deno_amt = one_thousand_deno * 1000;	
		five_hundred_deno_amt = five_hundred_deno * 500;	
		two_hundred_deno_amt = two_hundred_deno * 200;
		one_hundred_deno_amt = one_hundred_deno * 100;
		fifty_deno_amt = fifty_deno * 50;
		twenty_deno_amt = twenty_deno * 20;
		ten_deno_amt = ten_deno * 10;
		five_deno_amt = five_deno * 5;
		one_deno_amt = one_deno * 1;
		twenty_five_deno_amt = twenty_five_cent_deno * 0.25;
	
		total_cash_on_hand = one_thousand_deno_amt
		+ five_hundred_deno_amt
		+ two_hundred_deno_amt
		+ one_hundred_deno_amt
		+ fifty_deno_amt
		+ twenty_deno_amt
		+ ten_deno_amt
		+ five_deno_amt
		+ one_deno_amt
		+ twenty_five_deno_amt;
		
		$('#total_cash_on_hand_amt').html(total_cash_on_hand.toLocaleString("en-PH", {maximumFractionDigits: 2}));		
		
	}

	function twenty_deno_total(){
		
		var one_thousand_deno = $("input[name=one_thousand_deno]").val();
		var five_hundred_deno = $("input[name=five_hundred_deno]").val();
		var two_hundred_deno = $("input[name=two_hundred_deno]").val();
		var one_hundred_deno = $("input[name=one_hundred_deno]").val();
		var fifty_deno = $("input[name=fifty_deno]").val();
		var twenty_deno = $("input[name=twenty_deno]").val();
		var ten_deno = $("input[name=ten_deno]").val();
		var five_deno = $("input[name=five_deno]").val();
		var one_deno = $("input[name=one_deno]").val();
		var twenty_five_cent_deno = $("input[name=twenty_five_cent_deno]").val();
	
		if(twenty_deno!=0 || twenty_deno!=''){
			
				deno_amount = twenty_deno * 20;
				$('#twenty_deno_total_amt').html(deno_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));

		}		
		
		one_thousand_deno_amt = one_thousand_deno * 1000;	
		five_hundred_deno_amt = five_hundred_deno * 500;	
		two_hundred_deno_amt = two_hundred_deno * 200;
		one_hundred_deno_amt = one_hundred_deno * 100;
		fifty_deno_amt = fifty_deno * 50;
		twenty_deno_amt = twenty_deno * 20;
		ten_deno_amt = ten_deno * 10;
		five_deno_amt = five_deno * 5;
		one_deno_amt = one_deno * 1;
		twenty_five_deno_amt = twenty_five_cent_deno * 0.25;
	
		total_cash_on_hand = one_thousand_deno_amt
		+ five_hundred_deno_amt
		+ two_hundred_deno_amt
		+ one_hundred_deno_amt
		+ fifty_deno_amt
		+ twenty_deno_amt
		+ ten_deno_amt
		+ five_deno_amt
		+ one_deno_amt
		+ twenty_five_deno_amt;
		
		$('#total_cash_on_hand_amt').html(total_cash_on_hand.toLocaleString("en-PH", {maximumFractionDigits: 2}));
				
	}
	
	function ten_deno_total(){
		
		var one_thousand_deno = $("input[name=one_thousand_deno]").val();
		var five_hundred_deno = $("input[name=five_hundred_deno]").val();
		var two_hundred_deno = $("input[name=two_hundred_deno]").val();
		var one_hundred_deno = $("input[name=one_hundred_deno]").val();
		var fifty_deno = $("input[name=fifty_deno]").val();
		var twenty_deno = $("input[name=twenty_deno]").val();
		var ten_deno = $("input[name=ten_deno]").val();
		var five_deno = $("input[name=five_deno]").val();
		var one_deno = $("input[name=one_deno]").val();
		var twenty_five_cent_deno = $("input[name=twenty_five_cent_deno]").val();
		
		if(ten_deno!=0 || ten_deno!=''){
			
				deno_amount = ten_deno * 10;
				$('#ten_deno_total_amt').html(deno_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));

		}		
		
		one_thousand_deno_amt = one_thousand_deno * 1000;	
		five_hundred_deno_amt = five_hundred_deno * 500;	
		two_hundred_deno_amt = two_hundred_deno * 200;
		one_hundred_deno_amt = one_hundred_deno * 100;
		fifty_deno_amt = fifty_deno * 50;
		twenty_deno_amt = twenty_deno * 20;
		ten_deno_amt = ten_deno * 10;
		five_deno_amt = five_deno * 5;
		one_deno_amt = one_deno * 1;
		twenty_five_deno_amt = twenty_five_cent_deno * 0.25;
	
		total_cash_on_hand = one_thousand_deno_amt
		+ five_hundred_deno_amt
		+ two_hundred_deno_amt
		+ one_hundred_deno_amt
		+ fifty_deno_amt
		+ twenty_deno_amt
		+ ten_deno_amt
		+ five_deno_amt
		+ one_deno_amt
		+ twenty_five_deno_amt;
		
		$('#total_cash_on_hand_amt').html(total_cash_on_hand.toLocaleString("en-PH", {maximumFractionDigits: 2}));		
		
	}

	function five_deno_total(){
		
		var one_thousand_deno = $("input[name=one_thousand_deno]").val();
		var five_hundred_deno = $("input[name=five_hundred_deno]").val();
		var two_hundred_deno = $("input[name=two_hundred_deno]").val();
		var one_hundred_deno = $("input[name=one_hundred_deno]").val();
		var fifty_deno = $("input[name=fifty_deno]").val();
		var twenty_deno = $("input[name=twenty_deno]").val();
		var ten_deno = $("input[name=ten_deno]").val();
		var five_deno = $("input[name=five_deno]").val();
		var one_deno = $("input[name=one_deno]").val();
		var twenty_five_cent_deno = $("input[name=twenty_five_cent_deno]").val();
		
		if(five_deno!=0 || five_deno!=''){
			
				deno_amount = five_deno * 5;
				$('#five_deno_total_amt').html(deno_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));

		}		
		one_thousand_deno_amt = one_thousand_deno * 1000;	
		five_hundred_deno_amt = five_hundred_deno * 500;	
		two_hundred_deno_amt = two_hundred_deno * 200;
		one_hundred_deno_amt = one_hundred_deno * 100;
		fifty_deno_amt = fifty_deno * 50;
		twenty_deno_amt = twenty_deno * 20;
		ten_deno_amt = ten_deno * 10;
		five_deno_amt = five_deno * 5;
		one_deno_amt = one_deno * 1;
		twenty_five_deno_amt = twenty_five_cent_deno * 0.25;
	
		total_cash_on_hand = one_thousand_deno_amt
		+ five_hundred_deno_amt
		+ two_hundred_deno_amt
		+ one_hundred_deno_amt
		+ fifty_deno_amt
		+ twenty_deno_amt
		+ ten_deno_amt
		+ five_deno_amt
		+ one_deno_amt
		+ twenty_five_deno_amt;
		
		$('#total_cash_on_hand_amt').html(total_cash_on_hand.toLocaleString("en-PH", {maximumFractionDigits: 2}));
	
	}
	
	function one_deno_total(){
		
		var one_thousand_deno = $("input[name=one_thousand_deno]").val();
		var five_hundred_deno = $("input[name=five_hundred_deno]").val();
		var two_hundred_deno = $("input[name=two_hundred_deno]").val();
		var one_hundred_deno = $("input[name=one_hundred_deno]").val();
		var fifty_deno = $("input[name=fifty_deno]").val();
		var twenty_deno = $("input[name=twenty_deno]").val();
		var ten_deno = $("input[name=ten_deno]").val();
		var five_deno = $("input[name=five_deno]").val();
		var one_deno = $("input[name=one_deno]").val();
		var twenty_five_cent_deno = $("input[name=twenty_five_cent_deno]").val();
			
		if(one_deno!=0 || one_deno!=''){
			
				deno_amount = one_deno * 1;
				$('#one_deno_total_amt').html(deno_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));

		}		
		one_thousand_deno_amt = one_thousand_deno * 1000;	
		five_hundred_deno_amt = five_hundred_deno * 500;	
		two_hundred_deno_amt = two_hundred_deno * 200;
		one_hundred_deno_amt = one_hundred_deno * 100;
		fifty_deno_amt = fifty_deno * 50;
		twenty_deno_amt = twenty_deno * 20;
		ten_deno_amt = ten_deno * 10;
		five_deno_amt = five_deno * 5;
		one_deno_amt = one_deno * 1;
		twenty_five_deno_amt = twenty_five_cent_deno * 0.25;
	
		total_cash_on_hand = one_thousand_deno_amt
		+ five_hundred_deno_amt
		+ two_hundred_deno_amt
		+ one_hundred_deno_amt
		+ fifty_deno_amt
		+ twenty_deno_amt
		+ ten_deno_amt
		+ five_deno_amt
		+ one_deno_amt
		+ twenty_five_deno_amt;
		
		$('#total_cash_on_hand_amt').html(total_cash_on_hand.toLocaleString("en-PH", {maximumFractionDigits: 2}));
		
		
	}
	
	function twenty_five_cent_deno_total(){
		
		var one_thousand_deno = $("input[name=one_thousand_deno]").val();
		var five_hundred_deno = $("input[name=five_hundred_deno]").val();
		var two_hundred_deno = $("input[name=two_hundred_deno]").val();
		var one_hundred_deno = $("input[name=one_hundred_deno]").val();
		var fifty_deno = $("input[name=fifty_deno]").val();
		var twenty_deno = $("input[name=twenty_deno]").val();
		var ten_deno = $("input[name=ten_deno]").val();
		var five_deno = $("input[name=five_deno]").val();
		var one_deno = $("input[name=one_deno]").val();
		var twenty_five_cent_deno = $("input[name=twenty_five_cent_deno]").val();
			
		if(twenty_five_cent_deno!=0 || twenty_five_cent_deno!=''){
			
				deno_amount = twenty_five_cent_deno * 0.25;
				$('#twenty_five_cent_deno_total_amt').html(deno_amount.toLocaleString("en-PH", {maximumFractionDigits: 2}));

		}		
		one_thousand_deno_amt = one_thousand_deno * 1000;	
		five_hundred_deno_amt = five_hundred_deno * 500;	
		two_hundred_deno_amt = two_hundred_deno * 200;
		one_hundred_deno_amt = one_hundred_deno * 100;
		fifty_deno_amt = fifty_deno * 50;
		twenty_deno_amt = twenty_deno * 20;
		ten_deno_amt = ten_deno * 10;
		five_deno_amt = five_deno * 5;
		one_deno_amt = one_deno * 1;
		twenty_five_deno_amt = twenty_five_cent_deno * 0.25;
	
		total_cash_on_hand = one_thousand_deno_amt
		+ five_hundred_deno_amt
		+ two_hundred_deno_amt
		+ one_hundred_deno_amt
		+ fifty_deno_amt
		+ twenty_deno_amt
		+ ten_deno_amt
		+ five_deno_amt
		+ one_deno_amt
		+ twenty_five_deno_amt;
		
		$('#total_cash_on_hand_amt').html(total_cash_on_hand.toLocaleString("en-PH", {maximumFractionDigits: 2}));
		
		
	}	

	function LoadCashiersReportPH5() {		
		
		let CashiersReportId 			= {{ $CashiersReportId }};	
			  
			  $.ajax({
				url: "{{ route('CRP5_info') }}",
				type:"POST",
				data:{
				  CashiersReportId:CashiersReportId,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){						
				  console.log(response);
				  if(response!='') {			  
				  
					document.getElementById("one_thousand_deno").value 	= response[0].one_thousand_deno;
					
					document.getElementById("five_hundred_deno").value 	= response[0].five_hundred_deno;
					document.getElementById("two_hundred_deno").value 	= response[0].two_hundred_deno;
					document.getElementById("one_hundred_deno").value 	= response[0].one_hundred_deno;

					document.getElementById("fifty_deno").value 	= response[0].fifty_deno;
					document.getElementById("twenty_deno").value 	= response[0].twenty_deno;
					document.getElementById("ten_deno").value 		= response[0].ten_deno;
					
					document.getElementById("five_deno").value 	= response[0].five_deno;
					document.getElementById("one_deno").value 	= response[0].one_deno;
					
					document.getElementById("twenty_five_cent_deno").value 	= response[0].twenty_five_cent_deno;
					document.getElementById("cash_drop").value 	= response[0].cash_drop;
					
					document.getElementById("CASHONHAND").value = response[0].cashiers_report_p5_id;
					
					/*Load Amount*/
		one_thousand_deno_amt 		= response[0].one_thousand_deno * 1000;	
		five_hundred_deno_amt 		= response[0].five_hundred_deno * 500;	
		two_hundred_deno_amt 		= response[0].two_hundred_deno * 200;
		one_hundred_deno_amt 		= response[0].one_hundred_deno * 100;
		fifty_deno_amt 				= response[0].fifty_deno * 50;
		twenty_deno_amt 			= response[0].twenty_deno * 20;
		ten_deno_amt 				= response[0].ten_deno * 10;
		five_deno_amt 				= response[0].five_deno * 5;
		one_deno_amt 				= response[0].one_deno * 1;
		twenty_five_deno_amt 		= response[0].twenty_five_cent_deno * 0.25;
	
		total_cash_on_hand = one_thousand_deno_amt
		+ five_hundred_deno_amt
		+ two_hundred_deno_amt
		+ one_hundred_deno_amt
		+ fifty_deno_amt
		+ twenty_deno_amt
		+ ten_deno_amt
		+ five_deno_amt
		+ one_deno_amt
		+ twenty_five_deno_amt;
		
		$('#one_thousand_deno_total_amt').html(one_thousand_deno_amt.toLocaleString("en-PH", {maximumFractionDigits: 2}));
		
		$('#five_hundred_deno_total_amt').html(five_hundred_deno_amt.toLocaleString("en-PH", {maximumFractionDigits: 2}));
		$('#two_hundred_deno_total_amt').html(two_hundred_deno_amt.toLocaleString("en-PH", {maximumFractionDigits: 2}));
		$('#one_hundred_deno_total_amt').html(one_hundred_deno_amt.toLocaleString("en-PH", {maximumFractionDigits: 2}));
		
		$('#fifty_deno_total_amt').html(fifty_deno_amt.toLocaleString("en-PH", {maximumFractionDigits: 2}));
		$('#twenty_deno_total_amt').html(twenty_deno_amt.toLocaleString("en-PH", {maximumFractionDigits: 2}));
		$('#ten_deno_total_amt').html(ten_deno_amt.toLocaleString("en-PH", {maximumFractionDigits: 2}));

		$('#five_deno_total_amt').html(five_deno_amt.toLocaleString("en-PH", {maximumFractionDigits: 2}));
		$('#one_deno_total_amt').html(one_deno_amt.toLocaleString("en-PH", {maximumFractionDigits: 2}));
		
		$('#twenty_five_cent_deno_total_amt').html(twenty_five_deno_amt.toLocaleString("en-PH", {maximumFractionDigits: 2}));
		
		$('#total_cash_on_hand_amt').html(total_cash_on_hand.toLocaleString("en-PH", {maximumFractionDigits: 2}));

					
				  }else{
							/*No Result Found or Error*/	
				  }
				},
				error: function(error) {
				 console.log(error);	 
				}
			   });
	  }  	  
	
	$("#CASHONHAND").click(function(event){
		
			event.preventDefault();
		
			let CashiersReportId 		= {{ $CashiersReportId }};			
			let CHPH5_ID 				= document.getElementById("CASHONHAND").value;
			
			var one_thousand_deno = $("input[name=one_thousand_deno]").val();
			var five_hundred_deno = $("input[name=five_hundred_deno]").val();
			var two_hundred_deno = $("input[name=two_hundred_deno]").val();
			var one_hundred_deno = $("input[name=one_hundred_deno]").val();
			var fifty_deno = $("input[name=fifty_deno]").val();
			var twenty_deno = $("input[name=twenty_deno]").val();
			var ten_deno = $("input[name=ten_deno]").val();
			var five_deno = $("input[name=five_deno]").val();
			var one_deno = $("input[name=one_deno]").val();
			var twenty_five_cent_deno = $("input[name=twenty_five_cent_deno]").val();
			
			var cash_drop = $("input[name=cash_drop]").val();
			
			document.getElementById('CASHONHAND_FORM').className = "g-3 needs-validation was-validated";
					
				  $.ajax({
					url: "{{ route('SAVE_CHR_PH5') }}",
					type:"POST",
					data:{
						CashiersReportId:CashiersReportId,
						CHPH5_ID:CHPH5_ID,
						one_thousand_deno:one_thousand_deno,
						five_hundred_deno:five_hundred_deno,
						two_hundred_deno:two_hundred_deno,
						one_hundred_deno:one_hundred_deno,
						fifty_deno:fifty_deno,
						twenty_deno:twenty_deno,
						ten_deno:ten_deno,
						five_deno:five_deno,
						one_deno:one_deno,
						twenty_five_cent_deno:twenty_five_cent_deno,
						cash_drop:cash_drop, 
					  _token: "{{ csrf_token() }}"
					},
					success:function(response){
					  console.log(response);
					  if(response) {
						  
						$('#switch_notice_on').show();
						$('#sw_on').html(response.success);
						setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
						LoadCashiersReportPH5();
						LoadCashiersReportSummary();
						
						$('#cash_dropError').text('');
						$('#one_thousand_denoError').text('');
						$('#five_hundred_denoError').text('');
						$('#two_hundred_denoError').text('');
						$('#one_hundred_denoError').text('');
						$('#fifty_denoError').text('');
						$('#twenty_denoError').text('');
						$('#ten_denoError').text('');
						$('#five_denoError').text('');
						$('#one_denoError').text('');
						$('#twenty_five_cent_denoError').text('');
						
					  }
					},
					error: function(error) {
					 console.log(error);
						
						$('#one_thousand_denoError').text(error.responseJSON.errors.one_thousand_deno);
						document.getElementById('one_thousand_denoError').className = "invalid-feedback";
						
						$('#five_hundred_denoError').text(error.responseJSON.errors.five_hundred_deno);
						document.getElementById('five_hundred_denoError').className = "invalid-feedback";
						
						$('#two_hundred_denoError').text(error.responseJSON.errors.two_hundred_deno);
						document.getElementById('two_hundred_denoError').className = "invalid-feedback";
						
						$('#one_hundred_denoError').text(error.responseJSON.errors.one_hundred_deno);
						document.getElementById('one_hundred_denoError').className = "invalid-feedback";
						
						$('#fifty_denoError').text(error.responseJSON.errors.fifty_deno);
						document.getElementById('fifty_denoError').className = "invalid-feedback";
						
						$('#twenty_denoError').text(error.responseJSON.errors.twenty_deno);
						document.getElementById('twenty_denoError').className = "invalid-feedback";
						
						$('#ten_denoError').text(error.responseJSON.errors.ten_deno);
						document.getElementById('ten_denoError').className = "invalid-feedback";
						
						$('#five_denoError').text(error.responseJSON.errors.five_deno);
						document.getElementById('five_denoError').className = "invalid-feedback";
						
						$('#one_denoError').text(error.responseJSON.errors.one_deno);
						document.getElementById('one_denoError').className = "invalid-feedback";
						
						$('#twenty_five_cent_denoError').text(error.responseJSON.errors.twenty_five_cent_deno);
						document.getElementById('twenty_five_cent_denoError').className = "invalid-feedback";
						
						$('#cash_dropError').text(error.responseJSON.errors.cash_drop);
						document.getElementById('cash_dropError').className = "invalid-feedback";			

						$('#switch_notice_off').show();
						$('#sw_off').html("Invalid Input");
						setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);
							
					}
				   });		
	});


	function LoadCashiersReportSummary() {		
		
		let CashiersReportId 			= {{ $CashiersReportId }};	
			  
			  $.ajax({
				url: "{{ route('CashiersReportSummary') }}",
				type:"POST",
				data:{
				  CashiersReportId:CashiersReportId,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){						
				  console.log(response);
				  if(response!='') {			  
				  
					fuel_sales_total = response.fuel_sales_total;
					other_sales_total = response.other_sales_total;
					
					miscellaneous_total = response.miscellaneous_total;
					
					total_non_cash_payment = response.total_limitless_payment_amount + response.total_credit_debit_payment_amount + response.total_gcash_payment_amount + response.total_online_payment_amount;
					
					cash_on_hand = response.cash_on_hand;
					total_sales = cash_on_hand + total_non_cash_payment;
					
					theoretical_sales = (fuel_sales_total + other_sales_total) - miscellaneous_total;
					
					cash_short_or_over = (cash_on_hand + total_non_cash_payment) - theoretical_sales;				
					
					$('#fuel_sales_total').html(fuel_sales_total.toLocaleString("en-PH", {maximumFractionDigits: 2}));
					
					$('#other_sales_total').html(other_sales_total.toLocaleString("en-PH", {maximumFractionDigits: 2}));
					
					$('#total_sales').html(total_sales.toLocaleString("en-PH", {maximumFractionDigits: 2}));
					
					$('#miscellaneous_total').html(miscellaneous_total.toLocaleString("en-PH", {maximumFractionDigits: 2}));
					
					$('#total_cash_payment').html(cash_on_hand.toLocaleString("en-PH", {maximumFractionDigits: 2}));
					
					$('#total_non_cash_payment').html(total_non_cash_payment.toLocaleString("en-PH", {maximumFractionDigits: 2}));
					
					$('#theoretical_sales').html(theoretical_sales.toLocaleString("en-PH", {maximumFractionDigits: 2}));
					
					$('#cash_on_hand').html(cash_on_hand.toLocaleString("en-PH", {maximumFractionDigits: 2}));
					
					$('#cash_short_or_over').html(cash_short_or_over.toLocaleString("en-PH", {maximumFractionDigits: 2}));
					
				  }else{
							/*No Result Found or Error*/	
				  }
				},
				error: function(error) {
				 console.log(error);	 
				}
			   });
	  }  	

	function printCashierReportPDF(){
	  
		let CashiersReportId 			= {{ $CashiersReportId }};
		
		var query = {
			CashiersReportId:CashiersReportId,
			_token: "{{ csrf_token() }}"
		}

		var url = "{{URL::to('generate_cashier_report_pdf')}}?" + $.param(query)
		window.open(url);
	  
	}
	
	function postTest() {	
		$.ajax({
		  type: "POST",
		  url: "http://localhost:8000/check_time.php",
		  data: {
			"Id": 78912,
			"Customer": "Jason Sweet",
		  },
		  success: function (result) {
			 console.log(result);
			 alert(result);
		  },
		  dataType: "html"
		});
	}
	
</script>
