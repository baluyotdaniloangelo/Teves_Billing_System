<script type="text/javascript">
let productComponentOptions = null;

function fetchProductComponentOptions() {
    if (productComponentOptions) return $.Deferred().resolve(productComponentOptions).promise();
    return $.getJSON("{{ route('product_component_options') }}").then(function (options) {
        productComponentOptions = options || [];
        return productComponentOptions;
    });
}

function productComponentLabel(product) {
    const capacity = product.product_capacity
        ? ` — ${product.product_capacity} ${product.product_capacity_unit || ''}`.trim()
        : '';
    return `${product.product_name || 'Unnamed product'}${capacity} (${product.product_unit_measurement || 'unit'})`;
}

function renderProductComponentRows(parentProductId, components) {
    const rows = document.getElementById('productComponentsRows');
    const empty = document.getElementById('productComponentsEmpty');
    const wrap = document.getElementById('productComponentsTableWrap');
    const error = document.getElementById('productComponentsError');
    if (!rows || !empty || !wrap) return;
    rows.replaceChildren();
    if (error) { error.textContent = ''; error.classList.add('d-none'); }

    const optionsRequest = fetchProductComponentOptions();
    optionsRequest.done(function (options) {
        if (String(getSelectedProductId()) !== String(parentProductId)) return;
        (components || []).forEach(function (component) {
            const row = document.createElement('tr');
            const productCell = document.createElement('td');
            const select = document.createElement('select');
            select.className = 'form-select form-select-sm product-component-product';
            select.required = true;
            select.innerHTML = '<option value="">Select component product</option>';
            options.filter(product => Number(product.product_id) !== Number(parentProductId)).forEach(function (product) {
                const option = document.createElement('option');
                option.value = product.product_id;
                option.textContent = productComponentLabel(product);
                select.appendChild(option);
            });
            select.value = component.product_idx || '';
            productCell.appendChild(select);

            const quantityCell = document.createElement('td');
            const quantity = document.createElement('input');
            quantity.type = 'number';
            quantity.className = 'form-control form-control-sm product-component-quantity';
            quantity.min = '0.001';
            quantity.step = '0.001';
            quantity.required = true;
            quantity.value = component.quantity || '';
            quantity.setAttribute('aria-label', 'Quantity per set');
            quantityCell.appendChild(quantity);

            const actionCell = document.createElement('td');
            actionCell.className = 'text-end';
            const remove = document.createElement('button');
            remove.type = 'button';
            remove.className = 'btn btn-outline-danger btn-sm remove-product-component';
            remove.innerHTML = '<i class="bi bi-trash" aria-hidden="true"></i><span class="visually-hidden">Remove component</span>';
            actionCell.appendChild(remove);
            row.append(productCell, quantityCell, actionCell);
            rows.appendChild(row);
        });
        const hasRows = rows.children.length > 0;
        empty.classList.toggle('d-none', hasRows);
        wrap.classList.toggle('d-none', !hasRows);
    }).fail(function () {
        empty.textContent = 'Unable to load component products. Please refresh the page and try again.';
    });
    return optionsRequest;
}

$('#addProductComponent').on('click', function () {
    $('#productComponentsSuccess').addClass('d-none');
    const rows = document.getElementById('productComponentsRows');
    if (!rows) return;
    const components = Array.from(rows.querySelectorAll('tr')).map(function (row) {
        return {
            product_idx: row.querySelector('.product-component-product').value,
            quantity: row.querySelector('.product-component-quantity').value
        };
    });
    components.push({ product_idx: '', quantity: '' });
    renderProductComponentRows(getSelectedProductId(), components);
});

$('#productComponentsRows').on('click', '.remove-product-component', function () {
    $('#productComponentsSuccess').addClass('d-none');
    $(this).closest('tr').remove();
    const hasRows = document.getElementById('productComponentsRows').children.length > 0;
    $('#productComponentsEmpty').toggleClass('d-none', hasRows);
    $('#productComponentsTableWrap').toggleClass('d-none', !hasRows);
});

function hideProductUpdateSuccessNotice() {
    const notice = document.getElementById('productUpdateSuccessNotice');
    if (notice) notice.classList.add('d-none');
}

