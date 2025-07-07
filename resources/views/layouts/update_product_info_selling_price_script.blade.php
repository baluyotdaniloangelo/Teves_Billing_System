<script type="text/javascript">
	/*Client Pricing*/
	loadProductSellingPrice();
	
	function loadProductSellingPrice() {
		
			  $.ajax({
				url: "{{ route('ProductSellingPriceList') }}",
				type:"POST",
				data:{
				  productID:{{ $productID }},
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
									
				  console.log(response);
				  if(response!='') {
					  
							ProductPricePerClientListTable.clear().draw();
							ProductPricePerClientListTable.rows.add(response.data).draw();	
			
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
	let ProductPricePerClientListTable = $('#ProductPricePerClientListTable').DataTable({
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
					{data: 'client_name'},
					{data: 'product_price', orderable: false, render: $.fn.dataTable.render.number( ',', '.', 2, '' ), className: "text-right" },
					{data: 'action', name: 'action', orderable: false, searchable: false, className: "text-center"},
			],
			columnDefs: [
					//{ className: 'text-center', targets: [0, 3] },
			]
		});
		
	function reset_form_selling_price(){
		
		document.getElementById("ProductClientPrice").reset();
		$('#ProductPricePerClientModal_title').html("Add Selling Price");
		document.getElementById("save-selling-price").value = 0;
	}		
	
	<!--Save/Update->
	$("#save-selling-price").click(function(event){
			
			event.preventDefault();
			
					/*Reset Warnings*/
					$('#Client_priceError').text('');
					$('#client_idx_sellingError').text('');
					$('#branch_idx_ClientError').text('');

			document.getElementById('ProductClientPrice').className = "g-3 needs-validation was-validated";

			let selling_price_id 	= document.getElementById("save-selling-price").value;
			
			let client_idx 			= $("#client_name_selling option[value='" + $('#client_idx_selling').val() + "']").attr('data-id');
			let client_name 		= $("input[name=client_name_selling]").val();
			let branch_idx			= $("#branch_idx_selling").val();
			let selling_price		= $("#selling_price").val();
			
			  $.ajax({
				url: "/create_product_selling_price_post",
				type:"POST",
				data:{
				  product_idx:{{ $productID }},
				  selling_price_id:selling_price_id,
				  branch_idx:branch_idx,
				  client_idx:client_idx,
				  selling_price:selling_price,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					$('#selling_priceError').text('');
					$('#client_idx_sellingError').text('');
					$('#branch_idx_sellingError').text('');
					
					$('#switch_notice_on').show();
					$('#sw_on').html(response.success);
					setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
					
					$('#ProductPricePerClientModal').modal('toggle');	
					reset_form_selling_price();
					loadProductSellingPrice();
				  
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				 if(error.responseJSON.errors.product_idx=="The product idx has already been taken."){
							 
				  $('#client_idx_sellingError').html("<b>Price was already encoded to "+client_name+"");
				  document.getElementById('client_idx_sellingError').className = "invalid-feedback";
				  document.getElementById('client_idx_selling').className = "form-control is-invalid";
				  $('#client_idx_selling').val("");
				  
				}else{
					
				  $('#client_idx_sellingError').text(error.responseJSON.errors.client_idx);
				  document.getElementById('client_idx_sellingError').className = "invalid-feedback";
				  
				}
				
				  $('#selling_priceError').text(error.responseJSON.errors.selling_price);
				  document.getElementById('selling_priceError').className = "invalid-feedback";			
				
				$('#switch_notice_off').show();
				$('#sw_off').html("Invalid Input");
				setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);			  	  
				  
				}
			   });		
			   
	});
	
	<!--Select For Update-->
	$('body').on('click','#ProductSellingPrice_Edit',function(){
			
			event.preventDefault();
			let selling_price_id = $(this).data('id');
			
			  $.ajax({
				url: "/product_selling_price_info",
				type:"POST",
				data:{
				  selling_price_id:selling_price_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					$('#ProductPricePerClientModal_title').html("Edit Selling Price");
					
					document.getElementById("save-selling-price").value = selling_price_id;
					
					/*Set Details*/
					document.getElementById("client_idx_selling").value = response[0].client_name;
					document.getElementById("selling_price").value = response[0].product_price;
					document.getElementById("branch_idx_selling").value = response[0].branch_idx;
										
					$('#ProductPricePerClientModal').modal('toggle');		
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });	

	<!--Deletion Confirmation-->
	$('body').on('click','#ProductSellingPrice_delete',function(){
			
			event.preventDefault();
			let selling_price_id = $(this).data('id');
			
			  $.ajax({
				url: "/product_selling_price_info",
				type:"POST",
				data:{
				  selling_price_id:selling_price_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
											
					/*Set Details*/
					$('#branch_client_selling_price_delete').text(response[0].branch_code);					
					$('#client_name_selling_price_delete').text(response[0].client_name);			
					$('#client_selling_price_delete').text(response[0].product_price);
					
					document.getElementById("deleteSellingPriceInfoConfirmed").value = selling_price_id;
					
					$('#ProductSellingPriceInfoDeleteModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });		
	  });	 
	  
	<!--Confirmed For Deletion-->
	$('body').on('click','#deleteSellingPriceInfoConfirmed',function(){
			
			event.preventDefault();

			let selling_price_id = document.getElementById("deleteSellingPriceInfoConfirmed").value;
			
			  $.ajax({
				url: "/delete_selling_price_info_confirmed",
				type:"POST",
				data:{
				   selling_price_id:selling_price_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					$('#switch_notice_off').show();
					$('#sw_off').html("Client's Product Price Deleted!");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);	
					
					loadProductSellingPrice();
					
				  }
				},
				error: function(error) {
				 console.log(error);
				}
			   });		
	  });
</script>
	