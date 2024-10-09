<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    protected $fillable = ["name", "parent_id", "user_id"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function children()
    {
        return $this->hasMany(System::class, "parent_id");
    }
    public function responses()
    {
        return $this->hasMany(Response::class, 'system_one'); // Adjust the foreign key if needed
    }


    use HasFactory;
}
