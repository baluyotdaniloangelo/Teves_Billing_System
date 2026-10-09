<script type="text/javascript">
function cashierReportMiscTableHeader(iconName, label)
{
    return '<i class="bi bi-' + iconName + '" aria-hidden="true"></i>' + label;
}	
LoadCashiersReportPH3_SALES_CREDIT();
LoadCashiersReportPH3_DISCOUNT();
LoadCashiersReportPH3_OTHERS();

function LoadCashiersReportPH3_SALES_CREDIT()
{
    let CashiersReportId = {{ $CashiersReportId }};

    // ==========================================
    // DESTROY EXISTING DATATABLE
    // ==========================================

    if ($.fn.DataTable.isDataTable('#CashiersReportPH3SalesCreditTable'))
    {
        $('#CashiersReportPH3SalesCreditTable')
            .DataTable()
            .destroy();
    }


    // ==========================================
    // CLEAR TABLE BODY
    // ==========================================

    $('#table_product_data_msc_SALES_CREDIT')
        .empty();


    // ==========================================
    // LOAD DATA
    // ==========================================

    $.ajax({

        url: "{{ route('GetCashiersProductP3_SALES_CREDIT') }}",

        type: "POST",

        data:
        {
            CashiersReportId:
                CashiersReportId,

            _token:
                "{{ csrf_token() }}"
        },


        // ==========================================
        // SUCCESS
        // ==========================================

        success: function(response)
        {
            console.log(response);


            let tableData = [];


            // ==========================================
            // PREPARE DATATABLE DATA
            // ==========================================

            if (response && response.length > 0)
            {
                response.forEach(function(item, index)
                {
                    let pump_price =
                        parseFloat(item.pump_price) || 0;

                    let order_quantity =
                        parseFloat(item.order_quantity) || 0;

                    let order_total_amount =
                        parseFloat(item.order_total_amount) || 0;


                    tableData.push([
                        index + 1,

                        item.client_name ?? '',

                        item.reference_no ?? '',

                        item.product_name ?? '',

                        order_quantity.toLocaleString(
                            "en-PH",
                            {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }
                        ),

                        pump_price.toLocaleString(
                            "en-PH",
                            {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }
                        ),

                        order_total_amount.toLocaleString(
                            "en-PH",
                            {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }
                        ),

                        `
                        <div class="text-center">

                            <a href="#"
                               class="btn-danger btn-circle btn-sm
                                      bi-pencil-fill
                                      btn_icon_table
                                      btn_icon_table_edit"
                               id="CHPH3_Edit_SALES_CREDIT"
                               data-id="${item.cashiers_report_p3_id}">
                            </a>

                            <a href="#"
                               class="btn-danger btn-circle btn-sm
                                      bi-trash3-fill
                                      btn_icon_table
                                      btn_icon_table_delete"
                               id="deleteCashiersProductP3_SALES_CREDIT"
                               data-id="${item.cashiers_report_p3_id}">
                            </a>

                        </div>
                        `
                    ]);
                });
            }


            // ==========================================
            // INITIALIZE DATATABLE
            // ==========================================

            $('#CashiersReportPH3SalesCreditTable').DataTable({

                data: tableData,

                columns:
                [
                    {
                        title: cashierReportMiscTableHeader("hash", "#"),
                        className: "text-center"
                    },

                    {
                        title: cashierReportMiscTableHeader("person", "Account Name")
                    },

                    {
                        title: cashierReportMiscTableHeader("file-earmark-text", "Reference No.")
                    },

                    {
                        title: cashierReportMiscTableHeader("box-seam", "Product / Description")
                    },

                    {
                        title: cashierReportMiscTableHeader("cart3", "Quantity"),
                        className: "text-center"
                    },

                    {
                        title: cashierReportMiscTableHeader("tag", "Pump Price"),
                        className: "text-end"
                    },

                    {
                        title: cashierReportMiscTableHeader("cash-stack", "Total Amount"),
                        className: "text-end"
                    },

                    {
                        title: cashierReportMiscTableHeader("three-dots", "Action"),
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }
                ],

                pageLength: 10,

                lengthMenu:
                [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],

                responsive: true,

                autoWidth: false,

                ordering: true,

                searching: true,

                info: true,

                paging: true,

                language:
                {
                    emptyTable:
                        "No Sales Credit records found.",

                    zeroRecords:
                        "No matching records found.",

                    search:
                        "Search:"
                }
            });
        },


        // ==========================================
        // AJAX ERROR
        // ==========================================

        error: function(xhr)
        {
            console.log(
                "Error loading Sales Credit:",
                xhr
            );

            // Initialize empty DataTable
            $('#CashiersReportPH3SalesCreditTable')
                .DataTable({

                    data: [],

                    columns:
                    [
                        {
                            title: cashierReportMiscTableHeader("hash", "#"),
                            className: "text-center"
                        },
                        {
                            title: cashierReportMiscTableHeader("person", "Account Name")
                        },
                        {
                            title: cashierReportMiscTableHeader("file-earmark-text", "Reference No.")
                        },
                        {
                            title: cashierReportMiscTableHeader("box-seam", "Product / Description")
                        },
                        {
                            title: cashierReportMiscTableHeader("cart3", "Quantity"),
                            className: "text-center"
                        },
                        {
                            title: cashierReportMiscTableHeader("tag", "Pump Price"),
                            className: "text-end"
                        },
                        {
                            title: cashierReportMiscTableHeader("cash-stack", "Total Amount"),
                            className: "text-end"
                        },
                        {
                            title: cashierReportMiscTableHeader("three-dots", "Action"),
                            className: "text-center",
                            orderable: false,
                            searchable: false
                        }
                    ],

                    pageLength: 10,

                    language:
                    {
                        emptyTable:
                            "Unable to load Sales Credit records."
                    }
                });
        }
    });
}

