
	  
	$('body').on('click','#LockReceivables',function(){
			
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
					
					document.getElementById("LockConfirmed").value = ReceivableID;
					
					/*Set Details*/
					/*Receivale Details*/
					/*Product*/
					/*Payment*/
					/*Delivery*/
					//document.getElementById("confirm_delete_billing_date").value = response[0].billing_date;
					//document.getElementById("confirm_delete_control_number").innerHTML = response[0].control_number;
					//document.getElementById("confirm_delete_client_info").innerHTML = response[0].client_name;
					//document.getElementById("confirm_delete_description").textContent = response[0].receivable_description;
					//document.getElementById("confirm_delete_amount").innerHTML = response[0].receivable_amount;
					
					//$('#confirm_delete_Receivable_name').text(response.Receivable_name);
					//$('#confirm_delete_Receivable_price').text(response.Receivable_price);
					
					$('#ReceivableLockModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });		
	  });
