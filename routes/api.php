<?php

use App\Http\Controllers\API\ActController;
use App\Http\Controllers\API\AppStatisticController;
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
use App\Http\Controllers\API\DeviceBrandController;
use App\Http\Controllers\API\DeviceTypeController;
use App\Http\Controllers\API\InstructionController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\API\PerformerController;
use App\Http\Controllers\API\RegionController;
use App\Http\Controllers\API\ResponseController;
use App\Http\Controllers\API\ServiceActController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\StatisticController;
use App\Http\Controllers\API\SystemController;
use App\Http\Controllers\APP\ExpoNotificationController;
use App\Http\Controllers\APP\MediaController;
use App\Http\Controllers\APP\ResponseController as APPResponseController;
use App\Http\Controllers\APP\ServiceController as APPServiceController;
use App\Http\Controllers\APP\UserController as APPUserController;
use App\Http\Controllers\FolderController;

Route::get("app/statistics", [AppStatisticController::class, 'index']);
Route::get('app/purchaser-names', [PurchaserController::class, "purchaserNames"]);
Route::post('app/upload-media', [MediaController::class, "uploadMedia"]);

Route::get('/instructions', [InstructionController::class, 'index']);
Route::get('/instructions/{id}', [InstructionController::class, 'show']);

Route::group(['prefix' => 'app', 'as' => 'app.'], function () {
    Route::post('login',  [APPUserController::class, 'login']);
});


Route::middleware(['auth:sanctum'])->name('api.')->group(function () {

    // Folders
    Route::get('folders/{id}', [FolderController::class, 'getFolders']);

    // Delete File
    Route::post('/delete-file', [FolderController::class, 'deleteFile']);


    // APP
    Route::post('app/notifications/subscribe', [ExpoNotificationController::class, "subscribe"]);


    Route::apiResource("users", UserController::class);

    Route::get("statistics", [StatisticController::class, 'statistics']);

    Route::apiResource("purchasers", PurchaserController::class);
    Route::apiResource("purchasers.special-attributes", SpecialAttributeController::class);

    // Clients
    Route::apiResource("clients", ClientsController::class);

    Route::post("uploadClientFiles", [ClientsController::class, 'uploadClientFiles']);
    Route::delete("deleteClientFiles/{media_id}", [ClientsController::class, 'deleteClientFiles']);
    Route::post("updateClientFiles/{media_id}", [ClientsController::class, 'updateClientFiles']);

    Route::post("addClientExpense/{client_id}", [ClientsController::class, 'addClientExpense']);
    Route::delete("deleteClientExpense/{expense_id}", [ClientsController::class, 'deleteClientExpense']);

    // Regions
    Route::apiResource('regions', RegionController::class);

    // Device Types
    Route::apiResource('device-types', DeviceTypeController::class);

    // Device Brands
    Route::apiResource('device-brands', DeviceBrandController::class);

    // Locations
    Route::apiResource('locations', LocationController::class);

    // Instructions
    Route::post('/instructions', [InstructionController::class, 'storeOrUpdate']);
    Route::put('/instructions/{id}', [InstructionController::class, 'storeOrUpdate']);



    // Performers
    Route::apiResource('performers', PerformerController::class);

    // Responses
    Route::get('responses/export', [ResponseController::class, "export"])->name("responses.export");
    Route::apiResource('responses', ResponseController::class);

    // Services
    Route::get('services/export', [ServiceController::class, "export"])->name("services.export");
    Route::apiResource('services', ServiceController::class);

    // Systems
    Route::apiResource('systems', SystemController::class);
    Route::get('systems/{id}/children', [SystemController::class, 'children']);

    // Acts
    Route::apiResource('acts', ActController::class);
    Route::post("acts/{act}/reject", [ActController::class, "reject"]);
    Route::post("acts/{id}/change-status", [ActController::class, "chengeStatus"]);

    // Service Acts
    Route::apiResource('service-acts', ServiceActController::class);
    Route::post("service-acts/{act}/reject", [ServiceActController::class, "reject"]);
    Route::post("service-acts/{id}/change-status", [ServiceActController::class, "changeStatus"]);


    Route::apiResource("evaluations", EvaluationController::class);

    Route::apiResource("categories", CategoryController::class);
    Route::resource("invoices", InvoiceController::class);
    Route::delete("invoices/destroy-attribute/{id}", [InvoiceController::class, 'destroy_attribute'])->name('invoices.destroy_attribute');

    Route::apiResource("category-attributes", CategoryAttributeController::class);

    Route::delete("requests/destroy-attribute/{id}", [EvaluationController::class, 'destroy_attribute'])->name('requests.destroy_attribute');

    // Application
    Route::post('app/responses/{response}/attend ', [APPResponseController::class, 'arrived'])->name('app.acts.arrived');
    Route::post('app/services/{service}/attend ', [APPServiceController::class, 'arrived'])->name('app.services.arrived');
});