function LoadCashiersReportPH3_DISCOUNT()
{
    let CashiersReportId = {{ $CashiersReportId }};


    // ==========================================
    // DESTROY EXISTING DATATABLE
    // ==========================================

    if ($.fn.DataTable.isDataTable('#CashiersReportPH3DiscountTable'))
    {
        $('#CashiersReportPH3DiscountTable')
            .DataTable()
            .destroy();
    }


    // ==========================================
    // CLEAR TABLE BODY
    // ==========================================

    $('#table_product_data_msc_DISCOUNT')
        .empty();


    // ==========================================
    // LOAD DATA
    // ==========================================

    $.ajax({

        url: "{{ route('GetCashiersProductP3_DISCOUNTS') }}",

        type: "POST",

        data:
        {
            CashiersReportId:
                CashiersReportId,

            _token:
                "{{ csrf_token() }}"
        },


        // ==========================================
        // SUCCESS
        // ==========================================

        success: function(response)
        {
            console.log(response);


            let tableData = [];


            // ==========================================
            // PREPARE DATATABLE DATA
            // ==========================================

            if (response && response.length > 0)
            {
                response.forEach(function(item, index)
                {
                    let pump_price =
                        parseFloat(item.pump_price) || 0;

                    let unit_price =
                        parseFloat(item.unit_price) || 0;

                    let discounted_price =
                        parseFloat(item.discounted_price) || 0;

                    let order_quantity =
                        parseFloat(item.order_quantity) || 0;

                    let order_total_amount =
                        parseFloat(item.order_total_amount) || 0;


                    tableData.push([
                        index + 1,

                        item.reference_no ?? '',

                        item.product_name ?? '',

                        order_quantity.toLocaleString(
                            "en-PH",
                            {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }
                        ),

                        pump_price.toLocaleString(
                            "en-PH",
                            {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }
                        ),

                        unit_price.toLocaleString(
                            "en-PH",
                            {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }
                        ),

                        discounted_price.toLocaleString(
                            "en-PH",
                            {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }
                        ),

                        order_total_amount.toLocaleString(
                            "en-PH",
                            {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }
                        ),

                        `
                        <div class="text-center">

                            <a href="#"
                               class="btn-danger btn-circle btn-sm
                                      bi-pencil-fill
                                      btn_icon_table
                                      btn_icon_table_edit"
                               id="CHPH3_Edit_DISCOUNT"
                               data-id="${item.cashiers_report_p3_id}">
                            </a>

                            <a href="#"
                               class="btn-danger btn-circle btn-sm
                                      bi-trash3-fill
                                      btn_icon_table
                                      btn_icon_table_delete"
                               id="deleteCashiersProductP3_DISCOUNT"
                               data-id="${item.cashiers_report_p3_id}">
                            </a>

                        </div>
                        `
                    ]);
                });
            }


            // ==========================================
            // INITIALIZE DATATABLE
            // ==========================================

            $('#CashiersReportPH3DiscountTable').DataTable({

                data: tableData,

                columns:
                [
                    {
                        title: cashierReportMiscTableHeader("hash", "#"),
                        className: "text-center"
                    },

                    {
                        title: cashierReportMiscTableHeader("file-earmark-text", "Reference No."),
                        className: "text-center"
                    },

                    {
                        title: cashierReportMiscTableHeader("box-seam", "Product / Description")
                    },

                    {
                        title: cashierReportMiscTableHeader("cart3", "Quantity"),
                        className: "text-center"
                    },

                    {
                        title: cashierReportMiscTableHeader("tag", "Pump Price"),
                        className: "text-end"
                    },

                    {
                        title: cashierReportMiscTableHeader("tag-fill", "Unit Price"),
                        className: "text-end"
                    },

                    {
                        title: cashierReportMiscTableHeader("percent", "Discounted Price"),
                        className: "text-end"
                    },

                    {
                        title: cashierReportMiscTableHeader("cash-stack", "Total Amount"),
                        className: "text-end"
                    },

                    {
                        title: cashierReportMiscTableHeader("three-dots", "Action"),
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }
                ],

                pageLength: 10,

                lengthMenu:
                [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],

                responsive: true,

                autoWidth: false,

                ordering: true,

                searching: true,

                info: true,

                paging: true,

                language:
                {
                    emptyTable:
                        "No Discount records found.",

                    zeroRecords:
                        "No matching records found.",

                    search:
                        "Search:"
                }
            });
        },


        // ==========================================
        // AJAX ERROR
        // ==========================================

        error: function(xhr)
        {
            console.log(
                "Error loading Discount records:",
                xhr
            );


            $('#CashiersReportPH3DiscountTable')
                .DataTable({

                    data: [],

                    columns:
                    [
                        {
                            title: cashierReportMiscTableHeader("hash", "#"),
                            className: "text-center"
                        },

                        {
                            title: cashierReportMiscTableHeader("file-earmark-text", "Reference No."),
                            className: "text-center"
                        },

                        {
                            title: cashierReportMiscTableHeader("box-seam", "Product / Description")
                        },

                        {
                            title: cashierReportMiscTableHeader("cart3", "Quantity"),
                            className: "text-center"
                        },

                        {
                            title: cashierReportMiscTableHeader("tag", "Pump Price"),
                            className: "text-end"
                        },

                        {
                            title: cashierReportMiscTableHeader("tag-fill", "Unit Price"),
                            className: "text-end"
                        },

                        {
                            title: cashierReportMiscTableHeader("percent", "Discounted Price"),
                            className: "text-end"
                        },

                        {
                            title: cashierReportMiscTableHeader("cash-stack", "Total Amount"),
                            className: "text-end"
                        },

                        {
                            title: cashierReportMiscTableHeader("three-dots", "Action"),
                            className: "text-center",
                            orderable: false,
                            searchable: false
                        }
                    ],

                    pageLength: 10,

                    language:
                    {
                        emptyTable:
                            "Unable to load Discount records."
                    }
                });
        }

    });
}

function LoadCashiersReportPH3_OTHERS()
{
    let CashiersReportId = {{ $CashiersReportId }};


    // ==========================================
    // DESTROY EXISTING DATATABLE
    // ==========================================

    if ($.fn.DataTable.isDataTable('#CashiersReportPH3OthersTable'))
    {
        $('#CashiersReportPH3OthersTable')
            .DataTable()
            .destroy();
    }


    // ==========================================
    // CLEAR TABLE BODY
    // ==========================================

    $('#table_product_data_msc_OTHERS')
        .empty();


    // ==========================================
    // LOAD DATA
    // ==========================================

    $.ajax({

        url: "{{ route('GetCashiersProductP3_OTHERS') }}",

        type: "POST",

        data:
        {
            CashiersReportId:
                CashiersReportId,

            _token:
                "{{ csrf_token() }}"
        },


        // ==========================================
        // SUCCESS
        // ==========================================

        success: function(response)
        {
            console.log(response);


            let tableData = [];


            // ==========================================
            // PREPARE DATATABLE DATA
            // ==========================================

            if (response && response.length > 0)
            {
                response.forEach(function(item, index)
                {
                    let unit_price =
                        parseFloat(item.unit_price) || 0;

                    let order_quantity =
                        parseFloat(item.order_quantity) || 0;

                    let order_total_amount =
                        parseFloat(item.order_total_amount) || 0;


                    tableData.push([
                        index + 1,

                        item.reference_no ?? '',

                        item.item_description ?? '',

                        order_quantity.toLocaleString(
                            "en-PH",
                            {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }
                        ),

                        order_total_amount.toLocaleString(
                            "en-PH",
                            {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }
                        ),

                        `
                        <div class="text-center">

                            <a href="#"
                               class="btn-danger btn-circle btn-sm
                                      bi-pencil-fill
                                      btn_icon_table
                                      btn_icon_table_edit"
                               id="CHPH3_Edit_OTHERS"
                               data-id="${item.cashiers_report_p3_id}">
                            </a>

                            <a href="#"
                               class="btn-danger btn-circle btn-sm
                                      bi-trash3-fill
                                      btn_icon_table
                                      btn_icon_table_delete"
                               id="deleteCashiersProductP3_OTHERS"
                               data-id="${item.cashiers_report_p3_id}">
                            </a>

                        </div>
                        `
                    ]);
                });
            }


            // ==========================================
            // INITIALIZE DATATABLE
            // ==========================================

            $('#CashiersReportPH3OthersTable').DataTable({

                data: tableData,

                columns:
                [
                    {
                        title: cashierReportMiscTableHeader("hash", "#"),
                        className: "text-center"
                    },

                    {
                        title: cashierReportMiscTableHeader("file-earmark-text", "Reference No."),
                        className: "text-center"
                    },

                    {
                        title: cashierReportMiscTableHeader("box-seam", "Item Description")
                    },

                    {
                        title: cashierReportMiscTableHeader("cart3", "Quantity"),
                        className: "text-center"
                    },

                    {
                        title: cashierReportMiscTableHeader("cash-stack", "Total Amount"),
                        className: "text-end"
                    },

                    {
                        title: cashierReportMiscTableHeader("three-dots", "Action"),
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }
                ],

                pageLength: 10,

                lengthMenu:
                [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],

                responsive: true,

                autoWidth: false,

                ordering: true,

                searching: true,

                info: true,

                paging: true,

                language:
                {
                    emptyTable:
                        "No Other records found.",

                    zeroRecords:
                        "No matching records found.",

                    search:
                        "Search:"
                }
            });
        },


        // ==========================================
        // AJAX ERROR
        // ==========================================

        error: function(xhr)
        {
            console.log(
                "Error loading Other records:",
                xhr
            );


            $('#CashiersReportPH3OthersTable')
                .DataTable({

                    data: [],

                    columns:
                    [
                        {
                            title: cashierReportMiscTableHeader("hash", "#"),
                            className: "text-center"
                        },

                        {
                            title: cashierReportMiscTableHeader("file-earmark-text", "Reference No."),
                            className: "text-center"
                        },

                        {
                            title: cashierReportMiscTableHeader("box-seam", "Item Description")
                        },

                        {
                            title: cashierReportMiscTableHeader("cart3", "Quantity"),
                            className: "text-center"
                        },

                        {
                            title: cashierReportMiscTableHeader("cash-stack", "Total Amount"),
                            className: "text-end"
                        },

                        {
                            title: cashierReportMiscTableHeader("three-dots", "Action"),
                            className: "text-center",
                            orderable: false,
                            searchable: false
                        }
                    ],

                    pageLength: 10,

                    language:
                    {
                        emptyTable:
                            "Unable to load Other records."
                    }
                });
        }

    });
}

