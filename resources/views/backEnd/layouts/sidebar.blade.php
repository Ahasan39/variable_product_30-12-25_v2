<div class="left-side-menu position-fixed">
        <div class="h-100" data-simplebar>
          <!-- User box -->
          <div class="user-box text-center">
            <img src="{{asset('public/backEnd/')}}/assets/images/users/user-1.jpg" alt="user-img" title="Mat Helme" class="rounded-circle avatar-md" />
            <div class="dropdown">
              <a href="javascript: void(0);" class="text-dark dropdown-toggle h5 mt-2 mb-1 d-block" data-bs-toggle="dropdown">{{Auth::user()->name}}</a>
              <div class="dropdown-menu user-pro-dropdown">
                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                  <i class="fe-user me-1"></i>
                  <span>My Account</span>
                </a>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                  <i class="fe-settings me-1"></i>
                  <span>Settings</span>
                </a>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                  <i class="fe-lock me-1"></i>
                  <span>Lock Screen</span>
                </a>

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
            </div>
            <p class="text-muted">Admin Head</p>
          </div>

          <!--- Sidemenu -->
          <div id="sidebar-menu">
            <ul id="side-menu">
              <li>
                <a href="{{url('admin/dashboard')}}" data-bs-toggle="collapse">
                  <i data-feather="airplay"></i>
                  <span> Dashboard </span>
                </a>
              </li>

              <li>
                <a href="#sidebar-orders" data-bs-toggle="collapse">
                  <i data-feather="shopping-cart"></i>
                  <span> Orders </span>
                  <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebar-orders">
                  <ul class="nav-second-level">
                    <li>
                      <a href="{{route('admin.orders',['slug'=>'all'])}}"><i data-feather="file-plus"></i> All Order</a>
                    </li>
                    @foreach($orderstatus as $value)
                    <li>
                      <a href="{{route('admin.orders',['slug'=>$value->slug])}}"><i data-feather="file-plus"></i>{{$value->name}}</a>
                    </li>
                    @endforeach
                  </ul>
                </div>
              </li>
              <!-- nav items -->
              <li>
                <a href="#siebar-product" data-bs-toggle="collapse">
                  <i data-feather="database"></i>
                  <span> Products </span>
                  <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="siebar-product">
                  <ul class="nav-second-level">
                    <li>
                      <a href="{{route('products.index')}}"><i data-feather="file-plus"></i> Product Manage</a>
                    </li>
                    <li>
                      <a href="{{route('categories.index')}}"><i data-feather="file-plus"></i> Categories</a>
                    </li>
                    <li>
                      <a href="{{route('subcategories.index')}}"><i data-feather="file-plus"></i> Subcategories</a>
                    </li>
                    <li>
                      <a href="{{route('childcategories.index')}}"><i data-feather="file-plus"></i> Childcategories</a>
                    </li>
                    <li>
                      <a href="{{route('brands.index')}}"><i data-feather="file-plus"></i> Brands</a>
                    </li>
                    <li>
                      <a href="{{route('colors.index')}}"><i data-feather="file-plus"></i> Colors</a>
                    </li>
                    <li>
                      <a href="{{route('sizes.index')}}"><i data-feather="file-plus"></i> Sizes</a>
                    </li>
                    
                    <li>
                      <a href="{{route('products.price_edit')}}"><i data-feather="file-plus"></i> Price Edit</a>
                    </li>
                    
                  </ul>
                </div>
              </li>
              <!-- nav items end -->
              @php
                $pending_reviews = \App\Models\Review::where('status', 'pending')->count();
              @endphp
              <li>
                <a href="#sidebar-product-review" data-bs-toggle="collapse">
                  <i data-feather="star"></i>
                  <span> Reviews </span>
                  <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebar-product-review">
                  <ul class="nav-second-level">                   
                    <li>
                      <a href="{{route('reviews.pending')}}"><i data-feather="file-plus"></i> Pending Reviews ({{ $pending_reviews }})</a>
                    </li>
                    <li>
                      <a href="{{route('reviews.pending')}}"><i data-feather="file-plus"></i> Create</a>
                    </li>
                    <li>
                      <a href="{{route('reviews.index')}}"><i data-feather="file-plus"></i> All Reviews</a>
                    </li>
                  </ul>
                </div>
              </li>
              <!-- nav items end -->
              <li>
                <a href="#sidebar-landing-page" data-bs-toggle="collapse">
                  <i data-feather="airplay"></i>
                  <span> Landing Page </span>
                  <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebar-landing-page">
                  <ul class="nav-second-level">                   
                    
                    <li>
                      <a href="{{route('campaign.create')}}"><i data-feather="file-plus"></i> Create</a>
                    </li>
                    <li>
                      <a href="{{route('campaign.index')}}"><i data-feather="file-plus"></i> Campaign</a>
                    </li>
                  </ul>
                </div>
              </li>
              <!-- nav items end -->
              
              <li>
                <a href="#sidebar-users" data-bs-toggle="collapse">
                  <i data-feather="user"></i>
                  <span> Users </span>
                  <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebar-users">
                  <ul class="nav-second-level">
                    <li>
                      <a href="{{route('users.index')}}"><i data-feather="file-plus"></i> User</a>
                    </li>
                    <li>
                      <a href="{{route('roles.index')}}"><i data-feather="file-plus"></i> Roles</a>
                    </li>
                    <li>
                      <a href="{{route('permissions.index')}}"><i data-feather="file-plus"></i> Permissions</a>
                    </li>
                    <li>
                      <a href="{{route('customers.index')}}"><i data-feather="file-plus"></i> Customers</a>
                    </li>
                  </ul>
                </div>
              </li>
              
              <!-- nav items -->
              <li>
                  <a href="{{route('admin.messages')}}"><i data-feather="file-plus"></i> Messages</a> 
              </li>
              <!-- nav items -->
              <li>
                <a href="#siebar-sitesetting" data-bs-toggle="collapse">
                  <i data-feather="settings"></i>
                  <span> Site Setting </span>
                  <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="siebar-sitesetting">
                  <ul class="nav-second-level">
                    <li>
                      <a href="{{route('settings.index')}}"><i data-feather="file-plus"></i> General Setting</a>
                    </li>
                    <li>
                      <a href="{{route('pixels.index')}}"><i data-feather="file-plus"></i> Pixels Setting</a>
                    </li>
                    <li>
                      <a href="{{route('socialmedias.index')}}"><i data-feather="file-plus"></i> Social Media</a>
                    </li>
                    <li>
                      <a href="{{route('contact.index')}}"><i data-feather="file-plus"></i> Contact</a>
                    </li>
                    <li>
                      <a href="{{route('pages.index')}}"><i data-feather="file-plus"></i> Create Page</a>
                    </li>
                    <!--<li>-->
                    <!--  <a href="{{route('shippingcharges.index')}}"><i data-feather="file-plus"></i> Shipping Charge</a>-->
                    <!--</li>-->
                    <li>
                      <a href="{{route('shippingcharges.upazilaWise')}}"><i data-feather="file-plus"></i> Shipping Charge</a>
                    </li>
                    <li>
                      <a href="{{route('orderstatus.index')}}"><i data-feather="file-plus"></i> Order Status</a>
                    </li>
                  </ul>
                </div>
              </li>
              <!-- nav items end -->
              <li>
                <a href="#sidebar-api-integration" data-bs-toggle="collapse">
                  <i data-feather="save"></i>
                  <span> API Integration </span>
                  <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebar-api-integration">
                  <ul class="nav-second-level">                   
                    <li>
                      <a href="{{route('paymentgeteway.manage')}}"><i data-feather="file-plus"></i> Payment Gateway</a>
                    </li>
                    <li>
                      <a href="{{route('smsgeteway.manage')}}"><i data-feather="file-plus"></i> SMS Gateway</a>
                    </li>
                    <li>
                      <a href="{{route('courierapi.manage')}}"><i data-feather="file-plus"></i> Courier API</a>
                    </li>
                  </ul>
                </div>
              </li>
              <!-- nav items end -->
              <li>
                <a href="#sidebar-pixel-gtm" data-bs-toggle="collapse">
                  <i data-feather="save"></i>
                  <span> G. Pixel and GTM </span>
                  <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebar-pixel-gtm">
                  <ul class="nav-second-level">                   
                    <li>
                      <a href="{{route('tagmanagers.index')}}"><i data-feather="file-plus"></i> Tag Manager</a>
                    </li>
                    <li>
                      <a href="{{route('pixels.index')}}"><i data-feather="file-plus"></i> Pixel Manage</a>
                    </li>
                  </ul>
                </div>
              </li>
              <!-- nav items end -->
              <li>
                <a href="#siebar-banner" data-bs-toggle="collapse">
                  <i data-feather="image"></i>
                  <span> Banner & Ads </span>
                  <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="siebar-banner">
                  <ul class="nav-second-level">
                    <li>
                      <a href="{{route('banner_category.index')}}"><i data-feather="file-plus"></i> Banner Category</a>
                    </li>
                    <li>
                      <a href="{{route('banners.index')}}"><i data-feather="file-plus"></i> Banner & Ads</a>
                    </li>
                  </ul>
                </div>
              </li>
              <!-- nav items end -->
              <li>
                <a href="#sitebar-report" data-bs-toggle="collapse">
                  <i data-feather="pie-chart"></i>
                  <span> Reports </span>
                  <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sitebar-report">
                  <ul class="nav-second-level">
                    <li>
                      <a href="{{route('admin.stock_report')}}"><i data-feather="file-plus"></i> Stock Report</a>
                    </li>
                    <li>
                      <a href="{{route('customers.ip_block')}}"><i data-feather="file-plus"></i> IP Block</a>
                    </li>
                    <li>
                      <a href="{{route('admin.order_report')}}"><i data-feather="file-plus"></i> Order Reports</a>
                    </li>
                  </ul>
                </div>
              </li>
              <!-- nav items end -->
            </ul>
          </div>
          <!-- End Sidebar -->

          <div class="clearfix"></div>
        </div>
        <!-- Sidebar -left -->
      </div>