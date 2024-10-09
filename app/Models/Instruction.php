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
        "description",
        "parent_id"
    ];
    use HasFactory, InteractsWithMedia;

    public function parent()
    {
        return $this->belongsTo(Instruction::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Instruction::class, 'parent_id');
    }
}
