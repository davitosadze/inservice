<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Service extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Service has been {$eventName}");
    }

    protected $fillable = [
        "content",
        "exact_location",
        "job_description",
        "requisites",
        "inventory_number",
        "time",
        "date",
        "purchaser_id",
        "performer_id",
        "region_id",
        "subject_name",
        "subject_address",
        "name",
        "identification_num",
        "system_one",
        "system_two",
        "status",
        "device_type",
        'on_repair',
        'estimated_arrival_time'
    ];

    protected $appends = ["formatted_name", "job_time"];

    public function act()
    {
        return $this->hasOne(ServiceAct::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchaser()
    {
        return $this->belongsTo(Purchaser::class, "purchaser_id");
    }

    public function region()
    {
        return $this->belongsTo(Region::class, "region_id");
    }

    public function performer()
    {
        return $this->belongsTo(User::class, "performer_id");
    }
    public function chat() {
        return Chat::where('type', 'service')
            ->where('item_id', $this->id)
            ->first() ?? null;
    }
    public function getFormattedNameAttribute()
    {
        return preg_replace('/[^\p{L}]+/u', '', $this->name);
    }

    public function getJobTimeAttribute()
    {
        $diff = Carbon::parse($this->time)->diff($this->updated_at);
        return  $diff->format('%h საათი და %i წუთი');
    }
}