function input_settings_create_miscellaneous_sales()
{
    var miscellaneous_items_type =
        $("#miscellaneous_items_type").val();


    /*==================================================
    COMMON FIELDS
    ==================================================*/

    document.getElementById(
        "reference_no_miscellaneous_sales"
    ).disabled = false;

    document.getElementById(
        "product_manual_price_miscellaneous_sales"
    ).disabled = false;

    document.getElementById(
        "product_idx_miscellaneous_sales"
    ).disabled = false;

    document.getElementById(
        "order_quantity_miscellaneous_sales"
    ).disabled = false;


    /*==================================================
    SALES CREDIT
    ==================================================*/

    if (miscellaneous_items_type == 'SALES_CREDIT')
    {
        /*----------------------------------------------
        ENABLE CLIENT
        ----------------------------------------------*/

        $("#sold_to_client_id")
            .prop("disabled", false);


        /*----------------------------------------------
        ENABLE ORDER TIME
        ----------------------------------------------*/

        $("#order_time_miscellaneous_sales")
            .prop("disabled", false);


        /*----------------------------------------------
        LABELS
        ----------------------------------------------*/

        $("#quantity_label_miscellaneous_sales")
            .text("QUANTITY");

        $("#manual_price_label_miscellaneous_sales")
            .text("AMOUNT");

        $("#product_label_miscellaneous_sales")
            .text("PRODUCT");
    }


    /*==================================================
    DISCOUNTS
    ==================================================*/

    else if (miscellaneous_items_type == 'DISCOUNTS')
    {
        /*----------------------------------------------
        DISABLE CLIENT
        ----------------------------------------------*/

        $("#sold_to_client_id")
            .prop("disabled", true)
            .val("");

        $("#sold_to_client_idError")
            .text("")
            .removeClass("d-block");


        /*----------------------------------------------
        DISABLE ORDER TIME
        ----------------------------------------------*/

        $("#order_time_miscellaneous_sales")
            .prop("disabled", true)
            .val("");

        $("#order_time_miscellaneous_salesError")
            .text("")
            .removeClass("d-block");


        /*----------------------------------------------
        LABELS
        ----------------------------------------------*/

        $("#quantity_label_miscellaneous_sales")
            .text("LITERS");

        $("#manual_price_label_miscellaneous_sales")
            .text("UNIT PRICE");

        $("#product_label_miscellaneous_sales")
            .text("PRODUCT");
			
		LoadSellingPriceList_branch();
    }


    /*==================================================
    OTHER TYPES
    ==================================================*/

    else
    {
        /*----------------------------------------------
        DISABLE CLIENT
        ----------------------------------------------*/

        $("#sold_to_client_id")
            .prop("disabled", true)
            .val("");

        $("#sold_to_client_idError")
            .text("")
            .removeClass("d-block");


        /*----------------------------------------------
        DISABLE ORDER TIME
        ----------------------------------------------*/

        $("#order_time_miscellaneous_sales")
            .prop("disabled", true)
            .val("");

        $("#order_time_miscellaneous_salesError")
            .text("")
            .removeClass("d-block");


        /*----------------------------------------------
        LABELS
        ----------------------------------------------*/

        $("#quantity_label_miscellaneous_sales")
            .text("LITERS/PCS");

        $("#manual_price_label_miscellaneous_sales")
            .text("AMOUNT");

        $("#product_label_miscellaneous_sales")
            .text("ITEM DESCRIPTION");

        /*
         * Do not clear product/description here.
         *
         * This is important when editing OTHERS.
         */
		 
		 LoadSellingPriceList_branch();
    }
}

function LoadSellingPriceList(client_idx, callback)
{
    const branch_idx =
        {{ $CashiersReportData[0]['teves_branch'] }};

    // Clear existing products
    $("#product_list_miscellaneous_sales").empty();


    $.ajax({

        url: "/get_product_list_selling_price",

        type: "POST",

        data: {
            client_idx: client_idx,
            branch_idx: branch_idx,
            _token: "{{ csrf_token() }}"
        },


        success: function(response)
        {
            const list =
                response.clients_price_list || [];


            if (list.length === 0)
            {
                console.warn("⚠ No products returned");

                if (typeof callback === 'function')
                {
                    callback();
                }

                return;
            }


            list.forEach(function(item)
            {

                $("#product_list_miscellaneous_sales").append(`

                    <option
                        value="${item.product_name}"
                        data-id="${item.product_idx}"
                        data-price="${item.product_price}"
                        label="₱ ${item.product_price} | ${item.product_name}">
                    </option>

                `);

            });


            /*
            ==========================================
            PRODUCTS LOADED
            ==========================================
            */

            if (typeof callback === 'function')
            {
                callback();
            }

        },


        error: function(xhr)
        {
            console.error(
                "Error loading product list:",
                xhr
            );


            if (typeof callback === 'function')
            {
                callback();
            }
        }

    });
}

function LoadSellingPriceList_branch()
{
	
	const branch_idx  = {{ $CashiersReportData[0]['teves_branch'] }};
    
	// Clear existing products
    $("#product_list_miscellaneous_sales").empty();

    $.ajax({

        url: "/get_product_list_selling_price_per_branch",

        type: "POST",

        data: {
            branch_idx: branch_idx,
            _token: "{{ csrf_token() }}"
        },


        success: function(response)
        {
            const list = response.clients_price_list || [];


            if (list.length === 0)
            {
                console.warn("⚠ No products returned");
                return;
            }


            list.forEach(function(item)
            {

                $("#product_list_miscellaneous_sales").append(`

                    <option
                        value="${item.product_name}"
                        data-id="${item.product_idx}"
                        data-price="${item.product_price}"
                        label="₱ ${item.product_price} | ${item.product_name}">
                    </option>

                `);

            });

        },


        error: function(xhr)
        {
            console.error(
                "Error loading product list:",
                xhr
            );
        }

    });
}

	function load_so_reference_no() {		



		
		const client_name =
        $('#sold_to_client_id').val();

		const client_idx =
        $('#sold_to_client_name_list option[value="' +
        client_name +
        '"]').attr('data-id');
		
		let teves_branch   = {{ $CashiersReportData[0]['teves_branch'] }};
		
		
		LoadSellingPriceList(client_idx);
		
		
		$("#so_list_reference option").remove();
		$('<option style="display: none;"></option>').appendTo('#so_list_reference');
		
			  $.ajax({
				url: "{{ route('so_reference_list') }}",
				type:"POST",
				data:{
				  client_idx:client_idx,
				  teves_branch:teves_branch,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){						
				  console.log(response);
				  if(response!='') {	

						var len = response.length;
						for(var i=0; i<len; i++){
						
							var so_id = response[i].so_id;		
							var so_number = response[i].so_number;
	
							$('#so_list_reference option:last').after("<option label='"+so_number+"' data-id='"+so_id+"' value='"+so_number+"' data-price='"+so_number+"' >");	
						
					}			
				  }else{
							/*No Result Found or Error*/	
				  }
				},
				error: function(error) {
				 console.log(error);	 
				}
			   });
	}
	

