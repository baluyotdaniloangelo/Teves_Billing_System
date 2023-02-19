   <script type="text/javascript">

	<!--Load Table-->
	$(function () {

		var ReceivableListTable = $('#getReceivablesList').DataTable({
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
			ajax: "{{ route('getReceivablesList') }}",
			columns: [
					{data: 'DT_RowIndex', name: 'DT_RowIndex' , orderable: false, searchable: false},
					{data: 'billing_date'},
					{data: 'control_number'},
					{data: 'client_name'},   
					{data: 'or_number'},
					{data: 'payment_term'},
					{data: 'receivable_description'},
					{data: 'receivable_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },
					{data: 'receivable_status'},
					{data: 'action', name: 'action', orderable: false, searchable: false},
			],
			order: [[ 1, "desc" ]],
			columnDefs: [
					{ className: 'text-center', targets: [0, 1, 2, 3, 4] },
			]
		});
				/*
				$('<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">'+
				'<button type="button" class="btn btn-success new_item bi bi-plus-circle" data-bs-toggle="modal" data-bs-target="#CreateReceivableModal"></button>'+
				'</div>').appendTo('#Receivable_option');
				*/
	});
	
	<!--Select Receivable For Update-->
	
	$('body').on('click','#editReceivables',function(){
			
			event.preventDefault();
			let ReceivableID = $(this).data('id');
			
			  $.ajax({
				url: "/receivable_info",
				type:"POST",
				data:{
				  receivable_id:ReceivableID,
				  
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("update-receivables").value = ReceivableID;
					
					/*Set Details*/
							
					document.getElementById("client_name_receivables").innerHTML = response[0].client_name;
					document.getElementById("client_address_receivables").innerHTML = response[0].client_address;
					document.getElementById("control_no_receivables").innerHTML = response[0].control_number;	
					document.getElementById("billing_receivables").innerHTML = response[0].billing_date;					
					document.getElementById("client_tin_receivables").innerHTML = response[0].client_tin;
					document.getElementById("amount_receivables").innerHTML = response[0].receivable_amount;
					
					document.getElementById("billing_date").value = response[0].billing_date;
					document.getElementById("or_number").value = response[0].or_number;
					document.getElementById("payment_term").value = response[0].payment_term;
					document.getElementById("receivable_description").textContent = response[0].receivable_description;
					
					document.getElementById("receivable_status").value = response[0].receivable_status;
					
					document.getElementById("start_date").value = response[0].billing_period_start;
					document.getElementById("end_date").value = response[0].billing_period_end;
					document.getElementById("less_per_liter").value = response[0].less_per_liter;
					
					$('#UpdateReceivablesModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });


	$("#update-receivables").click(function(event){			
			event.preventDefault();
			
					/*Reset Warnings*/
					$('#update_Receivable_nameError').text('');
					$('#update_Receivable_priceError').text('');

			document.getElementById('ReceivableformEdit').className = "g-3 needs-validation was-validated";
			
			let ReceivableID 			= document.getElementById("update-receivables").value;
			let billing_date 			= $("input[name=billing_date]").val();	
			let or_number 				= $("input[name=or_number]").val();				
			let payment_term 			= $("input[name=payment_term]").val();
			let receivable_description 	= $("#receivable_description").val();
			let receivable_status 		= $("#receivable_status").val();
			
			let start_date 			= $("#start_date").val();
			let end_date 			= $("#end_date").val();
			let less_per_liter 		= $("#less_per_liter").val();
			
			  $.ajax({
				url: "/update_receivables_post",
				type:"POST",
				data:{
				  ReceivableID:ReceivableID,
				  billing_date:billing_date,
				  or_number:or_number,
				  payment_term:payment_term,
				  receivable_description:receivable_description,
				  receivable_status:receivable_status,
				  less_per_liter:less_per_liter,
				  start_date:start_date,
				  end_date:end_date,
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
					
					/*Close form*/
					$('#UpdateReceivablesModal').modal('toggle');
					
					/*Refresh Table*/
					var table = $("#getReceivablesList").DataTable();
				    table.ajax.reload(null, false);
					
					
					
					var query = {
								receivable_id:ReceivableID,
								_token: "{{ csrf_token() }}"
							}

					var url = "{{URL::to('generate_receivable_pdf')}}?" + $.param(query)
					window.open(url);
					
					
				  
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				$('#or_numberError').text(error.responseJSON.errors.product_price);
				document.getElementById('or_numberError').className = "invalid-feedback";	
				
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
	  
	<!--Receivable Deletion Confirmation-->
	$('body').on('click','#deleteReceivables',function(){
			
			event.preventDefault();
			let ReceivableID = $(this).data('id');
			
			  $.ajax({
				url: "/receivable_info",
				type:"POST",
				data:{
				  receivable_id:ReceivableID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("deleteReceivableConfirmed").value = ReceivableID;
					
					/*Set Details*/
					document.getElementById("confirm_delete_billing_date").value = response[0].billing_date;
					document.getElementById("confirm_delete_control_number").innerHTML = response[0].control_number;
					document.getElementById("confirm_delete_or_no").value = response[0].or_number;	
					document.getElementById("confirm_delete_client_info").innerHTML = response[0].client_name;
					document.getElementById("confirm_delete_description").textContent = response[0].receivable_description;
					document.getElementById("confirm_delete_amount").innerHTML = response[0].receivable_amount;
					
					$('#confirm_delete_Receivable_name').text(response.Receivable_name);
					$('#confirm_delete_Receivable_price').text(response.Receivable_price);
					
					$('#ReceivableDeleteModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });		
	  });

	  <!--Receivable Confirmed For Deletion-->
	  $('body').on('click','#deleteReceivableConfirmed',function(){
			
			event.preventDefault();

			let ReceivableID = document.getElementById("deleteReceivableConfirmed").value;
			
			  $.ajax({
				url: "/delete_receivable_confirmed",
				type:"POST",
				data:{
				  receivable_id:ReceivableID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					$('#switch_notice_off').show();
					$('#sw_off').html("Receivable Deleted");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);	
					
					/*
					If you are using server side datatable, then you can use ajax.reload() 
					function to reload the datatable and pass the true or false as a parameter for refresh paging.
					*/
					
					var table = $("#getReceivablesList").DataTable();
				    table.ajax.reload(null, false);
					
				  }
				},
				error: function(error) {
				 console.log(error);
				}
			   });		
	  });
	  
	  /*Re-print*/
	  $('body').on('click','#PrintReceivables',function(){	  
	  
			event.preventDefault();
			
			let ReceivableID = $(this).data('id');
			
			  $.ajax({
				url: "/receivable_info",
				type:"POST",
				data:{
				  receivable_id:ReceivableID,
				  
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("update-receivables").value = ReceivableID;
					
					/*Set Details*/
					let client_idx 		= response[0].client_id;
					let start_date 		= response[0].billing_period_start;
					let end_date 		= response[0].billing_period_end;
					let less_per_liter 	= response[0].less_per_liter;
					
					/*Open Billing Print Page*/				
					var query_billing = {
						client_idx:client_idx,
						start_date:start_date,
						end_date:end_date,
						less_per_liter:less_per_liter,
						_token: "{{ csrf_token() }}"
					}

					var url_billing = "{{URL::to('generate_report_pdf')}}?" + $.param(query_billing)
					window.open(url_billing);
		
					/*Open Receivable Print Page*/
					
					var query_receivable = {
								receivable_id:ReceivableID,
								_token: "{{ csrf_token() }}"
							}

					var url_receivable = "{{URL::to('generate_receivable_pdf')}}?" + $.param(query_receivable)
					window.open(url_receivable);
			
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });		
			
			
			
			
			
	  
	  });
  </script>
	