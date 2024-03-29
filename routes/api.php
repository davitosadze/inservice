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
use App\Http\Controllers\API\ClientsController;
use App\Http\Controllers\API\PerformerController;
use App\Http\Controllers\API\RegionController;
use App\Http\Controllers\API\ResponseController;
use App\Http\Controllers\API\StatisticController;
use App\Http\Controllers\API\SystemController;
use App\Http\Controllers\DashboardController;

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

    Route::get("statistics", [StatisticController::class, 'statistics']);

    Route::apiResource("purchasers", PurchaserController::class);
    Route::apiResource("purchasers.special-attributes", SpecialAttributeController::class);

    Route::apiResource("clients", ClientsController::class);
    Route::post("uploadClientFiles", [ClientsController::class, 'uploadClientFiles']);
    Route::delete("deleteClientFiles/{media_id}", [ClientsController::class, 'deleteClientFiles']);
    Route::post("updateClientFiles/{media_id}", [ClientsController::class, 'updateClientFiles']);

    Route::post("addClientExpense/{client_id}", [ClientsController::class, 'addClientExpense']);
    Route::delete("deleteClientExpense/{expense_id}", [ClientsController::class, 'deleteClientExpense']);

    // Regions
    Route::apiResource('regions', RegionController::class);

    // Performers
    Route::apiResource('performers', PerformerController::class);

    // Responses
    Route::get('responses/export', [ResponseController::class, "export"])->name("responses.export");
    Route::apiResource('responses', ResponseController::class);

    // Systems
    Route::apiResource('systems', SystemController::class);
    Route::get('systems/{id}/children', [SystemController::class, 'children']);

    Route::apiResource("evaluations", EvaluationController::class);

    Route::apiResource("categories", CategoryController::class);
    Route::resource("invoices", InvoiceController::class);
    Route::delete("invoices/destroy-attribute/{id}", [InvoiceController::class, 'destroy_attribute'])->name('invoices.destroy_attribute');

    Route::apiResource("category-attributes", CategoryAttributeController::class);

    Route::delete("requests/destroy-attribute/{id}", [EvaluationController::class, 'destroy_attribute'])->name('requests.destroy_attribute');
});
