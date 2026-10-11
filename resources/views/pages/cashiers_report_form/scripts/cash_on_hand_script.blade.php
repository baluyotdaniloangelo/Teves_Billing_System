<script>
/*==============================================================
 CASH ON HAND - CASHIER'S REPORT

 Purpose:
 - Calculate cash totals based on the quantity of each denomination.
 - Load the previously saved Cash on Hand record.
 - Save or update the record using the existing Laravel route.
 - Show modal notifications instead of inline success notices.
 - Refresh the Cashier's Report summary after saving.
==============================================================*/

$(function () {
    // Run this code after the page's HTML has finished loading.
    /*==========================================================
     DENOMINATION CONFIGURATION
     Each row contains:
     [backend field name, input element ID, total display ID, value]
     Add or remove denominations here if the form changes.
    ==========================================================*/
    const rows = [
        ['one_thousand_deno', 'cash_on_hand_one_thousand', 'cash_on_hand_one_thousand_total', 1000],
        ['five_hundred_deno', 'cash_on_hand_five_hundred', 'cash_on_hand_five_hundred_total', 500],
        ['two_hundred_deno', 'cash_on_hand_two_hundred', 'cash_on_hand_two_hundred_total', 200],
        ['one_hundred_deno', 'cash_on_hand_one_hundred', 'cash_on_hand_one_hundred_total', 100],
        ['fifty_deno', 'cash_on_hand_fifty', 'cash_on_hand_fifty_total', 50],
        ['twenty_deno', 'cash_on_hand_twenty', 'cash_on_hand_twenty_total', 20],
        ['ten_deno', 'cash_on_hand_ten', 'cash_on_hand_ten_total', 10],
        ['five_deno', 'cash_on_hand_five', 'cash_on_hand_five_total', 5],
        ['one_deno', 'cash_on_hand_one', 'cash_on_hand_one_total', 1],
        ['twenty_five_cent_deno', 'cash_on_hand_twenty_five_cent', 'cash_on_hand_twenty_five_cent_total', 0.25]
    ];

    /*==========================================================
     NUMBER FORMATTING
     Format amounts with comma separators and exactly 2 decimals.
    ==========================================================*/
    const money = value => Number(value || 0).toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    /*==========================================================
     CALCULATE CASH TOTALS
     - Multiply each denomination quantity by its value.
     - Update each denomination subtotal in the form.
     - Calculate cash counted, cash drop, and actual total.
    ==========================================================*/
    function calculate() {
        let cashOnHand = 0;

        rows.forEach(([field, input, output, denomination]) => {
            const amount = (Number($('#' + input).val()) || 0) * denomination;
            cashOnHand += amount;
            $('#' + output).text(money(amount));
        });

        // Cash drop is displayed separately and added to the actual total.
        const cashDrop = Number($('#cash_on_hand_drop').val()) || 0;
        $('#cash_on_hand_drop_amount').text(money(cashDrop));
        $('#cash_on_hand_total_amount').text(money(cashOnHand));
        $('#cash_on_hand_total_actual').text(money(cashOnHand + cashDrop));
    }

    /*==========================================================
     LOAD SAVED CASH ON HAND
     Request the saved record for this Cashier's Report from Laravel.
    ==========================================================*/
    function load() {
        $.ajax({
            url: "{{ route('cashiers_report_cash_on_hand.info') }}",
            type: 'POST',
            dataType: 'json',
            data: {
                cash_report_id: {{ $CashiersReportId }},
                _token: "{{ csrf_token() }}"
            },
            success: function (data) {
                // If the server returns no record, there is nothing to populate.
                if (!Array.isArray(data) || !data.length) return;

                const record = data[0];
                $('#cash_on_hand_id').val(record.cash_on_hand_id || 0);

                // Fill the denomination quantity inputs from the saved record.
                // The nullish fallback uses 0 only when the value is null/undefined.
                rows.forEach(([field, input]) => {
                    $('#' + input).val(record[field] ?? 0);
                });

                $('#cash_on_hand_drop').val(record.cash_drop ?? 0);
                calculate();
            },
            error: function (xhr) {
                // Log the full response for debugging in the browser console.
                console.error('Cash on Hand load failed.', xhr);
            }
        });
    }

    /*==========================================================
     LIVE CALCULATION
     Recalculate totals when the user types or changes a count/drop.
    ==========================================================*/
    $('.cash-on-hand-count, #cash_on_hand_drop').on('input change', calculate);

    /*==========================================================
     SAVE / UPDATE CASH ON HAND
     The backend can decide whether to insert or update based on
     cash_on_hand_id. Existing route and field names are preserved.
    ==========================================================*/
    $('#save_cash_on_hand').on('click', function () {
        const form = document.getElementById('cash_on_hand_form');
        if (!form || !form.reportValidity()) return;

        // Prepare the request payload expected by the Laravel controller.
        // Do not rename these fields without also updating the backend.
        const data = {
            cash_report_id: {{ $CashiersReportId }},
            cash_on_hand_id: $('#cash_on_hand_id').val() || 0,
            cash_drop: $('#cash_on_hand_drop').val(),
            _token: "{{ csrf_token() }}"
        };

        rows.forEach(([field, input]) => {
            data[field] = $('#' + input).val();
        });

        $.ajax({
            url: "{{ route('cashiers_report_cash_on_hand.save') }}",
            type: 'POST',
            data: data,
            success: function (response) {
                /*--------------------------------------------------
                 SAVE SUCCESS
                 Show the shared success modal instead of the inline
                 #switch_notice_on notification.
                --------------------------------------------------*/
                const successMessage = response.success || 'Cash on Hand saved successfully.';

                if (typeof showSuccessModal === 'function') {
                    showSuccessModal(successMessage);
                } else {
                    // Fallback in case the shared success-modal helper is unavailable.
                    window.alert(successMessage);
                }

                // Fetch the saved values again so the form reflects server data.
                load();

                // Refresh the report summary if this shared function exists.
                if (typeof LoadCashiersReportSummary === 'function') {
                    LoadCashiersReportSummary();
                }
            },
            error: function (xhr) {
                // Log the full response for debugging in the browser console.
                console.error('Cash on Hand save failed.', xhr);

                // Use the server's message when available; otherwise show a fallback.
                const message = xhr.responseJSON?.message ||
                    'Unable to save Cash on Hand. Please check the entered values and try again.';

                if (typeof showValidationErrorModal === 'function') {
                    showValidationErrorModal(message);
                } else {
                    window.alert(message);
                }
            }
        });
    });

    /*==========================================================
     INITIAL SETUP
     Calculate from current inputs first, then load any saved record.
    ==========================================================*/
    calculate();
    load();
});
</script>
