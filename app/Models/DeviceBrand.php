<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceBrand extends Model
{
    protected $fillable = ["name", "not_visible"];
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
