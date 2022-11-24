@include('layouts.header')
</head>

<body class="toggle-sidebar">
@yield('content')
 
@include('layouts.footer')

<?php

if (Request::is('billing')){

?>

   <!-- Page level plugins -->
   <script src="{{asset('datatables/jquery.dataTables.js')}}"></script>
   <script src="{{asset('datatables/dataTables.bootstrap4.js')}}"></script>
   <script type="text/javascript">

	<!--Load Table-->
	$(function () {

		var BillingListTable = $('#getBillingTransactionList').DataTable({
			"language": {
						"lengthMenu":'<select class="form-select form-control form-control-sm">'+
			             '<option value="10">10</option>'+
			             '<option value="20">20</option>'+
			             '<option value="30">30</option>'+
			             '<option value="40">40</option>'+
			             '<option value="50">50</option>'+
			             '<option value="-1">All</option>'+
			             '</select> '
		    },
			processing: true,
			serverSide: true,
			stateSave: true,/*Remember Searches*/
			ajax: "{{ route('getBillingTransactionList') }}",
			columns: [
					{data: 'DT_RowIndex', name: 'DT_RowIndex' , orderable: false, searchable: false},
					{data: 'order_date'},   
					{data: 'drivers_name'},     
					{data: 'order_po_number'},     
					{data: 'plate_no'},     
					{data: 'product_name'},     
					{data: 'order_quantity'},    
					{data: 'product_price'},     
					{data: 'order_total_amount'},  
					{data: 'order_time'},  
					{data: 'action', name: 'action', orderable: false, searchable: false},
			]
		});
		$('<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">'+
				'<button type="button" class="btn btn-success new_item bi bi-plus-circle" data-bs-toggle="modal" data-bs-target="#CreateBillingModal"></button>'+
				'</div>').appendTo('#billing_option');
	});
	
	<!--Save New Billing-->
	$("#save-billing-transaction").click(function(event){
			
			event.preventDefault();
			
					/*Reset Warnings*/
					$('#order_dateError').text('');
					$('#order_timeError').text('');
					$('#order_po_numberError').text('');				  
					$('#client_idxError').text('');
					$('#plate_noError').text('');
					$('#drivers_nameError').text('');
					$('#product_idxError').text('');
					$('#order_quantityError').text('');

			document.getElementById('Billingform').className = "g-3 needs-validation was-validated";

			let order_date 				= $("input[name=order_date]").val();
			let order_time 				= $("input[name=order_time]").val();
			let order_po_number 		= $("input[name=order_po_number]").val();
			let client_idx 				= $("#client_idx").val();
			let plate_no 				= $("input[name=plate_no]").val();
			let drivers_name 			= $("input[name=drivers_name]").val();
			let product_idx 			= $("#product_idx").val();
			let order_quantity 			= $("input[name=order_quantity]").val();
			
			  $.ajax({
				url: "/billingtransaction_post",
				type:"POST",
				data:{
				  order_date:order_date,
				  order_time:order_time,
				  order_po_number:order_po_number,
				  client_idx:client_idx,
				  plate_no:plate_no,
				  drivers_name:drivers_name,
				  product_idx:product_idx,
				  order_quantity:order_quantity,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					$('.success_modal_bg').html(response.success);
					$('#SuccessModal').modal('toggle');
					
					$('#order_dateError').text('');					
					$('#order_timeError').text('');
					$('#order_po_numberError').text('');				  
				  
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				  $('#order_dateError').text(error.responseJSON.errors.order_date);
				  document.getElementById('order_dateError').className = "invalid-feedback";
				  			  
				if(error.responseJSON.errors.order_time=="The site code has already been taken."){
							  
				  $('#order_timeError').html("<b>"+ order_time +"</b> has already been taken.");
				  document.getElementById('order_timeError').className = "invalid-feedback";
				  document.getElementById('order_time').className = "form-control is-invalid";
				  $('#order_time').val("");
				  
				}else{
					
				  $('#order_timeError').text(error.responseJSON.errors.order_po_number);
				  document.getElementById('order_timeError').className = "invalid-feedback";		
				
				}
				
				
				if(error.responseJSON.errors.order_po_number=="The site description has already been taken."){
							  
				  $('#order_po_numberError').html("<b>"+ order_po_number +"</b> has already been taken.");
				  document.getElementById('order_po_numberError').className = "invalid-feedback";
				  document.getElementById('order_po_number').className = "form-control is-invalid";
				  $('#order_po_number').val("");
				  
				}else{
					
				  $('#order_po_numberError').text(error.responseJSON.errors.order_po_number);
				  document.getElementById('order_po_numberError').className = "invalid-feedback";		
				
				}
				
				$('#InvalidModal').modal('toggle');				  	  
				  
				}
			   });
		
	  });

	<!--reset/set only the data-bs-target button for manual to CreateSiteModal-->
	$('body').on('click','#CreateSiteModal',function(){
			
			event.preventDefault();
			
			document.getElementById("update-site").value = '';
			$('#CloseManual').attr('data-bs-target','#CreateSiteModal');
				  	
	  });

	<!--Select Site For Update-->
	$('body').on('click','#editSite',function(){
			
			event.preventDefault();
			let siteID = $(this).data('id');
			
			  $.ajax({
				url: "/site_info",
				type:"POST",
				data:{
				  siteID:siteID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("update-site").value = siteID;
					$('#CloseManual').attr('data-bs-target','#UpdateSiteModal');
					
					/*Set Details*/
					document.getElementById("update_order_date").value = response.order_date;
					document.getElementById("update_order_time").value = response.order_time;
					document.getElementById("update_order_po_number").value = response.site_name;
					document.getElementById("update_building_type").value = response.building_type;
					document.getElementById("update_client_idx").value = response.client_idx;
					
					document.getElementById("update_plate_no").value = response.plate_no;
					document.getElementById("update_product_idx").value = response.product_idx;
					document.getElementById("update_drivers_name").value = response.drivers_name;
					document.getElementById("update_order_quantity").value = response.order_quantity;
					
					/*Information*/
					document.getElementById("site_details_site_desciption").innerHTML = response.site_name;
					document.getElementById("site_details_order_time").innerHTML = response.order_date;
					document.getElementById("site_details_order_date").innerHTML = response.order_date;
					document.getElementById("site_details_building_type").innerHTML = response.building_type;
					document.getElementById("site_details_plate_no").innerHTML = response.plate_no;
					document.getElementById("site_details_product_idx").innerHTML = response.product_idx;
					document.getElementById("site_details_drivers_name").innerHTML = response.drivers_name;
					document.getElementById("site_details_order_quantity").innerHTML = response.order_quantity;
					
					document.getElementById("site_details_sap_order_date").innerHTML = response.order_date;
					document.getElementById("site_details_company_no").innerHTML = response.company_no;
					document.getElementById("site_details_service_charge_key").innerHTML = response.service_charge_key;
					document.getElementById("site_details_participation_group").innerHTML = response.participation_group;
					document.getElementById("site_details_settlement_unit").innerHTML = response.settlement_unit;
					document.getElementById("site_details_settlement_variant_text").innerHTML = response.settlement_variant_text;
					document.getElementById("site_details_sap_validity").innerHTML = response.settlement_valid_from +"-"+response.settlement_valid_to;
					document.getElementById("site_details_sap_created_at").innerHTML = response.sap_created_at;
					document.getElementById("site_details_sap_last_edited_at").innerHTML = response.sap_last_edited_at;
					document.getElementById("site_details_sap_server").innerHTML = response.sap_server;
					
					$('#UpdateSiteModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });
				  
				
		
	  });


	$("#update-site").click(function(event){
			
			event.preventDefault();
			
					/*Reset Warnings*/
					let siteID = document.getElementById("update-site").value;
					$('#update_order_dateError').text('');
					$('#update_order_timeError').text('');
					$('#update_order_po_numberError').text('');				  
					$('#update_client_idxError').text('');
					$('#update_plate_noError').text('');
					$('#update_drivers_nameError').text('');
					$('#update_product_idxError').text('');
					$('#update_order_quantityError').text('');

			document.getElementById('siteform').className = "row g-3 needs-validation was-validated";

			let order_date 	= $("input[name=update_order_date]").val();
			let order_time 			= $("input[name=update_order_time]").val();
			let order_po_number 	= $("input[name=update_order_po_number]").val();
			let building_type 		= $("#update_building_type").val();
			let client_idx 		= $("input[name=update_client_idx]").val();
			let plate_no 	= $("input[name=update_plate_no]").val();
			let drivers_name 			= $("input[name=update_drivers_name]").val();
			let product_idx 			= $("input[name=update_product_idx]").val();
			let order_quantity 			= $("input[name=update_order_quantity]").val();
			
			  $.ajax({
				url: "/update_site_post",
				type:"POST",
				data:{
				  SiteID:siteID,
				  order_date:order_date,
				  order_time:order_time,
				  order_po_number:order_po_number,
				  building_type:building_type,
				  client_idx:client_idx,
				  plate_no:plate_no,
				  drivers_name:drivers_name,
				  product_idx:product_idx,
				  order_quantity:order_quantity,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					$('.success_modal_bg').html(response.success);
					$('#SuccessModal').modal('toggle');
					
					$('#order_dateError').text('');					
					$('#order_timeError').text('');
					$('#order_po_numberError').text('');				  
				  
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				  $('#order_dateError').text(error.responseJSON.errors.order_date);
				  document.getElementById('order_dateError').className = "invalid-feedback";
				  			  
				if(error.responseJSON.errors.order_time=="The site code has already been taken."){
							  
				  $('#order_timeError').html("<b>"+ order_time +"</b> has already been taken.");
				  document.getElementById('order_timeError').className = "invalid-feedback";
				  document.getElementById('order_time').className = "form-control is-invalid";
				  $('#order_time').val("");
				  
				}else{
					
				  $('#order_timeError').text(error.responseJSON.errors.order_po_number);
				  document.getElementById('order_timeError').className = "invalid-feedback";		
				
				}
				
				
				if(error.responseJSON.errors.order_po_number=="The site description has already been taken."){
							  
				  $('#order_po_numberError').html("<b>"+ order_po_number +"</b> has already been taken.");
				  document.getElementById('order_po_numberError').className = "invalid-feedback";
				  document.getElementById('order_po_number').className = "form-control is-invalid";
				  $('#order_po_number').val("");
				  
				}else{
					
				  $('#order_po_numberError').text(error.responseJSON.errors.order_po_number);
				  document.getElementById('order_po_numberError').className = "invalid-feedback";		
				
				}
				
				$('#InvalidModal').modal('toggle');				  
				  
				}
			   });
				  
				
		
	  });
	<!--Site Deletion Confirmation-->
	$('body').on('click','#deleteSite',function(){
			
			event.preventDefault();
			let siteID = $(this).data('id');
			
			  $.ajax({
				url: "/site_info",
				type:"POST",
				data:{
				  siteID:siteID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("deleteSiteConfirmed").value = siteID;
					$('#order_po_number_info').html(response.site_name);
					$('#order_po_number_info_confirmed').html(response.site_name);
					$('#SiteDeleteModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });
				  
				
		
	  });

	  <!--Site Confirmed For Deletion-->
	  $('body').on('click','#deleteSiteConfirmed',function(){
			
			event.preventDefault();

			let siteID = document.getElementById("deleteSiteConfirmed").value;
			
			  $.ajax({
				url: "/delete_site_confirmed",
				type:"POST",
				data:{
				  siteID:siteID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					$('#SiteDeleteModalConfirmed').modal('toggle');
					
					/*
					If you are using server side datatable, then you can use ajax.reload() 
					function to reload the datatable and pass the true or false as a parameter for refresh paging.
					*/
					
					var table = $("#siteList").DataTable();
				    table.ajax.reload(null, false);
					
				  }
				},
				error: function(error) {
				 console.log(error);
					//alert(error);
				}
			   });
				
		
	  });
  </script>
	<?php
}


?>
</body>

</html>