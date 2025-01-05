<aside class="admin-sidebar">
    <div class="sidebar-header">
        <h2 class="text-center">SHEIN</h2>
    </div>
    <nav class="admin-nav">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" href="#" id="productsDropdown" data-toggle="collapse" data-target="#productsMenu" aria-expanded="false">
                    <i class="fas fa-box"></i> Products
                </a>
                <ul class="collapse" id="productsMenu" data-parent=".admin-nav">
                    <li><a href="{{ route('admin.create-product') }}" class="dropdown-item">Create Product</a></li>
                    <li><a href="{{ route('admin.manage-products') }}" class="dropdown-item">Manage Products</a></li>
                    <li><a href="{{ route('admin.manage-variations') }}" class="dropdown-item">Manage Variations</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" href="#" id="stockDropdown" data-toggle="collapse" data-target="#stockMenu" aria-expanded="false">
                    <i class="fas fa-warehouse"></i> Stock Management
                </a>
                <ul class="collapse" id="stockMenu" data-parent=".admin-nav">
                    <li><a href="{{ route('admin.add-stock') }}" class="dropdown-item">Add Stock</a></li>
                    <li><a href="{{ route('admin.manage-stock') }}" class="dropdown-item">Manage Stock</a></li>
                    <li><a href="{{ route('admin.stock-history') }}" class="dropdown-item">Stock History</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" data-toggle="collapse" data-target="#categoriesMenu" aria-expanded="false">
                    <i class="fas fa-tags"></i> Manage Categories
                </a>
                <ul class="collapse" id="categoriesMenu" data-parent=".admin-nav">
                    <li><a href="{{ route('admin.manage-main-categories') }}" class="dropdown-item">Main Category</a></li>
                    <li><a href="{{ route('admin.manage-sub-categories') }}" class="dropdown-item">Sub-Category</a></li>
                    <li><a href="{{ route('admin.manage-sub-sub-categories') }}" class="dropdown-item">Sub-sub-category</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" href="#" id="ordersDropdown" data-toggle="collapse" data-target="#ordersMenu" aria-expanded="false">
                    <i class="fas fa-shopping-cart"></i> Orders
                </a>
                <ul class="collapse" id="ordersMenu" data-parent=".admin-nav">
                    <li><a href="{{ route('admin.manage-orders') }}" class="dropdown-item">Manage Orders</a></li>
                    <li><a href="{{ route('admin.order-history') }}" class="dropdown-item">Order History</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.users') }}" class="nav-link">
                    <i class="fas fa-users"></i> Users
                </a>
            </li>
        </ul>
    </nav>
</aside>
