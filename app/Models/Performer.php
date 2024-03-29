<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Performer extends Model
{
    protected $fillable = ["name", "user_id", "is_hidden"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    use HasFactory;
}
