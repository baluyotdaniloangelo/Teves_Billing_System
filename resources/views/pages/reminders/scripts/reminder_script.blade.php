<script>
// run immediately on load
loadReminders();

function loadReminders(){

    $.get('/notifications/reminders', function(res){

        let data = res.reminders;
        let unread = res.unread;

        $('.badge-number').text(unread);

        let html = '';

        // ✅ HEADER (cleaner)
        html += `
            <li class="dropdown-header d-flex justify-content-between align-items-center" >
                <span><strong>${unread}</strong> Notifications</span>
                <a href="/reminder" class="badge bg-primary rounded-pill px-2 py-1">
                    View all
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
        `;

        if(data.length === 0){
            html += `
                <li class="text-center text-muted py-3">
                    <i class="bi bi-bell-slash fs-4"></i>
                    <p class="mb-0">No notifications</p>
                </li>
            `;
        }

        // 🔁 ITEMS
data.forEach(reminder => {

    let isUnread = reminder.is_read == 0;
    let isOverdue = new Date(reminder.reminder_date) < new Date();

    let rowClass = '';
    let icon = 'bi-info-circle text-primary';

    if(isUnread){
        rowClass = 'notif-unread';
        icon = 'bi-bell-fill text-warning';
    }

    if(isOverdue){
        rowClass = 'notif-overdue';
        icon = 'bi-exclamation-circle text-danger';
    }

    html += `
        <li class="notification-item viewReminder ${rowClass}" 
            data-id="${reminder.reminder_id}">

            <i class="bi ${icon}"></i>

            <div>
                <h6>${reminder.reminders_title}</h6>
                <p>${reminder.reminders_content}</p>
                <small>${timeAgo(reminder.reminder_date)}</small>
            </div>

            ${isUnread ? '<span class="notif-dot"></span>' : ''}

        </li>

        <li><hr class="dropdown-divider"></li>
    `;
});

        // ✅ FOOTER
        html += `
            <li class="dropdown-footer text-center">
                <a href="/reminder" class="text-primary fw-semibold">
                    Show all notifications
                </a>
            </li>
        `;

        $('.notifications').html(html);
    });
}

// ⏱️ run every 10 seconds
setInterval(loadReminders, 590000);

// 🧠 helper function (time ago)
function timeAgo(dateString){
    let date = new Date(dateString);
    let seconds = Math.floor((new Date() - date) / 1000);

    let interval = Math.floor(seconds / 60);
    if(interval < 1) return "just now";
    if(interval < 60) return interval + " mins ago";

    interval = Math.floor(interval / 60);
    if(interval < 24) return interval + " hrs ago";

    interval = Math.floor(interval / 24);
    return interval + " days ago";
}

$(document).on('click', '.viewReminder', function(){

    let reminder_id = $(this).data('id');

    $.ajax({
        url: "/reminder_info",
        type: "POST",
        data: {
            reminder_id: reminder_id,
            _token: "{{ csrf_token() }}"
        },
        success: function(response){

            $("#view_title").text(response.reminders_title);
            $("#view_content").text(response.reminders_content);
            $("#view_date").text(response.reminder_date);

            $("#viewReminderModal").modal('show');
			mark_as_read(reminder_id);
        }
    });

});

function mark_as_read(reminder_id){

    let id = reminder_id;

    $.post('/mark_as_read', {
        reminder_id: id,
        _token: "{{ csrf_token() }}"
    });

			
    setTimeout(() => {
        loadReminders(); // refresh UI
    }, 300);
	
}

function formatDate(dateString){
    let date = new Date(dateString);
    return date.toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

$(document).on('click', '#viewReminder_tb', function(){

    let reminder_id = $(this).data('id');

    $.ajax({
        url: "/reminder_info",
        type: "POST",
        data: {
            reminder_id: reminder_id,
            _token: "{{ csrf_token() }}"
        },
        success: function(response){

            $("#view_title").text(response.reminders_title);
            $("#view_content").text(response.reminders_content);
            $("#view_date").text(formatDate(response.reminder_date));

            $("#viewReminderModal").modal('show');
        }
    });

});

</script>	