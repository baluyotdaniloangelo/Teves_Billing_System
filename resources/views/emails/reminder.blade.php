<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reminder</title>
</head>
<body style="font-family: Arial, sans-serif;">
<div style="padding:20px;border:1px solid #ddd;border-radius:8px;">
    <h2>🔔 Reminder Notification</h2>

    <p><strong>Title:</strong> {{ $reminder->reminders_title }}</p>

    <p><strong>Content:</strong><br>
        {{ $reminder->reminders_content }}
    </p>

    <p><strong>Date:</strong>
        {{ \Carbon\Carbon::parse($reminder->reminder_date)->format('M d, Y h:i A') }}
    </p>

    <hr>

    <p style="font-size:12px;color:gray;">
        This is an automated reminder from your system.
    </p>
</div>
</body>
</html>