<script type="text/javascript">

/* ================= SAVE PAYMENT ================= */
$("#save-CRPH9").click(function(event){

    event.preventDefault();

    let CashiersReportId = {{ $CashiersReportId }};

    // Reset previous errors
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    // ✅ Use FormData
    let formData = new FormData();

    formData.append('CRPH9_ID', 0);
    formData.append('CashiersReportId', CashiersReportId);
    formData.append('cash_deposit_bank', $("#cash_deposit_bank").val());
    formData.append('cash_deposit_date', $("#cash_deposit_date").val());
    formData.append('cash_deposit_amount', $("#cash_deposit_amount").val());
    formData.append('cash_deposit_reference', $("#cash_deposit_reference").val());
    formData.append('cash_deposit_remarks', $("textarea[name=cash_deposit_remarks]").val());

    // ✅ ADD FILE HERE
    let fileInput = $("#cash_deposit_photo")[0];

    if (fileInput.files.length > 0) {
        formData.append('cash_deposit_photo', fileInput.files[0]);
    }

    // CSRF
    formData.append('_token', "{{ csrf_token() }}");

    $.ajax({
        url: "{{ route('SAVE_CHR_PH9') }}",
        type: "POST",
        data: formData,

        processData: false, // REQUIRED
        contentType: false, // REQUIRED

        success: function(response) {

            $('#switch_notice_on').show();
            $('#sw_on').html(response.success);
            setTimeout(() => $('#switch_notice_on').fadeOut('fast'), 1000);
			
			$("#cash_deposit_preview").attr("src", "").hide();
			
            $("#CRPH9_form")[0].reset();

            LoadCashiersReportPH9();
        },

        error: function(xhr) {

            if (xhr.status === 422) {

                let errors = xhr.responseJSON.errors;

                $.each(errors, function(field, messages) {

                    let input = $("[name="+field+"]");

                    input.addClass("is-invalid");

                    input.after(
                        '<div class="invalid-feedback">' + messages[0] + '</div>'
                    );
                });
            } else {
                console.log(xhr);
            }
        }
    });
});


$("#cash_deposit_photo").on("change", function(){

    let file = this.files[0];

    if (file) {
        let reader = new FileReader();

        reader.onload = function(e) {
            $("#cash_deposit_preview")
                .attr("src", e.target.result)
                .fadeIn();
        }

        reader.readAsDataURL(file);
    }
});

$("#clear-CRPH9-save").click(function () {
    $("#cash_deposit_preview").attr("src", "").hide();
});

/* ================= EDIT ================= */
$('body').on('click', '#CRP9_Edit', function(event){

    event.preventDefault();
    let CRPH9_ID = $(this).data('id');

    $.ajax({
        url: "{{ route('CRP9_info') }}",
        type: "POST",
        data: {
            CRPH9_ID: CRPH9_ID,
            _token: "{{ csrf_token() }}"
        },
        success: function(response){

            $("#update-CRPH9").val(CRPH9_ID);
            $("#update_cash_deposit_bank").val(response[0].cash_deposit_bank);
            $("#update_cash_deposit_date").val(response[0].cash_deposit_date);

			$("#update_cash_deposit_amount").val(response[0].cash_deposit_amount);
			$("#update_cash_deposit_reference").val(response[0].cash_deposit_reference);
			$("#update_cash_deposit_remarks").val(response[0].cash_deposit_remarks);
			
			$("#current_photo_preview")
			.attr("src", "/storage/" + response[0].cash_deposit_photo)
			.show();
	
            $('#Update_CRPH9_Modal').modal('toggle');
        }
    });
});

$(document).on("click", ".CRP9_Preview", function(e){

    e.preventDefault();

    let photo     = $(this).data("photo");
    let bank      = $(this).data("bank");
    let amount    = $(this).data("amount");
    let reference = $(this).data("reference");
    let date      = $(this).data("date");

    // IMAGE
    if (photo) {
        $("#preview_image")
            .attr("src", "/storage/" + photo)
            .show();

        $("#no_image_text").hide();
    } else {
        $("#preview_image").hide();
        $("#no_image_text").show();
    }

    // DETAILS
    $("#preview_bank").text(bank ?? '-');
    $("#preview_amount").text('₱ ' + parseFloat(amount).toLocaleString());
    $("#preview_reference").text(reference ?? '-');
    $("#preview_date").text(date ?? '-');

    $("#Preview_CRPH9_Modal").modal("show");
});

