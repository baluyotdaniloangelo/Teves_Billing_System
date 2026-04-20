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

    $.ajax({
        url: "/create_reminder_post",
        type: "POST",
        data: {
            reminders_title: reminders_title,
            reminders_content: reminders_content,
            reminder_date: reminder_date,
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

            // format datetime-local
            let date = new Date(response.reminder_date);
            let formatted = date.toISOString().slice(0,16);
            $("#update_reminder_date").val(formatted);

            $('#UpdateReminderModal').modal('show');
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

    $.ajax({
        url: "/update_reminder_post",
        type: "POST",
        data: {
            reminder_id: reminder_id,
            reminders_title: reminders_title,
            reminders_content: reminders_content,
            reminder_date: reminder_date,
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