$('#miscellaneous_sales_form').on('submit', function(event)
{
    event.preventDefault();

    const form = this;


    /*==================================================
    CLEAR PREVIOUS VALIDATION MESSAGES
    ==================================================*/

    clearMiscellaneousSalesValidation();


    /*==================================================
    ENABLE BOOTSTRAP VALIDATION STATE
    ==================================================*/

    form.classList.add('was-validated');


    /*==================================================
    CASHIER'S REPORT INFORMATION
    ==================================================*/

    const CashiersReportId =
        {{ $CashiersReportId }};

    const teves_branch =
        {{ $CashiersReportData[0]['teves_branch'] }};

    const report_date =
        $("input[name=report_date]").val();


    /*==================================================
    CHPH3 ID
    ==================================================*/

    /*
     * IMPORTANT:
     *
     * Edit scripts store CHPH3_ID using:
     *
     * $('#save-miscellaneous_sales')
     *     .data('CHPH3_ID', CHPH3_ID);
     *
     * Therefore use .data() here instead of .val().
     */

    const CHPH3_ID =
        $('#save-miscellaneous_sales').val() || 0;


    /*==================================================
    MISCELLANEOUS TYPE
    ==================================================*/

    const miscellaneous_items_type =
        $('#miscellaneous_items_type').val();


    /*==================================================
    REFERENCE NUMBER
    ==================================================*/

    const reference_no =
        $('#reference_no_miscellaneous_sales').val();

    const reference_no_id =
        $('#so_list_reference option[value="' +
        reference_no +
        '"]').attr('data-id');


    /*==================================================
    CLIENT
    ==================================================*/

    const client_name =
        $('#sold_to_client_id').val();

    const client_idx =
        $('#sold_to_client_name_list option[value="' +
        client_name +
        '"]').attr('data-id');


    /*==================================================
    ORDER TIME
    ==================================================*/

    const order_time =
        $('#order_time_miscellaneous_sales').val();


    /*==================================================
    PRODUCT / DESCRIPTION
    ==================================================*/

    const product_name =
        $('#product_idx_miscellaneous_sales').val();


    const productOption =
        $('#product_list_miscellaneous_sales option[value="' +
        product_name +
        '"]');


    const product_idx =
        productOption.attr('data-id');


    /*==================================================
    QUANTITY
    ==================================================*/

    const order_quantity =
        $('#order_quantity_miscellaneous_sales').val();


    /*==================================================
    MANUAL PRICE
    ==================================================*/

    const product_manual_price =
        $('#product_manual_price_miscellaneous_sales').val();


    /*==================================================
    FRONTEND VALIDATION
    ==================================================*/

    let hasValidationError = false;


    /*--------------------------------------------------
    MISCELLANEOUS TYPE
    --------------------------------------------------*/

    if (!miscellaneous_items_type)
    {
        $('#miscellaneous_items_typeError')
            .text('Please select a miscellaneous type.')
            .addClass('d-block');

        hasValidationError = true;
    }


    /*--------------------------------------------------
    CLIENT
    --------------------------------------------------*/

    /*
     * Client is REQUIRED only for SALES CREDIT.
     *
     * DISCOUNTS:
     * Client is NOT required.
     *
     * OTHERS:
     * Client is NOT required.
     *
     * CASHOUT:
     * Client is NOT required.
     */

    if (
        miscellaneous_items_type === 'SALES_CREDIT'
    )
    {
        if (!client_idx)
        {
            $('#sold_to_client_idError')
                .text('Please select a valid account.')
                .addClass('d-block');

            hasValidationError = true;
        }
    }


    /*--------------------------------------------------
    PRODUCT
    --------------------------------------------------*/

    /*
     * Product is REQUIRED only for:
     *
     * SALES_CREDIT
     * DISCOUNTS
     *
     * OTHERS and CASHOUT use item description.
     */

    if (
        miscellaneous_items_type === 'SALES_CREDIT' ||
        miscellaneous_items_type === 'DISCOUNTS'
    )
    {
        if (!product_idx)
        {
            $('#product_idx_miscellaneous_salesError')
                .text('Please select a valid product.')
                .addClass('d-block');

            hasValidationError = true;
        }
    }


    /*--------------------------------------------------
    ITEM DESCRIPTION
    --------------------------------------------------*/

    /*
     * OTHERS and CASHOUT use the product/description
     * field as free text.
     */

    if (
        miscellaneous_items_type === 'OTHERS' ||
        miscellaneous_items_type === 'CASHOUT'
    )
    {
        if (!product_name.trim())
        {
            $('#product_idx_miscellaneous_salesError')
                .text('Please enter an item description.')
                .addClass('d-block');

            hasValidationError = true;
        }
    }


    /*--------------------------------------------------
    QUANTITY
    --------------------------------------------------*/

    /*
     * Quantity is required for:
     *
     * SALES_CREDIT
     * DISCOUNTS
     *
     * OTHERS
     * CASHOUT
     */

    if (order_quantity === '')
    {
        $('#order_quantity_miscellaneous_salesError')
            .text('Please enter the quantity.')
            .addClass('d-block');

        hasValidationError = true;
    }


    /*--------------------------------------------------
    ORDER TIME
    --------------------------------------------------*/

    if (order_time === '' && miscellaneous_items_type === 'SALES_CREDIT')
    {
        $('#order_time_miscellaneous_salesError')
            .text('Please enter the order time.')
            .addClass('d-block');

        hasValidationError = true;
    }


    /*--------------------------------------------------
    MANUAL PRICE
    --------------------------------------------------*/

    /*
     * Amount is required for:
     *
     * OTHERS
     * CASHOUT
     *
     * For SALES_CREDIT and DISCOUNTS,
     * the backend can determine the price.
     */

    if (
        miscellaneous_items_type === 'OTHERS' ||
        miscellaneous_items_type === 'CASHOUT'
    )
    {
        if (product_manual_price === '')
        {
            $('#product_manual_price_miscellaneous_salesError')
                .text('Please enter the amount.')
                .addClass('d-block');

            hasValidationError = true;
        }
    }


    /*==================================================
    STOP SUBMISSION IF FRONTEND VALIDATION FAILED
    ==================================================*/

    if (hasValidationError)
    {
        return;
    }


    /*==================================================
    AJAX DATA
    ==================================================*/

    let ajaxData =
    {
        /*
         * Backend field names remain unchanged.
         */

        CHPH3_ID:
            CHPH3_ID,

        CashiersReportId:
            CashiersReportId,

        reference_no:
            reference_no,

        reference_no_id:
            reference_no_id,

        client_idx:
            client_idx,

        product_idx:
            product_idx,

        order_time:
            order_time,

        branch_idx:
            teves_branch,

        item_description:
            product_name,

        order_quantity:
            order_quantity,

        product_manual_price:
            product_manual_price,

        report_date:
            report_date,

        _token:
            "{{ csrf_token() }}"
    };


    /*==================================================
    ONLY INCLUDE TYPE WHEN CREATING
    ==================================================*/

    /*
     * IMPORTANT:
     *
     * When adding a new record:
     * send miscellaneous_items_type.
     *
     * When updating:
     * DO NOT send miscellaneous_items_type.
     *
     * The backend will retrieve the existing type
     * from the database.
     */

    if (CHPH3_ID == 0)
    {
        ajaxData.miscellaneous_items_type =
            miscellaneous_items_type;
    }


    /*==================================================
    SAVE MISCELLANEOUS SALES
    ==================================================*/

    $.ajax({

        url: "{{ route('SAVE_CHR_PH3') }}",

        type: "POST",

        data: ajaxData,


        /*==================================================
        BEFORE AJAX REQUEST
        ==================================================*/

        beforeSend: function()
        {
            setButtonLoading(
                '#save-miscellaneous_sales',
                true,
                CHPH3_ID == 0
                    ? 'Saving...'
                    : 'Updating...'
            );
        },


        /*==================================================
        SUCCESS
        ==================================================*/

        success: function(response)
        {
            console.log(response);


            /*----------------------------------------------
            CLOSE MODAL
            ----------------------------------------------*/

            $('#miscellaneous_sales_Modal')
                .modal('hide');


            /*----------------------------------------------
            RESET FORM
            ----------------------------------------------*/

            resetMiscellaneousSalesForm();


            /*----------------------------------------------
            RELOAD APPROPRIATE TABLE
            ----------------------------------------------*/

            /*
             * For ADD:
             * use the selected type.
             *
             * For UPDATE:
             * the type remains unchanged, so the value
             * loaded in the form is still the correct type.
             */

            if (
                miscellaneous_items_type ===
                'SALES_CREDIT'
            )
            {
                LoadCashiersReportPH3_SALES_CREDIT();
            }
            else if (
                miscellaneous_items_type ===
                'DISCOUNTS'
            )
            {
                LoadCashiersReportPH3_DISCOUNT();
            }
            else
            {
                LoadCashiersReportPH3_OTHERS();
            }


            /*----------------------------------------------
            UPDATE CASHIER'S REPORT SUMMARY
            ----------------------------------------------*/

            if (
                typeof UpdateCashiersReportSummary ===
                'function'
            )
            {
                UpdateCashiersReportSummary();
            }


            if (
                typeof LoadCashiersReportSummary ===
                'function'
            )
            {
                LoadCashiersReportSummary();
            }


            /*----------------------------------------------
            SUCCESS MESSAGE
            ----------------------------------------------*/

            if (
                typeof showSuccessModal ===
                'function'
            )
            {
                showSuccessModal(
                    response.success ||
                    (
                        CHPH3_ID == 0
                            ? 'Miscellaneous sales added successfully.'
                            : 'Miscellaneous sales updated successfully.'
                    )
                );
            }
        },


        /*==================================================
        VALIDATION / AJAX ERROR
        ==================================================*/

        error: function(xhr)
        {
            console.log(xhr);

            handleMiscellaneousSalesValidation(
                xhr,
                CHPH3_ID
            );
        },


        /*==================================================
        AJAX COMPLETE
        ==================================================*/

        complete: function()
        {
            setButtonLoading(
                '#save-miscellaneous_sales',
                false
            );
        }

    });

});

