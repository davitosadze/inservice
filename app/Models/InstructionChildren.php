<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructionChildren extends Model
{
    use HasFactory;
    protected $table = "instruction_children";
    protected $fillable = [
        "name",
        "description",
        "parent_id"
    ];

    public function parent()
    {
        return $this->belongsTo(Instruction::class);
    }
}
