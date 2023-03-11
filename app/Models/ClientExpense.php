<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ClientExpense extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $fillable = [
        "amount",
        "client_id",
        "uuid",
    ];

    protected $hidden = ["client_id", "created_at", "updated_at"];
    protected $attributes = [
        "amount" => 0
    ];
}
