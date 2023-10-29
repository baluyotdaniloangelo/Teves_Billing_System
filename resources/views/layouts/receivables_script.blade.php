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
			responsive: true,
			ajax: "{{ route('getReceivablesList') }}",
			columns: [
					{data: 'DT_RowIndex', name: 'DT_RowIndex' , orderable: false, searchable: false},
					{data: 'billing_date'},
					{data: 'control_number'},
					{data: 'client_name'},
					{data: 'receivable_description'},
					{data: 'receivable_gross_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },
					{data: 'receivable_withholding_tax', render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },					
					{data: 'receivable_amount', render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },					
					{data: ({receivable_amount,receivable_remaining_balance}) => (Number(receivable_amount)-Number(receivable_remaining_balance)), render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },				
					{data: 'receivable_remaining_balance', render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },
					{data: 'receivable_status'},
					{data: 'action_print', name: 'action_print', orderable: false, searchable: false},
					{data: 'action', name: 'action', orderable: false, searchable: false},
			],
			order: [[ 1, "desc" ]],
			columnDefs: [
					{ className: 'text-center', targets: [0, 1, 2] },
			]
		});
				$('<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">'+
				'<a class="btn btn-success new_item bi bi-plus-circle"" href="{{ route('create_recievable') }}"></a>'+
				'</div>').appendTo('#receivable_option');
				
		$('a.toggle-vis').on('click', function (e) {
        e.preventDefault();
 
        // Get the column API object
        var column = table.column($(this).attr('data-column'));
 
        // Toggle the visibility
        column.visible(!column.visible());
		
		});			
				
	});
	
	<!--Pay Receivables-->
	$('body').on('click','#payReceivables',function(){
			
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
					
					document.getElementById("save-receivables-payment").value = ReceivableID;
					
					/*Set Details*/		
					document.getElementById("Pay_client_name_receivables").innerHTML = response[0].client_name;
					document.getElementById("Pay_client_address_receivables").innerHTML = response[0].client_address;
					document.getElementById("Pay_control_no_receivables").innerHTML = response[0].control_number;	
					document.getElementById("Pay_billing_receivables").innerHTML = response[0].billing_date;					
					document.getElementById("Pay_client_tin_receivables").innerHTML = response[0].client_tin;
					document.getElementById("Pay_amount_receivables").innerHTML = response[0].receivable_amount;
					
					/*Load Receivable Payment Table*/
					loadReceivablesPayment(ReceivableID);
					
					$('#PayReceivablesModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });
	 
	<!--Save New Sales Order-->
	$("#save-receivables-payment").click(function(event){

			event.preventDefault();
				
				let receivable_id 			= document.getElementById("save-receivables-payment").value;
				
				/*Payment Options*/
				var mode_of_payment = [];
				var date_of_payment = [];
				var reference_no = [];
				var payment_amount = [];
				var payment_id = [];
				
				 $.each($("[id='payment_item']"), function(){
					payment_id.push($(this).attr("data-id"));
				  });
				
 				 $('.mode_of_payment').each(function(){
					if($(this).val() == ''){
						alert('Please input Mode of Payment');
						exit();
					}else{  				  
				   		mode_of_payment.push($(this).val());
					}				  
				  });
				 
				  $('.date_of_payment').each(function(){
					if($(this).val() == ''){
						alert('Date of Payment is Empty');
						exit(); 
					}else{  				  
				   		date_of_payment.push($(this).val());
					}				  
				  });
				  
				  $('.reference_no').each(function(){
					if($(this).val() == ''){
						alert('Reference is Empty');
						exit(); 
					}else{  				  
				   		reference_no.push($(this).val());
					}				  
				  });	
				  
				  $('.payment_amount').each(function(){
					if($(this).val() == ''){
						alert('Payment Amount is Empty');
						exit(); 
					}else{  				  
				   		payment_amount.push($(this).val());
					}				  
				  });		
		
			  $.ajax({
				url: "/save_receivable_payment_post",
				type:"POST",
				data:{
				  receivable_id:receivable_id,
				  mode_of_payment:mode_of_payment,
				  date_of_payment:date_of_payment,
				  reference_no:reference_no,
				  payment_amount:payment_amount,
				  payment_id:payment_id,
				  _token: "{{ csrf_token() }}"
				},
			
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					$('#switch_notice_on').show();
					$('#sw_on').html(response.success);
					setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
					
					loadReceivablesPayment(receivable_id);
					
					var table = $("#getReceivablesList").DataTable();
				    table.ajax.reload(null, false);
		
					/*Close Modal*/
					//$('#CreateSalesOrderModal').modal('toggle');
					
				  }
				},
				error: function(error) {
					
				 console.log(error);
								
				$('#switch_notice_off').show();
				$('#sw_off').html("Invalid Input");
				setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);			  	  
				  
				}
			   });	
	  });
	 
	function NewPaymentRow() {
		
		var x = document.getElementById("receivable_payment_table_body_data").rows.length;
		/*Limit to 10 rows*/
		if(x > 50){
		   return;
		}else{
						$('#receivable_payment_table_body_data tr:last').after("<tr>"+
							"<td class='date_of_payment_td' align='center'><input type='date' class='form-control date_of_payment' id='date_of_payment' name='date_of_payment' value='<?=date('Y-m-d');?>'></td>"+
							"<td class='bank_td' align='center'><input type='text' class='form-control mode_of_payment' id='mode_of_payment' name=' mode_of_payment' list='mode_of_payment_list' autocomplete='off' value=''></td>"+
							"<td class='reference_no_td' align='center'><input type='text' class='form-control reference_no' id='reference_no' name='reference_no' value=''></td>"+
							"<td class='payment_amount_td' align='center'><input type='number' class='form-control payment_amount' id='payment_amount' name='payment_amount' value=''></td>"+
							"<td><div onclick='deletePaymentRow(this)' data-id='0' id='payment_item'><div align='center' class='action_table_menu_Product' style='margin-top: 6px;'><a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deletePayment'></a></div></div></td></tr>");
		}	
	}
	
	function deletePaymentRow(btn) {
			
		var paymentitemID= $(btn).data("id");			
		let receivable_id 			= document.getElementById("save-receivables-payment").value;
		var row = btn.parentNode.parentNode;
		row.parentNode.removeChild(row);
		
		if(paymentitemID!=0){
			/*Delete the Selected Item*/
			
			  $.ajax({
				url: "/delete_receivable_payment_item",
				type:"POST",
				data:{
				  paymentitemID:paymentitemID,
				  receivable_id:receivable_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
				  }
					var table = $("#getReceivablesList").DataTable();
				    table.ajax.reload(null, false);
				},
				error: function(error) {
				 console.log(error);
					//alert(error);
				}
			   });	
						   
		}
		
	}
	 
	function loadReceivablesPayment(ReceivableID) {
		
		event.preventDefault();
		
			  $.ajax({
				url: "/get_receivable_payment_list",
				type:"POST",
				data:{
				  receivable_id:ReceivableID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
							
				  console.log(response);
				  if(response!='') {
					  
					    $("#receivable_payment_table_body_data tr").remove();
						$('<tr style="display: none;"><td>HIDDEN</td></tr>').appendTo('#receivable_payment_table_body_data');
					  
						var len = response.length;
						for(var i=0; i<len; i++){
							
							var id = response[i].receivable_payment_id;
							
							var receivable_mode_of_payment 			= response[i].receivable_mode_of_payment;
							var receivable_date_of_payment 			= response[i].receivable_date_of_payment;
							var receivable_reference				= response[i].receivable_reference;
							var receivable_payment_amount 			= response[i].receivable_payment_amount;
							
							$('#receivable_payment_table_body_data tr:last').after("<tr>"+
							"<td class='date_of_payment_td' align='center'><input type='date' class='form-control date_of_payment' id='date_of_payment' name='date_of_payment' value='"+receivable_date_of_payment+"'></td>"+
							"<td class='bank_td' align='center'><input type='text' class='form-control mode_of_payment' id='mode_of_payment' name=' mode_of_payment' list='mode_of_payment_list' autocomplete='off' value='"+receivable_mode_of_payment+"'></td>"+
							"<td class='reference_no_td' align='center'><input type='text' class='form-control reference_no' id='reference_no' name='reference_no' value='"+receivable_reference+"'></td>"+
							"<td class='payment_amount_td' align='center'><input type='number' class='form-control payment_amount' id='payment_amount' name='payment_amount' value='"+receivable_payment_amount+"'></td>"+
							"<td><div onclick='deletePaymentRow(this)' data-id='"+id+"' id='payment_item'><div align='center' class='action_table_menu_Product' style='margin-top: 6px;'><a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deletePayment'></a></div></div></td></tr>");

						}			
				  }else{
							/*No Result Found or Error*/
							$("#receivable_payment_table_body_data tr").remove();
							$('<tr style="display: none;"><td>HIDDEN</td></tr>').appendTo('#receivable_payment_table_body_data');
				  }
				},
				error: function(error) {
				 console.log(error);	 
				}
			   });
	  }  	  

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
					//document.getElementById("or_number").value = response[0].or_number;
					document.getElementById("payment_term").value = response[0].payment_term;
					document.getElementById("receivable_description").textContent = response[0].receivable_description;					
					//document.getElementById("ar_reference").value = response[0].ar_reference;					
					document.getElementById("start_date").value = response[0].billing_period_start;
					document.getElementById("end_date").value = response[0].billing_period_end;
					document.getElementById("less_per_liter").value = response[0].less_per_liter;
					document.getElementById("company_header").value = response[0].company_header;					
					document.getElementById("withholding_tax_percentage").value = response[0].receivable_withholding_tax_percentage;
					document.getElementById("net_value_percentage").value = response[0].receivable_net_value_percentage;
					document.getElementById("vat_value_percentage").value = response[0].receivable_vat_value_percentage;				
					
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
			//let or_number 				= $("input[name=or_number]").val();		
			//let ar_reference 				= $("input[name=ar_reference]").val();				
			let payment_term 			= $("input[name=payment_term]").val();
			let receivable_description 	= $("#receivable_description").val();
			let receivable_status 		= $("#receivable_status").val();
			
			let start_date 			= $("#start_date").val();
			let end_date 			= $("#end_date").val();
			let less_per_liter 		= $("#less_per_liter").val();
			/*Added May 6, 2023*/
			let company_header 		= $("#company_header").val();
			/*Added June 4, 2023*/
			let withholding_tax_percentage 	= $("input[name=withholding_tax_percentage]").val() / 100;
			let net_value_percentage 		= $("input[name=net_value_percentage]").val();
			let vat_value_percentage 		= $("input[name=vat_value_percentage]").val() / 100;
			 
			 $.ajax({
				url: "/update_receivables_post",
				type:"POST",
				data:{
				  ReceivableID:ReceivableID,
				  billing_date:billing_date,
				  //or_number:or_number,
				  //ar_reference:ar_reference,
				  payment_term:payment_term,
				  receivable_description:receivable_description,
				  receivable_status:receivable_status,
				  less_per_liter:less_per_liter,
				  start_date:start_date,
				  end_date:end_date,
				  company_header:company_header,				  
				  withholding_tax_percentage:withholding_tax_percentage,				  
				  net_value_percentage:net_value_percentage,			  
				  vat_value_percentage:vat_value_percentage,
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
				beforeSend:function()
				{
					$('#update_loading_data').show();
				},
				complete: function(){
					$('#update_loading_data').hide();
				},
				error: function(error) {
				 console.log(error);	
				 
				//$('#or_numberError').text(error.responseJSON.errors.product_price);
				//document.getElementById('or_numberError').className = "invalid-feedback";	
				
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
	
	<!--Select Receivable For Update-->	
	$('body').on('click','#editReceivablesFromSalesOrder',function(){
			
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
					
					document.getElementById("SO-update-receivables").value = ReceivableID;
					
					/*Set Details*/
							
					document.getElementById("client_name_receivables_SO").innerHTML = response[0].client_name;
					document.getElementById("client_address_receivables_SO").innerHTML = response[0].client_address;
					document.getElementById("control_no_receivables_SO").innerHTML = response[0].control_number;	
					//document.getElementById("billing_receivables_SO").innerHTML = response[0].billing_date;					
					document.getElementById("client_tin_receivables_SO").innerHTML = response[0].client_tin;
					document.getElementById("amount_receivables_SO").innerHTML = response[0].receivable_amount;	
					
					document.getElementById("receivable_billing_date_SO").value = response[0].billing_date;
					//document.getElementById("receivable_or_number_SO").value = response[0].or_number;
					document.getElementById("receivable_payment_term_SO").value = response[0].payment_term;
					document.getElementById("receivable_description_SO").textContent = response[0].receivable_description;					
					//document.getElementById("receivable_ar_reference_SO").value = response[0].ar_reference;					
					//document.getElementById("start_date").value = response[0].billing_period_start;
					//document.getElementById("end_date").value = response[0].billing_period_end;
					//document.getElementById("less_per_liter").value = response[0].less_per_liter;
					//document.getElementById("company_header").value = response[0].company_header;					
					//document.getElementById("withholding_tax_percentage").value = response[0].receivable_withholding_tax_percentage;
					//document.getElementById("net_value_percentage").value = response[0].receivable_net_value_percentage;
					//document.getElementById("vat_value_percentage").value = response[0].receivable_vat_value_percentage;				
					
					$('#UpdateReceivablesFromSalesOrderModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });  
	  
	<!--Save New receivables->
	$("#SO-update-receivables").click(function(event){
			
			event.preventDefault();
			$('#receivable_description_SO_Error').text('');

			document.getElementById('ReceivableformEditFromSalesOrder').className = "g-3 needs-validation was-validated";
			
			let ReceivableID 			= document.getElementById("SO-update-receivables").value;

			let billing_date			= $("input[name=receivable_billing_date_SO]").val();	
			//let or_number 				= $("input[name=receivable_or_number_SO]").val();	
			//let ar_reference 			= $("input[name=receivable_ar_reference_SO]").val();	
			let payment_term 			= $("input[name=receivable_payment_term_SO]").val();
			let receivable_description 	= $("#receivable_description_SO").val();
			
			$.ajax({
				url: "/update_receivables_from_sale_order_post",
				type:"POST",
				data:{
				  ReceivableID:ReceivableID,
				  //or_number:or_number,
				  //ar_reference:ar_reference,
				  billing_date:billing_date,
				  payment_term:payment_term,
				  receivable_description:receivable_description,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					/*Reset Warnings*/
					//$('#receivable_or_numberError').text('');
					$('#receivable_payment_termError').text('');
					$('#receivable_descriptionError').text('');
					
					/*Clear Form*/
					//$('#receivable_or_number').val("");
					$('#receivable_payment_term').val("");
					$('#receivable_description').val("");
					/*Close Form*/
					$('#SalesOrderDeliveredModal').modal('toggle');
					
					var table = $("#getReceivablesList").DataTable();
				    table.ajax.reload(null, false);
					
					$('#switch_notice_on').show();
					$('#sw_on').html(response.success);
					setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
					
					
					var query = {
						receivable_id:response.receivable_id,
						_token: "{{ csrf_token() }}"
					}
					
					/*Reload Details or link for PDF*/
					
					//download_billing_report_pdf(response.receivable_id);
					var url = "{{URL::to('generate_receivable_pdf')}}?" + $.param(query)
					window.open(url);
					
				  }
				},
				beforeSend:function()
				{
					/*Disable Submit Button*/
					document.getElementById("SO-update-receivables").disabled = true;
					/*Show Status*/
					$('#update_loading_data_SO').show();
				},
				complete: function(){
					
					/*Enable Submit Button*/
					document.getElementById("SO-update-receivables").disabled = false;
					/*Hide Status*/
					$('#update_loading_data_SO').hide();
					
				},
				error: function(error) {
				 console.log(error);	
				
				$('#receivable_descriptionError').text(error.responseJSON.errors.receivable_description);
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
					//document.getElementById("confirm_delete_or_no").value = response[0].or_number;	
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

	function receivable_print(id){
		  
			event.preventDefault();
			var to_print = document.getElementById("receivable_print_"+id).value;
				
				if(to_print=='PrintStatement'){
					print_soa(id);
				}
				else if(to_print=='PrintBilling'){
					print_billing(id);
				}
				else if(to_print=='PrintSalesOrder'){
					print_sales_order(id);
				}
				else if(to_print=='PrintReceivables'){
					print_receivable(id);
				}
				else{
					/*No Action*/
				}
				
	}	    
	  
	/*Re-print*/
	function print_billing(id){
	  
			event.preventDefault();
			
			let ReceivableID = id;
			
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
					let company_header 	= response[0].company_header;
					
					let withholding_tax_percentage 	= response[0].receivable_withholding_tax_percentage/100;
					let net_value_percentage 		= response[0].receivable_net_value_percentage;
					let vat_value_percentage 		= response[0].receivable_vat_value_percentage/100;
					
					/*Open Billing Print Page*/				
					var query_billing = {
						receivable_id:ReceivableID,
						client_idx:client_idx,
						start_date:start_date,
						end_date:end_date,
						company_header:company_header,
						less_per_liter:less_per_liter,
						withholding_tax_percentage:withholding_tax_percentage,
						net_value_percentage:net_value_percentage,
						vat_value_percentage:vat_value_percentage,
						_token: "{{ csrf_token() }}"
					}

					var url_billing = "{{URL::to('generate_receivable_covered_bill_pdf')}}?" + $.param(query_billing)
					window.open(url_billing);

				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });		
	  
	  }
	  
	function print_receivable(id){
	  
			event.preventDefault();
			
			let ReceivableID = id;
			
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
	  
	}
	  
	function print_soa(id){
	  
			event.preventDefault();
			
			let ReceivableID = id;
			
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

					/*Open Receivable Print Page*/
					
					var query_receivable = {
								receivable_id:ReceivableID,
								_token: "{{ csrf_token() }}"
							}

					var url_receivable = "{{URL::to('generate_receivable_soa_pdf')}}?" + $.param(query_receivable)
					window.open(url_receivable);
			
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });
	  }
	  
	function print_sales_order(id){
	  
			event.preventDefault();
			
			let ReceivableID = id;
			
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
					
					//document.getElementById("update-receivables").value = ReceivableID;
			

					/*Open Receivable Print Page*/
					let sales_order_idx 		= response[0].sales_order_idx;
					var query_receivable = {
								sales_order_id:sales_order_idx,
								_token: "{{ csrf_token() }}"
							}

					var url_receivable = "{{URL::to('generate_sales_order_pdf')}}?" + $.param(query_receivable)
					window.open(url_receivable);
			
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });
	  }	  
  </script>
	