@php
    // Edit sidebar labels, icons, link targets and menu order in the SIDEBAR MENU below.
    // This map keeps a menu item selected on its related detail/form pages as well.
    $workspaceNavRoutes = [
        'user_activity' => ['user.activity'],
        'so' => ['so', 'create_so_billing', 'so_add_product'],
        'receivables' => ['receivables', 'create_recievable', 'receivable_from_billing_form'],
        'salesorder' => ['salesorder', 'sales_order_form'],
        'purchaseorder' => ['purchaseorder', 'purchase_order_form', 'purchase_order_forms'],
        'cashierReport' => ['cashierReport', 'cashiers_report_form', 'cashiers_report_form_fuel'],
        'cashierReportLPG' => ['cashierReportLPG', 'cashiers_report_form_lpg'],
        'lpgStartingAudits' => ['lpg.starting-audits.index', 'lpg.starting-audits.show'],
        'lpgAuditNotifications' => ['lpg.starting-audits.notifications'],
        'fuel_sales_volume_dashboard' => ['fuel_sales_volume_dashboard', 'fuel_sales_volume_dashboard.filter'],
        'product' => ['product', 'product_category', 'update_product_information'],
    ];
    $workspaceNavActive = function ($item) use ($workspaceNavRoutes) {
        return request()->routeIs(...($workspaceNavRoutes[$item] ?? [$item]));
    };
    $workspaceNavClass = function ($item) use ($workspaceNavActive) {
        return $workspaceNavActive($item) ? 'fuel-nav-active' : '';
    };
    $workspaceNavGroupOpen = function ($items) use ($workspaceNavActive) {
        foreach ($items as $item) {
            if ($workspaceNavActive($item)) return true;
        }
        return false;
    };
    $workspaceBillingOpen = $workspaceNavGroupOpen(['so', 'billing']);
    $workspaceCashierOpen = $workspaceNavGroupOpen(['cashierReport', 'cashierReportLPG']);
    $workspaceBillingReportsOpen = $workspaceNavGroupOpen(['report', 'soa_summary_history']);
    $workspaceSalesReportsOpen = $workspaceNavGroupOpen(['salesordersummary', 'daily_sales', 'cash_report_page', 'sales_report', 'MonthlySalesSummary']);
    $workspacePurchaseReportsOpen = $workspaceNavGroupOpen(['purchaseordersummary']);
    $workspaceMaintenanceOpen = $workspaceNavGroupOpen(['product', 'bank', 'branch', 'client', 'supplier', 'salesagent', 'reminder', 'user', 'user_activity']);
    $workspacePageTitle = $workspaceNavActive('product') ? 'Products & services' : ($title ?? 'Billing System');
