
<script type="text/javascript">
	LoadProduct();
	LoadSuppliersPriceList();
	function LoadProduct() {	
		
		$("#table_purchase_order_product_body_data tr").remove();
		$('<tr style="display: none;"><td>HIDDEN</td></tr>').appendTo('#table_purchase_order_product_body_data');

		$("#product_list_delivery span").remove();
		$('<span style="display: none;"></span>').appendTo('#product_list_delivery');

			  $.ajax({
				url: "/get_purchase_order_product_list",
				type:"POST",
				data:{
					purchase_order_id:{{ $PurchaseOrderID }},
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){						
				  console.log(response['productlist']);
				  if(response['productlist']!='') {			  
				  
						var len = response['productlist'].length;

						if(response['paymentcount']!=0){
							
							$(".action_column_class").hide();
							document.getElementById("AddPurchaseOrderProductBTN").disabled = true;
							
							for(var i=0; i<len; i++){
								
								var id = response['productlist'][i].purchase_order_component_id;
								var product_idx = response['productlist'][i].product_idx;
								var product_name = response['productlist'][i].product_name;
								var product_unit_measurement = response['productlist'][i].product_unit_measurement;
								var product_price = response['productlist'][i].product_price;
								var order_quantity = response['productlist'][i].order_quantity;
								
								var order_total_amount = response['productlist'][i].order_total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2});
									
								$('#table_purchase_order_product_body_data tr:last').after("<tr>"+
								"<td align='center'>" + (i+1) + "</td>" +
								"<td class='product_td' align='left'>"+product_name+"</td>"+
								"<td class='manual_price_td' align='center'>"+product_price+"</td>"+
								"<td class='calibration_td' align='center'>"+order_quantity+" "+product_unit_measurement+"</td>"+
								"<td class='manual_price_td' align='right'>"+order_total_amount+"</td>"+
								"</tr>");	
								
								$('#product_list_delivery span:last').after("<span style='font-family: DejaVu Sans; sans-serif;'>"+
								"<option label='Product:"+product_name + " | Quantity:"+order_quantity+"' data-id='"+id+"' product-id='"+product_idx+"' value='"+product_name+"'>" +
								"</span>");
							}
						
						}else{
							
							$(".action_column_class").show();
							document.getElementById("AddPurchaseOrderProductBTN").disabled = false;
						
							for(var i=0; i<len; i++){
								
								var id = response['productlist'][i].purchase_order_component_id;
								var product_idx = response['productlist'][i].product_idx;
								var product_name = response['productlist'][i].product_name;
								var product_unit_measurement = response['productlist'][i].product_unit_measurement;
								var product_price = response['productlist'][i].product_price;
								var order_quantity = response['productlist'][i].order_quantity;
								
								var order_total_amount = response['productlist'][i].order_total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2});
									
								$('#table_purchase_order_product_body_data tr:last').after("<tr>"+
								"<td align='center'>" + (i+1) + "</td>" +
								"<td><div align='center' class='action_table_menu_Product' ><a href='#' class='btn-danger btn-circle btn-sm bi-pencil-fill btn_icon_table btn_icon_table_edit' id='PurchaseOrderProduct_Edit' data-id='"+id+"'></a> <a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deletePurchaseOrderComponentProduct'  data-id='"+id+"'></a></div></td>"+
								"<td class='product_td' align='left'>"+product_name+"</td>"+
								"<td class='manual_price_td' align='center'>"+product_price+"</td>"+
								"<td class='calibration_td' align='center'>"+order_quantity+" "+product_unit_measurement+"</td>"+
								"<td class='manual_price_td' align='right'>"+order_total_amount+"</td>"+
								"</tr>");		

								$('#product_list_delivery span:last').after("<span style='font-family: DejaVu Sans; sans-serif;'>"+
								"<option label='Product:"+product_name + " | Quantity:"+order_quantity+"' data-id='"+id+"' product-id='"+product_idx+"' value='"+product_name+"'>" +
								"</span>");
								
							}							
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

	/*Not In Used*/
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
	/*Not In Used*/
	
	<!--Product-->
	$("#save-product").click(function(event){
		
			event.preventDefault();
			
			/*Reset Warnings*/
					
			$('#product_idxError').text('');
			$('#product_manual_priceError').text('');
			$('#order_quantityError').text('');

			document.getElementById('AddProduct').className = "g-3 needs-validation was-validated";
			
			let company_header 					= $("#company_header").val();
			let purchase_order_id 				= {{ $PurchaseOrderID }};
			let purchase_order_component_id 	= document.getElementById("save-product").value;
			let product_idx 					= $("#product_list option[value='" + $('#product_idx').val() + "']").attr('data-id');
			let product_manual_price 			= $("#product_manual_price").val();
			let order_quantity 					= $("input[name=order_quantity]").val();
			/*Product Name*/
			let product_name 					= $("input[name=product_name]").val();
			let purchase_order_net_percentage 	= $("input[name=purchase_order_net_percentage]").val();
			let purchase_order_less_percentage 	= $("input[name=purchase_order_less_percentage]").val();
			
			let supplier_idx 							= $('#supplier_name_list option[value="' + $('#supplier_idx').val() + '"]').attr('data-id');
			
			  $.ajax({
				url: "{{ route('PurchaseOrderProduct') }}",
				type:"POST",
				data:{
				  branch_idx:company_header,	
				  supplier_idx:supplier_idx,	
				  purchase_order_id:purchase_order_id,
				  purchase_order_component_id:purchase_order_component_id,
				  product_idx:product_idx,
				  item_description:product_name,
				  product_manual_price:product_manual_price,
				  order_quantity:order_quantity,
				  purchase_order_net_percentage:purchase_order_net_percentage,
				  purchase_order_less_percentage:purchase_order_less_percentage,
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
						
						document.getElementById("save-product").value = 0;
						
						$('#AddProductModal').modal('toggle');	
						
						}
						
						/*Reload Table*/
						LoadProduct();
									  
				},
				beforeSend:function()
				{
					/*Disable Submit Button*/
					document.getElementById("save-product").disabled = true;
					/*Show Status*/
					$('#loading_data_update_product').show();
			
				},
				complete: function(){
					
					/*Disable Submit Button*/
					document.getElementById("save-product").disabled = false;
					/*Hide Status*/
					$('#loading_data_update_product').hide();
				
				},
				error: function(error) {
					
				 console.log(error);	
				      
					if(error.responseJSON.errors.product_idx=='Product is Required'){
							
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
	  
	<!--Select Bill For Update-->
	$('body').on('click','#PurchaseOrderProduct_Edit',function(){
			
			event.preventDefault();
			let purchase_order_component_id = $(this).data('id');
			
			  $.ajax({
				url: "/purchase_order_product_info",
				type:"POST",
				data:{
				  purchase_order_component_id:purchase_order_component_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("save-product").value = purchase_order_component_id;
					
					/*Set Details*/
					document.getElementById("product_idx").value = response[0].product_name;
					document.getElementById("product_manual_price").value = response[0].product_price;
					document.getElementById("order_quantity").value = response[0].order_quantity;
					
					var total_amount = response[0].order_total_amount;
					$('#TotalAmount').html(total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}));
					
					$('#AddProductModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	});	  	  
	
	<!--Bill Deletion Confirmation-->
	$('body').on('click','#deletePurchaseOrderComponentProduct',function(){
			
			event.preventDefault();
			let purchase_order_component_id = $(this).data('id');
			
			  $.ajax({
				url: "/purchase_order_product_info",
				type:"POST",
				data:{
				  purchase_order_component_id:purchase_order_component_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("deletePurchaseOrderComponentConfirmed").value = purchase_order_component_id;
					
					/*Set Details*/
					$('#bill_delete_product_name').text(response[0].product_name);
					$('#bill_delete_order_quantity').text(response[0].order_quantity);					
					$('#bill_delete_order_total_amount').text(response[0].order_total_amount);

					$('#PurchaseOrderProductDeleteModal').modal('toggle');									  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });		
	});

	  <!-- Confirmed For Deletion-->
	  $('body').on('click','#deletePurchaseOrderComponentConfirmed',function(){
			
			event.preventDefault();
			let purchase_order_id 				= {{ $PurchaseOrderID }};
			let purchase_order_component_id 	= document.getElementById("deletePurchaseOrderComponentConfirmed").value;
			
			  $.ajax({
				url: "{{ route('PurchaseOrderDeleteProduct') }}",
				type:"POST",
				data:{
					purchase_order_id:purchase_order_id,
					purchase_order_component_id:purchase_order_component_id,
					_token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					$('#switch_notice_off').show();
					$('#sw_off').html("Purchase Order Product Deleted");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);	

					/*Reload Table*/
					LoadProduct();
					
				  }
				},
				error: function(error) {
				 console.log(error);
				}
			   });		
	});	 

	function LoadSuppliersPriceList() {	
		
		$("#product_list span").remove();
		$('<span style="display: none;"></span>').appendTo('#product_list');

		let supplier_idx 	= $('#update_supplier_name_list option[value="' + $('#update_supplier_idx').val() + '"]').attr('data-id');
		let branch_idx 		= $("#update_company_header").val();
		//alert(supplier_idx);
			  $.ajax({
				url: "/get_product_list_suppliers_price",
				type:"POST",
				data:{
					supplier_idx:supplier_idx,
					branch_idx:branch_idx,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){						
				  console.log(response['suppliers_price_list']);
				  if(response['suppliers_price_list']!='') {			  
				  
						var len = response['suppliers_price_list'].length;

						
							for(var i=0; i<len; i++){
								
								var product_idx = response['suppliers_price_list'][i].product_idx;
								var product_name = response['suppliers_price_list'][i].product_name;
								var product_unit_measurement = response['suppliers_price_list'][i].product_unit_measurement;
								var product_price = response['suppliers_price_list'][i].product_price;
								
								$('#product_list span:last').after("<span style='font-family: DejaVu Sans; sans-serif;'>"+
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
	
</script>
