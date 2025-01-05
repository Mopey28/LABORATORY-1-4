<header class="user-header">
    <div class="user-header-left">
        <h1>SHEIN</h1>
    </div>
    <div class="user-header-center">
        <form action="{{ route('search') }}" method="GET" class="search-form">
            <input type="text" name="query" placeholder="Search for products..." class="search-input">
            <button type="submit" class="search-button">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
    <div class="user-header-right">
        <a href="{{ route('user.dashboard') }}" class="header-icon"><i class="fas fa-home"></i></a>
        <a href="{{ route('user.wishlist.dashboard') }}" class="header-icon">
            <i class="fas fa-heart"></i>
            <span class="badge">{{ $wishlistCount ?? 0 }}</span>
        </a>
        <a href="{{ route('user.cart.dashboard') }}" class="header-icon">
            <i class="fas fa-shopping-cart"></i>
            <span class="badge">{{ $cartCount ?? 0 }}</span>
        </a>
        <div class="user-profile-dropdown">
            <a href="{{ route('user.profile.dashboard') }}" class="header-icon">
                <img src="{{ asset('storage/public/avatars/' . auth()->user()->avatar) }}" alt="Profile Picture" class="user-avatar">
                {{ auth()->user()->first_name }}
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('user.profile.dashboard') }}">Profile</a>
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</header>
