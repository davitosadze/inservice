<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    protected $fillable = ["date", "title", "purchaser_id"];
    use HasFactory;
}