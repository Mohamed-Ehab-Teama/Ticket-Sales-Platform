<div class="sidebar">
    <div class="sidebar-header">
        <h4><i class="fas fa-ticket-alt me-2"></i>TicketHub</h4>
        <p>Admin Dashboard</p>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-title">Main</div>

            <div class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Event Management</div>
            <div class="nav-item">
                <a href="{{ route('admin.events.index') }}" class="nav-link {{ Route::is('admin.events.index') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Events</span>
                    <span class="nav-badge"> {{ App\Models\Event::count() }} </span>
                </a>
            </div>

            <div class="nav-item">
                {{-- <a href="{{ route('admin.dates.index') }}" class="nav-link {{ Route::is('admin.dates.index') ? 'active' : '' }}"> --}}
                <a  class="nav-link {{ Route::is('admin.dates.index') ? 'active' : '' }}">
                    <i class="fas fa-calendar-day"></i>
                    <span>Dates</span>
                </a>
            </div>

            <div class="nav-item">
                <a class="nav-link {{ Route::is('admin.dates.time-slots.index') ? 'active' : '' }}">
                    <i class="fas fa-clock"></i>
                    <span>Time Slots</span>
                </a>
            </div>

            <div class="nav-item">
                <a class="nav-link {{ Route::is('admin.ticket-type.index') ? 'active' : '' }}">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Ticket Types</span>
                </a>
            </div>
        </div>

        {{-- <div class="nav-section">
            <div class="nav-section-title">Sales</div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                    <span class="nav-badge">8</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-credit-card"></i>
                    <span>Payments</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-tags"></i>
                    <span>Discounts</span>
                </a>
            </div>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Operations</div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-qrcode"></i>
                    <span>Check-ins</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-users"></i>
                    <span>Customers</span>
                </a>
            </div>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Reports</div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-chart-line"></i>
                    <span>Analytics</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-file-export"></i>
                    <span>Exports</span>
                </a>
            </div>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Settings</div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-user-shield"></i>
                    <span>Users & Roles</span>
                </a>
            </div>
        </div> --}}
    </nav>
</div>