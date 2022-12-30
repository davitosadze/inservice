<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PurchaserController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\InvoiceController;
use App\Http\Controllers\API\EvaluationController;
use App\Http\Controllers\API\SpecialAttributeController;
use App\Http\Controllers\API\CategoryAttributeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware(['auth:sanctum'])->name('api.')->group(function () {
    Route::apiResource("users", UserController::class);
    
    Route::apiResource("purchasers", PurchaserController::class);
    Route::apiResource("purchasers.special-attributes", SpecialAttributeController::class);

    Route::apiResource("evaluations", EvaluationController::class);

    Route::apiResource("categories", CategoryController::class);
    Route::resource("invoices", InvoiceController::class);
    Route::delete("invoices/destroy-attribute/{id}", [InvoiceController::class, 'destroy_attribute'])->name('invoices.destroy_attribute');

    Route::apiResource("category-attributes", CategoryAttributeController::class);

    Route::delete("requests/destroy-attribute/{id}", [EvaluationController::class, 'destroy_attribute'])->name('requests.destroy_attribute');
});