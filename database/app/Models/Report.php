<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Report extends Model
{
    use HasFactory;

    protected $casts = [
        "created_at" => "datetime:m/d/Y h:i:s"
    ];

    protected $fillable = [
        "title",
        "uuid",
        "subject_name",
        "subject_address"
    ];

    protected $appends = [
        'uid'
    ];

    public function items()
    {
        return $this->hasMany(ReportItem::class, "report_id")->with("nested.media")->where('parent_uuid', null);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getUidAttribute () {
        return isset($this->attributes['uid']) ? $this->attributes['uid'] : (string) Str::uuid();
    }
}
