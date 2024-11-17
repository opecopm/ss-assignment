<?php

namespace App\Livewire\Orders;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class OrderIndex extends Component
{
    public $orders = [];
    public $products = [];
    public $orderId, $customerName, $customerEmail, $customerAddress, $productId, $quantity, $totalPrice, $status;
    public $showModal = false;
    public $isEditMode = false;

    protected $rules = [
        'customerName' => 'required',
        'customerEmail' => 'required|email',
        'customerAddress' => 'required',
        'productId' => 'required',
        'quantity' => 'required|integer|min:1',
        'totalPrice' => 'required|numeric',
        'status' => 'required',
    ];

    public function mount()
    {
        $this->fetchOrders();
        $this->fetchProducts();
    }

    public function fetchOrders()
    {
        $response = Http::get(env('ORDER_SERVICE_URL') . '/api/orders');
        $this->orders = $response->json();
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

    public function addOrder()
    {
        $this->validate();
        $response = Http::post(env('ORDER_SERVICE_URL') . '/api/order', [
            'customer' => [
                'name' => $this->customerName,
                'email' => $this->customerEmail,
                'address' => $this->customerAddress,
            ],
            'product_id' => $this->productId,
            'quantity' => $this->quantity,
            'total_price' => $this->totalPrice,
            'status' => $this->status,
        ]);

        if ($response->successful()) {
            $this->fetchOrders();
            $this->closeModal();
        } else {
            session()->flash('error', 'Failed to add order');
        }
    }

    public function editOrder($id)
    {
        $order = collect($this->orders)->firstWhere('_id', $id); // Ensure '_id' is correct

        if ($order) {
            $this->isEditMode = true;
            $this->orderId = $id;
            $this->customerName = $order['customer']['name'] ?? '';
            $this->customerEmail = $order['customer']['email'] ?? '';
            $this->customerAddress = $order['customer']['address'] ?? '';
            $this->productId = $order['product_id'] ?? ''; // Ensure the correct key here
            $this->quantity = $order['quantity'] ?? 1;
            $this->totalPrice = $order['total_price'] ?? 0.0;
            $this->status = $order['status'] ?? '';
            $this->openModal();
        }
    }


    public function updateOrder()
    {
        $this->validate();
        $response = Http::put(env('ORDER_SERVICE_URL') . '/api/order/' . $this->orderId, [
            'customer' => [
                'name' => $this->customerName,
                'email' => $this->customerEmail,
                'address' => $this->customerAddress,
            ],
            'product_id' => $this->productId,
            'quantity' => $this->quantity,
            'total_price' => $this->totalPrice,
            'status' => $this->status,
        ]);

        if ($response->successful()) {
            $this->fetchOrders();
            $this->closeModal();
        } else {
            session()->flash('error', 'Failed to update order');
        }
    }

    public function deleteOrder($id)
    {
        $response = Http::delete(env('ORDER_SERVICE_URL') . '/api/order/' . $id);

        if ($response->successful()) {
            $this->fetchOrders();
            session()->flash('message', 'Order deleted successfully!');
        } else {
            session()->flash('error', 'Failed to delete order');
        }
    }

    public function resetForm()
    {
        $this->customerName = '';
        $this->customerEmail = '';
        $this->customerAddress = '';
        $this->productId = '';
        $this->quantity = 1;
        $this->totalPrice = '';
        $this->status = '';
        $this->orderId = null;
        $this->isEditMode = false;
    }

    public function render()
    {
        return view('livewire.orders.index');
    }
}
