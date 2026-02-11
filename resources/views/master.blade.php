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


        {{-- Error & Success Messages --}}
        @foreach (['success', 'error'] as $msg)
            @if(session($msg))
                <div class="alert alert-{{ $msg === 'success' ? 'success' : 'danger' }}">
                    {{ session($msg) }}
                </div>
            @endif
        @endforeach

        {{-- Error & Success Messages --}}


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