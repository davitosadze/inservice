<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "type",
        "description"
    ];

    protected $attributes = [
        "name" => "",
        "type" => "",
        "description" => ""
    ];

    public function attributes()
    {
        return $this->hasMany(CategoryAttribute::class, "category_id")->with("nested");
    }

    public function category_attributes()
    {
        return $this->attributes()->where('parent_uuid', null);
    }
}
