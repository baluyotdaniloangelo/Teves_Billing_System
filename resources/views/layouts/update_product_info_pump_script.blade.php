   <script type="text/javascript">
	/*Pump*/
	LoadProductPump();
	
	function LoadProductPump() {
		
			  $.ajax({
				url: "{{ route('ProductPump') }}",
				type:"POST",
				data:{
				  productID:{{ $productID }},
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
									
				  console.log(response);
				  if(response!='') {
					  
							ProductPumpListTable.clear().draw();
							ProductPumpListTable.rows.add(response.data).draw();	
			
				  }else{
							/*No Result Found or Error*/
				  }
				},
				error: function(error) {
				 console.log(error);	 
				}
			   });
	}  


	<!--Load Table-->
	let ProductPumpListTable = $('#ProductPumpListTable').DataTable({
			"language": {
						"lengthMenu":'<select class="dt-input">'+
			             '<option value="10">10</option>'+
			             '<option value="20">20</option>'+
			             '<option value="30">30</option>'+
			             '<option value="40">40</option>'+
			             '<option value="50">50</option>'+
			             '<option value="-1">All</option>'+
			             '</select> '
		    },
			/*processing: true,*/
			responsive: false,
			paging: true,
			searching: false,
			info: false,
			data: [],
			//scrollCollapse: true,
			//scrollY: '500px',
			//scrollx: false,
			columns: [
					{data: 'DT_RowIndex', name: 'DT_RowIndex' , orderable: false, searchable: false},
					{data: 'branch_code'},
					{data: 'pump_name'},
					{data: 'initial_reading', orderable: false, render: $.fn.dataTable.render.number( ',', '.', 2, '' ), className: "text-right" },
					{data: 'product_unit_measurement'},
					{data: 'action', name: 'action', orderable: false, searchable: false, className: "text-center"},
			],
			columnDefs: [
					//{ className: 'text-center', targets: [0, 3] },
			]
		});
		
	<!--Save New Pump->
	$("#save-pump").click(function(event){
			
			event.preventDefault();
			
					/*Reset Warnings*/
					$('#pump_nameError').text('');
					$('#initial_readingError').text('');

			document.getElementById('AddProductPump').className = "g-3 needs-validation was-validated";

			let pump_name 			= $("input[name=pump_name]").val();
			let initial_reading 	= $("input[name=initial_reading]").val();
			let branch_idx 			= $("#branch_idx_pump").val();
			
			  $.ajax({
				url: "/create_pump_post",
				type:"POST",
				data:{
				  product_idx:{{ $productID }},
				  pump_name:pump_name,
				  initial_reading:initial_reading,
				  branch_idx:branch_idx,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					$('#pump_nameError').text('');
					$('#initial_readingError').text('');
					
					$('#switch_notice_on').show();
					$('#sw_on').html(response.success);
					setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
					
					document.getElementById("AddProductPump").reset();
										
					$('#AddProductPumpModal').modal('toggle');	
					
					LoadProductPump();
				  
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				 if(error.responseJSON.errors.pump_name=="The pump name has already been taken."){
							  
				  $('#pump_nameError').html("<b>"+ pump_name +"</b> has already been taken.");
				  document.getElementById('pump_nameError').className = "invalid-feedback";
				  document.getElementById('pump_name').className = "form-control is-invalid";
				  $('#pump_name').val("");
				  
				}else{
					
				  $('#pump_nameError').text(error.responseJSON.errors.pump_name);
				  document.getElementById('pump_nameError').className = "invalid-feedback";
				  
				}
				
				  $('#initial_readingError').text(error.responseJSON.errors.initial_reading);
				  document.getElementById('initial_readingError').className = "invalid-feedback";			
				
				$('#switch_notice_off').show();
				$('#sw_off').html("Invalid Input");
				setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);			  	  
				  
				}
			   });		
			   
	});
	
	<!--Select Pump For Update-->
	$('body').on('click','#ProductPump_Edit',function(){
			
			event.preventDefault();
			let pumpID = $(this).data('id');
			
			  $.ajax({
				url: "/product_pump_info",
				type:"POST",
				data:{
				  pumpID:pumpID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("update-pump").value = pumpID;
					
					/*Set Details*/
					document.getElementById("update_pump_name").value = response.pump_name;
					document.getElementById("update_initial_reading").value = response.initial_reading;
					document.getElementById("update_branch_idx_pump").value = response.branch_idx;
										
					$('#UpdateProductPumpModal').modal('toggle');		
					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });	

	<!--Save New Pump->
	$("#update-pump").click(function(event){
			
			event.preventDefault();
			
					/*Reset Warnings*/
					$('#pump_nameError').text('');
					$('#initial_readingError').text('');

			document.getElementById('AddProductPump').className = "g-3 needs-validation was-validated";

			let pump_id 	= document.getElementById("update-pump").value;
			
			let pump_name 			= $("input[name=update_pump_name]").val();
			let initial_reading 	= $("input[name=update_initial_reading]").val();
			let branch_idx 			= $("#update_branch_idx_pump").val();
			
			  $.ajax({
				url: "/update_pump_post",
				type:"POST",
				data:{
				  pump_id:pump_id,
				  product_idx:{{ $productID }},
				  pump_name:pump_name,
				  initial_reading:initial_reading,
				  branch_idx:branch_idx,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					$('#update_pump_nameError').text('');
					$('#update_initial_readingError').text('');
					
					$('#switch_notice_on').show();
					$('#sw_on').html(response.success);
					setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
					
					document.getElementById("AddProductPump").reset();
										
					$('#UpdateProductPumpModal').modal('toggle');	
					
					LoadProductPump();
				  
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				 if(error.responseJSON.errors.pump_name=="The pump name has already been taken."){
							  
				  $('#update_pump_nameError').html("<b>"+ pump_name +"</b> has already been taken.");
				  document.getElementById('update_pump_nameError').className = "invalid-feedback";
				  document.getElementById('update_pump_name').className = "form-control is-invalid";
				  $('#update_update_gateway_sn').val("");
				  
				}else{
				  $('#update_pump_nameError').text(error.responseJSON.errors.pump_name);
				  document.getElementById('update_pump_nameError').className = "invalid-feedback";
				}
				
				  $('#update_initial_readingError').text(error.responseJSON.errors.initial_reading);
				  document.getElementById('update_initial_readingError').className = "invalid-feedback";			
				
				$('#switch_notice_off').show();
				$('#sw_off').html("Invalid Input");
				setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);			  	  
				  
				}
			   });		
	  });
	

	<!--Product Deletion Confirmation-->
	$('body').on('click','#ProductPump_delete',function(){
			
			event.preventDefault();
			let pumpID = $(this).data('id');
			
			  $.ajax({
				url: "/product_pump_info",
				type:"POST",
				data:{
				  pumpID:pumpID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
											
					/*Set Details*/
					$('#delete_pump_name').text(response.pump_name);					
					$('#delete_pump_name_branch').text(response.branch_code);	
					
					document.getElementById("deletePumpInfoConfirmed").value = pumpID;
					
					$('#PumpInfoDeleteModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });		
	  });	
	  
	  
	<!--Product Confirmed For Deletion-->
	$('body').on('click','#deletePumpInfoConfirmed',function(){
			
			event.preventDefault();

			let pumpID = document.getElementById("deletePumpInfoConfirmed").value;
			
			  $.ajax({
				url: "/delete_product_pump_confirmed",
				type:"POST",
				data:{
				   pumpID:pumpID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					$('#switch_notice_off').show();
					$('#sw_off').html("Pump Information Deleted!");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);	
					
					LoadProductPump();
					
				  }
				},
				error: function(error) {
				 console.log(error);
				}
			   });		
	  });
	  
	  
  </script>	  