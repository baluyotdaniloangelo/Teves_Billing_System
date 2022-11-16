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
		/*$('<div class="btn-group" role="group" aria-label="Basic outlined example">'+
				'<button type="button" class="btn btn-success new_item bi bi-plus-circle" data-bs-toggle="modal" data-bs-target="#CreateBillingModal"></button>'+
				'</div>').appendTo('.additional_page_options');*/
	});
	
	<!--Save New Site-->
	$("#save-site").click(function(event){
			
			event.preventDefault();
			
					/*Reset Warnings*/
					$('#business_entityError').text('');
					$('#site_codeError').text('');
					$('#site_descriptionError').text('');				  
					$('#site_cut_offError').text('');
					$('#device_ip_rangeError').text('');
					$('#ip_netmaskError').text('');
					$('#ip_networkError').text('');
					$('#ip_gatewayError').text('');

			document.getElementById('siteform').className = "g-3 needs-validation was-validated";

			let business_entity 	= $("input[name=business_entity]").val();
			let site_code 			= $("input[name=site_code]").val();
			let site_description 	= $("input[name=site_description]").val();
			let building_type 		= $("#building_type").val();
			let site_cut_off 		= $("input[name=site_cut_off]").val();
			let device_ip_range 	= $("input[name=device_ip_range]").val();
			let ip_netmask 			= $("input[name=ip_netmask]").val();
			let ip_network 			= $("input[name=ip_network]").val();
			let ip_gateway 			= $("input[name=ip_gateway]").val();
			
			  $.ajax({
				url: "/create_site_post",
				type:"POST",
				data:{
				  business_entity:business_entity,
				  site_code:site_code,
				  site_description:site_description,
				  building_type:building_type,
				  site_cut_off:site_cut_off,
				  device_ip_range:device_ip_range,
				  ip_netmask:ip_netmask,
				  ip_network:ip_network,
				  ip_gateway:ip_gateway,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					$('.success_modal_bg').html(response.success);
					$('#SuccessModal').modal('toggle');
					
					$('#business_entityError').text('');					
					$('#site_codeError').text('');
					$('#site_descriptionError').text('');				  
				  
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				  $('#business_entityError').text(error.responseJSON.errors.business_entity);
				  document.getElementById('business_entityError').className = "invalid-feedback";
				  			  
				if(error.responseJSON.errors.site_code=="The site code has already been taken."){
							  
				  $('#site_codeError').html("<b>"+ site_code +"</b> has already been taken.");
				  document.getElementById('site_codeError').className = "invalid-feedback";
				  document.getElementById('site_code').className = "form-control is-invalid";
				  $('#site_code').val("");
				  
				}else{
					
				  $('#site_codeError').text(error.responseJSON.errors.site_description);
				  document.getElementById('site_codeError').className = "invalid-feedback";		
				
				}
				
				
				if(error.responseJSON.errors.site_description=="The site description has already been taken."){
							  
				  $('#site_descriptionError').html("<b>"+ site_description +"</b> has already been taken.");
				  document.getElementById('site_descriptionError').className = "invalid-feedback";
				  document.getElementById('site_description').className = "form-control is-invalid";
				  $('#site_description').val("");
				  
				}else{
					
				  $('#site_descriptionError').text(error.responseJSON.errors.site_description);
				  document.getElementById('site_descriptionError').className = "invalid-feedback";		
				
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
					document.getElementById("update_business_entity").value = response.business_entity;
					document.getElementById("update_site_code").value = response.site_code;
					document.getElementById("update_site_description").value = response.site_name;
					document.getElementById("update_building_type").value = response.building_type;
					document.getElementById("update_site_cut_off").value = response.site_cut_off;
					
					document.getElementById("update_device_ip_range").value = response.device_ip_range;
					document.getElementById("update_ip_network").value = response.ip_network;
					document.getElementById("update_ip_netmask").value = response.ip_netmask;
					document.getElementById("update_ip_gateway").value = response.ip_gateway;
					
					/*Information*/
					document.getElementById("site_details_site_desciption").innerHTML = response.site_name;
					document.getElementById("site_details_site_code").innerHTML = response.business_entity;
					document.getElementById("site_details_business_entity").innerHTML = response.business_entity;
					document.getElementById("site_details_building_type").innerHTML = response.building_type;
					document.getElementById("site_details_device_ip_range").innerHTML = response.device_ip_range;
					document.getElementById("site_details_ip_network").innerHTML = response.ip_network;
					document.getElementById("site_details_ip_netmask").innerHTML = response.ip_netmask;
					document.getElementById("site_details_ip_gateway").innerHTML = response.ip_gateway;
					
					document.getElementById("site_details_sap_business_entity").innerHTML = response.business_entity;
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
					$('#update_business_entityError').text('');
					$('#update_site_codeError').text('');
					$('#update_site_descriptionError').text('');				  
					$('#update_site_cut_offError').text('');
					$('#update_device_ip_rangeError').text('');
					$('#update_ip_netmaskError').text('');
					$('#update_ip_networkError').text('');
					$('#update_ip_gatewayError').text('');

			document.getElementById('siteform').className = "row g-3 needs-validation was-validated";

			let business_entity 	= $("input[name=update_business_entity]").val();
			let site_code 			= $("input[name=update_site_code]").val();
			let site_description 	= $("input[name=update_site_description]").val();
			let building_type 		= $("#update_building_type").val();
			let site_cut_off 		= $("input[name=update_site_cut_off]").val();
			let device_ip_range 	= $("input[name=update_device_ip_range]").val();
			let ip_netmask 			= $("input[name=update_ip_netmask]").val();
			let ip_network 			= $("input[name=update_ip_network]").val();
			let ip_gateway 			= $("input[name=update_ip_gateway]").val();
			
			  $.ajax({
				url: "/update_site_post",
				type:"POST",
				data:{
				  SiteID:siteID,
				  business_entity:business_entity,
				  site_code:site_code,
				  site_description:site_description,
				  building_type:building_type,
				  site_cut_off:site_cut_off,
				  device_ip_range:device_ip_range,
				  ip_netmask:ip_netmask,
				  ip_network:ip_network,
				  ip_gateway:ip_gateway,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					$('.success_modal_bg').html(response.success);
					$('#SuccessModal').modal('toggle');
					
					$('#business_entityError').text('');					
					$('#site_codeError').text('');
					$('#site_descriptionError').text('');				  
				  
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				  $('#business_entityError').text(error.responseJSON.errors.business_entity);
				  document.getElementById('business_entityError').className = "invalid-feedback";
				  			  
				if(error.responseJSON.errors.site_code=="The site code has already been taken."){
							  
				  $('#site_codeError').html("<b>"+ site_code +"</b> has already been taken.");
				  document.getElementById('site_codeError').className = "invalid-feedback";
				  document.getElementById('site_code').className = "form-control is-invalid";
				  $('#site_code').val("");
				  
				}else{
					
				  $('#site_codeError').text(error.responseJSON.errors.site_description);
				  document.getElementById('site_codeError').className = "invalid-feedback";		
				
				}
				
				
				if(error.responseJSON.errors.site_description=="The site description has already been taken."){
							  
				  $('#site_descriptionError').html("<b>"+ site_description +"</b> has already been taken.");
				  document.getElementById('site_descriptionError').className = "invalid-feedback";
				  document.getElementById('site_description').className = "form-control is-invalid";
				  $('#site_description').val("");
				  
				}else{
					
				  $('#site_descriptionError').text(error.responseJSON.errors.site_description);
				  document.getElementById('site_descriptionError').className = "invalid-feedback";		
				
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
					$('#site_description_info').html(response.site_name);
					$('#site_description_info_confirmed').html(response.site_name);
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

else if (Request::is('site_details/'.@$SiteData->id)){
	?>

   <!-- Page level plugins -->
   <script src="{{asset('datatables/jquery.dataTables.js')}}"></script>
   <script src="{{asset('datatables/dataTables.bootstrap4.js')}}"></script>
   
   <script src="{{asset('NiceAdmin-pro/assets/vendor/chart.js/chart.min.js')}}"></script>
   <script>
                document.addEventListener("DOMContentLoaded", () => {
                  new Chart(document.querySelector('#gateway'), {
                    type: 'pie',
                    data: {
                      labels: [
                        'Online',
                        'Offline',
                        'Spare'
                      ],
                      datasets: [{
                        label: 'Gateway Sample',
                        data: [300, 50, 100],
                        backgroundColor: [
                          'green',
                          'red',
                          'blue'
                        ],
                        hoverOffset: 4
                      }]
                    }
                  });
                });
				
				document.addEventListener("DOMContentLoaded", () => {
                  new Chart(document.querySelector('#meter'), {
                    type: 'pie',
                    data: {
                      labels: [
                        'Online',
                        'Offline',
                        'Spare'
                      ],
                      datasets: [{
                        label: 'Meter Sample',
                        data: [400, 20, 50],
                        backgroundColor: [
                          'green',
                          'red',
                          'blue'
                        ],
                        hoverOffset: 4
                      }]
                    }
                  });
                });
								
	/*Load Gateway List*/	
		$(document).ready(function() {
			var table = $('#gatewaylist').DataTable( {
				"language": {
						"lengthMenu":'<label class="col-form-label">Limit: </label> <select class="form-select form-control form-control-sm">'+
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
				ajax: {				
					url: "{{ route('getGateway') }}",
					type: 'get',
					data:{
						siteID:{{ $SiteData->id }},
						_token: "{{ csrf_token() }}"
					},
					},
				"columns": [
					{data: 'DT_RowIndex', name: 'DT_RowIndex' , orderable: false, searchable: false},
					{data: 'rtu_sn_number'},           
					{data: 'phone_no_or_ip_address'},
					{data: 'mac_addr'},
					{data: 'rtu_physical_location'},
					{data: 'idf_number'},
					{data: 'switch_name'},
					{data: 'idf_port'},
					{data: 'status', name: 'status', orderable: true, searchable: true},
					{data: 'update', name: 'status', orderable: false, searchable: true},
					{data: 'action', name: 'action', orderable: false, searchable: false},
				]
			} );
			$('<div class="btn-group" role="group" aria-label="Basic outlined example">'+
				'<button type="button" class="btn btn-success new_item bi bi-plus-circle" data-bs-toggle="modal" data-bs-target="#CreateGatewayModal"></button>'+
				'<button type="button" class="btn btn-danger offline_item bi bi-cloud-slash" data-bs-toggle="modal" data-bs-target="#OfflineGatewayModal"></button>'+
			   '</div>').appendTo('.additional_page_options');
		} );	


	<!--Save New Gateway-->
	$("#save-gateway").click(function(event){
			
			event.preventDefault();
			
					/*Reset Warnings*/
					$('#gateway_snError').text('');
					$('#gateway_macError').text('');
					$('#gateway_ipError').text('');				  
					$('#idf_numberError').text('');
					$('#idf_switchError').text('');
					$('#idf_portError').text('');
					$('#physical_locationError').text('');
					$('#gateway_descriptionError').text('');
					$('#connection_typeError').text('');

			document.getElementById('gatewayform').className = "g-2 needs-validation was-validated";

			let gateway_sn 				= $("input[name=gateway_sn]").val();
			let gateway_mac 			= $("input[name=gateway_mac]").val();
			let gateway_ip 				= $("input[name=gateway_ip]").val();
			let idf_number 				= $("input[name=idf_number]").val();
			let idf_switch 				= $("input[name=idf_switch]").val();
			let idf_port 				= $("input[name=idf_port]").val();
			let physical_location 		= $("input[name=physical_location]").val();
			let gateway_description 	= $("input[name=gateway_description]").val();
			let connection_type 		= $("#connection_type").val();
			
			  $.ajax({
				url: "/create_gateway_post",
				type:"POST",
				data:{
				  siteID:{{ $SiteData->id }},
				  siteCode:'{{ $SiteData->site_code }}',
				  gateway_sn:gateway_sn,
				  gateway_mac:gateway_mac,
				  gateway_ip:gateway_ip,
				  idf_number:idf_number,
				  idf_switch:idf_switch,
				  idf_port:idf_port,
				  physical_location:physical_location,
				  gateway_description:gateway_description,
				  connection_type:connection_type,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					$('.success_modal_bg').html(response.success);
					$('#SuccessModal').modal('toggle');
					
					$('#gateway_snError').text('');					
					$('#gateway_macError').text('');
					$('#site_descriptionError').text('');				  
				  
				  }
				},
				error: function(error) {
				console.log(error);	
				  		
				document.getElementById("InvalidModalBtn").focus();		
				document.getElementById("InvalidModalBtn").click(); 
						
				if(error.responseJSON.errors.gateway_sn=="The gateway sn has already been taken."){
							  
				  $('#gateway_snError').html("<b>"+ gateway_sn +"</b> has already been taken.");
				  document.getElementById('gateway_snError').className = "invalid-feedback";
				  document.getElementById('gateway_sn').className = "form-control is-invalid";
				  $('#gateway_sn').val("");
				  
				}else{
					
				  $(gateway_snError).text(error.responseJSON.errors.gateway_sn);
				  document.getElementById('gateway_snError').className = "invalid-feedback";		
				
				}
				
				
				if(error.responseJSON.errors.gateway_mac=="The gateway mac has already been taken."){
							  
				  $('#gateway_macError').html("<b>"+ gateway_mac +"</b> has already been taken.");
				  document.getElementById('gateway_macError').className = "invalid-feedback";
				  document.getElementById('gateway_mac').className = "form-control is-invalid";
				  $('#gateway_mac').val("");
				  
				}else{
					
				  $('#gateway_macError').text(error.responseJSON.errors.gateway_mac);
				  document.getElementById('gateway_macError').className = "invalid-feedback";		
				
				}
				
				
				if(error.responseJSON.errors.gateway_ip=="The gateway ip has already been taken."){
							  
				  $('#gateway_ipError').html("<b>"+ gateway_ip +"</b> has already been taken.");
				  document.getElementById('gateway_ipError').className = "invalid-feedback";
				  document.getElementById('gateway_ip').className = "form-control is-invalid";
				  $('#gateway_ip').val("");
				  
				}else{
					
				  $('#gateway_ipError').text(error.responseJSON.errors.gateway_ip);
				  document.getElementById('gateway_ipError').className = "invalid-feedback";		
				
				}
				
				 $('#physical_locationError').text(error.responseJSON.errors.physical_location);
				  document.getElementById('physical_locationError').className = "invalid-feedback";
				 
				$('#InvalidModal').modal('toggle');			
				  
				}
			   });
		
	  });

	<!--CSV Enable Update-->
	$('body').on('click','#enablecsvUpdate',function(){
			
			event.preventDefault();
			let gatewayID = $(this).data('id');
			
			  $.ajax({
				url: "/enablecsvUpdate",
				type:"POST",
				data:{
				  gatewayID:gatewayID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
									
					var table = $("#gatewaylist").DataTable();
				    table.ajax.reload(null, false);
				
				}
				},
				error: function(error) {
				 console.log(error);
				}
			   });
			   
	  });
	  
	<!--CSV Disable Update-->
	$('body').on('click','#disablecsvUpdate',function(){
			
			event.preventDefault();
			let gatewayID = $(this).data('id');
			
			  $.ajax({
				url: "/disablecsvUpdate",
				type:"POST",
				data:{
				  gatewayID:gatewayID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
									
					var table = $("#gatewaylist").DataTable();
				    table.ajax.reload(null, false);
				
				}
				},
				error: function(error) {
				 console.log(error);
				}
			   });
			   
	  });	

	<!--Site Code Enable Update-->
	$('body').on('click','#enablesitecodeUpdate',function(){
			
			event.preventDefault();
			let gatewayID = $(this).data('id');
			
			  $.ajax({
				url: "/enablesitecodeUpdate",
				type:"POST",
				data:{
				  gatewayID:gatewayID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
									
					var table = $("#gatewaylist").DataTable();
				    table.ajax.reload(null, false);
				
				}
				},
				error: function(error) {
				 console.log(error);
				}
			   });
			   
	  });
	  
	<!--Site Code Disable Update-->
	$('body').on('click','#disablesitecodeUpdate',function(){
			
			event.preventDefault();
			let gatewayID = $(this).data('id');
			
			  $.ajax({
				url: "/disablesitecodeUpdate",
				type:"POST",
				data:{
				  gatewayID:gatewayID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
									
					var table = $("#gatewaylist").DataTable();
				    table.ajax.reload(null, false);
				
				}
				},
				error: function(error) {
				 console.log(error);
				}
			   });
			   
	  });

	<!--SSH Enable-->
/* 	$('body').on('click','#enableSSH',function(){
			
			event.preventDefault();
			let gatewayID = $(this).data('id');
			
			  $.ajax({
				url: "/enableSSH",
				type:"POST",
				data:{
				  gatewayID:gatewayID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
									
					var table = $("#gatewaylist").DataTable();
				    table.ajax.reload(null, false);
				
				}
				},
				error: function(error) {
				 console.log(error);
				}
			   });
			   
	  }); */
	  
	<!--SSH Disable-->
/* 	$('body').on('click','#disableSSH',function(){
			
			event.preventDefault();
			let gatewayID = $(this).data('id');
			
			  $.ajax({
				url: "/disableSSH",
				type:"POST",
				data:{
				  gatewayID:gatewayID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
									
					var table = $("#gatewaylist").DataTable();
				    table.ajax.reload(null, false);
				
				}
				},
				error: function(error) {
				 console.log(error);
				}
			   });
			   
	  }); */
	
	<!--Force Load Profile Enable-->
/* 	$('body').on('click','#enableLP',function(){
			
			event.preventDefault();
			let gatewayID = $(this).data('id');
			
			  $.ajax({
				url: "/enableLP",
				type:"POST",
				data:{
				  gatewayID:gatewayID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
									
					var table = $("#gatewaylist").DataTable();
				    table.ajax.reload(null, false);
				
				}
				},
				error: function(error) {
				 console.log(error);
				}
			   });
			   
	  }); */
	  
	<!--Force Load Profile Disable-->
	/* $('body').on('click','#disableLP',function(){
			
			event.preventDefault();
			let gatewayID = $(this).data('id');
			
			  $.ajax({
				url: "/disableLP",
				type:"POST",
				data:{
				  gatewayID:gatewayID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
									
					var table = $("#gatewaylist").DataTable();
				    table.ajax.reload(null, false);
				
				}
				},
				error: function(error) {
				 console.log(error);
				}
			   });
			   
	  }); */
	  
	function remember_sitetab(tab) {
		var tab = tab;
		
		event.preventDefault();
			
			  $.ajax({
				url: "/save_site_tab",
				type:"POST",
				data:{
				  tab:tab,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
									
					//var table = $("#gatewaylist").DataTable();
				    //table.ajax.reload(null, false);
				
				}
				},
				error: function(error) {
				 console.log(error);
				}
			   });
		
	}
	
	
    </script>
<?php
}
?>
</body>

</html>