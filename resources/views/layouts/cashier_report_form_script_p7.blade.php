   <script type="text/javascript">
	/*Load Cashiers Report Phase 1*/
	//LoadCashiersReportPH1();

/*	function TotalAmount(){
		
		let product_price 			= $('#product_name_inventory option[value="' + $('#product_idx_inventory').val() + '"]').attr('data-price');
		let ending_inventory 	= $("#ending_inventory").val();	
		var beginning_reading_inventory 		= $("input[name=beginning_reading_inventory]").val();
		var sales_in_liters_inventory 		= $("input[name=sales_in_liters_inventory]").val();
		var delivery_inventory 			= $("input[name=delivery_inventory]").val();	
		let order_quantity 			= (sales_in_liters_inventory - beginning_reading_inventory) - delivery_inventory;
		
		if(order_quantity!=0 || order_quantity!=''){
			if(ending_inventory!='' && ending_inventory!=0){
				var total_amount = ending_inventory * order_quantity;
				$('#TotalAmount').html(total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}));
			}else{
				var total_amount = product_price * order_quantity;
				$('#TotalAmount').html(total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}));
			}
		}		
	}
	
	function UpdateTotalAmount(){
		
		let product_price 			= $('#update_product_name_inventory option[value="' + $('#update_product_idx_inventory').val() + '"]').attr('data-price');
		let ending_inventory 	= $("#update_ending_inventory").val();		
		var beginning_reading_inventory 		= $("input[name=update_beginning_reading_inventory]").val();
		var sales_in_liters_inventory 		= $("input[name=update_sales_in_liters_inventory]").val();
		var delivery_inventory 			= $("input[name=update_delivery_inventory]").val();		
		let order_quantity 			= (sales_in_liters_inventory - beginning_reading_inventory) - delivery_inventory;
		
		if(order_quantity!=0 || order_quantity!=''){
			if(ending_inventory!='' && ending_inventory!=0){
				var total_amount = ending_inventory * order_quantity;
				$('#UpdateTotalAmount').html(total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}));
			}else{
				var total_amount = product_price * order_quantity;
				$('#UpdateTotalAmount').html(total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}));
			}
		}
		
	}		
*/
	function LoadProductTank() {		
	
		let branch_idx 			= $("#teves_branch").val();
		var product_id 			= $('#product_name_inventory option[value="' + $('#product_idx_inventory').val() + '"]').attr('data-id');
		
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
	
							$('#product_tank_list span:last').after("<span style='font-family: DejaVu Sans; sans-serif;'>"+
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
	$("#save-CRPH6").click(function(event){
		
			event.preventDefault();
			
				$('#product_idx_inventoryError').text('');	
				$('#product_tank_idx_inventoryError').text('');					
				$('#beginning_inventoryError').text('');		
				$('#delivery_inventoryError').text('');
				$('#ending_inventoryError').text('');
				$('#sales_in_liters_inventoryError').text('');
			
			let CashiersReportId 				= {{ $CashiersReportId }};			
		
			var product_idx 					= $('#product_name_inventory option[value="' + $('#product_idx_inventory').val() + '"]').attr('data-id');
			var tank_idx 						= $('#product_tank_list option[value="' + $('#product_tank_idx_inventory').val() + '"]').attr('data-id');
			var beginning_inventory 			= $("input[name=beginning_inventory]").val();
			var sales_in_liters_inventory 		= $("input[name=sales_in_liters_inventory]").val();
			var delivery_inventory 				= $("input[name=delivery_inventory]").val();
			var ending_inventory 				= $("input[name=ending_inventory]").val();
			
			document.getElementById('CRPH6_form').className = "g-3 needs-validation was-validated";
			
				/*Delete the Selected Item*/			
				  $.ajax({
					url: "{{ route('SAVE_CHR_PH6') }}",
					type:"POST",
					data:{
					  CRPH6_ID:0,
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
						
						LoadCashiersReportPH6();
						
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
		 
	LoadCashiersReportPH6(); 

	function LoadCashiersReportPH6() {		
	
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
						
						
						/*
						'teves_product_table.product_id',
						'teves_product_table.product_name',
						'teves_product_tank_table.tank_id',
						'teves_product_tank_table.tank_name',
						'teves_product_tank_table.tank_capacity',
						'teves_cashiers_report_p6.cashiers_report_p6_id',
						'teves_cashiers_report_p6.beginning_inventory',
						'teves_cashiers_report_p6.sales_in_liters',
						'teves_cashiers_report_p6.delivery',
						'teves_cashiers_report_p6.ending_inventory',
						'teves_cashiers_report_p6.book_stock',
						'teves_cashiers_report_p6.variance'
						*/
							
							var cashiers_report_p6_id = response[i].cashiers_report_p6_id;
						
							var product_name = response[i].product_name;
							var tank_name = response[i].tank_name;		
							
							var beginning_inventory = response[i].beginning_inventory.toLocaleString("en-PH", {maximumFractionDigits: 2});
							var sales_in_liters = response[i].sales_in_liters.toLocaleString("en-PH", {maximumFractionDigits: 2});
							var delivery = response[i].delivery.toLocaleString("en-PH", {maximumFractionDigits: 2});
							var ending_inventory = response[i].ending_inventory.toLocaleString("en-PH", {maximumFractionDigits: 2});
							var book_stock = response[i].book_stock.toLocaleString("en-PH", {maximumFractionDigits: 2});
							var variance = response[i].variance.toLocaleString("en-PH", {maximumFractionDigits: 2});
							
							//var sales_in_liters = response[i].product_name_inventory;
							//var beginning_reading_inventory = response[i].beginning_reading_inventory;
							//var order_quantity = response[i].order_quantity.toLocaleString("en-PH", {maximumFractionDigits: 2});
							//var sales_in_liters_inventory = response[i].sales_in_liters_inventory;
							//var delivery_inventory = response[i].delivery_inventory;
							//var order_total_amount = response[i].order_total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2});
							
							$('#table_product_inventory_body_data tr:last').after("<tr>"+
							"<td class='product_td' align='left'>"+product_name+"</td>"+
							"<td class='beginning_reading_inventory_td' align='left'>"+tank_name+"</td>"+
							"<td class='sales_in_liters_inventory_td' align='right'>"+beginning_inventory+"</td>"+
							"<td class='delivery_inventory_td' align='right'>"+sales_in_liters+"</td>"+
							"<td class='delivery_inventory_td' align='right'>"+delivery+"</td>"+
							"<td class='manual_price_td' align='right'>"+ending_inventory+"</td>"+
							"<td class='manual_price_td' align='right'>"+book_stock+"</td>"+
							"<td class='manual_price_td' align='right'>"+variance+"</td>"+
							
							"<td><div align='center' class='action_table_menu_Product' style='margin-top: 6px;'><a href='#' class='btn-danger btn-circle btn-sm bi-pencil-fill btn_icon_table btn_icon_table_edit' id='CRPH6_Edit' data-id='"+cashiers_report_p6_id+"'></a> <a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deleteCashiersProductP1'  data-id='"+cashiers_report_p6_id+"'></a></div></td>"+
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



</script>
