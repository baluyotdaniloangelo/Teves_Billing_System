@include('layouts.header')
</head>
<!--
Use to automatically hide the Side Nav Bar
<body class="toggle-sidebar">-->

@yield('content')


<?php

if (Request::is('dashboard')){
?>
<body class="">
@include('layouts.footer')
<?php
}
else if (Request::is('billing')){
?>
<body class="">
@include('layouts.footer')
@include('layouts.billing_script')
<?php
}else if (Request::is('so')){
?>
<body class="">
@include('layouts.footer')
@include('layouts.so_billing_script')
<?php
}
else if (Request::is('create_so_billing')){
?>
<body class="">
@include('layouts.footer')
@include('layouts.so_billing_script')
<?php
}
else if (Request::is('so_add_product/*')){
?>
<body class="">
@include('layouts.footer')
@include('layouts.update_so_billing_script')
<?php
}
else if (Request::is('product')){
?>
<body class="">
@include('layouts.footer')
@include('layouts.product_script')
<?php
}
else if (Request::path()==('update_product_information')){
?>
<body class="">
@include('layouts.footer')
@include('layouts.update_product_info_script')
@include('layouts.update_product_info_suppiers_price_script')
@include('layouts.update_product_info_selling_price_script')
@include('layouts.update_product_info_pump_script')
<?php
}
else if (Request::is('client')){
?>
<body class="">
@include('layouts.footer')
@include('layouts.client_script')
<?php
}
else if (Request::is('branch')){
?>
<body class="">
@include('layouts.footer')
@include('layouts.branch_script')
<?php
}
else if (Request::is('supplier')){
?>
<body class="">
@include('layouts.footer')
@include('layouts.supplier_script')
<?php
}
else if (Request::is('reminder')){
?>
<body class="">
@include('layouts.footer')
@include('layouts.reminder_script')
<?php
}
else if (Request::is('create_recievable')){
?>
<body class="">
@include('layouts.footer')
@include('layouts.create_recievable_script_v2')
<?php
}
else if (Request::is('billing_history')){
?>
<body class="">
@include('layouts.footer')
@include('layouts.billing_history_script')
<?php
}else if (Request::is('daily_sales')){
?>
<body class="">
@include('layouts.footer')
@include('layouts.daily_sales_script')
<?php
}
else if (Request::is('cash_report_page')){
?>
<body class="">
@include('layouts.footer')
@include('layouts.non-cash_report_script')
@include('layouts.cashdrop_report_script')
<?php
}
else if (Request::is('sales_order_summary')){
?>
<body class="">
@include('layouts.footer')
@include('layouts.sales_order_summary_script')
<?php
}
else if (Request::is('sales_report')){
?>
<body class="">
@include('layouts.footer')
@include('layouts.sales_report_script')
<?php
}
else if (Request::is('purchase_order_summary')){
?>
<body class="">
@include('layouts.footer')
@include('layouts.purchase_order_summary_script')
<?php
}
else if (Request::is('soa_summary_history')){
?>
<body class="">
@include('layouts.footer')
@include('layouts.soa_summary_report_script')
<?php
}
else if (Request::is('user')){
?>
<body class="">
@include('layouts.footer')
@include('layouts.user_script')
<?php
}
else if (Request::is('receivables')){
?>
<body class="">
@include('layouts.footer')
@include('layouts.receivables_script')
<?php
}
else if (Request::is('salesorder')){
?>
<body class="">
@include('layouts.footer')
@include('layouts.salesorder_script_v2')
<?php
}
/* Request::path(); for Get Method*/
else if (Request::path()==('sales_order_form')){
?>
<body class="">
@include('layouts.footer')
@include('layouts.sales_order_form_delivery_script')
@include('layouts.sales_order_form_script')

<?php
}
/* Request::path(); for Get Method*/
else if (Request::path()==('receivable_from_billing_form')){
?>
<body class="">
@include('layouts.footer')
@include('layouts.receivable_from_billing_form_script')
<?php
}

