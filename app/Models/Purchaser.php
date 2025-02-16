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
        "description",
        "technical_time",
        "cleaning_time"
    ];

    protected $attributes = [
        "name" => "",
        "subj_name" => "",
        "subj_address" => ""
    ];

    protected $appends = ["formatted_name"];

    public function getFormattedNameAttribute()
    {
        return preg_replace('/[^\p{L}]+/u', '', $this->name);
    }

    public function specialAttributes()
    {
        return $this->hasMany(SpecialAttribute::class);
    }
}
