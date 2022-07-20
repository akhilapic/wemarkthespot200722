<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->

<style>
    .sidebar-margin li {
        margin-bottom: 2px !important;
    }
    .sidebar-padding li a {
        padding: 4px 35px 4px 36px !important;
    }
</style>

<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- User profile -->
        <div class="user-profile position-relative" style="background: url({{ asset('assets/admin/images/background/user-info.jpg') }}) no-repeat;">
            <!-- User profile image -->
            <div class="profile-img">

                                  @if(Session::has('adminimage'))

                                        <img src="{{\App\Models\User::where('id',Session::get('adminid'))->pluck('image')[0]}}" alt="user" class="w-100" />
@else
  <img src="{{ asset('assets/admin/images/users/profile.png') }}" alt="user" class="w-100" />
@endif

                

            </div>
            <!-- User profile text-->
            <div class="profile-text pt-1 dropdown">
                <a href="#" class="dropdown-toggle u-dropdown w-100 text-white d-block position-relative" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">{{\App\Models\User::where('id',Session::get('adminid'))->pluck('name')[0]}}</a>
                <div class="dropdown-menu animated flipInY" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="{{ url('/my_profile') }}"><i data-feather="user" class="feather-sm text-info me-1 ms-1"></i> My Profile</a>
                    <div class="dropdown-divider"></div>
                    <a style="display:none" class="dropdown-item" href="{{ route('signout') }}"><i data-feather="log-out" class="feather-sm text-danger me-1 ms-1"></i> Logout</a>
                    <div class="dropdown-divider"></div>
                    <div class="pl-4 p-2"><a href="{{ route('signout') }}" class="btn d-block w-100 btn-info rounded-pill">Logout</a></div>
                </div>
            </div>
        </div>
        <!-- End User profile text-->
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav" class="sidebar-margin">
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link @if (Request::segment(1)=='dashboard') active @endif" href="{{ url('/dashboard') }}" aria-expanded="false">
                        <i class="mdi mdi-gauge"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item" style="">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link @if (Request::segment(1)=='user-view') active  @elseif (Request::segment(1)=='user_list')  aclass   @endif" href="{{ url('/user_list') }}" aria-expanded="false">
                        <i class="mdi mdi-account"></i>
                        <span class="hide-menu">User Management</span>
                    </a>
                </li>
                 <li class="sidebar-item" style="">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link @if (Request::segment(1)=='promocode-view') active  @elseif (Request::segment(1)=='promocode_list')  aclass   @endif" href="{{ url('/promocode_list') }}" aria-expanded="false">
                        <i class="mdi mdi-account"></i>
                        <span class="hide-menu">Promocode Management</span>
                    </a>
                </li>
				<li class="sidebar-item" style="display: none;">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link " href="{{ url('/workout_plans') }}" aria-expanded="false">
                        <i class="mdi mdi-account"></i>
                        <span class="hide-menu">Manage Workout Plans</span>
                    </a>
                </li>
                <li class="sidebar-item @if (Request::segment(1)=='manager_business') active  @elseif (Request::segment(1)=='manage_business_view')  aclass   @endif" style="">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link @if (Request::segment(1)=='manager_business') active  @elseif (Request::segment(1)=='manage_business_view')  active   @endif" href="{{ url('/manager_business') }}" aria-expanded="false">
                        <i style="font-size: 17px; margin-right:5px;" class="fas fa-tasks"></i>
                        <span class="hide-menu">Business Management</span>
                    </a>
                </li>
              
				
                <li class="sidebar-item" style="display:none">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/product') }}" aria-expanded="false">
                        <i class="mdi mdi-cart-outline"></i>
                        <span class="hide-menu">Product</span>
                    </a>
                </li>
				
                <li class="sidebar-item" style="display:none">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/404') }}" target="_blank" aria-expanded="false">
                        <i class="mdi mdi-alert-box"></i>
                        <span class="hide-menu">404</span>
                    </a>
                </li>
				
                <li class="sidebar-item" style="display:none">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/create') }}" aria-expanded="false">
                        <i class="mdi mdi-file"></i>
                        <span class="hide-menu">Create Page</span>
                    </a>
                </li>


                <li class="sidebar-item" style="">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i style="margin-right: 5px;" class="mdi mdi-format-list-bulleted"></i>
                        <span class="hide-menu">Category Management</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level sidebar-padding">
                        <li class="sidebar-item ">
                               
                            <a href="{{ url('/manager_category') }}" class="sidebar-link @if (Request::segment(1)=='manager_category') aclass  @elseif (Request::segment(1)=='category-view')  aclass @elseif (Request::segment(1)=='category_edit')  aclass  @endif">
                  
                                <i class="mdi mdi-adjust"></i>
                                <span class="hide-menu">Category  </span>
                            </a>
                    

                            
                             <a href="{{ url('/manage_sub_category') }}" class="sidebar-link @if (Request::segment(1)=='manage_sub_category') aclass @elseif (Request::segment(1)=='subcategory-view') aclass @elseif (Request::segment(1)=='subcategory_edit') aclass @endif">
                     
                                <i class="mdi mdi-adjust"></i>
                                <span class="hide-menu">Sub Category</span>
                            </a>

                            <a href="{{ url('/manage_offer_type') }}" class="sidebar-link @if (Request::segment(1)=='manage_offer_type') aclass @elseif (Request::segment(1)=='offer_type-view') aclass @elseif (Request::segment(1)=='offer_type_edit') aclass @endif">
                     
                     <i class="mdi mdi-adjust"></i>
                     <span class="hide-menu">Offer Type</span>
                 </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item" style="display:">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/business_report') }}" aria-expanded="false">
                        <!-- <i class="mdi mdi-email"></i> -->
                        <i style="font-size: 15px; margin-right: 7px;" class="fas fa-quote-right"></i>
                        <span class="hide-menu">Flagged Reviews</span>
                    </a>
                </li>

                 <li class="sidebar-item" style="display:none">
                    <a class="sidebar-link has-arrow waves-effect waves-dark " href="{{ url('/business_report') }}" aria-expanded="false">
                        <i style="margin-right: 5px;" class="mdi mdi-email"></i>
                        <span class="hide-menu">Flagged Reviews</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level" style="display: none;">
                        <li class="sidebar-item">
                            <a href="{{ url('/business_report') }}" class="sidebar-link aclass">
                                <i class="mdi mdi-adjust"></i>
                                <span class="hide-menu">Manage Reported Reviews</span>
                            </a>
                             <a style="display:none" href="{{ url('/') }}" class="sidebar-link">
                                <i class="mdi mdi-adjust"></i>
                                <span class="hide-menu">Sub Category</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item" style="display:">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/ReviewandRattingManagement') }}" aria-expanded="false">
                        <i class="fas fa-star"></i>
                        <span class="hide-menu">Ratings Management</span>
                    </a>
                </li>

                   <li class="sidebar-item" style="display:none">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="{{ url('/ReviewandRattingManagement') }}" aria-expanded="false">
                        <!-- <i class="mdi mdi-format-list-bulleted"></i> -->
                        <i style="font-size: 16px; margin-right: 7px;" class="fas fa-star"></i>
                        <span class="hide-menu">Ratings Management</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level" style="display:none">
                        <li class="sidebar-item">
                            <a href="{{ url('/ReviewandRattingManagement') }}" class="sidebar-link">
                                <i class="mdi mdi-adjust"></i>
                                <span class="hide-menu">Manage Reviews</span>
                            </a>
                            
                        </li>
                    </ul>
                </li>
                    <li class="sidebar-item" style="display:">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/quoates_managements') }}" aria-expanded="false">
                        <!-- <i class="mdi mdi-email"></i> -->
                        <i style="font-size: 15px; margin-right: 7px;" class="fas fa-quote-right"></i>
                        <!-- <span class="hide-menu">Home Screen Managements</span> -->
                        <span class="hide-menu">Quotes Managements</span>
                    </a>
                </li>

                <li class="sidebar-item" style="display:">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/contact') }}" aria-expanded="false">
                        <i class="mdi mdi-email"></i>
                        <span class="hide-menu">Contact Us</span>
                    </a>
                </li>
                <li class="sidebar-item" style="display:none">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/privacypolicy') }}" aria-expanded="false">
                        <i class="mdi mdi-email"></i>
                        <span class="hide-menu">Privacy & Policy</span>
                    </a>
                </li>
                <li class="sidebar-item" style="display:">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/Introductory_Video') }}" aria-expanded="false">
                        <i class="mdi mdi-email"></i>
                        <span class="hide-menu">Introductory Video</span>
                    </a>
                </li>
                <li class="sidebar-item" style="display:">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/business_giveaways') }}" aria-expanded="false">
                        <i class="mdi mdi-email"></i>
                        <span class="hide-menu">Business Giveaways</span>
                    </a>
                </li>
                <li class="sidebar-item  " style="display:block">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link " href="{{url('admin_subscriptions')}}" aria-expanded="false">
                        <i class="mdi mdi-cart-outline"></i>
                        <span class="hide-menu">Subscriptions</span>
                    </a>
                </li>

                <li class="sidebar-item @if (Request::segment(1)=='admin_payment_details') active  @elseif (Request::segment(1)=='admin_payment_details_view')  aclass   @endif" style="display:block">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/admin_payment_details') }}" aria-expanded="false">
                        <i class="fa fa-credit-card"></i>
                        <span class="hide-menu"> Business Payments</span>
                    </a>
                </li>
                <li class="sidebar-item @if (Request::segment(1)=='manager_donationhistory') active  @elseif (Request::segment(1)=='manage_donationhistory_view')  aclass   @endif" style="">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link @if (Request::segment(1)=='manager_donationhistory') active  @elseif (Request::segment(1)=='manage_donationhistory_view')  active   @endif" href="{{ url('/manager_donationhistory') }}" aria-expanded="false">
                        <i style="font-size: 17px; margin-right:5px;" class="fas fa-tasks"></i>
                        <span class="hide-menu">Donation History</span>
                    </a>
                </li>
                <li class="sidebar-item" style="display:none">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/manage_aboutus') }}" aria-expanded="false">
                        <i class="mdi mdi-email"></i>
                        <span class="hide-menu">About Us</span>
                    </a>
                </li>
                <li class="sidebar-item" style="display:none">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/faq') }}" aria-expanded="false">
                        <i class="mdi mdi-email"></i>
                        <span class="hide-menu">FAQ</span>
                    </a>
                </li>

                <li class="sidebar-item" style="display:">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="mdi mdi-format-list-bulleted"></i>
                        <span class="hide-menu">CMS Pages</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level sidebar-padding">
                        <li class="sidebar-item">
                            <a href="{{ url('/manage_aboutus') }}" class="sidebar-link">
                                <i class="mdi mdi-adjust"></i>
                                <span class="hide-menu">About Us</span>
                            </a>
                        </li>
                        <li class="sidebar-item" style="padding-top:0px;">
                            <a href="{{ url('/faq') }}" class="sidebar-link">
                                <i class="mdi mdi-adjust"></i>
                                <span class="hide-menu">FAQ</span>
                            </a>
                        </li>
                        <li class="sidebar-item " style="padding-top:0px;">
                            <a href="{{ url('/privacypolicy') }}" class="sidebar-link">
                                <i class="mdi mdi-adjust"></i>
                                <span class="hide-menu">Privacy Policy</span>
                            </a>
                        </li>
                        <li class="sidebar-item" style="display:">
                            <a href="{{ url('/manage_terms_conditions') }}" class="sidebar-link">
                                <i class="mdi mdi-adjust"></i>
                                <span class="hide-menu">Terms & Conditions</span>
                            </a>
                        </li>

                        <li class="sidebar-item" style="display:none">
                            <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-playlist-plus"></i>
                                <span class="hide-menu">Blog</span>
                            </a>
                            <ul aria-expanded="false" class="collapse second-level" style="display:none">
                                <li class="sidebar-item">
                                    <a href="{{ url('/blog_category') }}" class="sidebar-link">
                                        <i lass="mdi mdi-octagram"></i>
                                        <span class="hide-menu">Blog Category</span>
                                    </a>
                                </li>
                                <li class="sidebar-item" style="display:none">
                                    <a href="{{ url('/blog_list') }}" class="sidebar-link">
                                        <i lass="mdi mdi-octagram"></i>
                                        <span class="hide-menu">Blog List</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
    <!-- Bottom points-->
    <div class="sidebar-footer">
        <!-- item--><!--
        <a href="" class="link" data-bs-toggle="tooltip" data-bs-placement="top" title="Settings"><i class="ti-settings"></i></a>
        <a href="" class="link" data-bs-toggle="tooltip" data-bs-placement="top" title="Email"><i class="mdi mdi-gmail"></i></a>-->
        <a href="{{ url('/admin/login') }}" class="link" data-bs-toggle="tooltip" data-bs-placement="top" title="Logout"><i class="mdi mdi-power"></i></a>
    </div>
    <!-- End Bottom points-->
</aside>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<!-- ============================================================== -->

<style>
.sidebar-nav ul .sidebar-item .sidebar-link.active {
    color: #607d8b !important;
    opacity: 1;
    font-weight: normal;
}
.sidebar-nav ul .sidebar-item .sidebar-link.active.aclass {
    color: #000 !important;
    font-weight: 500 !important;
}
</style>