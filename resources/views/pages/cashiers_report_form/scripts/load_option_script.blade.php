<script type="text/javascript">	

/*==================================================
CASHIER'S REPORT - PH1
FUEL SALES
FUEL PRODUCT CHANGE EVENT
==================================================*/

$(document).on('change', '#fuel_product', function ()
{
    /*==================================================
    RECALCULATE TOTAL
    ==================================================*/

    calculateFuelSalesTotal();


    /*==================================================
    LOAD AVAILABLE TANKS
    ==================================================*/

    LoadProductTank('fuelsales');


    /*==================================================
    LOAD AVAILABLE PUMPS
    ==================================================*/

    LoadProductPump('fuelsales');
});



/*==================================================
LOAD PUMPS FOR FUEL SALES
==================================================*/

function LoadProductPump(inventory_mode, selectedPump = '')
{
    const branchID =
       {{ $CashiersReportData[0]['teves_branch'] }};

    const fuelProduct = $('#fuel_product').val();

    const productID =
        $('#fuelProductList option[value="' + fuelProduct + '"]')
            .attr('data-id');


    /*==================================================
    CLEAR PUMP LIST
    ==================================================*/

    $('#fuelPumpList').empty();


    if (!branchID || !productID)
    {
        $('#fuel_pump').val('');
        return;
    }


    /*==================================================
    LOAD PUMPS
    ==================================================*/

    $.ajax({
        url: "{{ route('ProductPumpPerBranch') }}",

        type: "POST",

        data:
        {
            branchID: branchID,
            productID: productID,
            _token: "{{ csrf_token() }}"
        },

        beforeSend: function ()
        {
            $('#fuel_pump').prop('disabled', true);
        },

        success: function (response)
        {
            if (Array.isArray(response))
            {
                response.forEach(function (pump)
                {
                    $('#fuelPumpList').append(
                        $('<option>', {
                            value: pump.pump_name,
                            label: pump.pump_name,
                            'data-id': pump.pump_id
                        })
                    );
                });
            }


            /*==================================================
            RESTORE SELECTED PUMP
            ==================================================*/

            if (selectedPump)
            {
                $('#fuel_pump').val(selectedPump);
            }
        },

        error: function (xhr)
        {
            console.log(xhr);
        },

        complete: function ()
        {
            $('#fuel_pump').prop('disabled', false);
        }
    });
}
/*==================================================
LOAD TANKS FOR FUEL SALES
==================================================*/

function LoadProductTank(inventory_mode, selectedTank = '')
{
    const branchID =
       {{ $CashiersReportData[0]['teves_branch'] }};

    const fuelProduct = $('#fuel_product').val();

    const productID =
        $('#fuelProductList option[value="' + fuelProduct + '"]')
            .attr('data-id');


    /*==================================================
    CLEAR TANK LIST
    ==================================================*/

    $('#fuelTankList').empty();


    if (!branchID || !productID)
    {
        $('#fuel_tank').val('');
        return;
    }


    /*==================================================
    LOAD TANKS
    ==================================================*/

    $.ajax({
        url: "{{ route('ProductTankPerBranch') }}",

        type: "POST",

        data:
        {
            branchID: branchID,
            productID: productID,
            _token: "{{ csrf_token() }}"
        },

        beforeSend: function ()
        {
            $('#fuel_tank').prop('disabled', true);
        },

        success: function (response)
        {
            if (Array.isArray(response))
            {
                response.forEach(function (tank)
                {
                    $('#fuelTankList').append(
                        $('<option>', {
                            value: tank.tank_name,
                            label: tank.tank_name,
                            'data-id': tank.tank_id
                        })
                    );
                });
            }


            /*==================================================
            RESTORE SELECTED TANK
            ==================================================*/

            if (selectedTank)
            {
                $('#fuel_tank').val(selectedTank);
            }
        },

        error: function (xhr)
        {
            console.log(xhr);
        },

        complete: function ()
        {
            $('#fuel_tank').prop('disabled', false);
        }
    });
}</script>