/* ================= UPDATE ================= */
$("#update-CRPH9s").click(function(event){

    event.preventDefault();

    let CRPH9_ID = $("#update-CRPH9").val();

    let cash_deposit_bank   = $("#update_cash_deposit_bank").val();
    let cash_deposit_date = $("#update_cash_deposit_date").val();

	let cash_deposit_amount = $("input[name=update_cash_deposit_amount]").val();
	let cash_deposit_reference = $("input[name=update_cash_deposit_reference]").val();
	let cash_deposit_remarks = $("input[name=update_cash_deposit_remarks]").val();

	
    document.getElementById('update_CRPH9_form').className =
        "g-3 needs-validation was-validated";

    $.ajax({
        url: "{{ route('SAVE_CHR_PH9') }}",
        type: "POST",
        data: {
            CRPH9_ID: CRPH9_ID,
            cash_deposit_bank: cash_deposit_bank,
            cash_deposit_date: cash_deposit_date,
			cash_deposit_amount: cash_deposit_amount,
			cash_deposit_reference: cash_deposit_reference,
			cash_deposit_remarks: cash_deposit_remarks,
            _token: "{{ csrf_token() }}"
        },
        success: function(response){

            $('#switch_notice_on').show();
            $('#sw_on').html(response.success);
            setTimeout(() => $('#switch_notice_on').fadeOut('fast'), 1000);

            LoadCashiersReportPH9();
            $('#Update_CRPH9_Modal').modal('toggle');
			
        }
    });
});


$("#update-CRPH9").click(function(event){

    event.preventDefault();

    let CashiersReportId = {{ $CashiersReportId }};

    // Reset previous errors
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    // ✅ Use FormData
    let formData = new FormData();

    formData.append('CRPH9_ID', $("#update-CRPH9").val());
    formData.append('CashiersReportId', CashiersReportId);
    formData.append('cash_deposit_bank', $("#update_cash_deposit_bank").val());
    formData.append('cash_deposit_date', $("#update_cash_deposit_date").val());
    formData.append('cash_deposit_amount', $("#update_cash_deposit_amount").val());
    formData.append('cash_deposit_reference', $("#update_cash_deposit_reference").val());
    formData.append('cash_deposit_remarks', $("textarea[name=update_cash_deposit_remarks]").val());
	
	formData.append('remove_photo', $("#remove_photo").is(":checked"));
	
    // ✅ ADD FILE HERE
    let fileInput = $("#update_cash_deposit_photo")[0];

    if (fileInput.files.length > 0) {
        formData.append('cash_deposit_photo', fileInput.files[0]);
    }

    // CSRF
    formData.append('_token', "{{ csrf_token() }}");

    $.ajax({
        url: "{{ route('SAVE_CHR_PH9') }}",
        type: "POST",
        data: formData,

        processData: false, // REQUIRED
        contentType: false, // REQUIRED

        success: function(response) {

            $('#switch_notice_on').show();
            $('#sw_on').html(response.success);
            setTimeout(() => $('#switch_notice_on').fadeOut('fast'), 1000);

            //$("#update_CRPH9_form")[0].reset();

            LoadCashiersReportPH9();
        },

        error: function(xhr) {

            if (xhr.status === 422) {

                let errors = xhr.responseJSON.errors;

                $.each(errors, function(field, messages) {

                    let input = $("[name="+field+"]");

                    input.addClass("is-invalid");

                    input.after(
                        '<div class="invalid-feedback">' + messages[0] + '</div>'
                    );
                });
            } else {
                console.log(xhr);
            }
        }
    });
});