function ProductInfo(productId, options) {
    const productID = productId || getSelectedProductId();
    if (!productID) return;
    const isNewlyCreated = Boolean(options && options.newlyCreated);
    hideProductUpdateSuccessNotice();

    window.selectedProductId = productID;
    $('#saveProductComponents, #addProductComponent').prop('disabled', true);
    $('#productComponentsRows').empty();
    $('#productComponentsTableWrap, #productComponentsSuccess, #productComponentsError').addClass('d-none');
    $('#productComponentsEmpty').removeClass('d-none').text('Loading components...');
    $.ajax({
        url: "/product_info",
        type: "POST",
        data: { productID: productID, _token: "{{ csrf_token() }}" },
        success: function (response) {
            if (!response || String(getSelectedProductId()) !== String(productID)) return;

            document.getElementById("update_product_name").value = response.product_name || '';
            document.getElementById("update_product_price").value = response.product_price || '';
            document.getElementById("update_product_unit_measurement").value = response.product_unit_measurement || '';
            document.getElementById("update_product_capacity").value = response.product_capacity ?? '';
            document.getElementById("update_product_capacity_unit").value = response.product_capacity_unit || '';
            const componentOptionsRequest = renderProductComponentRows(productID, response.components || []);
            const updatePhotoInput = document.getElementById('update_product_photo');
            updatePhotoInput.value = '';
            updatePhotoInput.dataset.currentPhotoPath = response.product_photo_path || '';
            $('#remove_product_photo').prop('checked', false);
            window.setProductPhotoPreview(
                updatePhotoInput,
                document.getElementById('update_product_photo_preview'),
                document.getElementById('update_product_photo_placeholder'),
                response.product_photo_path || null
            );
            syncProductCapacityFields('update_');
            document.getElementById("update_category_idx").value = response.category_idx || '';

            const isFuelProduct = updateFuelProductTabs();
            const $saveProductButton = $('#update-product');
            $saveProductButton.prop('disabled', true);
            let mainOptionsRequest = $.Deferred().resolve().promise();
            if (typeof window.loadProductMainOptions === 'function') {
                mainOptionsRequest = window.loadProductMainOptions(
                    $('#update_product_main_idx'),
                    response.product_main_idx || null,
                    response.category_idx || null
                );
            }
            mainOptionsRequest.always(function () {
                if (String(getSelectedProductId()) === String(productID)) {
                    $saveProductButton.prop('disabled', false);
                }
            });
            componentOptionsRequest.done(function () {
                if (String(getSelectedProductId()) === String(productID)) {
                    $('#saveProductComponents, #addProductComponent').prop('disabled', false);
                    $('#productComponentsEmpty').text('No components added. Add components if this product is an assembled set.');
                }
            }).fail(function () {
                if (String(getSelectedProductId()) === String(productID)) {
                    $('#productComponentsEmpty').text('Unable to load component products. Please refresh the page and try again.');
                }
            });
            if (isFuelProduct) {
                if (typeof LoadProductTank === 'function') LoadProductTank();
                if (typeof LoadProductPump === 'function') LoadProductPump();
            }
            $('#productInformationModalTitle').text(response.product_name + ' — Product Information');
            $('#productInformationHeading').text(isNewlyCreated
                ? 'Product created. Continue setup with branch, seller, and selling prices; configure tank and pump for fuel.'
                : 'Manage product details for ' + response.product_name + '.');
        },
        error: function (xhr) {
            const message = xhr.responseJSON && xhr.responseJSON.message
                ? xhr.responseJSON.message
                : 'Unable to load product information. Please try again.';
            showProductValidationErrorModal(message);
        }
    });
}

$('#ProductformEdit').on('submit', function (event) {
    event.preventDefault();

    const editForm = this;
    const updateButton = document.getElementById('update-product');
    const productID = getSelectedProductId();
    syncProductCapacityFields('update_');
    editForm.classList.add('was-validated');

    if (!editForm.checkValidity()) {
        editForm.reportValidity();
        return;
    }

    if (!productID) {
        showProductValidationErrorModal('Select a product before saving changes.');
        return;
    }

    window.ProductFormValidation.clearErrors(editForm);
    const productName = $('#update_product_name').val();
    const originalButtonHtml = updateButton.innerHTML;
    updateButton.disabled = true;
    updateButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Updating...';

    const formData = new FormData();
    formData.append('productID', productID);
    formData.append('category_idx', $('#update_category_idx').val());
    formData.append('product_main_idx', $('#update_product_main_idx').val() || '');
    formData.append('product_name', productName);
    formData.append('product_price', $('#update_product_price').val());
    formData.append('product_unit_measurement', $('#update_product_unit_measurement').val());
    formData.append('product_capacity', $('#update_product_capacity').val());
    formData.append('product_capacity_unit', $('#update_product_capacity_unit').val());
    const selectedProductPhoto = document.getElementById('update_product_photo').files[0];
    if (selectedProductPhoto) formData.append('product_photo', selectedProductPhoto);
    formData.append('remove_product_photo', $('#remove_product_photo').is(':checked') ? '1' : '0');
    formData.append('_token', "{{ csrf_token() }}");

    $.ajax({
        url: "/update_product_post",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            window.ProductFormValidation.reset(editForm);
            const photoInput = document.getElementById('update_product_photo');
            photoInput.value = '';
            photoInput.dataset.currentPhotoPath = response.product_photo_path || '';
            $('#remove_product_photo').prop('checked', false);
            window.setProductPhotoPreview(
                photoInput,
                document.getElementById('update_product_photo_preview'),
                document.getElementById('update_product_photo_placeholder'),
                response.product_photo_path || null
            );
            $('#productInformationModalTitle').text(productName + ' — Product Information');
            $('#productInformationHeading').text('Manage product details for ' + productName + '.');
            if (window.ProductListTable) window.ProductListTable.ajax.reload(null, false);
            if (window.UnassignedProductTable) window.UnassignedProductTable.ajax.reload(null, false);
            const variantsTable = window.productMainVariantsDataTable;
            if (variantsTable && variantsTable.ajax && typeof variantsTable.ajax.reload === 'function') {
                variantsTable.ajax.reload(null, false);
            }
            $('#productUpdateSuccessMessage').text(response.success || 'Product information updated successfully.');
            $('#productUpdateSuccessNotice').removeClass('d-none');
        },
        error: function (xhr) {
            const response = xhr.responseJSON || {};
            const errors = response.errors || {};
            const shownErrors = window.ProductFormValidation.showErrors(editForm, errors, {
                product_name: 'update_product_name',
                product_price: 'update_product_price',
                category_idx: 'update_category_idx',
                product_main_idx: 'update_product_main_idx',
                product_unit_measurement: 'update_product_unit_measurement',
                product_capacity: 'update_product_capacity',
                product_capacity_unit: 'update_product_capacity_unit',
                product_photo: 'update_product_photo'
            });
            if (!shownErrors) {
                showProductValidationErrorModal(response.message || 'Unable to update product information. Please review the form and try again.');
            }
        },
        complete: function () {
            updateButton.disabled = false;
            updateButton.innerHTML = originalButtonHtml;
        }
    });
});

