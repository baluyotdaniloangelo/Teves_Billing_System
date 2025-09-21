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
				
					<div class="col-sm-3">
						
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
					
					<div class="col-sm-9">
					
					    <ul class="nav nav-tabs" id="myTab" role="tablist">
							
							<!-- nav-link active -->
							<li class="nav-item" role="presentation">
								<button class="nav-link <?php if($tab=='branchprice') { echo 'active'; } ?>" id="branchprice-tab" data-bs-toggle="tab" data-bs-target="#branchprice" type="button" role="tab" aria-controls="branchprice" aria-selected="true" title='Product Prices per branch'>Price per Branch</button>
							</li>
							<!---->
							<li class="nav-item" role="presentation">
								<button class="nav-link <?php if($tab=='seller') { echo 'active'; } ?>" id="seller-tab" data-bs-toggle="tab" data-bs-target="#seller" type="button" role="tab" aria-controls="seller" aria-selected="false" tabindex="-1" title='Product Prices per Seller'>Seller Price</button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link <?php if($tab=='selling_price') { echo 'active'; } ?>" id="seller-tab" data-bs-toggle="tab" data-bs-target="#selling" type="button" role="tab" aria-controls="selling" aria-selected="false" tabindex="-1" title='Product Prices per Client'>Selling Price</button>
							</li>
							
							<li class="nav-item" role="presentation">
								<button class="nav-link <?php if($tab=='tank') { echo 'active'; } ?>" id="tank-tab" data-bs-toggle="tab" data-bs-target="#tank" type="button" role="tab" aria-controls="tank" aria-selected="false" tabindex="-1" title='Tank Information'>Tank</button>
							</li>
							
							<li class="nav-item" role="presentation">
								<button class="nav-link <?php if($tab=='pump') { echo 'active'; } ?>" id="pump-tab" data-bs-toggle="tab" data-bs-target="#pump" type="button" role="tab" aria-controls="pump" aria-selected="false" tabindex="-1" title='Pump Information'>Pump</button>
							</li>
									
						</ul>
					
						<div class="tab-content pt-2" id="myTabContent">
						
							<div class="tab-pane fade  <?php if($tab=='branchprice') { echo ' show active'; } ?>"" id="branchprice" role="tabpanel" aria-labelledby="branchprice-tab">
									
									<table class="table dataTable display nowrap cell-border" id="product_price_branch" width="100%" cellspacing="0" >
									<thead>
									<tr class='report'>
										<th class="all">Item #</th>
										<th class="all">Branch</th>
										<th class="none">Buying Price:</th>
										<th class="none">Profit Margin:</th>
										<th class="none">Type:</th>
										<th class="none">In Peso:</th>
										<th class="all">Selling Price</th>
										<th class="all">Action</th>
									</tr>
									</thead>
										<tbody>
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
							
							</div>

							</div>
					
						</div>
						<div class="tab-content pt-2" id="myTabContent">
						<div class="tab-pane fade  <?php if($tab=='seller') { echo ' show active'; } ?>"" id="seller" role="tabpanel" aria-labelledby="sellerprice-tab">
									
									<div class="d-flex justify-content-end" id="">
									<div class="btn-group" role="group" aria-label="Basic outlined example">
										<button type="button" class="btn btn-success new_item bi bi-plus-circle form_button_icon" data-bs-toggle="modal" data-bs-target="#ProductPricePerSellerModal" id="IDProductPricePerSellerModal" onclick="reset_form_sellers_price()"></button>
									</div>											
									</div>
									<br>
									
									<table class="table dataTable display nowrap cell-border" id="ProductPricePerSellerListTable" width="100%" cellspacing="0" >
									<thead>
									<tr class='report'>
										<th class="all">Item #</th>
										<th class="all">Branch</th>
										<th class="all">Supplier</th>
										<th class="all">Seller Price</th>
										<th class="all">Action</th>
									</tr>
									</thead>
										<tbody>
										</tbody>
									</table>
									
									

							</div>
					
						</div>
						
						<div class="tab-content pt-2" id="myTabContent">
						<div class="tab-pane fade  <?php if($tab=='selling_price') { echo ' show active'; } ?>"" id="selling" role="tabpanel" aria-labelledby="sellingprice-tab">
									
									<div class="d-flex justify-content-end" id="">
									<div class="btn-group" role="group" aria-label="Basic outlined example">
										<button type="button" class="btn btn-success new_item bi bi-plus-circle form_button_icon" data-bs-toggle="modal" data-bs-target="#ProductPricePerClientModal" id="IDProductPricePerClientModal" onclick="reset_form_selling_price()"></button>
									</div>											
									</div>
									<br>
									
									<table class="table dataTable display nowrap cell-border" id="ProductPricePerClientListTable" width="100%" cellspacing="0" >
									<thead>
									<tr class='report'>
										<th class="all">Item #</th>
										<th class="all">Branch</th>
										<th class="all">Client</th>
										<th class="all">Selling Price</th>
										<th class="all">Action</th>
									</tr>
									</thead>
										<tbody>
										</tbody>
									</table>
									
									

							</div>
					
						</div>
						
						<div class="tab-content pt-2" id="myTabContent">
						
							<div class="tab-pane fade  <?php if($tab=='tank') { echo ' show active'; } ?>"" id="tank" role="tabpanel" aria-labelledby="tank-tab">
									
									<div class="d-flex justify-content-end" id="">
									<div class="btn-group" role="group" aria-label="Basic outlined example" style="">
										<button type="button" class="btn btn-success new_item bi bi-plus-circle form_button_icon" data-bs-toggle="modal" data-bs-target="#AddProductTankModal" id="AddProductTankBTN"></button>
									</div>											
									</div>
									<br>
									<table class="table dataTable display nowrap cell-border" id="ProductTankListTable" width="100%">
									<thead>
									<tr class='report'>
										<th >Item #</th>
										<th >Branch</th>
										<th >Name</th>
										<th >Capacity</th>
										<th >Unit</th>
										<th >Action</th>
									</tr>
									</thead>
										<tbody>
										</tbody>
									</table>
								
							</div>
					
						</div>
						
						<div class="tab-content pt-2" id="myTabContent">
						
							<div class="tab-pane fade  <?php if($tab=='pump') { echo ' show active'; } ?>"" id="pump" role="tabpanel" aria-labelledby="pump-tab">
									
									<div class="d-flex justify-content-end" id="">
									<div class="btn-group" role="group" aria-label="Basic outlined example" style="">
										<button type="button" class="btn btn-success new_item bi bi-plus-circle form_button_icon" data-bs-toggle="modal" data-bs-target="#AddProductPumpModal" id="AddProductPumpBTN"></button>
									</div>											
									</div>
									<br>
									<table class="table dataTable display nowrap cell-border" id="ProductPumpListTable" width="100%">
									<thead>
									<tr class='report'>
										<th >Item #</th>
										<th >Branch</th>
										<th >Name</th>
										<th >Initial Reading</th>
										<th >Unit</th>
										<th >Action</th>
									</tr>
									</thead>
										<tbody>
										</tbody>
									</table>
								
							</div>
					
						</div>
						
					</div>

				</div>
		</div>
	</div>
	
	<!--Moda Product Tank-->
	<div class="modal fade" id="AddProductTankModal" tabindex="-1" data-bs-backdrop="static">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Add Tank</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-3 needs-validation" id="AddProductTank">
						<div class="row mb-2">
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<select class="form-select form-control" required="" name="branch_idx" id="branch_idx">
									@foreach ($teves_branch as $teves_branch_cols)
											<?php 
											$branch_id = $teves_branch_cols->branch_id;
													
											?>
										<option value="{{$teves_branch_cols->branch_id}}">
												{{$teves_branch_cols->branch_code}}
										</option>
													
									@endforeach
								</select>
								<label for="branch_idx">Branch</label>
								 <span class="valid-feedback" id="branch_idxError"></span>
							</div>
							
						</div>
						</div>


						<div class="row mb-2">
						
						<div class="col-sm-12">
						
						<div class="form-floating mb-3">
						  <input class="form-control" name="tank_name" id="tank_name" required autocomplete="off" placeholder="Date">
						  <label for="tank_name">Name</label>
						  <span class="valid-feedback" id="tank_nameError"></span>
						 </div>
						
						
						</div>
						
						</div>
						
						<div class="row mb-2">
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type="number" class="form-control" aria-label="" name="tank_capacity" id="tank_capacity" value="" step=".01" placeholder="Capacity">
								<label for="tank_capacity">Capacity</label>
								<span class="valid-feedback" id="tank_capacityError"></span>
							</div>
							
							
						</div>
						</div>
						
						
						</div>
						
                    <div class="modal-footer modal-footer_form">
							<div id="loading_data_add_product" style="display:none;">
							<div class="spinner-border text-success" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
							</div>
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="save-tank"> Save</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon" id="clear-save-tank"> Reset</button>					  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
				  
                </div>
             </div>	

	<div class="modal fade" id="UpdateProductTankModal" tabindex="-1" data-bs-backdrop="static">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Update Tank</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-3 needs-validation" id="UpdateProductTank">
						<div class="row mb-2">
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<select class="form-select form-control" required="" name="update_branch_idx" id="update_branch_idx">
									@foreach ($teves_branch as $teves_branch_cols)
											<?php 
											$branch_id = $teves_branch_cols->branch_id;
													
											?>
										<option value="{{$teves_branch_cols->branch_id}}">
												{{$teves_branch_cols->branch_code}}
										</option>
													
									@endforeach
								</select>
								<label for="update_branch_idx">Branch</label>
							</div>
							 <span class="valid-feedback" id="update_branch_idxError"></span>
						</div>
						</div>


						<div class="row mb-2">
						
						<div class="col-sm-12">
						
						<div class="form-floating mb-3">
						  <input class="form-control" name="update_tank_name" id="update_tank_name" required autocomplete="off" placeholder="Date">
						  <label for="tank_name">Name</label>
						 </div>
						<span class="valid-feedback" id="update_tank_nameError"></span>
						
						</div>
						
						</div>
						
						<div class="row mb-2">
						
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<input type="number" class="form-control" aria-label="" name="update_tank_capacity" id="update_tank_capacity" value="" step=".01" placeholder="Capacity">
								<label for="update_tank_capacity">Capacity</label>
							</div>
							<span class="valid-feedback" id="update_tank_capacityError"></span>
							
						</div>
						</div>
						
						
						</div>
						
                    <div class="modal-footer modal-footer_form">
							<div id="loading_data_update_product" style="display:none;">
							<div class="spinner-border text-success" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
							</div>
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="update-tank"> Update</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon" id="clear-update-tank"> Reset</button>					  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
				  
                </div>
             </div>	


	<!-- Bill Delete Modal-->
    <div class="modal fade" id="TankInfoDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>
				
				
                <div class="modal-body warning_modal_bg" id="modal-body">
				Are you sure you want to Delete This Tank?<br>
				</div>
				<div align="left"style="margin: 10px;">
				
				Name: <span id="delete_tank_name"></span><br>
				Capacity: <span id="delete_tank_capacity"></span><br>
				
				</div>
                <div class="modal-footer footer_modal_bg">
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deleteTankInfoConfirmed" value=""><i class="bi bi-trash3 form_button_icon"></i> Delete</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Cancel</button>
                  
                </div>
            </div>
        </div>
    </div>
			 	

	<div class="modal fade" id="UpdateProductPricePerBranchModal" data-bs-backdrop="static" tabindex="-1">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Update Price per Branch</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						
						<button type="button" class="btn btn-danger bi bi-x-circle form_button_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-3 needs-validation" id="UpdateProductPricePerBranch">

						<div class="row mb-2">
						<div class="col-sm-12">
								<ol class="list-group list-group-numbered">
									<li class="list-group-item d-flex justify-content-between align-items-start">
									  <div class="ms-2 me-auto">
										<div class="fw-bold">Branch Name</div>
										<span id="branch_name_price"></span>
									  </div>
									 
									</li>
									<li class="list-group-item d-flex justify-content-between align-items-start">
									  <div class="ms-2 me-auto">
										<div class="fw-bold">Branch Code</div>
										<div id="branch_code_price"></div>
									  </div>
									 
									</li>
									<li class="list-group-item d-flex justify-content-between align-items-start">
									  <div class="ms-2 me-auto">
										<div class="fw-bold">Branch Price</div>
										<span id="branch_price"></span>
									  </div>
									  
									</li>
							  </ol>
						</div>
						</div>
						<br>
						<div class="row mb-2">
						
						<div class="col-sm-12">
						
						<div class="form-floating mb-3">
						  <input type="number" class="form-control " name="update_buying_price" id="update_buying_price" value="" required="" step=".01">
						  <label for="update_buying_price">Buying Price</label>
						 </div>
						<span class="valid-feedback" id="update_buying_priceError"></span>
						
						</div>
						
						</div>						
						
						<div class="row mb-2">
						
						<div class="col-sm-12">
						
						<div class="form-floating mb-3">
						  <input type="number" class="form-control " name="update_profit_margin" id="update_profit_margin" value="" required="" step=".01">
						  <label for="update_profit_margin">Profit Margin</label>
						 </div>
						<span class="valid-feedback" id="update_profit_marginError"></span>
						
						</div>
						
						</div>

						<div class="row mb-2">
						<div class="col-sm-12">
						
							<div class="form-floating mb-3">
								<select class="form-select form-control" required="" name="update_profit_margin_type" id="update_profit_margin_type">
										<option value="Percentage">Percentage</option>
										<option value="Peso">Peso</option>
								</select>
								<label for="update_profit_margin_type">Profit Margin Type</label>
							</div>
							 <span class="valid-feedback" id="update_profit_margin_typeError"></span>
						</div>
						</div>						
	
						</div>
						
                    <div class="modal-footer modal-footer_form">
							<div id="loading_data_update_product" style="display:none;">
							<div class="spinner-border text-success" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
							</div>
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill form_button_icon" id="update-branch-price"> Update</button>
						  <!--<button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill form_button_icon" id="clear-update-branch-price"> Reset</button>-->				  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
				  
                </div>
             </div>	

	<div id="ProductHistoryChangesModal" class="modal custom-modal fade" data-bs-backdrop="static" role="dialog">
