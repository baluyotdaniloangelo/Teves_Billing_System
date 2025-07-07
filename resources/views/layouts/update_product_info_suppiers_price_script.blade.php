  <script type="text/javascript">
	/*Tank*/
	loadPricingListPerSeller();
	
	function loadPricingListPerSeller() {
		
			  $.ajax({
				url: "{{ route('ProductPricePerSellerList') }}",
				type:"POST",
				data:{
				  productID:{{ $productID }},
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
									
				  console.log(response);
				  if(response!='') {
					  
							ProductPricePerSellerListTable.clear().draw();
							ProductPricePerSellerListTable.rows.add(response.data).draw();	
			
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
	let ProductPricePerSellerListTable = $('#ProductPricePerSellerListTable').DataTable({
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
					{data: 'supplier_name'},
					{data: 'product_price', orderable: false, render: $.fn.dataTable.render.number( ',', '.', 2, '' ), className: "text-right" },
					{data: 'action', name: 'action', orderable: false, searchable: false, className: "text-center"},
			],
			columnDefs: [
					//{ className: 'text-center', targets: [0, 3] },
			]
		});
		
	function reset_form_sellers_price(){
		
		document.getElementById("ProductSellerPrice").reset();
		$('#ProductPricePerSellerModal_title').html("Add Seller Price");
		document.getElementById("save-seller-price").value = 0;
	}		
	
	<!--Save/Update->
	$("#save-seller-price").click(function(event){
			
			event.preventDefault();
			
					/*Reset Warnings*/
					$('#seller_priceError').text('');
					$('#supplier_idxError').text('');
					$('#branch_idx_sellerError').text('');

			document.getElementById('ProductSellerPrice').className = "g-3 needs-validation was-validated";

			let seller_price_id 	= document.getElementById("save-seller-price").value;
			
			let supplier_idx 		= $("#supplier_name option[value='" + $('#supplier_idx').val() + "']").attr('data-id');
			let supplier_name 		= $("input[name=supplier_name]").val();
			let branch_idx			= $("#branch_idx_seller").val();
			let seller_price		= $("#seller_price").val();
			
			  $.ajax({
				url: "/create_product_seller_price_post",
				type:"POST",
				data:{
				  product_idx:{{ $productID }},
				  seller_price_id:seller_price_id,
				  branch_idx:branch_idx,
				  supplier_idx:supplier_idx,
				  seller_price:seller_price,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					$('#seller_priceError').text('');
					$('#supplier_idxError').text('');
					$('#branch_idx_sellerError').text('');
					
					$('#switch_notice_on').show();
					$('#sw_on').html(response.success);
					setTimeout(function() { $('#switch_notice_on').fadeOut('fast'); },1000);
					
					$('#ProductPricePerSellerModal').modal('toggle');	
					reset_form_sellers_price();
					loadPricingListPerSeller();
				  
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				 if(error.responseJSON.errors.product_idx=="The product idx has already been taken."){
							  
				  $('#supplier_idxError').html("<b>Price was already encoded to "+supplier_name+"");
				  document.getElementById('supplier_idxError').className = "invalid-feedback";
				  document.getElementById('supplier_idx').className = "form-control is-invalid";
				  $('#supplier_idx').val("");
				  
				}else{
					
				  $('#supplier_idxError').text(error.responseJSON.errors.supplier_idx);
				  document.getElementById('supplier_idxError').className = "invalid-feedback";
				  
				}
				
				  $('#seller_priceError').text(error.responseJSON.errors.seller_price);
				  document.getElementById('seller_priceError').className = "invalid-feedback";			
				
				$('#switch_notice_off').show();
				$('#sw_off').html("Invalid Input");
				setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);			  	  
				  
				}
			   });		
			   
	});
	
	<!--Select For Update-->
	$('body').on('click','#ProductSellerPrice_Edit',function(){
			
			event.preventDefault();
			let seller_price_id = $(this).data('id');
			
			  $.ajax({
				url: "/product_price_per_seller_info",
				type:"POST",
				data:{
				  seller_price_id:seller_price_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					$('#ProductPricePerSellerModal_title').html("Edit Seller Price");
					
					document.getElementById("save-seller-price").value = seller_price_id;
					
					/*Set Details*/
					document.getElementById("supplier_idx").value = response[0].supplier_name;
					document.getElementById("seller_price").value = response[0].product_price;
					document.getElementById("branch_idx_seller").value = response[0].branch_idx;
										
					$('#ProductPricePerSellerModal').modal('toggle');		
					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  });	

	<!--Deletion Confirmation-->
	$('body').on('click','#ProductSellerPrice_delete',function(){
			
			event.preventDefault();
			let seller_price_id = $(this).data('id');
			
			  $.ajax({
				url: "/product_price_per_seller_info",
				type:"POST",
				data:{
				  seller_price_id:seller_price_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
											
					/*Set Details*/
					$('#branch_suppliers_price_delete').text(response[0].branch_code);					
					$('#suppliers_name_suppliers_price_delete').text(response[0].supplier_name);			
					$('#sellers_price_suppliers_price_delete').text(response[0].product_price);
					
					document.getElementById("deleteSuppliersProductPriceInfoConfirmed").value = seller_price_id;
					
					$('#SuppliersProductPriceInfoDeleteModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });		
	  });	
	  
	  
	<!--Confirmed For Deletion-->
	$('body').on('click','#deleteSuppliersProductPriceInfoConfirmed',function(){
			
			event.preventDefault();

			let seller_price_id = document.getElementById("deleteSuppliersProductPriceInfoConfirmed").value;
			
			  $.ajax({
				url: "/delete_product_price_per_seller_info_confirmed",
				type:"POST",
				data:{
				   seller_price_id:seller_price_id,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					$('#switch_notice_off').show();
					$('#sw_off').html("Seller's Product Price Deleted!");
					setTimeout(function() { $('#switch_notice_off').fadeOut('slow'); },1000);	
					
					loadPricingListPerSeller();
					
				  }
				},
				error: function(error) {
				 console.log(error);
				}
			   });		
	  });
	  
	
  </script>
	