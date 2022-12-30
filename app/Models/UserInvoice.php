<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInvoice extends Model
{
    use HasFactory;

    protected $table = "user_dates";


    protected $fillable = [
        'year',
        'month',
        'user_id',
        'type',
        'inovices_length',
    ];

    // protected $fillable = [
    //     'year',
    //     'month',
    //     'inovices_length',
    // ];
}
