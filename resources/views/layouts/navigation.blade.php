
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center navbar_header_bg">

    <div class="d-flex align-items-center justify-content-between">
	<i class="bi bi-list toggle-sidebar-btn toggle_sidebar"></i>
      <div class="logo d-flex align-items-center">
		<img src="{{asset('client_logo/logo.png')}}" class="rounded" alt="..." style="width:145px;">
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
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
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

      <li class="nav-item">
        <a class="nav-link collapsed navbar_bg" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-gear navbar_icon"></i><span>Maintenance</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
		
			<a href="{{ route('billing') }}" class="sidebar_li_a">
              <i class="bi bi-circle"></i><span>Billing</span>
            </a>
		
            <a href="{{ route('product') }}" class="sidebar_li_a">
              <i class="bi bi-circle"></i><span>Product</span>
            </a>
			
			<a href="{{ route('client') }}" class="sidebar_li_a">
              <i class="bi bi-circle"></i><span>Client</span>
            </a>
			<!--
			<a href="components-alerts.html" class="sidebar_li_a">
              <i class="bi bi-circle"></i><span>User</span>
            </a>
			-->
          </li>
        </ul>
      </li><!-- End Components Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed navbar_bg" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-bar-chart navbar_icon"></i><span>Reports</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="charts-chartjs.html" class="sidebar_li_a">
              <i class="bi bi-circle"></i><span>Billing</span>
            </a>
          </li>
        </ul>
      </li><!-- End Charts Nav -->

    </ul>

  </aside><!-- End Sidebar-->
