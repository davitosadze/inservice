<?php

use App\Http\Controllers\API\ActController;
use App\Http\Controllers\API\AppStatisticController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\PurchaserController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\InvoiceController;
use App\Http\Controllers\API\EvaluationController;
use App\Http\Controllers\API\SpecialAttributeController;
use App\Http\Controllers\API\CategoryAttributeController;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\ClientsController;
use App\Http\Controllers\API\ClientStatisticController;
use App\Http\Controllers\API\DeviceBrandController;
use App\Http\Controllers\API\DeviceTypeController;
use App\Http\Controllers\API\InstructionController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\API\PerformerController;
use App\Http\Controllers\API\RegionController;
use App\Http\Controllers\API\RepairActController;
use App\Http\Controllers\API\RepairController;
use App\Http\Controllers\API\RepairDeviceController;
use App\Http\Controllers\API\ResponseController;
use App\Http\Controllers\API\ServiceActController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\StatisticController;
use App\Http\Controllers\API\SystemController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\APP\ExpoNotificationController;
use App\Http\Controllers\APP\MediaController;
use App\Http\Controllers\APP\RepairController as APPRepairController;
use App\Http\Controllers\APP\ResponseController as APPResponseController;
use App\Http\Controllers\APP\ServiceController as APPServiceController;
use App\Http\Controllers\APP\UserController as APPUserController;
use App\Http\Controllers\FolderController;

Route::post("app/statistics", [AppStatisticController::class, 'index']);
Route::get('app/purchaser-names', [PurchaserController::class, "purchaserNames"]);
Route::post('app/upload-media', [MediaController::class, "uploadMedia"]);
Route::post("app/clients/register", [ClientsController::class, 'registerClient']);

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
    Route::get("clients/me", [ClientStatisticController::class, 'me']);
    Route::post("clients/statistics", [ClientStatisticController::class, 'index']);
    Route::apiResource("clients", ClientsController::class);
    Route::post("clients/assign-users/{client}", [ClientsController::class, 'assignUsers']);

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
    Route::apiResource('repair-devices', RepairDeviceController::class);

    // Instructions
    Route::post('/instructions', [InstructionController::class, 'storeOrUpdate']);
    Route::put('/instructions/{id}', [InstructionController::class, 'storeOrUpdate']);



    // Performers
    Route::apiResource('performers', PerformerController::class);

    // Responses
    Route::get('responses/export', [ResponseController::class, "export"])->name("responses.export");
    Route::apiResource('responses', ResponseController::class);

    // Repairs
    Route::get('repairs/export', [RepairController::class, "export"])->name("repairs.export");
    Route::apiResource('repairs', RepairController::class);

    // Services
    Route::get('services/export', [ServiceController::class, "export"])->name("services.export");
    Route::apiResource('services', ServiceController::class);

    // Systems
    Route::apiResource('systems', SystemController::class);
    Route::get('systems/{id}/children', [SystemController::class, 'children']);

    // Acts
    Route::apiResource('acts', ActController::class);
    Route::post("acts/{act}/reject", [ActController::class, "reject"]);
    Route::post("acts/{id}/change-status", [ActController::class, "changeStatusNew"]);

    // Service Acts
    Route::apiResource('service-acts', ServiceActController::class);
    Route::post("service-acts/{act}/reject", [ServiceActController::class, "reject"]);
    Route::post("service-acts/{id}/change-status", [ServiceActController::class, "changeStatus"]);

    // Repair Acts
    Route::apiResource('repair-acts', RepairActController::class);
    Route::post("repair-acts/{act}/reject", [RepairActController::class, "reject"]);
    Route::post("repair-acts/{id}/change-status", [RepairActController::class, "changeStatusNew"]);


    Route::apiResource("evaluations", EvaluationController::class);

    Route::apiResource("categories", CategoryController::class);
    Route::resource("invoices", InvoiceController::class);
    Route::delete("invoices/destroy-attribute/{id}", [InvoiceController::class, 'destroy_attribute'])->name('invoices.destroy_attribute');

    Route::apiResource("category-attributes", CategoryAttributeController::class);

    Route::delete("requests/destroy-attribute/{id}", [EvaluationController::class, 'destroy_attribute'])->name('requests.destroy_attribute');

    // Application
    Route::get('app/responses/{response} ', [APPResponseController::class, 'show'])->name('app.responses.show');
    Route::post('app/responses/{response}/attend ', [APPResponseController::class, 'arrived'])->name('app.acts.arrived');
    Route::get('app/responses ', [APPResponseController::class, 'index'])->name('app.responses.index');
    Route::get('app/responses-done', [APPResponseController::class, 'doneResponses'])->name('app.responses.done');
    Route::post('app/responses ', [APPResponseController::class, 'store'])->name('app.responses.store');
    Route::post('app/responses/{response}/change-status ', [APPResponseController::class, 'changeStatus'])->name('app.responses.changeStatus');
    Route::post('app/services/{service}/attend ', [APPServiceController::class, 'arrived'])->name('app.services.arrived');

 
    // App Repairs
    Route::post('app/repairs/{repair}/attend ', [APPRepairController::class, 'arrived'])->name('app.repair-acts.arrived');
    Route::post('app/repairs ', [APPRepairController::class, 'store'])->name('app.responses.store');
    Route::post('app/repairs ', [APPRepairController::class, 'store'])->name('app.responses.store');
    Route::get('app/repairs ', [APPRepairController::class, 'index'])->name('app.repairs.index');
     Route::get('app/repairs-done', [APPRepairController::class, 'doneRepairs'])->name('app.repairs.done');
    Route::get('app/repairs/{repair} ', [APPRepairController::class, 'show'])->name('app.repairs.show');

    Route::post('app/repairs/{repair}/change-status ', [APPRepairController::class, 'changeStatus'])->name('app.repairs.changeStatus');

});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/chat/start', [ChatController::class, 'startChat']);
    Route::post('/chat/message', [ChatController::class, 'sendMessage']);
    Route::get('/chat/{chatId}/messages', [ChatController::class, 'getMessages']);
});