/*==================================================
CLEAR MISCELLANEOUS SALES VALIDATION
==================================================*/

function clearMiscellaneousSalesValidation()
{
    $('#miscellaneous_items_typeError')
        .text('')
        .removeClass('d-block');


    $('#sold_to_client_idError')
        .text('')
        .removeClass('d-block');


    $('#reference_no_miscellaneous_salesError')
        .text('')
        .removeClass('d-block');


    $('#order_time_miscellaneous_salesError')
        .text('')
        .removeClass('d-block');


    $('#product_idx_miscellaneous_salesError')
        .text('')
        .removeClass('d-block');


    $('#order_quantity_miscellaneous_salesError')
        .text('')
        .removeClass('d-block');


    $('#product_manual_price_miscellaneous_salesError')
        .text('')
        .removeClass('d-block');
}

/*==================================================
HANDLE MISCELLANEOUS SALES VALIDATION
==================================================*/

function handleMiscellaneousSalesValidation(xhr, CHPH3_ID)
{
    const errors =
        xhr.responseJSON?.errors || {};


    /*==================================================
    MISCELLANEOUS TYPE
    ==================================================*/

    if (errors.miscellaneous_items_type)
    {
        let message =
            Array.isArray(errors.miscellaneous_items_type)
                ? errors.miscellaneous_items_type[0]
                : errors.miscellaneous_items_type;


        /*==================================================
        UPDATE RECORD
        ==================================================*/

        if (CHPH3_ID != 0)
        {
            message =
                'Miscellaneous Type cannot be changed during update.';
        }


        $('#miscellaneous_items_typeError')
            .text(message)
            .addClass('d-block');
    }


    /*==================================================
    CLIENT
    ==================================================*/

    if (errors.client_idx)
    {
        $('#sold_to_client_idError')
            .text(
                Array.isArray(errors.client_idx)
                    ? errors.client_idx[0]
                    : errors.client_idx
            )
            .addClass('d-block');
    }


    /*==================================================
    REFERENCE NUMBER
    ==================================================*/

    if (errors.reference_no)
    {
        $('#reference_no_miscellaneous_salesError')
            .text(
                Array.isArray(errors.reference_no)
                    ? errors.reference_no[0]
                    : errors.reference_no
            )
            .addClass('d-block');
    }


    /*==================================================
    ORDER TIME
    ==================================================*/

    if (errors.order_time)
    {
        $('#order_time_miscellaneous_salesError')
            .text(
                Array.isArray(errors.order_time)
                    ? errors.order_time[0]
                    : errors.order_time
            )
            .addClass('d-block');
    }


    /*==================================================
    PRODUCT
    ==================================================*/

    if (errors.product_idx)
    {
        let message =
            Array.isArray(errors.product_idx)
                ? errors.product_idx[0]
                : errors.product_idx;


        /*
        If backend says product is required
        but the user entered an invalid product name.
        */

        if (
            message ===
            'Item Description or Product is Required'
            &&
            product_name !== ''
        )
        {
            message =
                'Incorrect Product Name: ' +
                product_name;

            $('#product_idx_miscellaneous_sales')
                .val('');
        }


        $('#product_idx_miscellaneous_salesError')
            .text(message)
            .addClass('d-block');
    }


    /*==================================================
    QUANTITY
    ==================================================*/

    if (errors.order_quantity)
    {
        $('#order_quantity_miscellaneous_salesError')
            .text(
                Array.isArray(errors.order_quantity)
                    ? errors.order_quantity[0]
                    : errors.order_quantity
            )
            .addClass('d-block');
    }


    /*==================================================
    MANUAL PRICE
    ==================================================*/

    if (errors.product_manual_price)
    {
        $('#product_manual_price_miscellaneous_salesError')
            .text(
                Array.isArray(errors.product_manual_price)
                    ? errors.product_manual_price[0]
                    : errors.product_manual_price
            )
            .addClass('d-block');
    }


    /*==================================================
    GENERAL ERROR
    ==================================================*/

    if (
        Object.keys(errors).length === 0
    )
    {
        if (
            typeof showValidationErrorModal === 'function'
        )
        {
            showValidationErrorModal(
                xhr.responseJSON?.message ||
                'Unable to save miscellaneous sales.'
            );
        }
    }
}	

function TotalAmount_miscellaneous_sales()
{
    let CashiersReportId = {{ $CashiersReportId }};

    // ==========================================
    // MISCELLANEOUS TYPE
    // ==========================================

    let miscellaneous_items_type =
        $("#miscellaneous_items_type").val();


    // ==========================================
    // GET PRODUCT INFORMATION
    // ==========================================

    let product_name =
        $('#product_idx_miscellaneous_sales').val();

    let productOption =
        $('#product_list_miscellaneous_sales option[value="' +
        product_name +
        '"]');

    let product_id =
        productOption.attr('data-id');

    let product_price =
        productOption.attr('data-price');


    // ==========================================
    // GET PRICE & QUANTITY
    // ==========================================

    let product_manual_price =
        $("#product_manual_price_miscellaneous_sales").val();

    let order_quantity =
        $("#order_quantity_miscellaneous_sales").val();


    // ==========================================
    // VALIDATE QUANTITY
    // ==========================================

    if (
        order_quantity === '' ||
        order_quantity === null ||
        parseFloat(order_quantity) <= 0
    )
    {
        $('#pump_price_miscellaneous_sales')
            .text('0.00');

        $('#discounted_price_miscellaneous_sales')
            .text('0.00');

        $('#TotalAmount_miscellaneous_sales')
            .text('0.00');

        return;
    }


    // ==========================================
    // GET PUMP PRICE
    // ==========================================

    $.ajax({

        url: "{{ route('FuelSalesInformation') }}",

        type: "POST",

        data:
        {
            CashiersReportId:
                CashiersReportId,

            CHPH1_ID: 0,

            product_id:
                product_id,

            _token:
                "{{ csrf_token() }}"
        },

        success: function(response)
        {
            console.log(response);


            // ==========================================
            // DEFAULT VALUES
            // ==========================================

            let pump_price = 0;
            let discounted_price = 0;
            let total_amount = 0;


            // ==========================================
            // GET PUMP PRICE FROM RESPONSE
            // ==========================================

            if (
                response &&
                response.length > 0
            )
            {
                pump_price =
                    parseFloat(response[0].product_price) || 0;
            }
            else
            {
                /*
                 * If no pump price was returned,
                 * use the product selling price.
                 */

                pump_price =
                    parseFloat(product_price) || 0;
            }


            // ==========================================
            // GET MANUAL PRICE
            // ==========================================

            let manual_price =
                parseFloat(product_manual_price) || 0;

            let quantity =
                parseFloat(order_quantity) || 0;


            // ==========================================
            // DISCOUNTS
            // ==========================================

            if (
                miscellaneous_items_type === 'DISCOUNTS'
            )
            {
                /*
                 * Discounted price =
                 * Pump Price - Discount Amount
                 */

                if (manual_price > 0)
                {
                    discounted_price =
                        pump_price - manual_price;
                }
                else
                {
                    discounted_price =
                        pump_price;
                }


                /*
                 * Total =
                 * Discounted Price × Quantity
                 */

                total_amount =
                    discounted_price * quantity;
            }


            // ==========================================
            // SALES CREDIT / OTHERS
            // ==========================================

            else
            {
                /*
                 * If manual price is entered,
                 * use it as the unit amount.
                 *
                 * Otherwise use the pump/product price.
                 */

                if (manual_price > 0)
                {
                    total_amount =
                        manual_price * quantity;
                }
                else
                {
                    total_amount =
                        pump_price * quantity;
                }
            }


            // ==========================================
            // DISPLAY PUMP PRICE
            // ==========================================

            $('#pump_price_miscellaneous_sales')
                .text(
                    pump_price.toLocaleString(
                        "en-PH",
                        {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }
                    )
                );


            // ==========================================
            // DISPLAY DISCOUNTED PRICE
            // ==========================================

            $('#discounted_price_miscellaneous_sales')
                .text(
                    discounted_price.toLocaleString(
                        "en-PH",
                        {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }
                    )
                );


            // ==========================================
            // DISPLAY TOTAL AMOUNT
            // ==========================================

            $('#TotalAmount_miscellaneous_sales')
                .text(
                    total_amount.toLocaleString(
                        "en-PH",
                        {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }
                    )
                );
        },


        // ==========================================
        // AJAX ERROR
        // ==========================================

        error: function(xhr)
        {
            console.log(
                'Error getting pump price:',
                xhr
            );

            $('#pump_price_miscellaneous_sales')
                .text('0.00');

            $('#discounted_price_miscellaneous_sales')
                .text('0.00');

            $('#TotalAmount_miscellaneous_sales')
                .text('0.00');
        }

    });
}

