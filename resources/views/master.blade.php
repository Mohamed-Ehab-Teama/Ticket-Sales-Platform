<!DOCTYPE html>
<html lang="en">

@include('partials.head')

<body>
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        @include('partials.navbar')

        <!-- Content Wrapper -->
        <div class="content-wrapper">

            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title"> @yield('pageTitle') </h1>
                <p class="page-description"> @yield('pageDescription') </p>
            </div>

            @yield('content')
            
        </div>
    </div>

    @yield('modals')

    
    {{-- Scripts --}}
    @include('partials.scripts')
    @yield('scripts')
</body>

</html>