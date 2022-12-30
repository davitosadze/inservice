<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PurchaserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\SpecialAttributeController;
use App\Http\Controllers\CategoryAttributeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::middleware(['auth', 'has_permission'])->group(function () {

    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');

    Route::get("/profile", [UserController::class, 'profile'])->name('profile');

    Route::resource("users", UserController::class);
    Route::post("users/uploads", [UserController::class, 'upload']);
    Route::get("users/uploads2/{report_item}", [UserController::class, 'upload2']);

    Route::resource("roles", RoleController::class);
    Route::resource("permissions", PermissionController::class);

    Route::resource("purchasers", PurchaserController::class);
    Route::resource("purchasers.special-attributes", SpecialAttributeController::class);

    Route::resource("evaluations", EvaluationController::class);

    Route::resource("reports", ReportController::class);
    Route::post("reports/uploads", [ReportController::class, 'upload']);
    Route::get("reports/uploads2/{report_item}", [ReportController::class, 'upload2']);

    Route::get("evaluations/pdf/{id}", [EvaluationController::class, 'pdf'])->name('evaluations.pdf');
    Route::get("evaluations/excel/{id}", [EvaluationController::class, 'excel'])->name('evaluations.excel');

    Route::resource("invoices", InvoiceController::class);
    Route::get("invoices/pdf/{id}", [InvoiceController::class, 'pdf'])->name('invoices.pdf');
    Route::get("invoices/excel/{id}", [InvoiceController::class, 'excel'])->name('invoices.excel');
    
    Route::resource("categories", CategoryController::class);
    Route::resource("categories.category-attributes", CategoryAttributeController::class);
});

require __DIR__.'/auth.php';