else if (Request::is('purchaseorder')){
?>
<body class="">
@include('layouts.footer')
@include('layouts.purchaseorder_script')
<?php
}
else if (Request::is('purchaseorder_v2')){
	?>
	<body class="">
	@include('layouts.footer')
	@include('layouts.purchaseorder_script_v2')
	<?php
}
else if (Request::is('purchase_order_form/*')){
	?>
	<meta name="csrf-token" content="{{csrf_token()}}">
	<body class="">
	@include('layouts.footer')
	@include('layouts.purchase_order_form_script')
	@include('layouts.purchase_order_form_delivery_script')
	<?php
}
else if (Request::is('cashier_report')){
?>
<body class="">
@include('layouts.footer')
@include('layouts.cashier_report_script')
<?php
}
else if (Request::is('cashiers_report_form/*')){
?>
<body class="">
@include('layouts.footer')
@include('layouts.cashier_report_form_script')
@include('layouts.cashier_report_form_script_p1')
@include('layouts.cashier_report_form_script_p7')
@include('layouts.cashier_report_form_script_p8_v01212026')
<?php
}
else if (Request::is('monthly_sales')){
?>
<body class="">
@include('layouts.footer_chart')
<script type="text/javascript">

	var original_api_url = {{ $MonthlyChart->id }}_api_url;
	/*On Load page view current year*/
 
    $(".select_year").change(function(){
        var year = $(this).val();
    {{ $MonthlyChart->id }}_refresh(original_api_url + "?year="+year);
    });
	
	$('body').on('click','#reloadMonthlyData',function(){
			
			event.preventDefault();
			let year 		= $("#select_year").val();
			
			  $.ajax({
				url: "/reload_monthly_sales_per_year",
				type:"POST",
				data:{
				  year:year,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);

					/*Reload Chart*/
					reload_monthly_chart(year);

				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	});
	
	 function reload_monthly_chart(year){
		 {{ $MonthlyChart->id }}_refresh(original_api_url + "?year="+year);
	 }
	 
</script>

<?php
}
?>
<style>
.notifications {
    width: 320px;
}

/* base item */
.notification-item {
    padding: 10px 12px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
    border-radius: 0px;
}

/* hover */
.notification-item:hover {
    background: #f5f7ff;
}

/* 🔵 unread */
.notif-unread {
    background: #eef4ff;
    border-left: 4px solid #4e73df;
    font-weight: 500;
}

/* 🔴 overdue */
.notif-overdue {
    background: #ffecec;
    border-left: 4px solid #e74a3b;
}

/* 🟢 read (default) */
.notification-item h6 {
    margin: 0;
    font-size: 14px;
    font-weight: 600;
}

.notification-item p {
    margin: 0;
    font-size: 12px;
    color: #6c757d;
}

/* 🔵 unread dot */
.notif-dot {
    width: 8px;
    height: 8px;
    background: #4e73df;
    border-radius: 50%;
    margin-top: 5px;
}

/* icon spacing */
.notification-item i {
    font-size: 18px;
    margin-top: 2px;
}
.badge-number {
    background: linear-gradient(135deg, #4e73df, #1cc88a);
}
</style>
<script>
	const AccountUserform = document.querySelector('#AccountUserform');
	
	AccountUserform.addEventListener('change', function() {
		//enable
		document.getElementById("account-user").disabled = false;
	});

	<!--Selected Account For Update-->
	$('body').on('click','#accountUser',function(){
			
			event.preventDefault();
		
			  $.ajax({
				url: "/user_info",
				type:"POST",
				data:{
				  UserID:{{$data->user_id}},
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					document.getElementById("account-user").disabled = true;
					document.getElementById("account-user").value = {{$data->user_id}};
					
					/*Set Switch Details*/
					document.getElementById("account_user_real_name").value = response.user_real_name;
					document.getElementById("account_user_name").value = response.user_name;
					document.getElementById("user_email_address").value = response.user_email_address;
					
					$('#UserProfileModal').modal('toggle');					
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });		
	  });

	$("#account-user").click(function(event){
			
			event.preventDefault();
			
					/*Reset Warnings*/
					let userID = document.getElementById("account-user").value;
					$('#account_user_real_nameError').text('');				  
					$('#account_user_nameError').text('');
					$('#account_user_passwordError').text('');

			document.getElementById('AccountUserform').className = "g-2 needs-validation was-validated";

			let user_real_name 		= $("input[name=account_user_real_name]").val();
			let user_name 			= $("input[name=account_user_name]").val();
			let user_password 		= $("input[name=account_user_password]").val();
			let user_email_address 		= $("input[name=user_email_address]").val();
			
			$.ajax({
				url: "/user_account_post",
				type:"POST",
				data:{
				  userID:userID,
				  user_real_name:user_real_name,
				  user_name:user_name,
				  user_password:user_password,
				  user_email_address:user_email_address,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  					
					$('#account_user_real_nameError').text('');
					$('#account_switch_timerError').text('');		
					$('#account_user_typeError').text('');
					$('#user_email_addressError').text('');	
					
					$('.success_modal_bg').html(response.success);
					$('#SuccessModal').modal('toggle');
					//$('#UserProfileModal').modal('toggle');
				  
				  }
				},
				error: function(error) {
				 console.log(error);	
				 
				if(error.responseJSON.errors.user_real_name=="The user real name has already been taken."){
							  
				  $('#account_user_real_nameError').html("<b>"+ user_real_name +"</b> has already been taken.");
				  document.getElementById('account_user_real_nameError').className = "invalid-feedback";
				  document.getElementById('account_user_real_name').className = "form-control is-invalid";
				  $('#account_user_real_name').val("");
				  
				}else{
					
				  $('#account_user_real_nameError').text(error.responseJSON.errors.user_real_name);
				  document.getElementById('account_user_real_nameError').className = "invalid-feedback";		
				
				}
				
				if(error.responseJSON.errors.user_name=="The user name has already been taken."){
							  
				  $('#account_user_nameError').html("<b>"+ user_name +"</b> has already been taken.");
				  document.getElementById('account_user_nameError').className = "invalid-feedback";
				  document.getElementById('account_user_name').className = "form-control is-invalid";
				  $('#account_user_name').val("");
				  
				}else{
					
				  $('#account_user_nameError').text(error.responseJSON.errors.user_real_name);
				  document.getElementById('account_user_nameError').className = "invalid-feedback";		
				
				}
					
				  $('#account_user_passwordError').text(error.responseJSON.errors.user_password);
				  document.getElementById('account_user_passwordError').className = "invalid-feedback";		
				  
				
				$('#InvalidModal').modal('toggle');
				  
				}
			   });
	  });

		
</script>

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
setInterval(loadReminders, 59000);

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
</body>

</html>