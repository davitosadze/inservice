<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialAttribute extends Model
{
    use HasFactory;

    protected $table = "purchaser_special_attributes";

    protected $fillable = [
        "category_attribute_id",
        "purchaser_id",
        "json"
    ];

    protected $casts = [
        'json' => 'object'
    ];

    // protected $attributes = [
    //     "pivot" => [],
    //     "name" => "",
    //     "price" => "",
    //     "service_price" => "",
    //     "nested" => []
    // ];
}
