<section class="so-order-details-panel" aria-label="Sales Order details">
    <div class="so-order-details-toolbar">
        <div class="so-order-details-title">
            <span class="so-order-details-icon"><i class="bi bi-receipt" aria-hidden="true"></i></span>
            <div>
                <h2>Sales Order Details</h2>
                <p>Order information and customer details</p>
            </div>
        </div>
        <div class="so-order-details-actions">
            <button type="button" class="so-btn-secondary so-order-details-toggle" data-bs-toggle="collapse" data-bs-target="#so-editor-order-details-collapse" aria-expanded="false" aria-controls="so-editor-order-details-collapse">
                <i class="bi bi-chevron-down" aria-hidden="true"></i>
                <span class="so-order-details-label-closed">Show Order Details</span>
                <span class="so-order-details-label-open">Hide Order Details</span>
            </button>
            <button type="button" class="so-btn-primary so-order-details-edit" data-bs-toggle="modal" data-bs-target="#so-editor-UpdateSalesOrderModal" id="so-editor-UpdateSalesOrderBTN"><i class="bi bi-pencil-fill" aria-hidden="true"></i> Edit</button>
            <button type="button" class="so-order-details-print" id="so-editor-PrintSalesOrder"><i class="bi bi-printer" aria-hidden="true"></i> Print</button>
        </div>
    </div>
    <div class="collapse" id="so-editor-order-details-collapse">
        <div class="so-order-details-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><b>Date:</b><span>&nbsp;{{ $sales_order_data[0]['sales_order_date'] }}</span></li>
                <li class="list-group-item"><b>Branch:</b><span>&nbsp;{{ $sales_order_data[0]['branch_code'] }} - {{ $sales_order_data[0]['branch_name'] }}</span></li>
                <li class="list-group-item"><b>Sold To:</b><span>&nbsp;{{ $sales_order_data[0]['client_name'] }}</span></li>
                <li class="list-group-item"><b>Payment Type:</b><span>&nbsp;{{ $sales_order_data[0]['sales_order_payment_type'] }}</span></li>
                <li class="list-group-item"><b>With Sales Invoice:</b><span>&nbsp;{{ $sales_order_invoice == '1' ? 'YES' : 'NO' }}</span></li>
                <li class="list-group-item"><b>Net Value:</b><span>&nbsp;{{ $sales_order_data[0]['sales_order_net_percentage'] }}</span></li>
                <li class="list-group-item"><b>Withholding Tax:</b><span>&nbsp;{{ $sales_order_data[0]['sales_order_withholding_tax'] }}</span></li>
                <li class="list-group-item"><b>D.R Number:</b><span>&nbsp;{{ $sales_order_data[0]['sales_order_dr_number'] }}</span></li>
                <li class="list-group-item"><b>P.O Number:</b><span>&nbsp;{{ $sales_order_data[0]['sales_order_po_number'] }}</span></li>
                <li class="list-group-item"><b>Sales Invoice:</b><span>&nbsp;{{ $sales_order_data[0]['sales_order_or_number'] }}</span></li>
                <li class="list-group-item"><b>Charge Invoice:</b><span>&nbsp;{{ $sales_order_data[0]['sales_order_charge_invoice'] }}</span></li>
                <li class="list-group-item"><b>Collection Receipt:</b><span>&nbsp;{{ $sales_order_data[0]['sales_order_collection_receipt'] }}</span></li>
                <li class="list-group-item"><b>Payment Term:</b><span>&nbsp;{{ $sales_order_data[0]['sales_order_payment_term'] }}</span></li>
                <li class="list-group-item"><b>Delivered To:</b><span>&nbsp;{{ $sales_order_data[0]['sales_order_delivered_to'] }}</span></li>
                <li class="list-group-item"><b>Delivered To Address:</b><span>&nbsp;{{ $sales_order_data[0]['sales_order_delivered_to_address'] }}</span></li>
                <li class="list-group-item"><b>Delivery Method:</b><span>&nbsp;{{ $sales_order_data[0]['sales_order_delivery_method'] }}</span></li>
                <li class="list-group-item"><b>Hauler:</b><span>&nbsp;{{ $sales_order_data[0]['sales_order_hauler'] }}</span></li>
                <li class="list-group-item"><b>Required Date:</b><span>&nbsp;{{ $sales_order_data[0]['sales_order_required_date'] }}</span></li>
                <li class="list-group-item"><b>Instructions:</b><span>&nbsp;{{ $sales_order_data[0]['sales_order_instructions'] }}</span></li>
                <li class="list-group-item"><b>Notes:</b><span>&nbsp;{{ $sales_order_data[0]['sales_order_note'] }}</span></li>
            </ul>
        </div>
    </div>
</section>
