<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Client extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $fillable = [
        "client_name",
        "identification_code",
        "client_identification_code",
        "legal_address",
        "service_name",
        "contract_service_type",
        "contract_start_date",
        "contract_end_date",
        "contract_period",
        "contract_status",
        "contact_name",
        "contact_number",
        "total"
    ];

    protected $attributes = [
        "client_name" => "",
        "identification_code" => "",
        "client_identification_code" => "",
        "legal_address" => "",
        "service_name" => "",
        "contract_service_type" => "",
        "contract_start_date" => "",
        "contract_end_date" => "",
        "contract_period" => "",
        "contact_name" => "",
        "contact_number" => "",
        "total" => "",
    ];


    protected $appends = [
        'expenses',
        'contract_status',
        "total_files",
        "additional_files",
    ];

    public function getContractStatusAttribute()
    {
        if (Carbon::now() > $this->contract_end_date) {
            return "დასრულებული";
        }

        return "აქტიური";
    }
    public function expenses()
    {
        return $this->hasMany(ClientExpense::class);
    }

    public function getTotalFilesAttribute()
    {
        return $this->getMedia('client_total_files');
    }

    public function getAdditionalFilesAttribute()
    {
        return $this->getMedia('client_additional_files');
    }

    public function getExpensesAttribute()
    {
        return [];
    }

    protected $casts = [
        "created_at" => "datetime:m/d/Y h:i:s"
    ];
}
