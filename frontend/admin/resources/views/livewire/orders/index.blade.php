<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Order Management</h2>
        <button class="btn btn-primary" wire:click="openModal">Add Order</button>
    </div>

    <!-- Add/Edit Order Modal -->
    @if($showModal)
        <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $isEditMode ? 'Edit Order' : 'Add New Order' }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="{{ $isEditMode ? 'updateOrder' : 'addOrder' }}">
                            <div class="mb-3">
                                <label for="customerName" class="form-label">Customer Name</label>
                                <input type="text" wire:model="customerName" class="form-control" id="customerName">
                                @error('customerName') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="customerEmail" class="form-label">Customer Email</label>
                                <input type="email" wire:model="customerEmail" class="form-control" id="customerEmail">
                                @error('customerEmail') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="customerAddress" class="form-label">Customer Address</label>
                                <input type="text" wire:model="customerAddress" class="form-control" id="customerAddress">
                                @error('customerAddress') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <!-- Product Dropdown -->
                            <div class="mb-3">
                                <label for="productId" class="form-label">Product</label>
                                <select wire:model="productId" class="form-select" id="productId">
                                    <option value="">-- Select Product --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product['_id'] }}">
                                            {{ $product['name'] ?? 'Unknown Product' }} - ${{ $product['price'] ?? '0.00' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('productId') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" wire:model="quantity" class="form-control" id="quantity" min="1">
                                @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="totalPrice" class="form-label">Total Price</label>
                                <input type="number" wire:model="totalPrice" class="form-control" id="totalPrice">
                                @error('totalPrice') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select wire:model="status" class="form-select" id="status">
                                    <option value="">Select Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">
                                {{ $isEditMode ? 'Update Order' : 'Save Order' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Orders Table -->
    <h4 class="mt-4">Orders</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order['_id'] }}</td>
                    <td>{{ $order['customer']['name'] ?? 'N/A' }}</td>
                    <td>{{ $order['customer']['email'] ?? 'N/A' }}</td>
                    <td>{{ $order['customer']['address'] ?? 'N/A' }}</td>
                    <td>{{ $order['product']['name'] ?? 'Unknown Product' }}</td>
                    <td>{{ $order['quantity'] ?? '0' }}</td>
                    <td>${{ $order['total_price'] ?? '0.00' }}</td>
                    <td>{{ $order['status'] ?? 'N/A' }}</td>
                    <td>
                        <button wire:click="editOrder('{{ $order['_id'] }}')" class="btn btn-warning btn-sm">Edit</button>
                        <button wire:click="deleteOrder('{{ $order['_id'] }}')" class="btn btn-danger btn-sm">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
