<script type="text/javascript">


<!--Select Bill For Update-->
	$('body').on('click','[data-so-editor-action="delivery-edit"]',function(event){
            event.preventDefault();
			let sales_order_delivery_details_id = $(this).data('id');

			  salesOrderEditorRequest({
				url: "{{ route('SalesOrderDeliveryInfo') }}",
				type:"POST",
				data:{
				  sales_order_delivery_details_id:sales_order_delivery_details_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {

					/*Set Details*/
					document.getElementById("so-editor-save-product-delivery").value = sales_order_delivery_details_id;
					document.getElementById("so-editor-sales_order_delivery_date").value = response[0].sales_order_delivery_date;
					//document.getElementById("so-editor-sales_order_departure_date").value = response[0].sales_order_departure_date;
					document.getElementById("so-editor-sales_order_component_product_idx").value = response[0].product_name;
					document.getElementById("so-editor-sales_order_delivery_quantity").value = response[0].sales_order_delivery_quantity;
					document.getElementById("so-editor-sales_order_delivery_withdrawal_reference").value = response[0].sales_order_delivery_withdrawal_reference;
					document.getElementById("so-editor-sales_order_delivery_hauler_details").value = response[0].sales_order_delivery_hauler_details;
					document.getElementById("so-editor-sales_order_delivery_remarks").value = response[0].sales_order_delivery_remarks;
					//document.getElementById("so-editor-sales_order_branch_delivery").value = response[0].sales_order_branch_delivery;

					$('#so-editor-AddProductDeliveryModal').modal('toggle');

				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });
	});

	<!--Bill Deletion Confirmation-->
	$('body').on('click','[data-so-editor-action="delivery-delete"]',function(event){
            event.preventDefault();
			let sales_order_delivery_details_id = $(this).data('id');

			  salesOrderEditorRequest({
				url: "{{ route('SalesOrderDeliveryInfo') }}",
				type:"POST",
				data:{
				  sales_order_delivery_details_id:sales_order_delivery_details_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {

					document.getElementById("so-editor-deleteSalesOrderProdcutDeliveryConfirmed").value = sales_order_delivery_details_id;

					$('#so-editor-delete_delivery_sales_order_delivery_date').text(response[0].sales_order_delivery_date);
					//$('#so-editor-delete_delivery_sales_order_departure_date').text(response[0].sales_order_departure_date);
					$('#so-editor-delete_delivery_delete_product_name').text(response[0].product_name);
					$('#so-editor-delete_delivery_delete_sales_order_delivery_quantity').text(response[0].sales_order_delivery_quantity);

					$('#so-editor-delete_delivery_sales_order_delivery_withdrawal_reference').text(response[0].sales_order_delivery_withdrawal_reference);
					$('#so-editor-delete_delivery_sales_order_delivery_hauler_details').text(response[0].sales_order_delivery_hauler_details);
					$('#so-editor-delete_delivery_sales_order_delivery_remarks').text(response[0].sales_order_delivery_remarks);
					//$('#so-editor-delete_delivery_sales_order_branch_delivery').text(response[0].branch_name);

					$('#so-editor-SalesOrderProductDeliveryDeleteModal').modal('toggle');

				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });
	});


	<!--Bill Deletion Confirmation-->
	$('body').on('click','[data-so-editor-action="delivery-view"]',function(event){
            event.preventDefault();
			let sales_order_delivery_details_id = $(this).data('id');

			  salesOrderEditorRequest({
				url: "{{ route('SalesOrderDeliveryInfo') }}",
				type:"POST",
				data:{
				  sales_order_delivery_details_id:sales_order_delivery_details_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {

					$('#so-editor-view_delivery_sales_order_delivery_date').text(response[0].sales_order_delivery_date);
					//$('#so-editor-view_delivery_sales_order_departure_date').text(response[0].sales_order_departure_date);
					$('#so-editor-view_delivery_delete_product_name').text(response[0].product_name);
					$('#so-editor-view_delivery_delete_sales_order_delivery_quantity').text(response[0].sales_order_delivery_quantity);

					$('#so-editor-view_delivery_sales_order_delivery_withdrawal_reference').text(response[0].sales_order_delivery_withdrawal_reference);
					$('#so-editor-view_delivery_sales_order_delivery_hauler_details').text(response[0].sales_order_delivery_hauler_details);
					$('#so-editor-view_delivery_sales_order_delivery_remarks').text(response[0].sales_order_delivery_remarks);
					// $('#so-editor-view_delivery_sales_order_branch_delivery').text(response[0].branch_name);

					$('#so-editor-SalesOrderProductDeliveryViewModal').modal('toggle');

				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });
	});


	<!-- Confirmed For Deletion-->
	$('body').on('click','#so-editor-deleteSalesOrderProdcutDeliveryConfirmed',function(event){
            event.preventDefault();

			let sales_order_id 				= salesOrderEditorId();
			let receivable_id 				= salesOrderEditorReceivableId();

			let sales_order_delivery_details_id 	= document.getElementById("so-editor-deleteSalesOrderProdcutDeliveryConfirmed").value;

			  salesOrderEditorRequest({
				url: "{{ route('SalesOrderDeleteDelivery') }}",
				type:"POST",
				data:{
					sales_order_id:sales_order_id,
					receivable_id:receivable_id,
					sales_order_delivery_details_id:sales_order_delivery_details_id,
					_token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {

					$('#switch_notice_off').show();
					$('#sw_off').html("Sales Order Product Delivery Deleted");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);

					/*Reload Table*/
					LoadProductListDelivery();

				  }
				},
				error: function(error) {
				 console.log(error);
				}
			   });
	});


function LoadProductListDelivery() {

		$("#so-editor-product_list_delivery_data tr").remove();
		$('<tr style="display: none;"><td>HIDDEN</td></tr>').appendTo('#so-editor-product_list_delivery_data');

		let receivable_id = salesOrderEditorReceivableId();

			  salesOrderEditorRequest({
				url: "{{ route('ProductListDelivery') }}",
				type:"POST",
				data:{
				  sales_order_id:salesOrderEditorId(),
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){

				  console.log(response['productlist_delivery']);
				  var len = response['productlist_delivery'].length;

					  if(response['productlist_delivery']!='') {

								for(var i=0; i<len; i++){

									var id = response['productlist_delivery'][i].sales_order_delivery_details_id;

									var product_name = response['productlist_delivery'][i].product_name;
									var product_unit_measurement = response['productlist_delivery'][i].product_unit_measurement;
									//var sales_order_departure_date = response['productlist_delivery'][i].sales_order_departure_date;
									var sales_order_delivery_date = response['productlist_delivery'][i].sales_order_delivery_date;
									var sales_order_delivery_withdrawal_reference = response['productlist_delivery'][i].sales_order_delivery_withdrawal_reference;
									var sales_order_delivery_quantity = response['productlist_delivery'][i].sales_order_delivery_quantity;

									var created_at = response['productlist_delivery'][i].created_at;

									const oneDay = 24 * 60 * 60 * 1000; 	/*hours*minutes*seconds*milliseconds*/
									const firstDate = new Date(created_at);
									const secondDate = new Date();			/*Now*/

									const diffDays = Math.round(Math.abs((firstDate - secondDate) / oneDay));

									<?php
									if(Session::get('UserType')=="SUAdmin"){
									?>

											action_controls = "<a href='#' class='btn-danger btn-circle btn-sm bi bi-eye-fill btn_icon_table btn_icon_table_view' data-so-editor-action='delivery-view' data-id='"+id+"'></a>"+
																"<a href='#' class='btn-danger btn-circle btn-sm bi-pencil-fill btn_icon_table btn_icon_table_edit' data-so-editor-action='delivery-edit' data-id='"+id+"'></a>"+
																"<a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' data-so-editor-action='delivery-delete'  data-id='"+id+"'></a>";

									<?php
									}
									else if(Session::get('UserType')=="Admin"){
									?>

											action_controls = "<a href='#' class='btn-danger btn-circle btn-sm bi bi-eye-fill btn_icon_table btn_icon_table_view' data-so-editor-action='delivery-view' data-id='"+id+"'></a>"+
																"<a href='#' class='btn-danger btn-circle btn-sm bi-pencil-fill btn_icon_table btn_icon_table_edit' data-so-editor-action='delivery-edit' data-id='"+id+"'></a>"+
																"<a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' data-so-editor-action='delivery-delete'  data-id='"+id+"'></a>";

									<?php
									}

									else{
									?>

										if(diffDays>=3){

											action_controls = "";

										}else{

											action_controls = "<a href='#' class='btn-danger btn-circle btn-sm bi bi-eye-fill btn_icon_table btn_icon_table_view' data-so-editor-action='delivery-view' data-id='"+id+"'></a>"+
																"<a href='#' class='btn-danger btn-circle btn-sm bi-pencil-fill btn_icon_table btn_icon_table_edit' data-so-editor-action='delivery-edit' data-id='"+id+"'></a>"+
																"<a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' data-so-editor-action='delivery-delete'  data-id='"+id+"'></a>";

											}

									<?php
									}
									?>

									$('#so-editor-product_list_delivery_data tr:last').after("<tr>"+
									"<td class=''><div align='center' class='action_table_menu_Delivery' >"+action_controls+"</div></td>"+
									"<td align='center'>" + (i+1) + "</td>" +
									"<td class='manual_price_td' align='center'>"+sales_order_delivery_date+"</td>"+
									"<td class='product_td' align='left'>"+product_name+"</td>"+
									"<td class='calibration_td' align='right'>"+sales_order_delivery_quantity+" "+product_unit_measurement+"</td>"+
									"</tr>");

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