@endphp

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center navbar_header_bg">

    <div class="d-flex align-items-center justify-content-between">
	<button type="button" class="bi bi-list toggle-sidebar-btn toggle_sidebar fuel-sidebar-toggle" aria-label="Toggle navigation" aria-controls="sidebar"></button>
      <div class="logo d-flex align-items-center">
        <span class="fuel-header-context">Workspace <span>/</span> <strong>{{ $workspacePageTitle }}</strong></span>
      </div>
      
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        @if(($data->user_type ?? null) === 'SUAdmin' && config('ui_visibility.online_status', false))
            @include('layouts.partials.online_users_shortcut')
        @endif

		<li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-bell"></i>
            <span class="badge bg-primary badge-number">0</span>
          </a><!-- End Notification Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications" style="">
            <li class="dropdown-header">
              You have 0 new notifications
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-exclamation-circle text-warning"></i>
              <div>
                <h4></h4>
                <p></p>
                <p></p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>
            <li class="dropdown-footer">
              <a href="#">Show all notifications</a>
            </li>

          </ul><!-- End Notification Dropdown Items -->

        </li>
		
        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <!--<img src="{{asset('NiceAdmin-pro/assets/img/profile-img.jpg')}}" alt="Profile" class="rounded-circle">-->
			<span class="bi bi-person-circle rounded-circle" style="color: #8c9e7b !important; font-size: 30px;"></span>
            <span class="d-none d-md-block dropdown-toggle ps-2 navbar_white_text">{{$data->user_real_name}}</span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>{{$data->user_name}}</h6>
              <span>{{$data->user_type}}</span>
            </li>
           
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" id="accountUser" style="cursor: pointer;">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>
           
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
			
              <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar sidebar_bg">

    <a class="fuel-brand" href="{{ route('dashboard') }}" aria-label="Teves Billing System dashboard">
      <span class="fuel-brand-logo"><img src="{{ asset('client_logo/logo-2.png') }}" alt=""></span>
      <!--<span>TEVES<span class="fuel-brand-dot">.</span></span>-->
    </a>
    <div class="fuel-workspace-label">BILLING &amp; OPERATIONS</div>

    <!-- SIDEBAR MENU: edit labels, icons and route links in this section. -->
    <ul class="sidebar-nav" id="sidebar-nav">

	   <?php if($data->user_type=="SUAdmin"){ ?>
	   <li class="fuel-nav-section" role="presentation">DASHBOARDS</li>
	   <li class="nav-item">
        <a class="nav-link navbar_bg {{ $workspaceNavClass('dashboard') }}" href="{{ route('dashboard') }}" title="Cashier Dashboard" @if($workspaceNavActive('dashboard')) aria-current="page" @endif>
          <i class="bi bi-speedometer2 navbar_icon"></i><span>Cashier Dashboard</span>
        </a>
       </li>
	   @if(config('ui_visibility.dashboards.sales_receivables', false))
       <li class="nav-item">
        <a class="nav-link navbar_bg {{ $workspaceNavClass('sales_receivables_dashboard') }}" href="{{ route('sales_receivables_dashboard') }}" title="Sales &amp; Receivables Dashboard" @if($workspaceNavActive('sales_receivables_dashboard')) aria-current="page" @endif>
          <i class="bi bi-bar-chart-line navbar_icon"></i><span>Sales &amp; Receivables</span>
        </a>
       </li>
       @endif
       @if(config('ui_visibility.dashboards.fuel_sales_volume', false))
       <li class="nav-item">
        <a class="nav-link navbar_bg {{ $workspaceNavClass('fuel_sales_volume_dashboard') }}" href="{{ route('fuel_sales_volume_dashboard') }}" title="Fuel Sales Volume Dashboard" @if($workspaceNavActive('fuel_sales_volume_dashboard')) aria-current="page" @endif>
          <i class="bi bi-fuel-pump navbar_icon"></i><span>Fuel Sales Volume</span>
        </a>
       </li>
       @endif
	   @if(config('ui_visibility.dashboards.procurement_expenses', false))
       <li class="nav-item">
        <a class="nav-link navbar_bg {{ $workspaceNavClass('procurement_expenses_dashboard') }}" href="{{ route('procurement_expenses_dashboard') }}" title="Procurement &amp; Expenses Dashboard" @if($workspaceNavActive('procurement_expenses_dashboard')) aria-current="page" @endif>
          <i class="bi bi-bag-check navbar_icon"></i><span>Procurement &amp; Expenses</span>
        </a>
       </li>
       @endif
	   <?php } ?>
	  
	   <li class="fuel-nav-section" role="presentation">TRANSACTIONS</li>
	   <li class="nav-item">
        <a class="nav-link {{ $workspaceBillingOpen ? '' : 'collapsed' }} navbar_bg" data-bs-target="#billing-nav" data-bs-toggle="collapse" href="#" title="Billing" aria-expanded="{{ $workspaceBillingOpen ? 'true' : 'false' }}">
          <i class="bi bi-receipt navbar_icon"></i><span title="Billing">Billing</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="billing-nav" class="nav-content collapse {{ $workspaceBillingOpen ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
        
		<li>
		
		<a class="nav-link navbar_bg sidebar_li_a {{ $workspaceNavClass('so') }}" href="{{ route('so') }}" title="SO List" @if($workspaceNavActive('so')) aria-current="page" @endif>
          <i class="bi bi-file-spreadsheet navbar_icon"></i>
          <span title="New Billing Transaction History">SO List</span>
        </a>
		
		<a class="nav-link navbar_bg sidebar_li_a {{ $workspaceNavClass('billing') }}" href="{{ route('billing') }}" title="Billing Transaction" @if($workspaceNavActive('billing')) aria-current="page" @endif>
          <i class="bi bi-file-spreadsheet navbar_icon"></i>
          <span title="iew Billing Transaction History">Billing Transaction</span>
        </a>

          </li>
        </ul>
      </li>
	  
	  <li class="nav-item ">
        <a class="nav-link navbar_bg {{ $workspaceNavClass('receivables') }}" href="{{ route('receivables') }}" title="Receivable List" @if($workspaceNavActive('receivables')) aria-current="page" @endif>
          <i class="bi bi-cash-stack navbar_icon"></i>
          <span title="Create Billing Transaction">Receivable</span>
        </a>
      </li>
	  
	  <?php if($data->user_type=="Admin" || $data->user_type=="SUAdmin" || $data->user_type=="Accounting_Staff"){ ?>
	   <li class="nav-item ">
        <a class="nav-link navbar_bg {{ $workspaceNavClass('salesorder') }}" href="{{ route('salesorder') }}" title="Ship To" @if($workspaceNavActive('salesorder')) aria-current="page" @endif>
          <i class="bi bi-cart-check navbar_icon"></i>
          <span title="Ship To">Ship To</span>
        </a>
      </li>
		
	  
		
	  <li class="nav-item ">
        <a class="nav-link navbar_bg {{ $workspaceNavClass('purchaseorder') }}" href="{{ route('purchaseorder') }}" title="Create Purchase Order" @if($workspaceNavActive('purchaseorder')) aria-current="page" @endif>
          <i class="bi bi-bag-check navbar_icon"></i>
          <span title="Create Purchases Order Order">Purchase Order</span>
        </a>
      </li>
		<?php } ?>
		


	  <li class="fuel-nav-section" role="presentation">REPORTS</li>
	  <li class="nav-item">
		<a class="nav-link {{ $workspaceCashierOpen ? '' : 'collapsed' }} navbar_bg" data-bs-target="#reports-cashier-nav" data-bs-toggle="collapse" href="#" title="Cashier's Report" aria-expanded="{{ $workspaceCashierOpen ? 'true' : 'false' }}">
	      <i class="bi bi-graph-up-arrow navbar_icon"></i><span>Cashier's Report</span><i class="bi bi-chevron-down ms-auto"></i>
	    </a>
		<ul id="reports-cashier-nav" class="nav-content collapse {{ $workspaceCashierOpen ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
	      <li>
			<a class="nav-link navbar_bg {{ $workspaceNavClass('cashierReport') }}" href="{{ route('cashierReport') }}" title="Fuel Cashier Report" @if($workspaceNavActive('cashierReport')) aria-current="page" @endif><i class="bi bi-fuel-pump navbar_icon"></i><span>Fuel</span></a>
			<a class="nav-link navbar_bg {{ $workspaceNavClass('cashierReportLPG') }}" href="{{ route('cashierReportLPG') }}" title="LPG Cashier Report" @if($workspaceNavActive('cashierReportLPG')) aria-current="page" @endif><i class="bi bi-fire navbar_icon"></i><span>LPG</span></a>
		  </li>
		</ul>
      </li>

	  <li class="nav-item">
		<a class="nav-link {{ $workspaceBillingReportsOpen ? '' : 'collapsed' }} navbar_bg" data-bs-target="#reports-billing-nav" data-bs-toggle="collapse" href="#" title="Billing and Receivables Reports" aria-expanded="{{ $workspaceBillingReportsOpen ? 'true' : 'false' }}">
	      <i class="bi bi-receipt-cutoff navbar_icon"></i><span>Billing &amp; Receivables</span><i class="bi bi-chevron-down ms-auto"></i>
	    </a>
		<ul id="reports-billing-nav" class="nav-content collapse {{ $workspaceBillingReportsOpen ? 'show' : '' }}" data-bs-parent="#sidebar-nav"><li>
			<a href="{{ route('report') }}" class="{{ $workspaceNavClass('report') }}" title="Billing History" @if($workspaceNavActive('report')) aria-current="page" @endif><i class="bi bi-file-earmark-text navbar_icon"></i><span>Billing History</span></a>
			<?php if($data->user_type!="Encoder"){ ?><a href="{{ route('soa_summary_history') }}" class="{{ $workspaceNavClass('soa_summary_history') }}" title="Statement of Account Summary" @if($workspaceNavActive('soa_summary_history')) aria-current="page" @endif><i class="bi bi-file-earmark-text navbar_icon"></i><span>SOA Summary</span></a><?php } ?>
		</li></ul>
	  </li>

	  <?php if($data->user_type!="Encoder"){ ?>
	  <li class="nav-item">
		<a class="nav-link {{ $workspaceSalesReportsOpen ? '' : 'collapsed' }} navbar_bg" data-bs-target="#reports-sales-nav" data-bs-toggle="collapse" href="#" title="Sales Reports" aria-expanded="{{ $workspaceSalesReportsOpen ? 'true' : 'false' }}">
	      <i class="bi bi-graph-up navbar_icon"></i><span>Sales Reports</span><i class="bi bi-chevron-down ms-auto"></i>
	    </a>
		<ul id="reports-sales-nav" class="nav-content collapse {{ $workspaceSalesReportsOpen ? 'show' : '' }}" data-bs-parent="#sidebar-nav"><li>
			<a href="{{ route('salesordersummary') }}" class="{{ $workspaceNavClass('salesordersummary') }}" title="Sales Order Summary" @if($workspaceNavActive('salesordersummary')) aria-current="page" @endif><i class="bi bi-bar-chart-line navbar_icon"></i><span>Sales Order</span></a>
			<a href="{{ route('daily_sales') }}" class="{{ $workspaceNavClass('daily_sales') }}" title="Daily Sales" @if($workspaceNavActive('daily_sales')) aria-current="page" @endif><i class="bi bi-calendar3 navbar_icon"></i><span>Daily Sales</span></a>
			<a href="{{ route('cash_report_page') }}" class="{{ $workspaceNavClass('cash_report_page') }}" title="Cash/Non-Cash Report" @if($workspaceNavActive('cash_report_page')) aria-current="page" @endif><i class="bi bi-cash-coin navbar_icon"></i><span>Cash/Non-Cash</span></a>
			<a href="{{ route('sales_report') }}" class="{{ $workspaceNavClass('sales_report') }}" title="Sales Report" @if($workspaceNavActive('sales_report')) aria-current="page" @endif><i class="bi bi-graph-up-arrow navbar_icon"></i><span>Sales Report</span></a>
		</li></ul>
	  </li>

	  <li class="nav-item">
		<a class="nav-link {{ $workspacePurchaseReportsOpen ? '' : 'collapsed' }} navbar_bg" data-bs-target="#reports-purchase-nav" data-bs-toggle="collapse" href="#" title="Purchase Reports" aria-expanded="{{ $workspacePurchaseReportsOpen ? 'true' : 'false' }}">
	      <i class="bi bi-bag-check navbar_icon"></i><span>Purchasing</span><i class="bi bi-chevron-down ms-auto"></i>
	    </a>
		<ul id="reports-purchase-nav" class="nav-content collapse {{ $workspacePurchaseReportsOpen ? 'show' : '' }}" data-bs-parent="#sidebar-nav"><li>
			<a href="{{ route('purchaseordersummary') }}" class="{{ $workspaceNavClass('purchaseordersummary') }}" title="Purchase Order Summary" @if($workspaceNavActive('purchaseordersummary')) aria-current="page" @endif><i class="bi bi-bar-chart-line navbar_icon"></i><span>Purchase Order</span></a>
		</li></ul>
	  </li>
	  <?php } ?>

      <li class="fuel-nav-section" role="presentation">LPG AUDIT</li>
      <li class="nav-item">
        <a class="nav-link navbar_bg {{ $workspaceNavClass('lpgAuditNotifications') }}" href="{{ route('lpg.starting-audits.notifications') }}" title="LPG Audit Notifications" @if($workspaceNavActive('lpgAuditNotifications')) aria-current="page" @endif><i class="bi bi-bell navbar_icon"></i><span>Audit Notifications</span></a>
      </li>
	  <?php if(in_array($data->user_type, ['Admin', 'SUAdmin', 'Accounting_Staff'], true)){ ?>
      <li class="nav-item">
        <a class="nav-link navbar_bg {{ $workspaceNavClass('lpgStartingAudits') }}" href="{{ route('lpg.starting-audits.index') }}" title="LPG Starting Audit Approvals" @if($workspaceNavActive('lpgStartingAudits')) aria-current="page" @endif><i class="bi bi-clipboard-check navbar_icon"></i><span>Starting Audit Approvals</span></a>
      </li>
	  <?php } ?>

	  <?php if($data->user_type=="SUAdmin"){ ?>
	  <li class="fuel-nav-section" role="presentation">COMMUNICATION</li>
	  <li class="nav-item">
	    <a class="nav-link navbar_bg {{ $workspaceNavClass('sms_blast') }}" href="{{ route('sms_blast') }}" title="SMS Blast" @if($workspaceNavActive('sms_blast')) aria-current="page" @endif><i class="bi bi-chat-left-text navbar_icon"></i><span>SMS Blast</span></a>
	  </li>
	  <?php } ?>
	  
	  <?php if($data->user_type=="Admin" || $data->user_type=="SUAdmin" || $data->user_type=="Accounting_Staff" || $data->user_type=="Encoder" ){ ?>
      <li class="fuel-nav-section" role="presentation">ADMINISTRATION</li>
      <li class="nav-item">
        <a class="nav-link {{ $workspaceMaintenanceOpen ? '' : 'collapsed' }} navbar_bg" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#" title="Manage Product, Client and System User Account" aria-expanded="{{ $workspaceMaintenanceOpen ? 'true' : 'false' }}">
          <i class="bi bi-gear-fill navbar_icon"></i><span title="Manage Product, Client and System User Account">Maintenance</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse {{ $workspaceMaintenanceOpen ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
          <li>
		
			<?php if($data->user_type=="Admin" || $data->user_type=="SUAdmin" || $data->user_type=="Accounting_Staff"){ ?>


			<?php if($data->user_type=="Admin" || $data->user_type=="SUAdmin"){ ?>
			<a href="{{ route('product', ['tab' => 'product-variants-tab']) }}" class="sidebar_li_a {{ $workspaceNavClass('product') }}" title="Manage Product Categories" @if($workspaceNavActive('product')) aria-current="page" @endif>
	              <i class="bi bi-tags navbar_icon" title="Manage Product Categories"></i><span>Products and Services</span>
	            </a>
			<?php } ?>
			<a href="{{ route('bank') }}" class="sidebar_li_a {{ $workspaceNavClass('bank') }}" title="Manage Bank list" @if($workspaceNavActive('bank')) aria-current="page" @endif>
              <i class="bi bi-bank2 navbar_icon" title="Manage Bank list"></i><span>Bank</span>
            </a>
			<?php } ?>
			<?php if($data->user_type=="Admin" || $data->user_type=="SUAdmin"){ ?>

				<a href="{{ route('branch') }}" class="sidebar_li_a {{ $workspaceNavClass('branch') }}" title="Manage Branch list" @if($workspaceNavActive('branch')) aria-current="page" @endif>
					<i class="bi bi-building navbar_icon"></i>
					<span>Branch</span>
				</a>

			<?php } ?>

			<?php if($data->user_type=="Admin" || $data->user_type=="SUAdmin" || $data->user_type=="Encoder"){ ?>

				<a href="{{ route('client') }}" class="sidebar_li_a {{ $workspaceNavClass('client') }}" title="Manage Client list" @if($workspaceNavActive('client')) aria-current="page" @endif>
					<i class="bi bi-person-badge navbar_icon"></i>
					<span>Client</span>
				</a>

			<?php } ?>

			<?php if($data->user_type=="Admin" || $data->user_type=="SUAdmin"){ ?>

				<a href="{{ route('supplier') }}" class="sidebar_li_a {{ $workspaceNavClass('supplier') }}" title="Manage Supplier list" @if($workspaceNavActive('supplier')) aria-current="page" @endif>
					<i class="bi bi-truck navbar_icon"></i>
					<span>Supplier</span>
				</a>

				<a href="{{ route('salesagent') }}" class="sidebar_li_a {{ $workspaceNavClass('salesagent') }}" title="Sales Agent list" @if($workspaceNavActive('salesagent')) aria-current="page" @endif>
					<i class="bi bi-person-badge navbar_icon"></i>
					<span>Sales Agent</span>
				</a>

				<a href="{{ route('reminder') }}" class="sidebar_li_a {{ $workspaceNavClass('reminder') }}" title="Reminders" @if($workspaceNavActive('reminder')) aria-current="page" @endif>
					<i class="bi bi-bell navbar_icon"></i>
					<span>Reminders</span>
				</a>

				<a href="{{ route('user') }}" class="sidebar_li_a {{ $workspaceNavClass('user') }}" title="Manage System User" @if($workspaceNavActive('user')) aria-current="page" @endif>
					<i class="bi bi-people-fill navbar_icon"></i>
					<span>User's Account</span>
				</a>
                @if($data->user_type === 'SUAdmin' && config('ui_visibility.activity_center', false))
                <a href="{{ route('user.activity') }}" class="sidebar_li_a {{ $workspaceNavClass('user_activity') }}" title="User activity history" @if($workspaceNavActive('user_activity')) aria-current="page" @endif><i class="bi bi-clock-history navbar_icon" aria-hidden="true"></i><span>User Activity</span></a>
                @endif

			<?php } ?>

          </li>
        </ul>
      </li><!-- End Components Nav -->
	  <?php } ?>

    </ul>

    <div class="fuel-sidebar-note"><i class="bi bi-buildings" aria-hidden="true"></i><!--<strong>Every shift. Accounted for.</strong><p>Your station numbers,<br>in one clear view.</p>!--><small>TEVES BILLING SYSTEM</small></div>
  </aside><!-- End Sidebar-->
	<!--Modal to User Profile-->
	<div class="modal fade" id="UserProfileModal" tabindex="-1">
           <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header modal-header_form">
                      <h5 class="modal-title">Account Settings</h5>
					  <div class="btn-group" role="group" aria-label="Basic outlined example">		
						<button type="button" class="btn btn-danger bi bi-x-circle navbar_icon" data-bs-dismiss="modal"></button>
					  </div>
                    </div>
                    <div class="modal-body">

					  <form class="g-2 needs-validation" id="AccountUserform">
					  
						<div class="row mb-2">
						  <label for="account_user_real_name" class="col-sm-3 col-form-label" title="Switch Name">Name:</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control" name="account_user_real_name" id="account_user_real_name" value="" required>
							<span class="valid-feedback" id="account_user_real_nameError" title="Required"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="account_user_name" class="col-sm-3 col-form-label">User Name:</label>
						  <div class="col-sm-9">
							<input type="text" class="form-control " name="account_user_name" id="account_user_name" value="" required>
							<span class="valid-feedback" id="account_user_nameError"></span>
						  </div>
						</div>

						<div class="row mb-2">
						  <label for="user_email_address" class="col-sm-3 col-form-label" title="You Valid Email Address">Email Address:</label>
						  <div class="col-sm-9">
							<input type="email" class="form-control " name="user_email_address" id="user_email_address" value="" placeholder="Email Address">
							<span class="valid-feedback" id="user_email_addressError" title="Required"></span>
						  </div>
						</div>

						<div class="row mb-2">
						  <label for="account_user_password" class="col-sm-3 col-form-label">Password:</label>
						  <div class="col-sm-9">
							<input type="password" placeholder="Optional" class="form-control " name="account_user_password" id="account_user_password" value="">
							<span class="valid-feedback" id="account_user_passwordError"></span>
						  </div>
						</div>
						
						</div>
						
                    <div class="modal-footer modal-footer_form">
						
						  <button type="submit" class="btn btn-success bi bi-save-fill navbar_icon form_button_icon" id="account-user"> Submit</button>
						  <button type="reset" class="btn btn-primary bi bi-backspace-fill navbar_icon  form_button_icon" id="clear-user-account"> Clear Form</button>
						  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
    </div>
	


<div class="modal fade" id="viewReminderModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg border-0 rounded-4">

      <!-- HEADER -->
      <div class="modal-header text-white rounded-top-4" style="background: linear-gradient(135deg, #4e73df, #1cc88a);">
        <h5 class="modal-title fw-bold" id="view_title"></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- BODY -->
      <div class="modal-body">

        <!-- ICON + DATE -->
        <div class="d-flex align-items-center mb-3">
          <div class="me-3">
            <i class="bi bi-bell-fill fs-2 text-primary"></i>
          </div>
          <div>
            <small class="text-muted" id="view_date"></small>
          </div>
        </div>

        <!-- CONTENT -->
        <div class="p-3 rounded-3 bg-light border">
          <p id="view_content" style="white-space: pre-line;"></p>
        </div>

      </div>

      <!-- FOOTER -->
      <div class="modal-footer justify-content-between">

        <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
          Close
        </button>
		<!--
        <button id="markAsDoneBtn" class="btn btn-success btn-sm">
          ✔ Mark as Done
        </button>
		-->
      </div>

    </div>
  </div>
</div>

<style>
.modal-content {
    animation: fadeInUp 0.3s ease;
}

@keyframes fadeInUp {
    from {
        transform: translateY(20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}


</style>
