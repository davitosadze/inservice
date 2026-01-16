<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ServiceAct extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Service Act has been {$eventName}");
    }
    protected $fillable = [
        'location_id',
        'service_id',
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
        "service_id" => "",
        "note" => "",
        "client_name" => "",
        "position" => "",
        "additional_information" => "",

    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
