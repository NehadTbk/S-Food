<?php

use App\Http\Controllers\DelivererController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\FaqController as AdminFaq;
use App\Http\Controllers\Admin\MenuItemController as AdminMenuItem;
use App\Http\Controllers\Admin\NewsController as AdminNews;
use App\Http\Controllers\Admin\OrderController as AdminOrder;
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// News
Route::get('/nieuws', [NewsController::class, 'index'])->name('news.index');
Route::get('/nieuws/{newsPost}', [NewsController::class, 'show'])->name('news.show');

// FAQ
Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');

// Over ons
Route::get('/over-ons', [PageController::class, 'about'])->name('pages.about');

// Contact
Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Cart + Checkout (requires login, user role enforced in controller)
Route::middleware('auth')->group(function () {
    Route::post('/winkelmandje/toevoegen', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/winkelmandje/bijwerken', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/winkelmandje/verwijderen', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/winkelmandje/leegmaken', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Orders
    Route::get('/bestellingen', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/bestellingen/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/bestellingen/{order}/annuleren', [OrderController::class, 'cancel'])->name('orders.cancel');
});
Route::get('/winkelmandje/samenvatting', [CartController::class, 'summary'])->name('cart.summary');

// Public profile
Route::get('/profiel/{username}', [UserProfileController::class, 'show'])->name('profile.show');
Route::patch('/profiel/{username}', [UserProfileController::class, 'update'])->middleware('auth')->name('profile.update.public');


// Deliverer panel
Route::middleware(['auth', 'deliverer'])->prefix('bezorger')->name('deliverer.')->group(function () {
    Route::get('/', [DelivererController::class, 'index'])->name('index');
    Route::get('/leveringen', [DelivererController::class, 'myDeliveries'])->name('my-deliveries');
    Route::patch('/{order}/aannemen', [DelivererController::class, 'take'])->name('take');
    Route::patch('/{order}/betaal-cash', [DelivererController::class, 'payCash'])->name('pay-cash');
    Route::patch('/{order}/genereer-qr', [DelivererController::class, 'generateQr'])->name('generate-qr');
});

// Admin panel
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard + categories
    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::post('/categorieen', [AdminDashboard::class, 'storeCategory'])->name('categories.store');
    Route::patch('/categorieen/{category}', [AdminDashboard::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categorieen/{category}', [AdminDashboard::class, 'destroyCategory'])->name('categories.destroy');

    // Menu items
    Route::get('/menu/nieuw', [AdminMenuItem::class, 'create'])->name('menu.create');
    Route::post('/menu', [AdminMenuItem::class, 'store'])->name('menu.store');
    Route::get('/menu/{menuItem}/bewerken', [AdminMenuItem::class, 'edit'])->name('menu.edit');
    Route::patch('/menu/{menuItem}', [AdminMenuItem::class, 'update'])->name('menu.update');
    Route::delete('/menu/{menuItem}', [AdminMenuItem::class, 'destroy'])->name('menu.destroy');
    Route::patch('/menu/{menuItem}/toggle', [AdminMenuItem::class, 'toggle'])->name('menu.toggle');

    // News
    Route::get('/nieuws/nieuw', [AdminNews::class, 'create'])->name('news.create');
    Route::post('/nieuws', [AdminNews::class, 'store'])->name('news.store');
    Route::get('/nieuws/{newsPost}/bewerken', [AdminNews::class, 'edit'])->name('news.edit');
    Route::patch('/nieuws/{newsPost}', [AdminNews::class, 'update'])->name('news.update');
    Route::delete('/nieuws/{newsPost}', [AdminNews::class, 'destroy'])->name('news.destroy');

    // FAQ
    Route::post('/faq/categorieen', [AdminFaq::class, 'storeCategory'])->name('faq.categories.store');
    Route::patch('/faq/categorieen/{faqCategory}', [AdminFaq::class, 'updateCategory'])->name('faq.categories.update');
    Route::delete('/faq/categorieen/{faqCategory}', [AdminFaq::class, 'destroyCategory'])->name('faq.categories.destroy');
    Route::post('/faq/vragen', [AdminFaq::class, 'storeItem'])->name('faq.items.store');
    Route::patch('/faq/vragen/{faqItem}', [AdminFaq::class, 'updateItem'])->name('faq.items.update');
    Route::delete('/faq/vragen/{faqItem}', [AdminFaq::class, 'destroyItem'])->name('faq.items.destroy');

    // Orders
    Route::get('/bestellingen', [AdminOrder::class, 'index'])->name('orders.index');
    Route::get('/bestellingen/{order}', [AdminOrder::class, 'show'])->name('orders.show');
    Route::patch('/bestellingen/{order}/bevestig', [AdminOrder::class, 'confirm'])->name('orders.confirm');
    Route::patch('/bestellingen/{order}/annuleer', [AdminOrder::class, 'cancel'])->name('orders.cancel');
    Route::patch('/bestellingen/{order}/betaal-cash', [AdminOrder::class, 'payCash'])->name('orders.pay-cash');
    Route::patch('/bestellingen/{order}/genereer-qr', [AdminOrder::class, 'generateQr'])->name('orders.generate-qr');

    // Users
    Route::get('/gebruikers', [AdminUser::class, 'index'])->name('users.index');
    Route::get('/gebruikers/nieuw', [AdminUser::class, 'create'])->name('users.create');
    Route::post('/gebruikers', [AdminUser::class, 'store'])->name('users.store');
    Route::patch('/gebruikers/{user}/rol', [AdminUser::class, 'updateRole'])->name('users.update-role');
    Route::patch('/gebruikers/{user}/toggle-actief', [AdminUser::class, 'toggleActive'])->name('users.toggle-active');
});

// Demo QR payment page (public — customer scans QR code)
Route::get('/betalen/{token}', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/betalen/{token}', [PaymentController::class, 'confirm'])->name('payment.confirm');

require __DIR__.'/auth.php';
