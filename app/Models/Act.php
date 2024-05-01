<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Act extends Model
{
    use HasFactory;
    protected $fillable = [
        'location_id',
        'device_type_id',
        'device_brand_id',
        'response_id',
        'device_model',
        'inventory_code',
        'note',
        'client_name',
        'position',
        'additional_information',
        'signature',
        'uuid',
        'location_text',
        'device_type_text',
        'device_brand_text'
    ];

    protected $attributes = [
        "location_id" => "",
        "device_type_id" => "",
        "response_id" => "",
        "device_type_id" => "",
        "device_brand_id" => "",
        "device_model" => "",
        "inventory_code" => "",
        "note" => "",
        "client_name" => "",
        "position" => "",
        "additional_information" => "",
        "location_text" => "",
        "device_type_text" => "",
        "device_brand_text" => "",
    ];


    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function deviceType()
    {
        return $this->belongsTo(DeviceType::class);
    }

    public function deviceBrand()
    {
        return $this->belongsTo(DeviceBrand::class);
    }

    public function response()
    {
        return $this->belongsTo(Response::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
