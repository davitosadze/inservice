<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ReportItem extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;


    protected $fillable = [
        "title",
        "value",
        "report_id",
        "uuid",
        "parent_uuid",
    ];

    public function nested()
    {
        return $this->hasMany(ReportItem::class, "parent_uuid", "uuid");
    }

    public function report()
    {
        return $this->belongsTo(Report::class, "report_id");
    }
}
