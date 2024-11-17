<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Product Management</h2>
        <button class="btn btn-primary" wire:click="openModal">Add Product</button>
    </div>

    <!-- Add/Edit Product Modal -->
    @if($showModal)
        <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $isEditMode ? 'Edit Product' : 'Add New Product' }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="{{ $isEditMode ? 'updateProduct' : 'addProduct' }}">
                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name</label>
                                <input type="text" wire:model="name" class="form-control" id="name">
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Product Price</label>
                                <input type="number" wire:model="price" class="form-control" id="price">
                                @error('price') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Product Description</label>
                                <textarea wire:model="description" class="form-control" id="description"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                {{ $isEditMode ? 'Update Product' : 'Save Product' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Product Table -->
    <h4 class="mt-4">Products</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $index => $product)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product['name'] ?? '' }}</td>
                    <td>${{ $product['price'] ?? '' }}</td>
                    <td>{{ $product['description'] ?? '' }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" wire:click="editProduct('{{ $product['_id'] }}')">Edit</button>
                        <button class="btn btn-sm btn-danger" wire:click="deleteProduct('{{ $product['_id'] }}')">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
