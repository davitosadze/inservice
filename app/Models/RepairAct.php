<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairAct extends Model
{
    use HasFactory;
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
