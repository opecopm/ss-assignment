<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @livewireStyles
</head>
<body>
    <!-- Header -->
    <header class="bg-dark text-white p-3 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <h1 class="h4 mb-0">Admin Panel</h1>
        </div>
        <form class="d-flex w-50">
            <input class="form-control me-2" type="search" placeholder="Search..." aria-label="Search">
            <button class="btn btn-outline-light" type="submit">Search</button>
        </form>
    </header>
    <!-- Main Content -->
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 bg-light p-3 vh-100">
            <ul class="list-group">
                <li class="list-group-item">
                    <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a>
                </li>
                <li class="list-group-item">
                    <a href="#productMenu" class="text-decoration-none" data-bs-toggle="collapse">Products</a>
                    <ul id="productMenu" class="collapse list-unstyled ps-3 mt-2">
                        <li><a href="{{ route('admin.products') }}" class="text-decoration-none">Product List</a></li>
                    </ul>
                </li>
                <li class="list-group-item">
                    <a href="#orderMenu" class="text-decoration-none" data-bs-toggle="collapse">Orders</a>
                    <ul id="orderMenu" class="collapse list-unstyled ps-3 mt-2">
                        <li><a href="{{ route('admin.orders') }}" class="text-decoration-none">Order List</a></li>
                    </ul>
                </li>

            </ul>
        </nav>

        <!-- Main Content Area -->
        <main class="col-md-9 p-4">
            {{ $slot }}
        </main>
    </div>
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
