<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chat extends Model
{
    protected $fillable = ['item_id', 'type', 'user_id', 'status'];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function item()
    {
        switch ($this->type) {
 
            case 'repair':
                return $this->belongsTo(Repair::class, 'item_id', 'id');
            case 'response':
                return $this->belongsTo(Response::class, 'item_id', 'id');
            case 'service':
                return $this->belongsTo(Service::class, 'item_id', 'id');
            default:
                return null;
        }
    }

    public function repair(): BelongsTo
    {
        return $this->belongsTo(Repair::class, 'item_id', 'id');
    }

    public function response(): BelongsTo
    {
        return $this->belongsTo(Response::class, 'item_id', 'id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'item_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}