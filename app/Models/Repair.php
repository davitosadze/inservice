<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Repair extends Model
{
    protected $fillable = [
        "content",
        "exact_location",
        "job_description",
        "requisites",
        "inventory_number",
        "time",
        "date",
        "purchaser_id",
        "performer_id",
        "region_id",
        "subject_name",
        "subject_address",
        "name",
        "identification_num",
        "status",
        "device_type",
        'on_repair',
        'repair_device_id',
        'from_id',
        'from',
        "user_id",
        "type",
        "standby_mode",
        'estimated_arrival_time',
    ];
    use HasFactory;


protected $appends = ['act_note'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getActNoteAttribute(){ 
        if ($this->from == "response" && $this->from_id) {
            $response = Response::find($this->from_id);
            return $response?->act?->note ?? '';
        } elseif ($this->from == "service" && $this->from_id) {
            $service = Service::find($this->from_id);
            return $service?->act?->note ?? '';
        }
        
        return "";
    }


    public function act()
    {
        return $this->hasOne(RepairAct::class);
    }

    public function purchaser()
    {
        return $this->belongsTo(Purchaser::class, "purchaser_id");
    }

    public function region()
    {
        return $this->belongsTo(Region::class, "region_id");
    }

    public function performer()
    {
        return $this->belongsTo(User::class, "performer_id");
    }

    public function repairDevice()
    {
        return $this->belongsTo(RepairDevice::class, "repair_device_id");
    }

    public function getFormattedNameAttribute()
    {
        return preg_replace('/[^\p{L}]+/u', '', $this->name);
    }

    public function getJobTimeAttribute()
    {
        $diff = Carbon::parse($this->time)->diff($this->updated_at);
        return  $diff->format('%h საათი და %i წუთი');
    }

    /**
     * Get the related Response if from is 'response'
     */
/**
 * Get the related Response if from is 'response'
 */
        public function response(): BelongsTo
        {
            return $this->belongsTo(Response::class, 'from_id');
        }

        public function invoice() {
            return Invoice::where('repair_id', $this->id)->first() ?? null;
        }

        public function report() {
            return Report::where('repair_id', $this->id)
                    ->first() ?? null;
        }

        /**
         * Get the related Service if from is 'service'
         */
        public function service(): BelongsTo
        {
            return $this->belongsTo(Service::class, 'from_id');
        }

        public function chat() {
            return \App\Models\Chat::where('type', 'repair')
                ->where('item_id', $this->id)
                ->first() ?? null;
        }
        /**
         * Get the polymorphic relation based on 'from' type
         */
        public function fromModel()
        {
            return $this->from === 'response' ? $this->response() : $this->service();
        }
}
