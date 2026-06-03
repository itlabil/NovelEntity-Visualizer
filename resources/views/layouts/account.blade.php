<!DOCTYPE html>
<html lang="id" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="turbo-prefetch" content="false">
    <title>@yield('title') - Novel Entity Visualizer</title>
    <link rel="shortcut icon" href="{{ asset('images/icon-logo.svg') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- SweetAlerts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.2/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.2/dist/sweetalert2.min.js"></script>

    <!-- Datatables -->
    <link href="https://cdn.datatables.net/2.0.2/css/dataTables.tailwindcss.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @stack('styles')
</head>

<body class="bg-gray-100 font-sans">

    <div class="min-h-screen">

        <!-- mobile menu -->
        <x-account.mobile-menu-web />

        <!-- desktop menu -->
        <x-account.desktop-menu-web />

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            @yield('content')
        </main>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {

            // Mobile menu store
            Alpine.store('mobileMenuOpen', false);
            
            // Delete modal store
            Alpine.store('deleteModal', {
                show: false,
                url: '',
                open(url) {
                    this.url = url;
                    this.show = true;
                },
                close() {
                    this.show = false;
                    this.url = '';
                }
            });

        });
    </script>
    <script>
        @if($message = Session::get('success'))
            Swal.fire({
                icon: "success",
                title: "SUCCESS!",
                text: "{{ $message }}",
                timer: 2000,
                showConfirmButton: false,
                showCancelButton: false,
                buttons: false,
            });
        @elseif($message = Session::get('error'))
            Swal.fire({
                icon: "error",
                title: "ERROR!",
                text: "{{ $message }}",
                timer: 2000,
                showConfirmButton: false,
                showCancelButton: false,
                buttons: false,
            });
        @elseif($message = Session::get('warning'))
            Swal.fire({
                icon: "warning",
                title: "OPPS!",
                text: "{{ $message }}",
                timer: 2000,
                showConfirmButton: false,
                showCancelButton: false,
                buttons: false,
            });
        @endif
    </script>

    @stack('scripts')
</body>
</html>
