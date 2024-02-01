<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Purchaser extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        "name",
        "subj_name",
        "subj_address",
        "identification_num",
        "description"
    ];

    protected $attributes = [
        "name" => "",
        "subj_name" => "",
        "subj_address" => ""
    ];


    public function specialAttributes()
    {
        return $this->hasMany(SpecialAttribute::class);
    }
}
