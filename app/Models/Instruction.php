<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Instruction extends Model implements HasMedia
{
    protected $fillable = [
        'name',
        'slug'
    ];
    use HasFactory, InteractsWithMedia;
}
