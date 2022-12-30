<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $table = "attributables";

    public function reservings() {
        return $this->morpedByMany(Resseving::class, 'attributables');
    }
}



