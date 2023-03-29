   <script type="text/javascript">

	<!--Load Table-->
	$(function () {

		var SupplierListTable = $('#getsupplierList').DataTable({
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
			/*processing: true,*/
			serverSide: true,
			stateSave: true,/*Remember Searches*/
			ajax: "{{ route('getSupplierList') }}",
			columns: [
					{data: 'DT_RowIndex', name: 'DT_RowIndex' , orderable: false, searchable: false},
					{data: 'supplier_name'},   
					{data: 'supplier_address'},
					{data: 'supplier_tin'},
					{data: 'action', name: 'action', orderable: false, searchable: false},
			],
			columnDefs: [
					{ className: 'text-center', targets: [0] },
			]
		});
				$('<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">'+
				'<button type="button" class="btn btn-success new_item bi bi-plus-circle" data-bs-toggle="modal" data-bs-target="#CreatesupplierModal"></button>'+
				'</div>').appendTo('#supplier_option');
	});
	
	<!--Save New supplier->
	$("#save-supplier").click(function(event){
			
			event.preventDefault();
			
					/*Reset Warnings*/
					$('#supplier_nameError').text('');
					$('#supplier_addressError').text('');
					$('#supplier_tinError').text('');

			document.getElementById('supplierformNew').className = "g-3 needs-validation was-validated";

			let supplier_name 		= $("input[name=supplier_name]").val();
			let supplier_address 		= $("input[name=supplier_address]").val();
			let supplier_tin 			= $("input[name=supplier_tin]").val();
			
			  $.ajax({
				url: "/create_supplier_post",
				type:"POST",
				data:{
				  supplier_name:supplier_name,
				  supplier_address:supplier_address,
				  supplier_tin:supplier_tin,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					$('#switch_notice_on').show();
					$('#sw_on').html(response.success);
					setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
					
					$('#supplier_nameError').text('');
					$('#supplier_addressError').text('');
					$('#supplier_tinError').text('');
					
					document.getElementById("supplierformNew").reset();
					
					document.getElementById('supplierformNew').className = "g-3 needs-validation";
					
					/*Refresh Table*/
					var table = $("#getsupplierList").DataTable();
				    table.ajax.reload(null, false);
				  
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				 if(error.responseJSON.errors.supplier_name=="The supplier name has already been taken."){
							  
				  $('#supplier_nameError').html("<b>"+ supplier_name +"</b> has already been taken.");
				  document.getElementById('supplier_nameError').className = "invalid-feedback";
				  document.getElementById('supplier_name').className = "form-control is-invalid";
				  $('#supplier_name').val("");
				  
				}else{
				  $('#supplier_nameError').text(error.responseJSON.errors.supplier_name);
				  document.getElementById('supplier_nameError').className = "invalid-feedback";
				}
				
				  $('#supplier_addressError').text(error.responseJSON.errors.supplier_address);
				  document.getElementById('supplier_addressError').className = "invalid-feedback";	
				  
				  $('#supplier_tinError').text(error.responseJSON.errors.supplier_tin);
				  document.getElementById('supplier_tinError').className = "invalid-feedback";			

				$('#switch_notice_off').show();
				$('#sw_off').html("Invalid Input");
				setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);
				  
				}
			   });		
	  });

	<!--Select supplier For Update-->
	$('body').on('click','#editsupplier',function(){
			
			event.preventDefault();
			let supplierID = $(this).data('id');
			
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
					
					document.getElementById("update-supplier").value = supplierID;
					
					/*Set Details*/
					document.getElementById("update_supplier_name").value = response.supplier_name;
					document.getElementById("update_supplier_address").value = response.supplier_address;
					document.getElementById("update_supplier_tin").value = response.supplier_tin;
										
					$('#UpdatesupplierModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });


	$("#update-supplier").click(function(event){			
			event.preventDefault();
			
					/*Reset Warnings*/
					$('#update_supplier_nameError').text('');
					$('#update_supplier_addressError').text('');
					$('#update_supplier_tinError').text('');

			document.getElementById('supplierformEdit').className = "g-3 needs-validation was-validated";
			
			let supplierID 			= document.getElementById("update-supplier").value;
			let supplier_name 		= $("input[name=update_supplier_name]").val();
			let supplier_address 		= $("input[name=update_supplier_address]").val();
			let supplier_tin 			= $("input[name=update_supplier_tin]").val();
			
			  $.ajax({
				url: "/update_supplier_post",
				type:"POST",
				data:{
				  supplierID:supplierID,
				  supplier_name:supplier_name,
				  supplier_address:supplier_address,
				  supplier_tin:supplier_tin,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					$('#update_supplier_nameError').text('');	
					$('#update_supplier_addressError').text('');
					$('#update_supplier_tinError').text('');
					
					$('#switch_notice_on').show();
					$('#sw_on').html(response.success);
					setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
					
					/*Close form*/
					$('#UpdatesupplierModal').modal('toggle');
					
					document.getElementById('supplierformEdit').className = "g-3 needs-validation";
					
					/*Refresh Table*/
					var table = $("#getsupplierList").DataTable();
				    table.ajax.reload(null, false);
				  
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				if(error.responseJSON.errors.supplier_name=="The supplier name has already been taken."){
							  
					$('#supplier_nameError').html("<b>"+ supplier_name +"</b> has already been taken.");
					document.getElementById('supplier_nameError').className = "invalid-feedback";
					document.getElementById('supplier_name').className = "form-control is-invalid";
					$('#update_supplier_name').val("");
				  
				}else{
					$('#supplier_nameError').text(error.responseJSON.errors.supplier_name);
					document.getElementById('supplier_nameError').className = "invalid-feedback";
				}
				
					$('#supplier_addressError').text(error.responseJSON.errors.supplier_address);
					document.getElementById('supplier_addressError').className = "invalid-feedback";			
					
					$('#supplier_tinError').text(error.responseJSON.errors.supplier_tin);
					document.getElementById('supplier_tinError').className = "invalid-feedback";		
				
				$('#switch_notice_off').show();
				$('#sw_off').html("Invalid Input");
				setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);		  	  
				  
				}
			   });
		
	  });
	  
	<!--supplier Deletion Confirmation-->
	$('body').on('click','#deletesupplier',function(){
			
			event.preventDefault();
			let supplierID = $(this).data('id');
			
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
					
					document.getElementById("deletesupplierConfirmed").value = supplierID;
					
					/*Set Details*/
					$('#confirm_delete_supplier_name').text(response.supplier_name);
					$('#confirm_delete_supplier_address').text(response.supplier_address);	
					$('#confirm_delete_supplier_tin').text(response.supplier_tin);	
					
					$('#supplierDeleteModal').modal('toggle');		
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });		
	  });

	  <!--supplier Confirmed For Deletion-->
	  $('body').on('click','#deletesupplierConfirmed',function(){
			
			event.preventDefault();

			let supplierID = document.getElementById("deletesupplierConfirmed").value;
			
			  $.ajax({
				url: "/delete_supplier_confirmed",
				type:"POST",
				data:{
				  supplierID:supplierID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					$('#switch_notice_off').show();
					$('#sw_off').html("supplier Deleted");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);	
					/*
					If you are using server side datatable, then you can use ajax.reload() 
					function to reload the datatable and pass the true or false as a parameter for refresh paging.
					*/
					
					var table = $("#getsupplierList").DataTable();
				    table.ajax.reload(null, false);
					
				  }
				},
				error: function(error) {
				 console.log(error);
				}
			   });		
	  });
  </script>