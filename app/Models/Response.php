<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
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
        "system_two"
    ];

    use HasFactory;


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
        return $this->belongsTo(Performer::class, "performer_id");
    }

    public function systemOne()
    {
        return $this->belongsTo(System::class, "system_one");
    }

    public function systemTwo()
    {
        return $this->belongsTo(System::class, "system_two");
    }
}
