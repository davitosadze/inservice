<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RepairAct extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Repair Act has been {$eventName}");
    }
    protected $fillable = [
        'location_id',
        'device_type_id',
        'device_brand_id',
        'repair_id',
        'device_model',
        'inventory_code',
        'note',
        'client_name',
        'position',
        'additional_information',
        'signature',
        'uuid',
        'is_mobile',


    ];

    protected $attributes = [
        "location_id" => "",
        "repair_id" => "",
        "device_type_id" => "",
        "device_brand_id" => "",
        "device_model" => "",
        "inventory_code" => "",
        "note" => "",
        "client_name" => "",
        "position" => "",
        "additional_information" => "",

    ];


    public function deviceType()
    {
        return $this->belongsTo(RepairDevice::class);
    }

    public function deviceBrand()
    {
        return $this->belongsTo(DeviceBrand::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function repair()
    {
        return $this->belongsTo(Repair::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
