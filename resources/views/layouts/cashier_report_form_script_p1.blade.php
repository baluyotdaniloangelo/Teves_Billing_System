   <script type="text/javascript">


	const product_idx_OnChange = document.getElementById("product_idx");

	product_idx_OnChange.addEventListener("change", function(event) {
		const value = event.target.value; // gets the input value
		TotalAmount();
		LoadProductTank('fuelsales');
		LoadProductPump('fuelsales');
	});

	const update_product_idx_OnChange = document.getElementById("update_product_idx");

	update_product_idx_OnChange.addEventListener("change", function(event) {
		const value = event.target.value; // gets the input value
		UpdateTotalAmount();
		LoadProductTank('fuelsales');
		LoadProductPump('fuelsales_edit');
	});
	
	function TotalAmount(){
		
		let product_price 			= $('#product_name option[value="' + $('#product_idx').val() + '"]').attr('data-price');
		let product_manual_price 	= $("#product_manual_price").val();	
		var beginning_reading 		= $("input[name=beginning_reading]").val();
		var closing_reading 		= $("input[name=closing_reading]").val();
		var calibration 			= $("input[name=calibration]").val();	
		let order_quantity 			= (closing_reading - beginning_reading) - calibration;
		
		if(order_quantity!=0 || order_quantity!=''){
			if(product_manual_price!='' && product_manual_price!=0){
				var total_amount = product_manual_price * order_quantity;
				$('#TotalAmount').html(total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}));
			}else{
				var total_amount = product_price * order_quantity;
				$('#TotalAmount').html(total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}));
			}
		}		
	}
	
	function UpdateTotalAmount(){
		
		let product_price 			= $('#update_product_name option[value="' + $('#update_product_idx').val() + '"]').attr('data-price');
		let product_manual_price 	= $("#update_product_manual_price").val();		
		var beginning_reading 		= $("input[name=update_beginning_reading]").val();
		var closing_reading 		= $("input[name=update_closing_reading]").val();
		var calibration 			= $("input[name=update_calibration]").val();		
		let order_quantity 			= (closing_reading - beginning_reading) - calibration;
		
		if(order_quantity!=0 || order_quantity!=''){
			if(product_manual_price!='' && product_manual_price!=0){
				var total_amount = product_manual_price * order_quantity;
				$('#UpdateTotalAmount').html(total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}));
			}else{
				var total_amount = product_price * order_quantity;
				$('#UpdateTotalAmount').html(total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}));
			}
		}
		
	}		
	
	/*Call Function for Product Cahier's Report*/
	$("#save-CRPH1").click(function(event){
		
			event.preventDefault();
			
			$('#product_idxError').text('');
			$('#beginning_readingError').text('');
			$('#closing_readingError').text('');
			
			let CashiersReportId 		= {{ $CashiersReportId }};			
		
			var product_idx 			= $('#product_name option[value="' + $('#product_idx').val() + '"]').attr('data-id');
			
			var tank_idx	 			= $('#product_tank_list option[value="' + $('#product_tank_idx_fuel_sales').val() + '"]').attr('data-id');
			var pump_idx 				= $('#product_pump_list option[value="' + $('#product_pump_idx_fuel_sales').val() + '"]').attr('data-id');
			
			var beginning_reading 		= $("input[name=beginning_reading]").val();
			var closing_reading 		= $("input[name=closing_reading]").val();
			var calibration 			= $("input[name=calibration]").val();
			var product_manual_price 	= $("input[name=product_manual_price]").val();
			let teves_branch 			= $("#teves_branch").val();

			document.getElementById('CRPH1_form').className = "g-3 needs-validation was-validated";
			
				/*Delete the Selected Item*/			
				  $.ajax({
					url: "{{ route('SAVE_CHR_PH1') }}",
					type:"POST",
					data:{
					  CHPH1_ID:0,
					  CashiersReportId:CashiersReportId,
					  branch_idx:teves_branch,
					  product_idx:product_idx,
					  tank_idx:tank_idx,
					  pump_idx:pump_idx,
					  beginning_reading:beginning_reading,
					  closing_reading:closing_reading,
					  calibration:calibration,
					  product_manual_price:product_manual_price, 
					  _token: "{{ csrf_token() }}"
					},
					success:function(response){
					  console.log(response);
					  if(response) {
						  
						$('#switch_notice_on').show();
						$('#sw_on').html(response.success);
						setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
						LoadCashiersReportPH1();
						$('#product_idxError').text('');					
						$('#beginning_readingError').text('');		
						$('#closing_readingError').text('');
						
						/*Clear Form*/
						document.getElementById("product_idx").value = '';
						document.getElementById("beginning_reading").value = '';
						document.getElementById("closing_reading").value = '';
						document.getElementById("calibration").value = '';
						document.getElementById("product_manual_price").value = '';
						
						LoadCashiersReportSummary();
						
					  }
					},
					error: function(error) {
					 console.log(error);
						
							$('#product_idxError').text(error.responseJSON.errors.product_idx);
							document.getElementById('product_idxError').className = "invalid-feedback";
							
							
							if(error.responseJSON.errors.beginning_reading=="The beginning reading has already been taken."){
							  
								$('#beginning_readingError').text("The Beginning Reading already exist for the Selected Product.");
								document.getElementById('beginning_readingError').className = "invalid-feedback";	
								
								$('#beginning_reading').val("");
								  
							}else {
								
								$('#beginning_readingError').text(error.responseJSON.errors.beginning_reading);
								document.getElementById('beginning_readingError').className = "invalid-feedback";		
								
							}
				
							if(error.responseJSON.errors.closing_reading=="The closing reading has already been taken."){
							  
								$('#closing_readingError').text("The Closing Reading already exist for the Selected Product.");
								document.getElementById('closing_readingError').className = "invalid-feedback";	
								
								$('#closing_reading').val("");
								  
							}else {
								
								$('#closing_readingError').text(error.responseJSON.errors.closing_reading);
								document.getElementById('closing_readingError').className = "invalid-feedback";		
								
							}
							
							
							
					}
				   });		
		 });

	function LoadCashiersReportPH1_aaa() {	
	
		$("#table_product_body_data tr").remove();
		$('<tr style="display: none;"><td>HIDDEN</td></tr>').appendTo('#table_product_body_data');
		let CashiersReportId 			= {{ $CashiersReportId }};	
			  $.ajax({
				url: "{{ route('GetCashiersProductP1') }}",
				type:"POST",
				data:{
				  CashiersReportId:CashiersReportId,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){						
				  console.log(response);
				  if(response!='') {			  
						var len = response.length;
						for(var i=0; i<len; i++){
						
							var cashiers_report_p1_id = response[i].cashiers_report_p1_id;						
							var product_idx = response[i].product_idx;						
							var product_price = response[i].product_price.toLocaleString("en-PH", {maximumFractionDigits: 2});
							var product_name = response[i].product_name;
							var tank_name = response[i].tank_name;
							var pump_name = response[i].pump_name;
							var beginning_reading = response[i].beginning_reading;
							var order_quantity = response[i].order_quantity.toLocaleString("en-PH", {maximumFractionDigits: 2});
							var closing_reading = response[i].closing_reading;
							var calibration = response[i].calibration;
							var order_total_amount = response[i].order_total_amount.toLocaleString("en-PH", {maximumFractionDigits: 2});
							
							$('#table_product_body_data tr:last').after("<tr>"+
							"<td class='product_td' align='center'>"+product_name+"</td>"+
							"<td class='product_td' align='center'>"+tank_name+"</td>"+
							"<td class='product_td' align='center'>"+pump_name+"</td>"+
							"<td class='beginning_reading_td' align='center'>"+beginning_reading+"</td>"+
							"<td class='closing_reading_td' align='center'>"+closing_reading+"</td>"+
							"<td class='calibration_td' align='center'>"+calibration+"</td>"+
							"<td class='calibration_td' align='center'>"+order_quantity+"</td>"+
							"<td class='manual_price_td' align='center'>"+product_price+"</td>"+
							"<td class='manual_price_td' align='center'>"+order_total_amount+"</td>"+
							"<td><div align='center' class='action_table_menu_Product' style='margin-top: 6px;'><a href='#' class='btn-danger btn-circle btn-sm bi-pencil-fill btn_icon_table btn_icon_table_edit' id='CHPH1_Edit' data-id='"+cashiers_report_p1_id+"'></a> <a href='#' class='btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete' id='deleteCashiersProductP1'  data-id='"+cashiers_report_p1_id+"'></a></div></td>"+
							"</tr>");				
					}			
				  }else{
							/*No Result Found or Error*/	
				  }
				},
				error: function(error) {
				 console.log(error);	 
				}
			   });
	  }  	  

	/*Load Cashiers Report Phase 1*/
	LoadCashiersReportPH1();	
	function LoadCashiersReportPH1() {
		
		let CashiersReportId 		= {{ $CashiersReportId }};	
		
			  $.ajax({
				url: "{{ route('GetCashiersProductP1') }}",
				type:"POST",
				data:{
				  CashiersReportId:CashiersReportId,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
									
				  console.log(response);
				  if(response!='') {
					  
							LoadCashiersReportPH1_table.clear().draw();
							LoadCashiersReportPH1_table.rows.add(response.data).draw();	
			
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
	const  LoadCashiersReportPH1_table = $('#FuelSales').DataTable({
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
			responsive: true,
			paging: true,
			searching: false,
			info: false,
			data: [],
			//scrollCollapse: true,
			//scrollY: '500px',
			//scrollx: false,
			columns: [
					{data: 'DT_RowIndex', name: 'DT_RowIndex' , orderable: false, searchable: false},
					{data: 'product_name'},
					{data: 'tank_name'},
					{data: 'pump_name'},
					{data: 'beginning_reading', orderable: false, render: $.fn.dataTable.render.number( ',', '.', 2, '' ), className: "text-right" },
					{data: 'closing_reading', orderable: false, render: $.fn.dataTable.render.number( ',', '.', 2, '' ), className: "text-right" },
					{data: 'calibration', orderable: false, render: $.fn.dataTable.render.number( ',', '.', 2, '' ), className: "text-right" },
					{data: 'order_quantity', orderable: false, render: $.fn.dataTable.render.number( ',', '.', 2, '' ), className: "text-right" },
					{data: 'product_price', orderable: false, render: $.fn.dataTable.render.number( ',', '.', 2, '' ), className: "text-right" },
					{data: 'order_total_amount', orderable: false, render: $.fn.dataTable.render.number( ',', '.', 2, '' ), className: "text-right" },
					{data: 'action', name: 'action', orderable: false, searchable: false, className: "text-center"},
					{data: 'created_at',render: function (data, type, row) {
						if (!data) return "";
						let d = new Date(data);
						return d.toLocaleDateString('en-US', {
						  year: 'numeric',
						  month: 'short',
						  day: '2-digit',
						  hour: '2-digit',
						  minute: '2-digit'
						});
					  }},
					{data: 'updated_at',render: function (data, type, row) {
						if (!data) return "";
						let d = new Date(data);
						return d.toLocaleDateString('en-US', {
						  year: 'numeric',
						  month: 'short',
						  day: '2-digit',
						  hour: '2-digit',
						  minute: '2-digit'
						});
					  }},
			],
			columnDefs: [
					//{ className: 'text-center', targets: [0, 3] },
			]
		});

	<!--Select Bill For Update-->
	$('body').on('click','#CHPH1_Edit',function(){			
			event.preventDefault();
			let CHPH1_ID = $(this).data('id');			
			$.ajax({
				url: "{{ route('CRP1_info') }}",
				type:"POST",
				data:{
				  CHPH1_ID:CHPH1_ID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {				
					document.getElementById("update-CRPH1").value = CHPH1_ID;				
					/*Set Details*/		
						/*
						'teves_product_tank_table.tank_name',
						'teves_product_pump_table.pump_name',
						*/						
					document.getElementById("update_product_idx").value 					= response[0].product_name;
					document.getElementById("update_product_tank_idx_fuel_sales").value 	= response[0].tank_name;
					document.getElementById("update_product_pump_idx_fuel_sales").value 	= response[0].pump_name;
					document.getElementById("update_beginning_reading").value 				= response[0].beginning_reading;
					document.getElementById('update_closing_reading').value 				= response[0].closing_reading;
					document.getElementById("update_calibration").value 					= response[0].calibration;
					document.getElementById("update_product_manual_price").value 			= response[0].product_price;
					
					var total_amount = response[0].order_total_amount;
					$('#UpdateTotalAmount').html(total_amount.toLocaleString("en-PH", {minimumFractionDigits: 2}));				
					$('#Update_CRPH1_Modal').modal('toggle');		
					
					LoadProductTank('fuelsales_edit');
					LoadProductPump('fuelsales_edit');
						
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });

	/*Call Function for Product Cahier's Report*/
	$("#update-CRPH1").click(function(event){
		
			event.preventDefault();
			
			$('#update_product_idxError').text('');
			$('#update_beginning_readingError').text('');
			$('#update_closing_readingError').text('');
			
			let CashiersReportId 		= {{ $CashiersReportId }};			
			let CHPH1_ID 				= document.getElementById("update-CRPH1").value;
			var product_idx 			= $('#update_product_name option[value="' + $('#update_product_idx').val() + '"]').attr('data-id');
			
			var tank_idx	 			= $('#product_tank_list option[value="' + $('#update_product_tank_idx_fuel_sales').val() + '"]').attr('data-id');
			var pump_idx 				= $('#product_pump_list option[value="' + $('#update_product_pump_idx_fuel_sales').val() + '"]').attr('data-id');
			
			var beginning_reading 		= $("input[name=update_beginning_reading]").val();
			var closing_reading 		= $("input[name=update_closing_reading]").val();
			var calibration 			= $("input[name=update_calibration]").val();
			var product_manual_price 	= $("input[name=update_product_manual_price]").val();
			let teves_branch 			= $("#teves_branch").val();

			document.getElementById('CRPH1_form_edit').className = "g-3 needs-validation was-validated";
			
				/*Delete the Selected Item*/			
				  $.ajax({
					url: "{{ route('SAVE_CHR_PH1') }}",
					type:"POST",
					data:{ 
					  CHPH1_ID:CHPH1_ID,
					  CashiersReportId:CashiersReportId,
					  product_idx:product_idx,
					  tank_idx:tank_idx,
					  pump_idx:pump_idx,
					  branch_idx:teves_branch,
					  beginning_reading:beginning_reading,
					  closing_reading:closing_reading,
					  calibration:calibration,
					  product_manual_price:product_manual_price, 
					  _token: "{{ csrf_token() }}"
					},
					success:function(response){
					  console.log(response);
					  if(response) {
						  
						$('#switch_notice_on').show();
						$('#sw_on').html(response.success);
						setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
						LoadCashiersReportPH1();
						$('#update_product_idxError').text('');					
						$('#update_beginning_readingError').text('');		
						$('#update_closing_readingError').text('');
						
						/*Clear Form*/
						$('#Update_CRPH1_Modal').modal('toggle');
						LoadCashiersReportSummary();
					  }
					},
					error: function(error) {
					 console.log(error);
					 
							$('#update_product_idxError').text(error.responseJSON.errors.product_idx);
							document.getElementById('update_product_idxError').className = "invalid-feedback";
							
							if(error.responseJSON.errors.beginning_reading=="The beginning reading has already been taken."){
							  
								$('#update_beginning_readingError').text("The Beginning Reading already exist for the Selected Product.");
								document.getElementById('update_beginning_readingError').className = "invalid-feedback";	
								
								$('#update_beginning_reading').val("");
								  
							}else {
								
								$('#update_beginning_readingError').text(error.responseJSON.errors.beginning_reading);
								document.getElementById('update_beginning_readingError').className = "invalid-feedback";		
								
							}
				
							if(error.responseJSON.errors.closing_reading=="The closing reading has already been taken."){
							  
								$('#update_closing_readingError').text("The Closing Reading already exist for the Selected Product.");
								document.getElementById('update_closing_readingError').className = "invalid-feedback";	
								
								$('#update_closing_reading').val("");
								  
							}else {
								
								$('#update_closing_readingError').text(error.responseJSON.errors.closing_reading);
								document.getElementById('update_closing_readingError').className = "invalid-feedback";		
								
							}
							
					}
				   });		
	});

	<!--CRPH1 Deletion Confirmation-->	
	$('body').on('click','#deleteCashiersProductP1',function(){
			
			event.preventDefault();
			let CHPH1_ID = $(this).data('id');
			
			  $.ajax({
				url: "{{ route('CRP1_info') }}",
				type:"POST",
				data:{
				  CHPH1_ID:CHPH1_ID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("deleteCRPH1Confirmed").value = CHPH1_ID;
					
					/*Set Details*/
					$('#bill_delete_order_date').text(response[0].order_date);

					$('#CRPH1_delete_product_idx').text(response[0].product_name);
					$('#CRPH1_delete_beginning_reading').text(response[0].beginning_reading);
					$('#CRPH1_delete_closing_reading').text(response[0].closing_reading);
					$('#CRPH1_delete_calibration').text(response[0].calibration);
					$('#CRPH1_delete_product_order_quantity').text(response[0].order_quantity);
					$('#CRPH1_delete_product_manual_price').text(response[0].product_price);
					$('#CRPH1_delete_order_total_amount').text(response[0].order_total_amount);
					
					$('#CRPH1DeleteModal').modal('toggle');					
					LoadCashiersReportSummary();
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });		
	  });

	$('body').on('click','#deleteCRPH1Confirmed',function(){
			
		let CHPH1_ID = document.getElementById("deleteCRPH1Confirmed").value;
		
		if(CHPH1_ID!=0){
			/*Delete the Selected Item*/	
			  $.ajax({
				url: "{{ route('DeleteCashiersProductP1') }}",
				type:"POST",
				data:{
				  CHPH1_ID:CHPH1_ID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					$('#switch_notice_off').show();
					$('#sw_off').html("Deleted");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);	
					LoadCashiersReportPH1();
				  }
				},
				error: function(error) {
				 console.log(error);
				}
			   });		
		}	
	});


	</script>
