   <script type="text/javascript">
	
	function inventory_tank_dipstick()(){
		
			var beginning_inventory 			= $("input[name=dipstick_beginning_inventory]").val();
			var sales_in_liters_inventory 		= $("input[name=dipstick_sales_in_liters_inventory]").val();
			var delivery_inventory 				= $("input[name=dipstick_delivery_inventory]").val();
			var ending_inventory 				= $("input[name=dipstick_ending_inventory]").val();
		
		if(beginning_inventory!=0 || beginning_inventory!=''){
			
				var TotalBookStock = beginning_inventory - sales_in_liters_inventory;
				var TotalVariance = TotalBookStock - ending_inventory;
				
				$('#dipstick_TotalBookStock').html(TotalBookStock.toLocaleString("en-PH", {minimumFractionDigits: 2}));
				$('#dipstick_TotalVariance').html(TotalVariance.toLocaleString("en-PH", {minimumFractionDigits: 2}));
			
		}
		
	}		

	function update_inventory_tank_dipstick()(){
		
			var beginning_inventory 			= $("input[name=dipstick_update_beginning_inventory]").val();
			var sales_in_liters_inventory 		= $("input[name=dipstick_update_sales_in_liters_inventory]").val();
			var delivery_inventory 				= $("input[name=dipstick_update_delivery_inventory]").val();
			var ending_inventory 				= $("input[name=dipstick_update_ending_inventory]").val();
		
		if(beginning_inventory!=0 || beginning_inventory!=''){
			
				var TotalBookStock = beginning_inventory - sales_in_liters_inventory;
				var TotalVariance = TotalBookStock - ending_inventory;
				
				$('#dipstick_update_TotalBookStock').html(TotalBookStock.toLocaleString("en-PH", {minimumFractionDigits: 2}));
				$('#dipstick_update_TotalVariance').html(TotalVariance.toLocaleString("en-PH", {minimumFractionDigits: 2}));
			
		}
		
	}

	LoadProductListForInventory_dipstick();
	
	function LoadProductListForInventory_dipstick() {		
	
		let branch_idx 			= $("#teves_branch").val();
	
		$("#product_list_inventory_dipstick span").remove();
		$('<span style="display: none;"></span>').appendTo('#product_list_inventory');

			  $.ajax({
				url: "{{ route('ProductListPricingPerBranch') }}",
				type:"POST",
				data:{
				  branch_idx:branch_idx,
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
	
							$('#product_list_inventory_dipstick span:last').after("<span style='font-family: DejaVu Sans; sans-serif;'>"+
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
	

	
	function LoadProductTank_dipstick() {		
	
		let branch_idx 			= $("#teves_branch").val();
		var product_id 			= $('#product_list_inventory_dipstick option[value="' + $('#product_idx_inventory_dipstick').val() + '"]').attr('data-id');
		
		$("#product_tank_list span").remove();
		$('<span style="display: none;"></span>').appendTo('#product_tank_list');

			  $.ajax({
				url: "{{ route('ProductTankPerBranch') }}",
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
						
							var tank_id = response[i].tank_id;						
							var tank_name = response[i].tank_name;
	
							$('#product_tank_list_dipstick span:last').after("<span style='font-family: DejaVu Sans; sans-serif;'>"+
							"<option label='"+tank_name+"' data-id='"+tank_id+"' value='"+tank_name+"'>" +
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
	
	
		function LoadProductTank_Update_dipstick() {		
	
		let branch_idx 			= $("#teves_branch").val();
		var product_id 			= $('#product_list_inventory option[value="' + $('#update_product_idx_inventory').val() + '"]').attr('data-id');
		
		$("#update_product_tank_list_dipstick span").remove();
		$('<span style="display: none;"></span>').appendTo('#update_product_tank_list_dipstick');

			  $.ajax({
				url: "{{ route('ProductTankPerBranch') }}",
				type:"POST",
				data:{
				  branchID:branch_idx,
				  productID:product_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){						
				  console.log(response);
				  if(response!='') {			
				  
						document.getElementById("update_product_tank_idx_inventory_dipstick").value 	= '';
						
						var len = response.length;
						for(var i=0; i<len; i++){
						
							var tank_id = response[i].tank_id;						
							var tank_name = response[i].tank_name;
	
							$('#update_product_tank_list_dipstick span:last').after("<span style='font-family: DejaVu Sans; sans-serif;'>"+
							"<option label='"+tank_name+"' data-id='"+tank_id+"' value='"+tank_name+"'>" +
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
	
	/*Call Function for Product Cahier's Report*/
	$("#save-CRPH8").click(function(event){
		
			event.preventDefault();
			
				$('#product_idx_inventoryError').text('');	
				$('#product_tank_idx_inventoryError').text('');					
				$('#beginning_inventoryError').text('');		
				$('#delivery_inventoryError').text('');
				$('#ending_inventoryError').text('');
				$('#sales_in_liters_inventoryError').text('');
			
			let CashiersReportId 				= {{ $CashiersReportId }};			
		
			var product_idx 					= $('#product_list_inventory option[value="' + $('#product_idx_inventory').val() + '"]').attr('data-id');
			var tank_idx 						= $('#product_tank_list option[value="' + $('#product_tank_idx_inventory').val() + '"]').attr('data-id');
			var beginning_inventory 			= $("input[name=dipstick_beginning_inventory]").val();
			var sales_in_liters_inventory 		= $("input[name=dipstick_sales_in_liters_inventory]").val();
			var delivery_inventory 				= $("input[name=dipstick_delivery_inventory]").val();
			var ending_inventory 				= $("input[name=dipstick_ending_inventory]").val();
			
			document.getElementById('CRPH8_form').className = "g-3 needs-validation was-validated";
			
				/*Delete the Selected Item*/			
				  $.ajax({
					url: "{{ route('SAVE_CHR_PH8') }}",
					type:"POST",
					data:{
					  CRPH8_ID:0,
					  CashiersReportId:CashiersReportId,
					  product_idx:product_idx,
					  tank_idx:tank_idx,
					  beginning_inventory:beginning_inventory,
					  sales_in_liters_inventory:sales_in_liters_inventory,
					  delivery_inventory:delivery_inventory,
					  ending_inventory:ending_inventory, 
					  _token: "{{ csrf_token() }}"
					},
					success:function(response){
					  console.log(response);
					  if(response) {
						  
						$('#switch_notice_on').show();
						$('#sw_on').html(response.success);
						setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
							
						$('#product_idx_inventoryError').text('');	
						$('#product_tank_idx_inventoryError').text('');					
						$('#beginning_inventoryError').text('');		
						$('#delivery_inventoryError').text('');
						$('#ending_inventoryError').text('');
						$('#sales_in_liters_inventoryError').text('');
						
						/*Clear Form*/
						document.getElementById("product_idx_inventory").value = '';
						document.getElementById("product_tank_idx_inventory").value = '';
						document.getElementById("beginning_inventory").value = '';
						document.getElementById("sales_in_liters_inventory").value = '';
						document.getElementById("delivery_inventory").value = '';
						document.getElementById("ending_inventory").value = '';
						
						LoadCashiersReportPH8();
						
					  }
					},
					error: function(error) {
					 console.log(error);
					 
							$('#product_idx_inventoryError').text(error.responseJSON.errors.product_idx);
							document.getElementById('product_idx_inventoryError').className = "invalid-feedback";
							
							$('#product_tank_idx_inventoryError').text(error.responseJSON.errors.tank_idx);
							document.getElementById('product_tank_idx_inventoryError').className = "invalid-feedback";
							
							$('#beginning_inventoryError').text(error.responseJSON.errors.beginning_inventory);
							document.getElementById('beginning_inventoryError').className = "invalid-feedback";
							
							$('#delivery_inventoryError').text(error.responseJSON.errors.delivery);
							document.getElementById('delivery_inventoryError').className = "invalid-feedback";
							
							$('#ending_inventoryError').text(error.responseJSON.errors.ending_inventory);
							document.getElementById('ending_inventoryError').className = "invalid-feedback";
							
							$('#sales_in_liters_inventoryError').text(error.responseJSON.errors.sales_in_liters);
							document.getElementById('sales_in_liters_inventoryError').className = "invalid-feedback";
							
					}
				   });		
		 });
		
	$('body').on('click','#CHPH8_Edit',function(){		
	
			event.preventDefault();
			let CHPH8_ID = $(this).data('id');	
			
			$.ajax({
				url: "{{ route('CRP6_info') }}",
				type:"POST",
				data:{
				  CHPH8_ID:CHPH8_ID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  
				  if(response) {				
				
					document.getElementById("update-CRPH8").value = CHPH8_ID;				
					/*Set Details*/					
					
					document.getElementById("update_product_idx_inventory").value 		= response[0].product_name;
					document.getElementById("update_product_tank_idx_inventory").value 	= response[0].tank_name;
					
					document.getElementById("update_beginning_inventory").value 		= response[0].beginning_inventory;
					document.getElementById("update_sales_in_liters_inventory").value 	= response[0].sales_in_liters;
					
					document.getElementById("update_delivery_inventory").value 			= response[0].delivery;
					document.getElementById("update_ending_inventory").value 			= response[0].ending_inventory;
					
					var TotalBookStock 	= response[0].beginning_inventory - response[0].sales_in_liters;
					var TotalVariance 	= TotalBookStock - response[0].ending_inventory;
				
					$('#update_TotalBookStock').html(TotalBookStock.toLocaleString("en-PH", {minimumFractionDigits: 2}));
					$('#update_TotalVariance').html(TotalVariance.toLocaleString("en-PH", {minimumFractionDigits: 2}));
					
					
					$('#Update_CRPH8_Modal').modal('toggle');	
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	});


	/*Call Function for Product Cahier's Report*/
	$("#update-CRPH8").click(function(event){
		
			event.preventDefault();
			
				$('#update_product_idx_inventoryError').text('');	
				$('#update_product_tank_idx_inventoryError').text('');					
				$('#update_beginning_inventoryError').text('');		
				$('#update_delivery_inventoryError').text('');
				$('#update_ending_inventoryError').text('');
				$('#update_sales_in_liters_inventoryError').text('');
			
			let CashiersReportId 				= {{ $CashiersReportId }};			
			
			let CRPH8_ID = document.getElementById("update-CRPH8").value;
			
			var product_idx 					= $('#product_list_inventory option[value="' + $('#update_product_idx_inventory').val() + '"]').attr('data-id');
			var tank_idx 						= $('#update_product_tank_list option[value="' + $('#update_product_tank_idx_inventory').val() + '"]').attr('data-id');
			var beginning_inventory 			= $("input[name=dipstick_update_beginning_inventory]").val();
			var sales_in_liters_inventory 		= $("input[name=dipstick_update_sales_in_liters_inventory]").val();
			var delivery_inventory 				= $("input[name=dipstick_update_delivery_inventory]").val();
			var ending_inventory 				= $("input[name=dipstick_update_ending_inventory]").val();
			
			document.getElementById('CRPH8_form_edit').className = "g-3 needs-validation was-validated";
			
				/*Delete the Selected Item*/			
				  $.ajax({
					url: "{{ route('SAVE_CHR_PH8') }}",
					type:"POST",
					data:{
					  CRPH8_ID:CRPH8_ID,
					  CashiersReportId:CashiersReportId,
					  product_idx:product_idx,
					  tank_idx:tank_idx,
					  beginning_inventory:beginning_inventory,
					  sales_in_liters_inventory:sales_in_liters_inventory,
					  delivery_inventory:delivery_inventory,
					  ending_inventory:ending_inventory, 
					  _token: "{{ csrf_token() }}"
					},
					success:function(response){
					  console.log(response);
					  if(response) {
						  
						$('#switch_notice_on').show();
						$('#sw_on').html(response.success);
						setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
							
						$('#update_product_idx_inventoryError').text('');	
						$('#update_product_tank_idx_inventoryError').text('');					
						$('#update_beginning_inventoryError').text('');		
						$('#update_delivery_inventoryError').text('');
						$('#update_ending_inventoryError').text('');
						$('#update_sales_in_liters_inventoryError').text('');
						
						/*Clear Form*/
						document.getElementById("update_product_idx_inventory").value = '';
						document.getElementById("update_product_tank_idx_inventory").value = '';
						document.getElementById("update_beginning_inventory").value = '';
						document.getElementById("update_sales_in_liters_inventory").value = '';
						document.getElementById("update_delivery_inventory").value = '';
						document.getElementById("update_ending_inventory").value = '';
						
						LoadCashiersReportPH8();
						$('#Update_CRPH8_Modal').modal('toggle');	
						
					  }
					},
					error: function(error) {
					 console.log(error);
					 
							$('#update_product_idx_inventoryError').text(error.responseJSON.errors.product_idx);
							document.getElementById('update_product_idx_inventoryError').className = "invalid-feedback";
							
							$('#update_product_tank_idx_inventoryError').text(error.responseJSON.errors.tank_idx);
							document.getElementById('update_product_tank_idx_inventoryError').className = "invalid-feedback";
							
							$('#update_beginning_inventoryError').text(error.responseJSON.errors.beginning_inventory);
							document.getElementById('update_beginning_inventoryError').className = "invalid-feedback";
							
							$('#update_delivery_inventoryError').text(error.responseJSON.errors.delivery);
							document.getElementById('update_delivery_inventoryError').className = "invalid-feedback";
							
							$('#update_ending_inventoryError').text(error.responseJSON.errors.ending_inventory);
							document.getElementById('update_ending_inventoryError').className = "invalid-feedback";
							
							$('#update_sales_in_liters_inventoryError').text(error.responseJSON.errors.sales_in_liters);
							document.getElementById('update_sales_in_liters_inventoryError').className = "invalid-feedback";
							
					}
				   });		
		 });

	LoadCashiersReportPH8(); 

	function LoadCashiersReportPH8() {		
	
		$("#table_product_inventory_body_data tr").remove();
		$('<tr style="display: none;"><td>HIDDEN</td></tr>').appendTo('#table_product_inventory_body_data');
		let CashiersReportId 			= {{ $CashiersReportId }};	
			  $.ajax({
				url: "{{ route('GetCashiersP6') }}",
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

							var cashiers_report_p6_id = response[i].cashiers_report_p6_id;
						
							var product_name = response[i].product_name;
							var tank_name = response[i].tank_name;		
							
							var beginning_inventory = response[i].beginning_inventory.toLocaleString("en-PH", {maximumFractionDigits: 2});
							var sales_in_liters = response[i].sales_in_liters.toLocaleString("en-PH", {maximumFractionDigits: 2});
							var delivery = response[i].delivery.toLocaleString("en-PH", {maximumFractionDigits: 2});
							var ending_inventory = response[i].ending_inventory.toLocaleString("en-PH", {maximumFractionDigits: 2});
							var book_stock = response[i].book_stock.toLocaleString("en-PH", {maximumFractionDigits: 2});
							var variance = response[i].variance.toLocaleString("en-PH", {maximumFractionDigits: 2});
							
							$('#table_product_inventory_body_data tr:last').after("<tr>"+
							"<td class='product_td' align='left'>"+product_name+"</td>"+
							"<td class='beginning_reading_inventory_td' align='left'>"+tank_name+"</td>"+
							"<td class='sales_in_liters_inventory_td' align='right'>"+beginning_inventory+"</td>"+
							"<td class='delivery_inventory_td' align='right'>"+sales_in_liters+"</td>"+
							"<td class='delivery_inventory_td' align='right'>"+delivery+"</td>"+
							"<td class='manual_price_td' align='right'>"+ending_inventory+"</td>"+
							"<td class='manual_price_td' align='right'>"+book_stock+"</td>"+
							"<td class='manual_price_td' align='right'>"+variance+"</td>"+
							
							"<td><div align='center' class='action_table_menu_Product' style='margin-top: 6px;'><a href='#' class='btn-danger btn-circle btn-sm bi-pencil-fill btn_icon_table btn_icon_table_edit' id='CHPH8_Edit' data-id='"+cashiers_report_p6_id+"'></a> <a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='CHPH8_Delete'  data-id='"+cashiers_report_p6_id+"'></a></div></td>"+
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

	$('body').on('click','#CHPH8_Delete',function(){		
	
			event.preventDefault();
			let CHPH8_ID = $(this).data('id');	
			
			$.ajax({
				url: "{{ route('CRP6_info') }}",
				type:"POST",
				data:{
				  CHPH8_ID:CHPH8_ID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  
				  if(response) {				
				
					document.getElementById("deleteCRPH8Confirmed").value = CHPH8_ID;				
					/*Set Details*/					
					
					var TotalBookStock 	= response[0].beginning_inventory - response[0].sales_in_liters;
					var TotalVariance 	= TotalBookStock - response[0].ending_inventory;
					
					$('#delete_product_idx_inventory').text(response[0].product_name);
					$('#delete_product_tank_idx_inventory').text(response[0].tank_name);
					$('#delete_beginning_inventory').text(response[0].beginning_inventory);
					$('#delete_sales_in_liters_inventory').text(response[0].sales_in_liters);
					$('#delete_delivery_inventory').text(response[0].delivery);
					$('#delete_ending_inventory').text(response[0].ending_inventory);
					
					$('#CRPH8_delete_TotalBookStock').text(TotalBookStock);
					$('#CRPH8_delete_TotalVariance').text(TotalVariance);
					
					$('#CRPH8DeleteModal').modal('toggle');	
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	});


	$('body').on('click','#deleteCRPH8Confirmed',function(){
			
		let CHPH8_ID = document.getElementById("deleteCRPH8Confirmed").value;
		
		if(CHPH8_ID!=0){
			/*Delete the Selected Item*/	
			  $.ajax({
				url: "{{ route('DeleteCashiersProductP6') }}",
				type:"POST",
				data:{
				  CHPH8_ID:CHPH8_ID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					$('#switch_notice_off').show();
					$('#sw_off').html("Deleted");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);	
					LoadCashiersReportPH8();
				  }
				},
				error: function(error) {
				 console.log(error);
				}
			   });		
		}	
	});
</script>
