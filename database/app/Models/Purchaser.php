<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchaser extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "subj_name",
        "subj_address",
        "identification_num"
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
