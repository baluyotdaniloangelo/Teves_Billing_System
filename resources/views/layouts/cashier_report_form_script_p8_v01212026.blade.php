<script type="text/javascript">

/* ================= SAVE PAYMENT ================= */
$("#save-CRPH8").click(function(event){

    event.preventDefault();

    let CashiersReportId = {{ $CashiersReportId }};

    let payment_type   = $("#payment_type").val();
    let payment_amount = $("#payment_amount").val();

    document.getElementById('CRPH8_form').className =
        "g-3 needs-validation was-validated";

    $.ajax({
        url: "{{ route('SAVE_CHR_PH8') }}",
        type: "POST",
        data: {
            CRPH8_ID: 0,
            CashiersReportId: CashiersReportId,
            payment_type: payment_type,
            payment_amount: payment_amount,
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {

            $('#switch_notice_on').show();
            $('#sw_on').html(response.success);
            setTimeout(() => $('#switch_notice_on').fadeOut('fast'), 1000);

            $("#payment_type").val('');
            $("#payment_amount").val('');

            LoadCashiersReportPH8();
            document.getElementById('CRPH8_form').className =
                "g-3 needs-validation";
        },
        error: function(error) {
            console.log(error);
        }
    });
});


/* ================= EDIT ================= */
$('body').on('click', '#CHPH8_Edit', function(event){

    event.preventDefault();
    let CRPH8_ID = $(this).data('id');

    $.ajax({
        url: "{{ route('CRP8_info') }}",
        type: "POST",
        data: {
            CRPH8_ID: CRPH8_ID,
            _token: "{{ csrf_token() }}"
        },
        success: function(response){

            $("#update-CRPH8").val(CRPH8_ID);
            $("#update_payment_type").val(response[0].payment_type);
            $("#update_payment_amount").val(response[0].payment_amount);

            $('#Update_CRPH8_Modal').modal('toggle');
        }
    });
});


/* ================= UPDATE ================= */
$("#update-CRPH8").click(function(event){

    event.preventDefault();

    let CRPH8_ID = $("#update-CRPH8").val();

    let payment_type   = $("#update_payment_type").val();
    let payment_amount = $("#update_payment_amount").val();

    document.getElementById('update_CRPH8_form').className =
        "g-3 needs-validation was-validated";

    $.ajax({
        url: "{{ route('SAVE_CHR_PH8') }}",
        type: "POST",
        data: {
            CRPH8_ID: CRPH8_ID,
            payment_type: payment_type,
            payment_amount: payment_amount,
            _token: "{{ csrf_token() }}"
        },
        success: function(response){

            $('#switch_notice_on').show();
            $('#sw_on').html(response.success);
            setTimeout(() => $('#switch_notice_on').fadeOut('fast'), 1000);

            LoadCashiersReportPH8();
            $('#Update_CRPH8_Modal').modal('toggle');
        }
    });
});


/* ================= LOAD TABLE ================= 
LoadCashiersReportPH8();

function LoadCashiersReportPH8(){

    $("#table_cash_payment_body_data").empty();

    let CashiersReportId = {{ $CashiersReportId }};

    $.ajax({
        url: "{{ route('GetCashiersP8') }}",
        type: "POST",
        data: {
            CashiersReportId: CashiersReportId,
            _token: "{{ csrf_token() }}"
        },
        success: function(response){

            for (let i = 0; i < response.length; i++) {

                let id = response[i].cashiers_report_p8_id;
                let type = response[i].payment_type.replace('_', ' ').toUpperCase();
                let amount = Number(response[i].payment_amount)
                    .toLocaleString("en-PH", { minimumFractionDigits: 2 });

                $("#table_cash_payment_body_data").append(`
                    <tr>
                        <td align="center">${type}</td>
                        <td align="center">₱ ${amount}</td>
                        <td align="center">
                            <a href="#" class="btn-danger btn-circle btn-sm bi-pencil-fill"
                               id="CHPH8_Edit" data-id="${id}"></a>
                            <a href="#" class="btn-danger btn-circle btn-sm bi-trash3-fill"
                               id="CHPH8_Delete" data-id="${id}"></a>
                        </td>
                    </tr>
                `);
            }
        }
    });
}*/


/* ================= DELETE ================= */
$('body').on('click', '#CHPH8_Delete', function(event){

    event.preventDefault();
    let CRPH8_ID = $(this).data('id');

    $.ajax({
        url: "{{ route('CRP8_info') }}",
        type: "POST",
        data: {
            CRPH8_ID: CRPH8_ID,
            _token: "{{ csrf_token() }}"
        },
        success: function(response){

            $("#deleteCRPH8Confirmed").val(CRPH8_ID);
            $("#delete_payment_type").text(response[0].payment_type.toUpperCase());
            $("#delete_payment_amount").text(
                Number(response[0].payment_amount)
                .toLocaleString("en-PH", { minimumFractionDigits: 2 })
            );

            $('#CRPH8DeleteModal').modal('toggle');
        }
    });
});


$('body').on('click', '#deleteCRPH8Confirmed', function(){

    let CRPH8_ID = $(this).val();

    $.ajax({
        url: "{{ route('DeleteCashiersProductP8') }}",
        type: "POST",
        data: {
            CRPH8_ID: CRPH8_ID,
            _token: "{{ csrf_token() }}"
        },
        success: function(){

            $('#switch_notice_off').show();
            $('#sw_off').html("Deleted");
            setTimeout(() => $('#switch_notice_off').fadeOut('slow'), 1000);

            LoadCashiersReportPH8();
        }
    });
});

</script>


<script>
$(function () {

    let CashiersReportId = {{ $CashiersReportId }};

var CashiersReportPH8Table = $('#CashiersReportPH8Table').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,   // ❗ IMPORTANT: turn OFF responsive
    //scrollY: '400px',
    scrollX: false,       // ✅ ADD THIS
    //scrollCollapse: true,
    //autoWidth: false,    // ✅ ADD THIS

    ajax: {
        url: "{{ route('GetCashiersP8') }}",
        type: "POST",
        data: function (d) {
            d.CashiersReportId = {{ $CashiersReportId }};
            d._token = "{{ csrf_token() }}";
        }
    },

    columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'payment_type', name: 'payment_type', className: 'text-center' },
        {
            data: 'payment_amount',
			name: 'payment_amount',
			render: $.fn.dataTable.render.number(',', '.', 2, '₱ '),
			orderable: true,
        },
		{data: 'payment_amount', orderable: true, render: $.fn.dataTable.render.number( ',', '.', 2, '' ), className: "text-right" },
        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
    ],
	columnDefs: [
        {
            targets: [2],               // Amount column index
            className: 'text-right'    // BODY
        },
        {
            targets: [2],
            createdCell: function () {},
            title: 'Amount'
        }
    ],
    order: [[1, 'asc']],
	
	footerCallback: function (row, data) {

        let total = data.reduce(function (sum, row) {
            return sum + Number(row.payment_amount);
        }, 0);

        $('#ph8_footer_total').html(
            '₱ ' + total.toLocaleString('en-PH', { minimumFractionDigits: 2 })
        );
    }
	
});

    /* Reusable reload after save/update/delete */
    window.LoadCashiersReportPH8 = function () {
        CashiersReportPH8Table.ajax.reload(null, false);
    };
	
	
	CashiersReportPH8Table.on('draw', function () {
		CashiersReportPH8Table.columns.adjust();
	});

});
</script>
