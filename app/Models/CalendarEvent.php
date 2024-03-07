<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    protected $fillable = ["date", "title", "purchaser_id", "content", "reason", "response_id"];

    public function response()
    {
        return $this->belongsTo(Response::class, 'response_id');
    }
    use HasFactory;
}