<!--<div class="modal fade show" id="" tabindex="-1" aria-modal="true" role="dialog">-->
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>

                <div class="modal-body warning_modal_bg" id="modal-body">
				Price Changes<br>
				</div>
				
				<div align="left" style="margin: 10px;">
				Product: <span id="product_name_history_view"></span><br>
				Price: <span id="branch_price_history_view"></span><br>
				Branch Name: <span id="branch_name_history_view"></span><br>
				Branch Code: <span id="branch_code_history_view"></span><br>
					<div class="table-responsive">
						<table class="table dataTable display nowrap cell-border" width="100%" cellspacing="0" id="product_price_branch_history">
							<thead>
								<tr>
									<th class="all">Item #</th>
									<th class="all">Date</th>
									<th class="all">Time</th>
									<th class="all">Branch</th>
									<th class="none">Buying Price:</th>
									<th class="none">Profit Margin:</th>
									<th class="none">Type:</th>
									<th class="none">In Peso:</th>
									<th class="all">Selling Price</th>
								</tr>
							</thead>
								<tbody>
								</tbody>
						</table>
					</div>
				</div>
                <div class="modal-footer footer_modal_bg">
                 	<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Close</button>
                  
                </div>
            </div>
        </div>
    </div>

	@include('pages.update_product_information_form_seller_price')
	@include('pages.update_product_information_form_selling_price')
	@include('pages.update_product_information_form_pump')
    </section>
</main>


											

@endsection