/* ================= DELETE ================= */
$('body').on('click', '#CRP9_Delete', function(event){

    event.preventDefault();
    let CRPH9_ID = $(this).data('id');

    $.ajax({
        url: "{{ route('CRP9_info') }}",
        type: "POST",
        data: {
            CRPH9_ID: CRPH9_ID,
            _token: "{{ csrf_token() }}"
        },
        success: function(response){

            $("#deleteCRPH9Confirmed").val(CRPH9_ID);
            $("#delete_cash_deposit_bank").text(response[0].cash_deposit_bank.toUpperCase());
			$("#delete_cash_deposit_date").text(response[0].cash_deposit_date.toUpperCase());
            $("#delete_cash_deposit_amount").text(
                Number(response[0].cash_deposit_amount)
                .toLocaleString("en-PH", { minimumFractionDigits: 2 })
            );

            $('#CRPH9DeleteModal').modal('toggle');
        }
    });
});

$("#save-CRPH9").click(function(event){

    event.preventDefault();

    let form = document.getElementById('CRPH9_form');
    let formData = new FormData(form);

    formData.append('CRPH9_ID', 0);
    formData.append('CashiersReportId', {{ $CashiersReportId }});

    $.ajax({
        url: "{{ route('SAVE_CHR_PH9') }}",
        type: "POST",
        data: formData,
        processData: false, // REQUIRED
        contentType: false, // REQUIRED

        success: function(response) {

            $('#switch_notice_on').show();
            $('#sw_on').html(response.success);
            setTimeout(() => $('#switch_notice_on').fadeOut('fast'), 1000);

            $("#CRPH9_form")[0].reset();

            LoadCashiersReportPH9();
        },

        error: function(xhr) {
            console.log(xhr);
        }
    });
});
$('body').on('click', '#deleteCRPH9Confirmed', function(){

    let CRPH9_ID = $(this).val();

    $.ajax({
        url: "{{ route('DeleteCashDeposit') }}",
        type: "POST",
        data: {
            CRPH9_ID: CRPH9_ID,
            _token: "{{ csrf_token() }}"
        },
        success: function(){

            $('#switch_notice_off').show();
            $('#sw_off').html("Deleted");
            setTimeout(() => $('#switch_notice_off').fadeOut('slow'), 1000);

            LoadCashiersReportPH9();
        }
    });
});


$(function () {

    let CashiersReportId = {{ $CashiersReportId }};

	var CashiersReportPH9Table = $('#CashiersReportPH9Table').DataTable({
		processing: true,
		serverSide: true,
		responsive: true,   // ❗ IMPORTANT: turn OFF responsive
		//scrollY: '400px',
		scrollX: false,       // ✅ ADD THIS
		//scrollCollapse: true,
		//autoWidth: false,    // ✅ ADD THIS

		ajax: {
			url: "{{ route('GetCashiersP9') }}",
			type: "POST",
			data: function (d) {
				d.CashiersReportId = {{ $CashiersReportId }};
				d._token = "{{ csrf_token() }}";
			}
		},

		columns: [
			{ data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
			{ data: 'cash_deposit_bank', name: 'cash_deposit_bank', className: 'text-center' },
			{ data: 'cash_deposit_date', className: 'text-center' },
			{ data: 'cash_deposit_reference', className: 'text-center' },
			{ data: 'cash_deposit_remarks', className: 'text-center' },
			{
				data: 'cash_deposit_amount',
				name: 'cash_deposit_amount',
				render: $.fn.dataTable.render.number(',', '.', 2, '₱ '),
				orderable: true,
			},
			{ data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
		],
		columnDefs: [
			{
				targets: [5],               // Amount column index
				className: 'text-right'    // BODY
			},
			{
				targets: [5],
				createdCell: function () {},
				title: 'Amount'
			}
		],
		order: [[1, 'asc']],
		
		footerCallback: function (row, data) {

			let total = data.reduce(function (sum, row) {
				return sum + Number(row.cash_deposit_amount);
			}, 0);

			$('#PH9_footer_total').html(
				'₱ ' + total.toLocaleString('en-PH', { minimumFractionDigits: 2 })
			);
		}
		
	});

    /* Reusable reload after save/update/delete */
    window.LoadCashiersReportPH9 = function () {
        CashiersReportPH9Table.ajax.reload(null, false);
    };
	
	
	CashiersReportPH9Table.on('draw', function () {
		CashiersReportPH9Table.columns.adjust();
	});

});
</script>
