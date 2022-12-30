<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        "uuid",
        "name",
        "price",
        "item",
        'category_id',
        "parent_uuid",
        "service_price",
        "category_type"
    ];

    protected $guarded = [
        "pivot",
        "nested"
    ];

    protected $attributes = [
        // "pivot" => [],
        "name" => "",
        "price" => "",
        "service_price" => "",
        // "nested" => []
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, "category_id");
    }

    public function main()
    {
        return $this->belongsTo(CategoryAttribute::class, "parent_uuid", "uuid")->with("main");
    }

    public function nested()
    {
        return $this->hasMany(CategoryAttribute::class, "parent_uuid", "uuid")->with("nested")->with("category");
    }

    // public function main()
    // {
    //     return $this->belongsTo(CategoryAttribute::class, "parent_id")->where("parent_id", 0)->with("main");
    // }

    // public function nested()
    // {
    //     return $this->hasMany(CategoryAttribute::class, "parent_id")->with("nested");
    // }
}
