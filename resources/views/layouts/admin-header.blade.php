<header class="admin-header">
    <div class="admin-header-right">
        <div class="admin-profile-dropdown">
            <a href="{{ route('admin.profile') }}" class="header-icon">
                <img src="{{ asset('storage/public/avatars/' . auth()->user()->avatar) }}" alt="Profile Picture" class="admin-avatar">
                {{ auth()->user()->first_name }}
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('admin.profile') }}">Profile</a>
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
