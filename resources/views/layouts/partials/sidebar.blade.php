<div class="sidebar" id="sidebar">

    <div class="logo">
        {{-- <i class="fa-solid fa-cubes me-2"></i> --}}
        <span>Elegance</span>
    </div>

    <div class="sidebar-menu">

        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-house"></i>
            <span>Dashboard</span>
        </a>

        <a href="#">
            <i class="fa-solid fa-users"></i>
            <span>Users</span>
        </a>

        <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="fa-solid fa-layer-group"></i>
            <span>Categories</span>
        </a>

        <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
            <i class="fa-solid fa-briefcase"></i>
            <span>Product</span>
        </a>

        <a href="#">
            <i class="fa-solid fa-folder-open"></i>
            <span>Projects</span>
        </a>

        <a href="#">
            <i class="fa-solid fa-star"></i>
            <span>Reviews</span>
        </a>

        <a href="#">
            <i class="fa-solid fa-gear"></i>
            <span>Settings</span>
        </a>

    </div>

    <div class="sidebar-footer">

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button class="logout-btn" type="submit">
                <i class="fa-solid fa-right-from-bracket"></i>
                Logout
            </button>

        </form>

    </div>

</div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>
