<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = ["name", "user_id", "location"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    use HasFactory;
}
