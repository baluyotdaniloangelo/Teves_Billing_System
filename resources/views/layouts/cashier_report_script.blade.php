   <script type="text/javascript">

	<!--Load Table-->
	$(function () {

		var ClientListTable = $('#getCashierReport').DataTable({
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
			ajax: "{{ route('getCashierReport') }}",
			columns: [
					{data: 'DT_RowIndex', name: 'DT_RowIndex' , orderable: false, searchable: false},
					{data: 'report_date'},
					{data: 'user_real_name'},
					{data: 'teves_branch'},		
					{data: 'forecourt_attendant'},
					{data: 'shift'},
					{data: 'action', name: 'action', orderable: false, searchable: false},
			],
			columnDefs: [
					{ className: 'text-center', targets: [0] },
			]
		});
				$('<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">'+
				'<button type="button" class="btn btn-success new_item bi bi-plus-circle" data-bs-toggle="modal" data-bs-target="#CashierReportModal"></button>'+
				'</div>').appendTo('#cashier_report_option');
	});
	
	<!--Save New Client->
	$("#save-cashiers-report").click(function(event){
			
			event.preventDefault();
			
					/*Reset Warnings*/
					$('#teves_branchError').text('');
					$('#forecourt_attendantError').text('');
					$('#report_dateError').text('');

			document.getElementById('CashierReportformNew').className = "g-3 needs-validation was-validated";

			let teves_branch 				= $("#teves_branch").val();
			let forecourt_attendant 		= $("input[name=forecourt_attendant]").val();
			let report_date 				= $("input[name=report_date]").val();
			let shift 						= $("input[name=shift]").val();
			
			  $.ajax({
				url: "/create_cashier_report_post",
				type:"POST",
				data:{
				  teves_branch:teves_branch,
				  forecourt_attendant:forecourt_attendant,
				  report_date:report_date,
				  shift:shift,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					$('#switch_notice_on').show();
					$('#sw_on').html(response.success);
					setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
					
					$('#teves_branchError').text('');
					$('#forecourt_attendantError').text('');
					$('#report_dateError').text('');
					
					document.getElementById("CashierReportformNew").reset();				
					document.getElementById('CashierReportformNew').className = "g-3 needs-validation";
					
					cashier_report_id = response.cashiers_report_id;
					
					/*Open Cashier's Report*/
					var url = "{{URL::to('cashiers_report_form')}}";
					window.location.href = url+'/'+cashier_report_id;
				  
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				 if(error.responseJSON.errors.teves_branch=="The client name has already been taken."){
							  
				  $('#teves_branchError').html("<b>"+ teves_branch +"</b> has already been taken.");
				  document.getElementById('teves_branchError').className = "invalid-feedback";
				  document.getElementById('teves_branch').className = "form-control is-invalid";
				  $('#teves_branch').val("");
				  
				}else{
				  $('#teves_branchError').text(error.responseJSON.errors.teves_branch);
				  document.getElementById('teves_branchError').className = "invalid-feedback";
				}
				
				  $('#forecourt_attendantError').text(error.responseJSON.errors.forecourt_attendant);
				  document.getElementById('forecourt_attendantError').className = "invalid-feedback";	
				  
				  $('#report_dateError').text(error.responseJSON.errors.report_date);
				  document.getElementById('report_dateError').className = "invalid-feedback";			

				$('#switch_notice_off').show();
				$('#sw_off').html("Invalid Input");
				setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);
				  
				}
			   });		
	  });

	<!--client Deletion Confirmation-->
	$('body').on('click','#deleteCashiersReport',function(){
			
			event.preventDefault();
			let CashiersReportID = $(this).data('id');
			alert(CashiersReportID);
			  $.ajax({
				url: "/cashiers_report_info",
				type:"POST",
				data:{
				  CashiersReportID:CashiersReportID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					/*Set Details*/
					$('#confirm_delete_teves_branch').text(response[0].teves_branch);
					$('#confirm_delete_forecourt_attendant').text(response[0].forecourt_attendant);	
					$('#confirm_delete_report_date').text(response[0].report_date);
					$('#confirm_delete_cashiers_name').text(response[0].user_real_name);
					$('#confirm_delete_shift').text(response[0].shift);
					
					$('#CashiersReportDeleteModal').modal('toggle');		
				  
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
					
					$('#switch_notice_off').show();
					$('#sw_off').html("Client Deleted");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);	
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