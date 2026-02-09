<div class="top-navbar">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <button class="btn btn-link d-md-none me-2" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="input-group" style="width: 300px;">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" class="form-control border-start-0" placeholder="Search @yield('pageTitle')...">
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-icon">
                <i class="fas fa-bell"></i>
            </button>
            <div class="dropdown">
                <button class="btn btn-icon dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <span class="dropdown-item"> <u><i>User</i></u> : {{auth()->user()->name }} </span>
                    </li>

                    {{-- <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li> --}}
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    {{-- LogOut --}}
                    <li>
                        <form action="{{ route('logout') }}" class="d-inline" method="POST" id="NavLogoutForm">
                            @csrf
                            @method('POST')
                            <button type="submit" form="NavLogoutForm" class="dropdown-item">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>