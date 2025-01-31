<div class="iq-top-navbar">
    <div class="iq-navbar-custom">
        <nav class="navbar navbar-expand-lg navbar-light p-0">
            <div class="iq-navbar-logo d-flex align-items-center justify-content-between">
                <i class="ri-menu-line wrapper-menu"></i>
                <a href="{{ route('home') }}" class="header-logo">
                    <img src="{{ asset('images/logo.png') }}" class="img-fluid rounded-normal" alt="logo">
                    <h5 class="logo-title ml-3 pt-2">Installer Benefit Program</h5>

                </a>
            </div>
            <div class="iq-search-bar device-search">

            </div>

            <div class="d-flex align-items-center w-lg-100" id="notification">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-label="Toggle navigation">
                    <i class="ri-menu-3-line"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <div class="flex-fill justify-content-center">
                        <form action="{{ route('customauth.changeCurrentBranch') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <?php
                                                    $user = Auth::user();
                                                    $user_uuid = $user->uuid;
                                                    $userbranches = \App\Models\BranchUser::where("user_uuid",$user_uuid)->pluck("branch_id");
                                                    $branches = \App\Models\Branch::whereIn("branch_id",$userbranches)->get();
                                        ?>
                                        <select name="branch_id" id="current_branch_id" class="form-control @error('branch_id') is-invalid @enderror">
                                            <option selected disabled>Choose Branch</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->branch_id }}" {{ $branch->branch_id == getCurrentBranch() ? 'selected' : '' }}>{{ $branch->branch_name_eng }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <ul class="navbar-nav ml-auto navbar-list align-items-center">


                        <li class="nav-item nav-icon mx-2">{{Auth::user()->name}} @ {{ Auth::user()->branch->branch_name_eng }}</li>

                        <a onclick="openCloseFullscreen();"><i class="ri-fullscreen-line"></i></a>

                        <li class="nav-item nav-icon dropdown">
                            <a href="#" class="search-toggle dropdown-toggle btn border add-btn" id="dropdownMenuButton02" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               @php $locale = session()->get('locale'); @endphp
                                @switch($locale)
                                    @case('en')
                                        <img src="{{asset('images/small/flag-01.png')}}" alt="img-flag" class="img-fluid image-flag mr-2">English
                                        @break
                                    @case('mm')
                                        <img src="{{asset('images/small/flag-02.png')}}" alt="img-flag" class="img-fluid image-flag mr-2">Myanmar
                                        @break
                                    @default
                                        <img src="{{asset('images/small/flag-02.png')}}" alt="img-flag" class="img-fluid image-flag mr-2">Myanmar

                                @endswitch
                            </a>
                            <div class="iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                <div class="card shadow-none m-0">

                                    <div class="card-body p-3">
                                        <a class="iq-sub-card" href="{{ route('lang','en')}}"><img src="{{asset('images/small/flag-02.png')}}" alt="img-flag" class="img-fluid mr-2">Myanmar</a>
                                    </div>
                                    <div class="card-body p-3">
                                        <a class="iq-sub-card" href="{{ route('lang','mm')}}"><img src="{{asset('images/small/flag-01.png')}}" alt="img-flag" class="img-fluid mr-2">English</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        {{-- <li class="nav-item nav-icon dropdown">
                            <a href="#" class="search-toggle dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                </svg>
                                @if (number_convert(count(Auth::user()->unreadNotifications) > 0))
                                <span class="bg-secondary badge-card p-2" id="notification_count">{{number_convert(count(Auth::user()->unreadNotifications))}}</span>
                                @endif
                            </a>
                            <div class="iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <div class="card shadow-none m-0">
                                    <div class="card-body p-0 ">
                                        <div class="cust-title p-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h5 class="mb-0">Notifications</h5>
                                                <a class="badge badge-secondary badge-card" href="#">{{number_convert(count(Auth::user()->unreadNotifications))}}</a>
                                            </div>
                                        </div>
                                        <div class="px-3 pt-0 pb-0 sub-card">
                                            @forelse (Auth::user()->unreadNotifications as $notification)
                                            <a href="{{route('home.make_as_read',[$notification->id,$notification->data['document_id']])}}" class="iq-sub-card">
                                                <div class="media align-items-center cust-card py-3 border-bottom">
                                                    <div class="media-body ml-4">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-0">{{$notification->data['message']}}</h6>
                                                            <small class="text-dark"><b>{{$notification->created_at->diffForHumans()}}</b></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            @empty
                                            <a href="#" class="iq-sub-card">
                                                <div class="media align-items-center cust-card py-3 border-bottom">
                                                    <div class="media-body ml-3">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-0">There is no Notification</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li> --}}
                        <li class="nav-item nav-icon dropdown caption-content">
                            <a href="#" class="search-toggle dropdown-toggle" id="dropdownMenuButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="{{ asset('images/user/' . Auth::user()->roles->pluck('name')->first() .'.png') }}" class="img-fluid rounded" alt="user">
                            </a>
                            <div class="iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <div class="card shadow-none m-0">
                                    <div class="card-body p-0 text-center">
                                        <div class="media-body profile-detail text-center">
                                            <img src="{{ asset('images/PRO-1-Global-Logo.png') }}" alt="profile-bg" class="rounded-top img-fluid mb-4">
                                            <img src="{{ asset('images/user/' . Auth::user()->roles->pluck('name')->first() .'.png') }}" alt="profile-img" class="rounded profile-img img-fluid avatar-70">
                                        </div>
                                        <div class="p-3">
                                            <h5 class="mb-1">{{Auth::user()->name}}</h5>
                                            <p class="mb-0">{{Auth::user()->roles->pluck('name')->first()}}</p>
                                            <div class="d-flex align-items-center justify-content-center mt-3">
                                                <a href="{{ route('user.profile') }}" class="btn border mr-2">Profile</a>
                                                <a class="btn border" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">Sign Out
                                                </a>


                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</div>
