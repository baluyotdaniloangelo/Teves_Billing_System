@extends('layouts.layout')  
@section('content')  

<main id="main" class="main">	

    <section class="section">
	
		<div class="card">
		
            <div class="card-body">
			
				<h5 class="card-title" align="center">{{ $title }}</h5>
				<div class="row mb-2">
					<div class="col-sm-6">
					
					</div>
					<div class="col-sm-6">
						<div class="d-flex justify-content-end" id="">
							<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -35px; position: absolute;">
								<a class="btn btn-secondary new_item bi bi-chevron-double-left form_button_icon" href="{{ route('product') }}" title="Back">  
								  <span title="Back to Sales Order List">Back</span>
								</a>
				
							</div>					
						</div>
						
					</div>
				</div>
				
				<hr>
				
				<div class="row mb-2">
				
					<div class="col-sm-4">
						
						<form class="g-3 needs-validation" id="ProductformEdit">
							<div class="row mb-2">
						  <label for="update_product_name" class="col-sm-4 col-form-label">Product Name</label>
						  <div class="col-sm-8">
							<input type="text" class="form-control" name="update_product_name" id="update_product_name" value="" required>
							<span class="valid-feedback" id="update_product_nameError" title="Required"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="update_product_price" class="col-sm-4 col-form-label">Default Price</label>
						  <div class="col-sm-8">
							<input type="number" class="form-control " name="update_product_price" id="update_product_price" value="" required step=".01">
							<span class="valid-feedback" id="update_product_priceError"></span>
						  </div>
						</div>					
								
						<div class="row mb-2">
						  <label for="update_product_unit_measurement" class="col-sm-4 col-form-label">Unit of Measurement</label>
						  <div class="col-sm-8">
							<select class="form-control form-select " name="update_product_unit_measurement" id="update_product_unit_measurement" required>
									<option value="L">Liter | L</option>
									<option value="mL">Milliliter | mL</option>
									<option value="g">Gram | g</option>
									<option value="kg">Kilo | kg</option>
									<option value="PC">Pieces | PC</option>
									<option value="oz">Ounce | oz</option>
									<option value="Pail">Pail</option>
									<option value="Drum">Drum</option>
									<option value="Case">Case</option>
							</select>
							<span class="valid-feedback" id="update_product_unit_measurementError"></span>
						  </div>
						</div>
						
						<div class="row mb-3">
						
							<div class="col-sm-6" align='right'>
								<div id="loading_data_update_so" style="display:none;">
									<div class="spinner-border text-success" role="status">
										<span class="visually-hidden">Loading...</span>
									</div>
								</div>
							</div>
							
							<div class="col-sm-6" align='right'>
								<button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="update-product"> Update</button>
							</div>
							
						</div>
						
						</form>
						
					</div>
					
					<div class="col-sm-8">
					
					    <ul class="nav nav-tabs" id="myTab" role="tablist">
							<!-- nav-link active -->
							<li class="nav-item" role="presentation">
								<button class="nav-link <?php if($tab=='branchprice') { echo 'active'; } ?>" id="branchprice-tab" data-bs-toggle="tab" data-bs-target="#branchprice" type="button" role="tab" aria-controls="branchprice" aria-selected="true" onclick="loadPricingListPerBranch()" title='Product Prices per branch'>Price per Branch</button>
							</li>
									
							<li class="nav-item" role="presentation">
								<button class="nav-link <?php if($tab=='tank') { echo 'active'; } ?>" id="tank-tab" data-bs-toggle="tab" data-bs-target="#tank" type="button" role="tab" aria-controls="tank" aria-selected="false" tabindex="-1" onclick="LoadProductTank()" title='Tank Information'>Tank</button>
							</li>
									
						</ul>
					
						<div class="tab-content pt-2" id="myTabContent">
						
							<div class="tab-pane fade  <?php if($tab=='branchprice') { echo ' show active'; } ?>"" id="branchprice" role="tabpanel" aria-labelledby="branchprice-tab">
									
									<table class="table table-striped" id="">
									<thead>
									<tr class='report'>
										<th style="text-align:center !important;">Item #</th>
										<th style="text-align:center !important;">Branch</th>
										<th style="text-align:center !important;">Price</th>
									</tr>
									</thead>
										<tbody id="branch_pricing_table_body_data">
												<tr style="display: none;">
													<td>HIDDEN</td>
												</tr>
										</tbody>
									</table>
									<div class="row mb-3">
									<div class="col-sm-6" align='right'>
								<div id="loading_data_update_so" style="display:none;">
									<div class="spinner-border text-success" role="status">
										<span class="visually-hidden">Loading...</span>
									</div>
								</div>
							</div>
							
							<div class="col-sm-6" align='right'>
									<button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="save-product-price-per-branch" value=""> Save</button>
							</div>	
							
							</div>

							</div>
					
						</div>

						<div class="tab-content pt-2" id="myTabContent">
						
							<div class="tab-pane fade  <?php if($tab=='tank') { echo ' show active'; } ?>"" id="tank" role="tabpanel" aria-labelledby="tank-tab">
									
									<div class="d-flex justify-content-end" id="">
									<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">
										<button type="button" class="btn btn-success new_item bi bi-plus-circle form_button_icon" data-bs-toggle="modal" data-bs-target="#AddProductTankModal" id="AddProductTankBTN"></button>
									</div>											
									</div>
									
									<table class="table table-striped" id="">
									<thead>
									<tr class='report'>
										<th style="text-align:center !important;" class="action_column_class">Action</th>
										<th style="text-align:center !important;">Item #</th>
										<th style="text-align:center !important;">Branch</th>
										<th style="text-align:center !important;">Capacity</th>
									</tr>
									</thead>
										<tbody id="branch_tank_table_body_data">
												<tr style="display: none;">
													<td>HIDDEN</td>
												</tr>
										</tbody>
									</table>
								<!--	<div class="row mb-3">
									<div class="col-sm-6" align='right'>
								<div id="loading_data_update_so" style="display:none;">
									<div class="spinner-border text-success" role="status">
										<span class="visually-hidden">Loading...</span>
									</div>
								</div>
							</div>
							
							<div class="col-sm-6" align='right'>
									<button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="save-product-price-per-branch" value=""> Save</button>
							</div>	
							
							</div>-->

							</div>
					
						</div>
						
					</div>

				</div>
		</div>
	</div>
	
	
	<!--Modal to Create SO Product-->
	<div class="modal fade" id="AddProductTankModal" tabindex="-1">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Add Product</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-3 needs-validation" id="AddProduct">

						<div class="row mb-2">
						
						<div class="col-sm-12">
						
						<div class="form-floating mb-3">
						  <input class="form-control" list="product_list" name="product_name" id="product_idx" required autocomplete="off" onchange="TotalAmount()"placeholder="Date">
						  <label for="product_idx">Product</label>
						 </div>
						<span class="valid-feedback" id="product_idxError"></span>
						
						</div>
						
						</div>
						
						<div class="row mb-2">
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type="number" class="form-control" aria-label="" name="product_manual_price" id="product_manual_price" value="" step=".01" onchange="TotalAmount()" placeholder="Custom Price">
								<label for="product_manual_price">Custom Price</label>
							</div>
						 
						</div>
						</div>
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type="number" class="form-control" aria-describedby="basic-addon1" name="order_quantity" id="order_quantity" required step=".01" onchange="TotalAmount()" placeholder="Quantity">
								<label for="order_quantity">Quantity</label>
							</div>
							 <span class="valid-feedback" id="order_quantityError"></span>
						</div>

						<div class="row mb-2">
						  <label class="col-sm-3 col-form-label">Amount</label>
						  <div class="col-sm-9">
								<span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="TotalAmount">0.00</span>
						  </div>
						</div>
						</div>
						
                    <div class="modal-footer modal-footer_form">
							<div id="loading_data_add_product" style="display:none;">
							<div class="spinner-border text-success" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
							</div>
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="save-product"> Save</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon" id="clear-so-save-product"> Reset</button>					  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
				  
                </div>
             </div>	
			 
    </section>
</main>


											

@endsection

