<?php

namespace App\Models;

use Carbon\Carbon;
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
        "system_two",
        "status",
        "device_type",
        'on_repair',
        "user_id",
        "by_client",
        "end_time",
        "type"
    ];
    protected $appends = ["formatted_name", "job_time"];

    use HasFactory;


    public function user()
    {
        return $this->belongsTo(User::class);
    }



    public function act()
    {
        return $this->hasOne(Act::class);
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

    public function systemOne()
    {
        return $this->belongsTo(System::class, "system_one");
    }

    public function systemTwo()
    {
        return $this->belongsTo(System::class, "system_two");
    }

    public function getFormattedNameAttribute()
    {
        return preg_replace('/[^\p{L}]+/u', '', $this->name);
    }

    public function chat() {
        return Chat::where('type', 'response')
            ->where('item_id', $this->id)
            ->first() ?? null;
    }

    public function getJobTimeAttribute()
    {
        $diff = Carbon::parse($this->time)->diff($this->updated_at);
        return  $diff->format('%h საათი და %i წუთი');
    }
}
