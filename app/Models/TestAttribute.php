<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestAttribute extends Model
{
    use HasFactory;

    protected $table = "attributes";

    protected $fillable = [];

    protected $guarded = [];

    protected $attributes = [];


    public function nested()
    {
        return $this->hasMany(TestAttribute::class, "pid", "id")->with("nested");
    }
}
