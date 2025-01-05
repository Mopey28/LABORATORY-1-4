<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
    <link rel="stylesheet" href="{{ asset('css/wishlist.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <!-- Other head content -->
</head>
<body>
    <div class="user-container">
        @include('layouts.user-header')
        <main class="user-main">
            @yield('content')
        </main>
        @include('layouts.user-footer')
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userProfileDropdown = document.querySelector('.user-profile-dropdown');
            const dropdownMenu = document.querySelector('.dropdown-menu');

            userProfileDropdown.addEventListener('mouseenter', function() {
                dropdownMenu.style.display = 'block';
            });

            userProfileDropdown.addEventListener('mouseleave', function() {
                dropdownMenu.style.display = 'none';
            });
        });
    </script>
</body>
</html>
