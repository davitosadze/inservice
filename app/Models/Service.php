<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
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
        'on_repair'
    ];
    use HasFactory;

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
}
