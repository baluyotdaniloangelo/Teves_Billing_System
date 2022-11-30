<script type="text/javascript"> 
/* Ajax post */
$(document).ready(function() {
//myFunction();	

$("#create_report_btn").click(function(event) {
event.preventDefault();

var date_log = $("#date_log").val();

jQuery.ajax({
type: "POST",
url: "http://172.104.188.7/attendance/" + "index.php/employee_report_dec/generate_employee_attendance",
dataType: 'html',
data: {
	'date_log': date_log
	},
	
	success : function(result) {
	result_message = result.split(/\t/);

	report_result =result_message[0];
	$('#report_result').html(report_result);

	},
	beforeSend:function()
	{
	 $("#report_result").html("<b align='center'>* * * Loading * * *</b>")
	}

});
});
});
</script>

   <script type="text/javascript">

	<!--Load Table-->
	$("#save-billing-transaction").click(function(event){
			
			event.preventDefault();
			
					/*Reset Warnings*/
					$('#order_dateError').text('');
					$('#order_timeError').text('');
					$('#order_po_numberError').text('');				  
					$('#client_idxError').text('');
					$('#plate_noError').text('');
					$('#drivers_nameError').text('');
					$('#product_idxError').text('');
					$('#order_quantityError').text('');

			document.getElementById('BillingformNew').className = "g-3 needs-validation was-validated";

			let order_date 				= $("input[name=order_date]").val();
			let order_time 				= $("input[name=order_time]").val();
			let order_po_number 		= $("input[name=order_po_number]").val();
			let client_idx 				= $("#client_idx").val();
			let plate_no 				= $("input[name=plate_no]").val();
			let drivers_name 			= $("input[name=drivers_name]").val();
			let product_idx 			= $("#product_idx").val();
			let order_quantity 			= $("input[name=order_quantity]").val();
			
			  $.ajax({
				url: "/create_bill_post",
				type:"POST",
				data:{
				  order_date:order_date,
				  order_time:order_time,
				  order_po_number:order_po_number,
				  client_idx:client_idx,
				  plate_no:plate_no,
				  drivers_name:drivers_name,
				  product_idx:product_idx,
				  order_quantity:order_quantity,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					$('.success_modal_bg').html(response.success);
					$('#SuccessModal').modal('toggle');
					
					$('#order_dateError').text('');					
					$('#order_timeError').text('');
					$('#order_po_numberError').text('');
					$('#client_idxError').text('');
					
					$('#plate_noError').text('');
					$('#drivers_nameError').text('');
					$('#product_idxError').text('');
					$('#order_quantityError').text('');
					
				    /*
					If you are using server side datatable, then you can use ajax.reload() 
					function to reload the datatable and pass the true or false as a parameter for refresh paging.
					*/
					
					var table = $("#getBillingTransactionList").DataTable();
				    table.ajax.reload(null, false);
					
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				  $('#order_dateError').text(error.responseJSON.errors.order_date);
				  document.getElementById('order_dateError').className = "invalid-feedback";
				  			  
				  $('#order_timeError').text(error.responseJSON.errors.order_time);
				  document.getElementById('order_timeError').className = "invalid-feedback";		

				  $('#order_po_numberError').text(error.responseJSON.errors.order_po_number);
				  document.getElementById('order_po_numberError').className = "invalid-feedback";		
				
				  $('#client_idxError').text(error.responseJSON.errors.client_idx);
				  document.getElementById('client_idxError').className = "invalid-feedback";				
				  
				  $('#plate_noError').text(error.responseJSON.errors.plate_no);
				  document.getElementById('plate_noError').className = "invalid-feedback";				
				 
				  $('#drivers_nameError').text(error.responseJSON.errors.drivers_name);
				  document.getElementById('drivers_nameError').className = "invalid-feedback";				
				  
				  $('#product_idxError').text(error.responseJSON.errors.product_idx);
				  document.getElementById('product_idxError').className = "invalid-feedback";				
				 
 				  $('#order_quantityError').text(error.responseJSON.errors.order_quantity);
				  document.getElementById('order_quantityError').className = "invalid-feedback";
				
				
				$('#InvalidModal').modal('toggle');				  	  
				  
				}
			   });
		
	  });
  </script>
	