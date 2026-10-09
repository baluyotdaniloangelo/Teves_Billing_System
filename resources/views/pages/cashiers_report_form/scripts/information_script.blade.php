
<script type="text/javascript">
/*==================================================
CASHIER REPORT INFORMATION
==================================================*/

(function () {
    const overview = document.getElementById('cashierReportOverview');
    const label = document.getElementById('cashierReportOverviewLabel');
    const icon = document.getElementById('cashierReportOverviewIcon');

    if (!overview || !label || !icon) return;

    overview.addEventListener('show.bs.collapse', function () {
        label.textContent = 'Hide Report Details';
        icon.classList.remove('bi-chevron-down');
        icon.classList.add('bi-chevron-up');
    });

    overview.addEventListener('hide.bs.collapse', function () {
        label.textContent = 'Show Report Details';
        icon.classList.remove('bi-chevron-up');
        icon.classList.add('bi-chevron-down');
    });
})();

LoadCashierReportInfo();


function LoadCashierReportInfo()
{
    const CashiersReportID = 
        {{ $CashiersReportId }};


    $.ajax({

        url:
            "/cashiers_report_info",

        type:
            "POST",

        dataType:
            "json",

        data:
        {
            CashiersReportID:
                CashiersReportID,

            _token:
                "{{ csrf_token() }}"
        },


        /*==================================================
        SUCCESS
        ==================================================*/

        success: function(response)
        {
            console.log(response);


            /*================================================
            CHECK RESPONSE
            =================================================*/

            if(
                !Array.isArray(response) ||
                response.length === 0
            )
            {
                console.error(
                    "Cashier Report not found."
                );

                return;
            }


            const report =
                response[0];


            /*================================================
            HELPER : SET VALUE
            =================================================*/

            const setValue =
                (id, value = '') =>
                {
                    const el =
                        document.getElementById(id);


                    if(el)
                    {
                        el.value =
                            value ?? '';
                    }
                    else
                    {
                        console.warn(
                            `Element not found: ${id}`
                        );
                    }
                };


            /*================================================
            HELPER : SET TEXT
            =================================================*/

            const setText =
                (id, value = '') =>
                {
                    const el =
                        document.getElementById(id);


                    if(el)
                    {
                        el.textContent =
                            value ?? '';
                    }
                    else
                    {
                        console.warn(
                            `Element not found: ${id}`
                        );
                    }
                };


            /*================================================
            CASHIER REPORT FORM
            =================================================*/

            setValue(
                'teves_branch',
                report.teves_branch
            );


            setValue(
                'cashiers_name',
                report.cashiers_name
            );


            setValue(
                'forecourt_attendant',
                report.forecourt_attendant
            );


            setValue(
                'report_date',
                report.report_date ? String(report.report_date).slice(0, 10) : ''
            );


            setValue(
                'shift',
                report.shift
            );


            setValue(
                'cashier_report_remarks',
                report.cashier_report_remarks
            );


			/*================================================
			CASHIER REPORT INFORMATION
			================================================*/

			setText(
				'cashier_info_date',
				report.report_date
			);


			setText(
				'cashier_info_branch_name',
				report.branch_code
			);


			setText(
				'cashier_info_cashiers_name',
				report.cashiers_name
			);


			setText(
				'cashier_info_forecourt_attendant',
				report.forecourt_attendant
			);


			setText(
				'cashier_info_shift',
				report.shift
			);


			setText(
				'cashier_info_encoder_name',
				report.user_real_name
			);


			setText(
				'cashier_info_remarks',
				report.cashier_report_remarks
			);


            /*================================================
            REPORT ID
            =================================================*/

            const updateButton =
                document.getElementById(
                    'update-cashiers-report'
                );


            if(updateButton)
            {
                updateButton.value =
                    CashiersReportID;
            }

        },


        /*==================================================
        ERROR
        ==================================================*/

        error: function(xhr, status, error)
        {
            console.error(
                "AJAX Error:",
                {
                    status:
                        xhr.status,

                    response:
                        xhr.responseText,

                    error:
                        error
                }
            );


            showValidationErrorModal(
                "Unable to load Cashier Report information."
            );

        }

    });
}