/*==================================================
RESET MISCELLANEOUS SALES FORM
==================================================*/

function resetMiscellaneousSalesForm()
{
    const form =
        $('#miscellaneous_sales_form')[0];


    /*----------------------------------------------
    RESET FORM
    ----------------------------------------------*/

    if (form)
    {
        form.reset();
    }


    /*----------------------------------------------
    CLEAR VALIDATION
    ----------------------------------------------*/

    clearMiscellaneousSalesValidation();


    $('#miscellaneous_sales_form')
        .removeClass('was-validated');


    /*----------------------------------------------
    RESET MISCELLANEOUS TYPE
    ----------------------------------------------*/

    $('#miscellaneous_items_type')
        .val('');


    /*----------------------------------------------
    RESET CLIENT
    ----------------------------------------------*/

    $('#sold_to_client_id')
        .val('');


    /*----------------------------------------------
    RESET REFERENCE NUMBER
    ----------------------------------------------*/

    $('#reference_no_miscellaneous_sales')
        .val('');


    /*----------------------------------------------
    RESET ORDER TIME
    ----------------------------------------------*/

    $('#order_time_miscellaneous_sales')
        .val('');


    /*----------------------------------------------
    RESET PRODUCT
    ----------------------------------------------*/

    $('#product_idx_miscellaneous_sales')
        .val('');


    /*----------------------------------------------
    RESET QUANTITY
    ----------------------------------------------*/

    $('#order_quantity_miscellaneous_sales')
        .val('');


    /*----------------------------------------------
    RESET MANUAL PRICE
    ----------------------------------------------*/

    $('#product_manual_price_miscellaneous_sales')
        .val('');


    /*----------------------------------------------
    RESET PRICE DISPLAY
    ----------------------------------------------*/

    $('#pump_price_miscellaneous_sales')
        .text('0.00');


    $('#discounted_price_miscellaneous_sales')
        .text('0.00');


    $('#TotalAmount_miscellaneous_sales')
        .text('0.00');


    /*----------------------------------------------
    RESET FIELD STATES
    ----------------------------------------------*/

    $('#reference_no_miscellaneous_sales')
        .prop('disabled', false);

    $('#product_idx_miscellaneous_sales')
        .prop('disabled', false);

    $('#order_quantity_miscellaneous_sales')
        .prop('disabled', false);

    $('#product_manual_price_miscellaneous_sales')
        .prop('disabled', false);


    /*----------------------------------------------
    RESET LABELS
    ----------------------------------------------*/

    $('#quantity_label_miscellaneous_sales')
        .text('Quantity');

    $('#manual_price_label_miscellaneous_sales')
        .text('Unit Price');


    /*----------------------------------------------
    RESET SAVE BUTTON
    ----------------------------------------------*/

    $('#save-miscellaneous_sales')
        .prop('disabled', false)
        .html(
            '<i class="bi bi-save-fill me-1"></i>' +
            'Save'
        );


    /*----------------------------------------------
    RESET BUTTON LOADING STATE
    ----------------------------------------------*/

    if (
        typeof setButtonLoading === 'function'
    )
    {
        setButtonLoading(
            '#save-miscellaneous_sales',
            false
        );
    }
}



/*==================================================
    EDIT MISCELLANEOUS SALES - SALES CREDIT
==================================================*/
$('body').on('click', '#CHPH3_Edit_SALES_CREDIT', function(event)
{
    event.preventDefault();

    let CHPH3_ID = $(this).data('id');

    if (!CHPH3_ID) {
        showValidationErrorModal('Invalid Sales Credit record selected.');
        return;
    }

    /*==================================================
        CLEAR PREVIOUS FORM / VALIDATION
    ==================================================*/

    resetMiscellaneousSalesForm();

    /*==================================================
        GET SALES CREDIT INFORMATION
    ==================================================*/

    $.ajax({
        url: "{{ route('CRP3_info_SALES_CREDIT') }}",
        type: "POST",

        data: {
            CHPH3_ID: CHPH3_ID,
            _token: "{{ csrf_token() }}"
        },

        beforeSend: function()
        {
            /*
             * Optional loading state
             */
        },

        success: function(response)
        {
            console.log(response);

            if (!response || !response.length) {
                showValidationErrorModal(
                    'Unable to retrieve the selected Sales Credit record.'
                );
                return;
            }

            const data = response[0];


            /*==================================================
                STORE RECORD ID FOR UPDATE
            ==================================================*/

            /*
             * Your save script uses:
             *
             * CHPH3_ID: 0
             *
             * For editing, this should contain the
             * existing CHPH3_ID.
             */

            $('#save-miscellaneous_sales')
                .val(CHPH3_ID);


            /*==================================================
                MISCELLANEOUS TYPE
            ==================================================*/

            $('#miscellaneous_items_type').val(
                data.miscellaneous_items_type || ''
            );


            /*==================================================
                CLIENT
            ==================================================*/

            $('#sold_to_client_id').val(
                data.client_name || ''
            );


            /*==================================================
                REFERENCE NUMBER
            ==================================================*/

            $('#reference_no_miscellaneous_sales').val(
                data.reference_no || ''
            );


            /*==================================================
                LOAD CLIENT REFERENCE LIST
            ==================================================*/

            let client_idx = data.client_idx || '';



            /*==================================================
                ORDER TIME
            ==================================================*/

            $('#order_time_miscellaneous_sales').val(
                data.order_time || ''
            );


            /*==================================================
                PRODUCT
            ==================================================*/
			const editProductIdx =
				data.product_idx || '';

			const editProductName =
				data.product_name || '';

            /*==================================================
                QUANTITY
            ==================================================*/

            $('#order_quantity_miscellaneous_sales').val(
                data.order_quantity || ''
            );


            /*==================================================
                MANUAL / UNIT PRICE
            ==================================================*/

            $('#product_manual_price_miscellaneous_sales').val(
                data.unit_price || ''
            );



            if (client_idx)
			{
				load_so_reference_no(client_idx);

				LoadSellingPriceList(
					client_idx,
					function()
					{
						/*
						==========================================
						SELECT EXISTING PRODUCT
						==========================================
						*/

						const productOption =
							$('#product_list_miscellaneous_sales option[data-id="' +
							editProductIdx +
							'"]');


						if (productOption.length)
						{
							$('#product_idx_miscellaneous_sales')
								.val(productOption.val())
								.trigger('change');
						}
						else
						{
							console.warn(
								'Product ID not found in selling price list:',
								editProductIdx
							);

							$('#product_idx_miscellaneous_sales')
								.val(editProductName);
						}
					}
				);
			}
			else
			{
				$('#product_idx_miscellaneous_sales')
					.val(editProductName);
			}

            /*==================================================
                UPDATE INPUT SETTINGS
            ==================================================*/

            if (
                typeof input_settings_create_miscellaneous_sales === 'function'
            ) {
                input_settings_create_miscellaneous_sales();
            }


            /*==================================================
                PUMP PRICE
            ==================================================*/

            let pump_price =
                parseFloat(data.pump_price) || 0;

            $('#pump_price_miscellaneous_sales').text(
                pump_price.toLocaleString("en-PH", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                })
            );


            /*==================================================
                DISCOUNTED PRICE
            ==================================================*/

            let discounted_price =
                parseFloat(data.discounted_price) || 0;

            $('#discounted_price_miscellaneous_sales').text(
                discounted_price.toLocaleString("en-PH", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                })
            );


            /*==================================================
                TOTAL AMOUNT
            ==================================================*/

            let total_amount =
                parseFloat(data.order_total_amount) || 0;

            $('#TotalAmount_miscellaneous_sales').text(
                total_amount.toLocaleString("en-PH", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                })
            );


            /*==================================================
                CHANGE BUTTON TO UPDATE MODE
            ==================================================*/

            $('#save-miscellaneous_sales')
                .data('CHPH3_ID', CHPH3_ID)
                .html('<i class="bi bi-save-fill me-1"></i>Update');


            /*==================================================
                SHOW MODAL
            ==================================================*/

            $('#miscellaneous_sales_Modal').modal('show');
        },

        error: function(xhr)
        {
            console.log(xhr);

            let message =
                'Unable to load the selected Sales Credit record.';

            if (
                xhr.responseJSON &&
                xhr.responseJSON.message
            ) {
                message = xhr.responseJSON.message;
            }

            showValidationErrorModal(message);
        }
    });
});

