<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
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
        "cleaning_time",
        "is_hidden"
    ];

    protected $attributes = [
        "name" => "",
        "subj_name" => "",
        "subj_address" => ""
    ];


    // protected static function boot()
    // {
    //     parent::boot();

    //     static::addGlobalScope('hidden', function (Builder $builder) {
    //         $builder->where('is_hidden', 0);
    //     });
    // }

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
