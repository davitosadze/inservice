<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Invoice extends Model
{
    use HasFactory;

    protected $table = "evaluations";

    protected $casts = [
        "created_at" => "datetime:m/d/Y h:i:s"
    ];

    protected $fillable = [
        "p1",
        "p2",
        "p3",
        "p4",
        "p5",
        "uuid",
        "parent_uuid",
        "warranty_period",
        "title"
    ];

    protected $attributes = [
        "p1" => '',
        "p2" => '',
        "p3" => '',
        "p4" => '',
        "p5" => '',
        "type" => 'invoice'
    ];

    protected $appends = [
        'special',
        'purchaser',
        'category_attributes',
        'full_price'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function purchaser()
    {
        return $this->belongsTo(Purchaser::class);
    }

    public function getPurchaserAttribute()
    {
        return $this->purchaser()->first() ?? null;
    }

    public function getFullPriceAttribute()
    {
        return number_format(DB::table('attributables')
            ->where('attributable_id', $this->id)
            ->sum('calc'), 2) . " ლარი";
    }

    public function category_attributes()
    {
        return $this->morphToMany(CategoryAttribute::class, "attributable")->withPivot('id')
            ->withPivot('id')
            ->withPivot('title')
            ->withPivot('qty')
            ->withPivot('price')
            ->withPivot('service_price')
            ->withPivot('is_special')
            ->withPivot('calc')
            ->withPivot('evaluation_price')
            ->withPivot('evaluation_calc')
            ->withPivot('evaluation_service_price')->orderBy('sort');
    }

    public function getCategoryAttributesAttribute()
    {
        return [];
    }

    public function parent()
    {
        return $this->belongsTo(Evaluation::class, 'parent_uuid', 'uuid');
    }

    public function getSpecialAttribute()
    {
        return isset($this->purchaser) && isset($this->purchaser->specialAttributes) ? $this->purchaser->specialAttributes : [];
    }
}