$('body').on('click', '#CHPH3_Edit_DISCOUNT', function(event)
{
    event.preventDefault();

    let CHPH3_ID = $(this).data('id');

    if (!CHPH3_ID) {
        showValidationErrorModal('Invalid Discount record selected.');
        return;
    }

    /*==================================================
        RESET FORM
    ==================================================*/

    resetMiscellaneousSalesForm();


    /*==================================================
        GET DISCOUNT INFORMATION
    ==================================================*/

    $.ajax({
        url: "{{ route('CRP3_info_DISCOUNT') }}",
        type: "POST",

        data: {
            CHPH3_ID: CHPH3_ID,
            _token: "{{ csrf_token() }}"
        },

        success: function(response)
        {
            console.log(response);

            if (!response || !response.length) {
                showValidationErrorModal(
                    'Unable to retrieve the selected Discount record.'
                );
                return;
            }

            const data = response[0];


            /*==================================================
                STORE RECORD ID FOR UPDATE
            ==================================================*/

            $('#save-miscellaneous_sales')
                .val(CHPH3_ID);


            /*==================================================
                MISCELLANEOUS TYPE
            ==================================================*/

            $('#miscellaneous_items_type').val(
                data.miscellaneous_items_type || ''
            );


            /*==================================================
                REFERENCE NUMBER
            ==================================================*/

            $('#reference_no_miscellaneous_sales').val(
                data.reference_no || ''
            );


            /*==================================================
                PRODUCT
            ==================================================*/

            $('#product_idx_miscellaneous_sales').val(
                data.product_name || ''
            );


            /*==================================================
                QUANTITY
            ==================================================*/

            $('#order_quantity_miscellaneous_sales').val(
                data.order_quantity || ''
            );


            /*==================================================
                UNIT / MANUAL PRICE
            ==================================================*/

            $('#product_manual_price_miscellaneous_sales').val(
                data.unit_price || ''
            );


            /*==================================================
                UPDATE INPUT SETTINGS
            ==================================================*/

            input_settings_create_miscellaneous_sales();


            /*==================================================
                PUMP PRICE
            ==================================================*/

            let pump_price =
                parseFloat(data.pump_price) || 0;

            $('#pump_price_miscellaneous_sales').text(
                pump_price.toLocaleString("en-PH", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                })
            );


            /*==================================================
                DISCOUNTED PRICE
            ==================================================*/

            let discounted_price =
                parseFloat(data.discounted_price) || 0;

            $('#discounted_price_miscellaneous_sales').text(
                discounted_price.toLocaleString("en-PH", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                })
            );


            /*==================================================
                TOTAL AMOUNT
            ==================================================*/

            let total_amount =
                parseFloat(data.order_total_amount) || 0;

            $('#TotalAmount_miscellaneous_sales').text(
                total_amount.toLocaleString("en-PH", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                })
            );

			LoadSellingPriceList_branch();
            /*==================================================
                CHANGE BUTTON TO UPDATE MODE
            ==================================================*/

            $('#save-miscellaneous_sales')
                .html('<i class="bi bi-save-fill me-1"></i>Update');

			LoadSellingPriceList(0);
			
            /*==================================================
                SHOW MODAL
            ==================================================*/

            $('#miscellaneous_sales_Modal').modal('show');
        },

        error: function(xhr)
        {
            console.log(xhr);

            let message =
                'Unable to load the selected Discount record.';

            if (
                xhr.responseJSON &&
                xhr.responseJSON.message
            ) {
                message = xhr.responseJSON.message;
            }

            showValidationErrorModal(message);
        }
    });
});
 
/*==================================================
EDIT MISCELLANEOUS SALES - OTHERS
==================================================*/

