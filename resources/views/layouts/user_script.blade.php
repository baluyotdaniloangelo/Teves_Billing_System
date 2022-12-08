   <!-- Page level plugins -->
   <script src="{{asset('datatables/jquery.dataTables.js')}}"></script>
   <script src="{{asset('datatables/dataTables.bootstrap4.js')}}"></script>
   <script type="text/javascript">
	<!--Load Table-->				
	$(function () {
				
		var switchTable = $('#userList').DataTable({
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
			responsive: true,
			serverSide: true,
			stateSave: true,/*Remember Searches*/
			ajax: {
				url : "{{ route('UserList') }}",
				method : 'POST',
				data: { _token: "{{ csrf_token() }}" },
			},
			columns: [
					{data: 'DT_RowIndex', name: 'DT_RowIndex' , orderable: false, searchable: false},    
					{data: 'user_real_name'},   
					{data: 'user_job_title'},	
					{data: 'user_name'}, 					
					{data: 'user_type'},			
					{data: 'created_at_dt_format', name: 'switch_status', orderable: true, searchable: false},
					{data: 'updated_at_dt_format', name: 'switch_status', orderable: true, searchable: false},
					{data: 'action', name: 'action', orderable: false, searchable: false},
			],
			columnDefs: [
					{ className: 'text-center', targets: [0, 1, 2, 3, 4, 5, 6, 7] },
			],
			
		});
		  /*Add Options*/
		  $('<div class="btn-group" role="group" aria-label="Basic outlined example"style="margin-top: -50px; position: absolute;">'+
		  '<button type="button" class="btn btn-success new_item bi bi-plus-circle" data-bs-toggle="modal" data-bs-target="#CreateUserModal"></button>'+
		  '</div>').appendTo('#user_option');
	});
	
	
	<!--Save New Site-->
	$("#save-user").click(function(event){
			
			event.preventDefault();
			
					/*Reset Warnings*/			
					$('#user_real_nameError').text('');				  
					$('#user_nameError').text('');
					$('#user_passwordError').text('');
					$('#user_typeError').text('');

			document.getElementById('CreateUserform').className = "g-3 needs-validation was-validated";
			
			let user_real_name 		= $("input[name=user_real_name]").val();
			let user_name 			= $("input[name=user_name]").val();
			let user_password 		= $("input[name=user_password]").val();
			let user_type 			= $("#user_type").val();
			let user_job_title 		= $("input[name=user_job_title]").val();
			
			  $.ajax({
				url: "/create_user_post",
				type:"POST",
				data:{
				  user_real_name:user_real_name,
				  user_name:user_name,
				  user_password:user_password,
				  user_type:user_type,
				  user_job_title:user_job_title,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				 
				  if(response) {
					  
					$('.success_modal_bg').html(response.success);
					$('#SuccessModal').modal('toggle');
					
					$('#user_real_nameError').text('');				  
					$('#user_nameError').text('');
					$('#user_passwordError').text('');
					$('#user_typeError').text('');		
					$('#user_job_titleError').text('');
					
					document.getElementById("CreateUserform").reset();
				
					var table = $("#userList").DataTable();
				    table.ajax.reload(null, false);
				  
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				if(error.responseJSON.errors.user_real_name=="The user real name has already been taken."){
							  
				  $('#user_real_nameError').html("<b>"+ user_real_name +"</b> has already been taken.");
				  document.getElementById('user_real_nameError').className = "invalid-feedback";
				  document.getElementById('user_real_name').className = "form-control is-invalid";
				  $('#user_real_name').val("");
				  
				}else{
					
				  $('#user_real_nameError').text(error.responseJSON.errors.user_real_name);
				  document.getElementById('user_real_nameError').className = "invalid-feedback";		
				
				}
				
				if(error.responseJSON.errors.user_name=="The user name has already been taken."){
							  
				  $('#user_nameError').html("<b>"+ user_name +"</b> has already been taken.");
				  document.getElementById('user_nameError').className = "invalid-feedback";
				  document.getElementById('user_name').className = "form-control is-invalid";
				  $('#user_name').val("");
				  
				}else{
					
				  $('#user_nameError').text(error.responseJSON.errors.user_real_name);
				  document.getElementById('user_nameError').className = "invalid-feedback";		
				
				}
					
				  $('#user_passwordError').text(error.responseJSON.errors.user_password);
				  document.getElementById('user_passwordError').className = "invalid-feedback";		
				  
				  $('#user_typeError').text(error.responseJSON.errors.user_type);
				  document.getElementById('user_typeError').className = "invalid-feedback";		
				  
				  $('#user_job_titleError').text(error.responseJSON.errors.user_job_title);
				  document.getElementById('user_job_titleError').className = "invalid-feedback";	
				
				  $('#InvalidModal').modal('toggle');
				  
				}
			   });
		
	  });

	<!--Select Site For Update-->
	$('body').on('click','#editUser',function(){
			
			event.preventDefault();
			let UserID = $(this).data('id');
			
			  $.ajax({
				url: "/user_info",
				type:"POST",
				data:{
				  UserID:UserID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("update-user").value = UserID;
					
					/*Set Switch Details*/
					document.getElementById("update_user_real_name").value = response.user_real_name;
					document.getElementById("update_user_name").value = response.user_name;
					document.getElementById("update_user_type").value = response.user_type;
					document.getElementById("update_user_job_title").value = response.user_job_title;
					$('#UpdateUserModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });		
	  });

	$("#update-user").click(function(event){
			
			event.preventDefault();
			
					/*Reset Warnings*/
					let userID = document.getElementById("update-user").value;
					$('#update_user_real_nameError').text('');				  
					$('#update_user_nameError').text('');
					$('#update_user_passwordError').text('');
					$('#update_user_typeError').text('');
					$('#update_user_job_titleError').text('');
			
			document.getElementById('UpdateUserform').className = "g-2 needs-validation was-validated";

			let user_real_name 		= $("input[name=update_user_real_name]").val();
			let user_name 			= $("input[name=update_user_name]").val();
			let user_password 		= $("input[name=update_user_password]").val();
			let user_type 			= $("#update_user_type").val();			
			let user_job_title 		= $("input[name=update_user_job_title]").val();
			
			$.ajax({
				url: "/update_user_post",
				type:"POST",
				data:{
				  userID:userID,
				  user_real_name:user_real_name,
				  user_name:user_name,
				  user_password:user_password,
				  user_type:user_type,
				  user_job_title:user_job_title,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					$('.success_modal_bg').html(response.success);
					$('#SuccessModal').modal('toggle');
					
					$('#update_user_real_nameError').text('');
					$('#update_switch_timerError').text('');					
					$('#update_user_typeError').text('');
					
					$('#UpdateUserModal').modal('toggle');
					
					var table = $("#userList").DataTable();
				    table.ajax.reload(null, false);
				  
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				if(error.responseJSON.errors.user_real_name=="The user real name has already been taken."){
							  
				  $('#update_user_real_nameError').html("<b>"+ user_real_name +"</b> has already been taken.");
				  document.getElementById('update_user_real_nameError').className = "invalid-feedback";
				  document.getElementById('update_user_real_name').className = "form-control is-invalid";
				  $('#user_real_name').val("");
				  
				}else{
					
				  $('#update_user_real_nameError').text(error.responseJSON.errors.user_real_name);
				  document.getElementById('update_user_real_nameError').className = "invalid-feedback";		
				
				}
				
				if(error.responseJSON.errors.user_name=="The user name has already been taken."){
							  
				  $('#update_user_nameError').html("<b>"+ user_name +"</b> has already been taken.");
				  document.getElementById('update_user_nameError').className = "invalid-feedback";
				  document.getElementById('update_user_name').className = "form-control is-invalid";
				  $('#update_user_name').val("");
				  
				}else{
					
				  $('#update_user_nameError').text(error.responseJSON.errors.user_real_name);
				  document.getElementById('update_user_nameError').className = "invalid-feedback";		
				
				}
					
				  $('#update_user_passwordError').text(error.responseJSON.errors.user_password);
				  document.getElementById('user_passwordError').className = "invalid-feedback";		
				  
				  $('#update_user_typeError').text(error.responseJSON.errors.user_type);
				  document.getElementById('update_user_typeError').className = "invalid-feedback";	

			      $('#update_user_job_titleError').text(error.responseJSON.errors.user_job_title);
				  document.getElementById('update_user_job_titleError').className = "invalid-feedback";					  
				
				$('#InvalidModal').modal('toggle');
				  
				}
			   });
	  });
	<!--Switch Deletion Confirmation-->
	$('body').on('click','#deleteUser',function(){
			
			event.preventDefault();
			let UserID = $(this).data('id');
			
			  $.ajax({
				url: "/user_info",
				type:"POST",
				data:{
				  UserID:UserID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("deleteUserConfirmed").value = UserID;
					
					$('#user_real_name_info_confirm').html(response.user_real_name);
					$('#user_name_info_confirm').html(response.user_real_name);
					$('#user_type_info_confirm').html(response.user_type);

					$('#user_real_name_info_confirmed').html(response.user_real_name);
					$('#user_name_info_confirmed').html(response.user_real_name);
					$('#user_type_info_confirmed').html(response.user_type);
					
					$('#UserDeleteModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });

	  <!--User Confirmed For Deletion-->
	  $('body').on('click','#deleteUserConfirmed',function(){
			
			event.preventDefault();

			let userID = document.getElementById("deleteUserConfirmed").value;
			
				$.ajax({
				url: "/delete_user_confirmed",
				type:"POST",
				data:{
				  userID:userID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  
				  if(response) {
					
					$('#UserDeleteModalConfirmed').modal('toggle');

					var table = $("#userList").DataTable();
				    table.ajax.reload(null, false);
				  }
				},
				error: function(error) {
				 console.log(error);
				}
			   });	
		
	  });
</script>