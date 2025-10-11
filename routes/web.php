<?php

use App\Http\Controllers\ActController;
use App\Http\Controllers\Api\ClientStatisticController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PurchaserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SpecialAttributeController;
use App\Http\Controllers\CategoryAttributeController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceBrandController;
use App\Http\Controllers\DeviceTypeController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\InstructionController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\PerformerController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\RepairActController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\RepairDeviceController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\ServiceActController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SystemController;

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



    Route::get("/dashboard", [DashboardController::class, 'index'])->name('dashboard');
    Route::get("/calendar", [CalendarController::class, 'index'])->name('calendar.index');
    Route::get("/calendar/data", [CalendarController::class, 'getCalendarData'])->name('calendar.data');

     Route::get("/profile", [UserController::class, 'profile'])->name('profile');

    Route::resource("users", UserController::class);
     Route::post("users/storeNew", [UserController::class, 'storeNew'])->name('users.storeNew');
     Route::post("users/uploads", [UserController::class, 'upload']);
    Route::get("users/uploads2/{report_item}", [UserController::class, 'upload2']);

    Route::resource("clients", ClientsController::class);

    Route::resource("roles", RoleController::class);
    Route::resource("permissions", PermissionController::class);

    Route::resource("purchasers", PurchaserController::class);
    Route::resource("purchasers.special-attributes", SpecialAttributeController::class);

    Route::post("uploadPurchaserGallery/{purchaser_id}", [PurchaserController::class, 'uploadPurchaserGallery']);
    Route::get("purchaserGallery/{purchaser_id}", [PurchaserController::class, 'purchaserGallery']);
    Route::post("purchaser/{purchaser_id}/files", [PurchaserController::class, 'uploadFiles']);
    Route::get("purchaser/{purchaser_id}/files", [PurchaserController::class, 'purchaserFiles']);
    Route::delete("purchaser/media/{media_id}", [PurchaserController::class, 'deleteFiles']);

    // Calendar
    Route::post("calendar/add-event", [CalendarController::class, 'storeEvent']);
    Route::get("calendar/events/purchaser/{purchaser_id}", [CalendarController::class, 'events']);
    Route::get("calendar/events/{event}", [CalendarController::class, 'getEvent']);
    Route::post("calendar/update-event/{event_id}", [CalendarController::class, 'updateEvent']);
    Route::delete("calendar/events/{event}", [CalendarController::class, 'delete']);

    Route::resource("reports", ReportController::class);
    Route::post("reports/uploads", [ReportController::class, 'upload']);
    Route::get("reports/uploads2/{report_item}", [ReportController::class, 'upload2']);

    // Responses
    Route::resource("responses", ResponseController::class);
    Route::get("responses/{response}/arrived", [ResponseController::class, 'arrived'])->name('responses.arrived');
    Route::post("responses/{response}/assign-manager", [ResponseController::class, 'assignManager'])->name('responses.assign-manager');

 

    // Repairs
    Route::post("repairs/{repair}/assign-performer", [RepairController::class, "assignPerformer"])->name('repairs.assign-performer');
    Route::post("repairs/{repair}/change-mode", [RepairController::class, "changeMode"])->name('repairs.change-mode');
    Route::resource("repairs", RepairController::class);
    Route::get("repairs/{repair}/arrived", [RepairController::class, 'arrived'])->name('repairs.arrived');


    // Services
    Route::resource("services", ServiceController::class);
    Route::get("services/{service}/arrived", [ServiceController::class, 'arrived'])->name('services.arrived');


    // Regions
    Route::resource("regions", RegionController::class);

    // Device Types
    Route::resource("device-types", DeviceTypeController::class);

    // Device Brands
    Route::resource("device-brands", DeviceBrandController::class);

    // Locations
    Route::resource("locations", LocationController::class);

    // Repair Devices
    Route::resource("repair-devices", RepairDeviceController::class);

    // Act
    Route::resource("acts", ActController::class);
    Route::get("acts/{id}/export", [ActController::class, "export"])->name('acts.export');

    // Service Act
    Route::resource("service-acts", ServiceActController::class);
    Route::get("service-acts/{id}/export", [ServiceActController::class, "export"])->name('service-acts.export');


    Route::resource("repair-acts", RepairActController::class);
    Route::get("repair-acts/{id}/export", [RepairActController::class, "export"])->name('repair-acts.export');


    // Performers
    Route::resource("performers", PerformerController::class);

    // Settings
    Route::get("settings", [SettingController::class, 'index'])->name('settings.index');

    // Systems
    Route::resource("systems", SystemController::class);

    // Options
    Route::get("options", [OptionController::class, "index"])->name("options.index");
    Route::post("options", [OptionController::class, "store"])->name("options.store");


    Route::resource("invoices", InvoiceController::class);
    Route::get("invoices/pdf/{id}", [InvoiceController::class, 'pdf'])->name('invoices.pdf');
    Route::get("invoices/excel/{id}", [InvoiceController::class, 'excel'])->name('invoices.excel');
    Route::get("export-invoices-new", [InvoiceController::class, 'exportNew'])->name('invoices.export');

    Route::resource("categories", CategoryController::class);
    Route::resource("categories.category-attributes", CategoryAttributeController::class);

    Route::resource("instructions", InstructionController::class);

    // Chats

    Route::get('/chats', [App\Http\Controllers\Chat\ChatController::class, 'index'])->name('chats.index');
    Route::get('/chats/{type}/{model_id}', [App\Http\Controllers\Chat\ChatController::class, 'startChat'])->name('chats.startChat');
    Route::get('/chats/{id}', [App\Http\Controllers\Chat\ChatController::class, 'show'])->name('chats.show');
    Route::post('/chats/{id}/reply', [App\Http\Controllers\Chat\ChatController::class, 'reply'])->name('chats.reply');

});

// Token-authenticated route for chat PDF (outside auth middleware)
Route::get('/chats/history/{id}/pdf', [App\Http\Controllers\Chat\ChatController::class, 'pdf'])
    ->name('chats.pdf');

require __DIR__ . '/auth.php';
