   <!-- Page level plugins -->
   <script src="{{asset('Datatables/2.0.8/js/dataTables.js')}}"></script>
   <script src="{{asset('Datatables/responsive/3.0.2/js/dataTables.responsive.js')}}"></script>
   <script src="{{asset('Datatables/responsive/3.0.2/js/responsive.dataTables.js')}}"></script>
   
   <script type="text/javascript">

	ProductInfo();
	<!--Select Product For Update-->
	function ProductInfo() {
			
			//event.preventDefault();
			let productID = {{ $productID }};
			
			  $.ajax({
				url: "/product_info",
				type:"POST",
				data:{
				  productID:productID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("update-product").value = productID;
					
					/*Set Details*/
					document.getElementById("update_product_name").value = response.product_name;
					document.getElementById("update_product_price").value = response.product_price;
					document.getElementById("update_product_unit_measurement").value = response.product_unit_measurement;
										
					$('#UpdateProductModal').modal('toggle');		
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	}

	$("#update-product").click(function(event){			
			event.preventDefault();
			
					/*Reset Warnings*/
					$('#update_product_nameError').text('');
					$('#update_product_priceError').text('');

			document.getElementById('ProductformEdit').className = "g-3 needs-validation was-validated";
			
			let productID 					= document.getElementById("update-product").value;
			let product_name 				= $("input[name=update_product_name]").val();
			let product_price 				= $("input[name=update_product_price]").val();
			let product_unit_measurement 	= $("#update_product_unit_measurement").val();
			
			  $.ajax({
				url: "/update_product_post",
				type:"POST",
				data:{
				  productID:productID,
				  product_name:product_name,
				  product_price:product_price,
				  product_unit_measurement:product_unit_measurement ,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					$('#update_product_nameError').text('');	
					$('#update_product_priceError').text('');
					
					$('#switch_notice_on').show();
					$('#sw_on').html(response.success);
					setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
					
					/*Close form*/
					$('#UpdateProductModal').modal('toggle');
					
					/*Refresh Table*/
					var table = $("#getProductList").DataTable();
				    table.ajax.reload(null, false);
				  
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				if(error.responseJSON.errors.product_name=="The product name has already been taken."){
							  
					$('#product_nameError').html("<b>"+ product_name +"</b> has already been taken.");
					document.getElementById('product_nameError').className = "invalid-feedback";
					document.getElementById('product_name').className = "form-control is-invalid";
					$('#update_product_name').val("");
				  
				}else{
					$('#product_nameError').text(error.responseJSON.errors.product_name);
					document.getElementById('product_nameError').className = "invalid-feedback";
				}
				
					$('#product_priceError').text(error.responseJSON.errors.product_price);
					document.getElementById('product_priceError').className = "invalid-feedback";			
				
				$('#switch_notice_off').show();
				$('#sw_off').html("Invalid Input");
				setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);		  	  
				  
				}
			   });
		
	  });
	  
	<!--Pay Receivables-->
	$('body').on('click','#LoadProductPricePerBranch',function(){
			
			event.preventDefault();
			let productID = $(this).data('id');
			
			  $.ajax({
				url: "/product_info",
				type:"POST",
				data:{
				  productID:productID,
				  
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("save-product-price-per-branch").value = productID;
					
					/*Set Details*/		
					document.getElementById("branch_product_name").innerHTML = response.product_name;
					document.getElementById("branch_product_price").innerHTML = response.product_price;
					document.getElementById("branch_product_unit").innerHTML = response.product_unit_measurement;	
					
					/*Load Receivable Payment Table*/
					loadPricingPerBranch(productID);
					
					$('#ProductBranchPriceModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });	  

	loadPricingListPerBranch();
	
	function loadPricingListPerBranch() {
		
			  $.ajax({
				url: "{{ route('ProductPricingPerBranch') }}",
				type:"POST",
				data:{
				  productID:{{ $productID }},
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
						
				  console.log(response);
				  if(response!='') {
					  
					ProductPricingPerBranchListTable.clear().draw();
					ProductPricingPerBranchListTable.rows.add(response.data).draw();	
		
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
	let ProductPricingPerBranchListTable = $('#product_price_branch').DataTable({
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
					{data: 'branch_code'},
					{data: 'buying_price', orderable: false, render: $.fn.dataTable.render.number( ',', '.', 2, '' ), className: "text-right" },
					{data: 'profit_margin', orderable: false, render: $.fn.dataTable.render.number( ',', '.', 2, '' ), className: "text-right" },
					{data: 'profit_margin_type'},
					{data: 'profit_margin_in_peso', orderable: false, render: $.fn.dataTable.render.number( ',', '.', 2, '' ), className: "text-right" },
					{data: 'branch_price', orderable: false, render: $.fn.dataTable.render.number( ',', '.', 2, '' ), className: "text-right" },
					{data: 'action', name: 'action', orderable: false, searchable: false, className: "text-center"},
			],
			columnDefs: [
					//{ className: 'text-center', targets: [0, 3] },
			]
		});
	
	/*Product Prices History of Changes*/
	$('body').on('click','#ViewProductPriceperBranchHistory',function(){
			
			event.preventDefault();
			let branch_price_id = $(this).data('id');
			
			  $.ajax({
				url: "{{ route('ProductPricingPerBranchHistory') }}",
				type:"POST",
				data:{
				  branch_price_id:branch_price_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					//document.getElementById("save-product-price-per-branch").value = productID;
					
					/*Set Details*/		
					//document.getElementById("branch_product_name").innerHTML = response.product_name;
					//document.getElementById("branch_product_price").innerHTML = response.product_price;
					//document.getElementById("branch_product_unit").innerHTML = response.product_unit_measurement;	
					
					
					
					ProductPricingPerBranchHistoryListTable.clear().draw();
					ProductPricingPerBranchHistoryListTable.rows.add(response.data).draw();	
					
					/*Set Details of Product information to Modal of History*/
					loadPricingPerBranch_history(branch_price_id);
					
					$('#ProductHistoryChangesModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });	  

	<!--Select Tank For Update-->
	function loadPricingPerBranch_history(branch_price_id) {
			
			//event.preventDefault();
			//let branch_price_id = $(this).data('id');
			
			  $.ajax({
				url: "/product_price_per_branch_info",
				type:"POST",
				data:{
				  branch_price_id:branch_price_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
							
					/*Set Details*/
					$('#product_name_history_view').text(response[0].product_name);
					$('#branch_price_history_view').text(response[0].branch_price);
					$('#branch_name_history_view').text(response[0].branch_name);
					$('#branch_code_history_view').text(response[0].branch_code);
					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
			   
	}



	<!--Load Table-->
	let ProductPricingPerBranchHistoryListTable = $('#product_price_branch_history').DataTable({
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
			info: true,
			data: [],
			//scrollCollapse: true,
			//scrollY: '500px',
			//scrollx: false,
			columns: [
					{data: 'DT_RowIndex', name: 'DT_RowIndex' , orderable: false, searchable: false},
					{data: 'date_of_changes', orderable: true, className: "text-left"},
					{data: 'time_of_changes', orderable: true, className: "text-left"},
					{data: 'branch_code', className: "text-center"},
					{data: 'buying_price', orderable: false, render: $.fn.dataTable.render.number( ',', '.', 2, '' ), className: "text-left" },
					{data: 'profit_margin', orderable: false, render: $.fn.dataTable.render.number( ',', '.', 2, '' ), className: "text-left" },
					{data: 'profit_margin_type', className: "text-left"},
					{data: 'profit_margin_in_peso', orderable: false, render: $.fn.dataTable.render.number( ',', '.', 2, '' ), className: "text-left" },
					{data: 'branch_price', orderable: false, render: $.fn.dataTable.render.number( ',', '.', 2, '' ), className: "text-right" }
					
			],
			columnDefs: [
					//{ className: 'text-center', targets: [0, 3] },
			]
		});	

	<!--Select Tank For Update-->
	$('body').on('click','#EditProductPriceperBranch',function(){
			
			event.preventDefault();
			let branch_price_id = $(this).data('id');
			
			  $.ajax({
				url: "/product_price_per_branch_info",
				type:"POST",
				data:{
				  branch_price_id:branch_price_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("update-branch-price").value = branch_price_id;
							
					/*Set Details*/
					$('#branch_name_price').text(response[0].branch_name);
					$('#branch_code_price').text(response[0].branch_code);
					$('#branch_price').text(response[0].branch_price);
					//alert(response[0].branch_price);
					
					document.getElementById("update_buying_price").value = response[0].buying_price;
					document.getElementById("update_profit_margin").value = response[0].profit_margin;
					document.getElementById("update_profit_margin_type").value = response[0].profit_margin_type;
										
					$('#UpdateProductPricePerBranchModal').modal('toggle');		
					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });

	$("#update-branch-price").click(function(event){			
			event.preventDefault();
			
					/*Reset Warnings*/
					$('#update_buying_priceError').text('');
					$('#update_profit_marginError').text('');
					

			document.getElementById('UpdateProductPricePerBranch').className = "g-3 needs-validation was-validated";
			
			let productID 			= {{ $productID }};
			
			let branch_price_id 	= document.getElementById("update-branch-price").value;
			
			let buying_price 		= $("input[name=update_buying_price]").val();
			let profit_margin 		= $("input[name=update_profit_margin]").val();
			let profit_margin_type 	= $("#update_profit_margin_type").val();
			
			  $.ajax({
				url: "/update_product_price_per_branch_post",
				type:"POST",
				data:{
				  productID:productID,
				  branch_price_id:branch_price_id,
				  buying_price:buying_price,
				  profit_margin:profit_margin,
				  profit_margin_type:profit_margin_type,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					$('#update_buying_priceError').text('');	
					$('#update_profit_marginError').text('');
					
					$('#switch_notice_on').show();
					$('#sw_on').html(response.success);
					setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
					
					/*Close form*/
					$('#UpdateProductPricePerBranchModal').modal('toggle');
					
					/*Refresh Table*/
					loadPricingListPerBranch();
				  
				  }
				},
				error: function(error) {
				 console.log(error);	
				
				
					$('#update_buying_priceError').text(error.responseJSON.errors.buying_price);
					document.getElementById('update_buying_priceError').className = "invalid-feedback";
					
					$('#update_profit_marginError').text(error.responseJSON.errors.profit_margin);
					document.getElementById('update_profit_marginError').className = "invalid-feedback";			
				
					$('#switch_notice_off').show();
					$('#sw_off').html("Invalid Input");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);		  	  
				  
				}
			   });
		
	  });


	/*Tank*/
	LoadProductTank();
	
	function LoadProductTank() {
		
			  $.ajax({
				url: "{{ route('ProductTank') }}",
				type:"POST",
				data:{
				  productID:{{ $productID }},
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
									
				  console.log(response);
				  if(response!='') {
					  
							ProductTankListTable.clear().draw();
							ProductTankListTable.rows.add(response.data).draw();	
			
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
	let ProductTankListTable = $('#ProductTankListTable').DataTable({
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
					{data: 'tank_name'},
					{data: 'tank_capacity', orderable: false, render: $.fn.dataTable.render.number( ',', '.', 2, '' ), className: "text-right" },
					{data: 'product_unit_measurement'},
					{data: 'action', name: 'action', orderable: false, searchable: false, className: "text-center"},
			],
			columnDefs: [
					//{ className: 'text-center', targets: [0, 3] },
			]
		});
		
	<!--Save New Tank->
	$("#save-tank").click(function(event){
			
			event.preventDefault();
			
					/*Reset Warnings*/
					$('#tank_nameError').text('');
					$('#tank_capacityError').text('');

			document.getElementById('AddProductTank').className = "g-3 needs-validation was-validated";

			let tank_name 		= $("input[name=tank_name]").val();
			let tank_capacity 	= $("input[name=tank_capacity]").val();
			let branch_idx 		= $("#branch_idx").val();
			
			  $.ajax({
				url: "/create_tank_post",
				type:"POST",
				data:{
				  product_idx:{{ $productID }},
				  tank_name:tank_name,
				  tank_capacity:tank_capacity,
				  branch_idx:branch_idx,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					$('#tank_nameError').text('');
					$('#tank_capacityError').text('');
					
					$('#switch_notice_on').show();
					$('#sw_on').html(response.success);
					setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
					
					document.getElementById("AddProductTank").reset();
										
					$('#AddProductTankModal').modal('toggle');	
					
					LoadProductTank();
				  
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				 if(error.responseJSON.errors.tank_name=="The tank name has already been taken."){
							  
				  $('#tank_nameError').html("<b>"+ tank_name +"</b> has already been taken.");
				  document.getElementById('tank_nameError').className = "invalid-feedback";
				  document.getElementById('tank_name').className = "form-control is-invalid";
				  $('#tank_name').val("");
				  
				}else{
					
				  $('#tank_nameError').text(error.responseJSON.errors.tank_name);
				  document.getElementById('tank_nameError').className = "invalid-feedback";
				  
				}
				
				  $('#tank_capacityError').text(error.responseJSON.errors.tank_capacity);
				  document.getElementById('tank_capacityError').className = "invalid-feedback";			
				
				$('#switch_notice_off').show();
				$('#sw_off').html("Invalid Input");
				setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);			  	  
				  
				}
			   });		
			   
	});
	
	<!--Select Tank For Update-->
	$('body').on('click','#ProductTank_Edit',function(){
			
			event.preventDefault();
			let tankID = $(this).data('id');
			
			  $.ajax({
				url: "/product_tank_info",
				type:"POST",
				data:{
				  tankID:tankID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("update-tank").value = tankID;
					
					/*Set Details*/
					document.getElementById("update_tank_name").value = response.tank_name;
					document.getElementById("update_tank_capacity").value = response.tank_capacity;
					document.getElementById("update_branch_idx").value = response.branch_idx;
										
					$('#UpdateProductTankModal').modal('toggle');		
					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });	

	<!--Save New Tank->
	$("#update-tank").click(function(event){
			
			event.preventDefault();
			
					/*Reset Warnings*/
					$('#tank_nameError').text('');
					$('#tank_capacityError').text('');

			document.getElementById('AddProductTank').className = "g-3 needs-validation was-validated";

			let tank_id 	= document.getElementById("update-tank").value;
			
			let tank_name 		= $("input[name=update_tank_name]").val();
			let tank_capacity 	= $("input[name=update_tank_capacity]").val();
			let branch_idx 		= $("#update_branch_idx").val();
			
			  $.ajax({
				url: "/update_tank_post",
				type:"POST",
				data:{
				  tank_id:tank_id,
				  product_idx:{{ $productID }},
				  tank_name:tank_name,
				  tank_capacity:tank_capacity,
				  branch_idx:branch_idx,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					$('#update_tank_nameError').text('');
					$('#update_tank_capacityError').text('');
					
					$('#switch_notice_on').show();
					$('#sw_on').html(response.success);
					setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
					
					document.getElementById("AddProductTank").reset();
										
					$('#UpdateProductTankModal').modal('toggle');	
					
					LoadProductTank();
				  
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				 if(error.responseJSON.errors.tank_name=="The tank name has already been taken."){
							  
				  $('#update_tank_nameError').html("<b>"+ tank_name +"</b> has already been taken.");
				  document.getElementById('update_tank_nameError').className = "invalid-feedback";
				  document.getElementById('update_tank_name').className = "form-control is-invalid";
				  $('#update_update_gateway_sn').val("");
				  
				}else{
				  $('#update_tank_nameError').text(error.responseJSON.errors.tank_name);
				  document.getElementById('update_tank_nameError').className = "invalid-feedback";
				}
				
				  $('#update_tank_capacityError').text(error.responseJSON.errors.tank_capacity);
				  document.getElementById('update_tank_capacityError').className = "invalid-feedback";			
				
				$('#switch_notice_off').show();
				$('#sw_off').html("Invalid Input");
				setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);			  	  
				  
				}
			   });		
	  });
	

	<!--Product Deletion Confirmation-->
	$('body').on('click','#ProductTank_delete',function(){
			
			event.preventDefault();
			let tankID = $(this).data('id');
			
			  $.ajax({
				url: "/product_tank_info",
				type:"POST",
				data:{
				  tankID:tankID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
											
					/*Set Details*/
					$('#delete_tank_name').text(response.tank_name);					
					$('#delete_tank_capacity').text(response.tank_capacity);
					
					document.getElementById("deleteTankInfoConfirmed").value = tankID;
					
					$('#TankInfoDeleteModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });		
	  });	
	  
	  
	<!--Product Confirmed For Deletion-->
	$('body').on('click','#deleteTankInfoConfirmed',function(){
			
			event.preventDefault();

			let tankID = document.getElementById("deleteTankInfoConfirmed").value;
			
			  $.ajax({
				url: "/delete_product_tank_confirmed",
				type:"POST",
				data:{
				   tankID:tankID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					$('#switch_notice_off').show();
					$('#sw_off').html("Tank Information Deleted!");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);	
					
					LoadProductTank();
					
				  }
				},
				error: function(error) {
				 console.log(error);
				}
			   });		
	  });
	  
	
  </script>
	