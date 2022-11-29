@extends('layouts.layout')  
@section('content')  

<main id="main" class="main">	
    <section class="section">	  
          <div class="card">
		  
			  <div class="card">
			  
				<div class="card-header ">
				  <h5 class="card-title">&nbsp;{{ $title }}</h5>
					<div class="d-flex justify-content-end" id="Product_option"></div>				  
				  </div>
				</div>			  
		 
            <div class="card-body">			
				<div class="p-d3">
									<div class="table-responsive">
										<table class="table table-bordered dataTable" id="getProductTransactionList" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th>#</th>
													<th>Product Name</th>
													<th>Price</th>
													<th>Action</th>
												</tr>
											</thead>				
											
											<tbody>
												
											</tbody>
										</table>
									</div>		
				</div>									
                   
            </div>
          </div>

	<!-- Site Delete Modal-->
    <div class="modal fade" id="ProductDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>
				
				
                <div class="modal-body warning_modal_bg" id="modal-body">
				Are you sure you want to Delete This Product?<br>
				</div>
				<div align="left"style="margin: 10px;">
				Date: <span id="Product_delete_product_name"></span><br>
				Time: <span id="Product_delete_product_price"></span><br>
				
				</div>
                <div class="modal-footer footer_modal_bg">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle navbar_icon"></i> Cancel</button>
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deleteProductConfirmed" value=""><i class="bi bi-trash3 navbar_icon"></i> Delete</button>
                  
                </div>
            </div>
        </div>
    </div>	

	<!-- Site Delete Modal-->
    <div class="modal fade" id="ProductDeleteModalConfirmed" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>
                <div class="modal-body warning_modal_bg" id="modal-body">
				Successfully Deleted <span id="site_description_info_confirmed"></span>!
				</div>
				
				<div align="left"style="margin: 10px;">
				Date: <span id="Product_delete_confirmed_product_name"></span><br>
				Time: <span id="Product_delete_confirmed_product_price"></span><br>
				
				
				</div>
                <div class="modal-footer footer_modal_bg">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle navbar_icon"></i> Close</button>
					     
                </div>
            </div>
        </div>
    </div>

	<!--Modal to Create Product-->
	<div class="modal fade" id="CreateProductModal" tabindex="-1">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Create Product</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						<button type="button" class="btn btn-info bi bi-question navbar_icon" data-bs-toggle="modal" data-bs-target="#GatewayManual" id="manualbtn"></button>
						<button type="button" class="btn btn-danger bi bi-x-circle navbar_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-2 needs-validation" id="ProductformNew">
					  
						<div class="row mb-2">
						  <label for="product_name" class="col-sm-3 col-form-label">Product Name</label>
						  <div class="col-sm-9">
							<input type="date" class="form-control" name="product_name" id="product_name" value="" required>
							<span class="valid-feedback" id="product_nameError" title="Required"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="product_price" class="col-sm-3 col-form-label">Product Price</label>
						  <div class="col-sm-9">
							<input type="time" class="form-control " name="product_price" id="product_price" value="" required>
							<span class="valid-feedback" id="product_priceError"></span>
						  </div>
						</div>						
									
						</div>
						
                    <div class="modal-footer modal-footer_form">
						
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill navbar_icon" id="save-product"> Submit</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill navbar_icon" id="clear-product"> Reset</button>
						  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>
	
	
	<!--Modal to Upadate Site-->
	<div class="modal fade" id="UpdateProductModal" tabindex="-1">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Update Product</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						<button type="button" class="btn btn-danger bi bi-x-circle navbar_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">
					
					  <form class="g-2 needs-validation" id="ProductformEdit">
					  
						<div class="row mb-2">
						  <label for="update_product_name" class="col-sm-3 col-form-label">Product Name</label>
						  <div class="col-sm-9">
							<input type="date" class="form-control" name="update_product_name" id="update_product_name" value="" required>
							<span class="valid-feedback" id="update_product_nameError" title="Required"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="update_product_price" class="col-sm-3 col-form-label">Product Price</label>
						  <div class="col-sm-9">
							<input type="time" class="form-control " name="update_product_price" id="update_product_price" value="" required>
							<span class="valid-feedback" id="update_product_priceError"></span>
						  </div>
						</div>					
									
						</div>
						
                    <div class="modal-footer modal-footer_form">
						
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill navbar_icon" id="update-product"> Submit</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill navbar_icon" id="clear-product"> Reset</button>
						  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
             </div>
	

    </section>
</main>


@endsection

