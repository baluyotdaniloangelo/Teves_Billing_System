   <script type="text/javascript">
   
	LoadProduct();
	//LoadPayment();
	LoadReceivables();
	
	function ResetPaymentForm(){
		/*Reset Form*/
		document.getElementById("AddPayment").reset();
		/*Hide Image Reference Div*/
		$("#image_payment_div").hide();
		/*Reset Payment Id*/
		document.getElementById("receivable_payment_id").value = 0;
	}
	
	function UpdateBranch(){ 
	
		$('#switch_notice_off').show();
		$('#sw_off').html("You selected a new branch, to confirm changes click the update button");
		setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },2000);
		
		/*Disable the Add Product Button Until Changes not Save*/
		document.getElementById("AddSalesOrderProductBTN").disabled = true;
		
	}

	<!--Select Receivable For Update-->	
	function LoadReceivables(){ 
			
			  $.ajax({
				url: "/receivable_info",
				type:"POST",
				data:{
				  receivable_id: {{ @$receivables_details['receivable_id'] }},
				  
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					/*Set Details*/
					document.getElementById("receivable_amount_info").innerHTML = response[0].receivable_amount;
									
					document.getElementById("client_name_receivables").innerHTML = response[0].client_name;
					
					document.getElementById("billing_date").value = response[0].billing_date;
					document.getElementById("payment_term").value = response[0].payment_term;
					document.getElementById("receivable_description").textContent = response[0].receivable_description;						
					document.getElementById("start_date").value = response[0].billing_period_start;
					document.getElementById("end_date").value = response[0].billing_period_end;
					document.getElementById("less_per_liter").value = response[0].less_per_liter;
					document.getElementById("company_header").value = response[0].company_header;					
					document.getElementById("withholding_tax_percentage").value = response[0].receivable_withholding_tax_percentage;
					document.getElementById("net_value_percentage").value = response[0].receivable_net_value_percentage;
					document.getElementById("vat_value_percentage").value = response[0].receivable_vat_value_percentage;						
							
					
					$('#UpdateReceivablesModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	}

	
	<!--Save New receivables->
	$("#SO-update-receivables").click(function(event){
			
			event.preventDefault();
			$('#receivable_description_SO_Error').text('');

			document.getElementById('ReceivableformEditFromSalesOrder').className = "g-3 needs-validation was-validated";
			
			let ReceivableID 			= {{ @$receivables_details['receivable_id'] }};

			let billing_date 			= $("input[name=billing_date]").val();			
			let payment_term 			= $("input[name=payment_term]").val();
			let receivable_description 	= $("#receivable_description").val();
			let receivable_status 		= $("#receivable_status").val();
			
			let start_date 			= $("#start_date").val();
			let end_date 			= $("#end_date").val();
			let less_per_liter 		= $("#less_per_liter").val();
			/*Added May 6, 2023*/
			let company_header 		= $("#company_header").val();
			/*Added June 4, 2023*/
			let withholding_tax_percentage 	= $("input[name=withholding_tax_percentage]").val() / 100;
			let net_value_percentage 		= $("input[name=net_value_percentage]").val();
			let vat_value_percentage 		= $("input[name=vat_value_percentage]").val() / 100;
			
			$.ajax({
				url: "/update_receivables_post",
				type:"POST",
				data:{
				  ReceivableID:ReceivableID,
				  billing_date:billing_date,
				  payment_term:payment_term,
				  receivable_description:receivable_description,
				  receivable_status:receivable_status,
				  less_per_liter:less_per_liter,
				  start_date:start_date,
				  end_date:end_date,
				  company_header:company_header,				  
				  withholding_tax_percentage:withholding_tax_percentage,				  
				  net_value_percentage:net_value_percentage,			  
				  vat_value_percentage:vat_value_percentage,
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

				  }
				},
				beforeSend:function()
				{
					$('#update_loading_data').show();
				},
				complete: function(){
					$('#update_loading_data').hide();
				},
				error: function(error) {
				 console.log(error);	
				
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
	  
	/*Load Product from Billing*/  
	function LoadProduct() {
		
		$("#table_sales_order_product_body_data tr").remove();
		$('<tr style="display: none;"><td>HIDDEN</td></tr>').appendTo('#table_sales_order_product_body_data');
		
		let receivable_id = {{ @$receivables_details['receivable_id'] }};
		let client_idx = {{ @$receivables_details['client_idx'] }};
		let start_date 			= '{{ @$receivables_details['billing_period_start'] }}';
		let end_date 			= '{{ @$receivables_details['billing_period_end'] }}';
			
			  $.ajax({
				url: "/billing_to_receivable_product",
				type:"POST",
				data:{
				
				  receivable_id:receivable_id,
				  client_idx:client_idx,
				  start_date:start_date,
				  end_date:end_date,
				  
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){						
				  
				  console.log(response['productlist']);
				  var len = response['productlist'].length;
				  
					  if(response['productlist']!='') {			  
							
							if(response['paymentcount']!=0){
							
								$(".action_column_class").hide();
								
								for(var i=0; i<len; i++){
							
								var id = response['productlist'][i].sales_order_component_id;
								var product_name = response['productlist'][i].product_name;
								var product_unit_measurement = response['productlist'][i].product_unit_measurement;
								var product_price = response['productlist'][i].product_price;
								var order_quantity = response['productlist'][i].order_quantity;
								
								var order_total_amount = response['productlist'][i].order_total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2});
								
								$('#table_sales_order_product_body_data tr:last').after("<tr>"+
								"<td align='center'>" + (i+1) + "</td>" +
								"<td class='product_td' align='left'>"+product_name+"</td>"+
								"<td class='manual_price_td' align='right'>"+product_price+"</td>"+
								"<td class='calibration_td' align='right'>"+order_quantity+"</td>"+
								"<td class='calibration_td' align='center'>"+product_unit_measurement+"</td>"+
								"<td class='manual_price_td' align='right'>"+order_total_amount+"</td>"+
								"</tr>");
								
								}
								
							}else{
								
								$(".action_column_class").show();
								
								for(var i=0; i<len; i++){
							
								var id = response['productlist'][i].sales_order_component_id;
								var product_name = response['productlist'][i].product_name;
								var product_unit_measurement = response['productlist'][i].product_unit_measurement;
								var product_price = response['productlist'][i].product_price;
								var order_quantity = response['productlist'][i].order_quantity;
								
								var order_total_amount = response['productlist'][i].order_total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2});
								
								$('#table_sales_order_product_body_data tr:last').after("<tr>"+
								"<td class='action_column_class'><div align='center' class='action_table_menu_Product' ><a href='#' class='btn-danger btn-circle btn-sm bi-pencil-fill btn_icon_table btn_icon_table_edit' id='SalesOrderProduct_Edit' data-id='"+id+"'></a> <a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deleteSalesOrderComponentProduct'  data-id='"+id+"'></a></div></td>"+
								"<td align='center'>" + (i+1) + "</td>" +
								"<td class='product_td' align='left'>"+product_name+"</td>"+
								"<td class='manual_price_td' align='right'>"+product_price+"</td>"+
								"<td class='calibration_td' align='right'>"+order_quantity+"</td>"+
								"<td class='calibration_td' align='center'>"+product_unit_measurement+"</td>"+
								"<td class='manual_price_td' align='right'>"+order_total_amount+"</td>"+
								"</tr>");
								}
								
							}
					  }
				  else{
							/*No Result Found or Error*/	
				  }
				},
				error: function(error) {
				 console.log(error);	 
				}
			   });
	  } 	
 </script>