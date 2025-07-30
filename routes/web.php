<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\PdfController;

// Rota principal - Login
Route::get('/', function () {
    return view('auth.login');
})->name('login');

// Rotas de autenticação
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/me', [LoginController::class, 'me'])->name('me');

// Rotas protegidas por autenticação
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rotas de Clientes
    Route::resource('clients', ClientsController::class);
    Route::get('/clients/{client}/check-sales', [ClientsController::class, 'checkSales'])->name('clients.checkSales');
    
    // Rotas de Vendas
    Route::resource('sells', SellController::class);
    Route::post('/sells/{sell}/approve-payment', [SellController::class, 'approvePayment'])->name('sells.approvePayment');
    Route::get('/sells/{sell}/installments', [SellController::class, 'getInstallments'])->name('sells.installments');
    
    // Rotas de Parcelas
    Route::post('/installments/{installment}/mark-as-paid', [InstallmentController::class, 'markAsPaid'])->name('installments.markAsPaid');
    Route::post('/sells/{sell}/mark-all-pending-as-paid', [InstallmentController::class, 'markAllPendingAsPaid'])->name('installments.markAllPendingAsPaid');
    Route::post('/sells/{sell}/mark-overdue-as-paid', [InstallmentController::class, 'markOverdueAsPaid'])->name('installments.markOverdueAsPaid');
    
    // Rotas de Produtos
    Route::get('/products/search', [ProductController::class, 'getProducts'])->name('products.getProducts');
    Route::resource('products', ProductController::class);
    Route::get('/products/{id}/details', [ProductController::class, 'getProductDetails'])->name('products.getProductDetails');
    
    // Rotas de Tipos de Pagamento

    
    // Rotas de PDF
    Route::get('/pdf', [PdfController::class, 'index'])->name('pdf.index');
    Route::post('/pdf/generate', [PdfController::class, 'generate'])->name('pdf.generate');
    Route::get('/pdf/{id}/download', [PdfController::class, 'download'])->name('pdf.download');
    Route::delete('/pdf/{id}', [PdfController::class, 'destroy'])->name('pdf.destroy');
    
    // Perfil do usuário
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rota de fallback
Route::fallback(function () {
    return redirect()->route('login');
});
