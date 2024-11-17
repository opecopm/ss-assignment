<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Products\ProductIndex;
use App\Livewire\Orders\OrderIndex;
use App\Livewire\Dashboard;

Route::get('/admin/dashboard', Dashboard::class)->name('admin.dashboard');
Route::get('/admin/products', ProductIndex::class)->name('admin.products');
Route::get('/admin/orders', OrderIndex::class)->name('admin.orders');
