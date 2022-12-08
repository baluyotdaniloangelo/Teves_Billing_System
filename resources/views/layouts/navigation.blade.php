
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center navbar_header_bg">

    <div class="d-flex align-items-center justify-content-between">
	<i class="bi bi-list toggle-sidebar-btn toggle_sidebar"></i>
      <div class="logo d-flex align-items-center">
		<img src="{{asset('client_logo/logo.png')}}" class="rounded" alt="..." style="width:125px;">
        <span class="d-lg-block navbar_header_title">Billing System</span>
      </div>
      
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">


        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <!--<img src="{{asset('NiceAdmin-pro/assets/img/profile-img.jpg')}}" alt="Profile" class="rounded-circle">-->
			<span class="bi bi-person-circle rounded-circle" style="color: #c0ff00 !important; font-size: 30px;"></span>			
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

    <ul class="sidebar-nav" id="sidebar-nav">

	  <li class="nav-item ">
        <a class="nav-link navbar_bg" href="{{ route('billing') }}">
          <i class="bi bi-file-spreadsheet navbar_icon"></i>
          <span>Billing</span>
        </a>
      </li>

	  <li class="nav-item ">
        <a class="nav-link navbar_bg" href="{{ route('report') }}">
          <i class="bi bi-graph-up navbar_icon"></i>
          <span>Report</span>
        </a>
      </li>
	  
      <li class="nav-item">
        <a class="nav-link collapsed navbar_bg" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-gear navbar_icon"></i><span>Maintenance</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
		
            <a href="{{ route('product') }}" class="sidebar_li_a">
              <i class="bi bi-cart navbar_icon"></i><span>Product</span>
            </a>
			<?php if($data->user_type=="Admin"){ ?>
			<a href="{{ route('client') }}" class="sidebar_li_a">
              <i class="bi bi-file-person navbar_icon"></i><span>Client</span>
            </a>
			
			<a href="{{ route('user') }}" class="sidebar_li_a">
              <i class="bi bi-people navbar_icon"></i><span>User</span>
            </a>
			<?php } ?>

          </li>
        </ul>
      </li><!-- End Components Nav -->

	  <!-- End Charts Nav -->

    </ul>

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
						  <label for="account_user_password" class="col-sm-3 col-form-label">Password:</label>
						  <div class="col-sm-9">
							<input type="password" placeholder="Optional" class="form-control " name="account_user_password" id="account_user_password" value="">
							<span class="valid-feedback" id="account_user_passwordError"></span>
						  </div>
						</div>
						
						</div>
						
                    <div class="modal-footer modal-footer_form">
						
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill navbar_icon" id="account-user"> Submit</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill navbar_icon" id="clear-user-account"> Clear Form</button>
						  
					</div>
					</form><!-- End Multi Columns Form -->
                  </div>
                </div>
                  </div>