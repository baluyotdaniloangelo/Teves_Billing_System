
<script type="text/javascript">

	  
/*========================================
PURCHASE ORDER INFORMATION
========================================*/
LoadPurchaseOrderInfo();  
	
function LoadPurchaseOrderInfo() {

    const purchase_order_id = {{ $PurchaseOrderID }};

    $.ajax({
        url: "/purchase_order_info",
        type: "POST",
        dataType: "json",
        data: {
            purchase_order_id,
            _token: "{{ csrf_token() }}"
        },

        success: function (response) {

            if (!Array.isArray(response) || response.length === 0) {
                console.error("Purchase Order not found.");
                return;
            }

            const po = response[0];

            // Helper function
            const setValue = (id, value = '') => {
                const el = document.getElementById(id);
                if (el) {
                    el.value = value ?? '';
                } else {
                    console.warn(`Element not found: ${id}`);
                }
            };

            const setText = (id, value = '') => {
                const el = document.getElementById(id);
                if (el) {
                    el.textContent = value ?? '';
                } else {
                    console.warn(`Element not found: ${id}`);
                }
            };

            // Header
            setText('control_no', po.purchase_order_control_number);

            // Form
            setValue('purchase_order_date', po.purchase_order_date);
            setValue('purchase_order_type', po.category_idx);
            setValue('supplier_idx', po.supplier_name);
            setValue('purchase_order_sales_order_number', po.purchase_order_sales_order_number);
            setValue('purchase_order_collection_receipt_no', po.purchase_order_collection_receipt_no);
            setValue('purchase_order_official_receipt_no', po.purchase_order_official_receipt_no);
            setValue('purchase_order_delivery_receipt_no', po.purchase_order_delivery_receipt_no);

            setValue('purchase_order_delivery_method', po.purchase_order_delivery_method);
            setValue('purchase_loading_terminal', po.purchase_loading_terminal);

            setValue('purchase_order_net_percentage', po.purchase_order_net_percentage);
            setValue('purchase_order_less_percentage', po.purchase_order_less_percentage);

            setValue('hauler_operator', po.hauler_operator);
            setValue('lorry_driver', po.lorry_driver);
            setValue('plate_number', po.plate_number);

            setValue('purchase_destination', po.purchase_destination);
            setValue('purchase_order_instructions', po.purchase_order_instructions);
            setValue('purchase_order_note', po.purchase_order_note);

            setValue('company_header', po.company_header);
            setValue('purchase_order_invoice', po.purchase_order_invoice);

            const btn = document.getElementById('update-purchase-order');
            if (btn) {
                btn.value = purchase_order_id;
            }

            // Summary
            const invoiceText = po.purchase_order_invoice === 1 ? 'Yes' : 'No';

            setText('po_info_date', po.purchase_order_date);
            setText('po_info_branch_name', po.branch_code);
            setText('po_info_suppliers_name', po.supplier_name);
            setText('po_info_net_value', po.purchase_order_net_percentage);
            setText('po_info_with_sales_invoice', invoiceText);
            setText('po_info_less_value', po.purchase_order_less_percentage);

            setText('po_info_sales_order', po.purchase_order_sales_order_number);
            setText('po_info_collection_receipt', po.purchase_order_collection_receipt_no);
            setText('po_info_sales_invoice', po.purchase_order_official_receipt_no);
            setText('po_info_delivery_receipt', po.purchase_order_delivery_receipt_no);

            setText('po_info_delivery_method', po.purchase_order_delivery_method);
            setText('po_info_loading_terminal', po.purchase_loading_terminal);
            setText('po_info_haulers_name', po.hauler_operator);
            setText('po_info_drivers_name', po.lorry_driver);
            setText('po_info_plate_number', po.plate_number);
            setText('po_info_destination', po.purchase_destination);
            setText('po_info_instructions', po.purchase_order_instructions);
            setText('po_info_notes', po.purchase_order_note);
        },

        error: function (xhr, status, error) {
            console.error("AJAX Error:", {
                status: xhr.status,
                response: xhr.responseText,
                error: error
            });

            alert("Unable to load Purchase Order information.");
        }

    });

}

</script>
