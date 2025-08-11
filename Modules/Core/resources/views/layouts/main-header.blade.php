<!-- main-header opened -->
<div class="main-header sticky side-header nav nav-item">
    <div class="container-fluid">
        <div class="main-header-left ">
            <div class="responsive-logo">
                <a href="{{ url('/' . ($page = 'index')) }}"><img src="{{ URL::asset('assets/img/capsule.png') }}"
                        class="logo-1" alt="logo"></a>
                <a href="{{ url('/' . ($page = 'index')) }}"><img
                        src="{{ URL::asset('assets/img/brand/logo-white.png') }}" class="dark-logo-1" alt="logo"></a>
                <a href="{{ url('/' . ($page = 'index')) }}"><img src="{{ URL::asset('assets/img/capsule.png') }}"
                        class="logo-2" alt="logo"></a>
                <a href="{{ url('/' . ($page = 'index')) }}"><img src="{{ URL::asset('assets/img/capsule.png') }}"
                        class="dark-logo-2" alt="logo"></a>
            </div>
            <div class="app-sidebar__toggle" data-toggle="sidebar">
                <a class="open-toggle" href="#"><i class="header-icon fe fe-align-left"></i></a>
                <a class="close-toggle" href="#"><i class="header-icons fe fe-x"></i></a>
            </div>
            {{-- <div class="main-header-center mr-3 d-sm-none d-md-none d-lg-block">
                <input class="form-control" placeholder="Search for anything..." type="search"> <button
                    class="btn"><i class="fas fa-search d-none d-md-block"></i></button>
            </div> --}}
        </div>
        <div class="main-header-right">
            <div class="nav nav-item navbar-nav-right ml-auto">
                <div class="nav-link" id="bs-example-navbar-collapse-1">
                    <form class="navbar-form" role="search">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search">
                            <span class="input-group-btn">
                                <button type="reset" class="btn btn-default">
                                    <i class="fas fa-times"></i>
                                </button>
                                <button type="submit" class="btn btn-default nav-link resp-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-search">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>

                <div class="dropdown nav-item main-header-notification">
                    <a class="new nav-link" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-bell">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg><span class=" pulse"></span></a>
                    <div class="dropdown-menu">
                        <div class="menu-header-content bg-primary text-right">
                            <div class="d-flex">
                                <h6 class="dropdown-title mb-1 tx-15 text-white font-weight-semibold">الاشعارات
                                </h6>
                                {{-- <span class="badge badge-pill badge-warning mr-auto my-auto float-left">Mark All
                                    Read</span> --}}
                            </div>
                            {{-- <p class="dropdown-title-text subtext mb-0 text-white op-6 pb-0 tx-12 ">You have 4 unread
                                Notifications</p> --}}
                        </div>
                        <div class="main-notification-list Notification-scroll">
                            @forelse($notifications as $notification)
                                <a class="d-flex p-3 border-bottom"
                                    href="{{ route('notifications.read', $notification->id) }}"
                                    style="background-color: {{ $notification->is_read ? 'transparent' : '#ecf0fa' }};">
                                    <div class="notifyimg bg-primary">
                                        <i class="la la-bell text-white"></i> {{-- أيقونة الإشعار --}}
                                    </div>
                                    <div class="mr-3">
                                        <h5 class="notification-label mb-1">
                                            {{ $notification->title ?? 'إشعار جديد' }}
                                        </h5>
                                        <div class="notification-subtext">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <p class="p-3 text-center">لا توجد إشعارات جديدة</p>
                            @endforelse
                        </div>

                        {{-- <div class="dropdown-footer">
                            <a href="">VIEW ALL</a>
                        </div> --}}
                    </div>
                </div>

                <!-- زر تبديل الوضع الليلي/النهاري -->
                {{-- <div class="dropdown nav-item  theme-toggle"
                    style="background-color: #f8f9fa; border-radius: 50%; width: 36px; height: 36px;top:8px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                    <i class="bi bi-moon-fill text-dark" id="theme-icon"></i>
                </div> --}}
                <!-- نهاية زر تبديل الوضع -->

                <div class="dropdown main-profile-menu nav nav-item nav-link">
                    <a class="profile-user d-flex" href=""><img alt=""
                            src="{{ Auth::user()->profile_photo_url }}"></a>
                    <div class="dropdown-menu">
                        <div class="main-header-profile bg-primary p-3">
                            <div class="d-flex wd-100p">
                                <div class="main-img-user"><img alt=""
                                        src="{{ Auth::user()->profile_photo_url }}" class=""></div>
                                <div class="mr-3 my-auto">
                                    <h6>{{ Auth::user()->name }}</h6><span>{{ Auth::user()->phone }}</span>
                                </div>
                            </div>
                        </div>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="bx bx-cog"></i> تعديل بياناتي
                        </a>

                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="dropdown-item">
                                <i class="bx bx-log-out"></i> تسجيل الخروج
                            </a>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
