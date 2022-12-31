   <!-- Page level plugins -->
   <script src="{{asset('datatables/jquery.dataTables.js')}}"></script>
   <script src="{{asset('datatables/dataTables.bootstrap4.js')}}"></script>
   <script type="text/javascript">
	<!--Load Table-->				
	$(function () {

		var switchTable = $('#switchList').DataTable({
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
				<?php if($data->user_type!="User"){ ?>
				url : "{{ route('SwitchList') }}",
				<?php }else{ ?>
				url : "{{ route('SwitchListUser') }}",
				<?php } ?>
				method : 'POST',
				data: { _token: "{{ csrf_token() }}" },
			},
			columns: [
					{data: 'DT_RowIndex', name: 'DT_RowIndex' , orderable: false, searchable: false},
					{data: 'switch_name'},     
					{data: 'switch_panel_name'},           
					{data: 'switch_state', name: 'switch_state', orderable: false, searchable: false},
					{data: 'switch_status', name: 'switch_status', orderable: false, searchable: false},
					{data: 'switch_on_time'},
					{data: 'switch_off_time'},
					{data: 'action', name: 'action', orderable: false, searchable: false},
			],
			columnDefs: [
					{ className: 'text-center', targets: [0, 1, 2, 3, 4, 5, 6, 7] },
			]
		});
		  
		  /*Add Options*/
		  $('<div class="btn-group" role="group" aria-label="Basic outlined example"style="margin-top: -50px; position: absolute;">'+
		  '<button type="button" class="btn btn-success new_item bi bi-plus-circle" data-bs-toggle="modal" data-bs-target="#CreateSwitchModal"></button>'+
		  '</div>').appendTo('#switch_option');
			
		 <?php if($data->user_type!="User"){ ?>
		 /*Add Options*/
		  $('<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-bottom: 10px;"><span style="margin-top: 12px; margin-right: 10px; font-weight: bold;">Bulk Control:</span>'+
		  '<button type="button" id="onAll" class="btn ri-lightbulb-flash-fill" style="background-color: yellow;color: #b1b141;font-size: 1.3rem; border-radius: 30px;margin: 2px;"></></button>'+
		  '<button type="button" id="offAll" class="btn ri-lightbulb-line" style="background-color: #383d35c2;color: #c9cac8;font-size: 1.3rem; border-radius: 30px; margin: 1px;"></button>'+		  
		  '<button type="button" id="sendAll" class="btn ri-send-plane-fill" style="background-color: #fffbc1;color: #000;font-size: 1.3rem; border-radius: 30px;margin: 1px;"></button>'+
		  '</div>').appendTo('.sw_menu');
		  <?php }else{ ?>
		  /*Add Options*/
		  $('<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-bottom: 10px;"><span style="margin-top: 12px; margin-right: 10px; font-weight: bold;">Bulk Control:</span>'+
		  '<button type="button" id="onAll" class="btn ri-lightbulb-flash-fill" style="background-color: yellow;color: #b1b141;font-size: 1.3rem; border-radius: 30px;margin: 1px;"></></button>'+
		  '<button type="button" id="offAll" class="btn ri-lightbulb-line" style="background-color: #383d35c2;color: #c9cac8;font-size: 1.3rem; border-radius: 30px;margin: 1px;"></button>'+		  
		  '</div>').appendTo('.sw_menu');
		  <?php } ?>
		  
		  /*Auto Refresh Datatable*/
		  setInterval( function () {
				switchTable.ajax.reload(null, false);	/* user paging is not reset on reload*/
		  }, 500 );	/*Reload the table data every 1 seconds*/
	
	});

	<!--Save New Switch-->
	$("#save-switch").click(function(event){
			
			event.preventDefault();
			
					/*Reset Warnings*/			
					$('#switch_nameError').text('');				  
					$('#switch_on_timeError').text('');
					$('#switch_off_timeError').text('');
					$('#switch_module_idError').text('');
					$('#switch_relay_noError').text('');
					$('#switch_panel_nameError').text('');

			document.getElementById('switchform').className = "g-3 needs-validation was-validated";
			
			let switch_name 		= $("input[name=switch_name]").val();
			let switch_on_time 		= $("input[name=switch_on_time]").val();
			let switch_off_time 	= $("input[name=switch_off_time]").val();
			let switch_module_id 	= $("input[name=switch_module_id]").val();
			let switch_relay_no 	= $("input[name=switch_relay_no]").val();
			let switch_panel_name 	= $("input[name=switch_panel_name]").val();
					
			  $.ajax({
				<?php if($data->user_type!="User"){ ?>
				url: "/create_switch_post",
				<?php }else{ ?>
				url: "#",
				<?php } ?>
				type:"POST",
				data:{
				  switch_name:switch_name,
				  switch_on_time:switch_on_time,
				  switch_off_time:switch_off_time,
				  switch_module_id:switch_module_id,
				  switch_relay_no:switch_relay_no,
				  switch_panel_name:switch_panel_name,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				 
				  if(response) {
					
					$('#switch_nameError').text('');
					$('#switch_timerError').text('');					
					$('#switch_module_idError').text('');
					$('#switch_relay_noError').text('');
					$('#switch_panel_nameError').text('');		

					document.getElementById('switchform').className = "g-3 needs-validation";
					/*document.getElementById("switchform").reset();*/

					$('#switch_notice_on').show();
					$('#sw_on').html(response.success);
					setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },500);
				  
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				if(error.responseJSON.errors.switch_name=="The switch name has already been taken."){
							  
				  $('#switch_nameError').html("<b>"+ switch_name +"</b> has already been taken.");
				  document.getElementById('switch_nameError').className = "invalid-feedback";
				  document.getElementById('switch_name').className = "form-control is-invalid";
				  $('#switch_name').val("");
				  
				}else{
					
				  $('#switch_nameError').text(error.responseJSON.errors.switch_name);
				  document.getElementById('switch_nameError').className = "invalid-feedback";		
				
				}

				/*
				if(error.responseJSON.errors.switch_on_time!="" || error.responseJSON.errors.switch_off_time!=""){
							  
				  $('#switch_timerError').html("Invalid Time");
				  document.getElementById('switch_timerError').className = "invalid-feedback";
				  
				  document.getElementById('switch_on_time').className = "form-control is-invalid";
				  $('#switch_on_time').val("");
				  
				  document.getElementById('switch_off_time').className = "form-control is-invalid";
				  $('#switch_off_time').val("");
				  
				}
				*/
				
				document.getElementById('switch_module_idError').className = "invalid-feedback";
				document.getElementById('switch_module_id').className = "form-control is-invalid";				
				$('#switch_module_idError').html(error.responseJSON.errors.switch_module_id);
				 
				document.getElementById('switch_relay_noError').className = "invalid-feedback";
				document.getElementById('switch_relay_no').className = "form-control is-invalid";
				$('#switch_relay_noError').html(error.responseJSON.errors.switch_relay_no);
				 
				document.getElementById('switch_panel_nameError').className = "invalid-feedback";
				document.getElementById('switch_panel_name').className = "form-control is-invalid";
				$('#switch_panel_nameError').html(error.responseJSON.errors.switch_panel_name);
				 
				//$('#InvalidModal').modal('toggle');
				$('#switch_notice_off').show();
				$('#sw_off').html("Invalid Input");
				setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },500);
				  
				}
			   });		
	  });

	<!--Select Site For Update-->
	$('body').on('click','#infoSwitch',function(){
			
			event.preventDefault();
			let switchID = $(this).data('id');
			
			  $.ajax({
				url: "/switch_info",
				type:"POST",
				data:{
				  switchID:switchID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
									
					/*Set Switch Details*/
					
					if(response.switch_on_time==null){
						 sw_on_time = '';
					}else{
						 sw_on_time = response.switch_on_time;
					}
					
					if(response.switch_off_time==null){
						 sw_off_time = '';
					}else{
						 sw_off_time = response.switch_off_time;
					}
					
					$('#info_switch_name').html(response.switch_name);
					$('#info_switch_schedule').html('ON: '+sw_on_time+'<br> OFF: '+sw_off_time);
					$('#info_switch_module_id').html(response.switch_module_id);
					$('#info_switch_relay_no').html(response.switch_relay_no);
					$('#info_switch_panel_name').html(response.switch_panel_name);
					
					$('#info_heart_beat').html(response.heart_beat);
					
					if(response.switch_state==1){
					$('#info_switch_state').html("<span style='color:green; font-weight:bold;'>ON</span>");
					}else{
					$('#info_switch_state').html("<span style='color:black; font-weight:bold;'>OFF</span>");
					}
					
					$('#InfoSwitchModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });		
	  });

	<!--Select Site For Update-->
	$('body').on('click','#editSwitch',function(){
			
			event.preventDefault();
			let switchID = $(this).data('id');
			
			  $.ajax({
				<?php if($data->user_type!="User"){ ?>
				url: "/switch_info",
				<?php }else{ ?>
				url: "#",
				<?php } ?>
				type:"POST",
				data:{
				  switchID:switchID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("update-switch").value = switchID;
					
					/*Set Switch Details*/
					document.getElementById("update_switch_name").value = response.switch_name;
					document.getElementById("update_switch_on_time").value = response.switch_on_time;
					document.getElementById("update_switch_off_time").value = response.switch_off_time;
					document.getElementById("update_switch_relay_no").value = response.switch_relay_no;
					document.getElementById("update_switch_module_id").value = response.switch_module_id;
					document.getElementById("update_switch_panel_name").value = response.switch_panel_name;
					
					$('#UpdateSwitchModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });		
	  });

	$("#update-switch").click(function(event){
			
			event.preventDefault();
			
					/*Reset Warnings*/
					let switchID = document.getElementById("update-switch").value;
					$('#update_switch_nameError').text('');				  
					$('#update_switch_on_timeError').text('');
					$('#update_switch_off_timeError').text('');
					$('#update_switch_module_idError').text('');
					$('#update_switch_relay_noError').text('');
					$('#update_switch_panel_nameError').text('');

			document.getElementById('switchformupdate').className = "g-2 needs-validation was-validated";

			let switch_name 				= $("input[name=update_switch_name]").val();
			let switch_on_time 				= $("input[name=update_switch_on_time]").val();
			let switch_off_time 			= $("input[name=update_switch_off_time]").val();
			let switch_module_id 			= $("input[name=update_switch_module_id]").val();
			let switch_relay_no 			= $("input[name=update_switch_relay_no]").val();
			let switch_panel_name 			= $("input[name=update_switch_panel_name]").val();
			
			  $.ajax({
				<?php if($data->user_type!="User"){ ?>
				url: "/update_switch_post",
				<?php }else{ ?>
				url: "#",
				<?php } ?>
				type:"POST",
				data:{
				  switchID:switchID,
				  switch_name:switch_name,
				  switch_on_time:switch_on_time,
				  switch_off_time:switch_off_time,
				  switch_module_id:switch_module_id,
				  switch_relay_no:switch_relay_no,
				  switch_panel_name:switch_panel_name,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					$('.success_modal_bg').html(response.success);
					
					$('#update_switch_nameError').text('');
					$('#update_switch_timerError').text('');					
					$('#update_switch_module_idError').text('');
					$('#update_switch_relay_noError').text('');
					$('#update_switch_panel_nameError').text('');
					
					$('#UpdateSwitchModal').modal('toggle');
					
					$('#switch_notice_on').show();
					$('#sw_on').html(response.success);
					setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },500);
				  
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				if(error.responseJSON.errors.switch_name=="The switch name has already been taken."){
							  
				  $('#update_switch_nameError').html("<b>"+ switch_name +"</b> has already been taken.");
				  document.getElementById('update_switch_nameError').className = "invalid-feedback";
				  document.getElementById('update_switch_name').className = "form-control is-invalid";
				  $('#update_switch_name').val("");
				  
				}else{
					
				  $('#update_switch_nameError').text(error.responseJSON.errors.switch_name);
				  document.getElementById('update_switch_nameError').className = "invalid-feedback";		
				
				}
				
				/*
				if(error.responseJSON.errors.switch_on_time!="" || error.responseJSON.errors.switch_off_time!=""){
							  
				  $('#switch_timerError').html("Invalid Time");
				  document.getElementById('switch_timerError').className = "invalid-feedback";
				  
				  document.getElementById('switch_on_time').className = "form-control is-invalid";
				  $('#switch_on_time').val("");
				  
				  document.getElementById('switch_off_time').className = "form-control is-invalid";
				  $('#switch_off_time').val("");
				  
				}
				*/
				
				document.getElementById('update_switch_module_idError').className = "invalid-feedback";
				document.getElementById('update_switch_module_id').className = "form-control is-invalid";				
				$('#update_switch_module_idError').html(error.responseJSON.errors.switch_module_id);
				 
				document.getElementById('update_switch_relay_noError').className = "invalid-feedback";
				document.getElementById('update_switch_relay_no').className = "form-control is-invalid";
				$('#update_switch_relay_noError').html(error.responseJSON.errors.switch_relay_no);
				 
				document.getElementById('update_switch_panel_nameError').className = "invalid-feedback";
				document.getElementById('update_switch_panel_name').className = "form-control is-invalid";
				$('#update_switch_panel_nameError').html(error.responseJSON.errors.switch_panel_name); 
				
				$('#switch_notice_off').show();
				$('#sw_off').html("Invalid Input");
				setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },500);
				  
				}
			   });
	  });
	<!--Switch Deletion Confirmation-->
	$('body').on('click','#deleteSwitch',function(){
			
			event.preventDefault();
			let switchID = $(this).data('id');
			
			  $.ajax({
				<?php if($data->user_type!="User"){ ?>
				url: "/switch_info",
				<?php }else{ ?>
				url: "#",
				<?php } ?>
				type:"POST",
				data:{
				  switchID:switchID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("deleteSwitchConfirmed").value = switchID;
					$('#switch_name_info').html(response.switch_name);
					$('#SwitchDeleteModal').modal('toggle');
					$('#sw_off').html("Successfully Deleted "+response.switch_name+"!");					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });

	  <!--Switch Confirmed For Deletion-->
	  $('body').on('click','#deleteSwitchConfirmed',function(){
			
			event.preventDefault();

			let switchID = document.getElementById("deleteSwitchConfirmed").value;
			
			  $.ajax({
				<?php if($data->user_type!="User"){ ?>
				url: "/delete_switch_confirmed",
				<?php }else{ ?>
				url: "#",
				<?php } ?>
				type:"POST",
				data:{
				  switchID:switchID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					$('#switch_notice_off').show();
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },500);					
				  }
				},
				error: function(error) {
				 console.log(error);
					//alert(error);
				}
			   });	
	  });
	 
 	<!--Switch ON All-->
	$('body').on('click','#onAll',function(){
			event.preventDefault();
			var switch_id = [];
			$.each($("[id='ONSwitch']"), function(){
			switch_id.push($(this).attr("data-id"));
			});
			var switch_ids = switch_id.join(",");
			$.ajax({				
				url: "/toggle_all_state",
				type:"POST",
				data:{
				  switch_ids:switch_ids,
				  switch_state:1,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					   if(response!="No Action Taken"){
						/*$('#switch_notice_on').show();
						$('#sw_on').html("ON");
						setTimeout(function() { $('#switch_notice_on').fadeOut('slow'); },100);*/
					   }
					}
				},
				error: function(error) {
				 console.log(error);
				}
			   });
	  });	
 
 	<!--Switch ON All-->
	$('body').on('click','#offAll',function(){
			event.preventDefault();
			var switch_id = [];
			$.each($("[id='OFFSwitch']"), function(){
			switch_id.push($(this).attr("data-id"));
			});
			var switch_ids = switch_id.join(",");
			$.ajax({				
				url: "/toggle_all_state",
				type:"POST",
				data:{
				  switch_ids:switch_ids,
				  switch_state:2,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  if(response!="No Action Taken"){
					    /*$('#switch_notice_off').show();
						$('#sw_off').html("OFF");
						setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },200);*/
					  }
					}
				},
				error: function(error) {
				 console.log(error);
				}
			   });
	  });	
	
	<!--Send Switch-->
	$('body').on('click','#sendAll',function(){
			event.preventDefault();
			var switch_id = [];
			$.each($("[id='sendSwitch']"), function(){
			switch_id.push($(this).attr("data-id"));
			});
			var switch_ids = switch_id.join(",");
			  $.ajax({
				<?php if($data->user_type!="User"){ ?>
				url: "/toggle_all_send",
				<?php }else{ ?>
				url: "#",
				<?php } ?>
				type:"POST",
				data:{
				  switch_ids:switch_ids,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response!="No Action Taken") {
					/*$('#switch_notice_send').show();
					$('#sw_send').html("Send");
					setTimeout(function() { $('#switch_notice_send').fadeOut('slow'); },200);*/
				}
				},
				error: function(error) {
				 console.log(error);
				}
			   });
	  });	  
	  
	<!--Send Switch-->
	$('body').on('click','#sendSwitch',function(){
			event.preventDefault();
			let switchID = $(this).data('id');
			  $.ajax({
				<?php if($data->user_type!="User"){ ?>
				url: "/SENDSwitch",
				<?php }else{ ?>
				url: "#",
				<?php } ?>
				type:"POST",
				data:{
				  switchID:switchID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					/*$('#switch_notice_send').show();
					$('#sw_send').html(response + " Send");
					setTimeout(function() { $('#switch_notice_send').fadeOut('slow'); },100);*/
				}
				},
				error: function(error) {
				 console.log(error);
				}
			   });
	  });	  
	  
	<!--ON Switch-->
	$('body').on('click','#ONSwitch',function(){
			event.preventDefault();
			let switchID = $(this).data('id');
			  $.ajax({
				url: "/ONSwitch",
				type:"POST",
				data:{
				  switchID:switchID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					/*$('#switch_notice_on').show();
					$('#sw_on').html(response + " ON");
					setTimeout(function() { $('#switch_notice_on').fadeOut('slow'); },100);*/
				}
				},
				error: function(error) {
				 console.log(error);
				}
			   });
	  });
	  
	<!--OFF Switch-->
	$('body').on('click','#OFFSwitch',function(){
			event.preventDefault();
			let switchID = $(this).data('id');
			  $.ajax({
				url: "/OFFSwitch",
				type:"POST",
				data:{
				  switchID:switchID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					/*$('#switch_notice_off').show();
					$('#sw_off').html(response + " OFF");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },100);*/
				}
				},
				error: function(error) {
				 console.log(error);
				}
			   });   
	});	
</script>