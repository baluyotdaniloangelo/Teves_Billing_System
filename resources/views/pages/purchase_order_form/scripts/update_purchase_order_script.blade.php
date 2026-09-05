
<script type="text/javascript">
// Update Purchase Order
	$("#update-purchase-order").click(function(event){

			event.preventDefault();

			document.getElementById('PurchaseOrderformUpdate').className = "g-3 needs-validation was-validated";
			
			let purchase_order_id						= document.getElementById("update-purchase-order").value;
			let purchase_order_date 					= $("input[name=purchase_order_date]").val();
			let supplier_idx 							= $('#supplier_name_list option[value="' + $('#supplier_idx').val() + '"]').attr('data-id');
			
			/*Supplier's Name and Product Name*/
			let supplier_name 							= $("input[name=supplier_name]").val();
			/*Added May 6, 2023*/
			let company_header 							= $("#company_header").val();
			let purchase_order_type 					= $("#purchase_order_type").val();
			let purchase_order_invoice 					= $("#purchase_order_invoice").val();
			
			let purchase_order_sales_order_number 		= $("input[name=purchase_order_sales_order_number]").val();
			let purchase_order_collection_receipt_no 	= $("input[name=purchase_order_collection_receipt_no]").val();
			let purchase_order_official_receipt_no 		= $("input[name=purchase_order_official_receipt_no]").val();
			let purchase_order_delivery_receipt_no 		= $("input[name=purchase_order_delivery_receipt_no]").val();
			let purchase_order_delivery_method 			= $("#purchase_order_delivery_method").val();
			let purchase_loading_terminal 				= $("#purchase_loading_terminal").val();
			let purchase_order_net_percentage 			= $("input[name=purchase_order_net_percentage]").val();
			let purchase_order_less_percentage 			= $("input[name=purchase_order_less_percentage]").val();
			let hauler_operator 						= $("input[name=hauler_operator]").val();
			let lorry_driver 							= $("input[name=lorry_driver]").val();
			let plate_number 							= $("input[name=plate_number]").val();
			let purchase_destination 					= $("#purchase_destination").val(); 
			let purchase_order_instructions 			= $("#purchase_order_instructions").val();
			let purchase_order_note 					= $("#purchase_order_note").val();
				 
			  $.ajax({
				url: "/update_purchase_order_post",
				type:"POST",
				data:{
					purchase_order_id:purchase_order_id,
					purchase_order_date:purchase_order_date,
					supplier_idx:supplier_idx,
					purchase_order_type:purchase_order_type,
					company_header:company_header,
					purchase_order_invoice:purchase_order_invoice,
					purchase_order_sales_order_number:purchase_order_sales_order_number,
					purchase_order_collection_receipt_no:purchase_order_collection_receipt_no,
					purchase_order_official_receipt_no:purchase_order_official_receipt_no,
					purchase_order_delivery_receipt_no:purchase_order_delivery_receipt_no,
					purchase_order_delivery_method:purchase_order_delivery_method,
					purchase_loading_terminal:purchase_loading_terminal,
					purchase_order_net_percentage:purchase_order_net_percentage,
					purchase_order_less_percentage:purchase_order_less_percentage,
					hauler_operator:hauler_operator,
					lorry_driver:lorry_driver,
					plate_number:plate_number,
					purchase_destination:purchase_destination,
					purchase_order_instructions:purchase_order_instructions,
					purchase_order_note:purchase_order_note,
					_token: "{{ csrf_token() }}"
				},		
				success:function(response){
				  console.log(response);
				  if(response) {
					
					showSuccessModal(response.success);

				  }
				},
				beforeSend:function()
				{
					
					/*Disable Submit Button*/
					document.getElementById("update-purchase-order").disabled = true;
					/*Show Status*/
					$('#update_loading_data').show();
					
				},
				complete: function(){
						
					/*Enable Submit Button*/
					document.getElementById("update-purchase-order").disabled = false;
					/*Hide Status*/
					$('#update_loading_data').hide();	
					
				},
				error: function(error) {
					
				 console.log(error);	
				
					if(error.responseJSON.errors.supplier_idx=="Supplier's Name is Required"){
						
							if(supplier_idx==''){
								$('#supplier_idxError').html(error.responseJSON.errors.supplier_idx);
							}else{
								$('#supplier_idxError').html("Incorrect Supplier's Name <b>" + supplier_name + "</b>");
							}
						
							document.getElementById("supplier_idx").value = "";
							document.getElementById('supplier_idxError').className = "invalid-feedback";
							
					}

					console.log(error);
					handleValidation(error);
					$('#action_error_message').text('Validation Error');
					
				}
			   });	
	});	


	<!--Select Supplier-->
	function SupplierInfo(){
			
			let supplierID = $("#supplier_name_list option[value='" + $('#supplier_idx').val() + "']").attr('data-id');
			
			  $.ajax({
				url: "/supplier_info",
				type:"POST",
				data:{
				  supplierID:supplierID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					/*Set Details*/
					
					document.getElementById("purchase_order_less_percentage").value = response.default_withholding_tax_percentage;
					document.getElementById("purchase_order_net_percentage").value = response.default_net_percentage;				
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	}
	

</script>