/*==================================================
CASHIER REPORT SUMMARY
==================================================*/
LoadCashiersReportSummary();
function LoadCashiersReportSummary()
{
    const CashiersReportId =
        {{ $CashiersReportId }};


    /*==================================================
    FORMAT CURRENCY
    ==================================================*/

    const formatAmount = (value) =>
    {
        const amount =
            Number(value) || 0;

        return amount.toLocaleString(
            'en-PH',
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        );
    };


    /*==================================================
    SET SUMMARY VALUE
    ==================================================*/

    const setAmount = (id, value) =>
    {
        const element =
            document.getElementById(id);


        if(element)
        {
            element.textContent =
                formatAmount(value);
        }
        else
        {
            console.warn(
                `Element not found: ${id}`
            );
        }
    };


    /*==================================================
    AJAX
    ==================================================*/

    $.ajax({

        url:
            "{{ route('CashiersReportSummary') }}",

        type:
            "POST",

        dataType:
            "json",

        data:
        {
            CashiersReportId:
                CashiersReportId,

            _token:
                "{{ csrf_token() }}"
        },


        /*================================================
        SUCCESS
        =================================================*/

        success: function(response)
        {
            console.log(
                'Cashier Report Summary:',
                response
            );


            /*================================================
            CHECK RESPONSE
            =================================================*/

            if(
                !response ||
                typeof response !== 'object'
            )
            {
                console.warn(
                    'No Cashier Report Summary found.'
                );

                resetCashiersReportSummary();

                return;
            }


            /*================================================
            GET VALUES
            =================================================*/

            const fuelSalesTotal =
                Number(
                    response.fuel_sales_total
                ) || 0;


            const otherSalesTotal =
                Number(
                    response.other_sales_total
                ) || 0;


            const miscellaneousTotal =
                Number(
                    response.miscellaneous_total
                ) || 0;


            const limitlessPayment =
                Number(
                    response.total_limitless_payment_amount
                ) || 0;


            const creditDebitPayment =
                Number(
                    response.total_credit_debit_payment_amount
                ) || 0;


            const gcashPayment =
                Number(
                    response.total_gcash_payment_amount
                ) || 0;


            const checkPayment =
                Number(
                    response.total_check_payment_amount
                ) || 0;


            const cashOnHand =
                Number(
                    response.cash_on_hand
                ) || 0;


            /*================================================
            NON-CASH PAYMENT
            =================================================*/

            const totalNonCashPayment =
                limitlessPayment +
                creditDebitPayment +
                gcashPayment +
                checkPayment;


            /*================================================
            TOTAL SALES
            =================================================*/

            const totalSales =
                cashOnHand +
                totalNonCashPayment;


            /*================================================
            THEORETICAL SALES
            =================================================*/

            const theoreticalSales =
                (
                    fuelSalesTotal +
                    otherSalesTotal
                ) -
                miscellaneousTotal;


            /*================================================
            CASH SHORT / OVER
            =================================================*/

            const cashShortOrOver =
                totalSales -
                theoreticalSales;


            /*================================================
            UPDATE DISPLAY
            =================================================*/

            setAmount(
                'fuel_sales_total',
                fuelSalesTotal
            );


            setAmount(
                'other_sales_total',
                otherSalesTotal
            );


            setAmount(
                'total_sales',
                totalSales
            );


            setAmount(
                'miscellaneous_total',
                miscellaneousTotal
            );


            setAmount(
                'total_cash_payment',
                cashOnHand
            );


            setAmount(
                'total_non_cash_payment',
                totalNonCashPayment
            );


            setAmount(
                'theoretical_sales',
                theoreticalSales
            );


            setAmount(
                'cash_on_hand',
                cashOnHand
            );


            setAmount(
                'cash_short_or_over',
                cashShortOrOver
            );


            /*================================================
            OPTIONAL: COLOR SHORT / OVER
            =================================================*/

            const shortOverElement =
                document.getElementById(
                    'cash_short_or_over'
                );


            if(shortOverElement)
            {
                shortOverElement.classList.remove(
                    'text-danger',
                    'text-success',
                    'text-warning'
                );


                if(cashShortOrOver < 0)
                {
                    /* SHORT */

                    shortOverElement.classList.add(
                        'text-danger'
                    );

                }
                else if(cashShortOrOver > 0)
                {
                    /* OVER */

                    shortOverElement.classList.add(
                        'text-success'
                    );

                }
                else
                {
                    /* BALANCED */

                    shortOverElement.classList.add(
                        'text-warning'
                    );
                }
            }

        },


        /*================================================
        ERROR
        =================================================*/

        error: function(xhr, status, error)
        {
            console.error(
                'Cashier Report Summary AJAX Error:',
                {
                    status:
                        xhr.status,

                    response:
                        xhr.responseText,

                    error:
                        error
                }
            );


            resetCashiersReportSummary();
        }

    });
}


/*==================================================
RESET SUMMARY
==================================================*/

function resetCashiersReportSummary()
{
    const summaryFields =
    [
        'fuel_sales_total',
        'other_sales_total',
        'total_sales',
        'miscellaneous_total',
        'total_cash_payment',
        'total_non_cash_payment',
        'theoretical_sales',
        'cash_on_hand',
        'cash_short_or_over'
    ];


    summaryFields.forEach(function(id)
    {
        const element =
            document.getElementById(id);


        if(element)
        {
            element.textContent =
                '0.00';
        }
    });
}

	/*Re-print*/
	function printCashierReportPDF(){
		
		var query = {
			CashiersReportId:{{ $CashiersReportId }},
			_token: "{{ csrf_token() }}"
		}

		var url = "{{URL::to('generate_cashier_report_pdf')}}?" + $.param(query)
		window.open(url);
	  
	}
	
</script>
