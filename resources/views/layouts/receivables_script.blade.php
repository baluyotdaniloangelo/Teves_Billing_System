   <script type="text/javascript">

	<!--Load Table-->
	$(function () {

		var ProductListTable = $('#getReceivablesList').DataTable({
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
					{data: 'receivable_amount'},
					{data: 'action', name: 'action', orderable: false, searchable: false},
			],
			columnDefs: [
					{ className: 'text-center', targets: [0, 1, 2, 3, 4] },
			]
		});
				/*
				$('<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">'+
				'<button type="button" class="btn btn-success new_item bi bi-plus-circle" data-bs-toggle="modal" data-bs-target="#CreateProductModal"></button>'+
				'</div>').appendTo('#product_option');
				*/
	});
	
	<!--Save New product->
	$("#save-product").click(function(event){
			
			event.preventDefault();
			
					/*Reset Warnings*/
					$('#product_nameError').text('');
					$('#product_priceError').text('');

			document.getElementById('ProductformNew').className = "g-3 needs-validation was-validated";

			let product_name 				= $("input[name=product_name]").val();
			let product_price 				= $("input[name=product_price]").val();
			let product_unit_measurement 	= $("#product_unit_measurement").val();
			
			  $.ajax({
				url: "/create_product_post",
				type:"POST",
				data:{
				  product_name:product_name,
				  product_price:product_price,
				  product_unit_measurement:product_unit_measurement,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					$('#product_nameError').text('');
					$('#product_priceError').text('');
					
					$('#switch_notice_on').show();
					$('#sw_on').html(response.success);
					setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
					
					document.getElementById("ProductformNew").reset();
					
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
				  $('#update_gateway_sn').val("");
				  
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

	<!--Select Product For Update-->
	$('body').on('click','#editReceivable',function(){
			
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
	  });


	$("#update-receivable").click(function(event){			
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
	  
	<!--Product Deletion Confirmation-->
	$('body').on('click','#deleteReceivable',function(){
			
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
					
					document.getElementById("deleteProductConfirmed").value = productID;
					
					/*Set Details*/
					$('#confirm_delete_product_name').text(response.product_name);
					$('#confirm_delete_product_price').text(response.product_price);
					
					$('#ProductDeleteModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });		
	  });

	  <!--Product Confirmed For Deletion-->
	  $('body').on('click','#deleteReceivableConfirmed',function(){
			
			event.preventDefault();

			let productID = document.getElementById("deleteProductConfirmed").value;
			
			  $.ajax({
				url: "/delete_product_confirmed",
				type:"POST",
				data:{
				  productID:productID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					$('#switch_notice_off').show();
					$('#sw_off').html("Product Deleted");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);	
					
					/*
					If you are using server side datatable, then you can use ajax.reload() 
					function to reload the datatable and pass the true or false as a parameter for refresh paging.
					*/
					
					var table = $("#getProductList").DataTable();
				    table.ajax.reload(null, false);
					
				  }
				},
				error: function(error) {
				 console.log(error);
				}
			   });		
	  });
  </script>
	