$('#productComponentsForm').on('submit', function (event) {
    event.preventDefault();
    const productID = getSelectedProductId();
    const $error = $('#productComponentsError');
    const $button = $('#saveProductComponents');
    $('#productComponentsSuccess').addClass('d-none');
    $error.addClass('d-none').text('');
    if (!productID) {
        $error.text('Select a product before saving components.').removeClass('d-none');
        return;
    }
    if (!this.checkValidity()) {
        this.reportValidity();
        return;
    }
    const components = Array.from(document.querySelectorAll('#productComponentsRows tr')).map(function (row) {
        return { product_idx: row.querySelector('.product-component-product').value, quantity: row.querySelector('.product-component-quantity').value };
    });
    if (new Set(components.map(item => item.product_idx)).size !== components.length) {
        $error.text('Each component product can only be listed once.').removeClass('d-none');
        return;
    }
    $button.prop('disabled', true);
    $('#addProductComponent, #productComponentsRows input, #productComponentsRows select, #productComponentsRows button').prop('disabled', true);
    $.ajax({
        url: "{{ route('product_components_save') }}",
        type: 'POST',
        data: { productID: productID, components: JSON.stringify(components), _token: "{{ csrf_token() }}" },
        success: function () {
            if (String(getSelectedProductId()) === String(productID)) $('#productComponentsSuccess').removeClass('d-none');
        },
        error: function (xhr) {
            if (String(getSelectedProductId()) !== String(productID)) return;
            const response = xhr.responseJSON || {};
            const errors = response.errors || {};
            const firstError = Object.values(errors).flat()[0];
            $error.text(firstError || response.message || 'Unable to save components. Please try again.').removeClass('d-none');
        },
        complete: function () {
            if (String(getSelectedProductId()) === String(productID)) {
                $button.prop('disabled', false);
                $('#addProductComponent, #productComponentsRows input, #productComponentsRows select, #productComponentsRows button').prop('disabled', false);
            }
        }
    });
});

$('#productComponentsForm').on('input change', 'input, select', function () {
    $('#productComponentsSuccess, #productComponentsError').addClass('d-none');
});

$('#ProductformEdit').on('input change', 'input, select, textarea', hideProductUpdateSuccessNotice);
$('#dismissProductUpdateSuccessNotice').on('click', hideProductUpdateSuccessNotice);

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

	function loadPricingListPerBranch() {
			if (!getSelectedProductId()) return;
		
			  $.ajax({
				url: "{{ route('ProductPricingPerBranch') }}",
				type:"POST",
				data:{
				  productID:getSelectedProductId(),
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
	
	function showBranchPriceModal(modalSelector) {
		showProductInformationChildModal(modalSelector);
	}

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
					
					showBranchPriceModal('#ProductHistoryChangesModal');					
				  
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
										
					showBranchPriceModal('#UpdateProductPricePerBranchModal');		
					
				  
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
			
			let productID 			= getSelectedProductId();
			
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
					$('#UpdateProductPricePerBranchModal').modal('hide');
					
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
	</script>
	
