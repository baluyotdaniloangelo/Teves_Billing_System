   <script type="text/javascript">

	<!--Load Table-->
	$(function () {

		var ClientListTable = $('#getclientList').DataTable({
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
			ajax: "{{ route('getClientList') }}",
			columns: [
					{data: 'DT_RowIndex', name: 'DT_RowIndex' , orderable: false, searchable: false},
					{data: 'client_name'},   
					{data: 'client_address'},
					{data: 'action', name: 'action', orderable: false, searchable: false},
			],
			columnDefs: [
					{ className: 'text-center', targets: [0, 1, 2, 3] },
			]
		});
				$('<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">'+
				'<button type="button" class="btn btn-success new_item bi bi-plus-circle" data-bs-toggle="modal" data-bs-target="#CreateClientModal"></button>'+
				'</div>').appendTo('#client_option');
	});
	
	<!--Save New Client->
	$("#save-client").click(function(event){
			
			event.preventDefault();
			
					/*Reset Warnings*/
					$('#client_nameError').text('');
					$('#client_addressError').text('');

			document.getElementById('ClientformNew').className = "g-3 needs-validation was-validated";

			let client_name 			= $("input[name=client_name]").val();
			let client_address 			= $("input[name=client_address]").val();
			
			  $.ajax({
				url: "/create_client_post",
				type:"POST",
				data:{
				  client_name:client_name,
				  client_address:client_address,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					$('.success_modal_bg').html(response.success);
					$('#SuccessModal').modal('toggle');
					
					$('#client_nameError').text('');
					$('#client_addressError').text('');
					
					/*Refresh Table*/
					var table = $("#getclientList").DataTable();
				    table.ajax.reload(null, false);
				  
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				 if(error.responseJSON.errors.client_name=="The Client name has already been taken."){
							  
				  $('#client_nameError').html("<b>"+ client_name +"</b> has already been taken.");
				  document.getElementById('client_nameError').className = "invalid-feedback";
				  document.getElementById('client_name').className = "form-control is-invalid";
				  $('#update_gateway_sn').val("");
				  
				}else{
				  $('#client_nameError').text(error.responseJSON.errors.client_name);
				  document.getElementById('client_nameError').className = "invalid-feedback";
				}
				
				  $('#client_addressError').text(error.responseJSON.errors.client_address);
				  document.getElementById('client_addressError').className = "invalid-feedback";			
				
				$('#InvalidModal').modal('toggle');				  	  
				  
				}
			   });		
	  });

	<!--Select Client For Update-->
	$('body').on('click','#editclient',function(){
			
			event.preventDefault();
			let clientID = $(this).data('id');
			
			  $.ajax({
				url: "/client_info",
				type:"POST",
				data:{
				  clientID:clientID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("update-client").value = clientID;
					
					/*Set Details*/
					document.getElementById("update_client_name").value = response.client_name;
					document.getElementById("update_client_address").value = response.client_address;
										
					$('#UpdateClientModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });


	$("#update-client").click(function(event){			
			event.preventDefault();
			
					/*Reset Warnings*/
					$('#update_client_nameError').text('');
					$('#update_client_addressError').text('');

			document.getElementById('ClientformEdit').className = "g-3 needs-validation was-validated";
			
			let clientID 				= document.getElementById("update-client").value;
			let client_name 			= $("input[name=update_client_name]").val();
			let client_address 			= $("input[name=update_client_address]").val();
			
			  $.ajax({
				url: "/update_client_post",
				type:"POST",
				data:{
				  clientID:clientID,
				  client_name:client_name,
				  client_address:client_address,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					$('.success_modal_bg').html(response.success);
					$('#SuccessModal').modal('toggle');
					
					$('#update_client_nameError').text('');	
					$('#update_client_addressError').text('');
					
					/*Close form*/
					$('#UpdateClientModal').modal('toggle');
					
					/*Refresh Table*/
					var table = $("#getclientList").DataTable();
				    table.ajax.reload(null, false);
				  
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				if(error.responseJSON.errors.client_name=="The product name has already been taken."){
							  
					$('#client_nameError').html("<b>"+ client_name +"</b> has already been taken.");
					document.getElementById('client_nameError').className = "invalid-feedback";
					document.getElementById('client_name').className = "form-control is-invalid";
					$('#update_gateway_sn').val("");
				  
				}else{
					$('#client_nameError').text(error.responseJSON.errors.client_name);
					document.getElementById('client_nameError').className = "invalid-feedback";
				}
				
					$('#client_addressError').text(error.responseJSON.errors.client_address);
					document.getElementById('client_addressError').className = "invalid-feedback";			
				
				$('#InvalidModal').modal('toggle');				  	  
				  
				}
			   });
		
	  });
	  
	<!--client Deletion Confirmation-->
	$('body').on('click','#deleteclient',function(){
			
			event.preventDefault();
			let clientID = $(this).data('id');
			
			  $.ajax({
				url: "/client_info",
				type:"POST",
				data:{
				  clientID:clientID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("deleteClientConfirmed").value = clientID;
					
					/*Set Details*/
					$('#confirm_delete_client_name').text(response.client_name);
					$('#confirm_delete_client_address').text(response.client_address);

					/*Set Details Confirmed*/
					$('#confirmed_delete_client_name').text(response.client_name);
					$('#confirmed_delete_client_address').text(response.client_address);
					
					$('#ClientDeleteModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });		
	  });

	  <!--client Confirmed For Deletion-->
	  $('body').on('click','#deleteClientConfirmed',function(){
			
			event.preventDefault();

			let clientID = document.getElementById("deleteClientConfirmed").value;
			
			  $.ajax({
				url: "/delete_client_confirmed",
				type:"POST",
				data:{
				  clientID:clientID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					$('#ClientDeleteModalConfirmed').modal('toggle');
					
					/*
					If you are using server side datatable, then you can use ajax.reload() 
					function to reload the datatable and pass the true or false as a parameter for refresh paging.
					*/
					
					var table = $("#getclientList").DataTable();
				    table.ajax.reload(null, false);
					
				  }
				},
				error: function(error) {
				 console.log(error);
				}
			   });		
	  });
  </script>
	