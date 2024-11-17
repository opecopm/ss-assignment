<?php

namespace App\Livewire\Products;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class ProductIndex extends Component
{
    public $products = [];
    public $name, $price, $description, $productId;
    public $showModal = false;
    public $isEditMode = false;

    protected $rules = [
        'name' => 'required',
        'price' => 'required|numeric',
        'description' => 'nullable',
    ];

    public function mount()
    {
        $this->fetchProducts();
    }

    public function fetchProducts()
    {
        $response = Http::get(env('PRODUCT_SERVICE_URL') . '/api/products');
        $this->products = $response->json();
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function addProduct()
    {
        $this->validate();
        $response = Http::post(env('PRODUCT_SERVICE_URL') . '/api/product', [
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
        ]);

        if ($response->successful()) {
            $this->fetchProducts();
            $this->closeModal();
        } else {
            session()->flash('error', 'Failed to add product');
        }
    }

    public function editProduct($id)
    {
        $product = collect($this->products)->firstWhere('_id', $id);

        if ($product) {
            $this->isEditMode = true;
            $this->productId = $id;
            $this->name = $product['name'];
            $this->price = $product['price'];
            $this->description = $product['description'];
            $this->openModal();
        }
    }

    public function updateProduct()
    {
        $this->validate();
        $response = Http::put(env('PRODUCT_SERVICE_URL') . '/api/product/' . $this->productId, [
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
        ]);

        if ($response->successful()) {
            $this->fetchProducts();
            $this->closeModal();
        } else {
            session()->flash('error', 'Failed to update product');
        }
    }

    public function resetForm()
    {
        $this->name = '';
        $this->price = '';
        $this->description = '';
        $this->productId = null;
        $this->isEditMode = false;
    }

    public function render()
    {
        return view('livewire.products.index');
    }
}
