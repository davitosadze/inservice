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
        "single",
        "first_review_date",
        "technical_review_date",
        "base_creation_date",
        "first_review_description",
        "technical_review_description",
        "base_description",
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

    public function evaluations() {
        return $this->hasMany(Evaluation::class, "purchaser_id");
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }


    public function responses()
    {
        return $this->hasMany(Response::class);
    }
 
    public function specialAttributes()
    {
        return $this->hasMany(SpecialAttribute::class);
    }
}
