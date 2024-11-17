<div>
    <h2>Welcome to the Admin Dashboard</h2>
    <p class="text-muted">Here you can manage products, orders, and customers.</p>

    <div class="row mt-4">
        <!-- Card for Products -->
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Products</h5>
                    <p class="card-text">Manage all your products in one place.</p>
                    <a href="{{ route('admin.products') }}" class="btn btn-primary">View Products</a>
                </div>
            </div>
        </div>

        <!-- Card for Orders -->
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Orders</h5>
                    <p class="card-text">Track and manage customer orders.</p>
                    <a href="{{ route('admin.orders') }}" class="btn btn-success">View Orders</a>
                </div>
            </div>
        </div>
    </div>
</div>
