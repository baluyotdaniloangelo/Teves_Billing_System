<!-- DataTables -->
<script src="{{asset('Datatables/2.0.8/js/dataTables.js')}}"></script>
<script src="{{asset('Datatables/responsive/3.0.2/js/dataTables.responsive.js')}}"></script>

<script type="text/javascript">

$(function () {

    var ReminderTable = $('#reminderTable').DataTable({
        language: {
            lengthMenu:
                '<select class="dt-input">'+
                '<option value="10">10</option>'+
                '<option value="20">20</option>'+
                '<option value="30">30</option>'+
                '<option value="40">40</option>'+
                '<option value="50">50</option>'+
                '<option value="-1">All</option>'+
                '</select>'
        },
        serverSide: true,
        stateSave: true,
        ajax: "{{ route('getReminderList') }}",
        responsive: true,
        scrollCollapse: true,
        scrollY: '500px',

        columns: [
            {data: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'reminders_title'},
            {data: 'reminders_content'},
            {data: 'reminder_date'},
            {data: 'action', orderable: false, searchable: false, className: "text-center"}
        ]
    });

});


// ================= SAVE =================
$("#saveReminder").click(function(e){
    e.preventDefault();

    let reminders_title = $("input[name=reminders_title]").val();
    let reminders_content = $("textarea[name=reminders_content]").val();
    let reminder_date = $("input[name=reminder_date]").val();

	let is_recurring = $('#is_recurring').is(':checked') ? 1 : 0;
	let recurrence_type = $('#recurrence_type').val();
	let recurrence_end_date = $("input[name=recurrence_end_date]").val();

    $.ajax({
        url: "/create_reminder_post",
        type: "POST",
        data: {
            reminders_title: reminders_title,
            reminders_content: reminders_content,
            reminder_date: reminder_date,
			is_recurring: is_recurring,
			recurrence_type: recurrence_type,
			recurrence_end_date: recurrence_end_date,
            _token: "{{ csrf_token() }}"
        },
        success: function(response){

            $('#CreateReminderModal').modal('hide');

            $("#reminderFormNew")[0].reset();

            $("#reminderTable").DataTable().ajax.reload(null,false);

        },
        error: function(error){
            console.log(error);
            alert("Error saving reminder");
        }
    });
});

function formatLocalDateTime(dateString){
    let date = new Date(dateString);

    let year = date.getFullYear();
    let month = String(date.getMonth()+1).padStart(2,'0');
    let day = String(date.getDate()).padStart(2,'0');

    let hours = String(date.getHours()).padStart(2,'0');
    let minutes = String(date.getMinutes()).padStart(2,'0');

    return `${year}-${month}-${day}T${hours}:${minutes}`;
}

// ================= EDIT =================
$('body').on('click','#editReminder',function(){

    let reminder_id = $(this).data('id');

    $.ajax({
        url: "/reminder_info",
        type: "POST",
        data: {
            reminder_id: reminder_id,
            _token: "{{ csrf_token() }}"
        },
        success: function(response){

            $("#update_reminder_id").val(response.reminder_id);
            $("#update_reminders_title").val(response.reminders_title);
            $("#update_reminders_content").val(response.reminders_content);

             // FORMAT DATE
            let date = new Date(response.reminder_date);
            $("#update_reminder_date").val(date.toISOString().slice(0,16));

            // ✅ RECURRING
            if(response.is_recurring == 1){
                $('#update_is_recurring').prop('checked', true);
                $('#update_recurrence_fields').show();

                $('#update_recurrence_type').val(response.recurrence_type);

                if(response.recurrence_end_date){
				$("#update_recurrence_end_date").val(
					formatLocalDateTime(response.recurrence_end_date)
				);
	
				}

            } else {
                $('#update_is_recurring').prop('checked', false);
                $('#update_recurrence_fields').hide();
            }
			
            $('#UpdateReminderModal').modal('show');
        }
    });

});

$(document).ready(function(){

    $('#is_recurring').change(function(){
        if($(this).is(':checked')){
            $('#recurrence_fields').slideDown();
        } else {
            $('#recurrence_fields').slideUp();
        }
    });
	
	$('#update_is_recurring').change(function(){
	
		let enabled = $(this).is(':checked');

		$('#update_recurrence_type').prop('disabled', !enabled);
		$('#update_recurrence_end_date').prop('disabled', !enabled);
		
		if($(this).is(':checked')){
			$('#update_recurrence_fields').slideDown();
		} else {
			$('#update_recurrence_fields').slideUp();
		}

	});

});

// ================= UPDATE =================
$("#updateReminder").click(function(e){
    e.preventDefault();

    let reminder_id = $("#update_reminder_id").val();
    let reminders_title = $("#update_reminders_title").val();
    let reminders_content = $("#update_reminders_content").val();
    let reminder_date = $("#update_reminder_date").val();

	let is_recurring = $('#update_is_recurring').is(':checked') ? 1 : 0;
    let recurrence_type = $('#update_recurrence_type').val();
    let recurrence_end_date = $('#update_recurrence_end_date').val();
	
    $.ajax({
        url: "/update_reminder_post",
        type: "POST",
        data: {
            reminder_id: reminder_id,
            reminders_title: reminders_title,
            reminders_content: reminders_content,
            reminder_date: reminder_date,
			is_recurring: is_recurring,
            recurrence_type: recurrence_type,
            recurrence_end_date: recurrence_end_date,
            _token: "{{ csrf_token() }}"
        },
        success: function(response){

            $('#UpdateReminderModal').modal('hide');

            $("#reminderTable").DataTable().ajax.reload(null,false);

        },
        error: function(error){
            console.log(error);
        }
    });

});


// ================= DELETE =================
let deleteID = null;

$('body').on('click','#deleteReminder',function(){

    deleteID = $(this).data('id');
    let title = $(this).data('title');

    $("#delete_reminder_title").text(title);

    $('#DeleteReminderModal').modal('show');

});


$("#confirmDeleteReminder").click(function(){

    $.ajax({
        url: "/delete_reminder_confirmed",
        type: "POST",
        data: {
            reminder_id: deleteID,
            _token: "{{ csrf_token() }}"
        },
        success: function(){

            $('#DeleteReminderModal').modal('hide');

            $("#reminderTable").DataTable().ajax.reload(null,false);

        }
    });

});

</script>