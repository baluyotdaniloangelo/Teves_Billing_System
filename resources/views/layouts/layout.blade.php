@include('layouts.header')
</head>
<!--
Use to automatically hide the Side Nav Bar
<body class="toggle-sidebar">-->
<body class="">
@yield('content')
 
@include('layouts.footer')

<?php

if (Request::is('billing')){
?>
@include('layouts.billing_script')
<?php
}else if (Request::is('product')){
?>
@include('layouts.product_script')
<?php
}
else if (Request::is('client')){
?>
@include('layouts.client_script')
<?php
}
else if (Request::is('supplier')){
?>
@include('layouts.supplier_script')
<?php
}
else if (Request::is('report')){
?>
@include('layouts.report_script')
<?php
}
else if (Request::is('user')){
?>
@include('layouts.user_script')
<?php
}
else if (Request::is('receivables')){
?>
@include('layouts.receivables_script')
<?php
}
else if (Request::is('salesorder')){
?>
@include('layouts.salesorder_script')
<?php
}
else if (Request::is('purchaseorder')){
?>
@include('layouts.purchaseorder_script')
<?php
}
?>
<script>
	<!--Selected Account For Update-->
	$('body').on('click','#accountUser',function(){
			
			event.preventDefault();
		
			  $.ajax({
				url: "/user_info",
				type:"POST",
				data:{
				  UserID:{{$data->user_id}},
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("account-user").value = {{$data->user_id}};
					
					/*Set Switch Details*/
					document.getElementById("account_user_real_name").value = response.user_real_name;
					document.getElementById("account_user_name").value = response.user_name;
					
					$('#UserProfileModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });		
	  });

	$("#account-user").click(function(event){
			
			event.preventDefault();
			
					/*Reset Warnings*/
					let userID = document.getElementById("account-user").value;
					$('#account_user_real_nameError').text('');				  
					$('#account_user_nameError').text('');
					$('#account_user_passwordError').text('');

			document.getElementById('AccountUserform').className = "g-2 needs-validation was-validated";

			let user_real_name 		= $("input[name=account_user_real_name]").val();
			let user_name 			= $("input[name=account_user_name]").val();
			let user_password 		= $("input[name=account_user_password]").val();
			
			$.ajax({
				url: "/user_account_post",
				type:"POST",
				data:{
				  userID:userID,
				  user_real_name:user_real_name,
				  user_name:user_name,
				  user_password:user_password,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  					
					$('#account_user_real_nameError').text('');
					$('#account_switch_timerError').text('');		
					$('#account_user_typeError').text('');
					
					$('.success_modal_bg').html(response.success);
					$('#SuccessModal').modal('toggle');
					//$('#UserProfileModal').modal('toggle');
				  
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				if(error.responseJSON.errors.user_real_name=="The user real name has already been taken."){
							  
				  $('#account_user_real_nameError').html("<b>"+ user_real_name +"</b> has already been taken.");
				  document.getElementById('account_user_real_nameError').className = "invalid-feedback";
				  document.getElementById('account_user_real_name').className = "form-control is-invalid";
				  $('#account_user_real_name').val("");
				  
				}else{
					
				  $('#account_user_real_nameError').text(error.responseJSON.errors.user_real_name);
				  document.getElementById('account_user_real_nameError').className = "invalid-feedback";		
				
				}
				
				if(error.responseJSON.errors.user_name=="The user name has already been taken."){
							  
				  $('#account_user_nameError').html("<b>"+ user_name +"</b> has already been taken.");
				  document.getElementById('account_user_nameError').className = "invalid-feedback";
				  document.getElementById('account_user_name').className = "form-control is-invalid";
				  $('#account_user_name').val("");
				  
				}else{
					
				  $('#account_user_nameError').text(error.responseJSON.errors.user_real_name);
				  document.getElementById('account_user_nameError').className = "invalid-feedback";		
				
				}
					
				  $('#account_user_passwordError').text(error.responseJSON.errors.user_password);
				  document.getElementById('account_user_passwordError').className = "invalid-feedback";		
				  
				
				$('#InvalidModal').modal('toggle');
				  
				}
			   });
	  });

</script>
</body>

</html>