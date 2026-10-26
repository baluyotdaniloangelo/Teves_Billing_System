<div class="modal fade" id="SalesOrderPreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header header_modal_bg">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
 					<div class="btn-sm btn-warning btn-circle bi bi-exclamation-circle btn_icon_modal"></div>
                </div>

                <div class="modal-body warning_modal_bg" id="modal-body">
				Sales Order - Product<br>
				</div>
				
				<div align="left"style="margin: 10px;">
				Date: <span id="preview_product_sales_order_date"></span><br>
				S.O. No.: <span id="preview_product_sales_control_number"></span><br>
				Client: <span id="preview_product_client_name"></span><br>
				DR Number: <span id="preview_product_dr_number"></span><br>
				OR Number: <span id="preview_product_or_number"></span><br>
				Total Due: <span id="preview_product_total_due"></span><br>
				
				<table class="table table-striped" id="">
						<thead>
						<tr class='report'>
							<th style="text-align:center !important;">Item #</th>
							<th style="text-align:left !important;">Description</th>
							<th style="text-align:right !important;">Price</th>
							<th style="text-align:right !important;">Quantity</th>
							<!--<th style="text-align:center !important;">Amount</th>	-->
						</tr>
						</thead>
							<tbody id="table_sales_order_product_body_data">
									<tr style="display: none;">
										<td>HIDDEN</td>
									</tr>
							</tbody>
						</table>
				
				
				</div>
                <div class="modal-footer footer_modal_bg">
                 	<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-x-circle form_button_icon"></i> Close</button>
                  
                </div>
            </div>
        </div>
    </div>
