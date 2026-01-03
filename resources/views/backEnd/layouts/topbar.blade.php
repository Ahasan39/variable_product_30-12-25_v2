<div class="navbar-custom">
        <div class="container-fluid">
          <ul class="list-unstyled topnav-menu float-end mb-0">
            <li class="dropdown d-inline-block d-lg-none">
              <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <i class="fe-search noti-icon"></i>
              </a>
              <div class="dropdown-menu dropdown-lg dropdown-menu-end p-0">
                <form class="p-3">
                  <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username" />
                </form>
              </div>
            </li>

            <li class="dropdown d-none d-lg-inline-block">
              <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="fullscreen" href="#">
                <i class="fe-maximize noti-icon"></i>
              </a>
            </li>

            <li class="dropdown notification-list topbar-dropdown">
              <a class="nav-link dropdown-toggle waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <i class="fe-bell noti-icon"></i>
                <span class="badge bg-danger rounded-circle noti-icon-badge">{{$neworder}}</span>
              </a>
              <div class="dropdown-menu dropdown-menu-end dropdown-lg">
                <!-- item-->
                <div class="dropdown-item noti-title">
                  <h5 class="m-0">
                    <span class="float-end">
                      <a href="{{route('admin.orders',['slug'=>'pending'])}}" class="text-dark">
                        <small>View All</small>
                      </a>
                    </span>
                    Orders
                  </h5>
                </div>

                <div class="noti-scroll" data-simplebar>
                  @foreach($pendingorder as $porder)
                  <!-- item-->
                  <a href="{{route('admin.orders',['slug'=>'pending'])}}" class="dropdown-item notify-item active">
                    <div class="notify-icon">
                      <img src="{{asset($porder->customer?$porder->customer->image:'')}}" class="img-fluid rounded-circle" alt="" />
                    </div>
                    <p class="notify-details">{{$porder->customer?$porder->customer->name:''}}</p>
                    <p class="text-muted mb-0 user-msg">
                      <small>Invoice : {{$porder->invoice_id}}</small>
                    </p>
                  </a>
                  @endforeach

                  <!-- item-->
                </div>

                <!-- All-->
                <a href="{{route('admin.orders',['slug'=>'pending'])}}" class="dropdown-item text-center text-primary notify-item notify-all">
                  View all
                  <i class="fe-arrow-right"></i>
                </a>
              </div>
            </li>

            <li class="dropdown notification-list topbar-dropdown">
              <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <img src="{{asset(Auth::user()->image)}}" alt="user-image" class="rounded-circle" />
                <span class="pro-user-name ms-1"> {{Auth::user()->name}} <i class="mdi mdi-chevron-down"></i> </span>
              </a>
              <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                <!-- item-->
                <div class="dropdown-header noti-title">
                  <h6 class="text-overflow m-0">Welcome !</h6>
                </div>

                <!-- item-->
                <a href="{{route('dashboard')}}" class="dropdown-item notify-item">
                  <i class="fe-user"></i>
                  <span>Dashboard</span>
                </a>

                <!-- item-->
                <a href="{{route('change_password')}}" class="dropdown-item notify-item">
                  <i class="fe-settings"></i>
                  <span>Change Password</span>
                </a>

                <!-- item-->
                <a href="{{route('locked')}}" class="dropdown-item notify-item">
                  <i class="fe-lock"></i>
                  <span>Lock Screen</span>
                </a>

                <div class="dropdown-divider"></div>

                <!-- item-->
                <a
                  href="{{ route('logout') }}"
                  onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();"
                  class="dropdown-item notify-item"
                >
                  <i class="fe-log-out me-1"></i>
                  <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
                </form>
              </div>
            </li>

            <!--<li class="dropdown notification-list">-->
            <!--    <a href="javascript:void(0);" class="nav-link right-bar-toggle waves-effect waves-light">-->
            <!--        <i class="fe-settings noti-icon"></i>-->
            <!--    </a>-->
            <!--</li>-->
          </ul>

          <!-- LOGO -->
          <div class="logo-box">
            <a href="{{url('admin/dashboard')}}" class="logo logo-dark text-center">
              <span class="logo-sm">
                <img src="{{asset($generalsetting->white_logo)}}" alt="" height="50" />
                <!-- <span class="logo-lg-text-light">UBold</span> -->
              </span>
              <span class="logo-lg">
                <img src="{{asset($generalsetting->dark_logo)}}" alt="" height="50" />
                <!-- <span class="logo-lg-text-light">U</span> -->
              </span>
            </a>

            <a href="{{url('admin/dashboard')}}" class="logo logo-light text-center">
              <span class="logo-sm">
                <img src="{{asset($generalsetting->white_logo)}}" alt="" height="50" />
              </span>
              <span class="logo-lg">
                <img src="{{asset($generalsetting->white_logo)}}" alt="" height="50" />
              </span>
            </a>
          </div>

          <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
            <li>
              <button class="button-menu-mobile waves-effect waves-light">
                <i class="fe-menu"></i>
              </button>
            </li>

            <li>
              <!-- Mobile menu toggle (Horizontal Layout)-->
              <a class="navbar-toggle nav-link" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <div class="lines">
                  <span></span>
                  <span></span>
                  <span></span>
                </div>
              </a>
              <!-- End mobile menu toggle-->
            </li>

            <li class="dropdown d-none d-xl-block">
              <a class="nav-link dropdown-toggle waves-effect waves-light" href="{{route('home')}}" target="_blank"> <i data-feather="globe"></i> Visit Site </a>
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
      </div>