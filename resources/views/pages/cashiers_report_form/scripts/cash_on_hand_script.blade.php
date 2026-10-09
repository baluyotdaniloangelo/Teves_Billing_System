<script>
$(function () {
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

    const money = value => Number(value || 0).toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    function calculate() {
        let cashOnHand = 0;

        rows.forEach(([field, input, output, denomination]) => {
            const amount = (Number($('#' + input).val()) || 0) * denomination;
            cashOnHand += amount;
            $('#' + output).text(money(amount));
        });

        const cashDrop = Number($('#cash_on_hand_drop').val()) || 0;
        $('#cash_on_hand_drop_amount').text(money(cashDrop));
        $('#cash_on_hand_total_amount').text(money(cashOnHand));
        $('#cash_on_hand_total_actual').text(money(cashOnHand + cashDrop));
    }

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
                if (!Array.isArray(data) || !data.length) return;

                const record = data[0];
                $('#cash_on_hand_id').val(record.cash_on_hand_id || 0);
                rows.forEach(([field, input]) => {
                    $('#' + input).val(record[field] ?? 0);
                });
                $('#cash_on_hand_drop').val(record.cash_drop ?? 0);
                calculate();
            },
            error: function (xhr) {
                console.error('Cash on Hand load failed.', xhr);
            }
        });
    }

    $('.cash-on-hand-count, #cash_on_hand_drop').on('input change', calculate);

    $('#save_cash_on_hand').on('click', function () {
        const form = document.getElementById('cash_on_hand_form');
        if (!form.reportValidity()) return;

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
                $('#switch_notice_on').show();
                $('#sw_on').text(response.success || 'Cash on Hand saved.');
                setTimeout(() => $('#switch_notice_on').fadeOut('fast'), 1200);
                load();
                if (typeof LoadCashiersReportSummary === 'function') {
                    LoadCashiersReportSummary();
                }
            },
            error: function (xhr) {
                console.error('Cash on Hand save failed.', xhr);
            }
        });
    });

    calculate();
    load();
});
</script>
