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

    public function repair(): BelongsTo
    {
        return $this->belongsTo(Repair::class, 'item_id', 'id');
    }

    public function response(): BelongsTo
    {
        return $this->belongsTo(Response::class, 'item_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}