$('body').on('click', '#CHPH3_Edit_OTHERS', function(event)
{
    event.preventDefault();

    const CHPH3_ID = $(this).data('id');

    if (!CHPH3_ID)
    {
        showValidationErrorModal(
            'Invalid Other record selected.'
        );

        return;
    }

    /*==================================================
    RESET FORM
    ==================================================*/

    resetMiscellaneousSalesForm();


    /*==================================================
    LOAD RECORD
    ==================================================*/

    $.ajax({
        url: "{{ route('CRP3_info_OTHERS') }}",

        type: "POST",

        dataType: "json",

        data:
        {
            CHPH3_ID: CHPH3_ID,
            _token: "{{ csrf_token() }}"
        },

        success: function(response)
        {
            console.log('OTHERS EDIT:', response);

            if (
                !Array.isArray(response) ||
                response.length === 0
            )
            {
                showValidationErrorModal(
                    'Unable to retrieve the selected Other record.'
                );

                return;
            }

            const data = response[0];


            /*==================================================
            STORE ID FOR UPDATE
            ==================================================*/

            $('#save-miscellaneous_sales')
                .val(CHPH3_ID);


            /*==================================================
            SET TRANSACTION TYPE
            ==================================================*/

            $('#miscellaneous_items_type').val(
                data.miscellaneous_items_type || 'OTHERS'
            );


            /*==================================================
            APPLY INPUT SETTINGS
            ==================================================*/

            input_settings_create_miscellaneous_sales();


            /*==================================================
            REFERENCE NUMBER
            ==================================================*/

            $('#reference_no_miscellaneous_sales').val(
                data.reference_no || ''
            );


            /*==================================================
            ITEM DESCRIPTION
            ==================================================*/

            $('#product_idx_miscellaneous_sales').val(
                data.item_description || ''
            );


            /*==================================================
            QUANTITY
            ==================================================*/

            $('#order_quantity_miscellaneous_sales').val(
                data.order_quantity || ''
            );


            /*==================================================
            UNIT PRICE / AMOUNT
            ==================================================*/

            $('#product_manual_price_miscellaneous_sales').val(
                data.unit_price || ''
            );


            /*==================================================
            TOTAL AMOUNT
            ==================================================*/

            const totalAmount =
                parseFloat(data.order_total_amount) || 0;

            $('#TotalAmount_miscellaneous_sales').text(
                totalAmount.toLocaleString(
                    "en-PH",
                    {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                )
            );


            /*==================================================
            OTHERS DOES NOT USE PUMP PRICE
            ==================================================*/

            $('#pump_price_miscellaneous_sales')
                .text('0.00');

            $('#discounted_price_miscellaneous_sales')
                .text('0.00');

			
			LoadSellingPriceList_branch();
			
            /*==================================================
            CHANGE BUTTON TO UPDATE
            ==================================================*/

            $('#save-miscellaneous_sales')
                .html(
                    '<i class="bi bi-save-fill me-1"></i>Update'
                );


            /*==================================================
            SHOW MODAL
            ==================================================*/

            $('#miscellaneous_sales_Modal')
                .modal('show');
        },

        error: function(xhr)
        {
            console.log(xhr);

            showValidationErrorModal(
                xhr.responseJSON?.message ||
                'Unable to load the selected Other record.'
            );
        }
    });
});

/*==================================================
FORMAT SALES CREDIT AMOUNT
==================================================*/

function formatSalesCreditAmount(amount)
{
    return (parseFloat(amount) || 0).toLocaleString(
        "en-PH",
        {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }
    );
}

/*==================================================
DELETE MISCELLANEOUS SALES
SALES CREDIT / DISCOUNT / OTHERS
==================================================*/

$('body').on(
    'click',
    '#deleteCashiersProductP3_SALES_CREDIT, ' +
    '#deleteCashiersProductP3_DISCOUNT, ' +
    '#deleteCashiersProductP3_OTHERS',
    function(event)
{
    event.preventDefault();

    const CHPH3_ID = $(this).data('id');
    const deleteType = $(this).attr('id');

    if (!CHPH3_ID)
    {
        showValidationErrorModal(
            'Invalid record selected.'
        );

        return;
    }

    /*==================================================
    DETERMINE TYPE
    ==================================================*/

    let infoRoute = '';
    let recordType = '';

    if (deleteType === 'deleteCashiersProductP3_SALES_CREDIT')
    {
        infoRoute = "{{ route('CRP3_info_SALES_CREDIT') }}";
        recordType = 'SALES_CREDIT';
    }
    else if (deleteType === 'deleteCashiersProductP3_DISCOUNT')
    {
        infoRoute = "{{ route('CRP3_info_DISCOUNT') }}";
        recordType = 'DISCOUNTS';
    }
    else if (deleteType === 'deleteCashiersProductP3_OTHERS')
    {
        infoRoute = "{{ route('CRP3_info_OTHERS') }}";
        recordType = 'OTHERS';
    }

    if (!infoRoute)
    {
        showValidationErrorModal(
            'Unable to determine the selected transaction type.'
        );

        return;
    }

    /*==================================================
    LOAD RECORD INFORMATION
    ==================================================*/

    $.ajax({
        url: infoRoute,
        type: "POST",
        dataType: "json",

        data:
        {
            CHPH3_ID: CHPH3_ID,
            _token: "{{ csrf_token() }}"
        },

        success: function(response)
        {
            console.log(response);

            if (
                !Array.isArray(response) ||
                response.length === 0
            )
            {
                showValidationErrorModal(
                    'Unable to load the selected record.'
                );

                return;
            }

            const data = response[0];

            /*==================================================
            STORE DELETE INFORMATION
            ==================================================*/

            $('#deleteCRPH3Confirmed')
                .data('id', CHPH3_ID)
                .data('type', recordType);

            /*==================================================
            SET MODAL TITLE
            ==================================================*/

            if (recordType === 'SALES_CREDIT')
            {
                $('#CRPH3DeleteModalLabel')
                    .text('Delete Sales Credit');

                $('#CRPH3DeleteModalDescription')
                    .text(
                        'Please confirm the deletion of this Sales Credit transaction.'
                    );
            }
            else if (recordType === 'DISCOUNTS')
            {
                $('#CRPH3DeleteModalLabel')
                    .text('Delete Discount');

                $('#CRPH3DeleteModalDescription')
                    .text(
                        'Please confirm the deletion of this Discount transaction.'
                    );
            }
            else if (recordType === 'OTHERS')
            {
                $('#CRPH3DeleteModalLabel')
                    .text('Delete Other Transaction');

                $('#CRPH3DeleteModalDescription')
                    .text(
                        'Please confirm the deletion of this Other transaction.'
                    );
            }

            /*==================================================
            COMMON INFORMATION
            ==================================================*/

            $('#delete_reference_no_PH3')
                .text(data.reference_no || '-');

            $('#delete_product_idx_PH3')
                .text(
                    data.product_name ||
                    data.item_description ||
                    '-'
                );

            $('#delete_order_quantity_PH3')
                .text(
                    formatMiscDeleteNumber(
                        data.order_quantity
                    )
                );

            /*==================================================
            UNIT PRICE
            ==================================================*/

            let unitPrice =
                data.unit_price ??
                data.product_price ??
                0;

            $('#delete_product_manual_price_PH3')
                .text(
                    formatMiscDeleteAmount(unitPrice)
                );

            /*==================================================
            TOTAL AMOUNT
            ==================================================*/

            $('#delete_TotalAmount_PH3')
                .text(
                    formatMiscDeleteAmount(
                        data.order_total_amount
                    )
                );

            /*==================================================
            TYPE-SPECIFIC DISPLAY
            ==================================================*/

            if (recordType === 'DISCOUNTS')
            {
                $('#delete_discounted_price_row')
                    .removeClass('d-none');

                $('#delete_discounted_price_PH3')
                    .text(
                        formatMiscDeleteAmount(
                            data.discounted_price
                        )
                    );
            }
            else
            {
                $('#delete_discounted_price_row')
                    .addClass('d-none');

                $('#delete_discounted_price_PH3')
                    .text('0.00');
            }

            /*==================================================
            SHOW MODAL
            ==================================================*/

            $('#CRPH3DeleteModal').modal('show');
        },

        error: function(xhr)
        {
            console.log(xhr);

            showValidationErrorModal(
                xhr.responseJSON?.message ||
                'Unable to load the selected record.'
            );
        }
    });
});


/*==================================================
CONFIRM DELETE
==================================================*/

$('body').on(
    'click',
    '#deleteCRPH3Confirmed',
    function(event)
{
    event.preventDefault();

    const button = $(this);

    const CHPH3_ID = button.data('id');
    const recordType = button.data('type');

    if (!CHPH3_ID || !recordType)
    {
        showValidationErrorModal(
            'Invalid record selected for deletion.'
        );

        return;
    }

    /*==================================================
    DETERMINE DELETE ROUTE
    ==================================================*/

    let deleteRoute = '';

    if (recordType === 'SALES_CREDIT')
    {
        deleteRoute =
            "{{ route('DeleteCashiersProductP3') }}";
    }
    else if (recordType === 'DISCOUNTS')
    {
        deleteRoute =
            "{{ route('DeleteCashiersProductP3') }}";
    }
    else if (recordType === 'OTHERS')
    {
        deleteRoute =
            "{{ route('DeleteCashiersProductP3') }}";
    }

    if (!deleteRoute)
    {
        showValidationErrorModal(
            'Unable to determine the delete action.'
        );

        return;
    }

    /*==================================================
    DELETE
    ==================================================*/

    $.ajax({
        url: deleteRoute,

        type: "POST",

        data:
        {
            CHPH3_ID: CHPH3_ID,
            _token: "{{ csrf_token() }}"
        },

        beforeSend: function()
        {
            button
                .prop('disabled', true)
                .html(`
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    Deleting...
                `);
        },

        success: function(response)
        {
            $('#CRPH3DeleteModal').modal('hide');

            /*==================================================
            RELOAD CORRESPONDING TABLE
            ==================================================*/

            if (recordType === 'SALES_CREDIT')
            {
                LoadCashiersReportPH3_SALES_CREDIT();
            }
            else if (recordType === 'DISCOUNTS')
            {
                LoadCashiersReportPH3_DISCOUNT();
            }
            else if (recordType === 'OTHERS')
            {
                LoadCashiersReportPH3_OTHERS();
            }

            /*==================================================
            RELOAD SUMMARY
            ==================================================*/

            if (
                typeof UpdateCashiersReportSummary ===
                'function'
            )
            {
                UpdateCashiersReportSummary();
            }

            if (
                typeof LoadCashiersReportSummary ===
                'function'
            )
            {
                LoadCashiersReportSummary();
            }

            /*==================================================
            SUCCESS MESSAGE
            ==================================================*/

            if (typeof showSuccessModal === 'function')
            {
                let message =
                    response.success ||
                    'Transaction deleted successfully.';

                showSuccessModal(message);
            }
        },

        error: function(xhr)
        {
            console.log(xhr);

            showValidationErrorModal(
                xhr.responseJSON?.message ||
                'Unable to delete the selected transaction.'
            );
        },

        complete: function()
        {
            button
                .prop('disabled', false)
                .html(`
                    <i class="bi bi-trash3-fill me-2"></i>
                    Confirm Delete
                `);
        }
    });
});


/*==================================================
FORMAT NUMBER
==================================================*/

function formatMiscDeleteNumber(value)
{
    return (parseFloat(value) || 0).toLocaleString(
        "en-PH",
        {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }
    );
}


/*==================================================
FORMAT AMOUNT
==================================================*/

function formatMiscDeleteAmount(value)
{
    return (parseFloat(value) || 0).toLocaleString(
        "en-PH",
        {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }
    );
}
</script>