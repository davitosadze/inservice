<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chat extends Model
{
    protected $fillable = ['response_id', 'user_id', 'status'];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function response(): BelongsTo
    {
        return $this->belongsTo(Response::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}